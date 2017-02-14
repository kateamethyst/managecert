<?php

/**
 * This migration is intended to be used only if the database is in default state or schema is newly imported
 */
class blMigration
{
    private $oUpload;
    private $oStorage;
    private $oBlCertificate;
    private $oBlReception;
    private $oBlApplication;
    private $oBlDepartment;

    public function __construct($oUpload, $oStorage)
    {
        $this->oUpload = $oUpload;
        $this->oStorage = $oStorage;

        $oBlInstance = new libBlInstance();

        //certificate checking
        $this->oBlCertificate = $oBlInstance->getBlCertificate();
        $this->oBlDepartment = $oBlInstance->getBlDepartment();
        $this->oBlReception = $oBlInstance->getBlReception();
        $this->oBlApplication = $oBlInstance->getBlApplication();
    }

    public function migrateFile($aArgs)
    {
        $oLibExcel = libExcel::getInstance();
        $aFile = $this->oUpload->uploadedFiles();

        $sTempDir = 'migration/';
        $this->makeUploadDir($sTempDir);

        foreach ($aFile as $sKey => $aValue) {

            $aFileInfo = pathinfo($aValue['filename']);
        }

        $sFile =  'migration.' . $aFileInfo['extension'];
        $aConstants = get_defined_constants(true);

        if(libUtil::isLocal() === true) {
            $sStorageHost = $aConstants['user']['SDK_SIMULATOR_STORAGE']; //for local
            $sSource = $sStorageHost . DIRECTORY_SEPARATOR . $aArgs['sAppId'] . DIRECTORY_SEPARATOR . $sFile; //for local env
        } else {
            $sStorageHost = $aConstants['user']['APP_STORAGE_HOST']; //for dev
            $sStorageWebPort = $aConstants['user']['APP_STORAGE_WEBPORT'];
            $sSource = "http://" . $sStorageHost . ':' . $sStorageWebPort . DIRECTORY_SEPARATOR . $aArgs['sAppId'] . DIRECTORY_SEPARATOR . $aArgs['sDomain'] . DIRECTORY_SEPARATOR . str_replace('public_files/', '', $sFile); //for dev env
        }
        $this->oUpload->moveUploadedFile($aFile['migration_file']['tmpname'], $sTempDir, $sFile);

        $aData = $oLibExcel->getExcel($sSource);
        array_shift($aData);
        $aCertificate = array_unique(libUtil::arrayColumn($aData, '0'));
        $iCountCertificate = count($aCertificate);

        if($iCountCertificate === 0) {
            return false;
        }

        $aAllCertificate = $this->getAllCert();
        $aCertificateFromDb = $aAllCertificate['database'];
        $aCertificateNames = $aAllCertificate['names'];
        $aCertInsertion = array();

        foreach ($aCertificate as $sValue) {
            $mSearch = array_search($sValue, $aCertificateNames);

            if($mSearch === false) {
                $aCertInsertion[] = $this->oBlCertificate->addCertificate(
                    array(
                        'name' => $sValue
                    )
                );
            }
        }

        $aCertificateFromDb = $this->getAllCert();
        $aCertificateNames = $aCertificateFromDb['names'];
        $aCertificateFromDb = $aCertificateFromDb['database'];
        $aDepartment = $this->getAllDepartment();
        $aDepartmentCode = $aDepartment['codes'];
        $aDepartmentDetails = $aDepartment['details'];
        $aReceptions = array();

        foreach ($aData as $aValue) {
            $aReceptions[] = array(
                'seq_no' => '',
                'certificate_id' => array_search($aValue[0], $aCertificateNames),
                'name' => $aValue[1],
                'status' => '종료', //means that the reception has ended, change this if value is not applicable
                'department_id' => array_search($aValue[2], $aDepartmentCode),
                'exam_category' => $aValue[3], 
                'exam_duration' => $aValue[4],
                'exam_location' => '',
                'user_id' => $aValue[18],
                'is_deleted' => 'T',
            );
        }

        $aUniqueReceptions = array_values(array_map("unserialize", array_unique(array_map("serialize", $aReceptions))));
        
        //this will remove the conflict in data or existing reception in the database
        //e.g. multiple reception names with different agency and education type and test times.
        //the first entry for the reception will be the one saved
        //remove this if you want to save the contradicting data
        //saving the contradicting data will cause to have duplicate reception names in the database
        $aReceptions = $this->getAllReception();
        $aReceptionNames = $aReceptions['names'];
        $aReceptionsToInsert = array();
        
        foreach ($aUniqueReceptions as $iKey => $aValue) {
            if(in_array($aValue['name'], $aReceptionNames) === false) {
                $aReceptionsToInsert[] = $aValue;
                $aReceptionNames[] = $aValue['name'];
            }
        }

        $aReceptionsInsertion = array();

        foreach ($aReceptionsToInsert as $aValue) {
            $aReceptionsInsertion[] = $this->oBlReception->saveReception($aValue);
        }

        $aReceptions = $this->getAllReception();
        $aReceptionNames = $aReceptions['names'];
        $aReceptionDetails = $aReceptions['details'];
        $aApplicant = array();
        $aApplicationStatus = libConfig::applicationStatus();

        $aIds = array();

        foreach ($aData as $aValue) {
            $aCell = explode('-', $aValue[9] ?: '010-0000-0000');
            $aApplicant = array(
                'reception_id' => $aReceptionDetails[array_search($aValue[1], $aReceptionNames)]['seq_no'],
                'status' => $aApplicationStatus[20],
                'email' => $aValue[10],
                'user_id' => $aValue[18],
                'korean_name' => $aValue[6],
                'english_name' => $aValue[7],
                'birthday' => $aValue[8],
                'number_item1' => $aCell[0],
                'number_item2' => $aCell[1],
                'number_item3' => $aCell[2],
                'date_registered' => $aValue[11],
                'result' => $aValue[14],
                'certificate_number' => $aValue[15],
                'issued_date' => $aValue[16]
            );

            if(libValid::isNumeric($aValue[12]) === true) {
                $aApplicant['written_score'] = floatval($aValue[12]);
            }

            if(libValid::isNumeric($aValue[13]) === true) {
                $aApplicant['practical_score'] = floatval($aValue[13]);
            }

            $aIds[] = $this->oBlApplication->insertCustomedApplicant($aApplicant);
        }
        //ids of inserted application
        return $aIds;
    }

    private function getAllReception()
    {
        $aReceptions = $this->oBlReception->getAllReception();
        $aReceptions = $aReceptions['result'] ?: array();
        $aReceptionDetails = array();
        $aReceptionNames = array();

        foreach ($aReceptions as $iKey => $aValue) {
            $aReceptionDetails[$iKey] = $aValue;
            $aReceptionNames[$iKey] = $aValue['name'];
        }

        return array(
            'details' => $aReceptionDetails,
            'names' => $aReceptionNames
        );

    }

    private function getAllDepartment()
    {
        $aDepartment = $this->oBlDepartment->getDepartment();
        $aDepartment = $aDepartment['result'] ?: array();
        $aDepartmenDetails = array();
        $aDepartmentCode = array();

        foreach ($aDepartment as $iKey => $aValue) {
            $aDepartmenDetails[$iKey] = $aValue[0];
            $aDepartmentCode[$iKey] = $aValue[0]['department_code'];
        }

        return array(
            'details' => $aDepartmenDetails,
            'codes' => $aDepartmentCode
        );
    }

    private function getAllCert()
    {
        $aDbCertificate = $this->oBlCertificate->getCertificate();
        $aDbCertificate = $aDbCertificate['result'];
        $aCertificateFromDb = array();
        $aCertificateTemp = array();
        
        foreach ($aDbCertificate as $mKey => $aValue) {
            $aCertificateFromDb[$mKey] = $aValue[0];
            $aCertificateTemp[$mKey] = $aValue[0]['name'];
        }

        return array(
            'database' => $aCertificateFromDb,
            'names' => $aCertificateTemp
        );
    }


    /**
     * make upload directory
     * @param $sPath
     */
    private function makeUploadDir($sPath)
    {
        if ($this->oStorage->is_dir($sPath) !== true) {
            $this->oStorage->mkdir($sPath, true);
        }
    }
}

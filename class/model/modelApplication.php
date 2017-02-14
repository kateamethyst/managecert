<?php

class modelApplication extends modelCommon
{
    /**
     * get filtered list of applications for admin
     * @param  [type] $aParams [description]
     * @return [type]          [description]
     */
    public function getAdminFilteredApplication($aParams)
    {
        $this->getDBConnection();
        $aSqlParams = array(
            'is_deleted' => $aParams['is_deleted'] ?: 'F',
            'key'        => libDatabaseConfig::KEY
        );

        $oStatement = libQueryBuilder::instance()
            ->select(
                'SQL_CALC_FOUND_ROWS '
                . libDatabaseConfig::APPLICATION_TABLE . '.seq_no, '
                . 'AES_DECRYPT(UNHEX(' .libDatabaseConfig::APPLICATION_TABLE . '.applicant_name_kr), :key) as applicant_name_kr,'
                . libDatabaseConfig::APPLICATION_TABLE . '.applicant_id, '
                . libDatabaseConfig::APPLICATION_TABLE . '.ins_timestamp, '
                . libDatabaseConfig::APPLICATION_TABLE . '.written_exam_score, '
                . libDatabaseConfig::APPLICATION_TABLE . '.practical_test_score, '
                . libDatabaseConfig::APPLICATION_TABLE.  '.condition_status, '
                . libDatabaseConfig::APPLICATION_TABLE . '.issued_date, '
                . libDatabaseConfig::APPLICATION_TABLE . '.supplier_id AS supplier_id, '
                . libDatabaseConfig::APPLICATION_TABLE . '.test_result, '
                . libDatabaseConfig::RECEPTION_TABLE . '.name AS reception, '
                . libDatabaseConfig::CERTFICATE_TABLE . '.name AS certificate '
            )
            ->from(libDatabaseConfig::APPLICATION_TABLE)
            ->innerJoin(libDatabaseConfig::RECEPTION_TABLE, libDatabaseConfig::APPLICATION_TABLE . '.ixnn_reception_seq_no', libDatabaseConfig::RECEPTION_TABLE . '.seq_no')
            ->innerJoin(libDatabaseConfig::CERTFICATE_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', libDatabaseConfig::CERTFICATE_TABLE . '.seq_no')
            ->where(libDatabaseConfig::APPLICATION_TABLE . '.is_deleted', ':is_deleted');

        if (isset($aParams['ixnn_reception_seq_no']) === true && $aParams['ixnn_reception_seq_no'] > 0) {
            $oStatement->andWhere(libDatabaseConfig::APPLICATION_TABLE . '.ixnn_reception_seq_no', ':ixnn_reception_seq_no');
            $aSqlParams['ixnn_reception_seq_no'] = $aParams['ixnn_reception_seq_no'];
        }

        if (isset($aParams['ixnn_certificate_seq_no']) === true && $aParams['ixnn_certificate_seq_no'] > 0) {
            $oStatement->andWhere(libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', ':ixnn_certificate_seq_no');
            $aSqlParams['ixnn_certificate_seq_no'] = $aParams['ixnn_certificate_seq_no'];
        }

        if (isset($aParams['supplier_id']) === true) {
            $oStatement->andWhere(libDatabaseConfig::APPLICATION_TABLE . '.supplier_id', ':supplier_id');
            $aSqlParams['supplier_id'] = $aParams['supplier_id'];
        }

        //Date
        if (isset($aParams['start_date']) === true && $aParams['start_date'] !== '') {
            $oStatement->andBetween(libDatabaseConfig::APPLICATION_TABLE . '.ins_timestamp', array('start_date' => ':start_date', 'end_date' => ':end_date'));
            $aSqlParams['start_date'] = $aParams['start_date'];
            $aSqlParams['end_date'] = $aParams['end_date'];
        }

        //Condition Status
        if (libValid::isNull($aParams['condition_status']) === false && libValid::isArray($aParams['condition_status']) === true) {
            $oStatement->andWhereIn(libDatabaseConfig::APPLICATION_TABLE . '.condition_status', $aParams['condition_status']);
        }

        $oStatement->andWhere('t_application.ixnn_reception_seq_no', 't_reception.seq_no')
            ->andWhere('t_reception.ixnn_certificate_seq_no', 't_certificate.seq_no');

        if (isset($aParams['sort']) === true) {
            $oStatement->orderBy(libDatabaseConfig::APPLICATION_TABLE . '.test_result', $aParams['sort']);
        } else {
            $oStatement->orderBy(libDatabaseConfig::APPLICATION_TABLE . '.ins_timestamp', 'D');
        }

        if (isset($aParams['iLimit']) === true && isset($aParams['bPrint']) === false) {
            $oStatement->limit($aParams['iStart'] ?: 0, $aParams['iLimit']);
        }
        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);

        $oQuery->execute($aSqlParams);
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);
        $iTotalCount = $this->getTotalCount();

        $this->closeConnection();

        return array(
            'result'      => $aResult,
            'total_count' => $iTotalCount
        );
    }

    /**
     * get filtered list of application for suppliers and applicants
     * @param  [type] $aParams [description]
     * @return [type]          [description]
     */
    public function getCommonFilteredApplication($aParams)
    {
        $this->getDBConnection();
        $aSqlParams = array(
            'is_deleted' => $aParams['is_deleted'],
            'key'        => libDatabaseConfig::KEY
        );

        $oStatement = libQueryBuilder::instance()
            ->select(
                'SQL_CALC_FOUND_ROWS '
                . libDatabaseConfig::APPLICATION_TABLE . '.seq_no, '
                . 'AES_DECRYPT(UNHEX(' .libDatabaseConfig::APPLICATION_TABLE . '.applicant_name_kr), :key) as applicant_name_kr,'
                . libDatabaseConfig::APPLICATION_TABLE . '.applicant_id, '
                . libDatabaseConfig::APPLICATION_TABLE . '.ins_timestamp, '
                . libDatabaseConfig::APPLICATION_TABLE . '.written_exam_score, '
                . libDatabaseConfig::APPLICATION_TABLE . '.practical_test_score, '
                . libDatabaseConfig::APPLICATION_TABLE.  '.condition_status, '
                . libDatabaseConfig::APPLICATION_TABLE . '.issued_date, '
                . libDatabaseConfig::APPLICATION_TABLE . '.test_result, '
                . libDatabaseConfig::RECEPTION_TABLE . '.name AS reception, '
                . libDatabaseConfig::CERTFICATE_TABLE . '.name AS certificate '
            )
            ->from(libDatabaseConfig::APPLICATION_TABLE)
            ->innerJoin(libDatabaseConfig::RECEPTION_TABLE, libDatabaseConfig::APPLICATION_TABLE . '.ixnn_reception_seq_no', libDatabaseConfig::RECEPTION_TABLE . '.seq_no')
            ->innerJoin(libDatabaseConfig::CERTFICATE_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', libDatabaseConfig::CERTFICATE_TABLE . '.seq_no')
            ->where(libDatabaseConfig::APPLICATION_TABLE . '.is_deleted', ':is_deleted');

        $aSqlParams['supplier_id'] = $aParams['supplier_id'];

        if (isset($aParams['ixnn_reception_seq_no']) === true && $aParams['ixnn_reception_seq_no'] > 0) {
            $oStatement->andWhere(libDatabaseConfig::APPLICATION_TABLE . '.ixnn_reception_seq_no', ':ixnn_reception_seq_no');
            $aSqlParams['ixnn_reception_seq_no'] = $aParams['ixnn_reception_seq_no'];
        }

        if (isset($aParams['ixnn_certificate_seq_no']) === true && $aParams['ixnn_certificate_seq_no'] > 0) {
            $oStatement->andWhere(libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', ':ixnn_certificate_seq_no');
            $aSqlParams['ixnn_certificate_seq_no'] = $aParams['ixnn_certificate_seq_no'];
        }

        if (isset($aParams['supplier_id']) === true && $aParams['supplier_id'] > 0) {
            $oStatement->andWhere(libDatabaseConfig::APPLICATION_TABLE . '.supplier_id', ':supplier_id');
            $aSqlParams['supplier_id'] = $aParams['supplier_id'];
        }

        //Date
        if (isset($aParams['start_date']) === true && $aParams['start_date'] !== '') {
            $oStatement->andBetween(libDatabaseConfig::APPLICATION_TABLE . '.ins_timestamp', array('start_date' => ':start_date', 'end_date' => ':end_date'));
            $aSqlParams['start_date'] = $aParams['start_date'];
            $aSqlParams['end_date'] = $aParams['end_date'];
        }

        //Condition Status
        if (libValid::isNull($aParams['condition_status']) === false && libValid::isArray($aParams['condition_status']) === true) {
            $oStatement->andWhereIn(libDatabaseConfig::APPLICATION_TABLE . '.condition_status', $aParams['condition_status']);
        }

        $oStatement->andWhere('t_application.ixnn_reception_seq_no', 't_reception.seq_no')
            ->andWhere('t_reception.ixnn_certificate_seq_no', 't_certificate.seq_no');

        if (isset($aParams['sort']) === true) {
            $oStatement->orderBy(libDatabaseConfig::APPLICATION_TABLE . '.test_result', $aParams['sort']);
        } else {
            $oStatement->orderBy(libDatabaseConfig::APPLICATION_TABLE . '.ins_timestamp', 'D');
        }

        if (isset($aParams['iLimit']) === true) {
            $oStatement->limit($aParams['iStart'] ?: 0, $aParams['iLimit']);
        }

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute($aSqlParams);
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);
        $iTotalCount = $this->getTotalCount();

        $this->closeConnection();

        return array(
            'result'      => $aResult,
            'total_count' => $iTotalCount
        );
    }

    /**
     * searches application if exist using the receptiom, applicant name and cell number
     * @param  [type]  $aParams [description]
     * @return boolean          [description]
     */
    public function isRegistered($aParams)
    {
        $this->getDBConnection();
        $oStatement = libQueryBuilder::instance()->select()->from(libDatabaseConfig::APPLICATION_TABLE)
            ->where('is_deleted')
            ->andWhere('ixnn_reception_seq_no')
            ->andWhere('applicant_name_kr', 'HEX(AES_ENCRYPT(:applicant_name_kr, :key))')
            ->andWhere('applicant_name_en', 'HEX(AES_ENCRYPT(:applicant_name_en, :key))')
            ->andWhere('applicant_cell', 'HEX(AES_ENCRYPT(:applicant_cell, :key))');

        //Sql parameters
        $aSqlParams = array(
            'is_deleted'            => 'F',
            'ixnn_reception_seq_no' => (int)$aParams['ixnn_reception_seq_no'],
            'applicant_name_kr'     => $aParams['applicant_name_kr'],
            'applicant_name_en'     => $aParams['applicant_name_en'],
            'applicant_cell'        => $aParams['applicant_cell'],
            'key'                   => libDatabaseConfig::KEY
        );

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute($aSqlParams);
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);
        $iTotalCount = $this->getTotalCount();

        $this->closeConnection();

        return array(
            'result'      => $aResult,
            'total_count' => $iTotalCount
        );
    }

    /**
     * get application by id/seq_no
     * @param  [type] $iId [description]
     * @return [type]      [description]
     */
    public function getApplicationById($iId)
    {
        $this->getDBConnection();
        $oStatement = libQueryBuilder::instance()
            ->select(
                libDatabaseConfig::APPLICATION_TABLE . '.seq_no,'
                . 'AES_DECRYPT(UNHEX(' .libDatabaseConfig::APPLICATION_TABLE . '.applicant_name_kr), :key) as applicant_name_kr,'
                . 'AES_DECRYPT(UNHEX(' .libDatabaseConfig::APPLICATION_TABLE . '.applicant_name_en), :key) as applicant_name_en,'
                . 'AES_DECRYPT(UNHEX(' .libDatabaseConfig::APPLICATION_TABLE . '.applicant_cell), :key) as applicant_cell,'
                . 'AES_DECRYPT(UNHEX(' .libDatabaseConfig::APPLICATION_TABLE . '.birthday), :key) as birthday,'
                . libDatabaseConfig::APPLICATION_TABLE . '.email,'
                . libDatabaseConfig::APPLICATION_TABLE . '.condition_status,'
                . libDatabaseConfig::APPLICATION_TABLE . '.written_exam_score,'
                . libDatabaseConfig::APPLICATION_TABLE . '.practical_test_score,'
                . libDatabaseConfig::APPLICATION_TABLE . '.issued_date,'
                . libDatabaseConfig::APPLICATION_TABLE . '.certification_no,'
                . libDatabaseConfig::APPLICATION_TABLE . '.ixnn_reception_seq_no,'
                . libDatabaseConfig::APPLICATION_TABLE . '.supplier_id,'
                . libDatabaseConfig::APPLICATION_TABLE . '.test_result,'
                . libDatabaseConfig::APPLICATION_TABLE . '.ins_timestamp,'
                . libDatabaseConfig::RECEPTION_TABLE . '.education_type, '
                . libDatabaseConfig::RECEPTION_TABLE . '.nth_test, '
                . libDatabaseConfig::RECEPTION_TABLE . '.name AS reception_name, '
                . libDatabaseConfig::RECEPTION_TABLE . '.seq_no AS reception, '
                . libDatabaseConfig::CERTFICATE_TABLE . '.name AS certificate, '
                . libDatabaseConfig::DEPARTMENT_TABLE . '.department_name, '
                . libDatabaseConfig::DEPARTMENT_TABLE . '.department_code '
            )
            ->from(libDatabaseConfig::APPLICATION_TABLE)
            ->innerJoin(libDatabaseConfig::RECEPTION_TABLE, libDatabaseConfig::APPLICATION_TABLE . '.ixnn_reception_seq_no', libDatabaseConfig::RECEPTION_TABLE . '.seq_no')
            ->innerJoin(libDatabaseConfig::CERTFICATE_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', libDatabaseConfig::CERTFICATE_TABLE . '.seq_no')
            ->innerJoin(libDatabaseConfig::DEPARTMENT_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_reception_department_seq_no', libDatabaseConfig::DEPARTMENT_TABLE . '.seq_no')
            ->where(libDatabaseConfig::APPLICATION_TABLE . '.is_deleted', ':is_deleted')
            ->andWhere(libDatabaseConfig::APPLICATION_TABLE . '.seq_no', ':seq_no')
            ->andWhere('t_application.ixnn_reception_seq_no', 't_reception.seq_no')
            ->andWhere('t_reception.ixnn_certificate_seq_no', 't_certificate.seq_no')
            ->andWhere('t_reception.ixnn_reception_department_seq_no', 't_reception_department.seq_no');

        $aSqlParams = array(
            'is_deleted' => 'F',
            'seq_no'     => $iId,
            'key'        => libDatabaseConfig::KEY
        );

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute($aSqlParams);
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);

        $this->closeConnection();

        return array(
            'result' => $aResult
        );
    }

    /**
     * get certificate details of applicant
     * @param  [type] $iId [description]
     * @return [type]      [description]
     */
    public function getCertifiateDetails($iId)
    {
        $this->getDBConnection();
        $oStatement = libQueryBuilder::instance()->select(
            libDatabaseConfig::APPLICATION_TABLE . '.seq_no,'
            . 'AES_DECRYPT(UNHEX(' .libDatabaseConfig::APPLICATION_TABLE . '.applicant_name_kr), :key) as applicant_name_kr,'
            . 'AES_DECRYPT(UNHEX(' .libDatabaseConfig::APPLICATION_TABLE . '.applicant_name_en), :key) as applicant_name_en,'
            . 'AES_DECRYPT(UNHEX(' .libDatabaseConfig::APPLICATION_TABLE . '.birthday), :key) as birthday,'
            . libDatabaseConfig::APPLICATION_TABLE . '.issued_date,'
            . libDatabaseConfig::APPLICATION_TABLE . '.certification_no,'
            . libDatabaseConfig::CERTFICATE_TABLE . '.name AS certificate, '
            . libDatabaseConfig::DEPARTMENT_TABLE . '.department_code '
        )
        ->from(libDatabaseConfig::APPLICATION_TABLE)
        ->innerJoin(libDatabaseConfig::RECEPTION_TABLE, libDatabaseConfig::APPLICATION_TABLE . '.ixnn_reception_seq_no', libDatabaseConfig::RECEPTION_TABLE . '.seq_no')
        ->innerJoin(libDatabaseConfig::CERTFICATE_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', libDatabaseConfig::CERTFICATE_TABLE . '.seq_no')
        ->innerJoin(libDatabaseConfig::DEPARTMENT_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_reception_department_seq_no', libDatabaseConfig::DEPARTMENT_TABLE . '.seq_no')
        ->Where(libDatabaseConfig::APPLICATION_TABLE . '.seq_no', ':seq_no')
        ->andWhere('t_application.ixnn_reception_seq_no', 't_reception.seq_no')
        ->andWhere('t_reception.ixnn_certificate_seq_no', 't_certificate.seq_no')
        ->andWhere('t_reception.ixnn_reception_department_seq_no', 't_reception_department.seq_no');

        $aSqlParams = array(
            'seq_no' => $iId,
            'key'    => libDatabaseConfig::KEY
        );

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute($aSqlParams);
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);

        $this->closeConnection();

        return array(
            'result' => $aResult
        );
    }

    /**
     * Update application by ids
     * @param  [type] $iId     [description]
     * @param  [type] $aFields [description]
     * @return [type]          [description]
     */
    public function updateApplicationById($aIds, $aFields)
    {
        $this->getDBConnection();
        $aSqlParams = libQueryBuilder::separateEncrypData($aFields);
        $oStatement = libQueryBuilder::instance()->update($aSqlParams, libDatabaseConfig::APPLICATION_TABLE)->whereIn('seq_no', $aIds);
        
        $aBindParam = array_merge($aSqlParams['data'], $aSqlParams['encrypt'] ?: array());
        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $mResult = $oQuery->execute($aBindParam);
        $this->closeConnection();

        return $mResult;
    }

    public function insertApplication($aParams)
    {
        $this->getDBConnection();
        $aSqlParams = array(
            'data'    => array(
                'ixnn_reception_seq_no' => $aParams['ixnn_reception_seq_no'],
                'condition_status'      => $aParams['condition_status'],
                'email'                 => $aParams['email'],
                'ins_timestamp'         => date('Y-m-d H:i:s'),
                'supplier_id'           => $aParams['supplier_id'],
                'upd_timestamp'         => date('Y-m-d H:i:s'),
                'email'                 => $aParams['email']
            ),
            'encrypt' => array(
                'applicant_name_kr' => $aParams['applicant_name_kr'],
                'applicant_name_en' => $aParams['applicant_name_en'],
                'birthday'          => $aParams['birthday'],
                'applicant_cell'    => $aParams['applicant_cell']
            )
        );

        if (isset($aParams['applicant_id']) === true) {
            $aSqlParams['data']['applicant_id'] = $aParams['applicant_id'];
        }
        
        $oStatement = libQueryBuilder::instance()->insert($aSqlParams, libDatabaseConfig::APPLICATION_TABLE);
        $aParameters = array_merge($aSqlParams['data'], $aSqlParams['encrypt']);

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $aBindParam = array();

        foreach ($aParameters as $mKey => $aValue) {
            $aBindParam[':' . $mKey] = $aValue;
        }

        $oQuery->execute($aBindParam);
        $iNewId = $this->oDBInstance->lastInsertId();
        $this->closeConnection();

        return $iNewId;
    }

    public function insertCustomeApplication($aParams)
    {
        $this->getDBConnection();
        $aSqlParams = $aParams;
        
        $oStatement = libQueryBuilder::instance()->insert($aSqlParams, libDatabaseConfig::APPLICATION_TABLE);
        $aParameters = array_merge($aSqlParams['data'], $aSqlParams['encrypt']);

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $aBindParam = array();

        foreach ($aParameters as $mKey => $aValue) {
            $aBindParam[':' . $mKey] = $aValue;
        }

        $oQuery->execute($aBindParam);
        $iNewId = $this->oDBInstance->lastInsertId();
        $this->closeConnection();

        return $iNewId;
    }

    /**
     * get list of ids of supplier in the reception
     * @return [type] [description]
     */
    public function getSuppliers()
    {
        $this->getDBConnection();
        $oUnionStatement = libQueryBuilder::instance()
            ->select(libDatabaseConfig::RECEPTION_TABLE . '.supplier_id')
            ->from(libDatabaseConfig::RECEPTION_TABLE);
        //$oStatement->sStatement = $oStatement->sStatement . ' UNION DISTINCT ';
        $sStatement = $oUnionStatement->sStatement;
        $oStatement = libQueryBuilder::instance()
            ->select(libDatabaseConfig::APPLICATION_TABLE . '.supplier_id')
            ->from(libDatabaseConfig::APPLICATION_TABLE);
        $oStatement->concatQuery(' UNION DISTINCT ' . $sStatement);
        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute();
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);
        $iTotalCount = $this->getTotalCount();
        $this->closeConnection();

        return array(
            'result'      => $aResult,
            'total_count' => $iTotalCount
        );
    }
}

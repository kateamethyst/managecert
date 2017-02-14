<?php

/**
 * Business logic for application
 * @package  bl
 * @author  Perseus Laguador, Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class blApplication
{
    /**
     * instance of modelManageCert
     * @var object
     */
    private $oModel;

    /**
     * Constructor
     * @param object $oModel  Instance of model
     */
    public function __construct($oModel)
    {
        $this->oModel = $oModel;
        $this->status = libConfig::applicationStatus();
    }

    /**
     * Set method for api request module
     * @param array     $aParams    set parameters
     * @param string    $sMethod    Method used
     */
    public function setMethod($aParams, $sMethod)
    {
        if ($sMethod === 'get') {
            if (libValid::isString($aParams['sort']) === true) {
                return $this->getPaginatedApplication($aParams);
            } else {
                return $this->checkApplicationExist($aParams);
            }
        }

        if ($sMethod === 'post') {
            if ($aParams['option'] === 'updateCondition') {
                return $this->updateConditionStatus($aParams);
            } else {
                return $this->printCertificate($aParams['id']);
            }
        }
    }

    /**
     * Get paginated application (For search purpose)
     * @param  array    $aParams    List of params
     * @return array
     */
    public function getPaginatedApplication($aParams)
    {
        /*$iLimit, $iPage, $iTotalCount*/
        $iLimit = $aParams['limit'] ?: 20;
        $iStart = (($aParams['page'] ?: 1) - 1) * $iLimit;
        
        $aSqlParams['iStart'] = $iStart;

        $aSqlParams['iLimit'] = $iLimit;

        $aParams = libValid::escapeString($aParams);

        if ($aParams['mall_version'] === libConfig::SUPPLIER) {
            $aSqlParams['supplier_id'] = $aParams['user_id'];
        } else if ($aParams['mall_version'] === libConfig::CUSTOMER) {
            $aSqlParams['supplier_id'] = $aParams['user_id'];
        } else if (($aParams['mall_version'] === libConfig::SUPER_ADMIN || $aParams['mall_version'] === libConfig::ADMIN) && libValid::isString($aParams['supplier_id']) === true) {
            $aSqlParams['supplier_id'] = $aParams['supplier_id'];
        }

        if (libValid::isArray($aParams['status']) === true) {
            $aParams['status'] = array_filter($aParams['status'], function($sValue) {
                return ($sValue !== '');
            });

            if (libValid::isArray(array_filter($aParams['status'])) === true) {
                $aSqlParams['condition_status'] = $aParams['status'];
            }
        }

        if (libValid::isString($aParams['reception_id']) === true) {
            $aSqlParams['ixnn_reception_seq_no'] = (int)$aParams['reception_id'];
        }

        if (libValid::isString($aParams['certificate_id']) === true) {
            $aSqlParams['ixnn_certificate_seq_no'] = (int)$aParams['certificate_id'];
        }
        if (libValid::isString($aParams['end_date']) === true) {
            $aSqlParams['end_date'] = date('Y-m-d', strtotime($aParams['end_date'] . libConfig::ONE_DAY));
        } else {
            $aSqlParams['end_date'] = date('Y-m-d', strtotime(libConfig::ONE_DAY));
        }

        if (libValid::isString($aParams['start_date']) === true) {
            $aSqlParams['start_date'] = $aParams['start_date'];
        } else {
            $aSqlParams['start_date'] = date('Y-m-d', strtotime(libConfig::SEVEN_DAYS));
        }

        if (isset($aParams['bPrint']) === true) {
            $aSqlParams['bPrint'] = $aParams['bPrint'];
        }

        if (libValid::isString($aParams['sort']) === true) {
            $aSqlParams['sort'] = $aParams['sort'];
        }
        return $this->oModel->getAdminFilteredApplication($aSqlParams);
    }

    /**
     * Get suppliers
     * @param  array    $aParams     List of parameters
     * @return array
     */
    public function getSuppliers($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return array(
                'result' => array(),
                'total'  => 0
            );
        }

        if ($aParams['mall_version'] === libConfig::SUPPLIER) {
            return array(
                'result' => array(
                    $aParams['supplier_id']
                ),
                'total'  => 1
            );
        }
        return $this->oModel->getSuppliers();
    }
    /**
     * Check if the application is already registered
     * @param  array     $aParams     List of parameters
     * @return mixed
     */
    public function checkApplicationExist($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return array();
        }

        $aSqlParams = array(
            'ixnn_reception_seq_no' => (int)$aParams['reception_id'],
            'applicant_name_kr'     => $aParams['korean_name'],
            'applicant_name_en'     => $aParams['english_name'],
            'applicant_cell'        => $aParams['cell_no']
        );

        return $this->oModel->isRegistered($aSqlParams);
    }

    /**
     * Find application by id
     * @param  integer    $iId    Application ID
     * @return array
     */
    public function findApplication($iId)
    {
        if (libValid::isString($iId) === false) {
            return array(
                'result'      => array(),
                'total_count' => 0
            );
        }

        return $this->oModel->getApplicationById($iId);
    }

    /**
     * Delete application/s
     * @param  array           $aParams      contains id of applications to delete
     * @return array
     */
    public function deleteApplication($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return array(
                'error' => 'Delete Failed'
            );
        }
        return $this->oModel->updateApplicationById($aParams['application_id'], array('is_deleted' => 'T'));
    }

    /**
     * Get applicant certificate info
     * @param  integer    $iId      Application Id
     * @return array
     */
    public function getCertificate($iId)
    {
        if (libValid::isNull($iId) === true) {
            return array();
        }

        $aCertificate = $this->oModel->getCertifiateDetails((int)$iId);
        if (libValid::isArray($aCertificate) === false || libValid::isArray($aCertificate['result']) === false) {
            return '';
        }

        return $this->createCertificate($aCertificate['result'][0]);
    }

    /**
     * Download application in excell file
     * @param  array   $aParams    list of parameters
     * @return array
     */
    public function downloadApplication($aParams, $oOpenapi)
    {
        if (libValid::isArray($aParams) === false) {
            return array();
        }

        $aCondition = array('접수대기', '접수완료', '채점완료', '승인요청', '발급승인', '발급완료', '보완필요');

        $aParams['bPrint'] = true;

        $aData = $this->getPaginatedApplication($aParams);
        
        if (libValid::isArray($aData) === false || libValid::isArray($aData['result']) === false) {
            return array(
                'error' => '신청 내역이 없습니다.'
            );
        }
        //note validation array

        if ($aParams['sort'] === 'A') {
            $iInc = 1;
            $iTotal = 1;
        } else {
            $iTotal = $aData['total_count'];
            $iInc = -1;
        }

        if (libValid::isArray($aData) === true && libValid::isArray($aData['result']) === true) {
            foreach ($aData['result'] as $iKey => $aApplication) {
                $sWritten = ($aApplication['written_exam_score'] === null) ? '-' : $aApplication['written_exam_score'];
                $sPractical = ($aApplication['practical_test_score'] === null) ? '-' : $aApplication['practical_test_score'];
                $aCsvData[] = array(
                    'No'       => $iTotal, //pre and post increment doesnt work
                    '아이디'     => ($aApplication['applicant_id'] === null) ? '-' : stripcslashes($aApplication['applicant_id']),
                    '이름'      => stripcslashes($aApplication['applicant_name_kr']),
                    '신청일'     => date('Y-m-d', strtotime($aApplication['ins_timestamp'])),
                    '점수'      => $sWritten . ' / ' . $sPractical,
                    '상태'      => $aApplication['condition_status'],
                    '자격증 발급' => ($aApplication['issued_date'] === null) ? '-' : $aApplication['issued_date'],
                    '합격여부'   => $aApplication['test_result'],
                    '접수처'     => stripcslashes($aApplication['reception']),
                    '자격증'     => $aApplication['certificate']
                );
                $iTotal = $iTotal + $iInc;
            }
        }
        $sFilename = '/' .  date('Y-m-d') . libConfig::FILENAME;
        $bHeading = true;
        foreach ($aCsvData as $aRow) {
            if($bHeading === true) {
                $sCsv = $sCsv . implode("\t,", array_keys($aRow)) . "\n";
                $sCsv = $sCsv . implode("\t,", array_values($aRow)) . "\n";
                $bHeading = false;
            } else {
                $sCsv = $sCsv . implode("\t,", array_values($aRow)) . "\n";
            }
        }

        $mExcelFileResult = $oOpenapi->call('file', 'manage', array(
            'act'      => 'file_put_contents',
            'path'     => $sFilename,
            'contents' => iconv("UTF-8", "EUC-KR", $sCsv),
            'decoding' => 'utf-8'
        ), 'POST', 1);

        if ($mExcelFileResult['response'] === false) {
            return array(
                'error' => '신청 내역이 없습니다.'
            );
        }

        return array(
            'href' => 'http://' . $aParams['mall_id'] . '.cafe24.com/web/upload/cstore/shop1/managecert' . $sFilename
        );
    }

    /**
     * update the application entry to indicate that was printed
     * @param  integer     $iId      Applicant id
     * @return mixed
     */
    public function printCertificate($iId)
    {
        if (libValid::isString($iId) === false) {
            return array(
                'error' => 'Print Certificate is Failed'
            );
        }

        $aCertificate = $this->oModel->getApplicationById((int)$iId);

        if (libValid::isArray($aCertificate) === false || libValid::isArray($aCertificate['result']) === false) {
            return array(
                'error' => 'Print Certificate is Failed'
            );
        }

        if (libValid::isArray($aCertificate['result']) === true && libValid::isString($aCertificate['result'][0]['issued_date']) === false) {
            $aSqlParams = array(
                'issued_date'      => date('Y-m-d H:i:s'),
                'condition_status' => $this->status['60']
            );

            return $this->oModel->updateApplicationById(array((int)$iId), $aSqlParams);
        }

        return $aCertificate;

    }

    /**
     * Create cerfiticate html
     * @param  array $aCertificate Data
     * @return string
     */
    private function createCertificate($aCertificate)
    {
        $sDate = ($aCertificate['issued_date'] === null) ? date('F d, Y') : date_format(date_create($aCertificate['issued_date']), 'F d, Y');
        $sHtml = '<body id="popup" size="700,1000">'
            . '<div id="wrap-certificate">'
            . '<div class="section">'
            . '<img id="certificate-img" src="[img]/certificate.jpg">'
            . '<input id="certificate_no" type="hidden" value="' . $aCertificate['seq_no'] . '" name="seq_no">'
            . '<div class="certificate-header">'
            . '<p id="issue-number">발급번호: ' . htmlspecialchars($aCertificate['certification_no'], ENT_QUOTES, "UTF-8") . '</p>'
            . ' <h1 id="certificate_name">' . htmlspecialchars($aCertificate['certificate'], ENT_QUOTES, "UTF-8") .' </h1>'
            . ' <p id="name">이름: ' . htmlspecialchars($aCertificate['applicant_name_kr'], ENT_QUOTES, "UTF-8") . '(' . htmlspecialchars($aCertificate['applicant_name_en'], ENT_QUOTES, "UTF-8") . ')</p>'
            . ' <p id="birthday">생년월일: ' . htmlspecialchars($aCertificate['birthday'], ENT_QUOTES, "UTF-8") . '</p>'
            . ' </div>'
            . ' <div class="certificate-message">'
            . ' <p id="message">위 사람은 본 기관의 민간자격증관리운영규정에 의하여 실시한 소정의 검정과정을 거쳐 상기 자격을 취득하였음을 인증함.</p>'
            . ' <p id="date">' . $sDate  .'</p>'
            . ' </div>'
            . ' <div class="certificate-footer">'
            . ' <h2><strong>심플렉스인터넷</strong><span>㈜</span></h2>'
            . ' </div>'
            . ' </div>'
            . ' <div id="footer">'
            . ' <a href="#" class="btnEm eModal" onclick="window.close();">'
            . ' <span>닫기</span>'
            . ' </a>'
            . ' <a id="btnPrintCertificate" href="#none" class=" btnSubmit" >'
            . ' <span>인쇄</span>'
            . ' </a>'
            . ' </div>'
            . ' </div>'
            . ' </body>';

        return $sHtml;
    }

    /**
     * Save applicate(insert/update)
     * @param  array    $aParams     Parameters
     * @return mixed
     */
    public function saveApplication($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return array(
                'error' => '저장하지 못했습니다.'
            );
        }

        $aParams = libValid::escapeString($aParams);

        if (libValid::isString($aParams['seq_no']) === true) {

            $aResult = $this->oModel->getApplicationById((int)$aParams['seq_no']);

            if (libValid::isArray($aResult) === false && libValid::isArray($aResult['result']) === false) {
                return array(
                    'result'      => array(),
                    'total_count' => 0
                );
            }

            $aSqlParams = array(
                'condition_status'     => $aParams['status'],
                'applicant_name_kr'    => $aParams['korean_name'],
                'applicant_name_en'    => $aParams['english_name'],
                'birthday'             => $aParams['birthday'],
                'applicant_cell'       => $aParams['number_item1'] . '-' . $aParams['number_item2'] . '-' . $aParams['number_item3'],
                'email'                => $aParams['email'],
                'written_exam_score'   => $aParams['written_exam_score'],
                'practical_test_score' => $aParams['practical_test_score'],
                'test_result'          => $aParams['remarks'],
                'upd_timestamp'        => date('Y-m-d H:i:s')
            );

            if ((int)$aParams['status'] === $this->status['50']) {
                $aSqlParams['certification_no'] = date('Y') . '-' . $aParams['department_code'] . '00' . $aParams['seq_no'];
            }

            if (isset($aParams['member_id']) === true) {
                $aSqlParams['applicant_id'] = $aParams['member_id'];
            }
            return $this->oModel->updateApplicationById(array((int)$aParams['seq_no']), $aSqlParams);
        } else {
            $aErrors = $this->validateParams($aParams);
            if (libValid::isArray($aErrors) === true) {
                return $aErrors;
            }

            $aExists = $this->checkApplicationExist($aParams);

            if($aExists['total_count'] > 0) {
                return false;
            }
            $aSqlParams = array(
                'ixnn_reception_seq_no' => (int)$aParams['reception_id'],
                'condition_status'      => $aParams['status'],
                'email'                 => $aParams['email'],
                'ins_timestamp'         => date('Y-m-d H:i:s'),
                'supplier_id'           => $aParams['user_id'],
                'upd_timestamp'         => date('Y-m-d H:i:s'),
                'email'                 => $aParams['email'],
                'applicant_name_kr'     => $aParams['korean_name'],
                'applicant_name_en'     => $aParams['english_name'],
                'birthday'              => $aParams['birthday'],
                'applicant_cell'        => $aParams['number_item1'] . '-' . $aParams['number_item2'] . '-' . $aParams['number_item3']
            );

            if (isset($aParams['member_id']) === true) {
                $aSqlParams['applicant_id'] = $aParams['member_id'];
            }
            return $this->oModel->insertApplication($aSqlParams);
        }
    }

    /**
     * Update condition status
     * @param  array   $aParams   List of parameters
     * @return boolean
     */
    public function updateConditionStatus($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return array(
                'error' => '저장하지 못했습니다.'
            );
        }

        if ($aParams['status'] === $this->status['50']) {
            $aSqlParams['certification_no'] = date('Y') . '-' . $aParams['department_code'] . '00' . $aParams['seq_no'];
        }

        $aSqlParams['condition_status'] = $aParams['status'];

        return $this->oModel->updateApplicationById(array((int)$aParams['seq_no']), $aSqlParams);
    }

    /**
     * Validate Params
     * @param  array $aParams   parameters
     * @return array
     */
    private function validateParams($aParams)
    {
        //Check seq no
        if (libValid::isNull($aParams['reception']) === true) {
            return array(
                'error' => '접수처를 선택해주세요.'
            );
        }
        //Check name
        if (libValid::isNull($aParams['korean_name']) === true) {
            return array(
                'error' => '이름(한글)을 입력해주세요.'
            );
        }
        //Check names
        if (libValid::isNull($aParams['english_name']) === true) {
            return array(
                'error' => '이름(한글)을 입력해주세요.'
            );
        }
        //Check birthday
        if (libValid::isNull($aParams['birthday']) === true) {
            return array(
                'error' => '생년월일을 입력해주세요.'
            );
        }
        //Check application cell
        if (libValid::isNull($aParams['number_item1']) === true || libValid::isNull($aParams['number_item2']) === true || libValid::isNull($aParams['number_item3']) === true ) {
            return array(
                'error' => '휴대전화를 정확히 입력해주세요.'
            );
        }
        //Check email
        if (filter_var($aParams['email'], FILTER_VALIDATE_EMAIL) === false) {
            if (libValid::isString($aParams['email']) === true ) {
                return array(
                    'error' => '이메일 형식이 올바르지 않습니다. 다시 확인해주세요.'
                );
            }
        }
    }

    /**
     * Check of editable
     * @param  array      $aParam           List of parameters
     * @param  string     $sMallVersion     Mall Version
     * @return boolean
     */
    public function checkIfEditable($aParams, $sMallVersion)
    {
        if ($sMallVersion === libConfig::SUPER_ADMIN || $sMallVersion === libConfig::ADMIN) {
            if (in_array($aParams['condition_status'], array($this->status['10'], $this->status['20'], $this->status['30'], $this->status['40'], $this->status['70'])) === true) {
                return true;
            }
            return false;
        } else if ($sMallVersion === libConfig::SUPPLIER) {
            if (in_array($aParams['condition_status'], array($this->status['40'], $this->status['50'])) === true) {
                return false;
            }
            return true;
        } else if ($sMallVersion === libConfig::CUSTOMER) {
            if (in_array($aParams['condition_status'], array($this->status['10'], $this->status['20'])) === true) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    public function insertCustomedApplicant($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return array(
                'error' => '저장하지 못했습니다.'
            );
        }

        $aParams = libValid::escapeString($aParams);
        $aErrors = $this->validateParams($aParams);
        if (libValid::isArray($aErrors) === true) {
            return $aErrors;
        }

        $aExists = $this->checkApplicationExist($aParams);

        if($aExists['total_count'] > 0) {
            return false;
        }

        $aSqlParams = array(
            'ixnn_reception_seq_no' => (int)$aParams['reception_id'],
            'condition_status'      => $aParams['status'],
            'ins_timestamp'         => date('Y-m-d H:i:s', strtotime(($aParams['date_registered'] ?: date('Y-m-d H:i:s')))),
            'supplier_id'           => $aParams['user_id'],
            'upd_timestamp'         => date('Y-m-d H:i:s'),
            'applicant_name_kr'     => $aParams['korean_name'],
            'applicant_name_en'     => $aParams['english_name'],
            'birthday'              => $aParams['birthday'],
            'applicant_cell'        => $aParams['number_item1'] . '-' . $aParams['number_item2'] . '-' . $aParams['number_item3'],
            'is_deleted'            => $aParams['is_deleted'] ?: 'F'
        );

        if(isset($aParams['applicant']) === true) {
            $aSqlParams['applicant_id'] = $aParams['applicant'];
        }

        if(isset($aParams['email']) === true && libValid::isString($aParams['email']) === true) {
            $aSqlParams['email'] = $aParams['email'];
        }

        if(isset($aParams['written_score']) === true) {
            $aSqlParams['written_exam_score'] = floatval($aParams['written_score']);
        }

        if(isset($aParams['practical_score']) === true) {
            $aSqlParams['practical_test_score'] = floatval($aParams['practical_score']);
        }

        if(isset($aParams['result']) === true) {
            $aSqlParams['test_result'] = $aParams['result'];
        }

        if(isset($aParams['certificate_number']) === true) {
            $aSqlParams['certification_no'] = $aParams['certificate_number'];
        }

        if(isset($aParams['issued_date']) === true) {
            $aSqlParams['issued_date'] = date('Y-m-d', strtotime($aParams['issued_date']));
        }

        $aSqlParams = array(
            'data'    => $aSqlParams,
            'encrypt' => array(
                'applicant_name_kr' => $aParams['korean_name'],
                'applicant_name_en' => $aParams['english_name'],
                'birthday'          => $aParams['birthday'],
                'applicant_cell'    => $aParams['number_item1'] . '-' . $aParams['number_item2'] . '-' . $aParams['number_item3'],
            )
        );

        unset($aSqlParams['data']['applicant_name_kr']);
        unset($aSqlParams['data']['applicant_name_en']);
        unset($aSqlParams['data']['birthday']);
        unset($aSqlParams['data']['applicant_cell']);

        return $this->oModel->insertCustomeApplication($aSqlParams);
    }
}

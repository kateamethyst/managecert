<?php

/**
 * Business logic for reception
 * @package  bl
 * @author  Perseus Laguador, Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class blReception
{
    /**
     * instance of modelManageCert
     * @var object
     */
    private $oModel;

    /**
     * constructor
     * @param object   $oModel   model instance
     */
    public function __construct($oModel)
    {
        $this->oModel = $oModel;
        $this->status = libConfig::receptionStatus();
    }

    /**
     * Set method for api request module
     * @param array     $aParams    set parameters
     * @param string    $sMethod    Method used
     */
    public function setMethod($aParams, $sMethod)
    {
        if (isset($aParams['supplier_id']) === true && libValid::isString($aParams['supplier_id']) === false) {
            unset($aParams['supplier_id']);
        }

        if ($sMethod === 'get') {
            if ($aParams['option'] === 'inprogress') {
                return $this->getInProgressReception($aParams);
            } else if ($aParams['option'] === 'all') {
                return $this->getReception($aParams);
            } else {
                return $this->findReception($aParams);
            }
        }
    }

    /**
     * Get reception
     * @return array
     */
    public function getReception($aParams)
    {
        $aSqlParams = array(
            'is_deleted' => 'F'
        );

        if ($aParams['mall_version'] === libConfig::SUPPLIER) {
            $aSqlParams['supplier_id'] = $aParams['user_id'];
        }

        if (isset($aParams['supplier_id']) === true && libValid::isString($aParams['supplier_id']) === true && isset($aParams['option']) === true) {
            $aSqlParams['supplier_id'] = $aParams['supplier_id'];
        }

        if (isset($aParams['certificate_id']) === true || libValid::isString($aParams['certificate_id']) === true) {
            $aSqlParams['ixnn_certificate_seq_no'] = $aParams['certificate_id'];
        }

        return $this->oModel->getReception($aSqlParams);
    }

    /**
     * Get in progress reception
     * @return array
     */
    public function getInProgressReception($aParams)
    {
        $aSqlParams = array(
            'condition_status' => $this->status[20],
            'is_deleted'       => 'F'
        );

        if ($aParams['mall_version'] === libConfig::SUPPLIER) {
            $aSqlParams['supplier_id'] = $aParams['user_id'];
        }

        if (isset($aParams['supplier_id']) === true && libValid::isString($aParams['supplier_id']) === true && isset($aParams['option']) === true) {
            $aSqlParams['supplier_id'] = $aParams['supplier_id'];
        }

        if (isset($aParams['certificate_id']) === true || libValid::isString($aParams['certificate_id']) === true) {
            $aSqlParams['ixnn_certificate_seq_no'] = $aParams['certificate_id'];
        }
        return $this->oModel->getAllInProgress($aSqlParams);
    }

    /**
     * get paginated reception according to given parameters
     * @param  array    $aParams    parameters in search module
     * @return array
     */
    public function getPaginatedReception($aParams)
    {
        $iLimit = $aParams['limit'] ?: 20;
        $iStart = (($aParams['page'] ?: 1) - 1) * $iLimit;

        if (libValid::isArray($aParams) === false) {
            return array(
                'result'      => array(),
                'total_count' => 0
            );
        }

        $aParams = libValid::escapeString($aParams);

        if ($aParams['mall_version'] === libConfig::SUPPLIER) {
            $aSqlParams['supplier_id'] = $aParams['user_id'];
        }

        if (libValid::isString($aParams['classification_value']) === true) {
            switch ($aParams['classification']) {
                case 'certificate':
                    $aSqlParams['classification'] = libDatabaseConfig::CERTFICATE_TABLE . '.name';
                    break;
                case 'reception':
                    $aSqlParams['classification'] = libDatabaseConfig::RECEPTION_TABLE . '.name';
                    break;
                case 'test_location':
                    $aSqlParams['classification'] = libDatabaseConfig::RECEPTION_TABLE . '.test_site';
                    break;
            }

            $aSqlParams['classification_value'] = $aParams['classification_value'];
        }

        if (libValid::isArray($aParams['status']) === true) {
            $aSqlParams['condition_status'] = $aParams['status'];
        }

        if (libValid::isArray($aParams['department_id']) === true) {
            $aSqlParams['ixnn_reception_department_seq_no'] = $aParams['department_id'];
        }

        if (libValid::isString($aParams['start_date']) === true) {
            $aSqlParams['start_date'] = $aParams['start_date'];
            $aSqlParams['end_date'] = $aParams['end_date'];
        } else {
            $aSqlParams['start_date'] = date('Y-m-d', strtotime(libConfig::SEVEN_DAYS));
            $aSqlParams['end_date'] = date('Y-m-d', strtotime(libConfig::ONE_DAY));
        }

        if ($aParams['end_date'] === date('Y-m-d')) {
            $aSqlParams['end_date'] = date('Y-m-d', strtotime(libConfig::ONE_DAY));
        }

        $aSqlParams['iStart'] = $iStart;

        $aSqlParams['iLimit'] = $iLimit;

        $aSqlParams['sort'] = 'D';

        return $this->oModel->getAdminFilteredReception($aSqlParams);
    }

    /**
     * Find Reception
     * @param  array    $aParams    parameters
     * @return array
     */
    public function findReception($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return array(
                'result'      => array(),
                'total_count' => 0
            );
        }

        $aSqlParams = array(
            key($aParams) => $aParams[key($aParams)]
        );

        return $this->oModel->searchReception($aSqlParams);
    }

    /**
     * Add and update reception
     * @param  array $aParams   parameters
     * @return boolean
     */
    public function saveReception($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return array(
                'error' => '저장하지 못했습니다.'
            );
        }

        $aParams = libValid::escapeString($aParams);

        if (libValid::isString($aParams['seq_no']) === true) {
            $aResult = $this->findReception(array('seq_no' => $aParams['seq_no']));

            if (libValid::isArray($aResult) === false && libValid::isArray($aResult['result']) === false) {
                return array(
                    'result'      => array(),
                    'total_count' => 0
                );
            }

            $aSqlParams = array(
                'education_type'   => ($aParams['exam_category'] === '' || $aParams['exam_category'] === null) ? '-' : $aParams['exam_category'],
                'nth_test'         => ($aParams['exam_duration'] === '' || $aParams['exam_duration'] === null ) ? '-' : $aParams['exam_duration'],
                'test_site'        => ($aParams['exam_location'] === '' || $aParams['exam_location'] === null) ? '-' : $aParams['exam_location'],
                'condition_status' => $aParams['status']
            );

            return $this->oModel->updateReceptionById(array((int)$aParams['seq_no']), $aSqlParams);

        } else {
            $aErrors = $this->validateParams($aParams, 'create');

            if (libValid::isArray($aErrors) === true) {
                return $aErrors;
            }

            $aSqlParams = array(
                'ixnn_certificate_seq_no'          => (int)$aParams['certificate_id'],
                'name'                             => $aParams['name'],
                'condition_status'                 => $aParams['status'],
                'ixnn_reception_department_seq_no' => (int)$aParams['department_id'],
                'education_type'                   => ($aParams['exam_category'] === '' || $aParams['exam_category'] === null) ? '-' : $aParams['exam_category'],
                'nth_test'                         => ($aParams['exam_duration'] === '' || $aParams['exam_duration'] === null ) ? '-' : $aParams['exam_duration'],
                'test_site'                        => ($aParams['exam_location'] === '' || $aParams['exam_location'] === null) ? '-' : $aParams['exam_location'],
                'supplier_id'                      => $aParams['user_id'],
                'is_deleted'                       => ($aParams['is_deleted'] ?: 'F'),
                'upd_timestamp'                    => date('Y-m-d H:i:s')
            );

            return $this->oModel->insertReception($aSqlParams);
        }
    }

    /**
     * Delete reception
     * @param  array $aParams  paramaters
     * @return boolean
     */
    public function deleteReception($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return array(
                'error' => 'Delete Failed'
            );
        }

        $aSqlParams = array(
            'is_deleted' => 'T'
        );

         return $this->oModel->updateReceptionById($aParams['reception'], $aSqlParams);
    }


    /**
     * Validate parameters for reception
     * @param  array $aParams parameters
     * @return boolean
     */
    private function validateParams($aParams)
    {
        $aResult = $this->findReception(array('name' => $aParams['name'])); 

        if (libValid::isArray($aParams) === false) {
            return array(
                'error' => '저장하지 못했습니다.'
            );
        } else if (libValid::isNull($aParams['supplier_id']) === true) {
            return array(
                'error' => '저장하지 못했습니다.'
            );
        } else if (libValid::isNull($aParams['certificate_id']) === true) {
            return array(
                'error' => '저장하지 못했습니다.'
            );
        } else if (libValid::isNull($aParams['department_id']) === true) {
            return array(
                'error' => '소속을 선택해주세요.'
            );
        } else if (libValid::isNull($aParams['name']) === true) {
            return array(
                'error' => '접수처를 입력해주세요.'
            );
        } else if (libValid::isArray($aParams['name']) === true) {
            return array(
                'error' => '유효한 영수증을 입력하십시오.'
            );
        } else if (libValid::isArray($aResult['result']) === true) {
            return array(
                'error' => '동일한 접수처가 이미 등록되어 있습니다. 접수처 명칭을 다르게 지정해주세요.'
            );
        } else {
            return true;
        }
    }

    public function getAllReception()
    {
        return $this->oModel->getAllReception();
    }
}


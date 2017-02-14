<?php

/**
 * Business logic for department
 * @package  bl
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class blDepartment
{
    /**
     * instance of modelManageCert
     * @var object
     */
    private $oModel;

    /**
     * Constructor
     */
    public function __construct($oModel)
    {
        $this->oModel = $oModel;
    }

    /**
     * get institution/s
     * @return array
     */
    public function getDepartment()
    {
        return $this->oModel->getDepartment();
    }

    public function insertDepartment($aParams)
    {
        $aData = array();
        $aCodes = libConfig::departmentCodes();

        if(libValid::isString($aParams['name'] ?: '') === true && libValid::isString($aParams['code']) === true && in_array($aParams['code'], $aCodes) === true) {
            $aData = array(
                'department_name' => $aParams['name'],
                'department_code' => $aParams['code']
            );
        } else {
            return false;
        }

        $aProccessedData = array(
            'data' => $aData
        );

        return $this->oModel->saveDepartment($aProccessedData);
    }
}

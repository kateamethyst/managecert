<?php

/**
 * Business logic for certificate
 * @package  bl
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class blCertificate
{
    /**
     * instance of modelManageCert
     * @var object
     */
    private $oModel;

    /**
     *  blCertificate mconstructor
     * @param object $oModel  Model instance
     */
    public function __construct($oModel)
    {
        $this->oModel = $oModel;
    }

    /**
     * Get certificate/s
     * @param  array $aParams Parameters
     * @return array
     */
    public function getCertificate()
    {
        return $this->oModel->getCertificate();
    }

    /**
     * Add certificate
     * For future purposes
     */
    public function addCertificate($aParams)
    {
        $aData = array();

        if(libValid::isString($aParams['name'] ?: '') === true) {
            $aData = array(
                'name' => $aParams['name'],
                'is_active' => 'T'
            );
        }

        $aProccessedData = array(
            'data' => $aData
        );

        return $this->oModel->saveCertificate($aProccessedData);
    }
}

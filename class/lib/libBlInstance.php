<?php

/**
 * libBlInstance
 * @package lib
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version 1.0
 * @since   since 2016.12.19
 */
class libBlInstance
{

    /**
     * Group
     * @var object
     */
    private $oBlReception;

    /**
     * Stats
     * @var object
     */
    private $oBlApplication;

    /**
     * Certificate instance
     * @var object
     */
    private $oBlCertificate;

    /**
     * Department instance
     * @var object
     */
    private $oBlDepartment;

    /**
     * Model
     * @var object
     */
    private $oModel;

    /**
     * libBlInstance instance
     * @var object
     */
    public static $oInstance;

     /**
     * libBlInstance instance
     * @return object
     */
    public static function instance()
    {
        if (self::$oInstance === null) {
            self::$oInstance = new libQueryBuilder();
        }
        return self::$oInstance;
    }

    /**
     * Get blGroup instance
     * @return object
     */
    public function getBlReception()
    {
        $this->oModel = new modelReception();
        if ($this->oBlReception === null) {
            $this->oBlReception = new blReception($this->oModel);
        }
        return $this->oBlReception;
    }

    /**
     * Get blStats instance
     * @return object
     */
    public function getBlApplication()
    {
        $this->oModel = new modelApplication();
        if ($this->oBlApplication === null) {
            $this->oBlApplication = new blApplication($this->oModel);
        }
        return $this->oBlApplication;
    }

    /**
     * Get blDepartment instance
     * @return object
     */
    public function getBlDepartment()
    {
        $this->oModel = new modelDepartment();
        if ($this->oBlDepartment === null) {
            $this->oBlDepartment = new blDepartment($this->oModel);
        }
        return $this->oBlDepartment;
    }

    /**
     * Get blCertificate instance
     * @return object
     */
    public function getBlCertificate()
    {
        $this->oModel = new modelCertificate();
        if ($this->oBlCertificate === null) {
            $this->oBlCertificate = new blCertificate($this->oModel);
        }
        return $this->oBlCertificate;
    }
}

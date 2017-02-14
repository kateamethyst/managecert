<?php

/**
 * Common
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
abstract class adminCommon extends Controller_Admin
{
    /**
     * Parameters
     * @var array
     */
    protected $aArgs;

    /**
     * Request
     * @var array
     */
    protected $aRequest;

    /**
     * initialize
     * @param  array    $aArgs   Parameters
     * @return bool
     */
    protected function execute($aArgs, $sModule)
    {
        $this->aArgs = $aArgs;
        $this->initialize();
        $this->importJsCss();
        if ($sModule === 'application') {
            $this->importJS('application', array('aArgs' => $this->aArgs));
        } else if ($sModule === 'certificate') {
            $this->importCSS('certificate');
            $this->importJS('application', array('aArgs' => $this->aArgs));
        } else {
            $this->importJS('reception', array('aArgs' => $this->aArgs));
        }
        $this->setBl();
    }

    /**
     * Initialize all the request and parameters needed
     */
    protected function initialize()
    {
        $this->aArgs['mall_version'] = $this->Request->getService()->getType();
        $this->aArgs['supplier_id'] = $this->aArgs['supplier_id'];
        $this->aArgs['page'] = libValid::isNumeric($this->aArgs['page']) === true ? (int)$this->aArgs['page'] : 1;
        $this->aArgs['limit'] = libValid::isNumeric($this->aArgs['limit']) === true ? (int)$this->aArgs['limit'] : 20;
        $this->aArgs['user_id'] = $this->Service->getUserID();
    }

    /**
     * Import js and css
     */
    protected function importJsCss()
    {
        // JS
        $this->externalJS('//img.echosting.cafe24.com/js/suio.js');
        $this->externalJS('//img.echosting.cafe24.com/js/calendar/dateUtil.js');
        $this->UIPackage->addPlugin('calendar');
        $this->importJS('app');

        // CSS
        $this->externalCSS('//img.echosting.cafe24.com/css/suio.css');
        $this->importCSS('app');
    }

    protected function setBl()
    {
        $this->oBlCertificate = libBlInstance::getBlCertificate();
        $this->oBlReception = libBlInstance::getBlReception();
        $this->oBlApplication = libBlInstance::getBlApplication();
        $this->oBlDepartment = libBlInstance::getBlDepartment();
    }

    /**
     * Assign array
     * @param  array $aParam   Parameters
     * @return boolean
     */
    protected function arrayAssign($aParam)
    {
        if (libValid::isArray($aParam) === false) {
            return false;
        }
        foreach ($aParam as $sKey => $sVal) {
            $this->assign(trim($sKey), $sVal);
        }
    }

    /**
     * view method
     *
     * @return bool
     */
    protected function setView($sView)
    {
        $bView = $this->View($sView);
        if ($bView === false) {
            return false;
        }

        $this->setStatusCode('200');
        return true;
    }

    /**
     * Set default value
     */
    protected function setDefaultValue($sModule)
    {
        $iTotalPage = ceil($this->aArgs['total_count'] / $this->aArgs['limit']);
        if ($this->aArgs['page'] > $iTotalPage || abs($this->aArgs['page']) < 1) {
            return 'location.href="[link=admin/'. $sModule .'/index?page=1]";';
        }
    }


    protected function setPagingParam($sUrl)
    {
        return array(
            'url'         => $sUrl,
            'page_record' => $this->aArgs['limit'],
            'now_page'    => $this->aArgs['page'],
            'total_count' => $this->aArgs['total_count'],
            'search_data' => $this->aArgs
        );
    }

    /**
     * Set Script
     *
     * @return bool
     */
    protected function setScript($mResult, $sModule)
    {
        if ($this->aArgs['seq_no']) {
            if (libValid::isArray($mResult['error']) === true) {
                $this->writeJS('alert("' . $mResult['error'] . '"); location.href="[link=admin/' . $sModule . '/details?seq_no=' . $this->aArgs['seq_no'] . '"];');
            } else {
                $this->writeJS('alert("등록되었습니다."); window.history.back();');
            }
        } else {
            if (libValid::isArray($mResult['error']) === true) {
                $this->writeJS('alert("' . $mResult['error'] . '"); window.history.back();');
            } else {
                $this->writeJS('alert("등록 되었습니다."); location.href="[link=admin/' . $sModule . '/index"];');
            }
        }
    }
}

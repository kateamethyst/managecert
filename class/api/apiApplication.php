<?php

/**
 * Application
 * @package  api
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class apiApplication extends Controller_Api
{
    /**
     * Get
     * @param  array    $aArgs   paramters
     * @return mixed
     */
    protected function get($aArgs)
    {
        // $bLogin = $this->Service->isLogin();
        // if ($bLogin === true) {
            $oBlApplication = libBlInstance::getBlApplication();
            return $oBlApplication->setMethod($aArgs, 'get');
        // }
        // return false;
    }

    /**
     * POST
     * @param  array    $aArgs    Parameters
     * @return mixed
     */
    protected function post($aArgs)
    {
        // $bLogin = $this->Service->isLogin();
        // if ($bLogin === true) {
            $oBlApplication = libBlInstance::getBlApplication();
            return $oBlApplication->setMethod($aArgs, 'post');
        // }
        // return false;
    }
}

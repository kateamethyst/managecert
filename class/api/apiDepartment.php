<?php

/**
 * Department
 * @package  api
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class apiDepartment extends Controller_Api
{
    /**
     * Get
     * @param  array    $aArgs List of parameters
     * @return mixed
     */
    protected function get()
    {
        // $bLogin = $this->Service->isLogin();
        // if ($bLogin === true) {
            $oBlDepartment = libBlInstance::getBlDepartment();
            $aDepartment = $oBlDepartment->getDepartment();
            return $aDepartment;
        // }
        // return false;
    }
}

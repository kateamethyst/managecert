<?php

/**
 * Save
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class adminApplicationSave extends adminCommon
{
    /**
     * initial run for /application/index
     */
    public function run($aArgs)
    {
        $this->execute($aArgs, 'application');
        $mResult = $this->oBlApplication->saveApplication($this->aArgs);
        $this->setScript($mResult, 'application');
    }
}

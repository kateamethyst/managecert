<?php

/**
 * Save
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class adminReceptionSave extends adminCommon
{
    /**
     * initial run for /reception/index
     */
    public function run($aArgs)
    {
        $this->execute($aArgs, 'reception');
        $mResult = $this->oBlReception->saveReception($this->aArgs);
        $this->setScript($mResult, 'reception');
    }
}

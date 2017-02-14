<?php

/**
 * Certificate
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class adminMigration extends adminCommon
{
    protected function run($aArgs)
    {
        $this->importJS('migrate');
        $this->setView('migrate');
    }
}

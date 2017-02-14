<?php

/**
 * Delete
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class adminApplicationDelete extends adminCommon
{
    /**
     * initial execute for /reception/delete
     */
    public function run($aArgs)
    {
        $this->execute($aArgs, 'application');
        $aApplication = $this->oBlApplication->deleteApplication($this->aArgs);
        $this->writeJS('alert("선택하신 항목을 삭제 하였습니다."); location.href="[link=admin/application/index"];');
    }
}

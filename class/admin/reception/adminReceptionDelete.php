<?php

/**
 * Delete
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class adminReceptionDelete extends adminCommon
{
    /**
     * initial execute for /reception/delete
     */
    public function run($aArgs)
    {
        $this->execute($aArgs, 'reception');
        $aReception = $this->oBlReception->deleteReception($this->aArgs);
        $this->writeJS('alert("선택하신 항목을 삭제 하였습니다."); location.href="[link=admin/reception/index"];');
    }
}

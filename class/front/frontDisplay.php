<?php


class frontDisplay extends Controller_Front
{
    protected function run($aArgs)
    {
        $oBl = new blFront('pc', $this->Openapi);
        $aContent = $oBl->getContent($aArgs);
        $this->setStatusCode('200');
        $this->assign('sContent', $aContent);
    }
}

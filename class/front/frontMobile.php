<?php


class frontMobile extends Controller_Front
{
    protected function run($aArgs)
    {
        $oBl = new blFront('mobile', $this->Openapi);
        $aContent = $oBl->getContent($aArgs);
        $this->setStatusCode('200');
        $this->assign('sContent', $aContent);
    }
}

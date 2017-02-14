<?php


class blFront
{
    private $sView;

    private $oOpenApi;

    public function __construct($sView, $oOpenApi)
    {
        $this->sView = $sView;
        $this->oOpenApi = $oOpenApi;
    }

    public function getContent($aParams)
    {
        $sPage = $aParams['page'] ?: 'index';
        $sHtml = '';
        $sAnnouncementContent = ''

        switch ($sPage) {
            case 'index':
                $sHtml = $this->getIntroducePage($this->oOpenApi);
                $sAnnouncementContent = $this->getAnnouncementHtml();
                break;
            case 'procedure':
                $sHtml = $this->getProcedurePage();
                break;
            case 'step1':
                $sHtml = $this->getStepOnePage();
                break;
            case 'step2':
                if (isset($aParams['seq_no']) === true) { //application id
                    //get application data
                    //redirect to step one if application doesn't exists
                    //else
                    //$sHtml = $this->getStepTwoPage($aApplicationDataFetched)
                } else {
                    //redirect to step1
                }
                break;
            default:
                # code...
                break;
        }
        return array(
            'content' => $sHtml,
            'news' => $sAnnouncementContent
        );
    }

    private function getIntroducePage()
    {
        $sHtml = '';
        if($this->sView === 'pc') {
            //insert generation of html content for pc view in index page
        } else {
            //insert generation of html content for mobile index page
        }

        return $sHtml;
    }

    private function getProcedurePage()
    {
        $sHtml = '';
        if($this->sView === 'pc') {
            //insert generation of html content for pc view in index page
        } else {
            //insert generation of html content for mobile index page
        }

        return $sHtml;
    }

    private function getStepOnePage()
    {
        $sHtml = '';
        if($this->sView === 'pc') {
            //insert generation of html content for pc view in index page
        } else {
            //insert generation of html content for mobile index page
        }

        return $sHtml;
    }

    private function getStepTwoPage($aApplication)
    {
        $sHtml = '';
        if($this->sView === 'pc') {
            //insert generation of html content for pc view in index page
        } else {
            //insert generation of html content for mobile index page
        }

        return $sHtml;
    }

    private function getAnnouncementHtml()
    {
        $oAnnouncementBl = new blAnnouncement();
        $aResult = $oAnnouncementBl->getLatest(2);

        $sHtml = '';

        foreach ($aResult as $value) {
            //construct html here
        }

        return $sHtml;
    }
}

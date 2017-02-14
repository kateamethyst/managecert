<?php

class blAnnouncement
{
    private $oOpenApi;

    public function __construct($oOpenApi)
    {
        $this->oOpenApi = $oOpenApi;
    }
    public function getLatest($iLimit = 1)
    {
        //perform api call for latest announcement
    }
}

<?php

/**
 * contains the database configurations
 */
class libConfig
{
    const SUPPLIER = 'S';
    const SUPER_ADMIN = 'P';
    const ADMIN = 'A';
    const CUSTOMER = 'C';
    const ONE_DAY = '+1 day';
    const THREE_DAYS = '-3 days';
    const SEVEN_DAYS = '-7 days';
    const ONE_MONTH = '-1 month';
    const THREE_MONTHS = '-3 month';
    const ONE_YEAR = '-1 year';
    const FILENAME = '_certificate.csv';

    public static function departmentCodes()
    {
        return array('P', 'T', 'H', 'U');
    }

    public static function applicationStatus()
    {
        return array(
            10 => '접수대기', //application standby
            20 => '접수완료', //application accepted
            30 => '채점완료', //application graded
            40 => '승인요청', //approval requested
            50 => '발급승인', //issued approval
            60 => '발급완료', //issued certificate
            70 => '보완필요', //corrections required
        );
    }

    public static function receptionStatus()
    {
        return array(
            10 => '대기', //stand by
            20 => '진행중', //in progress
            30 => '종료', //completed
        );
    }
}


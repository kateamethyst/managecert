<?php

require 'blApplicationTest.php';
require 'blReceptionTest.php';

class testSuite
{
    /**
     * Execute test cases
     * @return object
     */
    public static function suite()
    {
        $oSuite = new Unittest_TestSuite('managecert test suite');
        // $oSuite->addTestSuite('blGroupTest');
        // $oSuite->addTestSuite('blStatsTest');
        return $oSuite;
    }
}

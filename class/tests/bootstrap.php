<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);

if (class_exists('Unittest_Testcase') !== true) {
    class Unittest_Testcase extends PHPUnit_Framework_TestCase
    {
    }
}

if (class_exists('Unittest_TestSuite') !== true) {
    class Unittest_TestSuite extends PHPUnit_Framework_TestSuite
    {
    }
}

if (function_exists('__CstoreUnitClassAutoload') !== true) {
    function __CstoreUnitClassAutoload($sClassName)
    {
        if (class_exists($sClassName, false) === true) {
            return true;
        }

        if (preg_match_all('/(?:^[a-z]+|(?:[A-Z])(?:[a-z0-9]+)?)/', $sClassName, $matches) === 0) {
            return false;
        }

        $matches[0] = array_map('strtolower', $matches[0]);
        $sClassPrefix = $matches[0][0];
        $sPath = dirname(__DIR__) . '/' . $sClassPrefix . '/' . $sClassName . '.php';

        if (file_exists($sPath) === true) {
            require_once($sPath);
            return class_exists($sClassName, false);
        } else {
            return false;
        }
    }

    spl_autoload_register('__CstoreUnitClassAutoload');
}


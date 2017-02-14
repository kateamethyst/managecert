<?php

/**
 * blApplicationTest
 *
 * @package test
 * @author  Ma. Angelica Concepcion
 * @version 1.0
 * @since   2016. 07. 29
 */
class blApplicationTest extends Unittest_Testcase
{

    /**
     * oBlGroup 
     * @var object
     */
    private $oBlApplication;

    /**
     * Set BlApplication and mock model
     * @param array $aOption list of methods and its return value
     */
    private function setBlApplication($aOption = array())
    {
        $aMethod = array(
        );

        $oModelApplicationMock = $this->getMockBuilder('modelManageCert')->disableOriginalConstructor()->setMethods($aMethod)->getMock();

        foreach ($aOption as $sMethod => $mReturn) {
            $oModelApplicationMock->expects($this->any())->method($sMethod)->will($this->returnValue($mReturn));
        }

        $this->oBlApplication = new blApplication($oModelApplicationMock);
    }
} 

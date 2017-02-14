<?php

/**
 * blReceptionTest
 *
 * @package test
 * @author  Ma. Angelica Concepcion
 * @version 1.0
 * @since   2016. 07. 29
 */
class blReceptionTest extends Unittest_Testcase
{

    /**
     * oBlGroup 
     * @var object
     */
    private $oBlReception;

    /**
     * Set BlApplication and mock model
     * @param array $aOption list of methods and its return value
     */
    private function setBlReception($aOption = array())
    {
        $aMethod = array(
        );

        $oModelReceptionMock = $this->getMockBuilder('modelManageCert')->disableOriginalConstructor()->setMethods($aMethod)->getMock();

        foreach ($aOption as $sMethod => $mReturn) {
            $oModelReceptionMock->expects($this->any())->method($sMethod)->will($this->returnValue($mReturn));
        }

        $this->oBlReception = new blReception($oModelReceptionMock);
    }
} 

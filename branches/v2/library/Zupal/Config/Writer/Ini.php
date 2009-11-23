<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ini
 *
 * @author bingomanatee
 */
class
Zupal_Config_Writer_Ini
extends Zend_Config_Writer_Ini
 {

    /**
     * Defined by Zend_Config_Writer
     *
     * @param  string      $filename
     * @param  Zend_Config $config
     * @param  boolean     $exclusiveLock
     * @throws Zend_Config_Exception When filename was not set
     * @throws Zend_Config_Exception When filename is not writable
     * @return void
     */
    public function __toString()
    {

        if ($exclusiveLock !== null) {
            $this->setExclusiveLock($exclusiveLock);
        }

        if ($this->_config === null) {
            require_once 'Zend/Config/Exception.php';
            throw new Zend_Config_Exception('No config was set');
        }

        $iniString   = '';
        $extends     = $this->_config->getExtends();
        $sectionName = $this->_config->getSectionName();

        if (is_string($sectionName)) {
            $iniString .= '[' . $sectionName . ']' . "\n"
                       .  $this->_addBranch($this->_config)
                       .  "\n";
        } else {
            foreach ($this->_config as $sectionName => $data) {
                if (!($data instanceof Zend_Config)) {
                    $iniString .= $sectionName
                               .  ' = '
                               .  $this->_prepareValue($data)
                               .  "\n";
                } else {
                    if (isset($extends[$sectionName])) {
                        $sectionName .= ' : ' . $extends[$sectionName];
                    }

                    $iniString .= '[' . $sectionName . ']' . "\n"
                               .  $this->_addBranch($data)
                               .  "\n";
                }
            }
        }

        return $iniString;
    }
}


<?php

/**
 * Description of Mods
 *
 * @author bingomanatee
 */
class Zupal_Module_Model_Mods
extends Zupal_Model_Domain_Abstract {

    private static $_container;
    protected function container() {
        if (!self::$_container) {
            self::$_container = new Zupal_Model_Container_Mongo('zupal', 'modules', array('schema' => $this->schema()));
        }
        return self::$_container;
    }

    /* @@@@@@@@@@@@@@@@ MOD @@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_mods = array();
    public function mod($pName) {
        $pName = strtolower(trim($pName));

        if(!array_key_exists($pName, $this->_mods)) {
            $crit = array('name' => $pName);
            $mod = $this->find_one($crit);
            if (!$mod) {
                $mod = $this->new_data($crit);
                $path = ZUPAL_MODULES . D . $pName;
                $mod->path = $path;
                $p_path = $path . D . 'profile.json';
                if(!file_exists($p_path)) {
                    throw new Exception(__METHOD__ . ": module $pName has no profile at $p_path");
                }
                $profile =  Zend_Json::decode(file_get_contents($p_path));
                $mod->profile = $profile;

                if (array_key_exists('settings', $profile) && !property_exists($mod, 'settings')) {
                    $mod->settings = $profile['settings'];
                }

                $mod->save();
            } else {
                $mod->insure_defaults();
            }
            $this->_mods[$pName] = $mod;
        }

        return $this->_mods[$pName];
    }

    public function mods() {
        return $this->_mods;
    }


    /**
     *
     * @return string
     */
    public function file() {
        $path = func_get_args();
        $mod_name = array_shift($path);
        $mod = $this->mod($mod_name);
        $root = $mod->path;

        return $root . D . join(D, $path);
    }

    /* @@@@@@@@@@@@@@@@@ NEW DATA @@@@@@@@@@@@@@@@@@@@@@ */

    public function new_data($pData) {
        if (is_array($pData)) {
            $pData = array_merge($this->schema()->defaults(), $pData);
            $out = new self($pData);
        } else {
            $out = new self($pData);
            $out->insure_defaults();
        }
        return $out;
    }

    /* @@@@@@@@@@@@@@@@@ INSTANCE @@@@@@@@@@@@@@@@@@@@@@ */

    private static $_instance;

    /**
     * @return Zupal_Module_Model_Mods
     */
    public static function instance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private $_schema;
    /**
     *
     * @return Zupal_Model_Schema_IF
     */
    public function schema() {
        if (!$this->_schema) {
            $path = dirname(__FILE__) . D . 'module_schema.json';
            $this->_schema = Zupal_Model_Schema_Item::make_from_json($path);
        }
        return $this->_schema;
    }
}

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
    public function file(){
        $path = func_get_args();
        $mod_name = array_shift($path);
        $mod = $this->mod($mod_name);
        $root = $mod->path;

        return $root . D . join(D, $path);
    }

    public function profile() {
        if (!$this->profile) {
            $mod_json_path = $this->path . D . 'profile.json';
            $this->profile = Zend_Json::decode(file_get_contents($mod_json_path));
            $this->save();
        }
        return $this->profile;
    }

    /* @@@@@@@@@@@@@@@@@@@@@ MOD LOAD @@@@@@@@@@@@@@@@@@ */


    function mod_load($mod_name, $path = NULL) {

        $mod = $this->mod($mod_name);
        if ($path && !$mod->path) {
            $mod->path = $path;
            $mod->save();
        }

        if (!$mod->active) {
            //   return;
        }

        $mod->_load_deps();

        $mod->_boot();
    }

    protected function _load_deps() {
        $profile = $this->profile();

        if (array_key_exists('deps', $profile)) {
            foreach($profile['deps'] as $dep_mod) {
                $this->mod_load($dep_mod);
            }
        }
    }

    protected function _boot() {

        $boot_path = $this->path . D . 'bootstrap.php';
        if (!file_exists($boot_path)) {
            throw new Exception(__METHOD__ . ": cannot boot $boot_path");
        }
        require $boot_path;

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

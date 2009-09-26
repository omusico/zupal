<?

class Administer_Model_Modules
extends Zupal_Domain_Abstract {

/**
 * @see Zupal_Formset_Domain::get()
 *
 * @param unknown_type $pID
 * @return Zupal_Domain_Abstract
 */
    public function get ($pID = null, $pLoad_Fields = NULL) {
        $out = new self($pID);
        if ($pLoad_Fields && is_array($pLoad_Fields)):
            $out->set_fields($pLoad_Fields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Zupal_Domain_Abstract
     */
    static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self(Zupal_Domain_Abstract::STUB);
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @see Zupal_Formset_Domain::get_table_class()
     *
     */
    public function tableClass () {
        return preg_replace('~(\w*)_Model_~', '\1_Model_DbTable_', __CLASS__);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ update_from_filesystem @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function update_from_filesystem () {
        $indexed_module_records = $this->find_all_indexed();

        foreach ($indexed_module_records as $imr):
            $imr->load_definitions();
            $imr->save();
        endforeach;

        $folders = $this->module_folders();

        foreach($folders as $folder):
            if (!array_key_exists($folder, $indexed_module_records)):
                $new_module_record = $this->get(NULL, array('folder' => $folder));
                $new_module_record->load_definitions();
                $new_module_record->save();
                $indexed_module_records[$folder] = $new_module_record;
        endif;
        endforeach;

        return $indexed_module_records;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ module_folders @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function module_folders () {

        $di = new DirectoryIterator(APPLICATION_PATH . '/modules');

        $modules = array();
        foreach($di as $fi):
            if ((!$fi->isDot()) && $fi->isDir() && (!preg_match('~^\.~', $fi->getFilename()))):
                $modules[] = $fi->getFilename();
        endif;
        endforeach;

        return $modules;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find_all_indexed @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function find_all_indexed () {
        $out = array();
        foreach($this->findAll('folder') as $module):
            $out[$module->folder] = $module;
        endforeach;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_definitions @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function load_definitions () {
        if ($this->path_exists(self::CONFIG_INFO)):

            $table_def = Zend_Db_Table::getDefaultAdapter()->describeTable($this->table()->tableName());
            $info = $this->get_info();
            $skip = array('id', 'folder', 'active');

            foreach(array_diff(array_keys($table_def), $skip) as $field):
                if (in_array($field, $skip)) continue;
                if (array_key_exists($field, $info)):
                    $this->$field = $info[$field];
            endif;
            endforeach;
        endif;

        $this->save();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ module_path @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pPath
     * @return string
     */
    public function module_path ($pPath) {
        $pPath = ltrim($pPath, DS);
        $out = APPLICATION_PATH . '/modules/' . $this->folder . ($pPath ? '/' . $pPath : '');
        return $out;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ path_exists @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pPath
     * @return <type>
     */
    public function path_exists ($pPath) {
        $path = $this->module_path($pPath);
        return file_exists($path) ? $path : FALSE;
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ info @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    const CONFIG_INFO = 'configuration/info.ini';

    private $_info = NULL;
    function get_info($pReload = FALSE) {
        if (($pReload || is_null($this->_info)) &&
                ($path = $this->path_exists(self::CONFIG_INFO))):
            $ini = new Zend_Config_Ini($path, 'info');
            $info_data = $ini->toArray();
            // process
            $this->_info = $info_data;
        endif;
        return $this->_info;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ present @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function present () {
        return $this->path_exists('');
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pages @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function load_menus ($pReload = FALSE) {
        if ($pReload || !$this->menu_loaded):
            if ($config = $this->menu_data()):
                $mm = Model_Menu::getInstance();
                $crit = $mm->table()->getAdapter()->quoteInto('created_by_module = ?', $this->folder);
                $mm->table()->delete($crit);
                $data = $config->toArray();
                $mm->add_menus($data, $this->folder);
            endif;
            $this->menu_loaded = TRUE;
            $this->save();
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ menu_data @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Zend_Config_Ini
     */
    public function menu_data () {
        $path = $this->path_exists('configuration/info.ini');
        if ($path):
            $config = new Zend_Config_Ini($path, 'menu');
            return $config;
        endif;
        return NULL;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function title () {
        return $this->title ? $this->title : ucwords($this->folder);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ is_active @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * whether the module is on / accessible. 
     * @return boolean
     */
    public function is_active () {
        return $this->required || $this->active;
    }
}
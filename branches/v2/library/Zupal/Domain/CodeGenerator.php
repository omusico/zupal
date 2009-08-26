<?

class Zupal_Domain_CodeGenerator {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __constructor @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param <type> $pTable, $pModule, $pDatabase = NULL
 * @return <type>
 */
    public function __constructor ($pTable, $pModule, $pDatabase = NULL) {

    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ module_name @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_module_name = null;
    /**
     * @return class;
     */

    public function get_module_name() { return $this->_module_name; }

    public function set_module_name($pValue) { $this->_module_name = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ table_name @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_table_name = null;
    /**
     * @return class;
     */

    public function get_table_name() { return $this->_table_name; }

    public function set_table_name($pValue) { $this->_table_name = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ database_name @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_database_name = null;
    /**
     * @return class;
     */

    public function get_database_name() { return $this->_database_name; }

    public function set_database_name($pValue) { $this->_database_name = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ adapter @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_adapter = NULL;
    function get_adapter($pReload = FALSE) {
        if ($pReload || is_null($this->_adapter)):
            if ($this->get_database_name()):
                $adapter = Zupal_Module_Manager::getInstance()->database($this->get_database_name());
            else:
                $adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
            endif;
            $this->_adapter = $adapter;
        endif;
        return $this->_adapter;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_file @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
    *
    * @return Zend_CodeGenerator_Php_File
    */
    public function domain_file ()
    {
       $parameters = array(array('name' => 'pID'));
       $params = array(
            'name' => 'get',
            'body' => 'return new self($pID);',
            'parameters' => $parameters
            );
       $get = new Zend_CodeGenerator_Php_Method($params);

        $tableClass = new Zend_CodeGenerator_Php_Method(
            array(
            'name' => 'tableClass',
            'body' => "return '{$this->table_class_name}';"
            )
        );

        $instance_prop = new Zend_CodeGenerator_Php_Property(
            array(
            'static' => TRUE,
            'visibility' => 'protected static',
            'name' => '_Instance'
            )
        );

        $body = '        if (is_null(self::$_Instance)): ' . "\n" .
            '      self::$_Instance = new self();' . "\n" .
            '  endif;' . "\n" .
            '   return self::$_Instance;';

        $instance_method = new Zend_CodeGenerator_Php_Method(
            array(
            'name' => 'getInstance',
            'body' => $body,
            'visibility' => 'public static'
            )
        );

        $methods = array(
            $get,
            $tableClass,
            $instance_method
        );

        $class = new Zend_CodeGenerator_Php_Class(
            array(
            'name' => $this->get_domain_class(),
            'extendedClass' => 'Zupal_Domain_Abstract',
            'methods' => $methods,
            'properties' => array($instance_prop)
            )
        );

        $file = new Zend_CodeGenerator_Php_File(
            array(
                'classes' => array($class)
            )
        );
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_file @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    public function table_file () {
        $cs = $this->create_sql();

        $create_method = new Zend_CodeGenerator_Php_Method(
            array(
            'name' => 'create_table',
            'visibility' => 'public',
            'static' => TRUE,
            'body' => "\$this->getInstance()->getAdapter()->query(\"$cs\");"
            )
        );

        $create_method->setStatic(TRUE);

        $init_method = new Zend_CodeGenerator_Php_Method(
            array(
            'name' => 'init',
            'visibility' => 'protected',
            'body' => '     $create_method->setStatic(TRUE);'
            )
        );

        $id_prop = new Zend_CodeGenerator_Php_Property(
            array(
            'name' => '_id_field',
            'defaultValue' => $this->get_id_field(),
            'visibility' => 'protected'
            )
        );

        $name_prop = new Zend_CodeGenerator_Php_Property(
            array(
            'name' => '_name',
            'defaultValue' => $this->get_table_name(),
            'visibility' => 'protected'
            )
        );

        $class = new Zend_CodeGenerator_Php_Class(
            array(
            'name' => $this->get_table_class_name(),
            'extendedClass' => 'Zupal_Table_Abstract',
            'methods' => array(
            $create_method// , $init_method
            ),
            'properties' => array($id_prop, $name_prop)
            )
        );

        if ($this->get_database_name()):
            $const = new Zend_CodeGenerator_Php_Method(
                array(
                'name' => '__construct',
                'body' => '        parent::__construct(array("db" => Zupal_Module_Manager::getInstance()->database("' .  $this->get_database_name() . '")));'
                )
            );
            $class->setMethod($const);
        endif;

        $file = new Zend_CodeGenerator_Php_File(
            array('classes' => array($class))
        );

        return $file;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_create_sql @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function table_create_sql () {
        $connection = $this->get_adapter()->getConnection();
        $result = $connection->query("SHOW CREATE TABLE `$pTable`");
        return array_pop($result->fetch_row());
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     * @return string
     */
    public function table_class_name () {
        $table_class = ($this->get_module_name() ? $this->fragment($this->get_module_name()) : '')
        . ($this->get_database_name() ? $this->fragment($this->database_name()) : '')
        . 'Table_'
        . $this->fragment($this->get_table_name(), FALSE);
        return $table_class;
    }

     /**
     *
     * @return string
     */
    public function domain_class_name () {
        $table_class = ($this->get_module_name() ? $this->fragment($this->get_module_name()) : '')
        . ($this->get_database_name() ? $this->fragment($this->database_name()) : '')
        . 'Model_'
        . $this->fragment($this->get_table_name(), FALSE);
        return $table_class;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ fragment @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pText
     * @return string
     */
    public function fragment ($pText, $pUS_suffix = TRUE) {
        $out = ucfirst(strtolower(preg_replace('~[ _]~', '', $pText)));
        if ($pUS_suffix):
            $out .= '_';
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_definition @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    private $_table_definition = NULL;
    function get_table_definition($pReload = FALSE) {
        if ($pReload || is_null($this->_table_definition)):
            $table_def = $adapter->describeTable($this->get_table_name());
            $td = $table_def;

            foreach($td as $c => $row) {
                if ($td[$c]['IDENTITY'] || $td[$c]['PRIMARY']):
                    $name = $td[$c]['COLUMN_NAME'];
                    $td[$c]['COLUMN_NAME'] = "<b><u>$name</u></b>";
                    $this->view->id_field = $name;
                endif;
                $this->view->table_name = $table_name = $td[$c]['TABLE_NAME'];

                unset($td[$c]['SCHEMA_NAME']);
                unset($td[$c]['TABLE_NAME']);
                unset($td[$c]['COLUMN_POSITION']);
                unset($td[$c]['PRIMARY_POSITION']);
                unset($td[$c]['PRIMARY']);
                unset($td[$c]['IDENTITY']);
            }        // process
            $this->_table_definition = $td;
        endif;
        return $this->_table_definition;
    }
    
}
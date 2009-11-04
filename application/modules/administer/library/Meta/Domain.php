<?

class Administer_Lib_Meta_Domain
{

    const TABLE_FOLDER = 'DbTable';

    function __construct($pTable, $pModule) {
	$this->set_table($pTable);
	$this->set_module($pModule);
	$target_dir = APPLICATION_PATH . '/modules/' . $this->get_module(TRUE) . '/models/';

	$this->set_domain_path($target_dir . $this->clean_table_name() . '.php');
	$this->set_table_path($target_dir . self::TABLE_FOLDER . '/' . $this->clean_table_name() . '.php');

	$form_root = APPLICATION_PATH . '/modules/' . $this->get_module(TRUE) . '/forms/';
	$this->set_form_path($form_root . $this->clean_table_name() . '.php');
	$this->set_form_ini_path($form_root . $this->clean_table_name() . '.ini');
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ table @@@@@@@@@@@@@@@@@@@@@@@@ */
    
    private $_table = null;
    /**
     * @return string;
     */
    
    public function get_table() { return $this->_table; }
    
    public function set_table($pValue) { $this->_table = $pValue; }    
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ module @@@@@@@@@@@@@@@@@@@@@@@@ */
    
    private $_module = null;
    /**
     * @return class;
     */
    
    public function get_module($pOrDefault = FALSE) { 
	if($this->_module):
	    return $this->_module;
	elseif($pOrDefault):
	    return 'default';
	else: 
	    return '';
	endif;
    }
    
    public function set_module($pValue) { $this->_module = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    public static function getInstance($pReload = FALSE) {
	if ($pReload || is_null(self::$_Instance)):
	// process
	    self::$_Instance = new self();
	endif;
	return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ clean_table_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     *
     * @return <type>
     */
    public function clean_table_name () {
	return ucfirst(strtolower(preg_replace('~[ _]~', '', $this->get_table())));
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function table_class () {
	return $this->mif() . 'Model_' . self::TABLE_FOLDER . '_' . $this->clean_table_name();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function domain_class () {
	return $this->mif() . 'Model_'  . $this->clean_table_name();
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function form_class () {
	return $this->mif() .  'Form_' .$this->clean_table_name();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ mif @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type>
     * @return <type>
     */
    public function mif () {
	return ($this->get_module() && strcasecmp($this->get_module(), 'default')) ? ucfirst($this->get_module()) . '_' : '';
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @param string $pTable, $pModule
     * @return string
     */
    public function create_domain () {
	
	$file = new Zend_CodeGenerator_Php_File(array(
	    'classes' => array(
		new Zend_CodeGenerator_Php_Class(array(
		    'name'    => $this->domain_class(),
		    'extendedClass' => 'Zupal_Domain_Abstract',
		    'properties' => array(
		    array(
			'name' => '_Instance',
			'visibility' => 'private',
                        'static' => true
		    )),
		    'methods' => array(
			new Zend_CodeGenerator_Php_Method(
			    array(
			    'name' => 'tableClass',
			    'body' => " return '{$this->table_class()}';",
			    )),
			new Zend_CodeGenerator_Php_Method(
			    array(
			    'name' => 'getInstance',
                            'static' => true,
			    'body' => '
    if ($pReload || is_null(self::$_Instance)):
    // process
        self::$_Instance = new self();
    endif;
    return self::$_Instance;',
			    )),
			new Zend_CodeGenerator_Php_Method(
			    array(
			    'name' => 'get',
                            'parameters' => array(
                                array(
                                    'name' => 'pID',
                                    'defaultValue' => NULL
                                ),
                                array(
                                    'name' => 'pLoadFields',
                                    'defaultValue' => NULL
                                )
                            ),
			    'body' => '     $out = new self($pID);
    if ($pLoad_Fields && is_array($pLoad_Fields)):
        $out->set_fields($pLoad_Fields);
    endif;
    return $out;',
			))),
			    'parameters' => array(
                                array('name' => '_instance', 'defaultValue' => 'NULL', 'static' => true ),
                                array('name' => 'pID', 'defaultValue' => 'NULL'),
                                array('name' => 'pLoad_Fields', 'defaultValue' => 'NULL')
                            )
		    )),
		)
	    ));

	$this->set_domain_code($file->generate());
	
        file_put_contents($this->get_domain_path(), $this->get_domain_code());
	
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function create_table () {
        $properties = array(
            array(
            'name' => '_name',
            'visibility' => 'protected',
            'defaultValue' => $this->get_table()
            )
            );
        $methods = array(
            new Zend_CodeGenerator_Php_Method(
            array(
            'name' => 'create_table',
            'body' => " \$sql = <<" . "<SQL\n\nSQL;\n\$this->getAdapter()->query(\$sql);;",
            ))
            );
            
        $file = new Zend_CodeGenerator_Php_File(array(
            'classes' => array(
            new Zend_CodeGenerator_Php_Class(array(
            'name'    => $this->table_class(),
            'extendedClass' => 'Zupal_Table_Abstract',
            'properties' => $properties,
            'methods' => $methods
            ))
        )));
        
        $this->set_table_code($file->generate());
        
        file_put_contents($this->get_table_path(), $this->get_table_code());
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_form @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function create_form () {

	$data = Zend_Db_Table::getDefaultAdapter()->describeTable($this->get_table());
	ob_start();
?>
	$ini_path = preg_replace('~php$~', 'ini', __FILE__);
	$config = new Zend_Config_Ini($ini_path, 'fields');
	parent::__construct($config);

	if ($pDomain):
	    $this->set_domain($pDomain);
	    $this->domain_to_fields();
	endif;
<?
	$body = ob_get_clean();

	$construct = new Zend_CodeGenerator_Php_Method(
	  array(
	      'name' => '__construct',
	      'body' => $body,
	      'visibility' => 'public',
		'parameters' => array(
		    new Zend_CodeGenerator_Php_Parameter(
		    array(
			'name' => 'pDomain',
			'defaultValue' => 0
		    )))
	  )  
	);


	$body = '';
	foreach(array_keys($data) as $field):
	    $body .= '$this->dtf("' . $field . '");' . "\n";
	endforeach;

	$body = 'return array("' . join('","', array_keys($data)) . '");';

	$domain_fields = new Zend_CodeGenerator_Php_Method(
	    array(
	    'name' => 'domain_fields',
	    'body' => $body,
	    'visibility' => 'public'));

        $get_domain_class = new Zend_CodeGenerator_Php_Method(
            array(
                'name' => 'get_domain_class',
                'body' => 'return "' . $this->domain_class() . '";',
                'visibility' => 'protected'
            ));

	$file = new Zend_CodeGenerator_Php_File(array(
	    'classes' => array(
		new Zend_CodeGenerator_Php_Class(array(
		    'name'    => $this->form_class(),
		    'extendedClass' => 'Zupal_Form_Abstract',
		    'methods' => array(
			$construct,
			$domain_fields,
			$get_domain_class
		    )
		))
	    )));

	$this->set_form_code($file->generate());

        file_put_contents($this->get_form_path(), $this->get_form_code());
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_form_ini @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function create_form_ini () {

	$data = Zend_Db_Table::getDefaultAdapter()->describeTable($this->get_table());
	$ini = new Zend_Config_Writer_Ini();
	$config = new Zend_Config(array(), true);

	$elements = array();
	foreach($data as $field => $values):
	    if ($values['PRIMARY']):
		$element = array(
		    'type' => 'hidden'
		);
	    else:
		$element = array(
		    'type' => 'text',
		    'options' => array('label' => $this->field_label($field)),
                    'value' => array_key_exists('DEFAULT', $values) ? $values['DEFAULT'] : 0
		);

		if (preg_match('~enum\((.*)\)~', $values['DATA_TYPE'], $matches)):
		    $element['type'] = 'select';
		    $element['options'] = $this->enum_options($matches[1]);
		endif;

	    endif;
	    $elements[$field] = $element;
	endforeach;
	$elements['save_button'] = array(
	    'options' => array('label' => 'Save'),
	    'type' => 'submit'
	);

	$config->fields = array('elements' => $elements);
	$ini->setConfig($config);
	$ini->write($this->get_form_ini_path());
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ field_label @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pText
     * @return <type>
     */
    public function field_label ($pText) {
	return ucwords(str_replace('_', ' ', $pText));
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ enum_options @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $options
     * @return <type>
     */
    public function enum_options ($options) {
	$opts = split(',', $options);
	foreach($opts as $i => $o) $opts[$i] = trim($o, "'");
	return $opts;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ domain_path @@@@@@@@@@@@@@@@@@@@@@@@ */
    
    private $_domain_path = null;
    /**
     * @return class;
     */
    
    public function get_domain_path() { return $this->_domain_path; }
    
    public function set_domain_path($pValue) { $this->_domain_path = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ table_path @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_table_path = null;
    /**
     * @return class;
     */

    public function get_table_path() { return $this->_table_path; }

    public function set_table_path($pValue) { $this->_table_path = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ form_path @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_form_path = null;
    /**
     * @return class;
     */

    public function get_form_path() { return $this->_form_path; }

    public function set_form_path($pValue) { $this->_form_path = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ form_ini_path @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_form_ini_path = null;
    /**
     * @return class;
     */

    public function get_form_ini_path() { return $this->_form_ini_path; }

    public function set_form_ini_path($pValue) { $this->_form_ini_path = $pValue; }




/* @@@@@@@@@@@@@@@@@@@@@@@@@@ table_code @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_table_code = null;
    /**
     * @return class;
     */

    public function get_table_code() { return $this->_table_code; }

    public function set_table_code($pValue) { $this->_table_code = $pValue; }



/* @@@@@@@@@@@@@@@@@@@@@@@@@@ domain_code @@@@@@@@@@@@@@@@@@@@@@@@ */
    
    private $_domain_code = null;
    /**
     * @return class;
     */
    
    public function get_domain_code() { return $this->_domain_code; }
    
    public function set_domain_code($pValue) { $this->_domain_code = $pValue; }
    

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ form_code @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_form_code = null;
    /**
     * @return class;
     */

    public function get_form_code() { return $this->_form_code; }

    public function set_form_code($pValue) { $this->_form_code = $pValue; }


}
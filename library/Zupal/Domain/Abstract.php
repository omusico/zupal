<?php

/**
 * Description of Abstract
 *
 * @author daveedelhart
 */
abstract class Zupal_Domain_Abstract
implements Zupal_Domain_IDomain {
    
    const STUB = '_asStub_';
    protected $_row = NULL;
    
    /**
     * Creates an object based on the passed ID.
     * NOTE: there is NO redundancy protection on domain objects because
     * it is presumed that the underlying row has redundancy insulation.
     *
     * @param unknown_type $pID
     */
    public function __construct ($pID = NULL) {
        if (is_object($pID)):
            $this->_row = $pID;
        elseif (!strcasecmp($pID, self::STUB)):
            $this->asStub();
        elseif ($pID):
            $this->load($pID);
        else:
            $this->newRow();
        endif;
        
        $this->init();
    }
    
    public static function _as($pItem, $pClass, $pAsID = FALSE) {
        if (!$pItem instanceof $pClass):
            if (is_scalar($pItem)):
                $pItem = new $pClass($pItem);
                if (!$pItem->isSaved()):
                    return FALSE;
            endif;
            else:
                throw new Exception(__METHOD__ . ': cannot convert ' . print_r($pItem, 1) . ' to ' . $pClass);
        endif;
        endif;
        
        if ($pAsID):
            return $pItem->identity();
        else:
            return $pItem;
    endif;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * an extension point for new records
     */
    public function init () {
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ new @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    protected function newRow($pData = NULL) {
        $this->_row = $this->table()->fetchNew();
        $row = $this->_row;
        if ($pData):
            $fields = array_keys($row->toArray());
            if ($pData instanceof stdClass):
                foreach($fields as $field):
                    if (property_exists($pData, $field)):
                        $row->$field = $pData->field;
                endif;
                endforeach;
            elseif ($pData instanceof Zend_Db_Table_Row_Abstract):
                foreach($fields as $field):
                    $row->$field = $pData->field;
                endforeach;
        endif;
        endif;
        
        return $row;
    }
    
    protected $_joins = array();
    
    public function get_join($pKey, $pCreate_if_empty = TRUE) {
    //	$pType = strtolower(trim($pType));
        
        $data = $this->_joins[$pKey];
        if ($data):
            extract($data);
            $id = $this->__get($local_key);
            
            extract($data); // should return $class, $local_key, $value = NULL
            
            if ($id):
                if ($value):
                    if ($value->identity() != $id):
                        $value = new $class($id);
                        $this->_joins[$pKey]['value'] = $value;
                    endif;
                    return $value;
                endif;
                return $this->set_join($pKey, $class, $id);
            elseif($value):
                return $value;
            elseif ($pCreate_if_empty):
                $stub = new $class();
                $this->_joins[$pKey]['value'] = $stub;
                return $stub;
        endif;
        endif;
        return NULL;
    }
    
    public function set_join($pKey, $pClass, $pID = NULL, $pLocal_Key = NULL) {
        if ($pLocal_Key):
            $data = array(
                'local_key' => $pLocal_Key,
                'value' => NULL,
                'class' => $pClass
            );
            $this->_joins[$pKey] = $data;
        elseif ($pID):
            $new = new $pClass($pID);
            $this->_joins[$pKey]['value'] = $new;
            return $new;
        else:
            return NULL;
    endif;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return Zupal_Table_Abstract
     */
    public function table () {
        $tc = $this->tableClass();
        if (! array_key_exists($tc, self::$_tables)) {
            self::$_tables[$tc] = new $tc();
        }
        
        return self::$_tables[$tc];
    }
    
    private static $_tables = array();
    
    protected abstract function tableClass ();
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ identity @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    public function identity() {
        if ($this->isStub()) return NULL;
        //@NOTE: elaborate retrieval for debugging purposes -- should be simplified for production.
        $id_field = $this->table()->idField();
	/*	if ($this->_row instanceof stdClass ):
			if (property_exists($this->_row, $id_field)):
				return $this->_row->$id_field;
			else:
				throw new Exception (__METHOD__ . ': bad id ' . $id_field . ' requested from ' . get_class($this));
			endif;
		elseif (is_object($this->_row)): */
        return $this->_row->$id_field;
	/*	else:
			throw new Exception(__METHOD__ . ': non object row in ' . $this->tableClass() . ': ' . print_r($this->_row, 1));
		endif; */
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/*
 * Loads the row with data from the database -- or mock source.
 */
    
    protected function load ($pID) {
        if (is_object($pID)):
            if ($pID instanceof Zend_Db_Table_Row):
                $this->_row = $pID;
                return;
            else:
                throw new Exception(__METHOD__ . ': Non integer ' . print_r($pID, 1) . ' passed to ' . __CLASS__);
        endif;
        endif;
        
        if ($pID):
            $hits = $this->table()->find($pID);
            if ($hits):
                $this->_row = $hits->current();
            else:
                $log = Zupal_Module_Manager::getInstance()->get('people')->logger();
                $log->error('cannot find ' . $pID . ' in ' . $this->tableClass());
        endif;
        endif;
        if (!$this->_row) $this->_row = $this->table()->createRow();
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Status tests @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    /**
     * isSaved and loaded answer two similar questions - but the difference is important.
     * isSaved uses the database as the system of record. If the loaded ID is in the database (or is zero)
     * the isSaved() method returns TRUE.
     *
     * loaded uses the object's ID field as the system of record; if the object has a row and that row's id field is nonzero,
     * the loaded() method returns TRUE;
     */
    //@TODO: test
    public function isSaved() {
        $id_field = $this->table()->idField();
        $table_name = $this->table()->tableName();
        $id = $this->identity();
        
        if (!$id):
            
            return FALSE;
            
        else:
            if (is_numeric($id)):
                $sql = "SELECT count(`$id_field`) FROM `$table_name` WHERE `$id_field` = $id";
            else:
                $sql = "SELECT count(`$id_field`) FROM `$table_name` WHERE `$id_field` LIKE '$id'";
            endif;
            $tally = $this->table()->getAdapter()->fetchOne($sql);
            
            return $tally; // note -- any (unlikely) duplication of an ID key in a table has to be handled downstream of this method
    
    endif;
    }
    
    public function loaded() {
        if (!$this->_row) return FALSE;
        if (is_object($this->_row)):
            $id_field = $this->table()->idField();
            $id = @$this->_row->$id_field;
            return $id > 0 ? TRUE : FALSE;
        else:
            return FALSE;
    endif;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    /**
     * return one (or more) row; should at least be able to find a single
     * domain object; more complex queries should be managed by the domain
     * object as well.
     *
     * The idea behind the find function is to localize all selection SQL to the domain object.
     * While the generic domain function will work for most cases (finding by one or more parameter,
     * simple comparison) some more abstruse searches (such as multi table domains)
     * might require a more sophisticated find mechanism.
     *
     * @param unknown_type $pParams -- either an id, or a hashet of parameters.
     public abstract function find ($pParams, $pSort = NULL);
     */
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    /**
     * Your basic query gateway.
     * Find returns an array or rowset of
     * all the matching results (even if there is one or no results.)
     * findOne on the other hand returns only one (the first) match.
     *
     * NOTE: cannot handle joins -- use find_from_sql with table = false for join based results.
     */
    
    public function find($pParams = NULL, $pSort = NULL) {
        $rows = array();
        if (is_numeric($pParams)):
            $rows = $this->get($pParams);
        elseif (is_array($pParams)):
            $select = $this->_select($pParams, $pSort);
            
            $table_rows = $this->table()->fetchAll($select);
        elseif (is_null($pParams) || ($pParams instanceof Zend_Db_Table_Select)):
            $table_rows = $this->table()->fetchAll($pParams, $pSort);
        endif;
        if ($table_rows):
            foreach($table_rows as $row):
                $rows[] = $this->get($row);
            endforeach;
        endif;
        
        return $rows;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findAll @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    public function findAll ($pSort = NULL) {
        return $this->find(NULL, $pSort);
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findOne @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    /**
     * @return Zupal_Domain_Abstract.
     * If no match is found, NULL returns. 
     */
    public function findOne($pParams = NULL, $pSort = NULL) {
        $select = $this->_select($pParams, $pSort);
        if (defined('DEBUG') && DEBUG):
            $sql = $select->assemble();
        endif;
        $row = $this->table()->fetchRow($select);
        return $row ? $this->get($row) : NULL;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _select @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pParams
     * @return Zend_Db_Table_Select
     */
    protected function _select ($pParams, $pSort = NULL) {
        $select = $this->table()->select();
        if (is_array($pParams) && count($pParams)):
            foreach($pParams as $field => $value):
                if (is_array($value)):
                    list($value, $operator) = $value;
                elseif(is_numeric($value)):
                    $operator = '=';
                else:
                    $operator = 'LIKE';
                endif;
                if (!strcasecmp('in', $operator)):
                    if (is_array($value)):
                        $value = '(' . join(',', $value) . ')';
                    endif;
                    $select->where("`$field` $operator $value");
                else:
                    $select->where("`$field` $operator ?", $value);
            endif;
            endforeach;
        endif;
        if ($pSort):
            $select->order($pSort);
        endif;
        //  $sql = $select->assemble();
        return $select;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    /**
     * returns a domain object for the passed ID.
     * NOTE: does not check for the EXISTENCE of a row with said ID.
     * Also accepts rowset objects (for pre-population) -- see __construct.
     *
     * Note that while this method is not particularly useful (has no advantages over $n = new Class($id),
     * it is used internally.
     *
     * @return Zupal_Domain_Abstract
     */
    public abstract function get ($pID = NULL, $pField_Values = NULL);
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    public function __get ($pField) {
        return $this->_row->__get($pField);
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    public function __set($pField, $pValue) {
    //@NOTE: __get accepts array data but this method presumes row object. One or the other direction needs to be solid.
        $this->_row->__set($pField, $pValue);
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pFields
     * @return void
     */
    public function set_fields (array $pFields) {
        $idf = $this->table()->idField();
        foreach($pFields as $f => $v):
            if (strcasecmp($f, $idf) || $v):
                try {
                    $this->$f = $v;
                }
                catch (Exception $e) {
                    error_log(__METHOD__ . ": bad field assignamtion: $f => $v");
                }
        endif;
        
        endforeach;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    public function save() {
    //@NOTE: __get accepts array data but this method presumes row object. One or the other direction needs to be solid.
        if ($this->_asStub):
            throw new Exception('Attempt to save a stub of ' . get_class($this));
        elseif ($this->_row):
            
            foreach($this->_joins as $join):
                if ($join && is_array($join)):
                    $value = NULL;
                    extract($join);
                    if (is_object($value)):
                        $value->save();
                        $this->$local_key = $value->identity();
                endif;
                
            endif;
            endforeach;
            if ($this->_row instanceof stdClass):
                print_r($this->_row);
                throw new Exception('Cannot save ' . print_r($this->_row, 1));
            endif;
            
            $this->_row->save();
            
        else:
            throw new Exception(__METHOD__ . ': Cannot save empty row');
    endif;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ asStub @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    protected $_asStub = FALSE;
    public function asStub() {
        $this->_asStub = TRUE;
    }
    public function isStub() { return $this->_asStub; }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    protected $_soft_delete = FALSE;
    protected $_soft_delete_key = 'active';
    
    public function delete($pTotal = FALSE) {
        if ($this->_soft_delete):
            if ($pTotal):
                $this->_row->delete();
            else:
                $sdk = $this->_soft_delete_key;
                $this->$sdk = 0;
                $this->save();
                return;
        endif;
        else:
            $this->_row->delete();
    endif;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ toArray @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function toArray () {
        return $this->_row->toArray();
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find_from_sql @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    /**
     * This method takes on faith that the SQL has been constructed
     * to return an array of (at least) identities,
     * and that they have not been mismapped or renamed.
     *
     * @TODO: validate this!
     * @return Zupal_Domain_Abstract[];
     */
    
    public function find_from_sql($pSQL, $pTable = TRUE, $pBy_ID = TRUE) {
        if ($pTable):
            $base = $this->table();
        else:
            $base = $this->table()->getAdapter();
        endif;
        
        if (is_array($pSQL)):
            $rowset = call_user_func_array(array($base, 'fetchAll'), $pSQL);
        else:
            $rowset = $base->fetchAll($pSQL);
        endif;
        
        $rows = array();
        
        if ($pBy_ID):
            $id_field = $this->table()->idField();
            foreach ($rowset as $data):
                $rows[] = $this->get($data[$id_field]);
            endforeach;
        else:
            foreach ($rowset as $data):
                $rows[] = $this->get($data);
            endforeach;
        endif;
        
        return $rows;
    }
    
	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ link @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    const LINK_TEMPLATE = '<a href="%s" %s>%s</a>';
    
    /**
     * returns a markup link to this item
     *
     * @param String $pURL
     * @param String $pClass
     * @return String
     */
    public function link ($pURL, $pClass = NULL) {
        $class = $pClass ? sprintf(' class="%s" ', $pClass) : '';
        
        return sprintf(self::LINK_TEMPLATE, $pURL, $class, $this);
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __call @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pName
     * @return <type>
     */
    public function __call ($pName, $pParams) {
        $name = explode('_', $pName);
        if (count($name) > 1):
            foreach($name as $n => $v) if ($n) $name[$n] = ucfirst($v);
            $alt = join('', $name);
        else:
            $alt = strtolower(preg_replace('/(?<=[a-z])(?=[A-Z])/','_',$pName));
        endif;
        if (method_exists($this, $alt)):
            return call_user_func_array(array($this, $alt), $pParams);
        endif;
        
        throw new Exception("No function $pName or $alt in " . get_class($this));
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ enum_field @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pField
     * @return <type>
     */
    private $_enum_fields = NULL;
    
    public function enum_field ($pField) {
        if (!is_array($this->_enum_fields)):
            $this->_enum_fields = array();
        endif;
        
        if (!array_key_exists($pField, $this->_enum_fields)):
            $this->_enum_fields[$pField] = new Zupal_Domain_Enum($this, $pField);
        endif;
        
        return $this->_enum_fields[$pField];
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ move @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Zupal_Domain_Abstract $pItem
     */
    public function move ($pItem, $pMode, $pRank, $pData = NULL) {
        
        $id = $pItem->identity();
        
        if (!$pItem->isSaved()):
            throw new Exception(__METHOD__ . ': attempt to move unsaved ' . get_class($pItem));
        endif;

        if (is_null($pData)):
            $pData = $this->findAll($pRank);
        endif;
        
                $found = FALSE;
                $out = array();
                
        switch($pMode):
            case self::MOVE_TOP:
                $out [] = $pItem;
                
                foreach($pData as $item):
                    if ($pItem->identity() != $item->identity()):
                        $out[] = $item;
                endif;
                endforeach;
                break;
                
            case self::MOVE_UP:
                
                foreach($pData as $item):
                    if ($found):
                        $out[] = $item;                    
                    elseif ($pItem->identity() == $item->identity()):
                        if (count($out)):
                            $prev = array_pop($out);
                            $out[] = $item;
                            $out[] = $prev;
                        else:
                            $out[] = $item;
                        endif;
                        $found = TRUE;
                    else:
                        $out[] = $item;
                    endif;
                endforeach;
                break;
                
            case self::MOVE_DOWN:
                $just_saved = FALSE;
                foreach($pData as $item):
                    if ($found):
                        $out[] = $item;  
                        if ($just_saved):
                            $out[] = $pItem;
                            $just_saved = FALSE;
                        endif;
                    elseif ($pItem->identity() == $item->identity()):
                        $found = TRUE;
                        $just_saved = TRUE;
                    endif;
                endforeach;
                    
                break;
            case self::MOVE_BOTTOM:
                
                foreach($pData as $item):
                    if ($pItem->identity() != $item->identity()):
                        $out[] = $item;
                endif;
                endforeach;
                $out [] = $pItem;
                break;

            default:
                throw new Exception(__METHOD__  . ': attempt to sort with unknown mode ' . $pMode);
        endswitch;

       foreach($out as $k => $item):
           $item->$pRank = $k;
           $item->save();
       endforeach;

    }
    
    const MOVE_UP = 'move_up';
    const MOVE_TOP = 'move_top';
    const MOVE_DOWN = 'move_down';
    const MOVE_BOTTOM = 'move_bottom';

}


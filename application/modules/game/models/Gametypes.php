<?php

class Game_Model_Gametypes extends Model_Zupalatomdomain {

    private static $_Instance = null;

    public function tableClass() {
        return 'Game_Model_DbTable_Gametypes';
    }

    public static function getInstance() {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

    public function get($pID = NULL, $pLoadFields = NULL) {
        $out = new self($pID);
        if ($pLoadFields && is_array($pLoadFields)):
            $out->set_fields($pLoadFields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ atomic_id @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return int;
     */

    public function get_atomic_id() { return $this->atomic_id; }

    public function set_atomic_id($pValue) { $this->atomic_id = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_atom_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int $pAtomic_id
     * @return Game_Model_Gametypes
     */
    public function for_atom_id ($pAtomic_id) {
        return $this->findOne(array('atomic_id' => $pAtomic_id));
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param boolean $pTotal = FALSE
     * @return void
     */
    public function delete ($pTotal = FALSE) {
        if ($pTotal):
            return parent::delete();
        else:
            $this->active = 0;
            $this->save();
    endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ tree @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pSkip_node = NULL
     * @return <type>
     */
    public function tree ($pSkip_node = NULL, $pFlatten = FALSE) {
        if ($pSkip_node):
            $pSkip_node = Zupal_Domain_Abstract::_as($pSkip_node, 'Game_Model_Gametypes', TRUE);
        endif;

        $data = $this->_tree_node(0, $pSkip_node, 0);

        if ($pFlatten):
            $data = $this->_flatten_nodes($data, $pFlatten);
            //$this->_flatten_nodes($data, $pFlatten);
        endif;

        return $data;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _flatten @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param $pData
     * @return array
     */
    protected function _flatten_nodes ($pData, $pFlat_mode) {

        $out = array();

        foreach($pData['children'] as $child):
            if ($pFlat_mode == self::FLATTEN_WITH_DEPTH):
                $out[] = array('type' => $child, 'depth' => $pData['depth']);
            else:
                $out[] = $child;
            endif;
            array_merge($out, $this->_flatten_nodes($child['children'], $pFlat_mode));
        endforeach;

        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _tree_node @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    const FLATTEN_WITH_DEPTH = 2;
/**
 *
 * @param Game_Model_Gametypes $pType
 * @param int $pSkip_node
 * @param int $pDepth
 * @return array
 */
 
    protected function _tree_node ($pParent, $pSkip_node = 0, $pDepth = 0) {
        $pParent = $pParent ? Zupal_Domain_Abstract::_as($pParent, 'Game_Model_Gametypes', TRUE) : 0;
        $params = array('based_on' => $pParent);
        if ($pSkip_node):
            $params['id'] = array($pSkip_node, '!=');
        endif;

        $tops = $this->find($params);
        $tree = array(
            'type' => $pType,
            'children' => array(),
            'depth' => $pDepth
        );

        foreach($tops as $type):
            $tree['children'][] = $this->_tree_node($type, $pSkip_node, $pDepth + 1);
        endforeach;

        return $tree;
    }
}


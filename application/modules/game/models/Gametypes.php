<?php

class Game_Model_Gametypes
extends Model_Zupalatomdomain {

    private static $_Instance = null;

    public function tableClass() {
        return 'Game_Model_DbTable_Gametypes';
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function init () {
        $this->_atom_field_map['title'] = 'name';
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ getInstance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @return Game_Model_Gametypes
 */
    public static function getInstance() {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 * Note -- uses a cache pattern 
 * @return Game_Model_Gametypes
 */
    public function get($pID = NULL, $pLoadFields = NULL) {
        if (array_key_exists($pID, self::$_cache)):
            return self::$_cache[$pID];
        else:
            $out = new self($pID);
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            self::$_cache[$pID] = $out;
            return $out;
        endif;
    }
    private static $_cache = array();

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


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ options @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function options () {
        $games = $this->find(array('active' => array(0, '>')));

        $options = array();

        foreach($games as $game):
            $options[$game->identity()] = $game->title;
        endforeach;

        asort($options);
        return $options;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ resource_classes @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_resource_classes = NULL;
    function get_resource_classes($pReload = FALSE) {
        if ($pReload || is_null($this->_resource_classes)):
        // process
            $params = array('game_type' => $this->identity(), 'active' => 1);
            $this->_resource_classes = Game_Model_Gameresourceclasses::getInstance()->find($params, 'rank');
        endif;
        return $this->_resource_classes;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_active_session @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param  $pFor_user = NULL
     * @return Game_session
     */
//    public function get_active_session (Model_Users $pFor_user = NULL) {
//        if (!$pFor_user):
//            $pFor_user = Model_Users::current_user();
//            if (!$pFor_user):
//                throw new Exception(__METHOD__ . ': trying to get with no user');
//            endif;
//        endif;
//
//        $user_atom_id = $pFor_user->get_atomic_id();
//        $game_atom_id = $this->get_atomic_id();
//
//        $params = array(
//            'from_atom' => $user_atom_id,
//            'bond_atom' => $game_atom_id,
//            'type' => Game_Model_Gamesessions::ACTIVE_KEY
//        );
//
//        $bond = Model_Zupalbonds::getInstance()->findOne($params, 'bonded_on DESC');
//        if ($bond):
//            $session = $bond->to_atom();
//        else:
//            $session = NULL;
//        endif;
//
//        return $session;
//    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find_by_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param string $pName
 * @return Game_Model_Gametypes
 */
    public function gametype_by_name ($pName) {
        return $this->findOne(array('name' => $pName), 'id DESC');
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ game_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pGame
     * @return <type>
     */
    public function game_type ($pGame) {
        if (is_numeric($pGame)):
            return self::getInstance()->get($pGame);
        else:
            return self::getInstance()->gametype_by_name($pGame);
        endif;
    }
}


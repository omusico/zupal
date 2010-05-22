<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of MongoDB
 *
 * @author bingomanatee
 */
class Zupal_Model_Query_MongoDB
implements Zupal_Model_Query_IF {

    public function __construct($pName) {
        if (is_array($pName)) {
            $pName = $pName['name'];
        }
        $this->_name = $pName;
    }

    private $_name;
    public function name() {
        return $this->_name;
    }

    public function get_data(Zupal_Model_Container_IF $container = NULL) {
        if (!$container || (!$container instanceof Zupal_Model_Container_MongoDB)) {
            $container = new Zupal_Model_Container_MongoDB();
        }
        return $container->find($this);
    }

    public static function as_query($pQuery){
        if (is_string($pQuery) || is_array($pQuery)) {
            $q = new Zupal_Model_Query_MongoDB($pQuery);
        } elseif ($pQuery instanceof Zupal_Model_Query_MongoDB) {
            $q = $pQuery;
        } else {
            throw new Exception(__METHOD__ . " only accepts Zupal_Model_Query_MongoDB");
        }
        return $q;
    }

}


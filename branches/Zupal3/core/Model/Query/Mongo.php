<?php

/**
 * Description of Mongo
 *
 * @author bingomanatee
 */
class Zupal_Model_Query_Mongo
implements Zupal_Model_Query_IF
{
    
    public function  __construct(array $pProps, $pContainer = NULL) {
        $this->_data = $pProps;
        $this->container($pContainer);
    }
    
    private $_data;
    
    public function toArray(){ return $this->_data; }
    
    /**
     * returns an array of Zupal_Model_IF items.
     * @param Zupal_Model_Container_IF $container
     * return array;
     */
    public function get_data(Zupal_Model_Container_IF $container = NULL){
        if (!($container || ($container = $this->container()))){
            throw new Exception(__METHOD__ . ': no container referenced');
        }

        return $container->find($this);
    }

    /**
     * transforms a variety of input to a query
     * 
     * @param variant $pWhat
     * @return Zupal_Model_Query_Mongo
     */
    public static function to_query($pWhat){
        if (is_scalar($pWhat)) {
            $q = new Zupal_Model_Query_Mongo(array('_id' => $pWhat));
        } elseif ($pWhat instanceof Zupal_Model_Query_Mongo) {
            $q = $pWhat;
        } elseif ($pWhat instanceof Zupal_Model_Data_Mongo){
            $a = array('_id' => $pWhat->key(TRUE));
            $q = new Zupal_Model_Query_Mongo($a);
        } elseif (is_array($pWhat)){
            $q = new Zupal_Model_Query_Mongo($pWhat);
        } else {
            throw new Exception(__METHOD__ . ": cannot interpret " . print_r($pWhat, 1));
        }

        return $q;
    }

        /* @@@@@@@@@@@@@@@@@@@@@@@@@@ CONTAINER @@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_container;
    /**
     * note -- to prevent circular logic, setting the container does not
     * actually add the data object to the container's index.
     * That must be done seperately.
     * @param Zupal_Model_Container_IF $pContainer
     * @return Zupal_Model_Container_IF
     */
    public function container(Zupal_Model_Container_Mongo $pContainer = NULL) {
        if ($pContainer) {
            $this->_container = $pContainer;
        }
        return $this->_container;
    }

}


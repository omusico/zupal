<?php

/**
 * This is simply an array of strings.
 */

class Zupal_Model_Query_Item
implements Zupal_Model_Query_IF
{

    private $_container;

    private $_filters = array();

    private $_orders = array();

    private $_fields = array();

    private $_range = array('start' => 0, 'count' => NULL);
    
    public function  __construct($pParams) {
        if ($pParams instanceof Zupal_Model_Container_IF) {
            $thid->_container = $pParams;
        } else {
            foreach($pParams as $name => $value) {
                switch($name) {
                    case 'container':
                        $this->_container = $value;
                        break;

                    case 'filter':
                        $this->add_filter($value);
                        break;

                    case 'filters':
                        foreach($value as $filter) {
                            $this->add_filter($filter);
                        }
                        break;

                    case 'order':
                        $this->add_order($value);
                        break;

                    case 'orders':
                        foreach($value as $order) {
                            $this->add_order($order);
                        }
                        break;

                    case 'field':
                        $this->add_field($value);
                        break;

                    case 'fields':
                        foreach($value as $field) {
                            $this->add_field($field);
                        }
                        break;

                    case 'range':
                        $this->set_range($value);
                        break;

                    default:
                        $this->set_prop($field, $value);
                }
            }
        }
    }

    private $_props = array();
    public function set_prop($field, $value){
        $this->_props[$field] = $value;
    }

    public function get_prop($field){
        return $this->_props[$field];
    }


    public function set_range($pRange) {
        $this->_range = array_merge($this->_range, $pRange);
    }

    /**
     * @param array $field
     * @return Zupal_Model_Query_item
     */
    public function add_field($field) {
        $this->_fields[] = $field;
        return $this;
    }

    /**
     * @param array $order
     * @return Zupal_Model_Query_item

     */
    public function add_order($order) {
        $this->_orders[] = $order;
        return $this;
    }

    /**
     *
     * @param array $pFilter
     * @return Zupal_Model_Query_item
     */
    public function add_filter($pFilter) {
        if (is_array($pFilter)){
            $pFilter = new Zupal_Model_Query_Filter($pFilter);
        }
        $this->_filters[] = $pFilter;
        return $this;
    }

    public function get_data(Zupal_Model_Container_IF $pContainer = NULL){
       $cont = ($pContainer) ? $pContainer : $this->_container;

       if (!$cont){
           throw new Exception(__METHOD__ . ': missing container');
       }

       $cont->get_data($this);
    }
}
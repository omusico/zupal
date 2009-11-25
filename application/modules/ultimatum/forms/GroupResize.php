<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GroupResource
 *
 * @author bingomanatee
 */
class Ultimatum_Form_GroupResize
extends Zend_Form
{

    public function __construct($pg) {
        $pg = Zupal_Domain_Abstract::_as($pg, 'Ultimatum_Model_Ultgamegroups');
        
        $this->set_player_group($pg);
        $path = dirname(__FILE__) . '/GroupResize.ini';
        $options = new Zend_Config_Ini($path, 'fields');
        parent::__construct($options);

        $this->network->setValue(100 + $pg->network_size());
        $this->growth ->setValue(100 + $pg->growth_size());
        $this->offense->setValue(100 + $pg->offense_size());
        $this->defense->setValue(100 + $pg->defense_size());
        $this->player_group->setValue($pg->identity());
        $this->player_group->removeDecorator('htmlTag');
        $this->player_group->removeDecorator('label');
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@ player_group @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_player_group = null;
    /**
     * @return class;
     */

    public function get_player_group() { return $this->_player_group; }

    public function set_player_group($pValue) { $this->_player_group = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ scale @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pParams
     * @return
     */
    public function scale ($pParams) {
        $network = $this->network->getValue();
        $offense = $this->offense->getValue();
        $defense = $this->defense->getValue();
        $growth = $this->growth->getValue();
        $total = $network + $offense + $defense + $growth - 400;

        extract($pParams);

        $new_total = $network + $offense + $defense + $growth - 400;

        $diff = $total - $new_total;
        $diff /= 4.0;

        $network += $diff;
        $offense += $diff;
        $defense += $diff;
        $growth += $diff;

        $this->network->setValue((int) $network);
        $this->growth ->setValue((int) $growth);
        $this->offense->setValue((int) $offense);
        $this->defense->setValue((int) $defense);

        return compact('network', 'offense', 'defense', 'growth');
    }

}

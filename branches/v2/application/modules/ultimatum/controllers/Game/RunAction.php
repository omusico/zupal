<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RunAction
 *
 * @author bingomanatee
 */
class Ultimatum_Game_RunAction
extends Zupal_Controller_Action_Abstract
{
    public function run() {
        $c = $this->get_controller();
        if(!$c->_prep()):
            return $this->_forward('index', 'index', NULL, array('error' => 'Problem playing Ultimatum'));
        endif;
        if (!count($c->view->player->player_groups())):
            return $c->forward('start');
        endif;
        $c->_draw_network();
        $orders = $c->view->player->pending_orders();
        $c->view->pending_orders = $orders;
    }


}

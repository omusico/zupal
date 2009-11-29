<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SwitchAction
 *
 * @author bingomanatee
 */
class Ultimatum_Game_SwitchAction
extends Zupal_Controller_Action_Abstract
{

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ execute @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function run () {

        $user = Model_Users::current_user();
        if ($user):
            $pi = Ultimatum_Model_Ultplayers::getInstance();
            $params = array('user' => $user->identity(), 'active' => 1, 'status' => 'active');
            $active_games = $pi->find($params, 'id DESC');
            foreach($active_games as $ag):
                $ag->active = FALSE;
                $ag->save();
            endforeach;
        else:
            $this->view()->nouser = true;
        endif;
        return $this->forward('index');
    }

}

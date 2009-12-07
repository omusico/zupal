<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Abstract
 *
 * @author bingomanatee
 */
interface Zupal_Fastform_Template_Interface {

    public function serial();

    public function render();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ render_form_tag @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_render_form_tag();

    public function set_render_form_tag($pValue);

    public function express();
}

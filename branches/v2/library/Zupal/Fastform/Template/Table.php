<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Table
 *
 * @author bingomanatee
 */
class Zupal_Fastform_Template_Table
extends Zupal_Fastform_Field_Abstract
//implements Zupal_Fastform_Template_Interface
{

/**
 *
 * @var Zupal_Fastform_Abstract
 */
    public $form;

    public function __construct(Zupal_Fastform_Abstract $pForm, $pProps = array()) {
        $this->set_form($pForm);
        $this->set_prop('class', 'zupal_form_table');
        $this->load_props($pProps);
    }

    public function serial() { return TRUE; }

    public function render_field(Zupal_Fastform_Tag_Abstract $pField) {
        if (is_string($pField)):
            $pField = $this->form->get_field($pField);
        endif;

        if (!$pField instanceof Zupal_Fastform_Tag_Abstract):
            return '';
        endif;

        ob_start(); ?>
<tr>
    <th><?= $pField->get_label() ?></th>
    <td><?= $pField ?></td>
    <td><small><?= $pField->get_description() ?></small></td>
</tr>
        <?
        $out = ob_get_clean();
        return $out;
    }

    public function express_field(Zupal_Fastform_Tag_Abstract $pField) {
        if (is_string($pField)):
            $pField = $this->form->get_field($pField);
        endif;

        if (!$pField instanceof Zupal_Fastform_Tag_Abstract):
            return '';
        endif;

        ob_start(); ?>
<tr>
    <th><?= $pField->get_label() ?></th>
    <td><?= $pField->express() ?></td>
    <td><small><?= $pField->get_description() ?></small></td>
</tr>
        <?
        $out = ob_get_clean();
        return $out;
    }

    public function render() {
        if ($this->get_render_form_tag()):
            $this->get_form()->set_body($this->__toString());
            return $this->get_form()->__toString();
        else:
            return $this->__toString();
        endif;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_body @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string;
     */
    public function get_body () {
        $out = '';
        foreach($this->get_form()->get_fields() as $field):
            $out .= $this->render_field($field);
        endforeach;
        ob_start();
        ?>
<tr><td colspan="3" style="text-align: center"><center><table border="0" class="controls"><tr>
                        <? foreach($this->get_form()->controls() as $button): ?>
                <td><?= $button ?></td>
                    <? endforeach; ?></tr>
        </table></center></td></tr>
        <?
        return $out . ob_get_clean();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ express_body @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return string
     */
    public function express_body () {
        $out = '';
        foreach($this->get_form()->get_fields() as $field):
            $out .= $this->express_field($field);
        endforeach;
        ob_start();
        ?>
<!-- CONTROL ROW -->
<tr><td colspan="3" style="text-align: center"><center><table border="0" class="controls"><tr>
                        <? foreach($this->get_form()->controls() as $key => $button): ?>
                    <!-- CONTROL <?= $key ?> -->
                <td><?= $button ?></td>
                    <!-- END CONTROL <?= $key ?> -->
                    <? endforeach; ?></tr>
        </table></center></td></tr>
<!-- END CONTROL ROW -->
        <?
        return $out . ob_get_clean();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ render_form_tag @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_render_form_tag = null;
    /**
     * @return class;
     */

    public function get_render_form_tag() { return $this->_render_form_tag; }

    public function set_render_form_tag($pValue) { $this->_render_form_tag = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ tag_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function tag_name () {
        return 'table';
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ head @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function head () {
        return '<h2>' . $this->get_form()->get_label() . '</h2>';
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __toString @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function __toString () {
        $out = '';
        if ($this->get_form()->get_label()):
            $out .= $this->head();
        endif;
        $out .= parent::__toString();
       return $out;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ my_props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return array
     */
    public function my_props () {
        $out = array();
        if ($this->get_name()):
            $out['name'] = $this->get_name();
        endif;

        return $out;
    }

}


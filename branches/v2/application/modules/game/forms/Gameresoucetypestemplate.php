<?

class Game_Form_Gameresoucetypestemplate
extends Zupal_Fastform_Field_Abstract
implements Zupal_Fastform_Template_Interface {

    public $form;

    public function __construct(Zupal_Fastform_Abstract $pForm, $pProps = array()) {
        $this->set_form($pForm);
        $this->set_prop('class', 'zupal_form_table');
        $this->load_props($pProps);
    }

    public function serial(){

    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ tag_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function tag_name () {
        return 'table';
    }

    public function render(){
        $control = array_pop($this->get_form()->controls());
        ob_start();
?>
<?= $this->get_form()->id ?>
<?= $this->get_form()->game_type ?>
<?= $this->get_form()->resource_class ?>
<table class="zupal_form_table">
    <tr>
        <th style="width: 150px"><?= $this->get_form()->title->get_label() ?></th>
        <td><?= $this->get_form()->title ?></td>
        <th style="width: 450px" colspan="2"><?= $this->get_form()->content->get_label() ?></th>
    </tr>
    <tr>
        <th style="width: 150px"><?= $this->get_form()->lead->get_label() ?></th>
        <td><?= $this->get_form()->lead ?></td>
        <td rowspan="3" colspan="2"><?= $this->get_form()->content ?></td>
    </tr>
    <tr>
        <th style="width: 150px">&nbsp;</th>
        <td><?= $this->get_form()->active ?></td>
    </tr>

    <tr><th colspan="2">
            <h2>Properties</h2>
        </th>
    </tr>
<? for($i = 1; $i <= 5; ++$i): ?>
    <tr>
        <th style="width: 150px"><?= $this->get_form()->value_label($i) ?></th>
        <td style="width: 300px"><?= $this->get_form()->get_field("value_$i") ?></td>
        <th style="width: 150px"><?= $this->get_form()->string_label($i) ?></th>
        <td style="width: 300px"><?= $this->get_form()->get_field("string_$i") ?></td>
    </tr>

    <? endfor; ?>

    <tr>
        <th colspan="4">
            <?= $control ?>
        </th>
    </tr>
</table>
<?
    return ob_get_clean();

    }

    public function express(){
        throw new Exception(__METHOD__ . ': not implemented');
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ render_form_tag @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_render_form_tag = null;
    /**
     * @return class;
     */

    public function get_render_form_tag() { return $this->_render_form_tag; }

    public function set_render_form_tag($pValue) { $this->_render_form_tag = $pValue; }

}

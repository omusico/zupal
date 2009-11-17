<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Choice
 *
 * @author bingomanatee
 */
class Zupal_Fastform_Field_Choice
extends Zupal_Fastform_Field_Abstract {
// note -- type has to be set to one of these options

    const CHOICE_RADIO = 0;
    const CHOICE_DROPDOWN = 1;
    const CHOICE_LIST = 2;
    const CHOICE_CHECKBOX = 3;

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __construct @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Zupal_Fastform_Abstract $pForm
     * @param array $pFields
     * @param array $pData
     */
    public function __construct ($pName, $pLabel, $pValue, $pData, $pProps = array(), Zupal_Fastform_Abstract $pForm = NULL) {
        $this->set_type(self::CHOICE_DROPDOWN);
        parent::__construct($pName, $pLabel, $pValue, $pProps, $pForm);
        $this->set_data_source($pData);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int $pType
     * @return void
     */
    public function set_type ($pType) {
        switch((int) $pType):
            case self::CHOICE_CHECKBOX:
            case self::CHOICE_DROPDOWN:
            case self::CHOICE_LIST:
            case self::CHOICE_RADIO:
                return parent::set_type($pType);
                break;
            default:
                throw new Exception(__METHOD__ . ': bad value ' . $pType . ' passed');
        endswitch;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ tag_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function tag_name () {
        $pType = $this->get_type();

        switch((int) $pType):
            case self::CHOICE_CHECKBOX:
                return 'checkbox';
                break;

            case self::CHOICE_DROPDOWN:
                return 'select';
                break;

            case self::CHOICE_LIST:
                return 'select';
                break;

            case self::CHOICE_RADIO:
                return 'radio';
                break;
            default:
                throw new Exception(__METHOD__ . ': bad value ' . $pType . ' passed');
        endswitch;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_prop @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pKey
     * @param string $pValue
     * @return void
     */
    public function set_prop ($pKey, $pValue) {
        switch($pKey):
            case 'chosen_value':
                return $this->set_chosen_value($pValue);
                break;

            default:
                return parent::set_prop($pKey, $pValue);
        endswitch;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ my_props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return array
     */
    public function my_props () {
        if ($this->get_type() != self::CHOICE_CHECKBOX):
            return parent::my_props();
        endif;

        $out = array();

        if ($this->get_name()):
            $out['name'] = $this->get_name();
        endif;

        return $out;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function props () {
        $out = parent::props();
        unset($out['value']);
        if (($this->get_type() == self::CHOICE_CHECKBOX) && is_array($this->data())):
            $out['name'] = rtrim($out['name'], '[]') . '[]';
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ chosen_value @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_chosen_value = 1;
    /**
     * @return class;
     */

    public function get_chosen_value() { return $this->_chosen_value; }

    public function set_chosen_value($pValue) { $this->_chosen_value = $pValue; }

    public function __toString() {
        $data = $this->data();

        $pType = $this->get_type();
        $sep = $this->get_seperator();
        $properties = $this->render_props();

        ob_start();
        switch((int) $pType):
            case self::CHOICE_CHECKBOX:
                $value = $this->get_value();

                if ($data && is_array($data)):
                    foreach($data as $key => $label):
                        if (is_array($value)):
                            $checked = in_array($key, $value) ? ' checked="checked" ' : '';
                        else:
                            $checked = $key == $value ? ' checked="checked" ' : '';
                        endif;
                        ?><label><input type="checkbox" <?= $properties ?> <?= $checked ?> value="<?= $key ?>" /><?= $label ?></label><?= $sep ?><?
                    endforeach;
                else:
                    $checked = $value ? ' checked="checked" ' : '';
                        ?><label><input type="checkbox" <?= $properties ?> <?= $checked ?> value="<?= $this->get_chosen_value() ?>" /><?= $this->get_label() ?></label><?
                    endif;
                    break;

                case self::CHOICE_DROPDOWN:
                case self::CHOICE_LIST:
                    ?><select <?= $properties ?> >
                    <? foreach($data as $k => $v): ?><option value="<?= $k ?>" <?= $k == $this->get_value() ? ' selected="selected" ' : '' ?> ><?=$v ?></option><? endforeach; ?></select><?
                break;

            case self::CHOICE_RADIO:
                foreach($data as $key => $label):
                        ?><label><input type="radio" <?= $properties ?> /><?= $label ?></label><?= $sep ?><?
                    endforeach;
                    break;

                default:
                    ob_flush();
                    throw new Exception(__METHOD__ . ': bad value ' . $pType . ' passed');
            endswitch;
            return ob_get_clean();
        }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ express @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function express () {

        $pType = $this->get_type();
        $sep = $this->get_seperator();

        ob_start();
        switch((int) $pType):
            case self::CHOICE_CHECKBOX:
                $this->_express_checkbox();
                break;

            case self::CHOICE_DROPDOWN:
            case self::CHOICE_LIST:
                $this->_express_select();
                break;

            case self::CHOICE_RADIO:
                $this->_express_radio();
                break;

            default:
                ob_flush();
                throw new Exception(__METHOD__ . ': bad type value ' . $pType . ' passed');
        endswitch;
        $out = ob_get_clean();
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ express_radio @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    protected function _express_radio () {
        foreach($data as $key => $label):
            ?><label><input type="radio" <?= $properties ?> /><?= $label ?></label><?= $sep ?><?
        endforeach;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _express_select @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * //@TODO: differentiate between dropdowns and lists. 
     */
    protected function _express_select () {
        $sep = $this->get_seperator();
        $properties = $this->express_props();
        $key = $this->get_name() . '_key';
        $label = $this->get_name() . '_label';
        ?><select <?= $properties ?> >
        <?
            echo '<? foreach($' . $this->get_name() . '_options as ' . $key . ' => ' . $label . '): ?>', "\n";
            ?><option value="<?=  "<?= $key ?>" ?>" <?= "<?= $key == {$this->get_name()} ? ' selected=\"selected\" ' : '' " ?> >
        <?= "<?= $label ?>"?></option>
        <?= '<? endforeach; ?>'. "\n"; ?>
</select><?
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _express_checkbox() @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    protected function _express_checkbox() {
        $sep = $this->get_seperator();
        $properties = $this->express_props();
        $key = $this->get_name() . '_key';
        $label = $this->get_name() . '_label';
        $value = $this->get_value();
        $data = $this->data();

        if ($data && is_array($data)):

            echo '<? foreach($' . $this->get_name() . '_options as ' . $key . ' => ' . $label . '): ?>', "\n";
            if(is_array($value)): // note -- because the value gets plastered over this will NEVER get hit.
                $checked = '<?= in_array($' . $key . ', ' . $this->get_name() . ') ? \' checked="checked" \' : \'\' ?>';
                ?><label><input type="checkbox" <?= $properties ?> <?= $checked ?> value="<?= '<?=' . $key . '?>' ?>" />
                <?= '<?= ' . $label . '?>' ?></label><?= $sep ?><?
            else:
                $checked = '<?= $' . $this->get_name() . ' == $' . $this->get_name() . '_key ? \' checked="checked" \' : \'\' ?>';
                ?><label><input type="checkbox" <?= $properties ?> <?= $checked ?> value="<?= '<?=' . $key . '?>' ?>" />
                <?= '<?= ' . $label . '?>' ?></label><?= $sep ?><?
            endif;
            echo '<? endforeach; ?>', "\n";

        else:
            $checked = '<?= $' . $this->get_name() . ' ? \' checked="checked" \' : \'\' ?>';
            ?><label><input type="checkbox" <?= $properties ?> <?= $checked ?> value="<?= $this->get_chosen_value() ?>" />
            <?= $this->get_label() ?></label><?
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ seperator @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_seperator = '<br />';
    /**
     * @return string;
     */

    public function get_seperator() { return $this->_seperator; }

    public function set_seperator($pValue) { $this->_seperator = $pValue; }

}

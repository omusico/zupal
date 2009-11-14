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
class Zupal_Fastform_Choice
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
    public function __construct (Zupal_Fastform_Abstract $pForm, $pProps = NULL, $pBody = NULL) {
        $this->set_type(self::CHOICE_DROPDOWN);
        parent::__construct ($pForm, $pProps, $pBody);
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
                return self::set_type($pType);
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ chosen_value @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_chosen_value = 1;
    /**
     * @return class;
     */

    public function get_chosen_value() { return $this->_chosen_value; }

    public function set_chosen_value($pValue) { $this->_chosen_value = $pValue; }

    public function __toString() {
        $data = $this->data(); 
        if (!$data):
            $data = array($this->chosen_value() => $this->get_label());
        endif;

        $pType = $this->get_type();
        $sep = $this->get_seperator();
        $properties = $this->render_props();

        ob_start();
        switch((int) $pType):
            case self::CHOICE_CHECKBOX:
                foreach($data as $key => $label):
                ?><label><input type="checkbox" <?= $properties ?> /><?= $label ?></label><?= $sep ?><?
                endforeach;
                return ob_get_clean();
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
                return ob_get_clean();
                break;

            default:
                ob_flush();
                throw new Exception(__METHOD__ . ': bad value ' . $pType . ' passed');
       endswitch;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ seperator @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_seperator = '<br />';
    /**
     * @return string;
     */

    public function get_seperator() { return $this->_seperator; }

    public function set_seperator($pValue) { $this->_seperator = $pValue; }

}

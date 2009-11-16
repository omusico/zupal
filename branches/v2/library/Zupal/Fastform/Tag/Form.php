<?

class Zupal_Fastform_Tag_Form
extends Zupal_Fastform_Tag_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @return string
 */
    public function tag_name (){
        return 'form';
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ my_props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function my_props () {
        $out = parent::my_props();
        $out['method'] = $this->get_method();
        $out['action'] = $this->get_action();
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ method @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_method = 'POST';
    /**
     * @return class;
     */

    public function get_method() { return $this->_method; }

    public function set_method($pValue) {
        switch(strtoupper($pValue)):
            case 'GET':
                $pValue = 'GET';
                break;
            case 'POST':
                $pValue = 'POST';
                break;

            default:
                $pValue = 'POST';
        endswitch;
        $this->_method = $pValue;

    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ action @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_action = '';
    /**
     * @return class;
     */

    public function get_action() { return $this->_action; }

    public function set_action($pValue) { $this->_action = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_prop @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pKey
     * @param $pValue
     * @return <type>
     */
    public function set_prop ($pKey, $pValue) {
        switch(strtolower($pKey)):
            case 'action':
                return $this->set_action($pValue);
            break;
            default:
                return parent::set_prop($pKey, $pValue);
        endswitch;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_prop @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pKey
     * @param $pValue
     * @return scalar
     */
    public function get_prop ($pKey) {
        switch(strtolower($pKey)):
            case 'action':
                return $this->get_action();
            break;
            default:
                return parent::get_prop($pKey);
        endswitch;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_name = null;
    /**
     * @return class;
     */

    public function get_name() { return $this->_name; }

    public function set_name($pValue) { $this->_name = $pValue; }

}
<?

abstract class Zupal_Fastform_Tag_Abstract {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __construct @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Zupal_Fastform_Abstract $pForm
 * @param array $pFields
 * @param array $pData
 */
    public function __construct ($pProps = NULL, $pBody = NULL) {
        if ($pProps) $this->load_props($pProps);
        if ($pBody) $this->set_body($pBody);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public abstract function tag_name ();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ body @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_body = null;
    /**
     * @return class;
     */

    public function get_body() { return $this->_body; }

    public function set_body($pValue) { $this->_body = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ express_body @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function express_body () {
        return $this->get_body();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    protected $_props = array();

    public function set_prop( $pID, $pValue) {
        if (!$pID) return;

        switch (strtolower($pID)):

            case 'id':
                return $this->set_id($pValue);
                break;

            default:
                $this->_props[$pID] = $pValue;
        endswitch;
    }

    public function get_prop($pID) {
        switch (strtolower($pID)):

            case 'id':
                return $this->get_id();
                break;

            case 'name':
                return $this->get_name();
                break;

            default:
                return $this->_props[$pID];
        endswitch;
    }

    public function get_props() { return $this->_props; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return array
     */
    public function props () {
        $props = $this->get_props();
        $my_props = $this->my_props();
        return array_merge($props, $my_props);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ my_props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return array
     */
    public function my_props () {
        $out = array();

        if ($this->get_id()):
            $out['id'] = $this->get_id();
        endif;

        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ render_props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function render_props () {
        $out = ' ';

        $props = $this->props();
        foreach($props as $k => $v):
            $prop = " $k=\"$v\" ";
            $out .= $prop;
        endforeach;

        return $out;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ express_props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function express_props () {
        return $this->render_props();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param $pProps
     */
    public function load_props (array $pProps) {
        foreach($pProps as $key => $value):
            $this->set_prop($key, $value);
        endforeach;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __toString @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * NOTE: this method is a little "overly abstracted" -- in extending this class,
     * this method should be overridden and optimized for your specific field.
     * @return string
     */
    public function __toString() {

        $tag_content = $this->render_props();

        $body = $this->get_body();
        if (is_null($body) || $body == ''):
            $style = $this->style_property();
            return sprintf('<%s %s %s/>',  $this->tag_name(), $tag_content, $style);
        else:
            return $this->render_head($tag_content) . $body . $this->render_foot();
        endif;

    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ render_head @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $tag_content
     * @return string
     */
    public function render_head ($tag_content = NULL) {
        if (is_null($tag_content)):
            $tag_content = $this->render_props();
        endif;

        $style = $this->style_property();

        return sprintf('<%s %s, %s>', $this->tag_name(), $tag_content, $style);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ render_foot @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function render_foot () {
        return sprintf('</%s >', $this->tag_name());
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ id @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_id = null;
    /**
     * @return class;
     */

    public function get_id() { return $this->_id; }

    public function set_id($pValue) { $this->_id = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ template_render_method @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_template_render_method = null;
    /**
     * @return class;
     */

    public function get_template_render_method() { return $this->_template_render_method; }

    public function set_template_render_method($pValue) { $this->_template_render_method = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ data_source @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_data_source = null;
    /**
     * @return class;
     */

    public function get_data_source() { return $this->_data_source; }

    public function set_data_source($pValue) { $this->_data_source = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ type @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_type = null;
    /**
     * @return class;
     */

    public function get_type() { return $this->_type; }

    public function set_type($pValue) { $this->_type = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ data @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function data () {
        if ($source = $this->get_data_source()):
            if (is_array($source)):
                return $source;
            else:
                return $this->get_form()->get_data($source);
        endif;
        else:
            return NULL;
    endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ express @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function express () {
        $po = '<?';
        $poe = '<?=';
        $pc = '?>';

        $tag_content = $this->express_props();

        ob_start(); // tag proper
        $body = $this->express_body();
        if (is_null($body) || $body == ''):
            ?><<?= $this->tag_name() ?> <?= $tag_content ?>  /><?
        else:
            ?><<?= $this->tag_name() ?> <?= $tag_content ?> ><?= $body ?></<?= $this->tag_name() ?> ><?
        endif;

        $out = ob_get_clean();
        return $out;
    }



/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ styles @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_styles = array();

    public function set_style($pID = NULL, $pValue) {
            $this->_styles[$pID] = $pValue;
    }

    public function get_style($pID) { return $this->_styles[$pID]; }

    public function get_styles() { return $this->_styles; }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ style_property @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function style_property () {
        $out = '';

        $style_body = '';

        foreach($this->get_styles() as $key => $value):
            $style_body .= sprintf('%s: %s;', $key, $value);
        endforeach;

        return $style_body ? sprintf(' style="%s" ', $style_body) : '';
    }
}
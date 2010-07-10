<?php

/**
 * Description of ZendLayout
 *
 * @author bingomanatee
 */
class Page_View_ZendLayout
implements Zupal_View_ViewIF {
    /**
     * @return string
     */
    function render($pData) {
        // @TODO: more discriminatiaon for proper type
        $options = array();
        $layout_args = array();
        //@ TODO: establish layouts, layout folder, proper path to....
        $layout_args['layoutPath']  =  $pData['layout_path'];
        $layout_args['layout']      = $pData['page'];

        $layout = new Zend_layout($layout_args);

        if (array_key_exists('layoutPath', $layout_args)) {
            $layout_def = $this->_layout_def($layout_args);
            if (array_key_exists('page_vars', $layout_def)) {
                foreach($layout_def['page_vars'] as $var) {
                    if (is_array($var)) {
                        $name = $var['name'];
                        $default = array_key_exists('default', $var) ? $var['default'] : '';
                        if (array_key_exists('action', $var)) { // event variable
                            if (array_key_exists('args', $var)) {
                                $args = $var['args'];
                            } else {
                                $args = array();
                            }
                            $args['page_data'] = $pData;
                            $var_ev = Zupal_Event_Manager::event($var['action'], $args);

                            if ($var_ev->get_status() == Zupal_Event_EventIF::STATUS_DONE) {
                                $layout->getView()->$name = $var_ev->get_result();
                            } else {
                                $layout->getView()->$name = $default;
                            }
                        }
                    } else { // not array
                        $name = $var;
                        $default = '';
                        if (array_key_exists($name, $pData)) {
                            $layout->getView()->$name = $pData[$name];
                        } else {
                            $layout->getView()->$name = $default;
                        }
                    }

                }
            }
        }

        $out = $layout->render();
        return $out;
    }

    protected function _layout_def(array $pProps) {
        $layout = 'layout';
        $layoutPath = NULL;

        extract ($pProps);

        $def_path = $layoutPath . D . $layout . '_def.json';

        if (file_exists($def_path)) {
            return Zend_Json::decode(file_get_contents($def_path));
        } else {
            return array();
        }
    }
}

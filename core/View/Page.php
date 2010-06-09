<?php

class Zupal_View_Page
implements Zupal_Event_HandlerIF {

    public function __construct($options = null) {
        parent::__construct($options, FALSE);
    }

    /**
     * handles event
     * @param Zupal_Event_EventIF $pEvent
     */
    public function respond(Zupal_Event_EventIF $pEvent, $pPhase = Zupal_Event_HandlerIF::PHASE_ACTION) {
        switch($pEvent->get_action()) {
            case 'render':
                self::_handle_render($pEvent, $pPhase);
                break;
        }
    }

    protected function _handle_render(Zupal_Event_EventIF $pEvent, $pPhase = Zupal_Event_HandlerIF::PHASE_ACTION) {

        switch ($pPhase) {
            case Zupal_Event_HandlerIF::PHASE_PRE:
                $layout_args = new ArrayObject();
                $layout_args['layoutPath']  =  dirname(__FILE__) . D . 'templates';
                $layout_args['layout']      = 'default';
                $pEvent->args()->offsetSet('layout_args', $layout_args);
                break;

            case Zupal_Event_HandlerIF::PHASE_ACTION:

                break;

            case Zupal_Event_HandlerIF::PHASE_POST:
                $props = $pEvent->args()->offsetGet('layout_args')->getArrayCopy();

                $layout = new Zend_layout($props);

                if (array_key_exists('layoutPath', $props)) {
                    $layout_props = $this->layout_def($props);
                    if ($layout_props && array_key_exists('page_vars', $layout_props)) {
                        foreach($layout_props['page_vars'] as $var) {
                            if (is_array($var)) {
                                $name = $var['name'];
                                $default = array_key_exists('default', $var) ? $var['default'] : '';
                            } else {
                                $name = $var;
                                $default = '';
                            }

                            if ($pEvent->args()->offsetExists($name)) {
                                $layout->$name = $pEvent->args()->offsetGet($name);
                            } else {
                                $layout->$name = $default;
                            }

                        }
                    }
                }

                $pEvent->set_result($layout->render());
                $pEvent->set_status(Zupal_Event_EventIF::STATUS_DONE);
                break;
        }

    }

    protected function layout_def(array $pProps) {
        $layout = 'layout';
        $layoutPath = NULL;

        extract ($pProps);

        $def_path = $layoutPath . D . $layout . '_def.json';

        if (file_exists($def_path)) {
            return Zend_Json::decode($def_path);
        } else {
            return array();
        }
    }

}
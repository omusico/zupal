<?php

/**
 * Description of Item
 *
 * @author bingomanatee
 */
class Zupal_Event_Item {

    public $name;

    public $target;

    public $params;

    public $status;

    public $result;

    public $responses;

    public function __construct($pName, $pTarget, $pParams) {
        $this->name         = $pName;
        $this->target       = $pTarget;
        $this->params       = (array) $pParams;
        $this->status       = self::STATUS_WORKING;
        $this->result       = FALSE;
        $this->responses    = 0;
    }

    public function target_type(){
        return get_class($this->target);
    }

    const STATUS_WORKING    = 'working';
    const STATUS_DONE       = 'done';
    const STATUS_ERROR      = 'error';

}


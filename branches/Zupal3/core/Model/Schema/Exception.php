<?php
/**
 * Description of Exception
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Exception
extends Exception{

    public function __construct($message, $code) {
        $this->_valid = $code;
        parent::__construct($message);
    }
    
    private $_valid;
    public function validation_errors(){ return $this->_valid; }

    public function  __toString() {
        ob_start();
        ?>
<p><?= $this->getMessage() ?></p>
<dl>
    <?php foreach($this->_valid as $error):
            $value = $field = $message = '';
            extract($error);
            ?>
    <dt><?= $field ?></dt>
    <dd><?= $message ?> (Value = <?= $value ?>)</dd>
    <?php endforeach; ?>
</dl>
<?php
    return ob_get_clean();
    }
}

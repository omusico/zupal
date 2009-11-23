<?

class Model_Zupalbondtypes extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Model_DbTable_Zupalbondtypes';
    }

    public static function getInstance() {
        if ($pReload || is_null(self::$_Instance)):
            // process
                self::$_Instance = new self();
            endif;
            return self::$_Instance;
    }

    public function get($pID, $pLoadFields) {
        $out = new self($pID);
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }

}

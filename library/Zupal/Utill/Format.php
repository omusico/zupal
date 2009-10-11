<?

class Zupal_Util_Format {

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ TOINT @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public static function number_format($pArray, $pPlaces = 0) {
        $out = array();
        foreach ($pArray as $k => $v) { $out[$k] = is_numeric($v) ?  number_format($v, $pPlaces) : $v; }
        return $out;
    }

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ TOINT @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public static function dollar($pArray, $pPlaces = 0) {
        $out = array();
        foreach ($pArray as $k => $v) { $out[$k] = is_numeric($v) ?
                CPF_Util_Format::dollar($v, TRUE, $pPlaces) : $v; }
        return $out;
    }
}
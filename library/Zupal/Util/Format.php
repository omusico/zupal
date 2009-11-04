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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ PERCENT @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * Our standard for percents. Can accept floats or integral (1-100) input.
     * By default shows no decimals, but can be adusted parametrically.
     * renders following percent sign.
     *
     * @param numeric $pNum
     * @param boolean $pisInt
     * @param posint $pNum_decimals
     * @return string
     */
    public static function percent($pNum, $pisInt = TRUE, $pNum_decimals = 0) {
        if (!$pisInt):
            $pNum *= 100;
        endif;
        return number_format($pNum, $pNum_decimals) . '%';
    }

    public static function percent_decimal($pNum, $pNum_decimals = 0) {
        return self::percent($pNum, FALSE, $pNum_decimals);
    }
}
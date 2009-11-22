<?php
class Zupal_Util_Array {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ preg_keys @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param array $pArray
 * @return array
 */
    public static function mod_keys (array $pArray, $pPrefix = '', $pSuffix = '') {
        $out = array();
        foreach($pArray as $key => $value):
            $out[$pPrefix . $key . $pSuffix] = $value;
        endforeach;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ diff @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public static function diff($a1, $a2) {
        foreach($a1 as $k => $v):
            if (array_key_exists($k, $a2)):
                $a1[$k] -= $a2[$k];
        endif;
        endforeach;
        return $a1;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ TOINT @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public static function toint($pArray) {
        $out = array();
        foreach ($pArray as $k => $v) { $out[$k] = is_numeric($v) ?  (int)$v : $v; }
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ alias @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public static function alias(array $pArray, array $pMap) {
        foreach($pMap as $pair):
            list($from, $to) = $pair;
            $pArray[$to] = $pArray[$from];
            unset($pArray[$from]);
        endforeach;

        return $pArray;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ FIRST @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public static function first ($pArray) {
        if (! count($pArray)) :
            return NULL;
        endif;
        return array_shift($pArray);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ string_values @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public static function string_values(array $pValues) {
        foreach($pValues as $i => $value) $pValues[$i] = (string) $value;
        return $pValues;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ scrub @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public static function scrub(array $pArray, $pScrub_zeros = FALSE, $pScrub_spaces = TRUE) {
        $array = array_unique($pArray);
        $out = array();
        foreach($array as $i => $value):
            if (is_null($value)):
                unset($array[$i]);
            elseif ($pScrub_zeros && is_numeric($value) && $value == 0):
                unset($array[$i]);
            elseif ($pScrub_spaces && (!strcasecmp('', $value))):
                unset($array[$i]);
            else:
                $out[] = $value;
        endif;
        endforeach;
        return $out;
    }



    public static function first_key($pArray) {
        $keys = array_keys($pArray);
        return array_shift($keys);
    }

    public static function last_key($pArray) {
        $keys = array_keys($pArray);
        return array_pop($keys);
    }

    public static function last ($pArray) {
        if (! count($pArray)) :
            return NULL;

        endif;
        return array_pop($pArray);
    }
    public static function key_is_first ($pKey, $pArray) {
        if (! count($pArray)) :
            return NULL;

        endif;
        $ak = array_keys($pArray);
        return ($pKey == array_shift($ak)) ? TRUE : FALSE;
    }
    public static function key_is_last ($pKey, $pArray) {
        if (! count($pArray)) :
            return NULL;

        endif;
        return ($pKey == array_pop(array_keys($pArray))) ? TRUE : FALSE;
    }
    public static function is_first ($pValue, $pArray) {
        return $pValue == array_shift($pArray);
    }
    public static function is_last ($pValue, $pArray) {
        return $pValue == array_pop($pArray);
    }
    /**
     * finds the keys before and/or after the passed key in the array.
     * If the key is in the array returns sets of three keys -- the one before, equal to and after the passed value.
     * if as_pairs is true the three value arrays are split into two two-key values --
     * the one before and equal to, and equal to and after the key.
     *
     * If the value is not in the array, a two value pair of the values before and after the value.
     *
     * Note -- if the value is greater than or less than all the keys,
     * the array ('min' => $key) or ('max' => $key) is returned.
     *
     * @param scalar $pKey
     * @param array $pArray
     * @param bool $pAs_Pairs
     * @return array -- either arrays of 2 -3 values or arrays of 2-value pairs if the last param is true
     */
    public static function get_array_neighbor_keys ($pKey, $pArray, $pAs_Pairs = FALSE) {
        $keys = array_keys($pArray);
        sort($keys);
        if (array_key_exists($pKey, $keys)) :
            $place = array_search($pKey, $keys);
            if (count($keys) < 3) :
                $set = $keys;
            elseif (self::is_first($pKey, $keys)) :
            //	echo "getting first set for $pKey <br />";
                $set = array_slice($keys, 0, 2);
            elseif (self::is_last($pKey, $keys)):
                $set = array_slice($keys, count($keys) -2, 2);
            else :
            //	echo "getting set for $pKey <br />";
                $set = array_slice($keys, $place - 1, 3);
            endif;

            if ($pAs_Pairs) :
                $pairs = array();
                while (count($set) > 1) :
                    $range = array_values($set);
                    $pairs[] = array_slice($range, 0, 2);
                    array_shift($set);
                endwhile;
                return $pairs;
            else :
                return $set;
        endif;

        elseif ($pKey < min($keys)):
            return array('min' => $pKey);
        elseif ($pKey > max($keys)):
            return array('min' => $pKey);
        else: //@TODO: binary search

            $before = NULL;
            $after = NULL;

            foreach($keys as $test_key):
                if ($test_key < $pKey):
                    $before = $test_key;
                else:
                    $after = $test_key;
                    break;
            endif;
            endforeach;

            return array($before, $after);

    endif;
    }

    /**
     * returns random elements from an array -- can return duplicates.
     * @param array $pArray
     * @param int $pValues
     * @return array
     */
    public static function random (array $pArray, $pValues = 1) {
        $out = array();
        for ($i = 0; $i < $pValues; ++ $i) :
            $key = array_rand($pArray, 1);
            $out[] = $pArray[$key];
        endfor;
        return $out;
    }

    public static function random_set (array $pArray, $pValues = 1) {
        $out = array();
        $keys = array_rand($pArray, $pValues);
        if(!is_array($key)) $key = array($key);
        foreach($keys as $k):
            $out[] = $pArray[$k];
        endforeach;
        return $out;
    }

    /**
     * Test range is for situations in which being out of range is an automatic fatal error.
     *
     */
    public static function test_range ($pNum, $pFirst, $pLast, $pThrow_exception = TRUE) {
        if ($pFirst > $pLast):
            $n = $pLast;
            $pLast = $pFirst;
            $pFirst = $n;
        endif;

        if (($pNum < $pFirst) || ($pNum > $pLast)) :
            if ($pThrow_exception) :
                throw new CPF_Exception_Array("Number out of range: $pNum in ($pFirst .. $pLast)");

            endif;
            return FALSE;
        else :
            return TRUE;
    endif;
    }
    /**
     * in_range returns whether the number is inside the range.
     * NOTE: unlike test_range, it doesn't fail if you are out of range.
     * @return boolean
     */
    public static function in_range ($pNum, $pFirst, $pLast,  $pClip_end = FALSE) {
        if ($pFirst > $pLast):
            $n = $pLast;
            $pLast = $pFirst;
            $pFirst = $n;
        endif;

        if ($pNum < $pFirst) :
            return FALSE;
        elseif ($pClip_end):
            return ($pNum < $pLast);
        else:
            return $pNum <= $pLast;
    endif;
    }
    /**
     * Limit input to the given range through min/max logic
     *
     * @param number $pValue
     * @param number $pMin
     * @param number $pMax
     * @return number
     */
    public static function force_range ($pValue, $pMin, $pMax) {
        if ($pMin > $pMax):
            $n = $pMax;
            $pMax = $pMin;
            $pMin = $n;
        endif;

        if ($pValue < $pMin) :
            return $pMin;
        elseif ($pValue > $pMax) :
            return $pMax;
        else :
            return $pValue;
    endif;
    }

    public static function distribute_step($pStart, $pEnd, $pIncrement = 1, $pForce_end = TRUE) {
        if ($pIncrement == 0):
            throw new CPF_exception(__METHOD__ . ": distributing with zero increment : inc = $pincrement, start= $pStart, end = $pEnd");
        elseif (($pIncrement < 0) && ($pEnd > $pStart)):
            throw new CPF_exception(__METHOD__ . ": distributing with bad increment : inc = $pincrement, start= $pStart, end = $pEnd");
        elseif (($pIncrement > 0) && ($pEnd < $pStart)):
            throw new CPF_exception(__METHOD__ . ": distributing with bad increment : inc = $pincrement, start= $pStart, end = $pEnd");
        endif;

        $distribution = array();
        for ($i = $pStart; $i <= $pEnd; $i += $pIncrement):
            $distribution[] = $i;
        endfor;

        if ($pForce_end && ($i < $pEnd)):
            $distribution[] = $pEnd;
        endif;

        return $distribution;
    }

    public static function distribute_values ($start, $end, $increments =  1, $to_int = FALSE) {
        if ($increments < 3) :
            return array($start , $end);

        endif;
        $distribution = array();
        $range = $end - $start;
        for ($i = 0; $i < ($increments - 1); ++ $i) :
            $value = $start + (($range * $i) / ($increments - 1));
            if ($to_int) :
                $value = round($value);

            endif;
            $distribution[] = $value;
        endfor;
        $distribution[] = $end;
        return $distribution;
    }
    /**
     * Multiplies all numeric values in an array by the scalar.
     * Accepts a mixed array but insists on a numeric scalar.
     *
     * @param array $pArray
     * @param numeric $pScale
     * @return array
     */
    public static function scale_array($pArray, $pScale = NULL) {
        if (is_null($pScale) || (!is_numeric($pScale))):
            CPFdebug_Log::message('terminate: bad scalar ' . $pScale, __FILE__, __METHOD__, __LINE__);
            die('Bad scalar ' . print_r($pScale, 1) . ' passed to ' . __METHOD__);
        endif;

        foreach($pArray as $i => $value):
            if (is_numeric($value)):
                $pArray[$i] = $value * $pScale;
        endif;
        endforeach;

        return $pArray;
    }
    /**
     * Sets, adds, or decrements an array item by a numeric value. Handles summary logic.
     *
     * @param array $array
     * @param scalar $item
     * @param numeric $value
     * @param int $term -- indicates to subtract term rather than add it.
     * @return array -- though the array is modified as a reference anyway.
     */
    public static function modify_array_item(&$array, $item, $value, $add = TRUE) {
        if (!$add):
            $array[$item] = $value;
        else:
            if ($add < 0): // the subtract option
                $value *= -1;
            endif;

            if (isset($array[$item]) && is_numeric($array[$item])):
                $array[$item] += $value;
            else:
                $array[$item] = $value;
        endif;
        endif;
        return $array;
    }
    /**
     * adds all the numeric values of the arrays, using keys from the first array.
     * non-numeric/missing values are skipped,
     * as are values that are not in the first array.
     * Note -- the first array value doesn't have to be numeric to be used as a key placeholder.
     * however it is the template for all other arrays -- only values indexed in the first array
     * are accounted for in the final total.
     *
     * If a single array is passed, this method permutes it as an array of arrays into a single recursion user_func call.
     * @return array
     */

    public static function add_arrays() {
        $arrays = func_get_args();
        if (func_num_args() == 1):
            $arrays = func_get_arg(0);
            $arrays = array_values($arrays);
        endif;

        $first_array_keys = array_keys($arrays[0]);
        $out = array();

        foreach($first_array_keys as $key):
            $out[$key] = 0;
            foreach ($arrays as $array):
                if (array_key_exists($key, $array) && is_numeric($array[$key])):
                    $out[$key] += $array[$key];
            endif;
            endforeach;
        endforeach;
        return $out;
    }

    public static function within($pRange, $pValue, $pOtherValue) {
        if (!is_numeric($pValue)):
            die (__METHOD__ . "can't find range for non numeric value $pValue");
        elseif (!is_numeric($pOtherValue)):
            die (__METHOD__ . "can't find range for non numeric value $pOtherValue");
        endif;

        $range = abs($pOtherValue - $pValue);
        if ($range > abs($pRange)):
            return FALSE;
        else:
            return TRUE;
    endif;
    }

    public static function stripslashes($value) {
        if(is_array($value)):
            $call = array('Zupal_Util_Array', 'stripslashes');
            $out = array_map($call, $value);
        else:
            $out = stripslashes($value);
        endif;
        return $out;
    }
    
}
/*
 * function stripslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

    return $value;
}
 */
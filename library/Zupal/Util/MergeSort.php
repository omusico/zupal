<?php
/* 
 * Calling merge_sort() on this class will sort its members using iterative
 * binary comparison. It is in other wayss an extended ArrayObject.
 */

/**
 * Description of Zupal_Util_MergeSort
 *
 * @author bingomanatee
 */
class Zupal_Util_MergeSort
extends ArrayObject {

    public function __construct($array) {
        /**
         * Validating that content is a collection of numbers.
         */
        if (is_array($array)):
            $array = array_values((array) $array); // enforce integral, sequential kesy.
        elseif(is_numeric($array)):
            $array = array($array);
        elseif ($array instanceof ArrayObject):
            $arrray = $array->ArrayCopy();
        else:
            $array = array();
        endif;
        $input = array();
        // non numeric data discarded
        foreach($array as $v):
            if (is_numeric($v)):
                $input[] = $v;
            endif;
        endforeach;

        parent::__construct($input);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ merge_sort @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * sort the contents of this array iteratively 
     */
    public function merge_sort () {

        if (($count = count($this)) < 2):
            return;
        endif;

        $mid = floor($count/ 2);
        $copy = $this->getArrayCopy();

        $baa = array_slice($copy, 0, $mid);
        $batch_a = new Zupal_Util_MergeSort($baa);
        $batch_a->merge_sort();

        $bab = array_slice($copy, $mid);
        $batch_b = new Zupal_Util_MergeSort($bab);
        $batch_b->merge_sort();

        $bfirst = $batch_b->first();

        $alast = $batch_a->last();      
        
        if ($alast > $bfirst):
            $merged = self::merge($batch_a, $batch_b);
        else:
            $merged = self::append_array($batch_a, $batch_b);
        endif;
        $this->exchangeArray((array) $merged);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ first @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return int
     */
    public function first () {
        if (count($this)):
            return $this[0];
        else:
            return NULL;
    endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ last @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return int
     */
    public function last () {
        if (count($this)):
            return $this[count($this) - 1];
        else:
            return NULL;
    endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ append @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Zupal_Util_MergeSort $pBatch_a
     * @param Zupal_Util_MergeSort $pBatch_b
     * @return Zupal_Util_MergeSort
     */
    public static function append_array(Zupal_Util_MergeSort $pBatch_a, Zupal_Util_MergeSort $pBatch_b) {
        if (!$pBatch_a):
            return $pBatch_b;
        endif;

        if (!$pBatch_b):
            return $pBatch_a;
        endif;
        $bac = $pBatch_a->getArrayCopy();
        $bbc = $pBatch_b->getArrayCopy();
        $out = new self(array_merge($bac, $bbc)); return $out;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ merge @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * note -- merge ASSUMES inputs have been sorted!
     * @param Zupal_Util_MergeSort $pBatch_a
     * @param Zupal_Util_MergeSort $pBatch_b
     * @return Zupal_Util_MergeSort
     */
    public static function merge (Zupal_Util_MergeSort $pBatch_a, Zupal_Util_MergeSort $pBatch_b) {
        $out = new Zupal_Util_MergeSort();

        if (!$pBatch_a):
            return $pBatch_b;
        elseif (!$pBatch_b):
            return $pBatch_a;
        endif;

        while ($acount = count($pBatch_a) && ($bcount = count($pBatch_b))):
            if ($pBatch_a->first() > $pBatch_b->first()):
                $out->push($pBatch_b->shift());
            else:
                $out->push($pBatch_a->shift());
            endif;
        endwhile;

        if (count($pBatch_a)):
            $out = self::append_array($out, $pBatch_a);
        elseif (count($pBatch_b)):
            $out = self::append_array($out, $pBatch_b);
        endif;

        return $out;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ unshift @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return variant
     */
    public function shift () {
        if (count($this)):
            $array = (array) $this;
            $out = array_shift($array);
            $this->exchangeArray($array);
            return $out;
        else:
            return NULL;
    endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pop @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return int
     */
    public function pop () {
        if ($count = count($this)):
            $out = $this->last();
            unset($this[$count - 1]);
        else:
            $out = NULL;
        endif;
        return $out;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ push @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param integer $pInt
     */
    public function push ($pInt) {
        $this->append($pInt);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offsetSet @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param scalar $pKey
     * @param variant $pValue
     * @return void
     */
    public function offsetSet ($index, $newval) {
        if (is_null($pKey)):
            $pKey = count($this);
        endif;
        return parent::offsetSet($index, $newval);
    } 
}

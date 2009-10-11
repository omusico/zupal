<?

class Zupal_Util_Stat
{
    	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ AVERAGE @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * Note: this method averages either all the arguments,
	 * or if the first argument is an array, the elements of the first argument.
	 * It presumes all the arguments are numeric; rather, any non-numeric value = 0.
	 * so average(2, 2, 'dog', 'dog') == 1.
	 * If for some reason you believe
	 * that some of them might be null or non-numeric, use average_nums.
	 *
	 * @param unknown_type $args
	 * @return unknown
	 */
	public static function average($args)
	{
		if (!is_array($args)):
			$args = func_get_args();
		endif;

		if (count($args) == 0):
			return 0;
		endif;

		return array_sum($args) / count($args);
	}

	public static function average_nums($args)
	{
		if (!is_array($args)):
			$args = func_get_args();
		endif;

		foreach($args as $i => $value):
			if (!is_numeric($value)):
				unset($args[$i]);
			endif;
		endforeach;

		return self::average($args);
	}
}
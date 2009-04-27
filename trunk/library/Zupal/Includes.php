<?php

class Zupal_Includes
{
	public static function add()
	{
		$args = func_get_args();

		if (is_array($args[0]))
		{
			$args = $args[0];
		}

		$include_paths = explode(PS, get_include_path());
		$include_paths = array_merge($include_paths, $args);
		$include_paths = array_unique($include_paths);

		set_include_path(join(PS, $include_paths));
	}
}
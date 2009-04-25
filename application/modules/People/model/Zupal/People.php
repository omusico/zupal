<?php

/**
 * Imeplements by extension Zupal_Node, Zupal_Domain
 */
class Zupal_People extends Zupal_Node_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @see CPF_Formset_Domain::get_table_class()
	 *
	 */
	protected function tableClass()
	{
		return 'Zupal_Table_People';
	}

	/**
	 * @see CPF_Formset_Domain::get()
	 *
	 * @param unknown_type $pID
	 * @return CPF_Formset_Domain
	 *
	 */
	public function get ($pID)
	{
		return new Zupal_People($pID);
	}

}
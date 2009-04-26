<?php

/**
 * this class represents the domain class for the nodes table.
 * as such it does NOT implement Zupal_Node_INode, but it is a component
 * of any domain that implements Zupal_Node_INode.
 */

class Zupal_Nodes Extends Zupal_Domain_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @see CPF_Formset_Domain::get_table_class()
	 *
	 */
	public function tableClass ()
	{
		return preg_replace('~^Zupal_~', 'Zupal_Table_', get_class($this));
	}

	/**
	 * @see CPF_Formset_Domain::get()
	 *
	 * @param unknown_type $pID
	 * @return CPF_Formset_Domain
	 */
	public function get ($pID)
	{
		return new Zupal_Nodes($pID);
	}
}
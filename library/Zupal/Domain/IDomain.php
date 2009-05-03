<?php


/**
 * A basic information requestor.
 * Compatible (mostly) with Doctrine and Zend_Db
 * @author daveedelhart
 */
interface Zupal_Domain_IDomain {

	public function save();
	
	/**
	 * A test to determine if the identity of this record is in the databsae.
	 * True in two cases: the record has not been saved, or it has been deleted.
	 */

	public function delete();

	/**
	 * A simple self-contained find method
	 * that finds records from the governing table
	 * based on a set of parameter/value matches
	 * passed as an array.
	 *
	 * if the optional sort field is absent,
	 * the results are returned in arbitrary order.
	 *
	 * Compound criteria intersect ("AND").
	 *
	 * This method is intentionally limited to single table
	 * intersect based simple field based retrieval.
	 * More advanced queries are not a part of this interface.
	 *
	 * @param scalar[] $searchCrit
	 * @param string $sort
	 * @return Zupal_Content_IDomain[]
	 */

	public function find(array $searchCrit = NULL, $sort = NULL);

	/**
	 * returns a single record matching the search crit. 
	 * If several records match the crit wil return the first one based on the sort param. 
	 * 
	 * @param scalar[] $searchCrit
	 * @param string $sort 
	 * @return Zupal_Content_IDomain
	 */
	public function findOne(array $searchCrit = NULL, $sort = NULL);

	public function findAll($pSort = NULL);

	/**
	 * @return array;
	 */
	public function toArray(); 
}

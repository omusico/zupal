<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *	Note that the individual elements can be passed as strings,
 *  IDs, or Zupal_IPlace_item elements. 
 * 
 *  They return Zupal_IPlace_Item elements which can boil down to strings.
 *
 * @author daveedelhart
 */
interface Zupal_Place_IPlace
extends Zupal_Domain_IDomain {
	/**
	 * @returns a stdClass with a lat and long prop
	 */
	public function getCoords();
	/**
	 * an array, class with lat/long params, or a pair of floats.
	 */
	public function setCoords($pVal1, $pVal2 = NULL);

	/**
	 * @return Zupal_Place_IItem
	 */
	public function getAddress();
	public function setAddress($pAddress);

	/**
	 * @return Zupal_Place_IItem
	 */
	public function getCity();
	public function setCity($pCity);

	/**
	 * @return Zupal_Place_IItem
	 */
	public function getState();
	public function setState($pState);

	/**
	 * @reutrn Zupal_Place_IItem
	 */
	public function getPostal();
	public function setPostal($pPostal);

	/**
	 *
	 * @param string $pSeperator
	 */
	public function block($pSeperator = "\n");

	/**
	 * @return IPlace[]
	 */
	public function findLikeMe();

	/**
	 * @return IPlace[]
	 */
	public function findNearMe($pKilometers, $pMax = NULL);

}
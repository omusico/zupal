<?php

/**
 * An item that exists in an ordered series.
 * A Version item has introspection to the entire series of items it is a member of.
 * (there is no VersionMember.)
 * @author daveedelhart
 */
interface Zupal_Interface_Version {
    //put your code here

	/**
	 * The version of an item.
	 * SHOULD NOT CHANGE for a specific item.
	 * @return int;
	 */
	public function version();

	/**
	 * The date the version was saved
	 * @return GMT timestamp
	 */
	public function version_date();

	/**
	 * the entire series of versioned peers.
	 *
	 * @return Zupal_Interface_Version[]
	 */

	public function versions();

	/**
	 * A test to see if this is the latest version.
	 * @return boolean;
	 */
	public function is_latest();

	/**
	 * the latest version (may or not return itself)
	 * @return Zupal_Interface_Version
	 */
	public function latest_version();

	/**
	 * returns one of the below constants.
	 */
	public function version_status();

	const VERSION_OLD = 0;
	const VERSION_CURRENT = 1;
	const VERSION_REVERT = 2;

	/**
	 * If this verison is a revert, the ID of the original version
	 * @return int;
	 */
	public function reverted_from();

	/**
	 * the ID of the version author.
	 * @return int;
	 */
	public function version_author();

}


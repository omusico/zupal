<?php

Interface Zupal_Module_IModule
{

/**
 * Does any required setup; returns true on success.
 *
 * @return boolean
 */
	function install();

/**
 * Does any required setup; returns true on success.
 * NOTE: it is reccomended that you do NOT destroy any tables/records upon unistall.
 * You should proabably flag any module dependant nodes as deleted though. 
 *
 * @return boolean
 */
	function unistall();

/**
 * Detects any requirements for successful installtion.
 * 
 * @return boolean
 */
	function can_install();

}
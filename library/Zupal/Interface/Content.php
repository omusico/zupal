<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author daveedelhart
 */
interface Zupal_Interface_Content {
    
	/**
	 * A string labelf or the content. 
	 * @return string
	 */
	public function title();

	/**
	 * A shorter title, for menus and lists.
	 */
	public function brief_title();

	/**
	 * Content, in HTML
	 */
	public function text();

	/**
	 * A summary, useful for search listings, front page content, sidebars, aggregation, etc.
	 * Can be equal to text(), or blank.
	 */
	public function brief_text();

	/**
	 * Allows for coded non-marked-up text including raw ASCII, wiki, etc.
	 * If you don't filter content, can be identical to text().
	 */
	public function raw_text();


	/**
	 * the expected or past date to expose the content.
	 * Can be left blank for content still in review.
	 *
	 * @return GMT timestamp
	 */
	public function publish_date();

	/**
	 * the final expected date to expose the content.
	 * Can be left blank for persistent content
	 * or content in review.
	 *
	 * @return GMT tiestamp
	 */
	public function unpublish_date();

	/**
	 * The user ID of the author (or whoever claims responsibility for the content).
	 * Can be left blank for anonymous messages, threats, and ameteur porn.
	 */
	public function author_id();

}


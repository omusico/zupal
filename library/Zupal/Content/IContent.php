<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author daveedelhart
 */
interface Zupal_Content_IContent {
    
	/**
	 * A string labelf or the content. 
	 * @return string
	 */
	public function title();

	/**
	 * A shorter title, for menus and lists.
	 */
	public function shortTitle();

	/**
	 * Content, in HTML
	 */
	public function text();

	/**
	 * A summary, useful for search listings, front page content, sidebars, aggregation, etc.
	 * Can be equal to text(), or blank.
	 */
	public function shortText();

	/**
	 * Allows for coded non-marked-up text including raw ASCII, wiki, etc.
	 * If you don't filter content, can be identical to text().
	 */
	public function rawText();

	/**
	 * determines whether the content is within the publish dates and is public. 
	 * @return boolean
	 */
	public function isPublished();

	/**
	 * the expected or past date to expose the content.
	 * Can be left blank for content still in review.
	 *
	 * @return GMT timestamp
	 */
	public function publishDate();

	/**
	 * the final expected date to expose the content.
	 * Can be left blank for persistent content
	 * or content in review.
	 *
	 * @return GMT tiestamp
	 */
	public function unpublishDate();

	/**
	 * The domain object of the author.
	 * @return Zupal_Content_Domain
	 */
	public function author();
	public function authorId();

	/**
	 * Indicates this content item has been approved for general view.
	 * if public is not public, only authorized people can view it -- in general, the admins,
	 * but ultimately determined by the role system.
	 * @return boolean
	 */
	public function isPublic ();

}


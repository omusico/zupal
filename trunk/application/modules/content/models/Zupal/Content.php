<?php
/**
 * Note -- this content is NOT a versioned model!
 * @TODO write a versioned flavor. 
 */

class Zupal_Content extends Zupal_Node_Abstract
implements Zupal_Content_IContent
{

/* @@@@@@@@@@@@@@@@@@@@@@ CONTENT INTERFACE @@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * A string labelf or the content.
	 * @return string
	 */
	public function title()
	{
		return $this->title;
	}

	/**
	 * A shorter title, for menus and lists.
	 */
	public function shortTitle()
	{
		return $this->short_title;
	}

	/**
	 * Content, in HTML
	 */
	public function text()
	{
		return $this->text;
	}

	/**
	 * A summary, useful for search listings, front page content, sidebars, aggregation, etc.
	 * Can be equal to text(), or blank.
	 */
	public function shortText()
	{
		return $this->short_text;
	}

	/**
	 * Allows for coded non-marked-up text including raw ASCII, wiki, etc.
	 * If you don't filter content, can be identical to text().
	 */
	public function rawText()
	{
		return $this->raw_text;
	}

	/**
	 * determines whether the content is within the publish dates and is public.
	 * @return boolean
	 */
	public function isPublished()
	{
		return TRUE;
		//@TODO: use publish dates to reflect cycle
	}

	/**
	 * the expected or past date to expose the content.
	 * Can be left blank for content still in review.
	 *
	 * @return GMT timestamp
	 */
	public function publishDate()
	{
		return $this->publish_date;
	}

	/**
	 * the final expected date to expose the content.
	 * Can be left blank for persistent content
	 * or content in review.
	 *
	 * @return GMT tiestamp
	 */
	public function unpublishDate()
	{
		return $this->unpublish_date;
	}

	/**
	 * The domain object of the author.
	 * @return Zupal_Content_Domain
	 */
	public function author()
	{
		@TODO;
	}

	public function authorId()
	{
		return $this->author_id;
	}

	/**
	 * Indicates this content item has been approved for general view.
	 * if public is not public, only authorized people can view it -- in general, the admins,
	 * but ultimately determined by the role system.
	 * @return boolean
	 */
	public function isPublic ()
	{
		return $this->is_public;
	}

}
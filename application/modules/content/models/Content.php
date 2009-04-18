<?php

class Content extends BaseContent
implements Zupal_Content_IContent, Zupal_IDomain, Zupal_Node_INode
{

/* @@@@@@@@@@@@@@@@@@@@@@ NODE INTERFACE @@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 * @return int
	 */
	public function nodeId()
	{
		return $this->node_id;
	}

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
		if (is_null($this->publishDate()))
		{

		}
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

/* @@@@@@@@@@@@@@@@@@@ DOMAIN INTERFACE @@@@@@@@@@@@@@@@@@@ */
	
	public function find(array $searchCrit, $sort = NULL)
	{
		@TODO;
	}

	/**
	 * returns a single record matching the search crit.
	 * If several records match the crit wil return the first one based on the sort param.
	 *
	 * @param scalar[] $searchCrit
	 * @param string $sort
	 * @return Zupal_Content_IDomain
	 */
	public function findOne(array $searchCrit, $sort = NULL)
	{
		@TODO;
	}


}
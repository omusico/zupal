<?php

class Zupal_Menu_Item
{
	public $module = '';
	public $controller = '';
	public $action = '';
	public $params = array();
	public $label = '';

	public function __construct($pLabel, $pModule, $pController = 'index', $pAction = 'index', $pParams = NULL)
	{
		$this->module = strtolower($pModule);
		$this->controller = $pController;
		$this->action = $pAction;
		if (is_array($pParams))
		{
			$this->params = $pParams;
		}

		if ($pLabel == '')
		{
			$pLabel = ucFirst($pAction) . ': ' . ucfirst($this->action) . ' ' . ucfirst($pController);
		}

		$this->label = $pLabel;
	}

	const LINK = '<a href="%s">%s</a>';

	public function __toString()
	{
		$url = DS  . ltrim(DS, Zend_Controller_Front::getInstance()->getBaseUrl());
		if (($this->module == '') || ($this->module == 'default'))
		{
			$path = array($this->controller, $this->action);
		}
		else
		{
			$path = array($this->module, $this->controller, $this->action);
		}

		foreach($this->params as $k => $v)
		{
			$path[] = $k;
			$path[] = (string) htmlentities($v);
		}

		$url = join(DS, $path);

		return sprintf(self::LINK, $url, $this->label);

	}

}
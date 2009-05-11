<?php
// 415 913 7321
class Zupal_Menu_Item
{
	public $module = '';
	public $controller = '';
	public $action = '';
	public $params = array();
	public $label = '';
	public $list_class = '';
	public $submenu = '';

	private static $_item_iter = 0;
	public function __construct($pLabel, $pModule = '', $pController = 'index', $pAction = 'index', $pParams = NULL)
	{
		if (is_array($pLabel)):
			foreach($pLabel as $key => $value):
				switch (strtolower($key)):
					case 'label':
						$pLabel = $value;
						break;
					
					case 'module':
						$pModule = $value;
						break;

					case 'controller':
						$pController = $value;
						break;
					
					case 'action':
						$pAction = $value;
						break;

					case 'list_class':
						$this->list_class = $value;
						break;
					
					default:
						if (!is_array($pParams)):
							$pParams = array($key => $value);
						else:
							$pParams[$key] = $value;
						endif;
				endswitch;
			endforeach;
		endif;

		if (is_array($pLabel)) $pLabel = '' ;

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

	const LINK = '<a href="%s">%s</a>%s';

	public function __toString()
	{
		$url = ZUPAL_BASEURL . DS;
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

		$url .= join(DS, $path);

		return sprintf(self::LINK, $url, $this->label, $this->submenu);

	}

}
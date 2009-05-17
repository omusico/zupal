<?

class Zupal_Control_Link
extends Zupal_Control_Abstract
{

	public function __construct($pLabel, $pParams)
	{

		parent::load($pParams);

		$this->set_label($pLabel);
	}

	public function __toString()
	{
		return $this->as_link();
	}

}
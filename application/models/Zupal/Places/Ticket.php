<?php

class Zupal_Places_Ticket
extends Zupal_View_Ticket
{

	public function __construct($pPlace)
	{
		$this->set_title('Place &quot;' . $pPlace->get_name() . '&quot; (' . $pPlace->identity() . ')');

		$this->set_value('Name', $pPlace->get_name());

		$this->set_value('Address', $pPlace->getAddress());

		$this->set_value('City', $pPlace->getCity());

		$this->set_value('State', $pPlace->getState());

		$this->set_value('Country', $pPlace->getCountry());

		$this->set_value('Postal Code', $pPlace->getPostalcode());
	}
}
<?

class Zupal_Form_Simple
implements Zupal_Form_IForm
{

	/**
	 * Sets the action of the form.
	 * If an object is passed must be resolvable to a string (i.e., have __toString();
	 * @param var $pAction
	 */
	private $_action = '';
	public function set_target($pAction){ $this->_action = $pAction; }
	protected function get_target(){ return $this->_action; }

	/**
	 * Note that the model passed in, the system of record for field values,
	 * and the model returned from get_model are not required to be one and the same.
	 * set_model may pass values to an internal system of record,
	 * and by implication, get_model may return a different object (or an array, or stdClass).
	 * That is there is no enforced binding of the domain or an ORM system to the form interface.
	 *
	 * @param boolean $pAutoLoad
	 */

	private $_fields = array();

	public function get_model($pAutoLoad = TRUE)
	{
		$out = array();
		foreach($this->_fields as $field => $data):
			$out[$field] = $data['value'];
		endforeach;
		return $out;
	}
	/**
	 * @param var $pModel -- expects an array or stdclass
	 */
	public function set_model($pModel)
	{
		foreach($pModel as $field => $value):
			$this->set_field_value($field, $value);
		endforeach;
	}

	public function get_field_value($pField){ return $this->_fields[$pField]['value']; }// @TODO: error condition for bad request
	public function set_field_value($pField, $pValue)
	{
		if (array_key_exists($pField, $this->_fields)):
			$this->_fields[$pField]['value'] = $value;
		else:
			$this->_fields[$pField] = array('value' => $value);
		endif;
	}
	public function define_field($pField, $pLabel, $pType, $pParams = NULL)
	{
		if (is_array($pParams)):
			$pParams['type'] = $pType;
			$pParams['label'] = $pLabel;
			$this->_fields[$pField] = $pParams;
		else:
			$this->_fields[$pField] = array(
				'type' => $pType,
				'value' => $pParams,
				'label' => $pLabel
			);
		endif;
	}

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _render_field @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pField, $pValue, $pType, $Params = NULL
	* @return <type>
	*/
	public function _render_field ($pField, $pValue, $pType, $pParams = NULL)
	{
		switch (strtolower($pType)):
			case 'textraea':
?><textarea name="<?= $pField ?>"><?= $pValue ?></textarea><?
			break;

			case 'button':
?><input type="button" name="pField" value="<?= $pParams['label'] ?>" onClick="<?= $pParams['onclick'] ?>"" /><?
			break;

			case 'select':
			case 'list':
?><select name="<?= $pField ?>">
<? foreach($pParams['options'] as $value => $label): ?>
<option value="<?= $value ?>"><?= $label ?></option>
<? endforeach; ?>
</select>
<?
			case 'text':
			default:
?><input type="text" name="<?= $pField ?>" value="<?= $pValue ?>" /><?
		endswitch;
	}

	/**
	 * renders the form.
	 * @return string;
	 */
	public function __toString(){
		ob_start();
?>
<form method="post" action= "<?= $this->get_target() ?>">
<table cellpadding="2" cellspacing="0" border="0">
<? foreach ($this->_fields as $field => $pParams): extract ($pParams);
	if ($type == 'hidden') continue;

	if (!isset($title)):
		$title = ucwords(str_replace('_', ' ', $field));
	endif;
?>
	<tr>
		<th valign="left" align="top"><?= $title ?></th>
		<td><?= $this->_render_field($field, $value, $type, $pParams) ?></td>
	</tr>
<?
	unset($title);
	unset($value);
	unset($type);
endforeach; ?>
</table>
</form>
<?
		return ob_end_clean();

	}


}
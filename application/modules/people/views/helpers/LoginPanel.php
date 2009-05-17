<?
class Zupal_People_Helper_LoginPanel extends Zend_View_Helper_Abstract
{
	public function LoginPanel()
	{
		ob_start();
?>
	<div class="panel">
		<h3>Log In</h3>
		<? $form = new Zupal_People_Loginform() ?>
		<?= $form->render() ?>
	</div>
<?
		return ob_get_clean();
	}
}
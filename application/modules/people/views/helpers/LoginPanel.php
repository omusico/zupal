<?
class Zupal_People_Helper_LoginPanel extends Zend_View_Helper_Abstract
{
	public function LoginPanel()
	{	
		$cache = Zupal_Bootstrap::$registry->cache;
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()):
			// Identity exists; get it
			$identity = $auth->getIdentity();
			if (!$cache->test('people_login_panel_logged_in')):
				ob_start();
?>
<div class="panel">
Logged in as <b>%s</b><br />
<?= new Zupal_Control_Link('Log Out', array('controller' =>'user', 'module' =>'people', 'action' => 'logout')) ?>
</div>
			<?
				$cache->save( ob_get_clean(), 'people_login_panel_logged_in');
			endif;
			return sprintf($cache->load('people_login_panel_logged_in'), $identity['username']);
		else:
			if (!$cache->test('people_form')):
				ob_start();
?>
	<div class="panel">
		<h3>Log In</h3>
		<? $form = new Zupal_People_Loginform() ?>
		<?= $form->render() ?>
	</div>
<?
				$cache->save(ob_get_clean(), 'people_form');
			endif;
			return $cache->load('people_form');
		endif;
	}
}
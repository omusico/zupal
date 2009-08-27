<?

class Zupal_Helper_Zupalusermenu extends Zend_View_Helper_Abstract {
/**
 *
 * @return Zend_View
 */
    public function getView()
    {
        return $this->view;
    }

    public function zupalusermenu() {
        ob_start();
           ?>
<div id="user_menu">
    <h1>User</h1>
<?
        $user_menu = array();
        $ini = Administer_Model_Modules::getInstance()->get('default')->module_path('configuration/info.ini');
        $user = Model_Users::current_user();
        if ($user):
            echo '<span class="tagline">Viewing As ', $user->username, '</span>';
            $pages = new Zend_Config_Ini($ini, 'user_menu_in');
        else:
            $pages = new Zend_Config_Ini($ini, 'user_menu_anon');        
        endif;

            echo $this->getView()->navigation()->menu()->renderMenu(new Zend_Navigation($pages));
        ?>
</div>
<?
    return ob_get_clean();
    }



}
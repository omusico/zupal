<?
class Ultimatum_View_Helper_Interact
extends Zend_View_Helper_Abstract
{

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ interact @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Ultimatum_Model_Ultplayergroupknowledge $pScan
 * @return string
 */
    public function interact (Ultimatum_Model_Ultplayergroupknowledge $pScan) {

    ob_start();
?>
<a href="<?= $this->view->baseUrl() ?>/ultimatum/game/attack/terget/<?= $pScan->get_group()->identity() ?>"
   class="linkbutton">Attack</a>

<a href="<?= $this->view->baseUrl() ?>/ultimatum/game/attack/target/<?= $pScan->get_group()->identity() ?>"
   class="linkbutton">Acquire</a>

<?
    return ob_get_clean();
    }
}
<?

class Ultimatum_View_Helper_Groupscan
extends Zend_View_Helper_Abstract
{

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ groupscan @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Ultimatum_Model_Ultplayergroupknowledge $pScan
 * @return string
 */
    public function groupscan (Ultimatum_Model_Ultplayergroupknowledge $pScan, $pContent = NULL) {

        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/style/ultimatum/ult_style.css');
        $group = $pScan->get_group();

        ob_start();
        ?>
<fieldset>
    <legend><?= $group->get_title() ?>
        (as of turn <?= $pScan->get_game()->turn(TRUE) ?>)</legend>
     <?= $this->view->powerMatrix($pScan) ?>

    <b><?= $group->get_lead() ?></b>
    <hr />
        <?= $group->get_content() ?>

    <? if ($pContent) echo $pContent; ?>
</fieldset>
        <?
        return ob_get_clean();
    }
}
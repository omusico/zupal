 <?
class Ultimatum_View_Helper_Game
extends Zend_View_Helper_Abstract {

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ interact @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Ultimatum_Model_Ultplayergroupknowledge $pScan
 * @return string
 */
    public function game (Ultimatum_Model_Ultplayergroupknowledge $pScan) {
        $game = Zend_Registry::get('ultimatum_game');
        if ($game):
        $user = Model_Users::current_user();
            ob_start();
            ?>
<fieldset id="ultimatum_game">
    <legend>Ultimatum</legend>
<b>Game:</b> <?= $game ?>, turn <?= $game->turn() ?>
<? if ($user && $user->can('ultimatum_manage')): ?>
<?= $this->view->zupallinkbutton("/ultimatum/game/nextturn/game/" . $game->identity(), 'Next Turn') ?>
<? endif; ?>
<br />
<?= $this->view->zupallinkbutton("/ultimatum/game/switch/", 'Stop Playing') ?>
</fieldset>
<?
            return ob_get_clean();
        else:
            return '';
    endif;
    }
}
<?


class Zupal_Helper_Zupalmovebar
extends Zend_View_Helper_Abstract {

    public function zupalmovebar($pModel, $pPath, $pPrefix = '', $pSuffix = '')
    {
        if (is_object($pModel)):
            $pModel = $pModel->identity();
        endif;
        ob_start();
?>
<?= $pPrefix ?>
<?= $this->view->zupalicon('move_top', "$pPath?mode=move_top&id=$pModel") ?>
<?= $pSuffix . $pPrefix ?>
<?= $this->view->zupalicon('move_up', "$pPath?mode=move_up&id=$pModel") ?>
<?= $pSuffix . $pPrefix ?>
<?= $this->view->zupalicon('move_down', "$pPath?mode=move_down&id=$pModel") ?>
<?= $pSuffix . $pPrefix ?>
<?= $this->view->zupalicon('move_bottom', "$pPath?mode=move_bottom&id=$pModel") ?>
<?= $pSuffix ?>
<?
        return ob_get_clean();
    }
}
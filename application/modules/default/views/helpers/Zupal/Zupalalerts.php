<?

class Zupal_Helper_Zupalalerts extends Zend_View_Helper_Abstract {

    public function getView()
    {
        return $this->view;
    }

    public function zupalalerts() {
        ob_start();
        if ($error = $this->getView()->placeholder('error')->getValue()):
            $this->box('Error', $error, 'error');
        endif;

        if ($message = $this->getView()->placeholder('message')->getValue()):
            $this->box('Message', $message, 'message');
        endif;

        return ob_get_clean();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ bos @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pTitle, $pContent
     * @return
     */
    public function box ($pTitle, $pContent, $pMessage) {
  ?>
<div class="<?= $pMessage ?>">
    <h2><?= $pTitle ?></h2>
    <p>
        <?= $pContent ?>
    </p>
</div>
<?

  }

}
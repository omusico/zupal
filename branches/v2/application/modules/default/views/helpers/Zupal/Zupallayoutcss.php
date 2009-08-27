<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
<?   
 */

?>

<?

class Zupal_Helper_Zupallayoutcss extends Zend_View_Helper_Abstract {

    public function getView()
    {
        return $this->view;
    }


    public function zupallayoutcss($pStylesheet, $pFile)
    {
        $prefix = preg_replace('~^' . ZUPAL_PUBLIC_PATH . '~', '', dirname($pFile));
        $this->getView()->headLink()->appendStylesheet("$prefix/$pStylesheet");
    }
}
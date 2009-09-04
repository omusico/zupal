<?php

class Zupal_Helper_Zupalticket{

    public function zupalticket(array $pValue, $pTitle, $pButtons = NULL)
    {
	ob_start();
	?>
<fieldset class="ticket">
    <legend><?= $pTitle ?></legend>
    <dl>
<? foreach($pValue as $title => $value): ?>
	<dt><?= $title ?></dt>
	<dd><?= $value ?></dd>
<? endforeach; ?>
    </dl>

<? if ($pButtons): ?>
<ol>
    <? foreach($pButtons as $page): $page->setParams($page->getParams() + array('rand' => rand(0, 1000))); ?>
    <li><a href="<?= $page->getHref() ?>"><?= $page->getLabel() ?></a></li>
    <? endforeach; ?>
</ol>
</fieldset>
<? endif; ?>
<?

	return ob_get_clean();
    }
}
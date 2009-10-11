<?php
/**
 * This helper renders our a Dojo Grid for a given data set.
 * The columns are passed in as an array of data.
 * Note -- while the dataset has only been tested on singular (table) reports,
 * there is no reason that it cannot represent a joined query also.
 */
class View_Helper_Dojogrid
 extends Zend_View_Helper_Abstract
 {

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ params @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	    /**
	    *
	    * @param array $pParam
	    * @return string
	    */
    public static function params ($pParam)
    {
	    $out = '';
	    if ($pParam):
		    foreach($pParam as $k => $v):
			    $out .= sprintf(' %s="%s" ', $k, $v);
		    endforeach;
	    endif;
	    return $out;
    }
     
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param string $pID - the javascript identity of the table
	* @param string $pStore_ID - the name of the datastore identity
	* @param array $pColumns - a parameter set for thecolumn definitions
	* @return string $pIdentifier -- the name of the identity column of the dataset
	* 
	*/
public function dojogrid ($pID, $pStore_ID = NULL,
    $pRows, $pIdentifier = 'id', $pParams = NULL, $pOptions = NULL)
{
    if ($pRows instanceof Zend_Config_Ini):
        $pRows = $pRows->toArray();
    endif;

    $this->prep_view();
    $height = 400;
    $width = 600;
    $query = array($pIdentifier => '*' );
    if ($pOptions):
        extract($pOptions);
    endif;
    ob_start();
	if (is_null($pStore_ID)):
	    $pStore_ID = $pID . '_store';
	endif;
?>
<table rowsPerPage="10" style="height: <?= $height ?>px; width: <?=$width ?>px" id="<?= $pID ?>"
dojoType="dojox.grid.DataGrid" clientSort="true" <?= self::params($pParams) ?>
query="<?= str_replace('"', "'", (is_string($query) ? $query : Zend_Json::encode($query))) ?>" store="<?= $pStore_ID ?>">
	<thead>
<? foreach($pRows as $columns): ?>
	    <tr>
<? foreach($columns as $key => $column): ?>
	<? if (is_array($column)): ?>
		<?= self::array_column($column) ?>
	<? elseif (is_string($column)): ?>
			<th field="<?= trim($key) ?>"> <?= $column ?></th>
	<? elseif (is_object($column)): // must have a __toString() method
	?>
			<?= $column ?>
	<? endif; ?>
<? endforeach; ?>
		</tr>
<? endforeach; ?>
	</thead>
</table>
<?
return ob_get_clean();
	}

	public static function array_column($pColumn)
	{
            if (array_key_exists('label', $pColumn)):
                $label = $pColumn['label'];
                unset($pColumn['label']);
            else:
                $label = ucfirst($pColumn['field']);
            endif;

            ob_start();
		?><th <? foreach($pColumn as $key => $value): ?> <?= $key ?>="<?= $value ?>" <? endforeach; // parameter loop
?> ><?= $label ?></th>
<?
    return ob_get_clean();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ prep_view @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param Zend_View $pView
	* @return void
	*/
	public function prep_view ()
	{
		$d = $this->view->dojo();
		$d->addLayer( $this->view->baseUrl() . '/scripts/dojo/grid_layer.js');
		$d->addStyleSheet($this->view->baseUrl() .  '/scripts/dojox/grid/resources/Grid.css');
		$d->setDjConfigOption('parseOnLoad', TRUE)
		    ->enable();
	}



}

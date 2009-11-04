<?php
/**
 * This helper renders our a Dojo Grid for a given data set.
 * The columns are passed in as an array of data.
 * Note -- while the dataset has only been tested on singular (table) reports,
 * there is no reason that it cannot represent a joined query also.
 */
class Zupal_Helper_Dojogrid {



/* @@@@@@@@@@@@@@@@@@@@@@@@@@ view @@@@@@@@@@@@@@@@@@@@@@@@ */

    public $view = null;
    /**
     * @return class;
     */

    public function getView() { return $this->_view; }

    public function setView($pValue) { $this->_view = $pValue; }


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
    $pRows, $pIdentifier = 'id', array $pParams = NULL, array $pOptions = NULL)
{
    if ($pRows instanceof Zend_Config_Ini):
        $pRows = $pRows->toArray();
    endif;

    $this->prep_view();
    $height = 400;
    $width = 600;
    $body = '';
    $query = array($pIdentifier => '*' );
    if ($pOptions):
        extract($pOptions);
    endif;
    ob_start();
	if (is_null($pStore_ID)):
	    $pStore_ID = $pID . '_store';
	endif;
?>
<table rowsPerPage="10" 
       id="<?= $pID ?>"

       style="<?= $this->sprop('height', $height, 'px') ?> <?= $this->sprop('width', $width, 'px') ?>"
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
        <?= $body ?>
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
		?><th 
<? foreach($pColumn as $key => $value): ?> <?= $key ?>="<?= $value ?>" <? endforeach; // parameter loop
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

		$this->getView()->dojo()
                       // ->requireModule('dojox.grid.DataGrid')
                       // ->requireModule('dojo.Data.ItemFileReadStore')
			->addLayer( $this->getView()->baseUrl() . '/scripts/dojo/grid_layer.js')
			->addStyleSheet($this->getView()->baseUrl() .  '/scripts/dojox/grid/resources/Grid.css')
             ->setDjConfigOption('parseOnLoad', TRUE)
			->enable(); 
	}

public static function query_store($pStore_ID, $pURL)
{
    ob_start();
?>
<div dojoType="dojox.data.QueryReadStore"
    jsId="<?= $pStore_ID ?>"
    url="<?= $pURL ?>"
    doClientPaging="false" />
<?
    return ob_get_clean();
}

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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ sprop @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param string $pProperty
 * @param int | string $value,
 * @param string $pUnit
 * @return string
 */
public function sprop ($pProperty, $value, $pUnit='px') {
    if (is_numeric($value)):
        $value = "$value$pUnit";
    endif;

    return "$pProperty: $value; ";
}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ querygrid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
        * for large datasets - pulls a section of the grid at a time
	* @param <type> $pParam
	* @return <type>
	*/
public static function querygrid($pID, $pStore_ID = NULL, $pColumns, $pIdentifier = 'id', array $pParams = NULL)
{
	if (is_null($pStore_ID)) $pStore_ID = $pID . '_store';
	$rows_per_page = 100;
	$table_height = 400;
	$table_width = 600;
	if ($pParams && is_array($pParams)):
		extract($pParams);
	endif;
?>
<table id="<?= $pID ?>"  rowsPerPage="<?= $rows_per_page ?>"
       style="<?= $this->sprop('height', $table_height, 'px') ?>
       <?= $this->sprop('width', $table_width, 'px') ?>" jsId="<?= $pID ?>"
dojoType="dojox.grid.DataGrid" clientSort="true" <?= self::params($pParams) ?>
	   query="{ <?= $pIdentifier ?> : '*' }" store="<?= $pStore_ID ?>">
	<thead>
		<tr>
<? foreach($pColumns as $key => $column): ?>
	<? if (is_array($column)): ?>
		<?= self::array_column($key, $column) ?>
	<? elseif (is_string($column)): ?>
			<th field="<?= $key ?>"><?= $column ?></th>
	<? elseif (is_object($column)): // must have a __toString() method
	?>
			<?= $column ?>
	<? endif; ?>
<? endforeach; ?>
		</tr>
	</thead>
<script type="dojo/method" event="requestRows" args="inRowIndex, inCount">
        // creates serverQuery-parameter
        var row  = inRowIndex || 0;
        var params = {
            start: row,
            count: inCount || this.rowsPerPage,
            serverQuery: dojo.mixin(
              { start: row,
                count: inCount || this.rowsPerPage,
                sort:(this.sortColumn || '')
              },
              this.query
            ),
            query: this.query,
            // onBegin: dojo.hitch(this, "beginReturn"),
            onComplete: dojo.hitch(this, "processRows")
        }
        this.store.fetch(params);
    </script>
    <script type="dojo/method" event="getRowCount">
        // should return total count (fetch from server), not "rowsPerPage"
        return <?= $rows_per_page ?>;
    </script>
    <script type="dojo/method" event="sort" args="colIndex">
        // clears old data to force loading of new, then requests new rows
        this.clearData();
        this.sortColumn = colIndex;
        this.requestRows();
    </script>
    <script type="dojo/method" event="setData" args="inData">
        // edited not to reset the store
        this.data = [];
        this.allChange();
    </script>
    <script type="dojo/method" event="canSort">
        // always true
        return true;
    </script>
	</table>
<?

}

}

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


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pID - the javascript identity of the table
     * @param string $pStore_ID - the name of the datastore identity
     * @param array $pColumns - a parameter set for thecolumn definitions
     * @return string $pIdentifier -- the name of the identity column of the dataset
     *
     */
    public function dojogrid ($pID, $pStore_ID = NULL, $pRows = array(), $pIdentifier = 'id', $pParams = NULL, $pOptions = NULL) {
	$declare = FALSE;
	$this->prep_view();
	$height = 400;
	$width = 600;
	$query = array($pIdentifier => '*' );

	if ($pOptions && is_array($pOptions)):
	    extract($pOptions); // may override all params
	endif;

	$query = (is_string($query) ? $query : Zend_Json::encode($query));
	$query_string = str_replace('"', "'", $query);

	if (empty($pStore_ID)):
	    $pStore_ID = $pID . '_store';
	endif;

	if ($declare):
	    return sef::grid_declarative($pID, $pRows, $query_string, $pStore_ID, $height, $width, $pParams);
	else:
	    return self::grid_interp($pID, $pRows, $query_string, $pStore_ID, $height, $width, $pParams);
	endif;
    }

    public static function array_column($pColumn) {
	if (array_key_exists('label', $pColumn)):
	    $label = $pColumn['label'];
	    unset($pColumn['label']);
	else:
	    $label = ucfirst($pColumn['field']);
	endif;

	$params = '';
	foreach($pColumns as $key => $value):
	    $params .= sprintf(' %s="%s" ', $key, $value);
	endforeach;

	ob_start();
	?><th <?= params ?> ><?= $label ?></th>
	<?
	return ob_get_clean();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ prep_view @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Zend_View $pView
     * @return void
     */
    public function prep_view () {
	$d = $this->view->dojo();
	$d->addLayer( $this->view->baseUrl() . '/scripts/dojo/grid_layer.js');
	$d->addStyleSheet($this->view->baseUrl() .  '/scripts/dojox/grid/resources/Grid.css');
	$d->setDjConfigOption('parseOnLoad', TRUE)
	    ->enable();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grid_declarative @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	    /**
	     *
	     * @param <type> $pID, $pRows, $query_string, $pStore_id
	     * @return <type>
	     */
    public function grid_declare ($pID, $pRows, $query_string, $pStore_id, $height, $width, $pParams) {
	return Zupal_Util_Dojogrid::declarative($this->view, $pID, $pRows, $query_string, $pStore_id, $height, $width, $pParams);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grid_interprative @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pID, $pRows
     * @return <type>
     */
    public function grid_interp ($pID, $pRows, $query_string, $pStore_id, $height, $width, $pParams) {
	$i = new Zupal_Util_Dojogrid();
	return Zupal_Util_Dojogrid::interp($this->view, $pID, $pRows, $query_string, $pStore_id, $height, $width, $pParams);
    }

}

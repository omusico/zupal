<?

interface Zupal_Grid_IGrid
{
	public function render_grid(Zend_View $pView, $pID, array $pColumns, $pURL);

	public function render_data(array $pParams, $pStart = 0, $pRows = 30, $pSort = NULL);
	
}
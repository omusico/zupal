<?

interface Zupal_Grid_IGrid
{
	public function render_grid(Zend_View $pView, $pID, $pStore_ID, array $pColumns);

	public function render_data(array $pParams, $pStart = 0, $pRows = 30, $pSort = NULL);

	public function render_script($pID, array $pParams = NULL);

	public function render_store($pStore_ID, $pURL);
}
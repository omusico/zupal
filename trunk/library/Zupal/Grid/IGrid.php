<?

interface Zupal_Grid_IGrid
{
	public function render_grid($pID, array $pParams = NULL);

	public function render_data(array $pParams, $pStart = 0, $pRows = 30, $pSort = NULL);
	
}
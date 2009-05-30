<?

class Media_MusicbrainzController
extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function indexAction ()
	{
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ loadAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function loadAction ()
	{
		$module = Zupal_Module_Manager::getInstance()->get('media');

		$dir_path = (string) $module->info()->musicolio_files;

		$di = new DirectoryIterator($dir_path);

		$this->view->files = array();

		foreach($di as $fi):
			if ($fi->isFile()):
				$this->view->files[$fi->getFilename()] = $fi->getRealPath();
			endif;
		endforeach;
		/*
	sql/temp.sql");
      print TEMPSQL "USE $g_db_name;\nLOAD DATA LOCAL INFILE 'mbdump/$file' INTO TABLE `$table`\n";
      print TEMPSQL "FIELDS TERMINATED BY '\\t' ENCLOSED BY '' ESCAPED BY '\\\\'\n";
      print TEMPSQL "LINES TERMINATED BY '\\n' STARTING BY '';";
		 */
	}

}
<?php
/**
 * Description of Nav
 *
 * @author bingomanatee
 */
class Nav_Model_Nav
extends Zupal_Model_Domain_Abstract
{

    private static $_container;
    public function container(){
        if (!self::$_container){
            global $mod_paths;
            $path = $mod_paths['nav'] . D . 'model' . D . 'nav_schema.json';
            $schema = Zupal_Model_Schema_Item::make_from_json($path);
            self::$_container = new Zupal_Model_Container_Mongo('zupal', 'nav', array('schema' => $schema));
        }
        return self::$_container;
    }

}


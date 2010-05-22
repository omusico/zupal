
<pre>
    <?php
    try {

        // part 1: general mongo test

        $connection = new Mongo();

        $db = $connection->foo;
        $coll = $db->foo_fighters;

        $doc = array('name' => 'Robert', 'type' => 'database', 'count' => 2,
                'info' => (object) array('x' => 1, 'y' => 2),
                'versions' => array('1.12', '1.22')
        );

        $coll->insert($doc);
        $obj = $coll->find();

        // part 2: Zupal Mongo Classes
        $schema_path = dirname(__FILE__) . D . 'mongo_test_user_schema.json';
        if (!file_exists($schema_path)){
            throw new Exception('Bad schema path: ' . $schema_path);
        }
        $schema_json = file_get_contents($schema_path);
        $schema_data = Zend_Json::decode($schema_json);
        $schema = new Zupal_Model_Schema_Item($schema_data);

        $users = new Zupal_Model_Container_Mongo('test', 'users', array('schema' => $schema));

        $crit = array('name' => 'alpha');

        $users->find_and_delete($crit);

        $alpha = $users->find($crit);
        if (!$alpha) {
            $alpha = $users->new_data($crit);
        } else {
            $alpha = array_pop($alpha);
        }
        $alpha['pass'] = 'abc123';

        $users->save_data($alpha);

        print_r($users->find($crit));
?>
</pre>
<?php
    } catch (Exception $e) { ?>
</pre>
<?php
        echo $e;
    }
    ?>
<pre>
    <?php

    function rand_name($names = 2) {
        static $rand_name_base;
        if (empty($rand_name_base)) {
            $rand_name_base = split(',', 'Barry,Sally,Moe,Fisher,Stevens,Dallas,Sucre,Doris,Cooper');
        }
        $out = array_rand($rand_name_base, $names);
        foreach($out as $i => $key) {
            $out[$i] = $rand_name_base[$key];
        }
        return join(' ', $out);
    }

    try {

        // part 2: Zupal Mongo Classes
        $schema_path = dirname(__FILE__) . D . 'mongo_test_user_schema.json';
        if (!file_exists($schema_path)) {
            throw new Exception('Bad schema path: ' . $schema_path);
        }
        $schema_json = file_get_contents($schema_path);
        $schema_data = Zend_Json::decode($schema_json);
        $schema = new Zupal_Model_Schema_Item($schema_data);

        $users = new Zupal_Model_Container_MongoCollection('test', 'users', array('schema' => $schema));

        $crit = array('name' => 'alpha');

        //  $users->find_and_delete($crit);

        $alpha = $users->find($crit);
        if (!$alpha) {
            $alpha = $users->new_data($crit);
        } else {
            $alpha = array_pop($alpha);
        }
        $alpha['pass'] = 'abc123';
        $alpha['last_visit'] = time();

        $users->save_data($alpha);

        print_r($users->find($crit));
        
        $beta_data = array('name' => rand_name(), 'pass' => rand_name(1) . rand(), 'last_visit' => time());
        
        $beta = $users->new_data($beta_data);
        
        $users->save_data($beta);
        
        $crit = array('name' => $beta_data['name']);

        print_r($users->find_one($crit));
        ?>
</pre>
<h2>Guests</h2>
<pre>
<?php
    $crit = array('auth' => array('guest'));
    $guests = $users->find($crit);
    print_r($guests);
?>
</pre>
    <?php
} catch (Exception $e) { ?>
</pre>
    <?php
    echo $e;
}

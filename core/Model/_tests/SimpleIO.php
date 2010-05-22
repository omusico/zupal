<?php

defined('ZF_PATH') ||
        define('ZF_PATH', '/Applications/Zend/library');

require_once(realpath(dirname(__FILE__) . '/../../../bootstrap.php'));

class Zupal_Test_SimpleIO extends PHPUnit_Framework_TestCase {

    protected function setUp() {
    }

    protected function tearDown() {
    }

    public function testSimpleContainer() {

        $mock_container = new Zupal_Model_Container_Mock('foo');

        $this->assertEquals(0, $mock_container->count(), 'Checking that container starts empty');

    }

    public function testSchema(){
        $schema_raw = file_get_contents(dirname(__FILE__) . '/testSchema_schema.json');
        $schema_data = Zend_Json::decode($schema_raw);

     //   echo __METHOD__ . ', schema raw = ', $schema_raw, "\n";
    //    echo __METHOD__ . ', schema = ', print_r($schema_data, 1), "\n";

        $schema = new Zupal_Model_Schema_Item($schema_data);
        $this->assertEquals(3, $schema->count(), 'counting fields');

        $pProps = array('schema' => $schema);
        $mock_schema_container = new Zupal_Model_Container_Mock('mock_data', $pProps);

        $alpha = $mock_schema_container->new_data(array('id' => 1, 'name' => 'Alpha'));

        $this->assertEquals(1, $mock_schema_container->count(), 'tallying new addition');

        $expect_raw = file_get_contents(dirname(__FILE__) . '/testSchema_expectedData.json');
        $expect_data = Zend_Json::decode($expect_raw);

        $this->assertEquals($expect_data, $mock_schema_container->toArray(), 'expected data');
    }
}
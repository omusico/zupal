<?php


/**
 * Description of JSONIterator
 *
 * @author bingomanatee
 */
class Zupal_Model_Container_JSONIterator {

    /**
     *
     * @var Zupal_Model_Container_JSON
     */
    private $_container;

    /**
     *
     * @var DirectoryIterator
     */
    private $_di;

    public function __construct(Zupal_Model_Container_JSON $pContainer) {
        $this->_container = $pContainer;
        $this->rewind();
    }

    const END_GBG = '~~~~~~~~~';

    public  function current  (   ) {
       if ($this->valid()){
           $id = $this->_di->getFilename();
           $id .= self::END_GBG;
           $id = str_replace('.json' . self::END_GBG, '', $id);
           return $this->_container->get($id);
       } else {
           return NULL;
       }
    }
    public function key (  ) {
        return $this->_di->getFilepath();
    }
    public function next (  ) {
        $this->_skip();
    }
    public function rewind () {
        $this->_di = new DirectoryIterator($this->_container->root());
        $this->_skip();
    }
    public function valid (  ) {
        return $this->_di->valid();
    }

    private function _skip() {
        while($this->_di->valid() && ($this->_di->isDir() || $this->_di->isDot() || (!$this->_is_json()))) {
            $this->_di->next();
        }
    }

    private function _is_json() {
        $filename = $this->_di->getFilename();

        return !strcasecmp('.json', substr($filename, -5));
    }
}

<?

class Xtractlib_Exception
extends Exception
{
    public $url;
    
    public function __construct($message, $url, $code = 0) {
        $this->url = $url;
        parent::__construct($message . "\nURL: $url", (int) $code);
    }

}
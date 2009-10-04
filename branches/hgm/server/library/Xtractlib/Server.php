<?

class Xtractlib_Server
{

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ adduri @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     * adds a URL to the search registry
     *
     * @param string $pURL
     * @return array
     */
    public function addurl ($pURL)
    {

        $urls = new Xtract_Model_Urls(Xtractlib_Domain_Abstract::STUB);
        $url = $urls->get_url($pURL);
        $url->parse();
        
        return $url->toArray();
    }

    public function domains()
    {
        $domains = Xtract_Model_UrlDomains::getInstance()->findAll('host');
    }
}
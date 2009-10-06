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
    public function add_url ($pURL)
    {

        $urls = Xtract_Model_Urls::getInstance();
        $url = $urls->get_url($pURL);
        return $url->parse();
    }
/**
 *
 * @param string $pHTML
 * @return array
 */
    public function scan_html ($pHTML)
    {

        $htmls = Xtract_Model_UrlHtmls::getInstance();
        return $html->scan_html($pHTML);
    }

    public function domains()
    {
        $domains = Xtract_Model_UrlDomains::getInstance()->findAll('host');
    }
}
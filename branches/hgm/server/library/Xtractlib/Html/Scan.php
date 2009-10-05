<?

class Xtractlib_Html_Scan
{
    public static function scan(Xtract_UrlHtml $html)
    {
        $html->save();
        $dom = new Zend_Dom_Query($html->html);

        $links = $dom->query('a');

        foreach(Xtract_Model_UrlLinks::getInstance()->find(array('from_url'  => $html->in_url)) as $link):
            $link->delete();
        endforeach;

        error_log("\n\n\n ************** \n\n\n" . __METHOD__ . ': url = ' . $html->in_url);
        
        try {
        foreach($links as $link):
            if (!($href =  $link->getAttribute('href'))):
                continue;
            endif;

            error_log('LINK: ' . $href);
            $url = $html->url();
            Xtractlib_Html_Link::link_to($url, $href, $html);
        endforeach;
        } catch (Exception $e)
        {
            error_log(__METHOD__ . ': links - ' . print_r($e, 1));
        }

        $images = $dom->query('img');

        try {
        foreach($images as $img):
            error_log(__METHOD__ . ': image');

            if (!($src = $img->getAttribute('src'))):
                continue;
            endif;

            error_log('IMAGE: ' . $src);
            Xtract_Model_UrlImages::make($html->url(), $src, $html);
        endforeach;
        } catch (Exception $e)
        {
            error_log(__METHOD__ . ': images - ' . print_r($e, 1));
        }
    }
}
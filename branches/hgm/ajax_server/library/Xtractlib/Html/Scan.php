<?

class Xtractlib_Scan
{
    public static function scan(Xtract_UrlHtml $html)
    {
        $html->save();
        $dom = new Zend_Dom_Query($html->html);

        $links = $dom->query('a');

        error_log("\n\n\n ************** \n\n\n" . __METHOD__ . ': url = ' . $html->url()->url);
        foreach($links as $link):
            if (!($href =  $link->getAttribute('href'))):
                continue;
            endif;

            error_log('LINK: ' . $href);
            $url = $html->url();
            Xtract_Model_UrlLinks::link($url, $href, $html);
        endforeach;
        Xtract_Model_UrlLinks::getInstance()->table()->delete('in_url = ?', $html->url);
        $images = $dom->query('img');

        foreach($images as $img):
            error_log(__METHOD__ . ': image');

            if (!($src = $img->getAttribute('src'))):
                continue;
            endif;

            error_log('IMAGE: ' . $src);
            Xtract_Model_UrlImages::make($html->url(), $src, $html);
        endforeach;
    }
}
<?

class Xtractlib_Html_Scan
{
    /**
     * Note -- scan_html takes a text input -- becuase its source is not 
     * truly internet baed its not saved to database. 
     * 
     * 
     * @param string $html
     * @return array
     */
    public static function scan_html($html)
    {

        $dom = new Zend_Dom_Query($html);

        $data = array('links' => array(), 'images' => array());

        $links = $dom->query('a');

        Xtractlib_Log::message("\n\n\n ************** \n\n\n" . __METHOD__ . ': url = ' . $html->in_url);

        try {
        foreach($links as $link):
            if (!($href =  $link->getAttribute('href'))):
                continue;
            endif;
            $href_url = $href;

            $data['links'][] = $href;
        endforeach;
        } catch (Exception $e)
        {
            Xtractlib_Log::message(__METHOD__ . ': links - ' . print_r($e, 1));
        }

        $images = $dom->query('img');

        foreach($images as $img):
            Xtractlib_Log::message(__METHOD__ . ': image');

            if (!($src = $img->getAttribute('src'))):
                continue;
            endif;

            $data['images'][] = $src;
        endforeach;

        $data['images'] = array_unique($data['images']);
        sort($data['images']);

        $data['links'] = array_unique($data['links']);
        sort($data['links']);

        return $data;
    }

    public static function scan(Xtract_Model_UrlHtmls $html)
    {


        $html->save();

        $domain = $html->url()->get_domain();

        $dom = new Zend_Dom_Query($html->html);

        $data = array('links' => array(), 'images' => array());

        $links = $dom->query('a');

        foreach(Xtract_Model_UrlLinks::getInstance()->find(array('from_url'  => $html->in_url)) as $link):
            $link->delete();
        endforeach;

        Xtractlib_Log::message("\n\n\n ************** \n\n\n" . __METHOD__ . ': url = ' . $html->in_url);

        try {
        foreach($links as $link):
            if (!($href =  $link->getAttribute('href'))):
                continue;
            endif;
            $href_url = $href;
            Xtractlib_Log::message('LINK: ' . $href);
            try {
                $url = $html->url();
                $link = Xtractlib_Html_Link::link_to($url, Xtract_Model_Urls::get_url($href_url, $domain), $html);
                $href_url = $link->get_to_url()->absolute_url();
            } catch (Exception $e) {
                Xtractlib_Log::message(__METHOD__ . ': error adding link ' . $href . ': ' . $e->getMessage());
            }
            $data['links'][] = $href_url;
        endforeach;
        } catch (Exception $e)
        {
            Xtractlib_Log::message(__METHOD__ . ': links - ' . print_r($e, 1));
        }

        $images = $dom->query('img');

        foreach($images as $img):
            Xtractlib_Log::message(__METHOD__ . ': image');

            if (!($src = $img->getAttribute('src'))):
                continue;
            endif;

            $img_src_url = $src;

            Xtractlib_Log::message('IMAGE: ' . $src);
            try {
                $image = Xtract_Model_UrlImages::make($html->url(), $src, $html);
                $img_src_url = $image->get_href_url()->absolute_url();
            } catch (Exception $e)
            {
                Xtractlib_Log::message(__METHOD__ . ': images - ' . print_r($e, 1));
            }
            $data['images'][] = $img_src_url;
        endforeach;

        $data['images'] = array_unique($data['images']);
        sort($data['images']);

        $data['links'] = array_unique($data['links']);
        sort($data['links']);

        return $data;
    }

}
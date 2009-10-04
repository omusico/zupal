<?

class Xtractlib_Html_Link
{
    public static function link_to($pFrom, $pTo, $pHtml)
    {
        error_log(__METHOD__);

        $from = Xtract_Model_Urls::get_url($pFrom);
        $to   = Xtract_Model_Urls::get_url($pTo);
        $html = Xtract_Model_UrlHtmls::get_html($pHtml);

        $params = array(
            'from_url'      => $from->identity(),
            'to_url'        => $to->identity(),
            'found_in_html' => $html->identity()
        );

        if (!($link = Xtract_Model_UrlLinks::getInstance()->findOne($params))):
            $link = Xtract_Model_UrlLinks::getInstance()->get(NULL, $params);
            $link->save();
        endif;

        return $link; 
    }
    
}
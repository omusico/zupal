<?

class Xtractlib_Log
{

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ message @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param message $pParam
 * @return void
 */
    public static function message ($pParam) {
        if (defined('APPLICATION_ENV') && strcasecmp(APPLICATION_ENV, 'production')):
            error_log($pParam);
        endif;
    }

}
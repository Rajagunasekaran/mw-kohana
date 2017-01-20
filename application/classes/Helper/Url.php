<?php 
/**
 * Extension of the Kohana URL helper class.
 */
class Helper_Url extends Kohana_URL 
{
    /**
     * Fetches the URL to the current request uri.
     *
     * @param   bool  make absolute url
     * @param   bool  add protocol and domain (ignored if relative url)
     * @return  string
     */
    public static function current($absolute = FALSE, $protocol = FALSE)
    {
        $current_url = Request::initial()->uri();
		  return $current_url;
		
    }
}
?>
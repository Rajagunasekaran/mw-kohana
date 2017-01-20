<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Tracking extends Controller {

	public function _construct() {
         parent::__construct($request, $response);
    } 
		
	public function action_index()
	{
		
	}
	public function action_email()
	{
		$this->auto_render = FALSE;
		if(isset($_GET['messageid'])) {	
			$messageid = $_GET['messageid'];			
			$query = DB::insert('tracking_table', array('message_id'))->values(array($messageid))->execute();
			//Begin the header output
			header( 'Content-Type: image/gif' );
			//Full URI to the image
			$graphic_http =  URL::base(true).'assets/img/blank.gif';
			
			//Get the filesize of the image for headers
			$filesize = filesize( './assets/img/blank.gif' );
			
			//Now actually output the image requested (intentionally disregarding if the database was affected)
			header( 'Pragma: public' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Cache-Control: private',false );
			header( 'Content-Disposition: attachment; filename="blank.gif"' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Content-Length: '.$filesize );
			readfile( $graphic_http );
			exit;
		}
	}
	
} // End Welcome

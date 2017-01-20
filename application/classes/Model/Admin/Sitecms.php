<?php defined('SYSPATH') or die('No direct script access.');

class Model_Admin_Sitecms extends ORM 
{
	protected $_table_name = 'sitehomepages';
	protected $_primary_key = 'id';  
	public function gethomepagedetails($field, $condtn)
	{	
		$sql = "SELECT $field FROM sitehomepages where $condtn"; 
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
		return $list;
	}
	public function getsocialpagedetails($field, $condtn) {	
		$sql = "SELECT $field FROM sitesocaialpages where $condtn"; 
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
		return isset($list[0]) ? $list[0] : $list;
	}
	public function savesocialpagedetails($postDetails, $savetype) {
		if($savetype == 'update'){
			$sql 	= "Update sitesocaialpages set description = '".trim($postDetails['description'])."', title = '".trim($postDetails['title'])."', ".(isset($postDetails['video']) ? "video = '".trim($postDetails['video'])."', " : '')." video_status = '".trim($postDetails['video_status'])."', ".(isset($postDetails['site_image']) ? "site_image = '".$postDetails['site_image']."', " : '')." user_id = '".$postDetails['user_id']."', date_modified= '".$postDetails['date_modified']."' Where site_id ='".$postDetails['site_id']."'";
			return DB::query(Database::UPDATE,$sql)->execute();
		}else{
			$query = DB::insert('sitesocaialpages', array('description', 'title', 'video', 'video_status', 'site_image', 'site_id', 'user_id', 'date_created', 'date_modified'))->values(array(trim($postDetails['description']), trim($postDetails['title']), $postDetails['video'], $postDetails['video_status'], $postDetails['site_image'], $postDetails['site_id'], $postDetails['user_id'], $postDetails['date_created'], $postDetails['date_modified']))->execute();
			return true;
		}
		return false;
	}
	
}
?>
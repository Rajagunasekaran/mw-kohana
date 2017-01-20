<?php

defined('SYSPATH') or die('No direct access allowed.');

class Model_dynamicpage extends Model
{
	
	public function get_page_content($site_id, $page_slug){
		$sql 	= "SELECT * FROM page WHERE site_id='$site_id' and page_slug='$page_slug' ";
		//echo $sql; exit;
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		if($list)
			return $list[0];
        return FALSE;
	}
	
	
	  
	
	
}
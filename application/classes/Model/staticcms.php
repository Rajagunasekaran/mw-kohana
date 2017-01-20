<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class Model_staticcms extends Model {
	public function updatePage($updateStr,$condtnStr){
		$sql = "update page set ".$updateStr." WHERE ".$condtnStr;				 
		$query = DB::query(Database::UPDATE,$sql);						
		return $query->execute();
	}
	public function getPageContent($slug,$site_id)
	{
		$sql = "SELECT page_content FROM page where status=1 AND site_id ='".$site_id."' AND  page_slug ='".$slug."'";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
        return isset($list[0]['page_content']) ? $list[0]['page_content'] : $list;;
	}
	public function getsitePages($siteid,$cs='')
	{
		$sql = "SELECT * from page join sites on sites.id = page.site_id where site_id = '".$siteid."'";
		if($cs){
			$sql .=	" and common_status=1";
		}
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
        return $list;
	}
	
}
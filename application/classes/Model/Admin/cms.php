<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class Model_Admin_cms extends Model {
	public function insertPage($array)
	{
		$results = DB::insert('page', array('page_title','page_slug','page_content','site_id','status','common_status','onlyadmin'))
				->values(array($array['page_title'],$array['page_slug'],$array['page_content'],$array['site_id'],$array['status'],
									(isset($array['common_status']))?$array['common_status']:0,
									(isset($array['onlyadmin']))?$array['onlyadmin']:0,
									));
      $results = $results->execute();
		return $results[0];
	}
	public function updatePage($updateStr,$condtnStr){
		$sql = "update page set ".$updateStr." WHERE ".$condtnStr;
		$query = DB::query(Database::UPDATE,$sql);
		//echo $query; die;
		return $query->execute();
	}
	public function getPage($field,$condition=1)
	{
		$sql = "SELECT ".$field." FROM page where ".$condition;
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
        return $list;
	}
	public function getsitePages($siteid,$cs='')
	{
		$sql = "SELECT * from page join sites on sites.id = page.site_id where site_id = '".$siteid."' and status!=3 ";
		if($cs){
			$sql .=	" and common_status=1";
		}else{
			$sql .=	" and common_status=0";
		}
		//echo $sql; exit;
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
        return $list;
	}
	
}
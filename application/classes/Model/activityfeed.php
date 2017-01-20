<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class Model_activityfeed extends Model {
	public function insert($table, $array)
	{
		if($table == 'activity_feed')
			$array['created_date'] = $array['modified_date'] = Helper_Common::get_default_datetime();
			
		$results = DB::insert($table, array_keys($array))->values(array_values($array))->execute();
		return $results[0];
	}
	public function update($table,$data,$array){
		$query = DB::update($table)->set($data)->where($array)->execute();
		return $query->execute();
	}
	public function delete($table, $array)
	{
		$results = DB::delete($table)->where($array)->execute();
		return $results[0];
	}
	public function getdata($table,$array)
	{
		$condition ='';
		$i=0;
		foreach($array as $k=>$v){
			if($i>0){
				$condition .= " and ";
			}
			$condition .= "$k = '$v'";
			$i++;
		}
		
		$sql = "SELECT * FROM $table where ".$condition;
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return $list;
	}
	public function getDeviceInfo($act_feed_id)
	{
		$sql = "SELECT * FROM activity_feed where id ='".$act_feed_id."'";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return (isset($list[0]) ? $list[0] : array());
	}

}
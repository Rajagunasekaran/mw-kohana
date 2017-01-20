<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class Model_error extends Model {
	public function insert($array)
	{
		$results = DB::insert('error_feed', array_keys($array))->values(array_values($array))->execute();
		return $results[0];
	}
	public function update($data,$array){
		$query = DB::update('error_feed')->set($data)->where($array)->execute();
		return $query->execute();
	}
	public function delete($array)
	{
		$results = DB::delete('error_feed')->where($array)->execute();
		return $results[0];
	}
	public function getdata($array,$type = 2)
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
		
		$sql = "SELECT * FROM error_feed where ".$condition;
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return $list;
	}

}
<?php

defined('SYSPATH') or die('No direct access allowed.');

class Model_site extends Model
{
	public function get_question($siteid, $question_id,$common)
	{
		$sql 	= "SELECT qu.id, qu.question FROM sitequestions as qu INNER JOIN sitequestionoptions op  ON qu.id=op.sqid WHERE qu.q_type=0 and qu.site_id = ".$siteid."  and qu.id=".$question_id." and qu.status = 0  "; //echo $sql; die;
		
		if($common==1){
			$sql1 	= "SELECT qu.id, qu.question FROM sitequestions as qu INNER JOIN sitequestionoptions op ON qu.id=op.sqid WHERE qu.q_type=1 and qu.id=".$question_id." and qu.status = 0  "; //echo $sql; die;
			$sql = "($sql) union ($sql1)";
		}
		
		
		$query 	= DB::query(Database::SELECT,$sql);
		$result 	= $query->execute()->as_array();
        return $result;
		/*
		$sql 	= "SELECT * FROM sitequestions WHERE site_id = ".$siteid." and status = 0";
		$query 	= DB::query(Database::SELECT,$sql);
		$result 	= $query->execute()->as_array();
        return $result;*/
	}
	
	public function get_option($siteid, $question_id){
		$sql 	= "SELECT sqid, option FROM sitequestionoptions  WHERE sqid=".$question_id." and status =0 ";
		$query 	= DB::query(Database::SELECT,$sql);
		$result 	= $query->execute()->as_array();
        return $result;
	}
	
	public function checkanswers($site_id,$user_id){
		$sql = "SELECT * FROM sitequestionanswers where site_id=$site_id AND user_id=$user_id AND status=0";
		$query = DB::query(Database::SELECT,$sql);
		return $list = $query->execute()->as_array();
	}
	public function insertans($insert){
		return DB::insert('sitequestionanswers', array_keys($insert) )->values(array_values($insert))->execute();
	}
	public function updateans($insert){
		$update_id = $insert['id'];
		unset($insert['id']);
		return DB::update('sitequestionanswers')->set($insert)->where('id', '=', $update_id)->execute();
	}
	public function changStatuseans($site_id,$sqid,$user_id){
		return DB::update('sitequestionanswers')->set(array('status'=>'1'))->where('sqid', '=', $sqid)->where('site_id', '=', $site_id)->where('user_id', '=', $user_id)->execute();
	}
}	
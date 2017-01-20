<?php

defined('SYSPATH') or die('No direct access allowed.');

class Model_Admin_Questions extends Model
{
	
	public function getCommonQuestions_status($siteid){
		$sql = "SELECT common_question FROM sites where id=$siteid";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return $list;
	}
	
	public function answers($post){
		$sql = "SELECT sqa.sqid, sq.question, if(sqa.sqoid=0,sqa.answer,group_concat(sqo.option,'###')) as answer FROM sitequestionanswers as sqa join sitequestions as sq on sqa.sqid=sq.id left join sitequestionoptions as sqo on sqa.sqoid=sqo.id where sq.q_type=0 and sqa.user_id=".$post["userid"]." and sqa.site_id=".$post["site_id"]." group by sqa.sqid";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return $list;
	}
	public function getQuestions($site_id, $limitCurrent= '',$offset = ''){
		$sql = "SELECT * FROM sitequestions where q_type=0 and status=0 and site_id=$site_id order by sequence asc ";
		if($limitCurrent !=''){
			$limitStart		= $offset;	
			$sql .=	" LIMIT ".$limitStart." , ".$limitCurrent."";
		} 
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return $list;
	}
	public function getallQuestions($site_id, $limitCurrent= '',$offset = ''){
		$sql = "SELECT * FROM sitequestions where q_type=0 and status=0 and  site_id=$site_id order by sequence asc ";
		if($limitCurrent !=''){
			$limitStart		= $offset;	
			$sql .=	" LIMIT ".$limitStart." , ".$limitCurrent."";
		} 
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return $list;
	}
	public function getQuestionOptions($sqid){
		$sql = "SELECT * FROM sitequestionoptions where status=0 and sqid=$sqid order by sequence asc";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return ($list)?$list:'';
	}
	
	public function getQuestion($id){
		$sql = "SELECT * FROM sitequestions where q_type=0 and status=0 and id=$id order by sequence asc";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return ($list)?$list:'';
	}
	
	public function getOption($oid){
		$sql = "SELECT * FROM sitequestionoptions where status=0 and id=$oid order by sequence asc";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return ($list)?$list:'';
	}
	public function insertQuestions($array){
		$array["q_type"] = 0;
		$results = DB::insert('sitequestions', array_keys($array) )->values(array_values($array))->execute();
      return $results[0];
	}
	public function updateQuestions($array,$sqid){
		$results = DB::update('sitequestions')->set($array)->where('id', '=', $sqid)->where('q_type', '=', 0);
      $results = $results->execute();
		return $results[0];
	}
	public function updateQuestionOptions($array,$sqid){
		$results = DB::update('sitequestionoptions')->set($array)->where('sqid', '=', $sqid);
      $results = $results->execute();
		return $results[0];
	}
	public function updateOption($array,$id){
		$results = DB::update('sitequestionoptions')->set($array)->where('id', '=', $id);
		//echo $results;
      $results = $results->execute();
		return $results[0];
	}
	public function updateQuestionSeq($seq,$siteid){
		$query = "UPDATE sitequestions SET sequence = sequence-1 WHERE sequence > $seq and site_id=$siteid  and q_type=0";
		$results = DB::query(Database::UPDATE,$query)->execute();
		return $results;
	}
	public function updateQuestionOptionsSeq($seq,$sqid){
		$query = "UPDATE sitequestionoptions SET sequence = sequence-1 WHERE sequence > $seq and sqid=$sqid";
		$results = DB::query(Database::UPDATE,$query)->execute();
		return $results;
	}
	public function deleteQuestions($sqid){
		$query = DB::delete('sitequestions')->where('id', '=', $sqid)->where('q_type', '=', 0)->execute();
		$query = DB::delete('sitequestionoptions')->where('sqid', '=', $sqid)->execute();
		return true;
	}
	public function deleteQuestionOptions($sqid){
		$query = DB::delete('sitequestionoptions')->where('sqid', '=', $sqid)->execute();
		return true;
	}
	public function deleteOption($id){
		$query = DB::delete('sitequestionoptions')->where('id', '=', $id)->execute();
		return true;
	}
	public function insertQuestionOptions($array){
		$results = DB::insert('sitequestionoptions', array_keys($array) )->values(array_values($array))->execute();
      return $results[0];
	}
	public function getSingleAnswers($user_id){
		$sql = "SELECT sqa.sqid, sq.question, if(sqa.sqoid=0,sqa.answer,group_concat(sqo.option,'###')) as answer FROM sitequestionanswers as sqa join sitequestions as sq on sqa.sqid=sq.id left join sitequestionoptions as sqo on sqa.sqoid=sqo.id where sq.q_type=1 and sqa.user_id=".$user_id." and sqa.sqid in (16,17) group by sqa.sqid";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
		$returnKeyArray = array('height','weight');
		$returnArray = array();
		if(!empty($list) && count($list)>0){
			foreach($list as $keys => $values){
				$returnArray[$returnKeyArray[$keys]] = str_replace('###','',$values['answer']);
			}
		}
      return $returnArray;
	}
}
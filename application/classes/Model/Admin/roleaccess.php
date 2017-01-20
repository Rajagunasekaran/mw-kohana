<?php
defined('SYSPATH') or die('No direct access allowed.');

class Model_Admin_Roleaccess extends Model{
    public function getAllRoleAccess(){
    	$sql = "SELECT rac.id AS cat_id, rac.name AS cat_name, rat.id as type_id,rat.name AS type_name
				FROM `roles_access_category` AS rac
				LEFT JOIN roles_access_type AS rat ON ( rac.id = rat.category_id )
				WHERE 1 ";	
		$query = DB::query(Database::SELECT,$sql);						
		return $query->execute()->as_array();
	}
    public function removeRoleAccess($id){
    	$sql = "DELETE FROM `roles_access` WHERE `id` = '".$id."'";				 
		$query = DB::query(Database::DELETE,$sql);						
		return $query->execute();
	}
	public function insertRoleAcces($array)
	{
		$results = DB::insert('roles_access', array('role_id','site_id','access_type_id'))
				->values(array($array['role_id'],$array['site_id'],$array['access_type_id']))->execute();
		return $results[0];
	}
	public function getRoleAccessByContn($field,$condtn=1) {
		$sql = "SELECT ".$field." FROM `roles_access` where ".$condtn;	
		$query = DB::query(Database::SELECT,$sql);						
		return $query->execute()->as_array();
	}
	public function getRoleAccessTypeByContn($field,$condtn=1) {
		$sql = "SELECT ".$field." FROM `roles_access_type` where ".$condtn;
		$query = DB::query(Database::SELECT,$sql);						
		return $query->execute()->as_array();
	}
}
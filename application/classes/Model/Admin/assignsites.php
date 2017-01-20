<?php
defined('SYSPATH') or die('No direct access allowed.');

class Model_Admin_Assignsites extends ORM{
    protected $_table_name = 'assign_sites';
    
    public function removeSites($user_id){
    	$sql = "DELETE FROM `assign_sites` WHERE `user_id` = '".$user_id."'";				 
		$query = DB::query(Database::DELETE,$sql);						
		return $query->execute();
	}
    public function removeSitesByUser($userid,$site_id){
    	$sql = "DELETE FROM `assign_sites` WHERE `user_id` = '".$userid."' AND `site_id` = '".$site_id."'";				 
		$query = DB::query(Database::DELETE,$sql);						
		return $query->execute();
	}
}
<?php

defined('SYSPATH') or die('No direct access allowed.');

class Model_workouts extends Model
{
	public function getWorkoutDetailsByUser($id_user = 0,$folder_id = 0){
		$sql 	= "SELECT wks.id as wksid, wks.wkout_id, wks.seq_order, wf.focus_grp_title as wkout_focus, wkgd.wkout_color, wc.color_title, wkgd.wkout_title, wkf.folder_title, wkf.id as folder_id, (select count(*) from wkout_seq as wk1 where wk1.parent_folder_id= wkf.id AND wk1.wkout_seq_status =0) as totalRecords, ua.id as access_id, wks.parent_folder_id FROM `wkout_seq` AS wks LEFT JOIN wkout_folders as wkf ON (wks.folder_id=wkf.id AND wkf.folder_status=0) LEFT JOIN wkout_gendata AS wkgd ON (wkgd.wkout_id=wks.wkout_id AND wkgd.status_id !=4) LEFT JOIN wkout_color AS wc ON wkgd.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON (wkgd.wkout_focus=wf.focus_grp_id AND wf.status_id = 1) LEFT JOIN unit_status AS uns ON (wkgd.status_id=uns.status_id) LEFT JOIN roles AS ua ON wkgd.access_id=ua.id where wks.wkout_seq_status = 0 AND wks.parent_folder_id='".$folder_id."'".(!empty($id_user) ? " AND wks.user_id='".$id_user."'" : '' )." order by wks.seq_order asc";
		$query 	= DB::query(Database::SELECT,$sql);
		$result 	= $query->execute()->as_array();
        return $result;
	}
	public function getSampleWorkoutDetails($id_user = 0 ,$folder_id = 0){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "SELECT wks.id as wksid, wks.wkout_sample_id, wks.seq_order, wf.focus_grp_title as wkout_focus, wkgd.wkout_color, wc.color_title, wkgd.wkout_title, wkf.folder_title, wkf.id as folder_id, wkgd.access_id, (select count(*) from wkout_sample_seq as wk1 LEFT JOIN default_site_mod_map as ds1 ON (ds1.record_id = wk1.wkout_sample_id AND ds1.record_type_id=1 ) LEFT JOIN sites s1 ON wk1.site_id=s1.id where s1.is_active = 1 AND s1.is_deleted = 0 AND wk1.parent_folder_id= wkf.id AND wk1.wkout_seq_status =0 ".(!empty($id_user) ? " AND (wk1.user_id='".$id_user."' OR wk1.default_status='1')" : '' )." AND ( (wk1.site_id in (".$site_id.") AND wk1.default_status='0') OR wk1.default_status='1' AND (ds1.record_mod_action!=2 OR ds1.id is NULL))) as totalRecords, ua.id as user_access_id, wks.parent_folder_id,wks.site_id FROM `wkout_sample_seq` AS wks LEFT JOIN wkout_folders as wkf ON (wks.folder_id=wkf.id AND wkf.folder_status=0) LEFT JOIN wkout_sample_gendata AS wkgd ON (wkgd.wkout_sample_id=wks.wkout_sample_id AND wkgd.status_id =1) LEFT JOIN wkout_color AS wc ON wkgd.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON (wkgd.wkout_focus=wf.focus_grp_id AND wf.status_id = 1) LEFT JOIN unit_status AS uns ON (wkgd.status_id=uns.status_id) LEFT JOIN roles AS ua ON wkgd.access_id=ua.id LEFT JOIN sites s ON wks.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = wks.wkout_sample_id AND ds.record_type_id=1 ) where wks.wkout_seq_status = 0 AND wks.parent_folder_id='".$folder_id."' AND ( (wks.site_id in (".$site_id.") AND wks.default_status='0') ".(Helper_Common::hasAccessBySampleWkouts($site_id) ?" OR (wks.default_status='1' AND s.sample_workouts=1 AND (ds.record_mod_action!=2 OR ds.id is NULL)) " : '')." ) AND s.is_active = 1 AND s.is_deleted = 0 order by wks.modified_date desc";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function updateReadStatus($replace_val ,$array = array()){
		if(empty($replace_val)){
			$workout_results = DB::insert('track_read_status', array('wkoutids', 'wkout_type', 'read_by', 'site_id', 'status_id', 'created_date', 'modified_date', 'is_from'))
			->values(array('#'.$array['wkoutids'], $array['wkout_type'], $array['read_by'], $array['site_id'], $array['status_id'], $array['created_date'], $array['modified_date'], '1'))->execute();
			return true;
		}else{
			$sql 	= "Update track_read_status set wkoutids = '".$array['wkoutids']."' where wkoutids like '".$replace_val."' AND wkout_type='".$array['wkout_type']."' AND status_id='1' AND is_from ='1' AND read_by='".$array['read_by']."'";
			return DB::query(Database::UPDATE,$sql)->execute();
		}
	}
	public function getSharedWorkoutDetails($id_user = 0 ,$folder_id = 0){
		$sql 	= "SELECT wks.id as wksid, wks.wkout_share_id, wks.seq_order, wf.focus_grp_title as wkout_focus, wkgd.wkout_color, wc.color_title, wkgd.wkout_title, wkf.folder_title, wkf.id as folder_id, wkgd.access_id, (select count(*) from wkout_share_seq as wk1 where wk1.parent_folder_id= wkf.id AND wk1.wkout_seq_status =0) as totalRecords, ua.id as access_id, wks.parent_folder_id,u.user_fname, u.user_lname,u.id as userid, wks.created_date,wks.shared_msg FROM `wkout_share_seq` AS wks LEFT JOIN wkout_folders as wkf ON (wks.folder_id=wkf.id AND wkf.folder_status=0) LEFT JOIN wkout_share_gendata AS wkgd ON (wkgd.wkout_share_id=wks.wkout_share_id AND wkgd.status_id !=4)  LEFT JOIN wkout_color AS wc ON wkgd.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON (wkgd.wkout_focus=wf.focus_grp_id AND wf.status_id = 1) LEFT JOIN unit_status AS uns ON (wkgd.status_id=uns.status_id) LEFT JOIN roles AS ua ON wkgd.access_id=ua.id Left Join users as u ON u.id= wks.shared_by JOIN sites s ON wks.site_id=s.id where wks.wkout_seq_status = 0 AND wks.parent_folder_id='".$folder_id."' AND wks.shared_for='".$id_user."' AND s.is_active = 1 AND s.is_deleted = 0 order by wks.created_date desc";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getAssignworkoutDetails($id_user = 0 ,$folder_id = 0){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "SELECT wkgd.wkout_assign_id, wkgd.wkout_assign_order, wf.focus_grp_title as wkout_focus, wkgd.wkout_color, wc.color_title, wkgd.wkout_title, wkgd.access_id, ua.id as access_id FROM `wkout_assign_gendata` AS wkgd LEFT JOIN wkout_color AS wc ON wkgd.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON (wkgd.wkout_focus=wf.focus_grp_id AND wf.status_id = 1) LEFT JOIN unit_status AS uns ON (wkgd.status_id=uns.status_id) LEFT JOIN roles AS ua ON wkgd.access_id=ua.id JOIN sites s ON wkgd.site_id=s.id where wkgd.assigned_by='".$id_user."' AND wkgd.status_id !=4 AND wkgd.site_id in (".$site_id.") AND s.is_active = 1 AND s.is_deleted = 0 order by wkgd.assigned_date desc";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	
	}
	public function getLogworkoutDetails($id_user = 0 ,$folder_id = 0){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "SELECT wkgd.wkout_log_id, wkgd.wkout_log_order, wf.focus_grp_title as wkout_focus, wkgd.wkout_color, wc.color_title, wkgd.wkout_title, wkgd.access_id, ua.id as access_id FROM `wkout_log_gendata` AS wkgd LEFT JOIN wkout_color AS wc ON wkgd.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON (wkgd.wkout_focus=wf.focus_grp_id AND wf.status_id = 1) LEFT JOIN unit_status AS uns ON (wkgd.status_id=uns.status_id) LEFT JOIN roles AS ua ON wkgd.access_id=ua.id JOIN sites s ON wkgd.site_id=s.id where wkgd.user_id='".$id_user."' AND wkgd.site_id in (".$site_id.") AND wkgd.status_id !=4 AND s.is_active = 1 AND s.is_deleted = 0 order by wkgd.assigned_date desc";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getSharedunreadCnt($id_user = 0){
		$sql 	= "Select count(wsg.wkout_share_id) as totalshare, trs.wkoutids as totalreadids  from wkout_share_gendata wsg JOIN wkout_share_seq as wss on (wss.wkout_share_id =wsg.wkout_share_id) JOIN sites s ON wss.site_id=s.id Left Join track_read_status as trs on (trs.wkoutids like CONCAT('%#', wsg.wkout_share_id ,'#%') AND trs.wkout_type='1' AND trs.status_id='1' AND trs.read_by='".$id_user."' AND trs.is_from=1) where wsg.user_id='".$id_user."' AND wss.shared_for='".$id_user."' AND wsg.status_id ='1' AND wss.folder_id = '0' AND wss.wkout_seq_status='0' AND s.is_active = 1 AND s.is_deleted = 0";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list[0];
	}
	public function getTotalSharedCnt($id_user = 0){
		$sql 	= "Select count(wsg.wkout_share_id) as totalshare from wkout_share_gendata wsg JOIN wkout_share_seq as wss on (wss.wkout_share_id =wsg.wkout_share_id) JOIN sites s ON wsg.site_id=s.id where wsg.user_id='".$id_user."' AND wss.shared_for='".$id_user."' AND wsg.status_id ='1' AND wss.folder_id = '0' AND wss.wkout_seq_status='0' AND s.is_active = 1 AND s.is_deleted = 0";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list[0]['totalshare'];
	}
	public function getTotalSampleCnt($id_user = 0){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "Select count(distinct(wsg.wkout_sample_id)) as totalsample from wkout_sample_gendata wsg JOIN wkout_sample_seq as wss on (wss.wkout_sample_id =wsg.wkout_sample_id) join sites s on wss.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = wsg.wkout_sample_id AND ds.record_type_id=1 ) where wsg.status_id ='1' AND s.is_active = 1 AND s.is_deleted = 0 AND wss.folder_id = '0' AND wss.wkout_seq_status='0' AND ((wss.site_id in (".$site_id.") AND wss.default_status='0' AND wsg.default_wkout_id ='0') ".(Helper_Common::hasAccessBySampleWkouts($site_id) ? " OR (wss.default_status='1' AND s.sample_workouts=1 AND (ds.record_mod_action!=2 OR ds.id is NULL)) " : '').")";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list[0]['totalsample'];
	}
	public function getTotalWkoutCnt($id_user = 0){
		$site_ids = Helper_Common::getAllSiteId();
		$sql 	= "Select count(wsg.wkout_id) as totalwkout from wkout_gendata wsg JOIN wkout_seq as wss on (wss.wkout_id =wsg.wkout_id) where wsg.user_id='".$id_user."' AND wsg.status_id ='1' AND wss.folder_id = '0' AND wss.wkout_seq_status='0' AND wss.site_id in (".$site_ids.")";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list[0]['totalwkout'];
	}
	public function getSharedunreadDetails($id_user = 0, $parent_fold_id = 0){
		$sql 	= "Select group_concat(wsg.wkout_share_id separator '#') as wkoutids, trs.wkoutids as wkoutidsreplace from wkout_share_gendata wsg JOIN wkout_share_seq as wss on (wss.wkout_share_id =wsg.wkout_share_id) JOIN sites s ON wss.site_id=s.id  Left Join track_read_status as trs on (trs.wkoutids like CONCAT('%#', wsg.wkout_share_id ,'#%') AND trs.wkout_type='1' AND trs.status_id='1' AND trs.read_by='".$id_user."'  AND trs.is_from=1) where wsg.user_id='".$id_user."' AND wss.shared_for='".$id_user."' AND wsg.status_id ='1' AND wss.folder_id = '0' AND wss.wkout_seq_status='0' AND s.is_active = 1 AND s.is_deleted = 0";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]) ? $list[0] : $list);
	}
	public function getSampleWkoutunreadCnt($id_user){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "Select count(wsg.wkout_sample_id) as totalsample, trs.wkoutids as totalreadids  from wkout_sample_gendata wsg JOIN wkout_sample_seq as wss on (wss.wkout_sample_id =wsg.wkout_sample_id) join sites s on wss.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = wsg.wkout_sample_id AND ds.record_type_id=1 ) Left Join track_read_status as trs on (trs.wkoutids like CONCAT('%#', wsg.wkout_sample_id ,'#%') AND trs.wkout_type='2' AND trs.status_id='1' AND trs.read_by='".$id_user."' AND (trs.site_id in (".$site_id.") OR trs.site_id is NULL) AND trs.is_from=1) where wsg.status_id ='1' AND wss.folder_id = '0' AND s.is_active = 1 AND s.is_deleted = 0 AND wss.wkout_seq_status='0' AND ((wss.site_id in (".$site_id.") AND wss.default_status='0' AND wsg.default_wkout_id ='0') ".(Helper_Common::hasAccessBySampleWkouts($site_id) ? " OR (wss.default_status='1' AND s.sample_workouts=1 AND (ds.record_mod_action!=2 OR ds.id is NULL)) " : '').")";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list[0];
	}
	public function getSampleWkoutunreadDetails($id_user,$parent_fold_id = 0){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "Select group_concat(wsg.wkout_sample_id separator '#') as wkoutids, trs.wkoutids as wkoutidsreplace from wkout_sample_gendata wsg JOIN wkout_sample_seq as wss on (wss.wkout_sample_id =wsg.wkout_sample_id) join sites s on wss.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = wsg.wkout_sample_id AND ds.record_type_id=1 ) Left Join track_read_status as trs on (trs.wkoutids like CONCAT('%#', wsg.wkout_sample_id ,'#%') AND trs.wkout_type='2' AND trs.status_id='1' AND trs.read_by='".$id_user."' AND (trs.site_id in (".$site_id.") OR trs.site_id is NULL) AND trs.is_from=1) where wsg.status_id ='1' AND s.is_active = 1 AND s.is_deleted = 0 AND wss.folder_id = '0' AND wss.wkout_seq_status='0' AND ((wss.site_id in (".$site_id.") AND wss.default_status='0' AND wsg.default_wkout_id ='0') ".(Helper_Common::hasAccessBySampleWkouts($site_id) ? " OR (wss.default_status='1' AND s.sample_workouts=1 AND (ds.record_mod_action!=2 OR ds.id is NULL)) " : '').")";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]) ? $list[0] : $list);
	}
	public function getWorkoutDetails($workoutTitle, $limit = 0){
		$sql 	= "SELECT wkgd.wkout_id, wkgd.wkout_color, wc.color_title, wkgd.wkout_title, wkf.folder_title FROM `wkout_gendata` AS wkgd JOIN wkout_seq as ws ON ws.wkout_id =wkgd.wkout_id JOIN wkout_color AS wc ON wkgd.wkout_color=wc.color_id JOIN wkout_focus_grp AS wf ON (wkgd.wkout_focus=wf.focus_grp_id AND wf.status_id = 1) JOIN unit_status AS uns ON (wkgd.status_id=uns.status_id) JOIN roles AS ua ON wkgd.access_id=ua.id  LEFT JOIN wkout_seq as wks ON (wkgd.wkout_id=wks.wkout_id and wks.parent_folder_id=0) LEFT JOIN wkout_folders as wkf ON (wks.folder_id=wkf.id AND wkf.folder_status=0)  WHERE wkgd.status_id=1 ".( !empty($workoutTitle) ? ' AND wkgd.wkout_title like "'.$workoutTitle.'%"' : '')." AND ws.wkout_seq_status=0 Order by wkgd.wkout_id asc ".(!empty($limit) ? ' limit 5' : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getFolderDetailsByUser($userId = 0,$fid = 0){
		$sql 	= "SELECT wkf.id as folder_id, wkf.folder_title, wkf.folder_type, wks.id, wks.wkout_id, wks.parent_folder_id, (select folder_title from wkout_folders as wkf1 where  wkf1.id= wks.parent_folder_id) as parent_folder_name from wkout_folders as wkf Join wkout_seq as wks on wks.folder_id= wkf.id Where wkf.id ='".$fid."' AND  wks.user_id='".$userId."' order by wkf.modified_date DESC ";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list[0];
	}
	public function getSampleFolderDetailsByUser($userId = 0,$fid = 0){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "SELECT wkf.id as folder_id, wks.id, wks.wkout_sample_id, wks.parent_folder_id, (select folder_title from wkout_folders as wkf1 where  wkf1.id= wks.parent_folder_id) as parent_folder_name from wkout_folders as wkf Join wkout_sample_seq as wks on wks.folder_id= wkf.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = wks.wkout_sample_id AND ds.record_type_id=1 ) join sites s on wks.site_id=s.id  Where wkf.id ='".$fid."' AND s.is_active = 1 AND s.is_deleted = 0 AND wks.wkout_seq_status=0 AND ((wks.site_id in (".$site_id.") AND wks.default_status = '0') ".(Helper_Common::hasAccessBySampleWkouts($site_id) ? " OR (wks.default_status='1' AND s.sample_workouts=1 AND (ds.record_mod_action!=2 OR ds.id is NULL)) " : '')." ) ".(!empty($userId) ? " AND wks.user_id='".$userId."' " : '' )." order by wks.modified_date DESC";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]) ? $list[0] : $list);
	}
	public function getShareFolderDetailsByUser($userId = 0,$fid = 0){
		$sql 	= "SELECT wkf.id as folder_id, wkf.folder_title, wkf.folder_type, wks.id, wks.wkout_share_id, wks.parent_folder_id, (select folder_title from wkout_folders as wkf1 where  wkf1.id= wks.parent_folder_id) as parent_folder_name from wkout_folders as wkf Join wkout_share_seq as wks on wks.folder_id= wkf.id JOIN sites s ON wks.site_id=s.id  Where wkf.id ='".$fid."' AND  wks.shared_for='".$userId."' AND s.is_active = 1 AND s.is_deleted = 0 order by wks.modified_date DESC";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]) ? $list[0] : $list);
	}
	public function getWkoutFolderDetailsByUser($userId = 0, $folder_id){
		$sql 	= "SELECT wkf.id as folder_id , wkf.folder_title, wkf.folder_type, wkf.folder_status, wkf.folder_access, wkf.created_by, wkf.modified_by, wkf.created_date, wkf.modified_date,wks.id, wks.parent_folder_id, wks.folder_id, wks.wkout_id, wks.wkout_seq_status, wks.seq_order, wks.user_id, wks.created_date, wks.modified_date from wkout_seq as wks join wkout_folders as wkf on wks.folder_id= wkf.id  Where wkf.id ='".$folder_id."' AND  wks.user_id='".$userId."' AND wks.wkout_seq_status=0";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list[0];
	}
	public function getSampleWkoutFolderDetails($userId = 0, $folder_id){
		$sql 	= "SELECT wkf.id as folder_id , wkf.folder_title, wkf.folder_type, wkf.folder_status, wkf.folder_access, wkf.created_by, wkf.modified_by, wkf.created_date, wkf.modified_date,wks.id, wks.parent_folder_id, wks.folder_id, wks.wkout_sample_id, wks.wkout_seq_status, wks.seq_order, wks.user_id, wks.created_date, wks.modified_date from wkout_sample_seq as wks join wkout_folders as wkf on wks.folder_id= wkf.id  Where wkf.id ='".$folder_id."' ".(!empty($id_user) ? " AND wks.user_id='".$id_user."' " : '' )." AND wks.wkout_seq_status=0";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list[0];
	}
	public function getWorkoutFolderDetails($id_user = 0, $folder_id = 0){
		$sql 	= "SELECT id,wkout_id,parent_folder_id, count(id) as totalRecords from wkout_seq as wks Where wks.id ='".$folder_id."' Order by wks.seq_order asc";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getFolderDetails($fid){
		$sql 	= "SELECT id,folder_title,folder_type from wkout_folders as wkf Where wkf.id ='".$fid."' and wkf.folder_status=0";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list[0];
	}
	public function getwkoutSeqOrder($parent_folder_id, $user_id){
		$sql 	= "SELECT MAX(seq_order) as seq_order from wkout_seq as wkf Where wkf.parent_folder_id ='".$parent_folder_id."' AND wkf.user_id ='".$user_id."' AND wkout_seq_status=0";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]['seq_order']) ? $list[0]['seq_order'] : '0');
	}
	public function updateWkoutSeqOrder($parent_folder_id, $user_id){
		$sql 	= "Update wkout_seq set seq_order = seq_order+1 Where parent_folder_id ='".$parent_folder_id."' AND user_id ='".$user_id."' AND wkout_seq_status=0";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function updateSampleWkoutSeqOrder($parent_folder_id, $user_id){
		$sql 	= "Update wkout_sample_seq set seq_order = seq_order+1 Where parent_folder_id ='".$parent_folder_id."'  AND wkout_seq_status=0"; //AND user_id ='".$user_id."'
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function getCurrentSeqorder($wkseqId){
		$sql 	= "SELECT seq_order from wkout_seq as wkf Where wkf.id ='".$wkseqId."' AND wkout_seq_status=0";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]['seq_order']) ? $list[0]['seq_order'] : '0');
	}
	public function getShareCurrentSeqorder($wkseqId){
		$sql 	= "SELECT seq_order from wkout_share_seq as wkf Where wkf.id ='".$wkseqId."' AND wkout_seq_status=0";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]['seq_order']) ? $list[0]['seq_order'] : '0');
	}
	public function getCurrentSeqorderByWkoutId($wkoutId){
		$sql 	= "SELECT id, seq_order, parent_folder_id, user_id from wkout_seq as wkf Where wkf.wkout_id ='".$wkoutId."' AND wkout_seq_status=0";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]) ? $list[0] : '');
	}
	public function getCurrentSeqorderByShareWkoutId($wkoutId){
		$sql 	= "SELECT id, seq_order, parent_folder_id, shared_for as user_id from wkout_share_seq as wkf Where wkf.wkout_share_id ='".$wkoutId."' AND wkout_seq_status=0";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]) ? $list[0] : '');
	}
	public function updateWkoutSeqOrderOtherthanFirst($parent_folder_id, $wkseq_ids, $user_id){
		$currentseqorder = $this->getCurrentSeqorder($wkseq_ids);
		$sql 	= "Update wkout_seq set seq_order = seq_order+1 Where parent_folder_id ='".$parent_folder_id."' AND user_id ='".$user_id."' AND wkout_seq_status=0 AND id not in (".$wkseq_ids.") and  seq_order > ".$currentseqorder;
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function updateWkoutSeqOrderOtherthanFirstSeq($parent_folder_id, $wkseq_ids, $user_id){
		$currentseqorder = $this->getCurrentSeqorder($wkseq_ids);
		$sql 	= "Update wkout_seq set seq_order = seq_order+1 Where parent_folder_id ='".$parent_folder_id."' AND user_id ='".$user_id."' AND wkout_seq_status=0 AND id not in (".$wkseq_ids.") and  seq_order >= ".$currentseqorder;
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function updateShareWkoutSeqOrderOtherthanFirstSeq($parent_folder_id, $wkseq_ids, $user_id){
		$currentseqorder = $this->getShareCurrentSeqorder($wkseq_ids);
		$sql 	= "Update wkout_share_seq set seq_order = seq_order+1 Where parent_folder_id ='".$parent_folder_id."' AND shared_by ='".$user_id."' AND wkout_seq_status=0 AND id not in (".$wkseq_ids.") and  seq_order >= ".$currentseqorder;
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function updateWkoutSeqOrderParentFolder($parent_folder_id, $wkseq_ids, $user_id){
		$sql 	= "Update wkout_seq set seq_order = seq_order+1 Where parent_folder_id ='".$parent_folder_id."' AND user_id ='".$user_id."' AND wkout_seq_status=0 AND id not in (".$wkseq_ids.")";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function updateWkoutSeqOrderParentFolderMin($parent_folder_id, $wkseq_ids, $user_id){
		$currentseqorder = $this->getCurrentSeqorder($wkseq_ids);
		$sql 	= "Update wkout_seq set seq_order = seq_order-1 Where parent_folder_id ='".$parent_folder_id."' AND user_id ='".$user_id."' AND wkout_seq_status=0 AND id not in (".$wkseq_ids.") and  seq_order > ".$currentseqorder;
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function insertWorkoutDetails($array){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$user_access = $this->getUserRole();
		$workout_results = DB::insert('wkout_gendata', array('wkout_group', 'wkout_title', 'wkout_color', 'wkout_order', 'user_id', 'site_id', 'status_id', 'access_id', 'wkout_focus', 'wkout_poa', 'wkout_poa_time', 'date_created', 'date_modified'))
				->values(array($array['wkout_group'], $array['wkout_title'], $array['wkout_color'], $array['wkout_order'], $array['user_id'], $site_id, $array['status_id'], (isset($array['access_id']) ? $array['access_id'] : $user_access), (isset($array['wkout_focus']) ? $array['wkout_focus'] : '0'), (isset($array['wkout_poa']) ? $array['wkout_poa'] : '0'), (isset($array['wkout_poa_time']) ? $array['wkout_poa_time'] : '0'),$array['created_date'], $array['modified_date']))->execute();
				
		$this->updateWkoutSeqOrder((isset($array['parent_folder_id']) ? $array['parent_folder_id'] : '0'), $array['user_id']);
		
		$seg_results = DB::insert('wkout_seq', array('parent_folder_id', 'folder_id', 'wkout_id', 'site_id', 'seq_order', 'user_id', 'created_date', 'modified_date'))
				->values(array((isset($array['parent_folder_id']) ? $array['parent_folder_id'] : '0'), '0', $workout_results[0], $site_id, '1' ,$array['user_id'], $array['created_date'], $array['modified_date']))->execute();
		return $workout_results[0];
	}
	
	
	public function insertSampleWorkoutDetails($array){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$workout_results = DB::insert('wkout_sample_gendata', array('wkout_group', 'wkout_title', 'wkout_color', 'wkout_order', 'site_id', 'status_id', 'access_id', 'wkout_focus', 'wkout_poa', 'wkout_poa_time'))
				->values(array($array['wkout_group'], $array['wkout_title'], $array['wkout_color'], $array['wkout_order'], $site_id, $array['status_id'], $array['access_id'], $array['wkout_focus'], $array['wkout_poa'], $array['wkout_poa_time']))->execute();
				
		$this->updateSampleWkoutSeqOrder($array['parent_folder_id'], '');
		
		$seg_results = DB::insert('wkout_sample_seq', array('parent_folder_id', 'folder_id', 'wkout_sample_id', 'site_id', 'seq_order',  'created_date', 'modified_date'))
				->values(array($array['parent_folder_id'], '0', $workout_results[0], $site_id, '1' , $array['created_date'], $array['modified_date']))->execute();
		return $workout_results[0];
	}
	
	public function insertWorkoutDetailsByseqOrder($array){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$workout_results = DB::insert('wkout_gendata', array('wkout_group', 'wkout_title', 'wkout_color', 'wkout_order', 'user_id', 'site_id', 'status_id', 'access_id', 'wkout_focus', 'wkout_poa', 'wkout_poa_time','date_created', 'date_modified'))
				->values(array($array['wkout_group'], $array['wkout_title'], $array['wkout_color'], $array['wkout_order'], $array['user_id'], $site_id, $array['status_id'], $array['access_id'], $array['wkout_focus'], $array['wkout_poa'], $array['wkout_poa_time'], $array['created_date'], $array['modified_date']))->execute();
		$seg_results = DB::insert('wkout_seq', array('parent_folder_id', 'folder_id', 'wkout_id', 'site_id', 'seq_order', 'user_id', 'created_date', 'modified_date'))
				->values(array($array['parent_folder_id'], '0', $workout_results[0], $site_id, $array['seq_order'] ,$array['user_id'], $array['created_date'], $array['modified_date']))->execute();
		$this->updateWkoutSeqOrderOtherthanFirstSeq($array['parent_folder_id'], $seg_results[0], $array['user_id']);
		return $workout_results[0];
	}
	public function insertShareWorkoutDetailsByseqOrder($array){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$workout_results = DB::insert('wkout_share_gendata', array('wkout_id', 'from_wkout', 'wkout_group', 'wkout_title', 'wkout_color', 'wkout_order', 'user_id', 'site_id', 'status_id', 'access_id', 'wkout_focus', 'wkout_poa', 'wkout_poa_time', 'created', 'modified', 'modified_by'))
				->values(array($array['wkout_id'], $array['from_wkout'], $array['wkout_group'], $array['wkout_title'], $array['wkout_color'], (isset($array['wkout_order']) ? $array['wkout_order'] : '0'), $array['shared_for'], (isset($array['site_id']) && !empty($array['site_id']) ? $array['site_id'] : $site_id), $array['status_id'], $array['access_id'], $array['wkout_focus'], $array['wkout_poa'], $array['wkout_poa_time'], $array['created'], $array['modified'],  $array['shared_for']))->execute();
		$seg_results = DB::insert('wkout_share_seq', array('parent_folder_id', 'folder_id', 'wkout_share_id', 'site_id', 'seq_order', 'shared_by', 'shared_for', 'created_date', 'modified_date','shared_msg'))
				->values(array($array['parent_folder_id'], '0', $workout_results[0], (isset($array['site_id']) && !empty($array['site_id']) ? $array['site_id'] : $site_id), $array['seq_order'] ,$array['shared_by'], $array['shared_for'], $array['created_date'], $array['modified_date'],$array['shared_msg']))->execute();
		$this->updateShareWkoutSeqOrderOtherthanFirstSeq($array['parent_folder_id'], $seg_results[0], $array['shared_for']);
		return $workout_results[0];
	}
	public function insertFolderDetails($array){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$folder_results = DB::insert('wkout_folders', array('folder_title', 'created_by', 'created_date', 'modified_date'))
				->values(array($array['folder_title'], $array['created_by'], $array['created_date'], $array['modified_date']))->execute();
		
		$this->updateWkoutSeqOrder($array['parent_folder_id'], $array['user_id']);
		
		$seg_results = DB::insert('wkout_seq', array('parent_folder_id', 'folder_id', 'site_id', 'seq_order', 'user_id', 'created_date', 'modified_date'))
				->values(array($array['parent_folder_id'], $folder_results[0], $site_id, '1' ,$array['user_id'], $array['created_date'], $array['modified_date']))->execute();

		return $folder_results[0];
	}
	public function updateFolderDetails($array,$id){
		$query = DB::update('wkout_folders')->set(array(
											'folder_title' 	=> $array['folder_title'], 
											'modified_by' 	=> $array['modified_by'], 
											'modified_date' => $array['modified_date']
											))->where('id', '=', $id)->execute();
		return $query[0];
	}
	public function deleteWorkoutDetails($parent_folder_id, $wkout_id, $user_id){
		$query = DB::update('wkout_gendata')->set(array('status_id' => 4))->where('wkout_id', '=', $wkout_id)->where('user_id', '=', $user_id)->execute();
		$query = DB::update('wkout_seq')->set(array('wkout_seq_status' 	=> 1))->where('wkout_id', '=', $wkout_id)->where('user_id', '=', $user_id)->execute();
		$sql 	= "Update wkout_seq set seq_order = seq_order-1 Where parent_folder_id ='".$parent_folder_id."' AND user_id ='".$user_id."' AND wkout_seq_status=0";
		return DB::query(Database::UPDATE,$sql)->execute();
		
		return $query[0];
	}
	public function deleteFolderDetails($folder_id, $user_id){
		$query = DB::update('wkout_folders')->set(array('folder_status' => 1))->where('id', '=', $folder_id)->execute();
		$query = DB::update('wkout_seq')->set(array('wkout_seq_status' 	=> 1))->where('folder_id', '=', $folder_id)->where('user_id', '=', $user_id)->execute();
		
		$wkout_seqResult = $this->getWorkoutFolderDetails($user_id,$folder_id);
		$parent_folder_id = (isset($wkout_seqResult['parent_folder_id']) ? $wkout_seqResult['parent_folder_id'] : '0');
		$this->updateWkoutSeqOrder($parent_folder_id, $user_id);
		
		$sql 	= "Update wkout_seq set parent_folder_id = '".$parent_folder_id."' Where parent_folder_id ='".$folder_id."' AND user_id ='".$user_id."' AND wkout_seq_status=0";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function getworkoutById($id_user = 0,$wk_it){
		$sql 	= "SELECT wkgd.user_id,wkgd.wkout_id,wkgd.wkout_focus, wkgd.wkout_poa, wkgd.wkout_poa_time, wkgd.wkout_title, wkgd.wkout_group, wkgd.wkout_color, g1.color_id, g1.color_title, wkgd.wkout_order, wkgd.user_id, wkgd.status_id, wkgd.access_id, ws.parent_folder_id, ws.id as wks_id FROM wkout_gendata AS wkgd LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id LEFT JOIN wkout_seq as ws on ws.wkout_id = wkgd.wkout_id WHERE wkgd.wkout_id ='".$wk_it."'  ".(!empty($id_user) ? "  AND wkgd.user_id='".$id_user."'" : '' );
		
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return isset($list[0]) ? $list[0] : $list;
	}
	public function getSampleworkoutById($id_user = 0,$wk_sample_id){
		$sql 	= "SELECT wkgd.wkout_sample_id,wkgd.wkout_focus, wkgd.wkout_poa, wkgd.wkout_poa_time, wkgd.wkout_title, wkgd.wkout_group, wkgd.wkout_color, g1.color_id, g1.color_title, wkgd.wkout_order, wkgd.user_id, wkgd.status_id, wkgd.access_id, ws.parent_folder_id, ws.id as wks_id FROM wkout_sample_gendata AS wkgd LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id LEFT JOIN wkout_sample_seq as ws on ws.wkout_sample_id = wkgd.wkout_sample_id WHERE wkgd.wkout_sample_id ='".$wk_sample_id."' AND wkgd.status_id!=4 ".(!empty($id_user) ? " AND wkgd.user_id='".$id_user."'" : '' );
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return isset($list[0]) ? $list[0] : $list;
	}
	public function getShareworkoutById($id_user = 0,$wk_share_id){
		$sql 	= "SELECT wkgd.wkout_share_id,wkgd.wkout_focus, wkgd.wkout_poa, wkgd.wkout_poa_time, wkgd.wkout_title, wkgd.wkout_group, wkgd.wkout_color, g1.color_id, g1.color_title, wkgd.wkout_order, wkgd.user_id, wkgd.status_id, wkgd.access_id, ws.parent_folder_id, ws.id as wks_id FROM wkout_share_gendata AS wkgd LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id LEFT JOIN wkout_share_seq as ws on ws.wkout_share_id = wkgd.wkout_share_id WHERE wkgd.wkout_share_id ='".$wk_share_id."' AND wkgd.status_id=1 ".(!empty($id_user) ? " AND wkgd.user_id='".$id_user."'" : '' );
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return isset($list[0]) ? $list[0] : $list;
	}
	public function getShareAssignworkoutById($wk_share_id,$share_user = 0,$assign_user = 0){
		$sql 	= "SELECT wkgd.wkout_share_id,wkgd.wkout_focus, wkgd.wkout_poa, wkgd.wkout_poa_time, wkgd.wkout_title, wkgd.wkout_group, wkgd.wkout_color, g1.color_id, g1.color_title, wkgd.wkout_order, wkgd.user_id, wkgd.status_id, wkgd.access_id, ws.parent_folder_id, ws.id as wks_id, afs.* FROM assign_from_share as afs Join wkout_share_gendata AS wkgd on wkgd.wkout_share_id = afs.wkout_share_id LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id JOIN wkout_share_seq as ws on ws.wkout_share_id = afs.wkout_share_id Where afs.wkout_share_id ='".$wk_share_id."' AND afs.is_active=1 AND afs.inserted_assign_id='0' AND afs.status=1 AND wkgd.status_id=1 ".(!empty($assign_user) ? " AND wkgd.user_id='".$assign_user."' AND afs.assigned_user_id='".$assign_user."' " : '' ).(!empty($share_user) ? " AND afs.shared_user_id='".$share_user."'" : '' );
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getShareAssignworkoutByIdvalue($id,$share_user = 0,$assign_user = 0){
		$sql 	= "SELECT wkgd.wkout_share_id,wkgd.wkout_focus, wkgd.wkout_poa, wkgd.wkout_poa_time, wkgd.wkout_title, wkgd.wkout_group, wkgd.wkout_color, g1.color_id, g1.color_title, wkgd.wkout_order, wkgd.user_id, wkgd.status_id, wkgd.access_id, ws.parent_folder_id, ws.id as wks_id, afs.* FROM assign_from_share as afs Join wkout_share_gendata AS wkgd on wkgd.wkout_share_id = afs.wkout_share_id LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id JOIN wkout_share_seq as ws on ws.wkout_share_id = afs.wkout_share_id Where afs.id ='".$id."' AND afs.is_active=1 AND afs.inserted_assign_id='0' AND afs.status=1 AND wkgd.status_id=1 ".(!empty($assign_user) ? " AND wkgd.user_id='".$assign_user."' AND afs.assigned_user_id='".$assign_user."' " : '' ).(!empty($share_user) ? " AND afs.shared_user_id='".$share_user."'" : '' );
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]) ? $list[0] : $list);
	}
	public function getAllFocus($focus_id = 0){
		$sql 	= "SELECT focus_grp_id as focus_id,focus_grp_title as focus_opt_title FROM wkout_focus_grp WHERE status_id = 1 ".(!empty($focus_id) ? ' AND focus_grp_id="'.$focus_id."'" : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getExerciseSet($wkout_id){
		$sql = "SELECT * FROM goal_gendata AS gset LEFT JOIN goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gset.wkout_id=".$wkout_id." AND gset.status_id=1 ORDER BY gset.goal_order";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getSampleExerciseSet($wkout_sample_id){
		$sql = "SELECT * FROM wkout_sample_goal_gendata AS gset LEFT JOIN wkout_sample_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gset.wkout_sample_id=".$wkout_sample_id." AND gset.status_id=1 ORDER BY gset.goal_order";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getExerciseOfDay(){
		$sql = "SELECT * FROM unit_gendata AS ugd LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE ugd.featured=1 AND ugd.access_id=2 AND ugd.status_id=1  AND ugd.is_shown='0' ORDER BY RAND() limit 1";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        /** cache the resultant **/
		$resultData	   	= isset($list[0]) ? $list[0] : $list;
		if(!empty($resultData) && count($resultData)>0){
			$serializedData = serialize($resultData);
			$cacheFilename = DOCROOT."assets/cache/".date("Y-m-d").'-ExerciseDayRecord';
			file_put_contents($cacheFilename, $serializedData);
			$sql 	= "Update unit_gendata set is_shown = '1' Where unit_id ='".$resultData['unit_id']."'";
			DB::query(Database::UPDATE,$sql)->execute();
			return $resultData;
		}else{
			$sql 	= "Update unit_gendata set is_shown = '0' Where featured=1 AND access_id=2  AND status_id=1 AND feat_img!=0";
			DB::query(Database::UPDATE,$sql)->execute();
			$sql = "SELECT * FROM unit_gendata AS ugd LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE ugd.featured=1 AND ugd.access_id=2 AND ugd.status_id=1  AND ugd.is_shown='0' ORDER BY RAND() limit 1";
			$query 	= DB::query(Database::SELECT,$sql);
			$list 	= $query->execute()->as_array();
			/** cache the resultant **/
			$resultData	   	= isset($list[0]) ? $list[0] : $list;
			if(!empty($resultData) && count($resultData)>0){
				$serializedData = serialize($resultData);
				$cacheFilename = DOCROOT."assets/cache/".date("Y-m-d").'-ExerciseDayRecord';
				file_put_contents($cacheFilename, $serializedData);
				$sql 	= "Update unit_gendata set is_shown = '1' Where unit_id ='".$resultData['unit_id']."'";
				DB::query(Database::UPDATE,$sql)->execute();
				return $resultData;
			}
		}
		/** cache the resultant **/
	}
	public function getExerciseById($unit_id){
		$sql = "SELECT xrgd.unit_id, xrgd.title, xrgd.status_id, g1.status_title, xrgd.created_by, g10.id as access_id, g10.name as access_title, xrgd.feat_img, xrgd.feat_vid, xrgd.type_id, g2.type_title, xrgd.musprim_id, g3.muscle_id, g3.muscle_title, xrgd.equip_id,g4.img_url,g4.img_title, g5.equip_title, xrgd.mech_id, g6.mech_title, xrgd.level_id, g7.level_title, xrgd.sport_id, g8.sport_title, xrgd.force_id, g9.force_title, xrgd.descbr, xrgd.descfull FROM unit_gendata AS xrgd LEFT JOIN unit_status AS g1 ON xrgd.status_id=g1.status_id LEFT JOIN unit_type AS g2 ON xrgd.type_id=g2.type_id LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id LEFT JOIN unit_mech AS g6 ON xrgd.mech_id=g6.mech_id LEFT JOIN unit_level AS g7 ON xrgd.level_id=g7.level_id LEFT JOIN unit_sport AS g8 ON xrgd.sport_id=g8.sport_id LEFT JOIN unit_force AS g9 ON xrgd.force_id=g9.force_id LEFT JOIN roles AS g10 ON xrgd.access_id=g10.id WHERE xrgd.unit_id = '" . $unit_id . "'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return isset($list[0]) ? $list[0] : $list;
	}
	public function getExerciseSetDetailsByWorkout($wkout_id,$goal_id){
		$sql = "SELECT gset.*, setvars.*, xrgd.unit_id, xrgd.title, xrgd.status_id, g1.status_title, g10.id as access_id, g10.name as access_title, xrgd.feat_img, xrgd.feat_vid, xrgd.type_id, g2.type_title, xrgd.musprim_id, g3.muscle_title, g4.img_url, xrgd.equip_id, g5.equip_title, xrgd.mech_id, g6.mech_title, xrgd.level_id, g7.level_title, xrgd.sport_id, g8.sport_title, xrgd.force_id, g9.force_title, xrgd.descbr, xrgd.descfull FROM goal_gendata AS gset LEFT JOIN goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS xrgd on xrgd.unit_id = gset.goal_unit_id LEFT JOIN unit_status AS g1 ON xrgd.status_id=g1.status_id LEFT JOIN unit_type AS g2 ON xrgd.type_id=g2.type_id LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id LEFT JOIN unit_mech AS g6 ON xrgd.mech_id=g6.mech_id LEFT JOIN unit_level AS g7 ON xrgd.level_id=g7.level_id LEFT JOIN unit_sport AS g8 ON xrgd.sport_id=g8.sport_id LEFT JOIN unit_force AS g9 ON xrgd.force_id=g9.force_id LEFT JOIN roles AS g10 ON xrgd.access_id=g10.id WHERE gset.goal_id in (" . $goal_id . ") AND gset.wkout_id='".$wkout_id."' AND gset.status_id=1 ORDER BY gset.goal_order";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getSampleExerciseSetDetailsByWorkout($wkout_sample_id,$goal_id){
		$sql = "SELECT gset.*, setvars.*, xrgd.unit_id, xrgd.title, xrgd.status_id, g1.status_title, g10.id as access_id, g10.name as access_title, xrgd.feat_img, xrgd.feat_vid, xrgd.type_id, g2.type_title, xrgd.musprim_id, g3.muscle_title, g4.img_url, xrgd.equip_id, g5.equip_title, xrgd.mech_id, g6.mech_title, xrgd.level_id, g7.level_title, xrgd.sport_id, g8.sport_title, xrgd.force_id, g9.force_title, xrgd.descbr, xrgd.descfull FROM wkout_sample_goal_gendata AS gset LEFT JOIN wkout_sample_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS xrgd on xrgd.unit_id = gset.goal_unit_id LEFT JOIN unit_status AS g1 ON xrgd.status_id=g1.status_id LEFT JOIN unit_type AS g2 ON xrgd.type_id=g2.type_id LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id LEFT JOIN unit_mech AS g6 ON xrgd.mech_id=g6.mech_id LEFT JOIN unit_level AS g7 ON xrgd.level_id=g7.level_id LEFT JOIN unit_sport AS g8 ON xrgd.sport_id=g8.sport_id LEFT JOIN unit_force AS g9 ON xrgd.force_id=g9.force_id LEFT JOIN roles AS g10 ON xrgd.access_id=g10.id WHERE gset.goal_id in (" . $goal_id . ") AND gset.wkout_sample_id='".$wkout_sample_id."' AND gset.status_id=1 ORDER BY gset.goal_order";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getExerciseSetAllDetailsByWorkout($wkout_id, $goal_id, $userId){
		$sql = "SELECT gset.*, setvars.* FROM goal_gendata AS gset LEFT JOIN goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS xrgd on xrgd.unit_id = gset.goal_unit_id LEFT JOIN unit_status AS g1 ON xrgd.status_id=g1.status_id LEFT JOIN roles AS g10 ON xrgd.access_id=g10.id WHERE gset.goal_id = '" . $goal_id . "' AND gset.wkout_id='".$wkout_id."' AND gset.user_id='".$userId."' AND gset.status_id=1 ORDER BY gset.goal_order";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return isset($list[0]) ? $list[0] : $list;
	}
	public function getExerciseSetTagDetailsByUnitId($unit_id){
		$sql = "SELECT * FROM `unit_tag` AS tagged LEFT JOIN unit_gendata AS xrgd ON tagged.unit_id=xrgd.unit_id LEFT JOIN tag AS tag ON tagged.tag_id=tag.tag_id LEFT JOIN unit_status AS g1 ON tag.status_id=g1.status_id LEFT JOIN roles AS g2 ON tag.access_id=g2.id WHERE xrgd.unit_id = '" . $unit_id . "' ORDER BY tagged.tag_id	DESC";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getExerciseSetRatingByUnitId($unit_id){
		$sql = "SELECT rate_value FROM `unit_gendata_rating` WHERE unit_id = '" . $unit_id . "' AND is_active=1";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		$rate = 0.0;
		$cnt  = 0;
		$rateValue = 0.00;
		if(count($list)>0){
			foreach($list as $keys => $values){
				if($values['rate_value']>0 && $values['rate_value'] <= 10){
					$rate	+= $values['rate_value'];
					$cnt++;
				}
			}
		}
		if($cnt > 0)
			$rateValue = sprintf('%0.2f', $rate/$cnt);
		return $rateValue;
	}
	public function isUserRatedbyUnitId($unit_id, $user_id){
		$sql = "SELECT rate_id FROM `unit_gendata_rating` WHERE unit_id = '" . $unit_id . "' AND user_id='".$user_id."'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return (isset($list[0]) ? false : true);
	}
	public function getGoalValues($set_table_variable,$goal_id){
		$sql = "SELECT * From set_".$set_table_variable." WHERE ".$set_table_variable."_id = '".$goal_id."'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		$label  = '';
		if(!empty($list[0]))
			if($set_table_variable !="int"){ $label=$list[0][$set_table_variable.'_title']; }else{$label=$list[0][$set_table_variable.'_opt_id']; }
        return $label;
	}
	
	public function getGoalVars($set_table_variable,$goal_id){
		$sql = "SELECT * FROM goal_vars AS gv LEFT JOIN	set_".$set_table_variable." AS g1 ON gv.goal_".$set_table_variable."_id=g1.".$set_table_variable."_id WHERE gv.goal_id='".$goal_id."'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		$label  = '';
		if(!empty($list[0]))
			if($set_table_variable !="int"){ $label=$list[0][$set_table_variable.'_title']; }else{$label=$list[0][$set_table_variable.'_opt_id']; }
        return $label;
	}
	public function getAssignGoalVars($set_table_variable,$goal_id){
		$sql = "SELECT * FROM wkout_assign_goal_vars AS gv LEFT JOIN set_".$set_table_variable." AS g1 ON gv.goal_".$set_table_variable."_id=g1.".$set_table_variable."_id WHERE gv.goal_id='".$goal_id."'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		$label  = '';
		if(!empty($list[0]))
			if($set_table_variable !="int"){ $label=$list[0][$set_table_variable.'_title']; }else{$label=$list[0][$set_table_variable.'_opt_id']; }
        return $label;
	}
	public function getcheckboxes($name, $before_var, $after_var, $id_var, $record_table, $alt_name, $tabindex){
		// global variables for populating this chkbx
		$chkbx			= 	$name;					// parameter 1 :: table name where chkbx options are managed
		//$before_var	= 	"unit_";				// parameter 2 :: prefix added to the beginning of the table name
		$table			=	$before_var.$chkbx;		// table where the select options are managed
		//$after_var	=	"_title";				// parameter 3 :: suffix appended to the end of the table name
		$column			=	$chkbx.$after_var;
		//$id_var		=	"_id";					// parameter 4 :: suffix appended to the end of the table name
		$column_row_id	=	$chkbx.$id_var;				// muscle_id
		$record_table	=	$record_table;			// parameter 5 :: record table where the $name ID is recorded
		$alt_name		=	$alt_name;				// parameter 6 :: ALTERNATE record table where the $name ID is recorded

		$chkd_bx_name	=	$alt_name;					// if different then the $name
		$chkd_bx_table	=	$before_var.$chkd_bx_name;	// unit_[]
		$chkd_bx_id		=	$chkd_bx_name.$id_var; 		// []_id
		$sql 			=   "SELECT * FROM ".$table." ORDER BY ".$column;
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getExerciseRecordData($whereStart, $whereEnd, $where, $startLimit,$endLimit){
		$sql = "SELECT * FROM  unit_gendata AS xrgd	LEFT JOIN unit_muscle AS g3	ON xrgd.musprim_id=g3.muscle_id	LEFT JOIN img AS g4	ON xrgd.feat_img=g4.img_id LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id WHERE xrgd.status_id = 1 AND xrgd.access_id = 2 ".$whereStart." ".$where." ".$whereEnd." ORDER BY	xrgd.unit_id DESC LIMIT ".$startLimit." , ".$endLimit;
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function doCopyForWorkoutFolderById($type, $userId, $wkout_Folder, $folder_id){
		$datetime = Helper_Common::get_default_datetime();
		$user_access = $this->getUserRole();
		if($type == 'workout'){
			$records = $this->getworkoutById($userId,$wkout_Folder);
			$exerciseRecords = $this->getExerciseSet($wkout_Folder);
			if(is_array($records) && count($records)>0){
				$records['parent_folder_id'] = $folder_id;
				$records['created_date']     = $datetime;
				$records['modified_date']	 = $datetime;
				$records['access_id']	 	 = $user_access;
				$records['wkout_title']		 = $records['wkout_title'];
				$insertId = $this->insertWorkoutDetails($records);
				if(is_array($exerciseRecords) && count($exerciseRecords)>0){
					foreach($exerciseRecords as $keys => $values){
						if($values['goal_title'] != 'Click_to_Edit'){
							$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
								
							$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $userId, '1'))->execute();
						}
					}
				}
				return $insertId;
			}
			return true;
		}else{
			$records = $this->getWkoutFolderDetailsByUser($userId, $wkout_Folder);
			if(is_array($records) && count($records)>0){
				$records['parent_folder_id'] = $folder_id;
				$records['created_date']     = $datetime;
				$records['modified_date']	 = $datetime;
				$records['folder_title']     = $records['folder_title'];
				$innerFolderId = $this->insertFolderDetails($records);
				$innerRecords    = $this->getWorkoutDetailsByUser($userId, $records['folder_id']);
				if(is_array($innerRecords) && count($innerRecords) >0){
					foreach($innerRecords as $innerkey => $innerValues){
						if(!empty($innerValues['wkout_id']) && is_numeric($innerValues['wkout_id'])){
							$innerRecords = $this->getworkoutById($userId,$innerValues['wkout_id']);
							$exerciseRecords = $this->getExerciseSet($innerValues['wkout_id']);
							$innerRecords['parent_folder_id'] 	= $innerFolderId;
							$innerRecords['created_date']     	= $datetime;
							$innerRecords['modified_date']		= $datetime;
							$innerRecords['wkout_title']		= $innerRecords['wkout_title'];
							$insertId =  $this->insertWorkoutDetails($innerRecords);
							if(is_array($exerciseRecords) && count($exerciseRecords)>0){
								foreach($exerciseRecords as $keys => $values){
									if($values['goal_title'] != 'Click_to_Edit'){
										$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
											
										$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $userId, '1'))->execute();
									}
								}
							}
						}else{
							$this->doCopyForWorkoutFolderById('folder', $userId, $innerValues['folder_id'] , $innerFolderId);
						}
					}
				}
				return $innerFolderId;
			}
			return true;
		}
	}
	public function getUserRole(){
		$userId = Auth::instance()->get_user()->pk();
		$usermodelORM = ORM::factory('user');
		$usermodel = $usermodelORM->where('id', '=', trim($userId))->find();
		if($usermodel->has('roles', ORM::factory('Role', array('name' => 'admin')))){
			return '2';
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'global')))){
			return '3';
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'localsite')))){
			return '4';
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'manager')))){
			return '8';
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'corporate')))){
			return '5';
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'trainer')))){
			return '7';
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'trial')))){
			return '9';
		}
		return '6';// register
	}
	public function getUserRoleById($id){
		$sql = "SELECT ru.role_id FROM roles_users AS ru JOIN users AS u ON u.id=ru.user_id WHERE ru.user_id='".$id."' order by ru.role_id ASC";
		$query = DB::query(Database::SELECT,$sql);
		$list  = $query->execute()->as_array();
		$newlist = array();
		if(!empty($list) && count($list)>0){
			foreach ($list as $key => $value) {
				$newlist[] = $value['role_id'];
			}
		}
		if(!empty($newlist) && count($newlist)>0){
			if(in_array('2',$newlist))
				return '2';
			else if(in_array('3',$newlist))
				return '3';
			else if(in_array('4',$newlist))
				return '4';
			else if(in_array('8',$newlist))
				return '8';
			else if(in_array('5',$newlist))
				return '5';
			else if(in_array('7',$newlist))
				return '7';
			else if(in_array('9',$newlist))
				return '9';
		}
		return '6';
	}
	public function getUserDetails(){
		$userId = Auth::instance()->get_user()->pk();
		$usermodelORM = ORM::factory('user');
		$usermodel = $usermodelORM->where('id', '=', trim($userId))->find();
		if($usermodel->has('roles', ORM::factory('Role', array('name' => 'admin')))){
			return array('user_id'=>'','access_id'=>'2');
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'global')))){
			return array('user_id'=>'','access_id'=>'3');
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'manager')))){
			return array('user_id'=>'','access_id'=>'8');
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'register')))){
			return array('user_id'=>'','access_id'=>'6');
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'localsite')))){
			return array('user_id'=>'','access_id'=>'4');
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'corporate')))){
			return array('user_id'=>'','access_id'=>'5');
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'trainer')))){
			return array('user_id'=>'','access_id'=>'7');
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'login')))){
			return array('user_id'=>'','access_id'=>'1');
		}else if($usermodel->has('roles', ORM::factory('Role', array('name' => 'trial')))){
			return array('user_id'=>'','access_id'=>'9');
		}
		return false;
	}
	public function doSampleCopyForWorkoutFolderById($type, $userId, $wkout_Folder, $folder_id){
		$datetime = Helper_Common::get_default_datetime();
		$user_access = $this->getUserRole();
		if($type == 'sampleworkout'){
			$records = $this->getSampleworkoutById(0,$wkout_Folder);
			$exerciseRecords = $this->getSampleExerciseSet($wkout_Folder);
			if(is_array($records) && count($records)>0){
				$records['parent_folder_id'] = $folder_id;
				$records['user_id']     	 = $userId;
				$records['access_id']		 = $user_access;
				$records['created_date']     = $datetime;
				$records['modified_date']	 = $datetime;
				$records['wkout_title']		 = $records['wkout_title'];
				$insertId = $this->insertWorkoutDetails($records);
				if(is_array($exerciseRecords) && count($exerciseRecords)>0){
					foreach($exerciseRecords as $keys => $values){
						if($values['goal_title'] != 'Click_to_Edit'){
							$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
								
							$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'],$userId, '1'))->execute();
						}
					}
				}
				return $insertId;
			}
			return true;
		}else{
			$records = $this->getSampleWkoutFolderDetails(0, $wkout_Folder);
			if(is_array($records) && count($records)>0){
				$records['parent_folder_id'] = $folder_id;
				$records['user_id'] 		 = $userId;
				$records['created_by'] 		 = $userId;
				$records['modified_by'] 	 = $userId;
				$records['created_date']     = $datetime;
				$records['modified_date']	 = $datetime;
				$records['folder_title']     = $records['folder_title'];
				$innerFolderId = $this->insertFolderDetails($records);
				$innerRecords    = $this->getSampleWorkoutDetails(0, $records['folder_id']);
				if(is_array($innerRecords) && count($innerRecords) >0){
					foreach($innerRecords as $innerkey => $innerValues){
						if(!empty($innerValues['wkout_sample_id']) && is_numeric($innerValues['wkout_sample_id'])){
							$innerRecords = $this->getSampleworkoutById('',$innerValues['wkout_sample_id']);
							$exerciseRecords = $this->getSampleExerciseSet($innerValues['wkout_sample_id']);
							$innerRecords['parent_folder_id'] 	= $innerFolderId;
							$innerRecords['user_id'] 	 		= $userId;
							$innerRecords['access_id']			= $user_access;
							$innerRecords['created_date']     	= $datetime;
							$innerRecords['modified_date']		= $datetime;
							$innerRecords['wkout_title']		= $innerRecords['wkout_title'];
							$insertId =  $this->insertWorkoutDetails($innerRecords);
							if(is_array($exerciseRecords) && count($exerciseRecords)>0){
								foreach($exerciseRecords as $keys => $values){
									if($values['goal_title'] != 'Click_to_Edit'){
										$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
											
										$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $userId, '1'))->execute();
									}
								}
							}
						}else{
							$this->doSampleCopyForWorkoutFolderById('folder', $userId, $innerValues['folder_id'] , $innerFolderId);
						}
					}
				}
				return $innerFolderId;
			}
			return true;
		}
	}
	public function doShareCopyForWorkoutFolderById($type, $userId, $wkout_Folder, $folder_id){
		$datetime = Helper_Common::get_default_datetime();
		$user_access = $this->getUserRole();
		if($type == 'shareworkout'){
			$records = $this->getShareworkoutById($userId,$wkout_Folder);
			$exerciseRecords = $this->getExerciseSets('shared', $wkout_Folder);
			if(is_array($records) && count($records)>0){
				$records['parent_folder_id'] = $folder_id;
				$records['user_id']     	 = $userId;
				$records['access_id']		 = $user_access;
				$records['created_date']     = $datetime;
				$records['modified_date']	 = $datetime;
				$records['wkout_title']		 = $records['wkout_title'];
				$insertId = $this->insertWorkoutDetails($records);
				if(is_array($exerciseRecords) && count($exerciseRecords)>0){
					foreach($exerciseRecords as $keys => $values){
						if($values['goal_title'] != 'Click_to_Edit'){
							$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
								
							$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'],$userId, '1'))->execute();
						}
					}
				}
				return $insertId;
			}
			return true;
		}else{
			$records = $this->getShareFolderDetailsByUser($userId, $wkout_Folder);
			if(is_array($records) && count($records)>0){
				$records['parent_folder_id'] = $folder_id;
				$records['user_id'] 		 = $userId;
				$records['created_by'] 		 = $userId;
				$records['modified_by'] 	 = $userId;
				$records['created_date']     = $datetime;
				$records['modified_date']	 = $datetime;
				$records['folder_title']     = $records['folder_title'];
				$innerFolderId = $this->insertFolderDetails($records);
				$innerRecords    = $this->getShareworkoutById($userId, $records['folder_id']);
				if(is_array($innerRecords) && count($innerRecords) >0){
					foreach($innerRecords as $innerkey => $innerValues){
						if(!empty($innerValues['wkout_share_id']) && is_numeric($innerValues['wkout_share_id'])){
							$innerRecords = $this->getShareworkoutById($userId,$innerValues['wkout_share_id']);
							$exerciseRecords = $this->getExerciseSets('shared',$innerValues['wkout_share_id']);
							$innerRecords['parent_folder_id'] 	= $innerFolderId;
							$innerRecords['user_id'] 	 		= $userId;
							$innerRecords['access_id']			= $user_access;
							$innerRecords['created_date']     	= $datetime;
							$innerRecords['modified_date']		= $datetime;
							$innerRecords['wkout_title']		= $innerRecords['wkout_title'];
							$insertId =  $this->insertWorkoutDetails($innerRecords);
							if(is_array($exerciseRecords) && count($exerciseRecords)>0){
								foreach($exerciseRecords as $keys => $values){
									if($values['goal_title'] != 'Click_to_Edit'){
										$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
											
										$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $userId, '1'))->execute();
									}
								}
							}
						}else{
							$this->doSampleCopyForWorkoutFolderById('folder', $userId, $innerValues['folder_id'] , $innerFolderId);
						}
					}
				}
				return $innerFolderId;
			}
			return true;
		}
	}
	public function doDeleteForWorkoutFolderById($type, $userId, $wkout_Folder, $folder_id){
		if($type == 'workout'){
			return $this->deleteWorkoutDetails($folder_id, $wkout_Folder, $userId);
		}else{
			$records = $this->getWkoutFolderDetailsByUser($userId,$wkout_Folder);
			return $this->deleteFolderDetails($wkout_Folder,$userId);
		}
	}
	public function getAssignedWorkouts($user_id,$fromDate = '',$toDate = ''){
		$sql = "SELECT wka.*,wc.* FROM  wkout_assign_gendata AS wka LEFT JOIN wkout_color AS wc ON wka.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON (wka.wkout_focus=wf.focus_grp_id AND wf.status_id = 1) LEFT JOIN unit_status AS uns ON (wka.status_id=uns.status_id AND wka.status_id !=4) LEFT JOIN roles AS ua ON wka.access_id=ua.id where wka.status_id != 4 AND wka.assigned_by='".$user_id."' ".(!empty($fromDate) ? ' AND wka.assigned_date >= "'.$fromDate.'"' : '').(!empty($toDate) ? ' AND wka.assigned_date < "'.$toDate.'"' : '')." AND wka.marked_status in ('1','2','0') order by wka.assigned_date desc";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getJournalWorkouts($user_id,$fromDate = '',$toDate = ''){
		$sql = "SELECT wka.*,wc.* FROM  wkout_log_gendata AS wka LEFT JOIN wkout_color AS wc ON wka.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON (wka.wkout_focus=wf.focus_grp_id AND wf.status_id = 1) LEFT JOIN unit_status AS uns ON (wka.status_id=uns.status_id AND wka.status_id !=4) LEFT JOIN roles AS ua ON wka.access_id=ua.id where wka.status_id != 4 AND wka.user_id='".$user_id."' ".(!empty($fromDate) ? ' AND wka.assigned_date >= "'.$fromDate.'"' : '').(!empty($toDate) ? ' AND wka.assigned_date < "'.$toDate.'"' : '')." AND wka.wkout_status in ('1','2','0','3') order by wka.assigned_date desc";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getAllPlannedWorkouts($user_id,$fromDate = '',$toDate = ''){
		$sql = "select * from (SELECT wka.wkout_assign_id as wkoutplan_id, IF(wka.wkout_assign_id is NULL,'logged','assigned') as wkoutplan_type ,wka.* ,wc.*, IF(wka.wkout_assign_id is NULL,null,null) as wkout_status, IF(wka.wkout_assign_id is NULL,null,null) as intensity, IF(wka.wkout_assign_id is NULL,null,null) as remark, wka.	associated_log_id as associated_id, IF(wka.wkout_assign_id is NULL,0,0) as xr_unmark_count FROM  wkout_assign_gendata AS wka LEFT JOIN wkout_color AS wc ON wka.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON (wka.wkout_focus=wf.focus_grp_id AND wf.status_id = 1) LEFT JOIN unit_status AS uns ON (wka.status_id=uns.status_id) where wka.status_id != 4 AND wka.assigned_by='".$user_id."' ".(!empty($fromDate) && !empty($toDate) ? 'AND wka.assigned_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'"' : (!empty($fromDate) ? 'AND wka.assigned_date >= "'.$fromDate.'"' : (!empty($toDate) ? ' AND wka.assigned_date < "'.$toDate.'"' : '')))." AND wka.marked_status in ('0') UNION SELECT wka.wkout_log_id as wkoutplan_id ,IF(wka.wkout_log_id is NULL,'assigned','logged') as wkoutplan_type ,wka.* ,wc.*,wka.wkout_status as wkout_status, wka.note_wkout_intensity as intensity,  wka.note_wkout_remarks as remark, wka.associated_assign_id as associated_id, (select count(wl.set_status) from wkout_log_goal_gendata as wl where wl.wkout_log_id = wka.wkout_log_id AND wl.set_status =0 AND wl.status_id=1) as xr_unmark_count FROM  wkout_log_gendata AS wka LEFT JOIN wkout_color AS wc ON wka.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON (wka.wkout_focus=wf.focus_grp_id AND wf.status_id = 1) LEFT JOIN unit_status AS uns ON (wka.status_id=uns.status_id) where wka.status_id != 4 AND wka.user_id='".$user_id."' ".(!empty($fromDate) && !empty($toDate) ? 'AND wka.assigned_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'"' : (!empty($fromDate) ? 'AND wka.assigned_date >= "'.$fromDate.'"' : (!empty($toDate) ? ' AND wka.assigned_date < "'.$toDate.'"' : '')))." AND wka.wkout_status in ('1','2','0','3')) plans order by plans.assigned_date desc";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getAssignedTodayWorkouts($user_id,$fromDate = ''){
		$sql = "SELECT wka.*,wc.* FROM  wkout_assign_gendata AS wka LEFT JOIN wkout_color AS wc ON wka.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON (wka.wkout_focus=wf.focus_grp_id AND wf.status_id = 1) LEFT JOIN unit_status AS uns ON (wka.status_id=uns.status_id AND wka.status_id !=4) LEFT JOIN roles AS ua ON wka.access_id=ua.id where wka.status_id != 4 AND wka.assigned_by='".$user_id."' ".(!empty($fromDate) ? ' AND wka.assigned_date = "'.$fromDate.'"' : '')." AND wka.marked_status = '0'  order by wka.assigned_date desc";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getColors(){
		$sql = "SELECT color_id, color_title from wkout_color";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function updateWkoutorder($new_order, $wkseq_id, $folderid, $workoutId, $parentfoldid, $user_id){
		$sql 	= "Update wkout_seq set seq_order = '".$new_order."',folder_id = '".$folderid."', wkout_id= '".$workoutId."', parent_folder_id ='".$parentfoldid."'  Where user_id ='".$user_id."' AND id ='".$wkseq_id."' AND wkout_seq_status=0";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function getunitsbytable($tableName){
		$sql = "SELECT * from $tableName";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getAdditionalMusules($unitId){
		$sql = "SELECT * FROM unit_gendata as xrgd LEFT JOIN unit_musoth as g1 ON xrgd.unit_id=g1.unit_id LEFT JOIN unit_muscle as g2 ON g1.musoth_id=g2.muscle_id WHERE xrgd.unit_id='".$unitId."' AND g1.status_id=1";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getAdditionalEquip($unitId){
		$equipothsql = "SELECT * FROM unit_gendata as xrgd LEFT JOIN unit_equipoth as g1 ON xrgd.unit_id=g1.unit_id LEFT JOIN unit_equip as g2 ON g1.equipoth_id=g2.equip_id WHERE xrgd.unit_id='".$unitId."' AND g1.status_id=1";
		$query 	= DB::query(Database::SELECT,$equipothsql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getSeqFromUnitId($unitId){
		$sql = "SELECT count(*) as total FROM unit_seq as seq JOIN unit_gendata as g1 ON seq.unit_id=g1.unit_id JOIN img as g2 ON seq.seq_img=g2.img_id WHERE seq.unit_id='".$unitId."' AND seq.status_id=1";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list[0]['total'];
	}
	public function getSequenceImage($unitId, $seqId = 0){
		$sql = "SELECT g2.img_url, g2.img_title  FROM unit_seq as seq JOIN unit_gendata as g1 ON seq.unit_id=g1.unit_id JOIN img as g2 ON seq.seq_img=g2.img_id WHERE seq.unit_id='".$unitId."' AND seq.status_id=1 AND g2.status_id=1 ".(!empty($seqId) ? " AND seq.seq_id = '".$seqId."'" : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return (isset($list[0]) ? $list[0] : $list);
	}
	public function getSequenceImages($unitId){
		$sql = "SELECT seq.seq_order, seq.seq_desc,g2.img_url, g2.img_title  FROM unit_seq as seq JOIN unit_gendata as g1 ON seq.unit_id=g1.unit_id JOIN img as g2 ON seq.seq_img=g2.img_id WHERE seq.unit_id='".$unitId."' AND seq.status_id=1 AND g2.status_id=1";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function relatedExcersise($fid){
		$sql="SELECT * FROM unit_gendata AS xrgd LEFT JOIN unit_status AS g1 ON xrgd.status_id=g1.status_id LEFT JOIN unit_type AS g2 ON	xrgd.type_id=g2.type_id LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id LEFT JOIN unit_mech AS g6 ON xrgd.mech_id=g6.mech_id	LEFT JOIN unit_level AS g7 ON xrgd.level_id=g7.level_id	LEFT JOIN unit_sport AS g8 ON xrgd.sport_id=g8.sport_id	LEFT JOIN	unit_force AS g9 ON	xrgd.force_id=g9.force_id LEFT JOIN	unit_access AS g10 ON xrgd.access_id=g10.access_id WHERE xrgd.unit_id = '" . $fid . "'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return isset($list[0]) ? $list[0] : $list;
	}
	public function getRelatedExercises($unit_id, $muscle_id, $type_id, $start, $limit){
		// $site_ids = Helper_Common::getAllSiteId();
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$user_id  = Auth::instance()->get_user()->pk();
		$sql="SELECT xrgd.*,g1.*,g3.*,g4.*,g5.*,g6.*,s.*,g2.default_status as img_default_status,g2.img_id,g2.img_title,g2.img_type,g2.img_url,g2.subfolder_id,g2.parentfolder_id,g2.status_id as img_status_id,g2.access_id,g2.user_id,g2.site_id as img_site_id FROM unit_gendata AS xrgd LEFT JOIN unit_muscle AS g1 ON xrgd.musprim_id=g1.muscle_id LEFT JOIN img AS g2 ON xrgd.feat_img=g2.img_id LEFT JOIN unit_equip AS g3 ON xrgd.equip_id=g3.equip_id LEFT JOIN unit_status AS g4 ON xrgd.status_id=g4.status_id LEFT JOIN unit_type AS g5 ON xrgd.type_id=g5.type_id LEFT JOIN unit_force AS g6 ON xrgd.force_id=g6.force_id LEFT JOIN sites s ON xrgd.site_id=s.id WHERE xrgd.status_id=1 AND xrgd.unit_id !=".$unit_id." AND ((xrgd.default_status=0 AND xrgd.created_by=".$user_id.") || xrgd.default_status=1 || (xrgd.default_status=2 AND xrgd.site_id in (".$site_id.")) || (xrgd.default_status=3 AND xrgd.created_by=".$user_id.")) AND xrgd.musprim_id ='".$muscle_id."' AND xrgd.type_id = ".$type_id." ORDER BY xrgd.title ASC LIMIT $start, $limit";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getRelatedExercise($unit_id, $muscle_id, $published, $type_id){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$user_id  = Auth::instance()->get_user()->pk();
		$sql="SELECT count(*) as total FROM unit_gendata AS xrgd LEFT JOIN unit_muscle AS g1 ON xrgd.musprim_id=g1.muscle_id LEFT JOIN img AS g2 ON xrgd.feat_img=g2.img_id LEFT JOIN unit_equip AS g3 ON xrgd.equip_id=g3.equip_id LEFT JOIN unit_status AS g4 ON xrgd.status_id=g4.status_id LEFT JOIN unit_type AS g5 ON xrgd.type_id=g5.type_id LEFT JOIN unit_force AS g6 ON xrgd.force_id=g6.force_id LEFT JOIN sites s ON xrgd.site_id=s.id WHERE xrgd.status_id=1 AND xrgd.unit_id !=".$unit_id." AND ((xrgd.default_status=0 AND xrgd.created_by=".$user_id.") || xrgd.default_status=1 || (xrgd.default_status=2 AND xrgd.site_id in (".$site_id.")) || (xrgd.default_status=3 AND xrgd.created_by=".$user_id.")) AND xrgd.musprim_id ='".$muscle_id."' AND xrgd.type_id = ".$type_id." ORDER BY xrgd.unit_id ASC";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list[0]['total'];
	}
	public function getInnerDrive(){
		$sql = "SELECT si.*,sig.int_grp_title FROM set_int si LEFT JOIN set_int_grp sig ON si.int_grp_id = sig.int_grp_id ORDER BY si.int_grp_id, si.int_opt_id";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function addWorkoutSetFromworkout($array,$keyvalue , $userId)
	{
		if((isset($array['s_row_count']) || isset($array['s_row_count_xr'])) && isset($array['wkout_id'])){
			$timehh=''; $timemm=''; $timess=''; $restmm=''; $restss=''; $distance=''; $distanceid=''; $repitation=''; $resistance='';
			$resistanceid=''; $rate=''; $rateid=''; $angle=''; $angleid=''; $intdrive=''; $remark=''; $title=''; $type='';

			if(isset($array['exercise_title'][$keyvalue]) && !empty($array['exercise_title'][$keyvalue])){
				$title=$array['exercise_title'][$keyvalue];
			}
			$unit_id= '0';
			$goal_title_self = '1';
			if (isset($array['exercise_unit'][$keyvalue])){
				$exerciseUnitArray = explode('_',$array['exercise_unit'][$keyvalue]);
				if(isset($exerciseUnitArray[1]) && is_numeric($exerciseUnitArray[1])){
					$goal_title_self = 0;
					$unit_id	= $exerciseUnitArray[1];
				}else{
					$unit_id	= 0;
				}
			}
			if(isset($array['exercise_time'][$keyvalue]) && !empty($array['exercise_time'][$keyvalue])){
				$timesplit=explode(':', $array['exercise_time'][$keyvalue]);
				$timehh=$timesplit[0];
				$timemm=$timesplit[1];
				$timess=$timesplit[2];
			}
			if(isset($array['exercise_rest'][$keyvalue]) && !empty($array['exercise_rest'][$keyvalue])){
				$restsplit=explode(':', $array['exercise_rest'][$keyvalue]);
				$restmm=$restsplit[0];
				$restss=$restsplit[1];
			}
			if(isset($array['exercise_distance'][$keyvalue]) && !empty($array['exercise_distance'][$keyvalue])){
				$distance=$array['exercise_distance'][$keyvalue];
			}
			if(isset($array['exercise_unit_distance'][$keyvalue]) && !empty($array['exercise_unit_distance'][$keyvalue])){
				$distanceid=$array['exercise_unit_distance'][$keyvalue];
			}
			if(isset($array['exercise_repetitions'][$keyvalue]) && !empty($array['exercise_repetitions'][$keyvalue])){
				$repitation=$array['exercise_repetitions'][$keyvalue];
			}
			if(isset($array['exercise_resistance'][$keyvalue]) && !empty($array['exercise_resistance'][$keyvalue])){
				$resistance=$array['exercise_resistance'][$keyvalue];
			}
			if(isset($array['exercise_unit_resistance'][$keyvalue]) && !empty($array['exercise_unit_resistance'][$keyvalue])){
				$resistanceid=$array['exercise_unit_resistance'][$keyvalue];
			}
			if(isset($array['exercise_rate'][$keyvalue]) && !empty($array['exercise_rate'][$keyvalue])){
				$rate=$array['exercise_rate'][$keyvalue];
			}
			if(isset($array['exercise_unit_rate'][$keyvalue]) && !empty($array['exercise_unit_rate'][$keyvalue])){
				$rateid=$array['exercise_unit_rate'][$keyvalue];
			}
			if(isset($array['exercise_angle'][$keyvalue]) && !empty($array['exercise_angle'][$keyvalue])){
				$angle=$array['exercise_angle'][$keyvalue];
			}
			if(isset($array['exercise_unit_angle'][$keyvalue]) && !empty($array['exercise_unit_angle'][$keyvalue])){
				$angleid=$array['exercise_unit_angle'][$keyvalue];
			}
			if(isset($array['exercise_innerdrive'][$keyvalue]) && !empty($array['exercise_innerdrive'][$keyvalue])){
				$intdrive=$array['exercise_innerdrive'][$keyvalue];
			}
			if(isset($array['exercise_remark'][$keyvalue]) && !empty($array['exercise_remark'][$keyvalue])){
				$remark=$array['exercise_remark'][$keyvalue];
			}
			
			$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'primary_resist', 'primary_rate', 'primary_angle', 'primary_rest', 'primary_int', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))
				->values(array(0, 0, $array['primary_time'][$keyvalue], $array['primary_dist'][$keyvalue], $array['primary_reps'][$keyvalue], $array['primary_resist'][$keyvalue], $array['primary_rate'][$keyvalue] , $array['primary_angle'][$keyvalue], $array['primary_rest'][$keyvalue], $array['primary_int'][$keyvalue], $timehh, $timemm, $timess, $distance, $distanceid, $repitation, $resistance, $resistanceid, $rate, $rateid, $angle, $angleid, $intdrive, $restmm, $restss, $remark))->execute();
			
			$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id' , 'status_id'))
				->values(array($array['wkout_id'], $unit_id, '0', $title, $goal_title_self, (!empty($array['goal_order'][$keyvalue]) ? $array['goal_order'][$keyvalue] : $keyvalue) , $userId, '1'))->execute();
		}
								
		return $goal_gendata_results[0];
	}
	
	
	
	public function addSampleWorkoutSetFromworkout($array,$keyvalue , $userId)
	{
		if((isset($array['s_row_count']) || isset($array['s_row_count_xr'])) && isset($array['wkout_id'])){
				$timehh=''; $timemm=''; $timess=''; $restmm=''; $restss=''; $distance=''; $distanceid=''; $repitation=''; $resistance='';
				$resistanceid=''; $rate=''; $rateid=''; $angle=''; $angleid=''; $intdrive=''; $remark=''; $title=''; $type='';

				if(isset($array['exercise_title'][$keyvalue]) && !empty($array['exercise_title'][$keyvalue])){
					$title=$array['exercise_title'][$keyvalue];
				}
				$unit_id= '0';
				$goal_title_self = '1';
				if (isset($array['exercise_unit'][$keyvalue])){
					$exerciseUnitArray = explode('_',$array['exercise_unit'][$keyvalue]);
					if(isset($exerciseUnitArray[1]) && is_numeric($exerciseUnitArray[1])){
						$goal_title_self = 0;
						$unit_id	= $exerciseUnitArray[1];
					}else{
						$unit_id	= 0;
					}
				}
				if(isset($array['exercise_time'][$keyvalue]) && !empty($array['exercise_time'][$keyvalue])){
					$timesplit=explode(':', $array['exercise_time'][$keyvalue]);
					$timehh=$timesplit[0];
					$timemm=$timesplit[1];
					$timess=$timesplit[2];
				}
				if(isset($array['exercise_rest'][$keyvalue]) && !empty($array['exercise_rest'][$keyvalue])){
					$restsplit=explode(':', $array['exercise_rest'][$keyvalue]);
					$restmm=$restsplit[0];
					$restss=$restsplit[1];
				}
				if(isset($array['exercise_distance'][$keyvalue]) && !empty($array['exercise_distance'][$keyvalue])){
					$distance=$array['exercise_distance'][$keyvalue];
				}
				if(isset($array['exercise_unit_distance'][$keyvalue]) && !empty($array['exercise_unit_distance'][$keyvalue])){
					$distanceid=$array['exercise_unit_distance'][$keyvalue];
				}
				if(isset($array['exercise_repetitions'][$keyvalue]) && !empty($array['exercise_repetitions'][$keyvalue])){
					$repitation=$array['exercise_repetitions'][$keyvalue];
				}
				if(isset($array['exercise_resistance'][$keyvalue]) && !empty($array['exercise_resistance'][$keyvalue])){
					$resistance=$array['exercise_resistance'][$keyvalue];
				}
				if(isset($array['exercise_unit_resistance'][$keyvalue]) && !empty($array['exercise_unit_resistance'][$keyvalue])){
					$resistanceid=$array['exercise_unit_resistance'][$keyvalue];
				}
				if(isset($array['exercise_rate'][$keyvalue]) && !empty($array['exercise_rate'][$keyvalue])){
					$rate=$array['exercise_rate'][$keyvalue];
				}
				if(isset($array['exercise_unit_rate'][$keyvalue]) && !empty($array['exercise_unit_rate'][$keyvalue])){
					$rateid=$array['exercise_unit_rate'][$keyvalue];
				}
				if(isset($array['exercise_angle'][$keyvalue]) && !empty($array['exercise_angle'][$keyvalue])){
					$angle=$array['exercise_angle'][$keyvalue];
				}
				if(isset($array['exercise_unit_angle'][$keyvalue]) && !empty($array['exercise_unit_angle'][$keyvalue])){
					$angleid=$array['exercise_unit_angle'][$keyvalue];
				}
				if(isset($array['exercise_innerdrive'][$keyvalue]) && !empty($array['exercise_innerdrive'][$keyvalue])){
					$intdrive=$array['exercise_innerdrive'][$keyvalue];
				}
				if(isset($array['exercise_remark'][$keyvalue]) && !empty($array['exercise_remark'][$keyvalue])){
					$remark=$array['exercise_remark'][$keyvalue];
				}
				
				$goal_results = DB::insert('wkout_sample_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'primary_resist', 'primary_rate', 'primary_angle', 'primary_rest', 'primary_int', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))
					->values(array(0, 0, $array['primary_time'][$keyvalue], $array['primary_dist'][$keyvalue], $array['primary_reps'][$keyvalue], $array['primary_resist'][$keyvalue], $array['primary_rate'][$keyvalue] , $array['primary_angle'][$keyvalue], $array['primary_rest'][$keyvalue], $array['primary_int'][$keyvalue], $timehh, $timemm, $timess, $distance, $distanceid, $repitation, $resistance, $resistanceid, $rate, $rateid, $angle, $angleid, $intdrive, $restmm, $restss, $remark))->execute();
				
				$goal_gendata_results = DB::insert('wkout_sample_goal_gendata', array('wkout_sample_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id' , 'status_id'))
					->values(array($array['wkout_id'], $unit_id, '0', $title, $goal_title_self, (!empty($array['goal_order'][$keyvalue]) ? $array['goal_order'][$keyvalue] : $keyvalue) , $userId, '1'))->execute();
		}
								
		return $goal_gendata_results[0];
	}
	
	public function addAssignWorkoutSetFromworkout($array,$keyvalue , $userId , $workassignid)
	{
		if((isset($array['s_row_count']) || isset($array['s_row_count_xr'])) && (isset($array['wkout_id']) || !isset($array['wkout_id'])) && !empty($workassignid)){
				$timehh=''; $timemm=''; $timess=''; $restmm=''; $restss=''; $distance=''; $distanceid=''; $repitation=''; $resistance='';
				$resistanceid=''; $rate=''; $rateid=''; $angle=''; $angleid=''; $intdrive=''; $remark=''; $title=''; $type='';

				if(isset($array['exercise_title'][$keyvalue]) && !empty($array['exercise_title'][$keyvalue])){
					$title=$array['exercise_title'][$keyvalue];
				}
				$unit_id= '0';
				$goal_title_self = '1';
				if (isset($array['exercise_unit'][$keyvalue])){
					$exerciseUnitArray = explode('_',$array['exercise_unit'][$keyvalue]);
					if(isset($exerciseUnitArray[1]) && is_numeric($exerciseUnitArray[1])){
						$goal_title_self = 0;
						$unit_id	= $exerciseUnitArray[1];
					}else{
						$unit_id	= 0;
					}
				}
				if(isset($array['exercise_time'][$keyvalue]) && !empty($array['exercise_time'][$keyvalue])){
					$timesplit=explode(':', $array['exercise_time'][$keyvalue]);
					$timehh=$timesplit[0];
					$timemm=$timesplit[1];
					$timess=$timesplit[2];
				}
				if(isset($array['exercise_rest'][$keyvalue]) && !empty($array['exercise_rest'][$keyvalue])){
					$restsplit=explode(':', $array['exercise_rest'][$keyvalue]);
					$restmm=$restsplit[0];
					$restss=$restsplit[1];
				}
				if(isset($array['exercise_distance'][$keyvalue]) && !empty($array['exercise_distance'][$keyvalue])){
					$distance=$array['exercise_distance'][$keyvalue];
				}
				if(isset($array['exercise_unit_distance'][$keyvalue]) && !empty($array['exercise_unit_distance'][$keyvalue])){
					$distanceid=$array['exercise_unit_distance'][$keyvalue];
				}
				if(isset($array['exercise_repetitions'][$keyvalue]) && !empty($array['exercise_repetitions'][$keyvalue])){
					$repitation=$array['exercise_repetitions'][$keyvalue];
				}
				if(isset($array['exercise_resistance'][$keyvalue]) && !empty($array['exercise_resistance'][$keyvalue])){
					$resistance=$array['exercise_resistance'][$keyvalue];
				}
				if(isset($array['exercise_unit_resistance'][$keyvalue]) && !empty($array['exercise_unit_resistance'][$keyvalue])){
					$resistanceid=$array['exercise_unit_resistance'][$keyvalue];
				}
				if(isset($array['exercise_rate'][$keyvalue]) && !empty($array['exercise_rate'][$keyvalue])){
					$rate=$array['exercise_rate'][$keyvalue];
				}
				if(isset($array['exercise_unit_rate'][$keyvalue]) && !empty($array['exercise_unit_rate'][$keyvalue])){
					$rateid=$array['exercise_unit_rate'][$keyvalue];
				}
				if(isset($array['exercise_angle'][$keyvalue]) && !empty($array['exercise_angle'][$keyvalue])){
					$angle=$array['exercise_angle'][$keyvalue];
				}
				if(isset($array['exercise_unit_angle'][$keyvalue]) && !empty($array['exercise_unit_angle'][$keyvalue])){
					$angleid=$array['exercise_unit_angle'][$keyvalue];
				}
				if(isset($array['exercise_innerdrive'][$keyvalue]) && !empty($array['exercise_innerdrive'][$keyvalue])){
					$intdrive=$array['exercise_innerdrive'][$keyvalue];
				}
				if(isset($array['exercise_remark'][$keyvalue]) && !empty($array['exercise_remark'][$keyvalue])){
					$remark=$array['exercise_remark'][$keyvalue];
				}
				
				$goal_results = DB::insert('wkout_assign_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'primary_resist', 'primary_rate', 'primary_angle', 'primary_rest', 'primary_int', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))
					->values(array(0, 0, $array['primary_time'][$keyvalue], $array['primary_dist'][$keyvalue], $array['primary_reps'][$keyvalue], $array['primary_resist'][$keyvalue], $array['primary_rate'][$keyvalue] , $array['primary_angle'][$keyvalue], $array['primary_rest'][$keyvalue], $array['primary_int'][$keyvalue], $timehh, $timemm, $timess, $distance, $distanceid, $repitation, $resistance, $resistanceid, $rate, $rateid, $angle, $angleid, $intdrive, $restmm, $restss, $remark))->execute();
				
				$goal_gendata_results = DB::insert('wkout_assign_goal_gendata', array('wkout_assign_id','wkout_id','goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id' , 'status_id'))
					->values(array($workassignid,(isset($array['wkout_id']) ? $array['wkout_id'] : '0'), $unit_id, '0', $title, $goal_title_self, (!empty($array['goal_order'][$keyvalue]) ? $array['goal_order'][$keyvalue] : $keyvalue) , $userId, '1'))->execute();
		}
								
		return $goal_gendata_results[0];
	}
	public function addWorkoutSetFromExistworkout($array, $userId , $workid)
	{
		$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'primary_resist', 'primary_rate', 'primary_angle', 'primary_rest', 'primary_int', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))
		->values(array(0, 0, $array['primary_time'], $array['primary_dist'], $array['primary_reps'], $array['primary_resist'], $array['primary_rate'] , $array['primary_angle'], $array['primary_rest'], $array['primary_int'], (isset($array['goal_time_hh']) ? $array['goal_time_hh'] : '0'), (isset($array['goal_time_mm']) ? $array['goal_time_mm'] : '0'), (isset($array['goal_time_ss']) ? $array['goal_time_ss'] : '0'), $array['goal_dist'], $array['goal_dist_id'], $array['goal_reps'], $array['goal_resist'], $array['goal_resist_id'], $array['goal_rate'], $array['goal_rate_id'], $array['goal_angle'], $array['goal_angle_id'], $array['goal_int_id'], (isset($array['goal_rest_mm']) ? $array['goal_rest_mm'] : '0'), (isset($array['goal_rest_ss']) ? $array['goal_rest_ss'] : '0'), $array['goal_remarks']))->execute();
				
		$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id','goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id' , 'status_id'))
		->values(array($workid, $array['goal_unit_id'], '0', $array['goal_title'], $array['goal_title_self'],$array['goal_order'] , $userId, '1'))->execute();
		return $goal_gendata_results[0];
	}
	public function addWorkoutSetFromExistSampleworkout($array, $userId , $workid)
	{
		$goal_results = DB::insert('wkout_sample_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'primary_resist', 'primary_rate', 'primary_angle', 'primary_rest', 'primary_int', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))
		->values(array(0, 0, $array['primary_time'], $array['primary_dist'], $array['primary_reps'], $array['primary_resist'], $array['primary_rate'] , $array['primary_angle'], $array['primary_rest'], $array['primary_int'], (isset($array['goal_time_hh']) ? $array['goal_time_hh'] : '0'), (isset($array['goal_time_mm']) ? $array['goal_time_mm'] : '0'), (isset($array['goal_time_ss']) ? $array['goal_time_ss'] : '0'), $array['goal_dist'], $array['goal_dist_id'], $array['goal_reps'], $array['goal_resist'], $array['goal_resist_id'], $array['goal_rate'], $array['goal_rate_id'], $array['goal_angle'], $array['goal_angle_id'], $array['goal_int_id'], (isset($array['goal_rest_mm']) ? $array['goal_rest_mm'] : '0'), (isset($array['goal_rest_ss']) ? $array['goal_rest_ss'] : '0'), $array['goal_remarks']))->execute();
				
		$goal_gendata_results = DB::insert('wkout_sample_goal_gendata', array('wkout_id','goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id' , 'status_id'))
		->values(array($workid, $array['goal_unit_id'], '0', $array['goal_title'], $array['goal_title_self'],$array['goal_order'] , $userId, '1'))->execute();
		return $goal_gendata_results[0];
	}
	public function addAssignWorkoutSetFromExistworkout($array, $userId , $workassignid)
	{
		$goal_results = DB::insert('wkout_assign_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'primary_resist', 'primary_rate', 'primary_angle', 'primary_rest', 'primary_int', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))
		->values(array(0, 0, $array['primary_time'], $array['primary_dist'], $array['primary_reps'], $array['primary_resist'], $array['primary_rate'] , $array['primary_angle'], $array['primary_rest'], $array['primary_int'], (isset($array['goal_time_hh']) ? $array['goal_time_hh'] : '0'), (isset($array['goal_time_mm']) ? $array['goal_time_mm'] : '0'), (isset($array['goal_time_ss']) ? $array['goal_time_ss'] : '0'), $array['goal_dist'], $array['goal_dist_id'], $array['goal_reps'], $array['goal_resist'], $array['goal_resist_id'], $array['goal_rate'], $array['goal_rate_id'], $array['goal_angle'], $array['goal_angle_id'], $array['goal_int_id'], (isset($array['goal_rest_mm']) ? $array['goal_rest_mm'] : '0'), (isset($array['goal_rest_ss']) ? $array['goal_rest_ss'] : '0'), $array['goal_remarks']))->execute();
				
		$goal_gendata_results = DB::insert('wkout_assign_goal_gendata', array('wkout_assign_id','wkout_id','goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id' , 'status_id'))
		->values(array($workassignid, $array['wkout_id'], $array['goal_unit_id'], '0', $array['goal_title'], $array['goal_title_self'],$array['goal_order'] , $userId, '1'))->execute();
		return $goal_gendata_results[0];
	}
	public function addLoggedWorkoutSetFromworkout($array,$keyvalue , $userId , $worklogid)
	{
		if((isset($array['s_row_count']) || isset($array['s_row_count_xr'])) && (isset($array['wkout_id']) || !isset($array['wkout_id'])) && !empty($worklogid)){
				$timehh=''; $timemm=''; $timess=''; $restmm=''; $restss=''; $distance=''; $distanceid=''; $repitation=''; $resistance='';
				$resistanceid=''; $rate=''; $rateid=''; $angle=''; $angleid=''; $intdrive=''; $remark=''; $title=''; $type='';

				if(isset($array['exercise_title'][$keyvalue]) && !empty($array['exercise_title'][$keyvalue])){
					$title=$array['exercise_title'][$keyvalue];
				}
				$unit_id= '0';
				$goal_title_self = '1';
				if (isset($array['exercise_unit'][$keyvalue])){
					$exerciseUnitArray = explode('_',$array['exercise_unit'][$keyvalue]);
					if(isset($exerciseUnitArray[1]) && is_numeric($exerciseUnitArray[1])){
						$goal_title_self = 0;
						$unit_id	= $exerciseUnitArray[1];
					}else{
						$unit_id	= 0;
					}
				}
				if(isset($array['exercise_time'][$keyvalue]) && !empty($array['exercise_time'][$keyvalue]) && $array['exercise_time'][$keyvalue] != '00:00:00'){
					$timesplit=explode(':', $array['exercise_time'][$keyvalue]);
					$timehh=$timesplit[0];
					$timemm=$timesplit[1];
					$timess=$timesplit[2];
				}
				if(isset($array['exercise_rest'][$keyvalue]) && !empty($array['exercise_rest'][$keyvalue]) && $array['exercise_rest'][$keyvalue] != '00:00'){
					$restsplit=explode(':', $array['exercise_rest'][$keyvalue]);
					$restmm=$restsplit[0];
					$restss=$restsplit[1];
				}
				if(isset($array['exercise_distance'][$keyvalue]) && !empty($array['exercise_distance'][$keyvalue])){
					$distance=$array['exercise_distance'][$keyvalue];
				}
				if(isset($array['exercise_unit_distance'][$keyvalue]) && !empty($array['exercise_unit_distance'][$keyvalue])){
					$distanceid=$array['exercise_unit_distance'][$keyvalue];
				}
				if(isset($array['exercise_repetitions'][$keyvalue]) && !empty($array['exercise_repetitions'][$keyvalue])){
					$repitation=$array['exercise_repetitions'][$keyvalue];
				}
				if(isset($array['exercise_resistance'][$keyvalue]) && !empty($array['exercise_resistance'][$keyvalue])){
					$resistance=$array['exercise_resistance'][$keyvalue];
				}
				if(isset($array['exercise_unit_resistance'][$keyvalue]) && !empty($array['exercise_unit_resistance'][$keyvalue])){
					$resistanceid=$array['exercise_unit_resistance'][$keyvalue];
				}
				if(isset($array['exercise_rate'][$keyvalue]) && !empty($array['exercise_rate'][$keyvalue])){
					$rate=$array['exercise_rate'][$keyvalue];
				}
				if(isset($array['exercise_unit_rate'][$keyvalue]) && !empty($array['exercise_unit_rate'][$keyvalue])){
					$rateid=$array['exercise_unit_rate'][$keyvalue];
				}
				if(isset($array['exercise_angle'][$keyvalue]) && !empty($array['exercise_angle'][$keyvalue])){
					$angle=$array['exercise_angle'][$keyvalue];
				}
				if(isset($array['exercise_unit_angle'][$keyvalue]) && !empty($array['exercise_unit_angle'][$keyvalue])){
					$angleid=$array['exercise_unit_angle'][$keyvalue];
				}
				if(isset($array['exercise_innerdrive'][$keyvalue]) && !empty($array['exercise_innerdrive'][$keyvalue])){
					$intdrive=$array['exercise_innerdrive'][$keyvalue];
				}
				if(isset($array['exercise_remark'][$keyvalue]) && !empty($array['exercise_remark'][$keyvalue])){
					$remark=$array['exercise_remark'][$keyvalue];
				}
				$intensity='0';
				if(isset($array['per_intent'][$keyvalue]) && !empty($array['per_intent'][$keyvalue])){
					$intensity=$array['per_intent'][$keyvalue];
				}
				$set_remarks = '';
				if(isset($array['per_remarks'][$keyvalue]) && !empty($array['per_remarks'][$keyvalue])){
					$set_remarks=$array['per_remarks'][$keyvalue];
				}
				$set_status = '0';
				if(isset($array['markedstatus'][$keyvalue]) && !empty($array['markedstatus'][$keyvalue])){
					$set_status=$array['markedstatus'][$keyvalue];
				}
				$goal_results = DB::insert('wkout_log_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'primary_resist', 'primary_rate', 'primary_angle', 'primary_rest', 'primary_int', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))
					->values(array(0, 0, $array['primary_time'][$keyvalue], $array['primary_dist'][$keyvalue], $array['primary_reps'][$keyvalue], $array['primary_resist'][$keyvalue], $array['primary_rate'][$keyvalue] , $array['primary_angle'][$keyvalue], $array['primary_rest'][$keyvalue], $array['primary_int'][$keyvalue], $timehh, $timemm, $timess, $distance, $distanceid, $repitation, $resistance, $resistanceid, $rate, $rateid, $angle, $angleid, $intdrive, $restmm, $restss, $remark))->execute();
				
				$goal_gendata_results = DB::insert('wkout_log_goal_gendata', array('wkout_log_id','goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id' , 'status_id','set_status', 'note_set_intensity','note_set_remarks'))
					->values(array($worklogid, $unit_id, '0', $title, $goal_title_self, (!empty($array['goal_order'][$keyvalue]) ? $array['goal_order'][$keyvalue] : $keyvalue) , $userId, '1',$set_status, $intensity, $set_remarks))->execute();
		}
		return $goal_gendata_results[0];
	}
	public function addLoggedWorkoutSetFromExist($array, $userId, $worklogid)
	{
		if(!empty($worklogid)){
				$goal_results = DB::insert('wkout_log_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'primary_resist', 'primary_rate', 'primary_angle', 'primary_rest', 'primary_int', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))
					->values(array(0, 0, $array['primary_time'], $array['primary_dist'], $array['primary_reps'], $array['primary_resist'], $array['primary_rate'], $array['primary_angle'], $array['primary_rest'], $array['primary_int'],(isset($array['goal_time_hh']) ? $array['goal_time_hh'] : '0'), (isset($array['goal_time_mm']) ? $array['goal_time_mm'] : '0'), (isset($array['goal_time_ss']) ? $array['goal_time_ss'] : '0'), $array['goal_dist'], $array['goal_dist_id'], $array['goal_reps'], $array['goal_resist'], $array['goal_resist_id'], $array['goal_rate'], $array['goal_rate_id'], $array['goal_angle'], $array['goal_angle_id'], $array['goal_int_id'], (isset($array['goal_rest_mm']) ? $array['goal_rest_mm'] : '0'), (isset($array['goal_rest_ss']) ? $array['goal_rest_ss'] : '0'), $array['goal_remarks']))->execute();
				
				$goal_gendata_results = DB::insert('wkout_log_goal_gendata', array('wkout_log_id','goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id' , 'status_id','set_status', 'note_set_intensity','note_set_remarks'))
					->values(array($worklogid, $array['goal_unit_id'], '0', $array['goal_title'], $array['goal_title_self'], (!empty($array['goal_order']) ? $array['goal_order'] : '1') , $userId, '1',(isset($array['set_status']) ? $array['set_status'] : '0'), (isset($array['note_set_intensity']) ? $array['note_set_intensity'] : '0'), (isset($array['note_set_remarks']) ? $array['note_set_remarks'] : '')))->execute();
		}
		return $goal_gendata_results[0];
	}
	public function get_images($searchval){
		if($searchval!=''){
			$search="AND img_title LIKE '%".$searchval."%'";
		}else{
			$search='';
		}
		$sql = "SELECT * FROM img WHERE status_id=1 AND img_id!=1 ".$search." ORDER BY img_title";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function update_images($data){
		$sql = "UPDATE img SET status_id=".$data['img_status']." WHERE img_id=".$data['img_id'];
		$query 	= DB::query(Database::UPDATE,$sql)->execute();
		if($query){
			$list = $this->get_images();
		}
		return $list;
	}
	public function get_exerciseRecordGallery($filters){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$user = Auth::instance()->get_user()->pk();
		$files = array();
		$sort='asc';
		$viewStatus = 1;
		$user_access = 2; // for later use: [test] user's access level matches the record access id
		$limitStart	= !empty($filters['slimit']) ? $filters['slimit'] : 0;
		if(isset($filters['maxRows'])){
			$limitCurrent = !empty($filters['maxRows']) ? $filters['maxRows'] : 5;
		}else{
			$limitCurrent = !empty($filters['elimit']) ? $filters['elimit'] : 10;
		}
		if(isset($filters['folderid']) && !empty($filters['folderid'])){ 
			$xrfolderid = $filters['folderid'];
		}elseif(isset($filters['folderid']) && empty($filters['folderid'])){ 
			$xrfolderid = '0';
		}elseif(!isset($filters['folderid'])){ 
			$xrfolderid = '';
		}
		if(empty($filters['searchval'])) { $search_x=0;	}else{ $search_x=1; $search = $this->escapeStr($filters['searchval']); }; 
		if(empty($filters['sortby'])) { $sort_x=0;	}else{ $sort_x=1;   	$sort    = $this->escapeStr($filters['sortby']); }; 
		if(empty($filters['musprim'])) { $muscle_x=0; }else{ $muscle_x=1; $muscle  = $this->escapeStr($filters['musprim']); };
		if(empty($filters['type'])) 	{ $type_x=0;	}else{ $type_x=1;		$type		= $filters['type']; };
		if(empty($filters['equip']))	{ $equip_x=0;	}else{ $equip_x=1;	$equip	= $filters['equip']; };
		if(empty($filters['level']))	{ $level_x=0;	}else{ $level_x=1;	$level	= $filters['level']; };
		if(empty($filters['sport']))	{ $sport_x=0;	}else{ $sport_x=1;	$sport	= $filters['sport']; };
		if(empty($filters['force']))	{ $force_x=0;	}else{ $force_x=1;	$force	= $filters['force']; };
		if(empty($filters['tags']))	{ $tags_x=0; 	}else{ $tags_x=1;		$tags		= $filters['tags']; };
		if(!isset($filters['recform']) || empty($filters['recform'])) { $recform_x=0; }else{ $recform_x=1; $recform = $filters['recform']; };
		
		// for create from library
		if($recform_x==1 && !empty($recform)){
			if($recform == 'myexercise')
				$xrfolderid = '0';
			elseif($recform == 'sampleexercise')
				$xrfolderid = '2';
			elseif($recform == 'sharedexercise')
				$xrfolderid = '3';
		}

		/* Count of the all filtered items */
		$c_filters = $search_x
			+$muscle_x
			+$type_x
			+$equip_x
			+$level_x
			+$sport_x
			+$force_x
			+$tags_x ;
		if($c_filters>0){
			$f_start='AND (';
			$f_end=')';
		}else{
			$f_start='';
			$f_end='';
		}	
		$orderBy = '';
		/* function for escaping each of the array items */
		function escape_str($item) {
		   $item 	= Database::instance()->escape($item);  
		   return $item;
		}
		
		$f='';
		$e=0;

		/* $f_search */
		if ( $search_x==0 ) {
		}else{  
			$e=1;
			$f.= '(xrgd.title LIKE "%'.$search.'%")';
			$orderBy = ' (CASE WHEN xrgd.title = "'.$search.'" THEN 0
				WHEN xrgd.title LIKE "'.$search.'%" THEN 1
				WHEN xrgd.title LIKE "% %'.$search.'% %" THEN 2
				WHEN xrgd.title LIKE "%'.$search.'" THEN 3
				ELSE 4
			END), ';
		}		
		
		/* $f_muscles */
		if ( $muscle_x==0 ) {}else{ 
			/* Gate_1 */
			if(	$e==1 ){ // there's an AND from before this
				$f.=' AND ';
			}else{} // no AND from before this	

			$e=1;
			$f.= 'xrgd.musprim_id='.$muscle;
		}

		/* $f_types */
		if ( $type_x==0 ) {}else{
			/* Gate_2 */
			if(	$e==1 ){ // there's an AND from before this
				$f.=' AND ';
			}else{}  // no AND from before this
			$e=1;
			$f.= '(';
			$c_type = count($type);
			for ($i = 0; $i<$c_type; ++$i) {	
				$type_i = escape_str($type[$i]);
				if( $i != ($c_type-1) ){ $f.= 'xrgd.type_id='.$type_i.' OR '; 
				}else{ $f.= 'xrgd.type_id='.$type_i.')';
				}
			}
		}
		
		/* $f_equips */		
		if ( $equip_x==0 ) {}else{	
			/* Gate_3*/
			if(	$e==1 ){ // there's an AND from before this
				$f.=' AND ';
			}else{}  // no AND from before this
			$e=1;
			$f.= '(';
			$c_equip = count($equip);
			for ($i = 0; $i<$c_equip; ++$i) {	
				$equip_i = escape_str($equip[$i]);
				if( $i != ($c_equip-1) ){ $f.= 'xrgd.equip_id='.$equip_i.' OR '; 
				}else{ $f.= 'xrgd.equip_id='.$equip_i.')';
				}
			}
		}
		
		/* $f_levels */
		if ( $level_x==0 ) {}else{ 			
			/* Gate_4 */
			if(	$e==1 ){ // there's an AND from before this
				$f.=' AND ';
			}else{}  // no AND from before this
			$e=1;
			$f.= '(';
			$c_level = count($level);
			for ($i = 0; $i<$c_level; ++$i) {	
				$level_i = escape_str($level[$i]);
				if( $i != ($c_level-1) ){ $f.= 'xrgd.level_id='.$level_i.' OR '; 
				}else{ $f.= 'xrgd.level_id='.$level_i.')';
				}
			}
		}

		/* $f_sports */		
		if ( $sport_x==0 ) {}else{ 
			/* Gate_5 */
			if(	$e==1 ){ // there's an AND from before this
				$f.=' AND ';
			}else{}  // no AND from before this
			$e=1;
			$f.= '(';
			$c_sport = count($sport);
			for ($i = 0; $i<$c_sport; ++$i) {	
				$sport_i = escape_str($sport[$i]);
				if( $i != ($c_sport-1) ){$f.= 'xrgd.sport_id='.$sport_i.' OR '; 
				}else{ $f.= 'xrgd.sport_id='.$sport_i.')';
				}
			}
		}
		
		/* $f_forces */
		if ( $force_x==0 ) {}else{ 		
			/* Gate_6 */
			if(	$e==1 ){ // there's an AND from before this
				$f.=' AND ';
			}else{}  // no AND from before this
			$e=1;
			$f.= '(';
			$c_force = count($force);
			for ($i = 0; $i<$c_force; ++$i) {	
				$force_i = escape_str($force[$i]);
				if( $i != $c_force-1 ){ $f.= 'xrgd.force_id='.$force_i.' OR '; 
				}else{ $f.= 'xrgd.force_id='.$force_i.')';
				}
			}
		}

		/*$f_tags*/
		if ( $tags_x==0 ) {}else{
			/* Gate_7 */
			if( $e==1 ){ // there's an AND from before this
				$f.=' AND ';
			}else{}  // no AND from before this
			$e=1;
			$f.= 'g6.created_by="'.$user.'" AND (';
			$c_tag = count($tags);
			for ($i = 0; $i<$c_tag; ++$i) {	
				$tag_i = escape_str($tags[$i]);
				if( $i != ($c_tag-1) ){ $f.= 'g6.tag_id='.$tag_i.' OR '; 
				}else{ $f.= 'g6.tag_id='.$tag_i.')';
				}
			}
		}
		/*sorting*/
		if($sort == 'asc' || $sort == 'desc'){
			$orderBy .= 'xrgd.title '.strtoupper($sort);
		}
		else{
			$orderBy .= 'xrgd.'.$sort.' DESC';
		}
		$shared_select = $shared_from = '';
		// getExerciseByType();
		$site_ids = Helper_Common::getAllSiteId();
		$cond = " AND ((xrgd.default_status=0 AND xrgd.created_by=".$user." AND xrgd.site_id in (".$site_ids.")) OR (xrgd.default_status=3 AND xrgd.created_by=".$user." AND xrgd.site_id in (".$site_ids.")) OR ((xrgd.default_status=2 AND xrgd.site_id in (".$site_id.")) ".(Helper_Common::hasAccessByDefaultXr($site_id) ?" OR (xrgd.default_status=1 AND s.sample_workouts=1 AND (ds.record_mod_action!=2 OR ds.id is NULL))" : '').")) ";
		/*query build for create form folder and folder section*/
		if($xrfolderid!=''){
			if($xrfolderid=='0'){
				$cond = " AND xrgd.default_status=0 ";
			}elseif($xrfolderid=='2'){
				$cond = " AND ((xrgd.default_status=2 AND xrgd.site_id in (".$site_id.")) ".(Helper_Common::hasAccessByDefaultXr($site_id) ?" OR (xrgd.default_status=1 AND s.sample_workouts=1 AND (ds.record_mod_action!=2 OR ds.id is NULL))" : '').") ";
			}elseif($xrfolderid=='3'){
				$cond = " AND xrgd.default_status=3 ";
				$shared_select = ' ,ugs.unit_share_id, ugs.shared_by, ugs.shared_msg, concat(usr.user_fname," ",usr.user_lname) as user_name ';
				$shared_from = ' LEFT JOIN unit_gendata_shared AS ugs ON xrgd.unit_id=ugs.unit_id LEFT JOIN users AS usr ON usr.id=ugs.shared_by ';
			}
		}
		// Query the exercise record data
		$sql = "SELECT DISTINCT xrgd.*, g3.*, g4.img_id, g4.img_title, g4.img_type, g4.img_url, g4.subfolder_id, g4.parentfolder_id, g5.* ".$shared_select." FROM unit_gendata AS xrgd 
			LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id 
			LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id 
			LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id 
			LEFT JOIN unit_tag AS g6 ON xrgd.unit_id=g6.unit_id 
			LEFT JOIN sites s ON xrgd.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = xrgd.unit_id AND ds.record_type_id=2 )" .$shared_from. "
			WHERE xrgd.status_id = ".$viewStatus.$cond.(isset($xrfolderid) && $xrfolderid=='0' ? " AND xrgd.created_by=".$user." AND xrgd.site_id in (".$site_ids.") " : '').(isset($xrfolderid) && $xrfolderid =='3' ? " AND xrgd.created_by=".$user." AND xrgd.site_id in (".$site_ids.") " : '')." AND s.is_active = 1 AND s.is_deleted = 0 ".$f_start." ".$f." ".$f_end." ORDER BY ".$orderBy." LIMIT ".$limitStart.", ".$limitCurrent."";
      // echo "<br>";echo $sql; exit;
	  	$data = DB::query(Database::SELECT,$sql)->execute();
		// Run the recursive function 
		$files[] = array('itemcount'=>count($data), 'folder' => $xrfolderid);
		if($data!=null && count($data)>0){
			foreach($data as $row){
				$thumbimage = $image ='';
				$url1 = substr ( $row['img_url'] ,strripos( $row['img_url'],"img_" ) );
				$urlPrefix_thmb = 'assets/images/dynamic/exercise/thumb/';
				if( !empty($url1) && file_exists ( $urlPrefix_thmb.'thumb_'.$url1 ) ) {
					$thumbimage = URL::base().$urlPrefix_thmb.'thumb_'.$url1;
				} else {
					if(!empty($row['img_url'])){
						$url2 = str_replace('exercise/img/','',substr($row['img_url'],strripos($row['img_url'],"exercise/img/")));
						if( !empty($url2) && file_exists ( $urlPrefix_thmb.$url2 ) )
							$thumbimage = URL::base().$urlPrefix_thmb.$url2;
					}
				}
				if( !empty($row['img_url']) && file_exists ( $row['img_url'] ) ) {
					$image = URL::base().$row['img_url'];
					if(empty($thumbimage) && !empty($image))
						$thumbimage = $image;
				}
				if(empty($image) && !empty($thumbimage))
						$image = $thumbimage;
				$recUrl = '?unit_id='.$row['unit_id'];
				// Pack file listings into array
				$relatedcount = $this->getRelatedExercise($row['unit_id'], $row['muscle_id'], $row['status_id'], $row['type_id']);
				$xrcisetags = $this->getUnitTagsById($row['unit_id']);
				$default_status = ($row['default_status'] == 0 ? 'from My Records' : ($row['default_status'] == 1 ? (Helper_Common::is_admin() || Helper_Common::is_manager() ? 'from Default Records' : 'from Sample Records') : ($row['default_status'] == 2 ? 'from Sample Records' : ($row['default_status'] == 3 ? 'from Shared Records' : '' ))));
				$sharedby_role = (isset($row['shared_by']) && !empty($row['shared_by']) ? $this->getRoleNameByUserId($row['shared_by']) : '');
				$files[] = array(
					"id"		=> $row['unit_id'],
					"name"		=> $row['title'],
					"featimg"	=> $thumbimage,
					"previmg"	=> $image,
					"muscle"	=> $row['muscle_title'],
					"equip"		=> $row['equip_title'],
					"path"		=> $recUrl,
					"related"	=> $relatedcount,
					"tags"		=> $xrcisetags,
					"default"	=> $default_status,
					"folderid"	=>	$row['default_status'],
					"shared_by" => (isset($sharedby_role['name']) && !empty($sharedby_role) ? ucwords($sharedby_role['name']) : ''),
					"shared_msg" => (isset($row['shared_msg']) ? $row['shared_msg'] : ''),
					"user_name" => (isset($row['user_name']) ? ucwords($row['user_name']) : '')
				);
			}
		}
		return $files;
	}
	public function getExerciseCountByFolder($xrfolderid){
		$userid = Auth::instance()->get_user()->pk();
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$site_ids = Helper_Common::getAllSiteId();
		$sql = "SELECT count(DISTINCT xrgd.unit_id) as xrcisecnt FROM unit_gendata AS xrgd 
			LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id 
			LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id 
			LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id 
			LEFT JOIN unit_tag AS g6 ON xrgd.unit_id=g6.unit_id 
			LEFT JOIN sites s ON xrgd.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = xrgd.unit_id AND ds.record_type_id=2 )
			WHERE xrgd.status_id = 1 ".($xrfolderid==0 ? " AND xrgd.default_status=0 AND xrgd.created_by=".$userid." AND xrgd.site_id in (".$site_ids.") " : ($xrfolderid==2 ? " AND ((xrgd.default_status=2 AND xrgd.site_id in (".$site_id.")) ".(Helper_Common::hasAccessByDefaultXr($site_id) ? " OR (xrgd.default_status=1 AND s.sample_workouts=1 AND (ds.record_mod_action!=2 OR ds.id is NULL))" : '').") " : ($xrfolderid==3 ? " AND xrgd.default_status=3 AND xrgd.created_by=".$userid." AND xrgd.site_id in (".$site_ids.") " : "")))." AND s.is_active = 1 AND s.is_deleted = 0  ORDER BY xrgd.title ASC";
	  	$rescnt = DB::query(Database::SELECT,$sql)->execute()->as_array();
		return $rescnt[0]['xrcisecnt'];
	}
	public function getExerciseByType($type='', $unit_id = 0){
		$userid = Auth::instance()->get_user()->pk();
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$site_ids = Helper_Common::getAllSiteId();
		$cond = (!empty($unit_id) ? " AND xrgd.unit_id='".$unit_id."' " : "")." AND xrgd.default_status=0 AND xrgd.created_by=".$userid." AND xrgd.site_id in (".$site_ids.") ";
		if($type == 'sampleexercise'){
			$cond = (!empty($unit_id) ? " AND xrgd.unit_id='".$unit_id."' " : "")." AND ((xrgd.default_status=2 AND xrgd.site_id in (".$site_id.")) ".(Helper_Common::hasAccessByDefaultXr($site_id) ? " OR (xrgd.default_status=1 AND s.sample_workouts=1 AND (ds.record_mod_action!=2 OR ds.id is NULL))" : '').") ";
		}elseif($type == 'sharedexercise'){
			$cond = (!empty($unit_id) ? " AND xrgd.unit_id='".$unit_id."' " : "")." AND xrgd.default_status=3 AND xrgd.created_by=".$userid." AND xrgd.site_id in (".$site_ids.") ";
		}
		$sql = "SELECT DISTINCT xrgd.*, g3.*, g4.img_id, g4.img_title, g4.img_type, g4.img_url, g4.subfolder_id, g4.parentfolder_id, g5.* FROM unit_gendata AS xrgd 
			LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id 
			LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id 
			LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id 
			LEFT JOIN unit_tag AS g6 ON xrgd.unit_id=g6.unit_id 
			LEFT JOIN sites s ON xrgd.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = xrgd.unit_id AND ds.record_type_id=2 )
			WHERE xrgd.status_id = 1".$cond."AND s.is_active = 1 AND s.is_deleted = 0 ORDER BY xrgd.date_created DESC";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
     	return $list;
	}
	public function getRoleNameByUserId($userId){
		$sql = "SELECT name FROM roles WHERE id = ".$this->getUserRoleById($userId);
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
		return $list[0];
	}
	public function getSharedXrUnreadDetails($user_id){
		$site_ids = Helper_Common::getAllSiteId();
		$sql = "SELECT GROUP_CONCAT(ugs.unit_share_id separator '#') AS unitids, trs.wkoutids AS unitidsreplace 
			FROM unit_gendata_shared AS ugs JOIN unit_gendata AS xrgd ON (xrgd.unit_id = ugs.unit_id) JOIN sites AS s ON ugs.site_id = s.id
			LEFT JOIN track_read_status AS trs ON (trs.wkoutids LIKE CONCAT('%#', ugs.unit_share_id, '#%') AND trs.xr_type = '1' AND trs.status_id = '1' AND trs.read_by = '".$user_id."' AND trs.is_from = '1') 
			WHERE xrgd.created_by = '".$user_id."' AND ugs.shared_for = '".$user_id."' AND xrgd.default_status = 3 AND xrgd.site_id IN (".$site_ids.") AND xrgd.status_id = '1' AND ugs.status_id = '1' AND s.is_active = 1 AND s.is_deleted = 0";
		$rescnt = DB::query(Database::SELECT,$sql)->execute()->as_array();
		return (isset($rescnt[0]) ? $rescnt[0] : $rescnt);
	}
	public function getSharedXrUnreadCount($user_id){
		$site_ids = Helper_Common::getAllSiteId();
		$sql = "SELECT COUNT(ugs.unit_share_id) AS totalsharedxr, trs.wkoutids AS totalxrreadids
			FROM unit_gendata_shared AS ugs JOIN unit_gendata AS xrgd ON (xrgd.unit_id = ugs.unit_id) JOIN sites AS s ON ugs.site_id = s.id
			LEFT JOIN track_read_status AS trs ON (trs.wkoutids LIKE CONCAT('%#', ugs.unit_share_id, '#%') AND trs.xr_type = '1' AND trs.status_id = '1' AND trs.read_by = '".$user_id."' AND trs.is_from = '1') 
			WHERE xrgd.created_by = '".$user_id."' AND ugs.shared_for = '".$user_id."' AND xrgd.default_status = 3 AND xrgd.site_id IN (".$site_ids.") AND xrgd.status_id = '1' AND ugs.status_id = '1' AND s.is_active = 1 AND s.is_deleted = 0";
		$rescnt = DB::query(Database::SELECT,$sql)->execute()->as_array();
		return (isset($rescnt[0]) ? $rescnt[0] : $rescnt);
	}
	public function updateUnitReadStatus($replace_val ,$array = array()){
		if(empty($replace_val)){
			$workout_results = DB::insert('track_read_status', array('wkoutids', 'xr_type', 'read_by', 'site_id', 'status_id', 'created_date', 'modified_date', 'is_from'))
			->values(array('#'.$array['wkoutids'], $array['xr_type'], $array['read_by'], $array['site_id'], $array['status_id'], $array['created_date'], $array['modified_date'], '1'))->execute();
			return true;
		}else{
			$sql = "UPDATE track_read_status SET wkoutids = '".$array['wkoutids']."' WHERE wkoutids LIKE '".$replace_val."' AND xr_type='".$array['xr_type']."' AND status_id='1' AND is_from='1' AND read_by='".$array['read_by']."'";
			return DB::query(Database::UPDATE,$sql)->execute();
		}
	}
	public function updateExerciseSet($updateArr , $goal_id){
		$query = DB::update('goal_gendata')->set(array(
											'goal_title' 	=> $updateArr['title'], 
											'goal_title_self' => $updateArr['goal_title_self'], 
											'goal_unit_id' => $updateArr['goal_unit_id'],
											'goal_order' => $updateArr['goal_order']
											))->where('goal_id', '=', $goal_id)->execute();
		unset($updateArr['title']);
		unset($updateArr['goal_order']);
		unset($updateArr['goal_title_self']);
		unset($updateArr['goal_unit_id']);
		$query = DB::update('goal_vars')->set($updateArr)->where('goal_id', '=', $goal_id)->execute();
		return true;
	}
	public function updateSampleExerciseSet($updateArr , $goal_id){
		$query = DB::update('wkout_sample_goal_gendata')->set(array(
											'goal_title' 	=> $updateArr['title'], 
											'goal_title_self' => $updateArr['goal_title_self'], 
											'goal_unit_id' => $updateArr['goal_unit_id'],
											'goal_order' => $updateArr['goal_order']
											))->where('goal_id', '=', $goal_id)->execute();
		unset($updateArr['title']);
		unset($updateArr['goal_order']);
		unset($updateArr['goal_title_self']);
		unset($updateArr['goal_unit_id']);
		$query = DB::update('wkout_sample_goal_vars')->set($updateArr)->where('goal_id', '=', $goal_id)->execute();
		return true;
	}
	public function insertExerciseSet($insertArr , $userId){
		$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'primary_resist', 'primary_rate', 'primary_angle', 'primary_rest', 'primary_int', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))
					->values(array(0, 0, $insertArr['primary_time'], $insertArr['primary_dist'], $insertArr['primary_reps'], $insertArr['primary_resist'], $insertArr['primary_rate'] , $insertArr['primary_angle'], $insertArr['primary_rest'], $insertArr['primary_int'], $insertArr['goal_time_hh'], $insertArr['goal_time_mm'], $insertArr['goal_time_ss'], $insertArr['goal_dist'], $insertArr['goal_dist_id'], $insertArr['goal_reps'], $insertArr['goal_resist'], $insertArr['goal_resist_id'], $insertArr['goal_rate'], $insertArr['goal_rate_id'], $insertArr['goal_angle'], $insertArr['goal_angle_id'], $insertArr['goal_int_id'], $insertArr['goal_rest_mm'], $insertArr['goal_rest_ss'], $insertArr['goal_remarks']))->execute();
				
		$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id' , 'status_id'))
					->values(array($insertArr['wkout_id'], $insertArr['goal_unit_id'], '0', $insertArr['title'], $insertArr['goal_title_self'], (!empty($insertArr['goal_order']) ? $insertArr['goal_order'] : '1') , $userId, '1'))->execute();
			
								
		return $goal_gendata_results[0];
	}
	public function insertSampleExerciseSet($insertArr , $userId){
		$goal_results = DB::insert('wkout_sample_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'primary_resist', 'primary_rate', 'primary_angle', 'primary_rest', 'primary_int', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))
					->values(array(0, 0, $insertArr['primary_time'], $insertArr['primary_dist'], $insertArr['primary_reps'], $insertArr['primary_resist'], $insertArr['primary_rate'] , $insertArr['primary_angle'], $insertArr['primary_rest'], $insertArr['primary_int'], $insertArr['goal_time_hh'], $insertArr['goal_time_mm'], $insertArr['goal_time_ss'], $insertArr['goal_dist'], $insertArr['goal_dist_id'], $insertArr['goal_reps'], $insertArr['goal_resist'], $insertArr['goal_resist_id'], $insertArr['goal_rate'], $insertArr['goal_rate_id'], $insertArr['goal_angle'], $insertArr['goal_angle_id'], $insertArr['goal_int_id'], $insertArr['goal_rest_mm'], $insertArr['goal_rest_ss'], $insertArr['goal_remarks']))->execute();
				
		$goal_gendata_results = DB::insert('wkout_sample_goal_gendata', array('wkout_sample_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order',  'status_id'))
					->values(array($insertArr['wkout_id'], $insertArr['goal_unit_id'], '0', $insertArr['title'], $insertArr['goal_title_self'], (!empty($insertArr['goal_order']) ? $insertArr['goal_order'] : '1') ,  '1'))->execute();								
		return $goal_gendata_results[0];
	}
	
	public function updateAssignExerciseSet($updateArr , $goal_id){
		$query = DB::update('wkout_assign_goal_gendata')->set(array(
											'goal_title' 	=> $updateArr['title'], 
											'goal_title_self' => $updateArr['goal_title_self'], 
											'goal_unit_id' => $updateArr['goal_unit_id'],
											'goal_order' => $updateArr['goal_order']
											))->where('goal_id', '=', $goal_id)->execute();
		unset($updateArr['title']);
		unset($updateArr['goal_order']);
		unset($updateArr['goal_title_self']);
		unset($updateArr['goal_unit_id']);
		$query = DB::update('wkout_assign_goal_vars')->set($updateArr)->where('goal_id', '=', $goal_id)->execute();
		return true;
	}
	public function updateLogExerciseSet($updateArr , $goal_id){
		$query = DB::update('wkout_log_goal_gendata')->set(array(
							'goal_title' 	=> $updateArr['title'], 
							'goal_title_self' => $updateArr['goal_title_self'], 
							'goal_unit_id' => $updateArr['goal_unit_id'],
							'set_status'  => $updateArr['set_status'],
							'note_set_intensity'  => $updateArr['note_set_intensity'],
							'note_set_remarks'  => $updateArr['note_set_remarks'],
							'goal_order' => $updateArr['goal_order']
							))->where('goal_id', '=', $goal_id)->execute();
		unset($updateArr['title']);
		unset($updateArr['goal_order']);
		unset($updateArr['goal_title_self']);
		unset($updateArr['goal_unit_id']);
		unset($updateArr['set_status']);
		unset($updateArr['note_set_intensity']);
		unset($updateArr['note_set_remarks']);
		$query = DB::update('wkout_log_goal_vars')->set($updateArr)->where('goal_id', '=', $goal_id)->execute();
		return true;
	}
	public function doCopyForExerciseSetsById($type, $userId, $xrsetId, $wkoutId, $moveAction =''){
		$datetime = Helper_Common::get_default_datetime();
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$user_access = $this->getUserRole();
		if($type == 'exerciseSet'){
			$exerciseSet = $this->getExerciseSetAllDetailsByWorkout($wkoutId, $xrsetId , $userId);
			$upFlagUpdateOrder = false;
			if(empty($moveAction))
				$getGoalOrder = $this->getMaxGoalorder($wkoutId, $userId);
			else{
				$currOrder = $this->getCurrentGoalorder($wkoutId, $xrsetId , $userId);
				//down insert last, up insert befor curr record
				if($moveAction =='down')
					$getGoalOrder = $currOrder+1;
				else{
					$getGoalOrder = (($currOrder> 1) ? $currOrder-1 : '1');
					$upFlagUpdateOrder = true;
				}
			}
			if(is_array($exerciseSet)){
				$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'primary_resist', 'primary_rate', 'primary_angle', 'primary_rest', 'primary_int', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))
					->values(array($exerciseSet['goal_iso'], $exerciseSet['goal_alt'], $exerciseSet['primary_time'], $exerciseSet['primary_dist'], $exerciseSet['primary_reps'], $exerciseSet['primary_resist'], $exerciseSet['primary_rate'] , $exerciseSet['primary_angle'], $exerciseSet['primary_rest'], $exerciseSet['primary_int'], $exerciseSet['goal_time_hh'], $exerciseSet['goal_time_mm'], $exerciseSet['goal_time_ss'], $exerciseSet['goal_dist'], $exerciseSet['goal_dist_id'], $exerciseSet['goal_reps'], $exerciseSet['goal_resist'], $exerciseSet['goal_resist_id'], $exerciseSet['goal_rate'], $exerciseSet['goal_rate_id'], $exerciseSet['goal_angle'], $exerciseSet['goal_angle_id'], $exerciseSet['goal_int_id'], $exerciseSet['goal_rest_mm'], $exerciseSet['goal_rest_ss'], $exerciseSet['goal_remarks']))->execute();
				
				$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order' , 'user_id' , 'status_id'))
					->values(array($wkoutId, $exerciseSet['goal_unit_id'], $exerciseSet['goal_group'], $exerciseSet['goal_title'], $exerciseSet['goal_title_self'], $getGoalOrder , $userId,'1'))->execute();
				if($upFlagUpdateOrder)
					$this->updateAllGoalorder($moveAction,$goal_gendata_results[0], $wkoutId);
				return $goal_gendata_results[0];
			}else{
				return false;
			}
		}else if($type == 'workout'){
			$records = $this->getworkoutById($userId,$wkoutId);
			$curSeqOrderArray = $this->getCurrentSeqorderByWkoutId($wkoutId);
			$exerciseRecords = $this->getExerciseSets('wkout', $wkoutId);
			if(is_array($records) && count($records)>0){
				$records['parent_folder_id'] = $curSeqOrderArray['parent_folder_id'];
				$records['created_date']     = $datetime;
				$records['modified_date']	 = $datetime;
				$records['wkout_title']		 = $records['wkout_title'];
				$currentWkseqOrder = $curSeqOrderArray['seq_order'];
				if($moveAction =='down')
					$records['seq_order'] = $currentWkseqOrder+1;
				else
					$records['seq_order'] = (($currentWkseqOrder> 1) ? $currentWkseqOrder-1 : '1');
				$insertId = $this->insertWorkoutDetailsByseqOrder($records);
				if(is_array($exerciseRecords) && count($exerciseRecords)>0){
					foreach($exerciseRecords as $keys => $values){
						if($values['goal_title'] != 'Click_to_Edit'){
							$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
								
							$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $userId, '1'))->execute();
						}
					}
				}
				return $insertId;
			}
			return true;
		}else if($type == 'workoutAssign'){
			$wkout_record = $this->getAssignworkoutById($wkoutId,$userId);
			$exerciseSets = $this->getExerciseSets('assigned',$wkoutId);
			if(is_array($wkout_record) && count($wkout_record)>0){
				$assignArr['assigned_by'] = $assignArr['assigned_for'] = $userId;
				$assignArr['modified_by'] = '0';
				$assignArr['created'] = $assignArr['modified'] = $datetime;
				$assign_results = DB::insert('wkout_assign_gendata', array('wkout_id', 'from_wkout','wkout_group' ,'wkout_title' ,'wkout_color' ,'wkout_assign_order' ,'user_id' ,'site_id', 'status_id' ,'access_id' ,'wkout_focus' ,'wkout_poa' ,'wkout_poa_time' ,'assigned_by' ,'assigned_for' ,'assigned_date' ,'created' ,'modified' ,'modified_by'))->values(array((isset($wkout_record['wkout_id']) ? $wkout_record['wkout_id'] : '0'), '3',(isset($wkout_record['wkout_group']) ? $wkout_record['wkout_group'] : '') ,  (isset($wkout_record['wkout_title']) ? $wkout_record['wkout_title'] : '') , (isset($wkout_record['wkout_color']) ? $wkout_record['wkout_color'] : '') , '0' , $userId , $site_id, '1' ,$user_access ,(isset($wkout_record['wkout_focus']) ? $wkout_record['wkout_focus'] : '') , (isset($wkout_record['wkout_poa']) ? $wkout_record['wkout_poa'] : '') , (isset($wkout_record['wkout_poa_time']) ? $wkout_record['wkout_poa_time'] : '') ,$assignArr['assigned_by'], $assignArr['assigned_for'], $wkout_record['assigned_date'], $assignArr['created'], $assignArr['modified'], $assignArr['modified_by']))->execute();
				if(isset($exerciseSets) && is_array($exerciseSets) && count($exerciseSets)>0){
					foreach($exerciseSets as $keys => $values){
						$goal_results = DB::insert('wkout_assign_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
										
						$goal_gendata_results = DB::insert('wkout_assign_goal_gendata', array('wkout_assign_id','wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($assign_results[0], (isset($values['wkout_id']) ? $values['wkout_id'] : '0') , $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $userId, '1'))->execute();
					}
				}
				return $assign_results[0];
			}
			return true;
		}else if($type == 'workoutToshare'){
			if(is_array($userId)){
				$shared_by  = $userId['shared_by'];
				$shared_for = $userId['shared_for'];
				$shared_msg = $userId['shared_msg'];
				$site_id	= $userId['site_id'];
			}
			$shared_user_access = $this->getUserRoleById($userId['shared_for']);
			$records = $this->getworkoutById($shared_by,$wkoutId);
			$exerciseRecords = $this->getExerciseSets('wkout', $wkoutId);
			if(is_array($records) && count($records)>0){
				$records['parent_folder_id'] = '0';
				$records['created']   = $records['created_date']  	 = $datetime;
				$records['modified']  = $records['modified_date'] 	 = $datetime;
				$records['shared_by']     	 = $shared_by;
				$records['shared_for']	 	 = $shared_for;
				$records['shared_msg']	 	 = $shared_msg;
				$records['wkout_id']		 = $wkoutId;
				$records['site_id']			 = $site_id;
				$records['access_id']		 = $shared_user_access;
				$records['from_wkout']		 = '0';
				$records['wkout_title']		 = $records['wkout_title'];
				$records['seq_order'] 		 = 1;
				$insertId = $this->insertShareWorkoutDetailsByseqOrder($records);
				if(is_array($exerciseRecords) && count($exerciseRecords)>0){
					foreach($exerciseRecords as $keys => $values){
						if($values['goal_title'] != 'Click_to_Edit'){
							$goal_results = DB::insert('wkout_share_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
								
							$goal_gendata_results = DB::insert('wkout_share_goal_gendata', array('wkout_share_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $shared_for, '1'))->execute();
						}
					}
				}
				return $insertId;
			}
			return true;
		}else if($type == 'workoutAssignToshare'){
			if(is_array($userId)){
				$shared_by  = $userId['shared_by'];
				$shared_for = $userId['shared_for'];
				$shared_msg = $userId['shared_msg'];
				$site_id	= $userId['site_id'];
			}
			$shared_user_access = $this->getUserRoleById($userId['shared_for']);
			$records 	= $this->getAssignworkoutById($wkoutId,$shared_by);
			if(is_array($records) && count($records)>0){
				$exerciseRecords = $this->getExerciseSets('assigned', $wkoutId);
				$records['parent_folder_id'] = '0';
				$records['created']   = $records['created_date'] = $datetime;
				$records['modified']  = $records['modified_date']= $datetime;
				$records['shared_by']     	 = $shared_by;
				$records['shared_for']	 	 = $shared_for;
				$records['shared_msg']	 	 = $shared_msg;
				$records['wkout_id']		 = $wkoutId;
				$records['site_id']			 = $site_id;
				$records['access_id']		 = $shared_user_access;
				$records['from_wkout']		 = '3';
				$records['wkout_title']		 = $records['wkout_title'];
				$records['seq_order'] 		 = 1;
				$insertId = $this->insertShareWorkoutDetailsByseqOrder($records);
				if(is_array($exerciseRecords) && count($exerciseRecords)>0){
					foreach($exerciseRecords as $keys => $values){
						if($values['goal_title'] != 'Click_to_Edit'){
							$goal_results = DB::insert('wkout_share_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
								
							$goal_gendata_results = DB::insert('wkout_share_goal_gendata', array('wkout_share_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $shared_for, '1'))->execute();
						}
					}
				}
				return $insertId;
			}
			return true;
		}else if($type == 'workoutLogToshare'){
			if(is_array($userId)){
				$shared_by  = $userId['shared_by'];
				$shared_for = $userId['shared_for'];
				$shared_msg = $userId['shared_msg'];
				$site_id	= $userId['site_id'];
			}
			$shared_user_access = $this->getUserRoleById($userId['shared_for']);
			$records = $this->getLoggedworkoutById($wkoutId,$shared_by);
			if(is_array($records) && count($records)>0){
				$exerciseRecords = $this->getExerciseSets('wkoutlog',$wkoutId);
				$records['parent_folder_id'] = '0';
				$records['created']   = $records['created_date']  	 = $datetime;
				$records['modified']  = $records['modified_date'] 	 = $datetime;
				$records['shared_by']     	 = $shared_by;
				$records['shared_for']	 	 = $shared_for;
				$records['shared_msg']	 	 = $shared_msg;
				$records['wkout_id']		 = $wkoutId;
				$records['site_id']			 = $site_id;
				$records['access_id']		 = $shared_user_access;
				$records['from_wkout']		 = '4';
				$records['wkout_title']		 = $records['wkout_title'];
				$records['seq_order'] 		 = 1;
				$insertId = $this->insertShareWorkoutDetailsByseqOrder($records);
				if(is_array($exerciseRecords) && count($exerciseRecords)>0){
					foreach($exerciseRecords as $keys => $values){
						if($values['goal_title'] != 'Click_to_Edit'){
							$goal_results = DB::insert('wkout_share_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
								
							$goal_gendata_results = DB::insert('wkout_share_goal_gendata', array('wkout_share_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $shared_for, '1'))->execute();
						}
					}
				}
				return $insertId;
			}
			return true;
		}else if($type == 'workoutLog'){
			$assignDate = Helper_Common::get_default_date();
			$wkout_record = $this->getLoggedworkoutById($wkoutId,$userId);
			$exerciseSets = $this->getExerciseSets('wkoutlog',$wkoutId);
			if(is_array($wkout_record) && count($wkout_record)>0){
				$assignArr['created'] = $assignArr['modified'] = $datetime;
				$logged_results = DB::insert('wkout_log_gendata', array('wkout_id', 'from_wkout' ,'wkout_group' ,'wkout_title' ,'wkout_color' ,'wkout_log_order' ,'user_id' ,'site_id' ,'status_id' ,'access_id' ,'wkout_focus' ,'wkout_poa' ,'wkout_poa_time' ,'assigned_date' ,'created' ,'modified' ,'modified_by', 'wkout_status', 'note_wkout_intensity', 'note_wkout_remarks'))->values(array((isset($wkout_record['wkout_id']) ? $wkout_record['wkout_id'] : ''),'4', ($wkout_record['wkout_group'] ? $wkout_record['wkout_group'] : '') ,  ($wkout_record['wkout_title'] ? $wkout_record['wkout_title'] : '') , ($wkout_record['wkout_color'] ? $wkout_record['wkout_color'] : '') , '0' , $userId , $site_id, '1' ,$user_access ,($wkout_record['wkout_focus'] ? $wkout_record['wkout_focus'] : '') , ($wkout_record['wkout_poa'] ? $wkout_record['wkout_poa'] : '') , ($wkout_record['wkout_poa_time'] ? $wkout_record['wkout_poa_time'] : '') , $assignDate, $assignArr['created'], $assignArr['modified'],'0', (isset($wkout_record['wkout_status']) ? $wkout_record['wkout_status'] : '0') , $wkout_record['note_wkout_intensity'], $wkout_record['note_wkout_remarks']))->execute();
				if(isset($exerciseSets) && is_array($exerciseSets) && count($exerciseSets)>0){
					foreach($exerciseSets as $keys => $values){
						$goal_results = DB::insert('wkout_log_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
										
						$goal_gendata_results = DB::insert('wkout_log_goal_gendata', array('wkout_log_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($logged_results[0], $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $userId, '1'))->execute();
					}
				}
				return $logged_results[0];
			}
			return true;
		}else if($type == 'workoutAssignToNewWkout'){
			$records 	= $this->getAssignworkoutById($wkoutId,$userId);
			if(is_array($records) && count($records)>0){
				$exerciseRecords = $this->getExerciseSets('assigned',$wkoutId);
				$records['parent_folder_id'] = '0';
				$records['created_date']     = $datetime;
				$records['modified_date']	 = $datetime;
				$records['wkout_title']		 = $records['wkout_title'].'_new';
				$records['seq_order'] 		 = '1';
				$records['wkout_order'] 		 = '1';
				$insertId = $this->insertWorkoutDetailsByseqOrder($records);
				if(is_array($exerciseRecords) && count($exerciseRecords)>0){
					foreach($exerciseRecords as $keys => $values){
						if($values['goal_title'] != 'Click_to_Edit'){
							$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
								
							$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $userId, '1'))->execute();
						}
					}
				}
				return $insertId;
			}
			return true;
		}else if($type == 'workoutLogToNewWkout'){
			$records 	= $this->getLoggedworkoutById($wkoutId,$userId);
			if(is_array($records) && count($records)>0){
				$exerciseRecords = $this->getExerciseSets('wkoutlog',$wkoutId);
				$records['parent_folder_id'] = '0';
				$records['created_date']     = $datetime;
				$records['modified_date']	 = $datetime;
				$records['wkout_title']		 = $records['wkout_title'].'_new';
				$records['seq_order'] 		 = '1';
				$records['wkout_order'] 		 = '1';
				$insertId = $this->insertWorkoutDetailsByseqOrder($records);
				if(is_array($exerciseRecords) && count($exerciseRecords)>0){
					foreach($exerciseRecords as $keys => $values){
						if($values['goal_title'] != 'Click_to_Edit'){
							$goal_results = DB::insert('goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
								
							$goal_gendata_results = DB::insert('goal_gendata', array('wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($insertId, $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $userId, '1'))->execute();
						}
					}
				}
				return $insertId;
			}
			return true;
		}
		return true;
	}
	public function getMaxGoalorder($wkoutId, $userId){
		$sql 	= "SELECT MAX(goal_order) as goal_order from goal_gendata Where wkout_id ='".$wkoutId."' AND user_id ='".$userId."' AND status_id!='4'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]['goal_order']) ? $list[0]['goal_order'] : '0');
	}
	public function getCurrentGoalorder($wkoutId, $xrsetId , $userId){
		$sql 	= "SELECT goal_order from goal_gendata Where wkout_id ='".$wkoutId."' AND user_id ='".$userId."' AND goal_id='".$xrsetId."'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]['goal_order']) ? $list[0]['goal_order'] : '0');
	}
	public function getCurrentSampleGoalorder($wkoutId, $xrsetId , $userId){
		$sql 	= "SELECT goal_order from wkout_sample_goal_gendata Where wkout_sample_id ='".$wkoutId."' AND  goal_id='".$xrsetId."'"; //user_id ='".$userId."' AND
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]['goal_order']) ? $list[0]['goal_order'] : '0');
	}
	public function getCurrentAssignGoalorder($wkoutId, $xrsetId , $userId){
		$sql 	= "SELECT goal_order from wkout_assign_goal_gendata Where wkout_assign_id ='".$wkoutId."' AND user_id ='".$userId."' AND goal_id='".$xrsetId."'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]['goal_order']) ? $list[0]['goal_order'] : '0');
	}
	public function getCurrentLogGoalorder($wkoutId, $xrsetId , $userId){
		$sql 	= "SELECT goal_order from wkout_log_goal_gendata Where wkout_log_id ='".$wkoutId."' AND user_id ='".$userId."' AND goal_id='".$xrsetId."'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]['goal_order']) ? $list[0]['goal_order'] : '0');
	}
	public function updateAllGoalorder($type = 'up', $goalId , $workid){
		if($type == 'down'){
			$sql 	= "Update goal_gendata set goal_order = goal_order-1 Where wkout_id ='".$workid."' AND goal_id !='".$goalId."' AND status_id!=4";
		}else{
			$sql 	= "Update goal_gendata set goal_order = goal_order+1 Where wkout_id ='".$workid."' AND goal_id !='".$goalId."' AND status_id!=4";
		}
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function updateAllGoalorderByWkoutId($wkoutId , $currOrder){
		$sql 	= "Update goal_gendata set goal_order = goal_order-1 Where wkout_id ='".$wkoutId."' AND goal_order > '".$currOrder."' AND status_id!=4";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	
	public function updateAllSampleGoalorderByWkoutId($wkoutId , $currOrder){
		$sql 	= "Update wkout_sample_goal_gendata set goal_order = goal_order-1 Where wkout_sample_id ='".$wkoutId."' AND goal_order > '".$currOrder."' AND status_id!=4";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	
	public function updateAllAssignGoalorderByWkoutId($wkoutId , $currOrder){
		$sql 	= "Update wkout_assign_goal_gendata set goal_order = goal_order-1 Where wkout_assign_id ='".$wkoutId."' AND goal_order > '".$currOrder."' AND status_id!=4";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function updateAllLogGoalorderByWkoutId($wkoutId , $currOrder){
		$sql 	= "Update wkout_log_goal_gendata set goal_order = goal_order-1 Where wkout_log_id ='".$wkoutId."' AND goal_order > '".$currOrder."' AND status_id!=4";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function doDeleteForExerciseSetsById($type , $goalId , $workid, $userId){
		if($type == 'exerciseSet'){
			$currOrder = $this->getCurrentGoalorder($workid, $goalId , $userId);
			DB::update('goal_gendata')->set(array('status_id' => '4','goal_order'=>'0'))->where('goal_id', '=', $goalId)->execute();
			$this->updateAllGoalorderByWkoutId($workid ,$currOrder);
			return true;
		}elseif($type == 'sampleexerciseSet'){
			$currOrder = $this->getCurrentSampleGoalorder($workid, $goalId , $userId);
			DB::update('wkout_sample_goal_gendata')->set(array('status_id' => '4','goal_order'=>'0'))->where('goal_id', '=', $goalId)->execute();
			$this->updateAllSampleGoalorderByWkoutId($workid ,$currOrder);
			return true;
		}
		else if($type == 'workout'){
			$currentWkoutArr = $this->getCurrentSeqorderByWkoutId($workid);
			DB::update('wkout_gendata')->set(array('status_id' => '4'))->where('wkout_id', '=', $workid)->execute();
			DB::update('wkout_seq')->set(array('wkout_seq_status' => '1','seq_order'=>'0'))->where('wkout_id', '=', $workid)->execute();
			$this->updateWkoutSeqOrderOtherthanFirstSeq($currentWkoutArr['parent_folder_id'], $currentWkoutArr['id'], $currentWkoutArr['user_id']);
			return true;
		}else if($type == 'AssignexerciseSet'){
			$currOrder = $this->getCurrentAssignGoalorder($workid, $goalId , $userId);
			DB::update('wkout_assign_goal_gendata')->set(array('status_id' => '4','goal_order'=>'0'))->where('goal_id', '=', $goalId)->execute();
			$this->updateAllAssignGoalorderByWkoutId($workid ,$currOrder);
			return true;
		}else if($type == 'LogexerciseSet'){
			$currOrder = $this->getCurrentLogGoalorder($workid, $goalId , $userId);
			DB::update('wkout_log_goal_gendata')->set(array('status_id' => '4','goal_order'=>'0'))->where('goal_id', '=', $goalId)->execute();
			$this->updateAllLogGoalorderByWkoutId($workid ,$currOrder);
			return true;
		}else if($type == 'Assignworkout'){
			DB::update('wkout_assign_gendata')->set(array('status_id' => '4'))->where('wkout_assign_id', '=', $workid)->execute();
			return true;
		}else if($type == 'Sharedworkout'){
			DB::update('wkout_share_seq')->set(array('wkout_seq_status' => '1'))->where('wkout_share_id', '=', $workid)->execute();
			DB::update('wkout_share_gendata')->set(array('status_id' => '4'))->where('wkout_share_id', '=', $workid)->execute();
			return true;
		}
		
		return false;
	}
	public function doDeleteForGoalDataById($wkoutId){
		if(DB::update('goal_gendata')->set(array('status_id' => '4'))->where('wkout_id', '=', $wkoutId)->execute())
			return true;
		return false;
	}

	public function get_exerciseUnitData($request){
		$files = array();
		$sql = "SELECT * FROM unit_gendata AS xrgd
			LEFT JOIN unit_status AS g1 ON xrgd.status_id=g1.status_id
			LEFT JOIN unit_type AS g2 ON xrgd.type_id=g2.type_id
			LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id
			LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id
			LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id
			LEFT JOIN unit_mech AS g6 ON xrgd.mech_id=g6.mech_id
			LEFT JOIN unit_level AS g7 ON xrgd.level_id=g7.level_id
			LEFT JOIN unit_sport AS g8 ON xrgd.sport_id=g8.sport_id
			LEFT JOIN unit_force AS g9 ON xrgd.force_id=g9.force_id
			LEFT JOIN roles AS g10 ON xrgd.access_id=g10.id			
			WHERE xrgd.unit_id=".$request ;

	  	$data = DB::query(Database::SELECT,$sql)->execute();
		// Run the recursive function 
		if($data!=null && count($data)>0){
			foreach($data as $row){
				$url1 = substr ( $row['img_url'] ,strripos( $row['img_url'],"img_" ) );				
				$urlPrefix_thmb = 'assets/images/dynamic/exercise/thumb/';
				if( !empty($url1) && file_exists ( $urlPrefix_thmb.'thumb_'.$url1 ) ) {
					$image = URL::base().$urlPrefix_thmb.'thumb_'.$url1;
				} else {
					if(!empty($row['img_url'])){
						$url2 = str_replace('exercise/img/','',substr($row['img_url'],strripos($row['img_url'],"exercise/img/")));
						if( !empty($url2) && file_exists ( $urlPrefix_thmb.$url2 ) )
							$image = URL::base().$urlPrefix_thmb.$url2;
					}
				}
				if( empty($image) && !empty($row['img_url']) && file_exists ( $row['img_url'] ) ) {
					$image = URL::base().$row['img_url'];
				}
				if(empty($row['img_url'])){
					$image = '';
				}
				$recUrl = '?unit_id='.$row['unit_id'];
				
				// It is a file
				$file[] = array(
					"id" 		=> $row['unit_id'],
					"title"		=> $row['title'],
					"featimg"	=> $image,
					"status"	=> $row['status_id'],
					"access"	=> $row['access_id'],
					"user"		=> $row['created_by'],
					"xrtype"	=> $row['type_title'],
					"muscle"	=> $row['muscle_title'],
					"equip"		=> $row['equip_title'],
					"mechanics"	=> $row['mech_title'],
					"level"		=> $row['level_title'],
					"force"		=> $row['force_title'],
					"path"		=> $recUrl
				);
			}
		}
		return $file;
	}
	public function updateGoalOrder($new_goal_order, $workout_id, $goal_id, $user_id){
		$sql 	= "Update goal_gendata set goal_order = '".$new_goal_order."' Where goal_id ='".$goal_id."' AND user_id ='".$user_id."' AND wkout_id='".$workout_id."'";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function updateSampleGoalOrder($new_goal_order, $workout_id, $goal_id, $user_id){
		$sql 	= "Update wkout_sample_goal_gendata set goal_order = '".$new_goal_order."' Where goal_id ='".$goal_id."' AND wkout_sample_id='".$workout_id."'"; //AND user_id ='".$user_id."' 
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function updateAssignedGoalOrder($new_goal_order, $wkout_assign_id, $goal_id, $user_id){
		$sql 	= "Update wkout_assign_goal_gendata set goal_order = '".$new_goal_order."' Where goal_id ='".$goal_id."' AND user_id ='".$user_id."' AND wkout_assign_id='".$wkout_assign_id."'";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function updateLogGoalOrder($new_goal_order, $wkout_log_id, $goal_id, $user_id){
		$sql 	= "Update wkout_log_goal_gendata set goal_order = '".$new_goal_order."' Where goal_id ='".$goal_id."' AND user_id ='".$user_id."' AND wkout_log_id='".$wkout_log_id."'";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function updateWkoutDetails($updateArray, $workout_id){
		$updateArray['date_modified'] = Helper_Common::get_default_datetime();
		$query = DB::update('wkout_gendata')->set($updateArray)->where('wkout_id', '=', $workout_id)->execute();
		return true;
	}
	public function updateSampleWkoutDetails($updateArray, $workout_id){
		$updateArray['date_modified'] = Helper_Common::get_default_datetime();
		$query = DB::update('wkout_sample_gendata')->set($updateArray)->where('wkout_sample_id', '=', $workout_id)->execute();
		return true;
	}
	public function updateAssignedWkoutDetails($updateArray, $wkout_assign_id){
		$updateArray['modified'] = Helper_Common::get_default_datetime();
		$query = DB::update('wkout_assign_gendata')->set($updateArray)->where('wkout_assign_id', '=', $wkout_assign_id)->execute();
		return true;
	}
	public function updateLoggedWkoutDetails($updateArray, $wkout_log_id){
		$updateArray['modified'] = Helper_Common::get_default_datetime();
		$query = DB::update('wkout_log_gendata')->set($updateArray)->where('wkout_log_id', '=', $wkout_log_id)->execute();
		if(isset($updateArray['status_id']) && $updateArray['status_id'] == 4){
			$sql 	= "Select wlg.wkout_id from wkout_log_gendata as wlg join wkout_assign_gendata as wsg on (wsg.wkout_assign_id = wlg.associated_assign_id) where wlg.wkout_log_id='".$wkout_log_id."' AND wlg.status_id ='4' AND wlg.from_wkout ='3' AND wlg.wkout_id = wlg.associated_assign_id AND wlg.wkout_id !='0' AND wsg.associated_log_id = '".$wkout_log_id."'";
			$query 	= DB::query(Database::SELECT,$sql);
			$list 	= $query->execute()->as_array();
			if(isset($list[0]['wkout_id'])){
				$sql = "UPDATE wkout_assign_gendata SET journal_status =  '0',marked_status='0' WHERE wkout_assign_id = '".$list[0]['wkout_id']."' AND associated_log_id='".$wkout_log_id."'";
				DB::query(Database::UPDATE,$sql)->execute();
			}
		}
		return true;
	}
	public function updateLoggedWkoutXRDetails($updateArray, $wkout_log_id){
		$query = DB::update('wkout_log_goal_gendata')->set($updateArray)->where('wkout_log_id', '=', $wkout_log_id)->where('status_id', '=','1')->execute();
		if(isset($updateArray['set_status'])){
			$sql 	= "Select wlg.wkout_id from wkout_log_gendata as wlg join wkout_assign_gendata as wsg on (wsg.wkout_assign_id = wlg.associated_assign_id) where wlg.wkout_log_id='".$wkout_log_id."' AND wlg.status_id ='1' AND wlg.from_wkout ='3' AND wlg.wkout_id = wlg.associated_assign_id AND wlg.wkout_id !='0' AND wsg.associated_log_id= '".$wkout_log_id."'";
			$query 	= DB::query(Database::SELECT,$sql);
			$list 	= $query->execute()->as_array();
			if(isset($list[0]['wkout_id'])){
				$sql = "UPDATE wkout_assign_gendata SET journal_status =  '".$updateArray['set_status']."' WHERE wkout_assign_id = '".$list[0]['wkout_id']."' AND associated_log_id = '".$wkout_log_id."'";
				DB::query(Database::UPDATE,$sql)->execute();
			}
		}
		return true;
	}
	public function CopyDeleteExerciseRecById($type, $xrRecId, $method='', $valArr=array()){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$datetime = Helper_Common::get_default_datetime();
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		$imagelibrary = ORM::factory('imagelibrary');
		$data['success'] = false; $feat_imgid = '0';
		if($type == 'exerciseRecord' && ($method=='copy' || $method=='new_create' || $method=='share')){
			$getmusothdata = $this->getMusOthByUnitId($xrRecId);
			$getequipothdata = $this->getEquipOthByUnitId($xrRecId);
			$getseqdata = $this->getSequencesByUnitId($xrRecId);
			$gettaglist = $this->getUnitTagsById($xrRecId, 1);
			$sql = "SELECT * FROM unit_gendata WHERE unit_id=".$xrRecId ;
			$XrRecdata = DB::query(Database::SELECT,$sql)->execute()->as_array();
			if(isset($XrRecdata[0]) && count($XrRecdata) > 0){
				$values = $XrRecdata[0];
				$sqlimg = "SELECT * FROM img WHERE img_id=".$values['feat_img'];
				$Xrimg = DB::query(Database::SELECT,$sqlimg)->execute()->as_array();
				if(isset($Xrimg[0]) && !empty($Xrimg[0])){
					$Xrimg = $Xrimg[0];
					$feat_imgid = $imagelibrary->InsertImg($Xrimg['img_title'], $Xrimg['img_url'], 1, 5, 0, 1, (isset($valArr['shared_for']) && !empty($valArr['shared_for']) ? $valArr['shared_for'] : ''));
				}
				if($method == 'share'){
					if(isset($valArr) && !empty($valArr['shared_for']) && !empty($valArr['site_id'])){
						$shared_useraccess = $this->getUserRoleById($valArr['shared_for']);
						$unit_results = DB::insert('unit_gendata', array('title', 'default_status', 'date_created', 'date_modified', 'created_by', 'access_id', 'site_id', 'urlkey', 'feat_img', 'feat_vid', 'type_id', 'musprim_id', 'equip_id', 'mech_id', 'level_id', 'sport_id', 'force_id', 'descbr', 'descfull', 'hits'))->values(array($this->escapeStr($values['title']), 3, $datetime, $datetime, $valArr['shared_for'], $shared_useraccess, $valArr['site_id'], $values['urlkey'], $feat_imgid, $values['feat_vid'], $values['type_id'], $values['musprim_id'], $values['equip_id'], $values['mech_id'], $values['level_id'], $values['sport_id'], $values['force_id'], $this->escapeStr($values['descbr']), $this->escapeStr($values['descfull']), $values['hits']))->execute();
						$newunitid = $unit_results[0] ? $unit_results[0] : '';
						if(!empty($newunitid)){
							$shareunit_results = DB::insert('unit_gendata_shared', array('unit_id', 'unit_title', 'from_default_status', 'site_id', 'shared_for', 'shared_by', 'shared_msg', 'access_id', 'created_date', 'modified_date', 'created_by', 'modified_by'))->values(array($newunitid, $this->escapeStr($values['title']), $values['default_status'], $valArr['site_id'], $valArr['shared_for'], $valArr['shared_by'], $valArr['shared_msg'], $shared_useraccess, $datetime, $datetime, $valArr['shared_for'], $valArr['shared_for']))->execute();
						}
						$data['shared_xrid'] = $newunitid;
					}else{
						$data['success'] = false;
						return $data;
					}
				}else{
					$unit_results = DB::insert('unit_gendata', array('title', 'date_created', 'date_modified', 'created_by', 'access_id', 'site_id', 'urlkey', 'feat_img', 'feat_vid', 'type_id', 'musprim_id', 'equip_id', 'mech_id', 'level_id', 'sport_id', 'force_id', 'descbr', 'descfull', 'hits'))->values(array($this->escapeStr($values['title'].'_copy'), $datetime, $datetime, $userid, $useraccess, $site_id, $values['urlkey'], $feat_imgid, $values['feat_vid'], $values['type_id'], $values['musprim_id'], $values['equip_id'], $values['mech_id'], $values['level_id'], $values['sport_id'], $values['force_id'], $this->escapeStr($values['descbr']), $this->escapeStr($values['descfull']), $values['hits']))->execute();
					$newunitid = $unit_results[0] ? $unit_results[0] : '';
				}
			}else{
				$data['success'] = false;
				return $data;
			}
			if(!empty($newunitid)){
				if($method=='copy'){
					$this->insertActivityFeed(5, 22, $xrRecId);
				}elseif($method=='new_create'){
					$this->insertActivityFeed(5, 1, $newunitid);
				}else{
				}
				$unitgen_data = $this->getExerciseRecordById($newunitid);
				if(!empty($unitgen_data) && count($unitgen_data) > 0){
					$data['xrid'] = $newunitid;
					$data['title'] = $unitgen_data[0]['title'];
					$data['img_url'] = $unitgen_data[0]['img_url'];
				}
				if(!empty($getmusothdata) && count($getmusothdata)>0){
					foreach($getmusothdata as $muscvalues){
						$unit_results = DB::insert('unit_musoth', array('unit_id', 'musoth_id'))->values(array($newunitid, $muscvalues['musoth_id']))->execute();
					}
				}else{ }
				if(!empty($getequipothdata) && count($getequipothdata)>0){
					foreach($getequipothdata as $equipvalues){
						$unit_results = DB::insert('unit_equipoth', array('unit_id', 'equipoth_id'))->values(array($newunitid, $equipvalues['equipoth_id']))->execute();
					}
				}else{ }
				if(!empty($getseqdata) && count($getseqdata)>0){
					foreach($getseqdata as $seqvalues){
						$unit_results = DB::insert('unit_seq', array('unit_id', 'seq_img', 'seq_desc', 'seq_order'))->values(array($newunitid, $seqvalues['seq_img'], $seqvalues['seq_desc'], $seqvalues['seq_order']))->execute();
					}
				}else{ }
				if(!empty($gettaglist) && count($gettaglist)>0){
					foreach($gettaglist as $tagvalues){
						$unit_results = DB::insert('unit_tag', array('tag_id', 'unit_id', 'created_by'))->values(array($tagvalues['tag_id'], $newunitid, (($method == 'share') ? $valArr['shared_for'] : $userid)))->execute();
					}
				}else{ }
			}
			$data['success'] = true;
			return $data;
		}elseif($type == 'exerciseRecord' && $method=='delete'){
			$sql = "UPDATE unit_gendata SET status_id = 4, date_modified='".$datetime."' WHERE unit_id = ".$xrRecId."";
	  		$recdel = DB::query(Database::UPDATE,$sql)->execute();
	  		if($recdel){
				$this->insertActivityFeed(5, 2, $xrRecId);
				$data['success'] = true;
				return $data;
			}
			$data['success'] = false;
			return $data;
		}
	}
	public function addToWkoutAssignCustom($wkout_record,$userId){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$user_access = $this->getUserRole();
		$assign_results = DB::insert('wkout_assign_gendata', array('wkout_id', 'from_wkout','wkout_group' ,'wkout_title' ,'wkout_color' ,'wkout_assign_order' ,'user_id', 'status_id' ,'access_id', 'site_id' ,'wkout_focus' ,'wkout_poa' ,'wkout_poa_time' ,'assigned_by' ,'assigned_for' ,'assigned_date' ,'created' ,'modified' ,'modified_by'))->values(array((isset($wkout_record['wkout_id']) ? $wkout_record['wkout_id'] : '0'), (isset($wkout_record['from_wkout']) ? $wkout_record['from_wkout'] : 0), (isset($wkout_record['wkout_group']) ? $wkout_record['wkout_group'] : '0') ,  (isset($wkout_record['wkout_title']) ? $wkout_record['wkout_title'] : '') , (isset($wkout_record['wkout_color']) ? $wkout_record['wkout_color'] : '') , '0' , $wkout_record['assigned_by'] , '1' ,$user_access , $site_id,(isset($wkout_record['wkout_focus']) ? $wkout_record['wkout_focus'] : '0') , (isset($wkout_record['wkout_poa']) ? $wkout_record['wkout_poa'] : '0') , (isset($wkout_record['wkout_poa_time']) ? $wkout_record['wkout_poa_time'] : '0') ,$wkout_record['assigned_by'], $wkout_record['assigned_for'], $wkout_record['assigned_date'], $wkout_record['created'], $wkout_record['modified'], $wkout_record['modified_by']))->execute();
		return $assign_results[0];
	}
	public function addToWkoutAssign($assignArr,$userId){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$wkout_record = array();
		$user_access = $this->getUserRole();
		if(isset($assignArr['wkout_id']) && !empty($assignArr['wkout_id'])){
			$wkout_record = $this->getworkoutById($assignArr['assigned_by'],$assignArr['wkout_id']);
			$exerciseSets = $this->getExerciseSets('wkout', $assignArr['wkout_id']);
		}elseif(isset($assignArr['wkout_sample_id']) && !empty($assignArr['wkout_sample_id'])){
			$wkout_record = $this->getSampleworkoutById('0',$assignArr['wkout_sample_id']);
			$assignArr['wkout_id'] = $assignArr['wkout_sample_id'];
			$exerciseSets = $this->getExerciseSets('sample', $assignArr['wkout_id']);
		}elseif(isset($assignArr['wkout_share_id']) && !empty($assignArr['wkout_share_id'])){
			$wkout_record = $this->getShareworkoutById($assignArr['assigned_by'],$assignArr['wkout_share_id']);
			$assignArr['wkout_id'] = $assignArr['wkout_share_id'];
			$exerciseSets = $this->getExerciseSets('shared', $assignArr['wkout_id']);
		}elseif(isset($assignArr['wkout_log_id']) && !empty($assignArr['wkout_log_id'])){
			$wkout_record = $this->getLoggedworkoutById($assignArr['wkout_log_id'],$assignArr['assigned_by']);
			$assignArr['wkout_id'] = $assignArr['wkout_log_id'];
			$exerciseSets = $this->getExerciseSets('wkoutlog', $assignArr['wkout_id']);
		}elseif(isset($assignArr['wkout_assign_id']) && !empty($assignArr['wkout_assign_id'])){
			$wkout_record =$this->getAssignworkoutById($assignArr['wkout_assign_id'],$assignArr['assigned_by']);
			$assignArr['wkout_id'] = $assignArr['wkout_assign_id'];
			$exerciseSets = $this->getExerciseSets('assigned', $assignArr['wkout_id']);
		}
		if(!empty($wkout_record)){
			$assign_results = DB::insert('wkout_assign_gendata', array('wkout_id', 'from_wkout','wkout_group' ,'wkout_title' ,'wkout_color' ,'wkout_assign_order' ,'user_id' ,'site_id' ,'status_id' ,'access_id' ,'wkout_focus' ,'wkout_poa' ,'wkout_poa_time' ,'assigned_by' ,'assigned_for' ,'assigned_date' ,'created' ,'modified' ,'modified_by'))->values(array((isset($wkout_record['wkout_id']) ? $wkout_record['wkout_id'] : '0'), (isset($assignArr['from_wkout']) ? $assignArr['from_wkout'] : 0), (isset($wkout_record['wkout_group']) ? $wkout_record['wkout_group'] : '0') ,  (isset($wkout_record['wkout_title']) ? $wkout_record['wkout_title'] : '') , (isset($wkout_record['wkout_color']) ? $wkout_record['wkout_color'] : '') , '0' , $assignArr['assigned_by'] , $site_id ,'1' ,$user_access ,(isset($wkout_record['wkout_focus']) ? $wkout_record['wkout_focus'] : '0') , (isset($wkout_record['wkout_poa']) ? $wkout_record['wkout_poa'] : '0') , (isset($wkout_record['wkout_poa_time']) ? $wkout_record['wkout_poa_time'] : '0') ,$assignArr['assigned_by'], $assignArr['assigned_for'], $assignArr['assigned_date'], $assignArr['created'], $assignArr['modified'], $assignArr['modified_by']))->execute();
			if(isset($exerciseSets) && is_array($exerciseSets) && count($exerciseSets)>0){
				foreach($exerciseSets as $keys => $values){
					$goal_order = $keys +1;
					$goal_results = DB::insert('wkout_assign_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
									
					$goal_gendata_results = DB::insert('wkout_assign_goal_gendata', array('wkout_assign_id','wkout_id', 'goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id'))->values(array($assign_results[0], $assignArr['wkout_id'] , $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $goal_order, $userId, '1'))->execute();
				}
			}
			return $assign_results[0];
		}
		return false;
	}
	public function addToReassignWkoutAssign($assignArr){
		$sql = "UPDATE wkout_assign_gendata SET assigned_date = '".$assignArr['assigned_date']."', modified_by = '".$assignArr['modified_by']."', modified = '".$assignArr['modified']."'   WHERE wkout_assign_id = '".$assignArr['wkout_assign_id']."'";
	  	return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function getExerciseSets($type, $wkoutId){
		if($type =='wkout'){
			$sql = "SELECT * FROM goal_gendata AS gset JOIN goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gset.wkout_id=".$wkoutId." AND gset.status_id=1 ORDER BY gset.goal_order";
		}elseif($type == 'sample'){
			$sql = "SELECT * FROM wkout_sample_goal_gendata AS gset JOIN wkout_sample_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gset.wkout_sample_id=".$wkoutId." AND gset.status_id=1 ORDER BY gset.goal_order";
		}elseif($type == 'shared'){
			$sql = "SELECT * FROM wkout_share_goal_gendata AS gset JOIN wkout_share_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gset.wkout_share_id=".$wkoutId." AND gset.status_id=1 ORDER BY gset.goal_order";
		}elseif($type == 'wkoutlog'){
			$sql = "SELECT * FROM wkout_log_goal_gendata AS gset JOIN wkout_log_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gset.wkout_log_id=".$wkoutId." AND gset.status_id=1 ORDER BY gset.goal_order";
		}else{
			$sql = "SELECT * FROM wkout_assign_goal_gendata AS gset JOIN wkout_assign_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gset.wkout_assign_id=".$wkoutId." AND gset.status_id=1 ORDER BY gset.goal_order";
		}
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getExerciseSetDetailsByAssignWkout($wkout_assign_id, $goal_id){
		$sql = "SELECT gd.*, gset.*, setvars.*, xrgd.unit_id, xrgd.title, xrgd.status_id, g10.id as access_id, g10.name as access_title, xrgd.feat_img, xrgd.feat_vid, xrgd.type_id, g2.type_title, xrgd.musprim_id, g3.muscle_title, g4.img_url, xrgd.equip_id, g5.equip_title, xrgd.mech_id, g6.mech_title, xrgd.level_id, g7.level_title, xrgd.sport_id, g8.sport_title, xrgd.force_id, g9.force_title, xrgd.descbr, xrgd.descfull FROM wkout_assign_gendata AS gd JOIN wkout_assign_goal_gendata AS gset ON gset.wkout_assign_id =gd.wkout_assign_id JOIN wkout_assign_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS xrgd on xrgd.unit_id = gset.goal_unit_id LEFT JOIN unit_status AS g1 ON xrgd.status_id=g1.status_id LEFT JOIN unit_type AS g2 ON xrgd.type_id=g2.type_id LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id LEFT JOIN unit_mech AS g6 ON xrgd.mech_id=g6.mech_id LEFT JOIN unit_level AS g7 ON xrgd.level_id=g7.level_id LEFT JOIN unit_sport AS g8 ON xrgd.sport_id=g8.sport_id LEFT JOIN unit_force AS g9 ON xrgd.force_id=g9.force_id LEFT JOIN roles AS g10 ON xrgd.access_id=g10.id WHERE gd.wkout_assign_id = '" . $wkout_assign_id . "' AND gset.goal_id in (" . $goal_id . ") AND gset.status_id=1 ORDER BY gset.goal_order";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	
	public function getExerciseSetDetailsByWkoutLog($wkout_log_id, $goal_id){
		$sql = "SELECT gd.*, gset.*, setvars.*, wc.color_title, xrgd.unit_id, xrgd.title, xrgd.status_id, g10.id as access_id, g10.name as access_title, xrgd.feat_img, xrgd.feat_vid, xrgd.type_id, g2.type_title, xrgd.musprim_id, g3.muscle_title, g4.img_url, xrgd.equip_id, g5.equip_title, xrgd.mech_id, g6.mech_title, xrgd.level_id, g7.level_title, xrgd.sport_id, g8.sport_title, xrgd.force_id, g9.force_title, xrgd.descbr, xrgd.descfull FROM wkout_log_gendata AS gd JOIN wkout_log_goal_gendata AS gset ON gset.wkout_log_id =gd.wkout_log_id JOIN wkout_log_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS xrgd on xrgd.unit_id = gset.goal_unit_id LEFT JOIN unit_status AS g1 ON xrgd.status_id=g1.status_id LEFT JOIN unit_type AS g2 ON xrgd.type_id=g2.type_id LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id LEFT JOIN unit_mech AS g6 ON xrgd.mech_id=g6.mech_id LEFT JOIN unit_level AS g7 ON xrgd.level_id=g7.level_id LEFT JOIN unit_sport AS g8 ON xrgd.sport_id=g8.sport_id LEFT JOIN unit_force AS g9 ON xrgd.force_id=g9.force_id LEFT JOIN roles AS g10 ON xrgd.access_id=g10.id LEFT JOIN wkout_color AS wc ON gd.wkout_color=wc.color_id WHERE gd.wkout_log_id = '" . $wkout_log_id . "' AND gset.goal_id in (" . $goal_id . ") AND gset.status_id=1 ORDER BY gset.goal_order";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	
	public function getExerciseSetDetailsByShareWkout($wkout_share_id, $goal_id){
		$sql = "SELECT gd.*, gset.*, setvars.*, xrgd.unit_id, xrgd.title, xrgd.status_id, g10.id as access_id, g10.name as access_title, xrgd.feat_img, xrgd.feat_vid, xrgd.type_id, g2.type_title, xrgd.musprim_id, g3.muscle_title, g4.img_url, xrgd.equip_id, g5.equip_title, xrgd.mech_id, g6.mech_title, xrgd.level_id, g7.level_title, xrgd.sport_id, g8.sport_title, xrgd.force_id, g9.force_title, xrgd.descbr, xrgd.descfull FROM wkout_share_gendata AS gd JOIN wkout_share_goal_gendata AS gset ON gset.wkout_share_id =gd.wkout_share_id JOIN wkout_share_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS xrgd on xrgd.unit_id = gset.goal_unit_id LEFT JOIN unit_status AS g1 ON xrgd.status_id=g1.status_id LEFT JOIN unit_type AS g2 ON xrgd.type_id=g2.type_id LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id LEFT JOIN unit_mech AS g6 ON xrgd.mech_id=g6.mech_id LEFT JOIN unit_level AS g7 ON xrgd.level_id=g7.level_id LEFT JOIN unit_sport AS g8 ON xrgd.sport_id=g8.sport_id LEFT JOIN unit_force AS g9 ON xrgd.force_id=g9.force_id LEFT JOIN roles AS g10 ON xrgd.access_id=g10.id WHERE gd.wkout_share_id = '" . $wkout_share_id . "' AND gset.goal_id in (" . $goal_id . ") AND gset.status_id=1 ORDER BY gset.goal_order";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	
	public function getAssignworkoutById($wkout_assign_id, $id_user = 0){
		$user_roles = Helper_Common::get_user_roles();
		// AND wkgd.access_id in ('.$user_roles.')
		$sql 	= "SELECT wkgd.*,g1.color_id, g1.color_title FROM wkout_assign_gendata AS wkgd LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id  WHERE wkgd.status_id=1 ".(!empty($id_user) ? " AND wkgd.user_id='".$id_user."'" : '' ).(!empty($wkout_assign_id) ? " AND wkgd.wkout_assign_id ='".$wkout_assign_id."'" : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return isset($list[0]) ? $list[0] : $list;
	}
	public function getLoggedworkoutById($wkout_log_id, $id_user = 0){
		$user_roles = Helper_Common::get_user_roles();
		// AND wkgd.access_id in ('.$user_roles.')
		$sql 	= "SELECT wkgd.*,g1.color_id, g1.color_title FROM wkout_log_gendata AS wkgd LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id  WHERE wkgd.status_id=1 ".(!empty($id_user) ? " AND wkgd.user_id='".$id_user."'" : '' ).(!empty($wkout_log_id) ? " AND wkgd.wkout_log_id ='".$wkout_log_id."'" : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return isset($list[0]) ? $list[0] : $list;
	}
	public function updateMarkedStatus($assignId, $Markstatus, $wkout_log_id = '0'){
		$sql = "UPDATE wkout_assign_gendata SET marked_status =  '".$Markstatus."' WHERE wkout_assign_id = '".$assignId."'";
		return DB::query(Database::UPDATE,$sql)->execute();
	}
	public function createWkoutLogByassignId($workassignid, $assignedArray, $statusFlag = false){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$wkout_assign_record = array();
		$user_access = $this->getUserRole();
		if(isset($workassignid) && !empty($workassignid)){
			$wkout_assign_record = $this->getAssignworkoutById($workassignid);
		}
		$logged_results = DB::insert('wkout_log_gendata', array('wkout_id' ,'from_wkout','wkout_group' ,'wkout_title' ,'wkout_color' ,'wkout_log_order' ,'user_id' ,'site_id' ,'status_id' ,'access_id' ,'wkout_focus' ,'wkout_poa' ,'wkout_poa_time' ,'assigned_date' ,'created' ,'modified' ,'modified_by', 'wkout_status', 'note_wkout_intensity', 'note_wkout_remarks', 'associated_assign_id'))->values(array($workassignid, ($assignedArray['from_wkout'] ? $assignedArray['from_wkout'] : '0'),($wkout_assign_record['wkout_group'] ? $wkout_assign_record['wkout_group'] : '') ,  ($wkout_assign_record['wkout_title'] ? $wkout_assign_record['wkout_title'] : '') , ($wkout_assign_record['wkout_color'] ? $wkout_assign_record['wkout_color'] : '') , '0' , ($wkout_assign_record['user_id'] ? $wkout_assign_record['user_id'] : '') , $site_id,'1' ,$user_access ,($wkout_assign_record['wkout_focus'] ? $wkout_assign_record['wkout_focus'] : '') , ($wkout_assign_record['wkout_poa'] ? $wkout_assign_record['wkout_poa'] : '') , ($wkout_assign_record['wkout_poa_time'] ? $wkout_assign_record['wkout_poa_time'] : '') , (isset($assignedArray['assigned_date']) ? $assignedArray['assigned_date'] : $wkout_assign_record['assigned_date']), $assignedArray['created'], $assignedArray['modified'],'0', (isset($assignedArray['wkout_status']) ? $assignedArray['wkout_status'] : '0') , (isset($assignedArray['note_wkout_intensity']) ? $assignedArray['note_wkout_intensity'] : '0'), (isset($assignedArray['note_wkout_remarks']) ? $assignedArray['note_wkout_remarks'] : ''), (isset($assignedArray['associated_assign_id']) ? $assignedArray['associated_assign_id'] : '0')))->execute();
		return $logged_results[0];
	}
	
	public function createWkoutLogByCustom($wkout_record, $userId){
		$user_access = $this->getUserRole();
		if(is_array($wkout_record) && !empty($wkout_record)){
			$logged_results = DB::insert('wkout_log_gendata', array('wkout_id', 'from_wkout', 'wkout_group' ,'wkout_title' ,'wkout_color' ,'wkout_log_order' ,'user_id' ,'status_id' ,'access_id', 'site_id', 'wkout_focus' ,'wkout_poa' ,'wkout_poa_time' ,'assigned_date' ,'created' ,'modified' ,'modified_by', 'wkout_status', 'note_wkout_intensity', 'note_wkout_remarks'))->values(array((isset($wkout_record['wkout_id']) ? $wkout_record['wkout_id'] : '0'), (isset($wkout_record['from_wkout']) ? $wkout_record['from_wkout'] : '0'), (isset($wkout_record['wkout_group']) ? $wkout_record['wkout_group'] : '') ,  (isset($wkout_record['wkout_title']) ? $wkout_record['wkout_title'] : '') , (isset($wkout_record['wkout_color']) ? $wkout_record['wkout_color'] : '') , '0' , $userId , '1' ,$user_access , (isset($wkout_record['site_id']) ? $wkout_record['site_id'] : '1'), (isset($wkout_record['wkout_focus']) ? $wkout_record['wkout_focus'] : '') , (isset($wkout_record['wkout_poa']) ? $wkout_record['wkout_poa'] : '') , (isset($wkout_record['wkout_poa_time']) ? $wkout_record['wkout_poa_time'] : ''), (isset($wkout_record['assigned_date']) ? $wkout_record['assigned_date'] : ''), $wkout_record['created'], $wkout_record['modified'], $wkout_record['modified_by'], (isset($wkout_record['wkout_status']) ? $wkout_record['wkout_status'] : '0'), (isset($wkout_record['note_wkout_intensity']) ? $wkout_record['note_wkout_intensity'] : ''), (isset($wkout_record['note_wkout_remarks']) ? $wkout_record['note_wkout_remarks'] : '')))->execute();
			return $logged_results[0];
		}
		return false;
	}
	
	public function createWkoutLogBywkoutId($type, $wkoutid, $loggedArray , $userId){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$wkout_record = array();
		$user_access = $this->getUserRole();
		if(isset($wkoutid) && !empty($wkoutid)){
			if($type == 'workout'){
				$wkout_record = $this->getworkoutById($userId,$wkoutid);
				$exerciseSets = $this->getExerciseSets('wkout',$wkoutid);
			}elseif($type == 'assigned'){
				$wkout_record = $this->getAssignworkoutById($wkoutid, $userId);
				$wkout_record['from_wkout'] = '3';
				$exerciseSets = $this->getExerciseSets('assigned',$wkoutid);
				if(isset($wkout_record['wkout_status']) && empty($wkout_record['associated_log_id']) && isset($loggedArray['associated_assign_id']) && !empty($loggedArray['associated_assign_id']))
					$this->updateMarkedStatus($wkoutid,$loggedArray['wkout_status']);
			}elseif($type == 'wkoutlog'){
				$wkout_record = $this->getLoggedworkoutById($wkoutid, $userId);
				$wkout_record['associated_assign_id'] = '0';
				$wkout_record['from_wkout'] = '4';
				$exerciseSets = $this->getExerciseSets('wkoutlog',$wkoutid);
			}elseif($type == 'shared'){
				$wkout_record = $this->getShareworkoutById($userId, $wkoutid);
				$wkout_record['from_wkout'] = '1';
				$exerciseSets = $this->getExerciseSets('shared',$wkoutid);
			}
		}
		if(is_array($wkout_record) && !empty($wkout_record)){
			$logged_results = DB::insert('wkout_log_gendata', array('wkout_id' ,'from_wkout','wkout_group' ,'wkout_title' ,'wkout_color' ,'wkout_log_order' ,'user_id', 'site_id' ,'status_id' ,'access_id' ,'wkout_focus' ,'wkout_poa' ,'wkout_poa_time', 'assigned_date' ,'created' ,'modified' ,'modified_by', 'wkout_status', 'note_wkout_intensity', 'note_wkout_remarks', 'associated_assign_id'))->values(array($wkoutid,($wkout_record['from_wkout'] ? $wkout_record['from_wkout'] : '0'), ($wkout_record['wkout_group'] ? $wkout_record['wkout_group'] : '') ,  ($wkout_record['wkout_title'] ? $wkout_record['wkout_title'] : '') , ($wkout_record['wkout_color'] ? $wkout_record['wkout_color'] : '') , '0' , $userId , $site_id , '1',$user_access ,($wkout_record['wkout_focus'] ? $wkout_record['wkout_focus'] : '') , ($wkout_record['wkout_poa'] ? $wkout_record['wkout_poa'] : '') , ($wkout_record['wkout_poa_time'] ? $wkout_record['wkout_poa_time'] : '') , (isset($loggedArray['assigned_date']) ? $loggedArray['assigned_date'] : ''), $loggedArray['created'], $loggedArray['modified'], $loggedArray['modified_by'], (isset($loggedArray['wkout_status']) ? $loggedArray['wkout_status'] : '0'), (isset($loggedArray['note_wkout_intensity']) ? $loggedArray['note_wkout_intensity'] : ''), (isset($loggedArray['note_wkout_remarks']) ? $loggedArray['note_wkout_remarks'] : ''), (isset($loggedArray['associated_assign_id']) && empty($wkout_record['associated_log_id']) ? $loggedArray['associated_assign_id'] : '0')))->execute();
			if(isset($exerciseSets) && is_array($exerciseSets) && count($exerciseSets)>0){
				foreach($exerciseSets as $keys => $values){
					$goal_results = DB::insert('wkout_log_goal_vars', array('goal_iso', 'goal_alt', 'primary_time', 'primary_dist', 'primary_reps', 'goal_time_hh', 'goal_time_mm', 'goal_time_ss', 'goal_dist', 'goal_dist_id', 'goal_reps', 'goal_resist', 'goal_resist_id', 'goal_rate', 'goal_rate_id', 'goal_angle', 'goal_angle_id', 'goal_int_id', 'goal_rest_mm', 'goal_rest_ss', 'goal_remarks'))->values(array($values['goal_iso'], $values['goal_alt'], $values['primary_time'], $values['primary_dist'], $values['primary_reps'], $values['goal_time_hh'], $values['goal_time_mm'], $values['goal_time_ss'], $values['goal_dist'], $values['goal_dist_id'], $values['goal_reps'], $values['goal_resist'], $values['goal_resist_id'], $values['goal_rate'], $values['goal_rate_id'], $values['goal_angle'], $values['goal_angle_id'], $values['goal_int_id'], $values['goal_rest_mm'], $values['goal_rest_ss'], $values['goal_remarks']))->execute();
									
					$goal_gendata_results = DB::insert('wkout_log_goal_gendata', array('wkout_log_id','goal_unit_id', 'goal_group', 'goal_title', 'goal_title_self', 'goal_order', 'user_id', 'status_id','set_status'))->values(array($logged_results[0], $values['goal_unit_id'], $values['goal_group'], $values['goal_title'], $values['goal_title_self'], $values['goal_order'], $userId, '1',(isset($loggedArray['wkout_status']) ? $loggedArray['wkout_status'] : '0')))->execute();
				}			
			}
			return $logged_results[0];
		}
		return false;
	}
	public function getExerciseSetByAssignworkout($wkout_assign_id , $editFlag){
		if($editFlag)
			$sql = "SELECT gd.*, gset.*,setvars.*,ugd.*,img.* FROM wkout_assign_gendata as gd JOIN wkout_assign_goal_gendata AS gset on gd.wkout_assign_id = gset.wkout_assign_id JOIN wkout_assign_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gd.wkout_assign_id=".$wkout_assign_id." AND gset.status_id=1 ORDER BY gset.goal_order";
		else
			$sql = "SELECT gd.*, gset.*,setvars.*,ugd.*,img.* FROM wkout_assign_gendata as gd JOIN goal_gendata AS gset on gd.wkout_id = gset.wkout_id JOIN goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gd.wkout_assign_id=".$wkout_assign_id." AND gset.status_id=1 AND gd.modified_by ='' ORDER BY gset.goal_order";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getExerciseSetByLoggworkout($wkout_log_id , $editFlag, $wkoutAssignModifyFlag = null){
		if($editFlag)
			$sql = "SELECT gd.*, gset.*,setvars.*,ugd.*,img.* FROM wkout_log_gendata as gd JOIN wkout_log_goal_gendata AS gset on gd.wkout_log_id = gset.wkout_log_id JOIN wkout_log_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gd.wkout_log_id=".$wkout_log_id." AND gset.status_id=1 ORDER BY gset.goal_order";
		else{
			if($wkoutAssignModifyFlag == ''){
				$sql = "SELECT gd.*, gset.*,setvars.*,ugd.*,img.* FROM wkout_log_gendata as gd JOIN goal_gendata AS gset on gd.wkout_id = gset.wkout_id JOIN goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gd.wkout_assign_id='' AND gd.wkout_log_id=".$wkout_log_id." AND gset.status_id=1 ORDER BY gset.goal_order";
			}else{
				$sql = "SELECT gd.*, gset.*,setvars.*,ugd.*,img.* FROM wkout_log_gendata as gd JOIN wkout_assign_goal_gendata AS gset on gd.wkout_assign_id = gset.wkout_assign_id JOIN wkout_assign_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gd.wkout_assign_id!='' AND gd.wkout_log_id=".$wkout_log_id." AND gset.status_id=1 ORDER BY gset.goal_order";
			}
		}
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function escapeStr($str){
		if($str != "'"){
			$newstr = trim(Database::instance()->escape(trim($str)), "'");
			return $newstr;
		}
		return $str;
	}
	public function getExerciseRecordById($unit_id = 0){
		$sql = "SELECT * FROM unit_gendata AS ugd LEFT JOIN img AS img ON ugd.feat_img = img.img_id WHERE ugd.status_id=1 AND ugd.unit_id = $unit_id limit 1";
		$query = DB::query(Database::SELECT, $sql);
		$list = $query->execute()->as_array();
     	return $list;
	}
	public function InsertExerciseRecByIdData(){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$datetime = Helper_Common::get_default_datetime();
		$data = array();
		$imgnewid='';
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		// General Data
		$title 			= $this->escapeStr($_POST['xru_title']);
		$unit_status 	= (int)$this->escapeStr($_POST['xru_status']);
		$unit_access 	= (int)$this->escapeStr(isset($_POST['xru_access']));
		// Exercise Data
		$feat_img	 	= (int)$this->escapeStr($_POST['xru_featImage']);
		$feat_vid		= $this->escapeStr($_POST['xru_featVideo']);
		$unit_type		= $this->escapeStr($_POST['xru_type']);
		if(isset($_POST['xru_musprim']) && !empty($_POST['xru_musprim'])){
			$unit_muscle = $this->escapeStr($_POST['xru_musprim']);
		}else{
			$unit_muscle = '18';
		}
		if(isset($_POST['xru_equip']) && !empty($_POST['xru_equip'])){
			$unit_equip	= $this->escapeStr($_POST['xru_equip']);
		}else{
			$unit_equip	= '3';
		}
		$unit_mech		= $this->escapeStr($_POST['xru_mech']);
		$unit_level		= $this->escapeStr($_POST['xru_level']);
		$unit_sport		= $this->escapeStr($_POST['xru_sports']);
		$unit_force		= $this->escapeStr($_POST['xru_force']);
		// Descriptions
		$unit_descbr 	= $this->escapeStr($_POST['xru_descbr']);
		$unit_descfull	= $this->escapeStr($_POST['xru_descfull']);
		// Other muscles Array(s)
		$othmusl_arr	= (isset($_POST['chkdMusOth']) ? $_POST['chkdMusOth'] : '');
		// Other equipments Array(s)
		$othequip_arr	= (isset($_POST['chkdEquipOth']) ? $_POST['chkdEquipOth'] : '');
		// Sequence Array(s)
		$seqimg_arr		= (isset($_POST['seqImg']) ? $_POST['seqImg'] : '');
		$seqdesc_arr	= (isset($_POST['seqDesc']) ? $_POST['seqDesc'] : '');
		$default_status = (isset($_POST['default_status']) ? $_POST['default_status'] : '0');
		// Tags
		$unit_tags		= $this->escapeStr($_POST['xru_Tags']);
		if(!empty($unit_access)){
			$accessid = $unit_access;
		}else{
			$accessid = $useraccess;
		}
		if(!empty($feat_img) && is_int($feat_img)){
			$unitnewid='';
			$unitdatasql = "INSERT INTO `unit_gendata` (`title`, `status_id`, `default_status`, `date_created`, `date_modified`, `created_by`, `access_id`, `site_id`, `feat_img`, `feat_vid`, `type_id`, `musprim_id`, `equip_id`, `mech_id`, `level_id`, `sport_id`, `force_id`, `descbr`, `descfull`) VALUES ('$title', '$unit_status', '$default_status', '$datetime', '$datetime', '$userid', '$accessid', '$site_id', '$feat_img', '$feat_vid', '$unit_type', '$unit_muscle', '$unit_equip', '$unit_mech', '$unit_level', '$unit_sport', '$unit_force', '$unit_descbr', '$unit_descfull')";
			$unitquery = DB::query(Database::INSERT,$unitdatasql);
			$unitinsert	= $unitquery->execute();
			$unitnewid = $unitinsert[0] ? $unitinsert[0] : '';

			$data['xrid'] = $unitnewid;
			$data['success'] = true;

			if(is_int($unitnewid) && !empty($unitnewid)){
				$this->insertActivityFeed(5, 1, $unitnewid);
				$unitgen_data = $this->getExerciseRecordById($unitnewid);
				if(!empty($unitgen_data) && count($unitgen_data) > 0){
					$data['title'] = $unitgen_data[0]['title'];
					$data['img_url'] = $unitgen_data[0]['img_url'];
				}
				if(!empty($unit_tags)){
					$insertunittags = $this->insertUnitTagById($unit_tags, $unitnewid);
				}
				if(!empty($othmusl_arr)){
					$musothsql = "SELECT * FROM `unit_musoth` WHERE `unit_id`=".$unitnewid."";
					$muscdata = DB::query(Database::SELECT,$musothsql)->execute();

					if($muscdata!=null && count($muscdata)>0){
						$updatemusc = "UPDATE `unit_musoth` SET `status_id`=4 WHERE unit_id=".$unitnewid."";
						$muscupdateres = DB::query(Database::UPDATE,$updatemusc)->execute();
					}

					$musArray = $othmusl_arr;

					$musinssql= "INSERT INTO `unit_musoth` (`unit_id`,`musoth_id`) VALUES ";
					$i=0; $k=$i+1;
					$musArray_count = count($musArray);

					foreach($musArray as $musOth) {
						$end = ($i == $musArray_count-1) ? ';' : ',';
						if(!empty($musOth)){
							$musinssql .= "(".$unitnewid.",".$musOth.")".$end;
						}
						$i++; $k++;
					}
					$musinssql = rtrim($musinssql, ",");
					$muscquery = DB::query(Database::INSERT,$musinssql);
					$muscres = $muscquery->execute();

					$data['success'] = true;
			 	} else {

			 	}

			 	if(!empty($othequip_arr)){
					$equipothsql = "SELECT * FROM `unit_equipoth` WHERE `unit_id`=".$unitnewid."";
					$equipdata = DB::query(Database::SELECT,$equipothsql)->execute();

					if($equipdata!=null && count($equipdata)>0){
						$updateequip = "UPDATE `unit_equipoth` SET `status_id`=4 WHERE unit_id=".$unitnewid."";
						$equipupdateres = DB::query(Database::UPDATE,$updateequip)->execute();
					}

					$equipArray = $othequip_arr;

					$equipinssql= "INSERT INTO `unit_equipoth` (`unit_id`,`equipoth_id`) VALUES ";
					$i=0; $k=$i+1;
					$equipArray_count = count($equipArray);

					foreach($equipArray as $equipOth) {
						$end = ($i == $equipArray_count-1) ? ';' : ',';
						if(!empty($equipOth)){
							$equipinssql .= "(".$unitnewid.",".$equipOth.")".$end;
						}
						$i++; $k++;
					}
					$equipinssql = rtrim($equipinssql, ",");
					$equipquery = DB::query(Database::INSERT,$equipinssql);
					$equipres = $equipquery->execute();

					$data['success'] = true;
			 	} else {

			 	}

				if(!empty($seqimg_arr) && !empty($seqdesc_arr)) {
					$seqselect = "SELECT * FROM `unit_seq` WHERE unit_id=".$unitnewid."";
					$seqdata = DB::query(Database::SELECT,$seqselect)->execute();
					
					if($seqdata!=null && count($seqdata)>0){
						$updateseq = "UPDATE `unit_seq` SET `status_id`=4 WHERE unit_id=".$unitnewid."";
						$sequpdateres = DB::query(Database::UPDATE,$updateseq)->execute();
					}
					$seqImgs = $seqimg_arr;
					$seqDescs = $seqdesc_arr;
					$i=0; $k=$i+1;
					foreach($seqImgs as $seqImgk => $seqImgv) {
						$seqImg = (int)$this->escapeStr($seqImgv);
						$seqDesc = $this->escapeStr($seqDescs[$seqImgk]);
						if(!empty($seqImg) || !empty($seqDesc)){
							$seqinssql = "INSERT INTO `unit_seq` (`unit_id`,`seq_img`,`seq_desc`,`seq_order`) VALUES (".$unitnewid.",".$seqImg.",'".$seqDesc."',".$k.")";
							$seqquery = DB::query(Database::INSERT,$seqinssql)->execute();
							$i++; $k++;
						}
					}
					$data['success'] = true;
				}else {

				}
			} else {
				$data['success'] = false;
			}
		} else {
			$data['success'] = false;
		}
		return $data;
	}
	public function UpdateExerciseRecByIdData($POST, $XrRecid){
		$datetime = Helper_Common::get_default_datetime();
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		$data = array();
		$imgnewid='';
		// General Data
		$uid 			= (int)(!empty($XrRecid) ? $XrRecid : '');
		$title 			= $this->escapeStr($POST['xru_title']);
		// echo $title;exit;
		$unit_status 	= $this->escapeStr($POST['xru_status']);
		$unit_access 	= $this->escapeStr(isset($POST['xru_access']));
		// Exercise Data
		$feat_img	 	= (int)$this->escapeStr($POST['xru_featImage']);
		$feat_vid		= $this->escapeStr($POST['xru_featVideo']);
		$unit_type		= $this->escapeStr($POST['xru_type']);
		if(isset($POST['xru_musprim']) && !empty($POST['xru_musprim'])){
			$unit_muscle = $this->escapeStr($POST['xru_musprim']);
		}else{
			$unit_muscle = '18';
		}
		if(isset($POST['xru_equip']) && !empty($POST['xru_equip'])){
			$unit_equip = $this->escapeStr($POST['xru_equip']);
		}else{
			$unit_equip = '3';
		}
		$unit_mech		= $this->escapeStr($POST['xru_mech']);
		$unit_level		= $this->escapeStr($POST['xru_level']);
		$unit_sport		= $this->escapeStr($POST['xru_sports']);
		$unit_force		= $this->escapeStr($POST['xru_force']);
		// Descriptions
		$unit_descbr 	= $this->escapeStr($POST['xru_descbr']);
		$unit_descfull	= $this->escapeStr($POST['xru_descfull']);
		// Other muscles Array(s)
		$othmusl_arr	= (isset($POST['chkdMusOth']) ? $POST['chkdMusOth'] : '');
		// Other equipments Array(s)
		$othequip_arr	= (isset($_POST['chkdEquipOth']) ? $_POST['chkdEquipOth'] : '');
		// Sequence Array(s)
		$seqimg_arr		= (isset($POST['seqImg']) ? $POST['seqImg'] : '');
		$seqdesc_arr	= (isset($POST['seqDesc']) ? $POST['seqDesc'] : '');
		// Tags
		$unit_tags		= $this->escapeStr($_POST['xru_Tags']);
		if(!empty($unit_access)){
			$accessid = $unit_access;
		}else{
			$accessid = $useraccess;
		}
		if(!empty($feat_img) && is_int($feat_img)){
			$unitstatussql = "SELECT status_id FROM unit_gendata WHERE unit_id=$uid";
			$unitstatus = DB::query(Database::SELECT, $unitstatussql)->execute()->as_array();

			$unitdatasql = "UPDATE `unit_gendata` SET `title`='$title', `status_id`='$unit_status', `date_modified`='$datetime', `access_id`=$accessid, `feat_img`=$feat_img, `feat_vid`='$feat_vid', `type_id`='$unit_type', `musprim_id`='$unit_muscle', `equip_id`='$unit_equip', `mech_id`='$unit_mech', `level_id`='$unit_level', `sport_id`='$unit_sport', `force_id`='$unit_force', `descbr`='$unit_descbr', `descfull`='$unit_descfull' WHERE unit_id=$uid";
			$unitquery = DB::query(Database::UPDATE,$unitdatasql)->execute();
			$data['xrid'] = $uid;
			$data['success'] = true;

			if(is_int($uid) && !empty($uid)){
				// if($unitstatus[0]['status_id'] != $unit_status){
				// 	$this->insertActivityFeed(5, 26, $uid, array($unit_status));
				// }
				$this->insertActivityFeed(5, 26, $uid);
				$unitgen_data = $this->getExerciseRecordById($uid);
				if(!empty($unitgen_data) && count($unitgen_data) > 0){
					$data['title'] = $unitgen_data[0]['title'];
					$data['img_url'] = $unitgen_data[0]['img_url'];
				}
				if(!empty($unit_tags)){
					$insertunittags = $this->insertUnitTagById($unit_tags, $uid);
				}
				if(!empty($othmusl_arr)){
					$musothsql = "SELECT * FROM `unit_musoth` WHERE `unit_id`=".$uid."";
					$muscdata = DB::query(Database::SELECT,$musothsql)->execute();

					if($muscdata!=null && count($muscdata)>0){
						$deletemusc = "DELETE FROM `unit_musoth` WHERE unit_id=".$uid."";
						$muscdeltres = DB::query(Database::DELETE,$deletemusc)->execute();
					}

					$musArray = $othmusl_arr;

					$musinssql= "INSERT INTO `unit_musoth` (`unit_id`,`musoth_id`) VALUES ";
					$i=0; $k=$i+1;
					$musArray_count = count($musArray);

					foreach($musArray as $musOth) {
						$end = ($i == $musArray_count-1) ? ';' : ',';
						if(!empty($musOth)){
							$musinssql .= "(".$uid.",".$musOth.")".$end;
						}
						$i++; $k++;
					}
					$musinssql = rtrim($musinssql, ",");
					$muscquery = DB::query(Database::INSERT,$musinssql);
					$muscres = $muscquery->execute();

					$data['success'] = true;
			 	} else {
			 		$musothsql = "SELECT * FROM `unit_musoth` WHERE `unit_id`=".$uid."";
					$muscdata = DB::query(Database::SELECT,$musothsql)->execute();

					if($muscdata!=null && count($muscdata)>0){
						$deletemusc = "DELETE FROM `unit_musoth` WHERE unit_id=".$uid."";
						$muscdeltres = DB::query(Database::DELETE,$deletemusc)->execute();
					}
			 	}

			 	if(!empty($othequip_arr)){
					$equipothsql = "SELECT * FROM `unit_equipoth` WHERE `unit_id`=".$uid."";
					$equipdata = DB::query(Database::SELECT,$equipothsql)->execute();

					if($equipdata!=null && count($equipdata)>0){
						$deleteequip = "DELETE FROM `unit_equipoth` WHERE unit_id=".$uid."";
						$equipdeltres = DB::query(Database::DELETE,$deleteequip)->execute();
					}

					$equipArray = $othequip_arr;

					$equipinssql= "INSERT INTO `unit_equipoth` (`unit_id`,`equipoth_id`) VALUES ";
					$i=0; $k=$i+1;
					$equipArray_count = count($equipArray);

					foreach($equipArray as $equipOth) {
						$end = ($i == $equipArray_count-1) ? ';' : ',';
						if(!empty($equipOth)){
							$equipinssql .= "(".$uid.",".$equipOth.")".$end;
						}
						$i++; $k++;
					}
					$equipinssql = rtrim($equipinssql, ",");
					$equipquery = DB::query(Database::INSERT,$equipinssql);
					$equipres = $equipquery->execute();

					$data['success'] = true;
			 	} else {
			 		$equipothsql = "SELECT * FROM `unit_equipoth` WHERE `unit_id`=".$uid."";
					$equipdata = DB::query(Database::SELECT,$equipothsql)->execute();

					if($equipdata!=null && count($equipdata)>0){
						$deleteequip = "DELETE FROM `unit_equipoth` WHERE unit_id=".$uid."";
						$equipdeltres = DB::query(Database::DELETE,$deleteequip)->execute();
					}
			 	}

				if(!empty($seqimg_arr) && !empty($seqdesc_arr)) {
					$seqselect = "SELECT * FROM `unit_seq` WHERE unit_id=".$uid."";
					$seqdata = DB::query(Database::SELECT,$seqselect)->execute();

					if($seqdata!=null && count($seqdata)>0){
						$updateseq = "DELETE FROM `unit_seq` WHERE unit_id=".$uid."";
						$sequpdateres = DB::query(Database::DELETE,$updateseq)->execute();
					}
					$seqImgs = $seqimg_arr;
					$seqDescs = $seqdesc_arr;
					$i=0; $k=$i+1;
					foreach($seqImgs as $seqImgk => $seqImgv) {
						$seqImg = (int)$this->escapeStr($seqImgv);
						$seqDesc = $this->escapeStr($seqDescs[$seqImgk]);
						if(!empty($seqImg) || !empty($seqDesc)){
							$seqinssql = "INSERT INTO `unit_seq` (`unit_id`,`seq_img`,`seq_desc`,`seq_order`) VALUES (".$uid.",".$seqImg.",'".$seqDesc."',".$k.")";
							$seqquery = DB::query(Database::INSERT,$seqinssql)->execute();
							$i++; $k++;
						}
					}
					$data['success'] = true;
				}else {
					$seqselect = "SELECT * FROM `unit_seq` WHERE unit_id=".$uid."";
					$seqdata = DB::query(Database::SELECT,$seqselect)->execute();

					if($seqdata!=null && count($seqdata)>0){
						$updateseq = "DELETE FROM `unit_seq` WHERE unit_id=".$uid."";
						$sequpdateres = DB::query(Database::DELETE,$updateseq)->execute();
					}
				}
			} else {
				$data['success'] = false;
			}
		} else {
			$data['success'] = false;
		}
		return $data;
	}
	public function random_color_part() {
   	 	return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
	}	
	public function random_color() {
		return "#".$this->random_color_part() . $this->random_color_part() . $this->random_color_part();
	}
	public function addQuotes($string) {
	    return "'". implode("', '", explode(",", $string)) ."'";
	}
	public function getUnitTagsNotinByTitle($tags, $unitid, $userid) {
   	$itemsql = "SELECT u.unit_id, t.tag_id, t.tag_title FROM `unit_gendata` u JOIN `unit_tag` ut ON u.unit_id=ut.unit_id JOIN `tag` t ON ut.tag_id=t.tag_id WHERE BINARY t.tag_title NOT IN (".$tags.") AND u.unit_id=".$unitid." AND ut.created_by=".$userid." ORDER BY u.unit_id DESC";
		$itemlist = DB::query(Database::SELECT,$itemsql)->execute()->as_array();
		$tagidarry = array();
		if(!empty($itemlist) && count($itemlist)>0){
			foreach ($itemlist as $key => $value) {
				$tagidarry[] = $value['tag_id'];
			}
		}
		return $tagidarry;
	}
	public function deleteUnitTagsByIds($tagids, $unitid, $userid) {
		if(!empty($tagids) && !empty($unitid)){
		   	$unittagdelsql = "DELETE FROM unit_tag WHERE tag_id IN (SELECT tag_id FROM `tag` WHERE tag_id IN (".$tagids.")) AND created_by=".$userid." AND unit_id IN(".$unitid.")";
			$delres = DB::query(Database::DELETE,$unittagdelsql)->execute();
			return true;
		}
		return false;
	}
	public function getUnitTagsById($unit_id, $copyflag=0) {
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		if($copyflag==0){
			$itemsql = "SELECT u.unit_id, t.tag_id, t.tag_title, ut.created_by FROM `unit_gendata` u JOIN `unit_tag` ut ON u.unit_id=ut.unit_id JOIN `tag` t ON ut.tag_id=t.tag_id WHERE u.unit_id=".$unit_id." AND ut.created_by=".$userid." ORDER BY u.unit_id DESC";
		}else{
			$itemsql = "SELECT u.unit_id, t.tag_id, t.tag_title, ut.created_by FROM `unit_gendata` u JOIN `unit_tag` ut ON u.unit_id=ut.unit_id JOIN `tag` t ON ut.tag_id=t.tag_id WHERE u.unit_id=".$unit_id." ORDER BY u.unit_id DESC";
		}
		$query 	= DB::query(Database::SELECT,$itemsql);
		$itemlist = $query->execute()->as_array();
		return $itemlist;
	}
	public function insertUnitTagById($unittags, $unitid){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		$successflag = false;
		if(isset($unittags) && !empty($unittags)){
			$posttags = $unittags;
		}else{
			$posttags = '';
		}
		if(!empty($posttags)){
			$explodtag = explode(',', $posttags);
			$taglist = $this->addQuotes($posttags);
			if(count($explodtag > 0)){
				$deletetag = $this->getUnitTagsNotinByTitle($taglist, $unitid, $userid);
				if(count($deletetag) > 0){
					$this->deleteUnitTagsByIds(implode(',', $deletetag), $unitid, $userid);
					$feedjson = array();
					$feedjson["text"] = "from exercise record";
					$feedjson["tag_id"] = $deletetag;
					$this->insertActivityFeed(8, 2, $unitid, $feedjson);
				}
				$taggedids = array();
				foreach ($explodtag as $key => $tagvalue) {
					$sql = "SELECT * FROM tag WHERE BINARY tag_title='".$tagvalue."' limit 1";
					$query = DB::query(Database::SELECT,$sql);
					$list = $query->execute()->as_array();

					if(count($list) > 0 && !empty($list)){
						//delete the duplicate tag for same unitid
						$unit_chktagsql = "SELECT ut.* FROM `unit_tag` ut JOIN `unit_gendata` u ON ut.unit_id=u.unit_id JOIN `tag` t ON ut.tag_id=t.tag_id WHERE t.tag_id=".$list[0]['tag_id']." AND u.unit_id=".$unitid." AND ut.created_by=".$userid;
						$unit_chktagres = DB::query(Database::SELECT,$unit_chktagsql)->execute()->as_array();
						if(count($unit_chktagres) > 1 && !empty($unit_chktagres)){
							$this->deleteUnitTagsByIds($list[0]['tag_id'], $unitid, $userid);
							$successflag = true;
						}

						$unit_tagsql = "SELECT ut.* FROM `unit_tag` ut JOIN `unit_gendata` u ON ut.unit_id=u.unit_id JOIN `tag` t ON ut.tag_id=t.tag_id WHERE t.tag_id=".$list[0]['tag_id']." AND u.unit_id=".$unitid." AND ut.created_by=".$userid." limit 1";
						$unit_tagres = DB::query(Database::SELECT,$unit_tagsql)->execute()->as_array();
						if(count($unit_tagres) > 0 && !empty($unit_tagres)){
							$successflag = true;
						} else {
							$sqlunittag = "INSERT INTO `unit_tag`(`tag_id`, `unit_id`, `created_by`) VALUES (".$list[0]['tag_id'].", ".$unitid.", ".$userid.")";
							$unittagquery = DB::query(Database::INSERT,$sqlunittag)->execute();
							$unittagnewid = $unittagquery[0] ? $unittagquery[0] : $unittagquery;
							if($unittagnewid){
								$taggedids[] = $list[0]['tag_id'];
								$successflag = true;
							}else{
								$successflag = false;
							}
						}
					} else {
						$sqltag = "INSERT INTO `tag`(`tag_title`, `tag_color`, `tag_cat_id`, `access_id`, `created_by`, `created`, `hits`) VALUES ('".$tagvalue."', '".$this->random_color()."', 1, ".$useraccess.", ".$userid.", '".Helper_Common::get_default_datetime()."', 0)";
						$tagquery = DB::query(Database::INSERT,$sqltag)->execute();
						$tagnewid = $tagquery[0] ? $tagquery[0] : '';
						if($tagnewid){
							$sqlimgtag = "INSERT INTO `unit_tag`(`tag_id`, `unit_id`, `created_by`) VALUES (".$tagnewid.", ".$unitid.", ".$userid.")";
							$unittagquery = DB::query(Database::INSERT,$sqlimgtag)->execute();
							$unittagnewid = $unittagquery[0] ? $unittagquery[0] : $unittagquery;
							if($unittagnewid){
								$taggedids[] = (string)$tagnewid;
								$successflag = true;
							}else{
								$successflag = false;
							}
						}
						else{
							$successflag = false;
						}
					}
				}
				if(count($taggedids) > 0 && !empty($taggedids)){
					$feedjson = array();
					$feedjson["text"] = "for exercise record";
					$feedjson["tag_id"] = $taggedids;
					$this->insertActivityFeed(8, 1, $unitid, $feedjson);
				}
				if($successflag){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		else {
			$deletetag = $this->getUnitTagsNotinByTitle("''", $unitid, $userid);
			if(count($deletetag) > 0){
				$this->deleteUnitTagsByIds(implode(',', $deletetag), $unitid, $userid);
				$feedjson = array();
				$feedjson["text"] = "from exercise record";
				$feedjson["tag_id"] = $deletetag;
				$this->insertActivityFeed(8, 2, $unitid, $feedjson);
				return true;
			}else{
				return 'no-tag';
			}
		}
		return true;
	}
	/* Activity Feed */
	public function insertActivityFeed($feedtype, $actiontype, $typeid, $activityjson = array(), $contextdate = ''){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$activity_feed = array();
		$activity_feed["feed_type"] = $feedtype; // This get from feed_type table
		$activity_feed["action_type"] = $actiontype; // This get from action_type table  
		$activity_feed["type_id"] = $typeid; // Workout Id or User id or Exercise setid or image id or workout folder id or tag id
		$activity_feed["site_id"] = $site_id;
		$activity_feed["user"] = Auth::instance()->get_user()->pk();
		if(!empty($activityjson) && count($activityjson) > 0){
			$activity_feed["json_data"] = json_encode($activityjson); // if need to encode data and store
		}
		if(!empty($contextdate)){
			$activity_feed["context_date"] = $contextdate; // if need to encode data and store
		}
		$activity_result = Helper_Common::createActivityFeed($activity_feed);
		return true;
	}
	public function getActivityFeedDetail($whereArr){
		$condition ='';
		$i=0;
		foreach($whereArr as $key => $val){
			if($i>0){
				$condition .= " AND ";
			}
			$condition .= "$key = '$val'";
			$i++;
		}	
		$sql = "SELECT * FROM activity_feed WHERE ".$condition."ORDER BY created_date DESC LIMIT 1";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return $list;
	}
	public function getMusOthByUnitId($unitId){
		$musothsql = "SELECT um.*, ums.muscle_title FROM `unit_musoth` um LEFT JOIN `unit_muscle` ums ON um.musoth_id = ums.muscle_id LEFT JOIN `unit_gendata` ug ON um.unit_id = ug.unit_id WHERE um.unit_id=".$unitId." AND um.status_id=1";
		$query 	= DB::query(Database::SELECT,$musothsql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getEquipOthByUnitId($unitId){
		$equipothsql = "SELECT ueo.*, ue.equip_title FROM `unit_equipoth` ueo LEFT JOIN `unit_equip` ue ON ueo.equipoth_id = ue.equip_id LEFT JOIN `unit_gendata` ug ON ueo.unit_id = ug.unit_id WHERE ueo.unit_id=".$unitId." AND ueo.status_id=1";
		$query 	= DB::query(Database::SELECT,$equipothsql);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function getSequencesByUnitId($unitId, $start = 0, $limit = 0){
		//$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$seqselect = "SELECT us . * , img . * FROM  `unit_seq` us LEFT JOIN  `img` img ON us.seq_img = img.img_id LEFT JOIN  `unit_gendata` ug ON us.unit_id = ug.unit_id WHERE us.unit_id=".$unitId." AND us.status_id=1  ORDER BY us.seq_order ASC ".($limit > 0 ? " LIMIT $start, $limit" : '');
		$query 	= DB::query(Database::SELECT,$seqselect);
		$list 	= $query->execute()->as_array();
		return $list;
	}
	public function insertRatingDetails($insertArray, $user_id){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$rate_result = DB::insert('unit_gendata_rating', array('unit_id', 'user_id', 'rate_value', 'rate_comments', 'site_id', 'created_date', 'modified_date'))->values(array($insertArray['unit_id'], $user_id, $insertArray['rate_value'], $insertArray['rate_comments'], $site_id ,$insertArray['created_date'], $insertArray['modified_date']))->execute();
		return $rate_result[0];
	}
	public function getSiteTableData($siteid){
		$sitesql = "SELECT * FROM sites WHERE id=".$siteid;
		$sitedata = DB::query(Database::SELECT, $sitesql)->execute()->as_array();
		return $sitedata;
	}
	public function checkPageName($page_name){
		$sql = "SELECT id FROM `site_pages` WHERE page_name = '" . $page_name . "' AND is_active='1' AND is_delete='0'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		if(isset($list[0]) && count($list[0]) > 0)
			return $list[0]['id'];
		else{
			$datetime = Helper_Common::get_default_datetime();
			$page_result = DB::insert('site_pages', array('page_name', 'is_active', 'is_delete',  'created_date', 'modified_date'))->values(array($page_name, '1', '0' ,$datetime, $datetime))->execute();
			return $page_result[0];
		}
	}
	public function getIdbyPage($page_id,$user_id){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql = "SELECT id FROM `user_page_display` WHERE page_id = '" . $page_id . "' AND user_id='".$user_id."' AND site_id='".$site_id."' AND status='1'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		if(isset($list[0]) && count($list[0]) > 0)
			return $list[0]['id'];
		else
			return 0;
	}
	public function updateDontshow($page_id, $user_id, $type, $flag){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$datetime = Helper_Common::get_default_datetime();
		$insertedId = $this->getIdbyPage($page_id, $user_id);
		if(!empty($insertedId)){
			$query = DB::update('user_page_display')->set(array('page_id' 	=> $page_id, $type 	=> $flag,'modified_date' => $datetime))->where('id', '=', $insertedId)->execute();
		}else{
			$confirm_result = DB::insert('user_page_display', array('user_id', 'page_id', $type, 'status', 'site_id', 'created_date', 'modified_date'))->values(array($user_id, $page_id, $flag, '1',$site_id ,$datetime, $datetime))->execute();
			$insertedId = $confirm_result[0];
		}
		return $insertedId;
	}
	public function getAllowAccess($page_id, $user_id){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql = "SELECT * FROM `user_page_display` WHERE page_id = '" . $page_id . "' AND user_id='".$user_id."' AND site_id='".$site_id."' AND status='1'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return (isset($list[0]) && count($list[0]) > 0 ? $list[0] : array());
	}
	public function insertAddToHomeEnd($site_id, $user_id){
		$datetime = Helper_Common::get_default_datetime();
		$tour_result = DB::insert('user_addtohome', array('user_id', 'site_id', 'created_date'))->values(array($user_id, $site_id, $datetime))->execute();
		return $tour_result[0];
	}
	public function getAddToHomeEnd($site_id, $user_id){
		$sql = "SELECT count(*) as count FROM `user_addtohome` WHERE site_id = '" . $site_id . "' AND user_id='".$user_id."'";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return (isset($list[0]['count']) && $list[0]['count'] > 0 ? false : true);
	}
	public function getAssignDateById($wkout_assign_id){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "SELECT wkgd.assigned_date FROM `wkout_assign_gendata` AS wkgd where wkgd.wkout_assign_id='".$wkout_assign_id."' AND wkgd.status_id !=4 AND wkgd.site_id in (".$site_id.")";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return (isset($list[0]['assigned_date']) ? $list[0]['assigned_date'] : Helper_Common::get_default_date());
	}
	public function getAssignInfoById($wkout_assign_id){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "SELECT wkgd.wkout_id, wkgd.from_wkout, wkgd.assigned_by, wkgd.assigned_date FROM `wkout_assign_gendata` AS wkgd where wkgd.wkout_assign_id='".$wkout_assign_id."' AND wkgd.status_id !=4 AND wkgd.site_id in (".$site_id.")";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return (isset($list[0]) ? $list[0] : $list);
	}
	public function getAssignDateByLogId($wkout_log_id){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "SELECT wkgd.assigned_date FROM `wkout_log_gendata` AS wkgd where wkgd.wkout_log_id='".$wkout_log_id."' AND wkgd.status_id !=4 AND wkgd.site_id in (".$site_id.")";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		return (isset($list[0]['assigned_date']) ? $list[0]['assigned_date'] : Helper_Common::get_default_date());
	}
	public function insertEmailNotify($assignArray){
		$datetime = Helper_Common::get_default_datetime();
		$user_id  = Auth::instance()->get_user()->pk();
		$site_id  = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$result = DB::insert('email_automation', array('user_id', 'site_id', 'wkout_assign_id', 'triggerby_date', 'triggerby_time', 'created_date', 'modified_date'))->values(array($user_id, $site_id, $assignArray['wkout_assign_id'], $assignArray['triggerby_date'], $assignArray['triggerby_time'], $datetime, $datetime))->execute();
		return $result[0];
	}
	public function updateEmailNotify($assignArray){
		$assignArray['modified_date'] = Helper_Common::get_default_datetime();
		$user_id  = Auth::instance()->get_user()->pk();
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$query = DB::update('email_automation')->set($assignArray)->where('user_id', '=', $user_id)->where('site_id', '=', $site_id)->where('is_active', '=', '0')->where('is_delete', '=', '0')->where('is_mail_send', '=', '0')->execute();
		return $query[0];
	}
	public function updateEmailNotifyByAssignId($wkout_assign_id, $assignArray=array()){
		$assignArray['is_delete'] 	  = '1';
		$assignArray['modified_date'] = Helper_Common::get_default_datetime();
		$user_id  = Auth::instance()->get_user()->pk();
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$query = DB::update('email_automation')->set($assignArray)->where('user_id', '=', $user_id)->where('site_id', '=', $site_id)->where('wkout_assign_id', '=', $wkout_assign_id)->execute();
		return true;
	}
	public function updateEmailNotifyByIdStatus($wkout_assign_id, $assignArray=array()){
		$assignArray['modified_date'] = Helper_Common::get_default_datetime();
		if(!isset($assignArray['is_mail_send'])){
			$sql = "update email_automation  set attempt_inc = attempt_inc + 1,modified_date = '".$assignArray['modified_date']."'  WHERE wkout_assign_id = '".$wkout_assign_id."'";
			$query = DB::query(Database::UPDATE,$sql);						
			$query->execute();
		}else
			$query = DB::update('email_automation')->set($assignArray)->where('wkout_assign_id', '=', $wkout_assign_id)->execute();
		if(isset($assignArray['is_mail_send']) && $assignArray['is_mail_send'] == '1'){
			$sql = "update wkout_assign_gendata  set is_email = '1' WHERE wkout_assign_id = '".$wkout_assign_id."'";
			$query = DB::query(Database::UPDATE,$sql);						
			$query->execute();
		}
		return true;
	}
	public function insertShareAssign($assignArray){
		$datetime = Helper_Common::get_default_datetime();
		$user_id  = Auth::instance()->get_user()->pk();
		$site_id  = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$result = DB::insert('assign_from_share', array('wkout_share_id','assign_date','shared_user_id','assigned_user_id','site_id', 'created_date', 'modified_date'))->values(array($assignArray['wkout_share_id'], $assignArray['assign_date'], $user_id, $assignArray['assigned_user_id'], $site_id, $datetime, $datetime))->execute();
		return $result[0];
	}
	public function updateShareAssign($assignArray,$sharedassign_id){
		$assignArray['modified_date'] = Helper_Common::get_default_datetime();
		$query = DB::update('assign_from_share')->set($assignArray)->where('id', '=', $sharedassign_id)->execute();
		return $query[0];
	}
}
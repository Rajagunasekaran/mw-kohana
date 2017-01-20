<?php
defined('SYSPATH') OR die('No direct access allowed.');
class Model_Admin_Workouts extends Model
{
   public function getUserRole()
   {
      $userId = Auth::instance()->get_user()->pk();
      $usermodelORM = ORM::factory('user');
	  $usermodel = $usermodelORM->where('id', '=', trim($userId))->find();
      if ($usermodel->has('roles', ORM::factory('Role', array(
         'name' => 'admin'
      )))) {
         return '2';
      } else if ($usermodel->has('roles', ORM::factory('Role', array(
            'name' => 'global'
         )))) {
         return '3';
      } else if ($usermodel->has('roles', ORM::factory('Role', array(
            'name' => 'localsite'
         )))) {
         return '4';
      } else if ($usermodel->has('roles', ORM::factory('Role', array(
            'name' => 'manager'
         )))) {
         return '8';
      } else if ($usermodel->has('roles', ORM::factory('Role', array(
            'name' => 'corporate'
         )))) {
         return '5';
      } else if ($usermodel->has('roles', ORM::factory('Role', array(
            'name' => 'trainer'
         )))) {
         return '7';
      } else if ($usermodel->has('roles', ORM::factory('Role', array(
            'name' => 'trial'
         )))) {
         return '9';
      }
      return '6'; // register
   }
   public function getExerciseSetAllDetailsByWorkout($wkout_id, $goal_id, $userId)
   {
      $sql   = "SELECT gset.*, setvars.* FROM goal_gendata AS gset LEFT JOIN goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS xrgd on xrgd.unit_id = gset.goal_unit_id LEFT JOIN unit_status AS g1 ON xrgd.status_id=g1.status_id LEFT JOIN roles AS g10 ON xrgd.access_id=g10.id WHERE gset.goal_id = '" . $goal_id . "' AND gset.wkout_id='" . $wkout_id . "' AND gset.user_id='" . $userId . "' AND gset.status_id=1 ORDER BY gset.goal_order";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return isset($list[0]) ? $list[0] : $list;
   }
   public function getMaxGoalorder($wkoutId, $userId)
   {
      $sql   = "SELECT MAX(goal_order) as goal_order from goal_gendata Where wkout_id ='" . $wkoutId . "' AND user_id ='" . $userId . "' AND status_id!='4'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return (isset($list[0]['goal_order']) ? $list[0]['goal_order'] : '0');
   }
   public function getCurrentGoalorder($wkoutId, $xrsetId, $userId)
   {
      $sql   = "SELECT goal_order from goal_gendata Where wkout_id ='" . $wkoutId . "' AND user_id ='" . $userId . "' AND goal_id='" . $xrsetId . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return (isset($list[0]['goal_order']) ? $list[0]['goal_order'] : '0');
   }
   public function updateAllGoalorder($type = 'up', $goalId, $workid)
   {
      if ($type == 'down') {
         $sql = "Update goal_gendata set goal_order = goal_order-1 Where wkout_id ='" . $workid . "' AND goal_id !='" . $goalId . "' AND status_id!=4";
      } else {
         $sql = "Update goal_gendata set goal_order = goal_order+1 Where wkout_id ='" . $workid . "' AND goal_id !='" . $goalId . "' AND status_id!=4";
      }
      return DB::query(Database::UPDATE, $sql)->execute();
   }
   public function getAllFocus($focus_id = 0)
   {
	  $sql 	= "SELECT focus_grp_id as focus_id,focus_grp_title as focus_opt_title FROM wkout_focus_grp WHERE 1 ".(!empty($focus_id) ? ' AND focus_grp_id="'.$focus_id."'" : '');
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getworkoutPreviewById($id_user = 0, $wk_it)
   {
      $sql   = "SELECT wkgd.wkout_id,wkgd.wkout_focus, wkgd.wkout_poa, wkgd.wkout_poa_time, wkgd.wkout_title, wkgd.wkout_group, wkgd.wkout_color, g1.color_id, g1.color_title, wkgd.wkout_order, wkgd.user_id, wkgd.status_id, wkgd.access_id, ws.parent_folder_id, ws.id as wks_id FROM wkout_gendata AS wkgd LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id LEFT JOIN wkout_seq as ws on ws.wkout_id = wkgd.wkout_id WHERE wkgd.wkout_id ='" . $wk_it . "'   " . (!empty($id_user) ? " AND wkgd.user_id='" . $id_user . "'" : '');
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return isset($list[0]) ? $list[0] : $list;
   }
   public function getworkoutById($id_user = 0, $wk_it)
   {
      $sql   = "SELECT wkgd.site_id,wkgd.wkout_id,wkgd.wkout_focus, wkgd.wkout_poa, wkgd.wkout_poa_time, wkgd.wkout_title, wkgd.wkout_group, wkgd.wkout_color, g1.color_id, g1.color_title, wkgd.wkout_order, wkgd.user_id, wkgd.status_id, wkgd.access_id, ws.parent_folder_id, ws.id as wks_id FROM wkout_gendata AS wkgd LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id LEFT JOIN wkout_seq as ws on ws.wkout_id = wkgd.wkout_id WHERE wkgd.wkout_id ='" . $wk_it . "' AND wkgd.status_id=1  " . (!empty($id_user) ? " AND wkgd.user_id='" . $id_user . "'" : '');
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return isset($list[0]) ? $list[0] : $list;
   }
   public function getsampleworkoutById($id_user = 0, $wk_it)
	{
      $sql   = "SELECT wkgd.wkout_sample_id as wkout_id,wkgd.wkout_focus, wkgd.wkout_poa, wkgd.wkout_poa_time, wkgd.wkout_title, wkgd.wkout_group, wkgd.wkout_color, g1.color_id, g1.color_title, wkgd.wkout_order, wkgd.user_id, wkgd.status_id, wkgd.access_id, ws.parent_folder_id, ws.id as wks_id FROM wkout_sample_gendata AS wkgd LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id LEFT JOIN wkout_sample_seq as ws on ws.wkout_sample_id = wkgd.wkout_sample_id WHERE wkgd.wkout_sample_id ='" . $wk_it . "' AND wkgd.status_id=1  " . (!empty($id_user) ? " AND wkgd.user_id='" . $id_user . "'" : '');
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return isset($list[0]) ? $list[0] : $list;
   }
   public function getShareworkoutById($id_user = 0, $wk_it)
   {
      $sql   = "SELECT wkgd.wkout_id,wkgd.wkout_share_id,wkgd.wkout_focus, wkgd.wkout_poa, wkgd.wkout_poa_time, wkgd.wkout_title, wkgd.wkout_group, wkgd.wkout_color, g1.color_id, g1.color_title, wkgd.wkout_order, wkgd.user_id, wkgd.status_id, wkgd.access_id FROM wkout_share_gendata AS wkgd LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id  WHERE wkgd.wkout_share_id ='" . $wk_it . "' AND wkgd.status_id=1 " . (!empty($id_user) ? " AND  wkgd.user_id='" . $id_user . "'" : '');
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return isset($list[0]) ? $list[0] : $list;
   }
   public function getCurrentSeqorder($wkseqId)
   {
      $sql   = "SELECT seq_order from wkout_seq as wkf Where wkf.id ='" . $wkseqId . "' AND wkout_seq_status=0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return (isset($list[0]['seq_order']) ? $list[0]['seq_order'] : '0');
   }
   public function getCurrentSeqorderByWkoutId($wkoutId)
   {
      $sql   = "SELECT id, seq_order, parent_folder_id, user_id from wkout_seq as wkf Where wkf.wkout_id ='" . $wkoutId . "' AND wkout_seq_status=0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return (isset($list[0]) ? $list[0] : '');
   }
   public function getCurrentSeqorderBySampleWkoutId($wkoutId)
   {
      $sql   = "SELECT id, seq_order, parent_folder_id, user_id from wkout_sample_seq as wkf Where wkf.wkout_sample_id ='" . $wkoutId . "' AND wkout_seq_status=0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return (isset($list[0]) ? $list[0] : '');
   }
   public function getCurrentSeqorderByShareWkoutId($wkoutId)
   {
      $sql   = "SELECT id, seq_order, parent_folder_id, shared_for as user_id from wkout_share_seq as wkf Where wkf.wkout_share_id ='" . $wkoutId . "' AND wkout_seq_status=0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return (isset($list[0]) ? $list[0] : '');
   }
   public function getExerciseSetPreview($wkout_id)
   {
      $sql   = "SELECT * FROM goal_gendata AS gset LEFT JOIN goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gset.wkout_id=" . $wkout_id . "   ORDER BY gset.goal_order";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getExerciseSet($wkout_id)
   {
      $sql   = "SELECT * FROM goal_gendata AS gset LEFT JOIN goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gset.wkout_id=" . $wkout_id . " AND gset.status_id=1  ORDER BY gset.goal_order";
      //echo $sql;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getExerciseSampleSet($wkout_id)
   {
      $sql   = "SELECT * FROM wkout_sample_goal_gendata AS gset LEFT JOIN wkout_sample_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gset.wkout_sample_id=" . $wkout_id . " AND gset.status_id=1  ORDER BY gset.goal_order";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getExerciseSetShare($wkout_id)
   {
      $sql   = "SELECT * FROM wkout_share_goal_gendata AS gset LEFT JOIN wkout_share_goal_vars AS setvars ON gset.goal_id=setvars.goal_id LEFT JOIN unit_gendata AS ugd ON gset.goal_unit_id=ugd.unit_id LEFT JOIN img AS img ON ugd.feat_img=img.img_id WHERE gset.wkout_share_id=" . $wkout_id . " AND gset.status_id=1  ORDER BY gset.goal_order";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function updateWkoutSeqOrder($parent_folder_id, $user_id)
   {
      $sql = "Update wkout_seq set seq_order = seq_order+1 Where parent_folder_id ='" . $parent_folder_id . "' AND user_id ='" . $user_id . "' AND wkout_seq_status=0";
      return DB::query(Database::UPDATE, $sql)->execute();
   }
   public function updateWkoutSeqOrderOtherthanFirstSeq($parent_folder_id, $wkseq_ids, $user_id)
   {
      $currentseqorder = $this->getCurrentSeqorder($wkseq_ids);
      $sql             = "Update wkout_seq set seq_order = seq_order+1 Where parent_folder_id ='" . $parent_folder_id . "' AND user_id ='" . $user_id . "' AND wkout_seq_status=0 AND id not in (" . $wkseq_ids . ") and  seq_order >= " . $currentseqorder;
      return DB::query(Database::UPDATE, $sql)->execute();
   }
   public function insertWorkoutDetails($array)
   {
      $workout_results = DB::insert('wkout_gendata', array(
         'wkout_group',
         'wkout_title',
         'wkout_color',
         'wkout_order',
         'user_id',
         'site_id',
         'status_id',
         'access_id',
         'wkout_focus',
         'wkout_poa',
         'wkout_poa_time',
		 'date_created',
         'date_modified'
      ))->values(array(
         $array['wkout_group'],
         $array['wkout_title'],
         $array['wkout_color'],
         $array['wkout_order'],
         $array['user_id'],
         $array['site_id'],
         $array['status_id'],
         $array['access_id'],
         $array['wkout_focus'],
         $array['wkout_poa'],
         $array['wkout_poa_time'],
		 $array['created_date'],
         $array['modified_date']
      ))->execute();
      $this->updateWkoutSeqOrder($array['parent_folder_id'], $array['user_id']);
      $seg_results = DB::insert('wkout_seq', array(
         'parent_folder_id',
         'folder_id',
         'wkout_id',
         'site_id',
         'seq_order',
         'user_id',
         'created_date',
         'modified_date'
      ))->values(array(
         $array['parent_folder_id'],
         '0',
         $workout_results[0],
         $array['site_id'],
         '1',
         $array['user_id'],
         $array['created_date'],
         $array['modified_date']
      ))->execute();
      return $workout_results[0];
   }
   public function insertWorkoutDetailsByseqOrder($array)
   {
      //print_r($array); die;
      $workout_results = DB::insert('wkout_gendata', array(
         'wkout_group',
         'wkout_title',
         'wkout_color',
         'wkout_order',
         'user_id',
         'site_id',
         'status_id',
         'access_id',
         'wkout_focus',
         'wkout_poa',
         'wkout_poa_time',
		 'date_created',
         'date_modified'
      ))->values(array(
         $array['wkout_group'],
         $array['wkout_title'],
         $array['wkout_color'],
         $array['wkout_order'],
         $array['user_id'],
         $array['site_id'],
         $array['status_id'],
         $array['access_id'],
         $array['wkout_focus'],
         $array['wkout_poa'],
         $array['wkout_poa_time'],
		 $array['created_date'],
         $array['modified_date']
      ))->execute();
      $seg_results     = DB::insert('wkout_seq', array(
         'parent_folder_id',
         'folder_id',
         'wkout_id',
         'seq_order',
         'site_id',
         'user_id',
         'created_date',
         'modified_date'
      ))->values(array(
         $array['parent_folder_id'],
         '0',
         $workout_results[0],
         $array['seq_order'],
         $array['site_id'],
         $array['user_id'],
         $array['created_date'],
         $array['modified_date']
      ))->execute();
      $this->updateWkoutSeqOrderOtherthanFirstSeq($array['parent_folder_id'], $seg_results[0], $array['user_id']);
      return $workout_results[0];
   }
   public function getAssignworkoutById($wkout_assign_id, $id_user = 0)
   {
      $user_access = $this->getUserRole();
      $sql         = "SELECT wkgd.*,g1.color_id, g1.color_title FROM wkout_assign_gendata AS wkgd LEFT JOIN wkout_color AS g1 ON wkgd.wkout_color=g1.color_id  WHERE wkgd.wkout_assign_id ='" . $wkout_assign_id . "' AND wkgd.status_id=1 AND " . (!empty($id_user) ? " wkgd.user_id='" . $id_user . "'" : 'wkgd.access_id=' . $user_access);
      $query       = DB::query(Database::SELECT, $sql);
      $list        = $query->execute()->as_array();
      return isset($list[0]) ? $list[0] : $list;
   }
	
	public function DefaultSampleExerciseSetsById($type, $userId, $xrsetId, $wkoutId, $moveAction = '', $default_status = '')
   {
		$sql = "UPDATE wkout_sample_gendata SET default_status=1 WHERE  wkout_sample_id= " . $wkoutId . "";
		$res = DB::query(Database::UPDATE, $sql)->execute();
		$sqlseq = "UPDATE wkout_sample_seq SET default_status=1 WHERE  wkout_sample_id= " . $wkoutId . "";
		$res = DB::query(Database::UPDATE, $sqlseq)->execute();
	
	}
	
   public function doCopyForExerciseSetsById($type, $userId, $xrsetId, $wkoutId, $moveAction = '', $default_status = '')
   {
		//echo "$type---TEst";echo "<hr>"; //die;
		
      $user_access = $this->getUserRole();
      $site_id     = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '1');
		$datetime = Helper_Common::get_default_datetime();
		if ($type == 'exerciseSet') {
			$exerciseSet       = $this->getExerciseSetAllDetailsByWorkout($wkoutId, $xrsetId, $userId);
         $upFlagUpdateOrder = false;
         if (empty($moveAction))
            $getGoalOrder = $this->getMaxGoalorder($wkoutId, $userId);
         else {
            $currOrder = $this->getCurrentGoalorder($wkoutId, $xrsetId, $userId);
            //down insert last, up insert befor curr record
            if ($moveAction == 'down')
               $getGoalOrder = $currOrder + 1;
            else {
               $getGoalOrder      = (($currOrder > 1) ? $currOrder - 1 : '1');
               $upFlagUpdateOrder = true;
            }
         }
         if (is_array($exerciseSet)) {
            $goal_results         = DB::insert('goal_vars', array(
               'goal_iso',
               'goal_alt',
               'primary_time',
               'primary_dist',
               'primary_reps',
               'primary_resist',
               'primary_rate',
               'primary_angle',
               'primary_rest',
               'primary_int',
               'goal_time_hh',
               'goal_time_mm',
               'goal_time_ss',
               'goal_dist',
               'goal_dist_id',
               'goal_reps',
               'goal_resist',
               'goal_resist_id',
               'goal_rate',
               'goal_rate_id',
               'goal_angle',
               'goal_angle_id',
               'goal_int_id',
               'goal_rest_mm',
               'goal_rest_ss',
               'goal_remarks'
            ))->values(array(
               $exerciseSet['goal_iso'],
               $exerciseSet['goal_alt'],
               $exerciseSet['primary_time'],
               $exerciseSet['primary_dist'],
               $exerciseSet['primary_reps'],
               $exerciseSet['primary_resist'],
               $exerciseSet['primary_rate'],
               $exerciseSet['primary_angle'],
               $exerciseSet['primary_rest'],
               $exerciseSet['primary_int'],
               $exerciseSet['goal_time_hh'],
               $exerciseSet['goal_time_mm'],
               $exerciseSet['goal_time_ss'],
               $exerciseSet['goal_dist'],
               $exerciseSet['goal_dist_id'],
               $exerciseSet['goal_reps'],
               $exerciseSet['goal_resist'],
               $exerciseSet['goal_resist_id'],
               $exerciseSet['goal_rate'],
               $exerciseSet['goal_rate_id'],
               $exerciseSet['goal_angle'],
               $exerciseSet['goal_angle_id'],
               $exerciseSet['goal_int_id'],
               $exerciseSet['goal_rest_mm'],
               $exerciseSet['goal_rest_ss'],
               $exerciseSet['goal_remarks']
            ))->execute();
            $goal_gendata_results = DB::insert('goal_gendata', array(
               'wkout_id',
               'goal_unit_id',
               'goal_group',
               'goal_title',
               'goal_title_self',
               'goal_order',
               'user_id',
               'status_id'
            ))->values(array(
               $wkoutId,
               $exerciseSet['goal_unit_id'],
               $exerciseSet['goal_group'],
               $exerciseSet['goal_title'],
               $exerciseSet['goal_title_self'],
               $getGoalOrder,
               $userId,
               '1'
            ))->execute();
            if ($upFlagUpdateOrder)
               $this->updateAllGoalorder($moveAction, $goal_gendata_results[0], $wkoutId);
         } else {
            return false;
         }
      }
		else if ($type == 'sampleToworkout' || $type == 'defaultToworkout') {
         $records          = $this->getsampleworkoutById('', $wkoutId);        
         $curSeqOrderArray = $this->getCurrentSeqorderBySampleWkoutId($wkoutId);
         $exerciseRecords  = $this->getExerciseSampleSet($wkoutId);
         if (is_array($records) && count($records) > 0) {
            $records['parent_folder_id'] = $curSeqOrderArray['parent_folder_id'];
            $records['created_date']     = $datetime;
            $records['modified_date']    = $datetime;
            $records['wkout_title']      = $records['wkout_title'];
            $records['site_id']          = $site_id;
            $records["user_id"]          = $userId;
            $currentWkseqOrder           = $curSeqOrderArray['seq_order'];
            if ($moveAction == 'down')
               $records['seq_order'] = $currentWkseqOrder + 1;
            else
               $records['seq_order'] = (($currentWkseqOrder > 1) ? $currentWkseqOrder - 1 : '1');
            $insertId = $this->insertWorkoutDetailsByseqOrder($records);
            if (is_array($exerciseRecords) && count($exerciseRecords) > 0) {
               foreach ($exerciseRecords as $keys => $values) {
                  if ($values['goal_title'] != 'Click_to_Edit') {
                     $goal_results         = DB::insert('goal_vars', array(
                        'goal_iso',
                        'goal_alt',
                        'primary_time',
                        'primary_dist',
                        'primary_reps',
                        'goal_time_hh',
                        'goal_time_mm',
                        'goal_time_ss',
                        'goal_dist',
                        'goal_dist_id',
                        'goal_reps',
                        'goal_resist',
                        'goal_resist_id',
                        'goal_rate',
                        'goal_rate_id',
                        'goal_angle',
                        'goal_angle_id',
                        'goal_int_id',
                        'goal_rest_mm',
                        'goal_rest_ss',
                        'goal_remarks'
                     ))->values(array(
                        $values['goal_iso'],
                        $values['goal_alt'],
                        $values['primary_time'],
                        $values['primary_dist'],
                        $values['primary_reps'],
                        $values['goal_time_hh'],
                        $values['goal_time_mm'],
                        $values['goal_time_ss'],
                        $values['goal_dist'],
                        $values['goal_dist_id'],
                        $values['goal_reps'],
                        $values['goal_resist'],
                        $values['goal_resist_id'],
                        $values['goal_rate'],
                        $values['goal_rate_id'],
                        $values['goal_angle'],
                        $values['goal_angle_id'],
                        $values['goal_int_id'],
                        $values['goal_rest_mm'],
                        $values['goal_rest_ss'],
                        $values['goal_remarks']
                     ))->execute();
                     $goal_gendata_results = DB::insert('goal_gendata', array(
                        'wkout_id',
                        'goal_unit_id',
                        'goal_group',
                        'goal_title',
                        'goal_title_self',
                        'goal_order',
                        'user_id',
                        'status_id'
                     ))->values(array(
                        $insertId,
                        $values["goal_unit_id"],
                        $values['goal_group'],
                        $values['goal_title'],
                        $values['goal_title_self'],
                        $values['goal_order'],
                        $userId,
                        '1'
                     ))->execute();
                  }
               }
            }
         }
         return $insertId;
      }
		else if ($type == 'sampleToDefault') {
		 $user_access	   = $this->getUserRole();
         $records          = $this->getsampleworkoutById('', $wkoutId);
         $curSeqOrderArray = $this->getCurrentSeqorderBySampleWkoutId($wkoutId);
         $exerciseRecords  = $this->getExerciseSampleSet($wkoutId);
         if (is_array($records) && count($records) > 0) {
            $records['parent_folder_id'] = $curSeqOrderArray['parent_folder_id'];
            $records['created_date']     = $datetime;
            $records['modified_date']    = $datetime;
            $records['wkout_title']      = $records['wkout_title'];
            $currentWkseqOrder           = $curSeqOrderArray['seq_order'];
            $records["from_wkout"]       = 2;
            $records["user_id"]          = $userId;
			$records["access_id"]        = $user_access;
			$records["default_status"]   = $default_status;
            $records["site_id"]          = $site_id;
            if ($moveAction == 'down')
               $records['seq_order'] = $currentWkseqOrder + 1;
            else
               $records['seq_order'] = (($currentWkseqOrder > 1) ? $currentWkseqOrder - 1 : '1');
            //print_r($records); exit;
            $insertId = $this->insertSampleWorkoutDetailsByseqOrder($records);
            if (is_array($exerciseRecords) && count($exerciseRecords) > 0) {
               foreach ($exerciseRecords as $keys => $values) {
                  if ($values['goal_title'] != 'Click_to_Edit') {
                     $goal_results         = DB::insert('wkout_sample_goal_vars', array(
                        'goal_iso',
                        'goal_alt',
                        'primary_time',
                        'primary_dist',
                        'primary_reps',
                        'goal_time_hh',
                        'goal_time_mm',
                        'goal_time_ss',
                        'goal_dist',
                        'goal_dist_id',
                        'goal_reps',
                        'goal_resist',
                        'goal_resist_id',
                        'goal_rate',
                        'goal_rate_id',
                        'goal_angle',
                        'goal_angle_id',
                        'goal_int_id',
                        'goal_rest_mm',
                        'goal_rest_ss',
                        'goal_remarks'
                     ))->values(array(
                        $values['goal_iso'],
                        $values['goal_alt'],
                        $values['primary_time'],
                        $values['primary_dist'],
                        $values['primary_reps'],
                        $values['goal_time_hh'],
                        $values['goal_time_mm'],
                        $values['goal_time_ss'],
                        $values['goal_dist'],
                        $values['goal_dist_id'],
                        $values['goal_reps'],
                        $values['goal_resist'],
                        $values['goal_resist_id'],
                        $values['goal_rate'],
                        $values['goal_rate_id'],
                        $values['goal_angle'],
                        $values['goal_angle_id'],
                        $values['goal_int_id'],
                        $values['goal_rest_mm'],
                        $values['goal_rest_ss'],
                        $values['goal_remarks']
                     ))->execute();
                     $goal_gendata_results = DB::insert('wkout_sample_goal_gendata', array(
                        'wkout_sample_id',
                        'goal_unit_id',
                        'goal_group',
                        'goal_title',
                        'goal_title_self',
                        'goal_order',
                        'status_id'
                     ))->values(array(
                        $insertId,
                        $values["goal_unit_id"],
                        $values['goal_group'],
                        $values['goal_title'],
                        $values['goal_title_self'],
                        $values['goal_order'],
                        '1'
                     ))->execute();
                  }
               }
            }
         }
         return $insertId;
      }
		else if ($type == 'workout') {
			$user_access	   = $this->getUserRole();
         $records          = $this->getworkoutById($userId, $wkoutId);
         $curSeqOrderArray = $this->getCurrentSeqorderByWkoutId($wkoutId);
         $exerciseRecords  = $this->getExerciseSet($wkoutId);
         if (is_array($records) && count($records) > 0) {
            $records['parent_folder_id'] = $curSeqOrderArray['parent_folder_id'];
            $records['created_date']     = $datetime;
            $records['modified_date']    = $datetime;
            $records['wkout_title']      = $records['wkout_title'];
            $records['site_id']          = $site_id;
			$records["access_id"]        = $user_access;
            $records["user_id"]          = $userId;
            $currentWkseqOrder           = $curSeqOrderArray['seq_order'];
            if ($moveAction == 'down')
               $records['seq_order'] = $currentWkseqOrder + 1;
            else
               $records['seq_order'] = (($currentWkseqOrder > 1) ? $currentWkseqOrder - 1 : '1');
            $insertId = $this->insertWorkoutDetailsByseqOrder($records);
            if (is_array($exerciseRecords) && count($exerciseRecords) > 0) {
               foreach ($exerciseRecords as $keys => $values) {
                  if ($values['goal_title'] != 'Click_to_Edit') {
                     $goal_results         = DB::insert('goal_vars', array(
                        'goal_iso',
                        'goal_alt',
                        'primary_time',
                        'primary_dist',
                        'primary_reps',
                        'goal_time_hh',
                        'goal_time_mm',
                        'goal_time_ss',
                        'goal_dist',
                        'goal_dist_id',
                        'goal_reps',
                        'goal_resist',
                        'goal_resist_id',
                        'goal_rate',
                        'goal_rate_id',
                        'goal_angle',
                        'goal_angle_id',
                        'goal_int_id',
                        'goal_rest_mm',
                        'goal_rest_ss',
                        'goal_remarks'
                     ))->values(array(
                        $values['goal_iso'],
                        $values['goal_alt'],
                        $values['primary_time'],
                        $values['primary_dist'],
                        $values['primary_reps'],
                        $values['goal_time_hh'],
                        $values['goal_time_mm'],
                        $values['goal_time_ss'],
                        $values['goal_dist'],
                        $values['goal_dist_id'],
                        $values['goal_reps'],
                        $values['goal_resist'],
                        $values['goal_resist_id'],
                        $values['goal_rate'],
                        $values['goal_rate_id'],
                        $values['goal_angle'],
                        $values['goal_angle_id'],
                        $values['goal_int_id'],
                        $values['goal_rest_mm'],
                        $values['goal_rest_ss'],
                        $values['goal_remarks']
                     ))->execute();
                     $goal_gendata_results = DB::insert('goal_gendata', array(
                        'wkout_id',
                        'goal_unit_id',
                        'goal_group',
                        'goal_title',
                        'goal_title_self',
                        'goal_order',
                        'user_id',
                        'status_id'
                     ))->values(array(
                        $insertId,
                        $values["goal_unit_id"],
                        $values['goal_group'],
                        $values['goal_title'],
                        $values['goal_title_self'],
                        $values['goal_order'],
                        $userId,
                        '1'
                     ))->execute();
                  }
               }
            }
         }
         return $insertId;
      }
		else if ($type == 'shared') {
			$user_access	   = $this->getUserRole();
         $records          = $this->getShareworkoutById($userId, $wkoutId);
         $curSeqOrderArray = $this->getCurrentSeqorderByShareWkoutId($wkoutId);
         $exerciseRecords  = $this->getExerciseSetShare($wkoutId);
         if (is_array($records) && count($records) > 0) {
            $records['parent_folder_id'] = $curSeqOrderArray['parent_folder_id'];
            $records['created_date']     = $datetime;
            $records['modified_date']    = $datetime;
            $records['wkout_title']      = $records['wkout_title'];
            $records['site_id']          = $site_id;
			$records["access_id"]        = $user_access;
            $records["user_id"]          = $userId;
            $currentWkseqOrder           = $curSeqOrderArray['seq_order'];
            if ($moveAction == 'down')
               $records['seq_order'] = $currentWkseqOrder + 1;
            else
               $records['seq_order'] = (($currentWkseqOrder > 1) ? $currentWkseqOrder - 1 : '1');

            $insertId = $this->insertWorkoutDetailsByseqOrder($records);
            if (is_array($exerciseRecords) && count($exerciseRecords) > 0) {
               foreach ($exerciseRecords as $keys => $values) {
                  if ($values['goal_title'] != 'Click_to_Edit') {
                     $goal_results         = DB::insert('goal_vars', array(
                        'goal_iso',
                        'goal_alt',
                        'primary_time',
                        'primary_dist',
                        'primary_reps',
                        'goal_time_hh',
                        'goal_time_mm',
                        'goal_time_ss',
                        'goal_dist',
                        'goal_dist_id',
                        'goal_reps',
                        'goal_resist',
                        'goal_resist_id',
                        'goal_rate',
                        'goal_rate_id',
                        'goal_angle',
                        'goal_angle_id',
                        'goal_int_id',
                        'goal_rest_mm',
                        'goal_rest_ss',
                        'goal_remarks'
                     ))->values(array(
                        $values['goal_iso'],
                        $values['goal_alt'],
                        $values['primary_time'],
                        $values['primary_dist'],
                        $values['primary_reps'],
                        $values['goal_time_hh'],
                        $values['goal_time_mm'],
                        $values['goal_time_ss'],
                        $values['goal_dist'],
                        $values['goal_dist_id'],
                        $values['goal_reps'],
                        $values['goal_resist'],
                        $values['goal_resist_id'],
                        $values['goal_rate'],
                        $values['goal_rate_id'],
                        $values['goal_angle'],
                        $values['goal_angle_id'],
                        $values['goal_int_id'],
                        $values['goal_rest_mm'],
                        $values['goal_rest_ss'],
                        $values['goal_remarks']
                     ))->execute();
                     $goal_gendata_results = DB::insert('goal_gendata', array(
                        'wkout_id',
                        'goal_unit_id',
                        'goal_group',
                        'goal_title',
                        'goal_title_self',
                        'goal_order',
                        'user_id',
                        'status_id'
                     ))->values(array(
                        $insertId,
                        $values["goal_unit_id"],
                        $values['goal_group'],
                        $values['goal_title'],
                        $values['goal_title_self'],
                        $values['goal_order'],
                        $userId,
                        '1'
                     ))->execute();
                  }
               }
            }
         }
         return $insertId;
      }
		else if ($type == 'sample workout') {
		 $user_access	   = $this->getUserRole();
         $records          = $this->getsampleworkoutById('', $wkoutId);
         $curSeqOrderArray = $this->getCurrentSeqorderBySampleWkoutId($wkoutId);
         $exerciseRecords  = $this->getExerciseSampleSet($wkoutId);
         if (is_array($records) && count($records) > 0) {
            $records['parent_folder_id'] = $curSeqOrderArray['parent_folder_id'];
            $records['created_date']     = $datetime;
            $records['modified_date']    = $datetime;
            $records['wkout_title']      = $records['wkout_title'];
            $currentWkseqOrder           = $curSeqOrderArray['seq_order'];
            $records["from_wkout"]       = 2;
			$records["access_id"]        = $user_access;
            $records["user_id"]          = $userId;
            $records["site_id"]          = $site_id;
            $records["default_status"]   = $default_status;
            if ($moveAction == 'down')
               $records['seq_order'] = $currentWkseqOrder + 1;
            else
               $records['seq_order'] = (($currentWkseqOrder > 1) ? $currentWkseqOrder - 1 : '1');
            $insertId = $this->insertSampleWorkoutDetailsByseqOrder($records);
			if($default_status && $insertId){
				$sqlupdate = "Update wkout_sample_gendata set default_wkout_id = '".$insertId."' Where wkout_sample_id ='" . $wkoutId . "'";
				DB::query(Database::UPDATE, $sqlupdate)->execute();
			}
            if (is_array($exerciseRecords) && count($exerciseRecords) > 0) {
               foreach ($exerciseRecords as $keys => $values) {
                  if ($values['goal_title'] != 'Click_to_Edit') {
                     $goal_results         = DB::insert('wkout_sample_goal_vars', array(
                        'goal_iso',
                        'goal_alt',
                        'primary_time',
                        'primary_dist',
                        'primary_reps',
                        'goal_time_hh',
                        'goal_time_mm',
                        'goal_time_ss',
                        'goal_dist',
                        'goal_dist_id',
                        'goal_reps',
                        'goal_resist',
                        'goal_resist_id',
                        'goal_rate',
                        'goal_rate_id',
                        'goal_angle',
                        'goal_angle_id',
                        'goal_int_id',
                        'goal_rest_mm',
                        'goal_rest_ss',
                        'goal_remarks'
                     ))->values(array(
                        $values['goal_iso'],
                        $values['goal_alt'],
                        $values['primary_time'],
                        $values['primary_dist'],
                        $values['primary_reps'],
                        $values['goal_time_hh'],
                        $values['goal_time_mm'],
                        $values['goal_time_ss'],
                        $values['goal_dist'],
                        $values['goal_dist_id'],
                        $values['goal_reps'],
                        $values['goal_resist'],
                        $values['goal_resist_id'],
                        $values['goal_rate'],
                        $values['goal_rate_id'],
                        $values['goal_angle'],
                        $values['goal_angle_id'],
                        $values['goal_int_id'],
                        $values['goal_rest_mm'],
                        $values['goal_rest_ss'],
                        $values['goal_remarks']
                     ))->execute();
                     $goal_gendata_results = DB::insert('wkout_sample_goal_gendata', array(
                        'wkout_sample_id',
                        'goal_unit_id',
                        'goal_group',
                        'goal_title',
                        'goal_title_self',
                        'goal_order',
                        'status_id'
                     ))->values(array(
                        $insertId,
                        $values["goal_unit_id"],
                        $values['goal_group'],
                        $values['goal_title'],
                        $values['goal_title_self'],
                        $values['goal_order'],
                        '1'
                     ))->execute();
                  }
               }
            }
         }
         return $insertId;
      }
		return true;
   }
   public function getWorkoutDetails($workoutTitle, $limit = 0)
   {
      $sql   = "SELECT wkgd.wkout_id, wkgd.wkout_color, wc.color_title, wkgd.wkout_title, wkf.folder_title   FROM `wkout_gendata` AS wkgd JOIN wkout_color AS wc ON wkgd.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON wkgd.wkout_focus=wf.focus_grp_id JOIN unit_status AS uns ON (wkgd.status_id=uns.status_id AND wkgd.status_id !=4) JOIN roles AS ua ON wkgd.access_id=ua.id  LEFT JOIN wkout_seq as wks ON (wkgd.wkout_id=wks.wkout_id and wks.parent_folder_id=0) LEFT JOIN wkout_folders as wkf ON (wks.folder_id=wkf.id AND wkf.folder_status=0)  WHERE wkgd.status_id=1 " . (!empty($workoutTitle) ? ' AND wkgd.wkout_title like "' . $workoutTitle . '%"' : '') . " Order by wkgd.wkout_id asc " . (!empty($limit) ? ' limit 5' : '');
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getSharedWorkoutDetailsByUser($id_user = 0, $folder_id = 0, $site_id = 0)
   {
      $sql = "SELECT
					wkgd.wkout_focus, wkgd.wkout_share_id as wkout_id, wkgd.wkout_color, wc.color_title, wkgd.wkout_title, wkf.folder_title
				FROM
					wkout_share_gendata AS wkgd					
					JOIN wkout_color AS wc ON wkgd.wkout_color=wc.color_id
					LEFT JOIN wkout_focus_grp AS wf ON wkgd.wkout_focus=wf.focus_grp_id
					JOIN unit_status AS uns ON (wkgd.status_id=uns.status_id AND wkgd.status_id !=4)
					JOIN roles AS ua ON wkgd.access_id=ua.id
					LEFT JOIN wkout_share_seq as wks ON (wkgd.wkout_share_id=wks.wkout_share_id )
					LEFT JOIN wkout_folders as wkf ON (wks.parent_folder_id=wkf.id AND wkf.folder_status=0)
					LEFT JOIN user_sites as us ON us.user_id = wks.shared_for
				WHERE wkgd.status_id=1 ";
      if ($site_id)
         $sql .= " AND us.site_id in ($site_id) ";
      $sql .= " group by wkgd.wkout_share_id order by wks.wkout_share_id desc";
      $query  = DB::query(Database::SELECT, $sql);
      $result = $query->execute()->as_array();
      return $result;
   }
   public function getWorkoutDetailsByUser($id_user = 0, $wkout_ids = 0, $site_id = 0, $status = 1, $order_by = '', $futured_val = '', $autosearch = '', $limitCurrent = '', $offset = '')
   {
      $limitStart = $offset;
      $sql        = "SELECT
				wkgd.wkout_focus, wkgd.wkout_id, wkgd.wkout_color, wc.color_title, wkgd.wkout_title, wkf.folder_title,wkgd.user_id,
				group_concat(tag.tag_title SEPARATOR '@@') as tagdetails 
			FROM
				wkout_gendata AS wkgd					
				LEFT JOIN wkout_color AS wc ON wkgd.wkout_color=wc.color_id
				LEFT JOIN wkout_focus_grp AS wf ON wkgd.wkout_focus=wf.focus_grp_id
				JOIN unit_status AS uns ON (wkgd.status_id=uns.status_id)
				JOIN roles AS ua ON wkgd.access_id=ua.id
				LEFT JOIN wkout_seq as wks ON (wkgd.wkout_id=wks.wkout_id AND wks.wkout_seq_status=0)
				LEFT JOIN wkout_folders as wkf ON (wks.parent_folder_id=wkf.id AND wkf.folder_status=0)
				LEFT OUTER JOIN wkout_tags on wkout_tags.wkout_id = wkgd.wkout_id
				LEFT OUTER JOIN tag on tag.tag_id = wkout_tags.tag_id
			WHERE wkgd.status_id in ($status) ";
      if ($id_user) {
         $sql .= " AND wkgd.user_id in ($id_user) ";
      }
      if ($site_id)
         $sql .= " AND wkgd.site_id in ($site_id) ";
      if ($futured_val == 1) {
         $sql .= " AND wkgd.featured =$futured_val";
      }
      if ($autosearch != '') {
         $sql .= " and wkgd.wkout_title LIKE '%" . $autosearch . "%' ";
      }
	  if(isset($_GET["wsid"]) && !empty($_GET["wsid"]) && $_GET["wsid"]){
		 $sql .= " AND wkgd.wkout_id in (".$_GET["wsid"].")";
	  }else if(!empty($wkout_ids)){
		 $sql .= " AND wkgd.wkout_id in (".$wkout_ids.")"; 
	  }
      $sql .= " group by wkgd.wkout_id";
      if ($order_by == 1) {
         $sql .= ' order by wkgd.wkout_title asc';
      } elseif ($order_by == 2) {
         $sql .= ' order by wkgd.wkout_title desc';
      } elseif ($order_by == 3) {
         $sql .= ' order by wkgd.date_created desc';
      } elseif ($order_by == 4) {
         $sql .= ' order by wkgd.date_modified desc';
      } else {
         $sql .= ' order by wkgd.date_created desc';
      }
      if ($limitCurrent != '') {
         $sql .= " LIMIT " . $limitStart . " , " . $limitCurrent . "";
      }
      $query  = DB::query(Database::SELECT, $sql);
      $result = $query->execute()->as_array();
      return $result;
   }
   
   public function deleteWorkoutDetails($parent_folder_id, $wkout_id)
   {
	   
	$sql_1 = "UPDATE wkout_gendata SET status_id= 4 WHERE wkout_id IN (".$wkout_id.")";
	DB::query(Database::UPDATE, $sql_1)->execute();
	$sql_2 = "UPDATE wkout_seq SET wkout_seq_status=1 WHERE wkout_id IN (".$wkout_id.")";
	DB::query(Database::UPDATE, $sql_2)->execute(); 
	   /*
      $query = DB::update('wkout_gendata')->set(array(
         'status_id' => 4
      ))->where('wkout_id', '=', $wkout_id)->execute();
      $query = DB::update('wkout_seq')->set(array(
         'wkout_seq_status' => 1
      ))->where('wkout_id', '=', $wkout_id)->execute();*/
	  
      $sql   = "Update wkout_seq set seq_order = seq_order-1 Where parent_folder_id ='" . $parent_folder_id . "' AND wkout_seq_status=0";
      return DB::query(Database::UPDATE, $sql)->execute();
      return $query[0];
   }
   public function deleteSampleWorkoutDetails($parent_folder_id,$parent_folder, $wkout_id,$default_status = 0)
   { //print_r($parent_folder_id );
		//if(is_array($parent_folder_id)) {echo " This is array";} else{echo "This is not array";}
	//echo count($parent_folder_id ); //die;
	if($default_status == 0){
		$sql_1 = "UPDATE wkout_sample_gendata SET status_id= 4 WHERE wkout_sample_id IN (".$wkout_id.")";
		DB::query(Database::UPDATE, $sql_1)->execute();
		$sql_2 = "UPDATE wkout_sample_seq SET wkout_seq_status=1 WHERE wkout_sample_id in (".$wkout_id.")";
		DB::query(Database::UPDATE, $sql_2)->execute();
    }else{
		$sql_1 = "UPDATE wkout_sample_gendata SET status_id= 4 WHERE default_status= 1 AND wkout_sample_id IN (".$wkout_id.")";
		DB::query(Database::UPDATE, $sql_1)->execute();
		$sql_2 = "UPDATE wkout_sample_seq SET wkout_seq_status=1 WHERE default_status= 1 AND  wkout_sample_id in (".$wkout_id.")";
		DB::query(Database::UPDATE, $sql_2)->execute();
	}
	  /*$query = DB::update('wkout_sample_gendata')->set(array(
         'status_id' => 4
      ))->where('wkout_sample_id', 'IN', array($wkout_id))->execute();
	  
      $query = DB::update('wkout_sample_seq')->set(array(
         'wkout_seq_status' => 1
      ))->where('wkout_sample_id', 'IN', array($wkout_id) )->execute(); */

	//Delete Folder : Start
		$workoutmodel = ORM::factory('admin_workouts');
		
		if(is_array($parent_folder_id)){
			if( count($parent_folder_id) > 0){
				for($i=0;$i<count($parent_folder_id);$i++){
					if( $parent_folder_id[$i] >0 ){
						$workoutmodel->delete_folder( $parent_folder_id[$i] );
					}
				}
			}
		}else{
			$workoutmodel->delete_folder( $parent_folder_id );
		}
	//Delete Folder : End

	$sql   = "Update wkout_sample_seq set seq_order = seq_order-1 Where parent_folder_id ='" . $parent_folder . "' AND wkout_seq_status=0";	  
   // return DB::query(Database::UPDATE, $sql)->execute();
   // return $query[0];
   }
	
	public function deleteSharedWorkoutDetails($parent_folder_id, $wkout_id)
   {
	   $sql_1 = "UPDATE wkout_share_gendata SET status_id= 4 WHERE wkout_share_id IN (".$wkout_id.")";
		DB::query(Database::UPDATE, $sql_1)->execute();
		$sql_2 = "UPDATE wkout_share_seq SET wkout_seq_status=1 WHERE wkout_share_id IN (".$wkout_id.")";
		DB::query(Database::UPDATE, $sql_2)->execute(); 
			/*
			$query = DB::update('wkout_gendata')->set(array(
				'status_id' => 4
			))->where('wkout_id', '=', $wkout_id)->execute();
			$query = DB::update('wkout_seq')->set(array(
				'wkout_seq_status' => 1
			))->where('wkout_id', '=', $wkout_id)->execute();*/
		  
			$sql   = "Update wkout_share_seq set seq_order = seq_order-1 Where parent_folder_id ='" . $parent_folder_id . "' AND wkout_seq_status=0";
			return DB::query(Database::UPDATE, $sql)->execute();
			return $query[0];
   }

	public function delete_folder($parent_folder_id){
		if($parent_folder_id!=""){
			$sql_folder = "Select * from  wkout_sample_seq where wkout_seq_status = 0 and parent_folder_id in(".$parent_folder_id.")";
			//echo $sql_folder; //die;
			$query = DB::query(Database::SELECT, $sql_folder);
			$folder_list  = $query->execute()->as_array();
			//echo "count=".count($folder_list);
			if(count($folder_list) == 0 ){
				$sql_del_folder = "UPDATE wkout_sample_seq SET wkout_seq_status=1 WHERE folder_id =".$parent_folder_id;
				//echo $sql_del_folder;
				DB::query(Database::UPDATE, $sql_del_folder)->execute();
				$chk_parent = "SELECT parent_folder_id FROM wkout_sample_seq  where  wkout_seq_status = 0 and folder_id =".$parent_folder_id;
				$query = DB::query(Database::SELECT, $chk_parent);
				$parent_folder = $query->execute()->as_array();
				if( count($parent_folder) > 0){
					$new_parent_folder_id = $parent_folder[0]['parent_folder_id'];
					if($new_parent_folder_id >0){
						delete_folder($parent_folder_id);
					}else{
						return true;
					}
				}else{
					return true;
				}
			}
			}
	}
	/*
		1 -> 2
		
	*/
	
   public function get_tags_user($tags)
   {
      $sql   = "SELECT user_id FROM user_tags WHERE tag_id in(" . $tags . ") group by user_id";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_user_created_tags($userid, $tag_catid)
   {
      $sql   = "SELECT * FROM tag WHERE tag_cat_id = '" . $tag_catid . "' AND created_by = '" . $userid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_user_tags($userid)
   {
      $sql   = "SELECT tag_id FROM user_tags WHERE user_id = '" . $userid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_wkout_tags($wkoutid, $siteid)
   {
      if (is_array($wkoutid) && count($wkoutid) > 1) {
         $cnt     = count($wkoutid);
         $wkoutid = implode(',', $wkoutid);
         $sql     = "SELECT wt.tag_id,t.tag_title, count(*) as cnt FROM wkout_tags as wt join tag as t WHERE t.tag_id=wt.tag_id and wt.wkout_id in (" . $wkoutid . ") and wt.site_id=$siteid group by wt.tag_id having cnt = $cnt";
      } else {
         $wkoutid = (is_array($wkoutid)) ? implode(',', $wkoutid) : $wkoutid;
         $sql     = "SELECT wt.tag_id,t.tag_title FROM wkout_tags as wt join tag as t WHERE t.tag_id=wt.tag_id and wt.wkout_id in (" . $wkoutid . ") and wt.site_id=$siteid";
      }
      //echo $sql."<br><br><br>";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_listwkout_tags($wkoutid)
   {
      $wkoutid = (is_array($wkoutid)) ? implode(',', $wkoutid) : $wkoutid;
      $sql     = "SELECT wt.wkout_id,group_concat(t.tag_title) as tag_title FROM wkout_tags as wt join tag as t WHERE t.tag_id=wt.tag_id and wt.wkout_id in (" . $wkoutid . ") group by wt.wkout_id";
      //echo $sql."<br><br><br>";
      $query   = DB::query(Database::SELECT, $sql);
      $list    = $query->execute()->as_array();
      return $list;
   }
   public function add_wkout_tag($tagid, $wkoutid, $siteid)
   {
      if (is_array($wkoutid)) {
         foreach ($wkoutid as $k => $v) {
            $id[] = DB::insert('wkout_tags', array(
               'tag_id',
               'wkout_id',
               'site_id'
            ))->values(array(
               $tagid,
               $v,
               $siteid
            ))->execute();
         }
      } else {
         list($id) = DB::insert('wkout_tags', array(
            'tag_id',
            'wkout_id',
            'site_id'
         ))->values(array(
            $tagid,
            $wkoutid,
            $siteid
         ))->execute();
      }
      return $id;
   }
   public function add_tag($data)
   {
	  $datetime = Helper_Common::get_default_datetime();
      $values = array(
         $data['tag_title'],
         $this->random_color(),
         $data['tag_cat_id'],
         $data['created_by'],
         $datetime
      );
      list($id) = DB::insert('tag', array(
         'tag_title',
         'tag_color',
         'tag_cat_id',
         'created_by',
         'created'
      ))->values($values)->execute();
      return $id;
   }
   public function random_color_part()
   {
      return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
   }
   public function random_color()
   {
      return "#" . $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
   }
   public function delete_wkout_tag($wkoutid, $tagid)
   {
      //$sql = "DELETE FROM wkout_tags WHERE tag_id = ".$tagid." and wkout_id in (".$wkoutid.")"; //echo $sql;
      $wkoutid = (is_array($wkoutid)) ? implode(',', $wkoutid) : $wkoutid;
      $sql     = "DELETE FROM wkout_tags WHERE wkout_id in (" . $wkoutid . ") ";
      if ($tagid) {
         $sql .= " and tag_id = $tagid";
      }
      $query = DB::query(Database::DELETE, $sql);
      return $query->execute();
   }
   public function getColors()
   {
      $sql   = "SELECT color_id, color_title from wkout_color";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getunitsbytable($tableName)
   {
      $sql   = "SELECT * from $tableName";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getInnerDrive()
   {
      $sql   = "SELECT si.*,sig.int_grp_title FROM set_int si LEFT JOIN set_int_grp sig ON si.int_grp_id = sig.int_grp_id ORDER BY si.int_grp_id, si.int_opt_id";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getSampleWorkoutDetails($default, $id_user = 0, $wkout_ids = 0, $site_id = '', $userids = '', $status = 1, $order_by = '', $futured_val = '', $autosearch = '', $limitCurrent = '', $offset = '')
   {
      $sql = "SELECT wkgd.wkout_focus, wkgd.wkout_sample_id as wkout_id, wkgd.wkout_color, wc.color_title, wkgd.wkout_title, wkf.folder_title,wkf.id as folder_id,wkgd.default_status FROM wkout_sample_gendata AS wkgd Left JOIN wkout_color AS wc ON wkgd.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON wkgd.wkout_focus=wf.focus_grp_id JOIN unit_status AS uns ON (wkgd.status_id=uns.status_id) JOIN roles AS ua ON wkgd.access_id=ua.id JOIN wkout_sample_seq as wks ON (wkgd.wkout_sample_id=wks.wkout_sample_id AND wks.wkout_seq_status=0) LEFT JOIN wkout_folders as wkf ON (wks.parent_folder_id=wkf.id AND wkf.folder_status=0) LEFT JOIN sites s ON wks.site_id=s.id ".(($default =='0' || $default=='all') && Helper_Common::hasAccessBySampleWkouts($site_id) ? "LEFT JOIN default_site_mod_map as ds ON (ds.record_id = wkgd.wkout_sample_id AND ds.record_type_id=1 AND ds.site_id in ($site_id)) " : '')."  WHERE wkgd.status_id in ($status) AND s.is_deleted = 0";
	  if(Helper_Common::hasAccessBySampleWkouts($site_id) && Helper_Common::is_admin()){
		if(!empty($default))
			$sql .= " AND s.sample_workouts=1 AND wkgd.default_status=$default ";
		if ($site_id && empty($default))
			$sql .= " AND s.sample_workouts=1 AND ((wkgd.site_id in ($site_id) AND wkgd.default_status='0') OR (wkgd.default_status='1' AND (ds.record_mod_action!=2 OR ds.id is NULL)))";	
	  }else if($default == '1' && Helper_Common::is_admin() && Helper_Common::hasAccessBySampleWkouts($site_id) != true){
		$sql .= " AND wkgd.default_status=1 ";
	  }else if(empty($default) && Helper_Common::is_admin() && Helper_Common::hasAccessBySampleWkouts($site_id) != true){
		$sql .= " AND wkgd.site_id in ($site_id) AND wkgd.default_status=0 ";
	  }else{
		if($default=='all' && !empty($default))
			$sql .= " AND ((wkgd.site_id in ($site_id) AND wkgd.default_status='0') OR (wkgd.default_status='1' AND (ds.record_mod_action!=2 OR ds.id is NULL)))";
		else
			$sql .= " AND wkgd.site_id in ($site_id) AND wkgd.default_status=0 ";
	  }
	  if(isset($_GET["wsid"]) && !empty($_GET["wsid"]) && $_GET["wsid"]){
			$sql .= " AND wkgd.wkout_sample_id in (".$_GET["wsid"].")";
	  }else if(!empty($wkout_ids)){
		 $sql .= " AND wkgd.wkout_sample_id in (".$wkout_ids.")"; 
	  }
		
      //if ($userids)
         //$sql .= " AND wkgd.user_id in ($userids)";
			
      if ($futured_val == 1) {
         $sql .= " AND wkgd.featured =$futured_val";
      }
      if ($autosearch != '') {
         $sql .= " and wkgd.wkout_title LIKE '%" . $autosearch . "%' ";
      }
      if ($order_by == 1) {
         $sql .= ' order by wkgd.wkout_title asc';
      } elseif ($order_by == 2) {
         $sql .= ' order by wkgd.wkout_title desc';
      } elseif ($order_by == 3) {
         $sql .= ' order by wkgd.date_created desc';
      } elseif ($order_by == 4) {
         $sql .= ' order by wkgd.date_modified desc';
      } else {
         $sql .= ' order by wkgd.date_created desc';
      }
      if ($limitCurrent != '') {
         $limitStart = $offset;
         $sql .= " LIMIT " . $limitStart . " , " . $limitCurrent . "";
      }
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
	public function getSharedWorkoutDetails($id_user = 0, $wkout_ids = 0, $site_id = '', $userids = '', $status = 1, $order_by = '', $futured_val = '', $autosearch = '', $limitCurrent = '', $offset = '')
   {
      $sql = "SELECT '' as default_status, wkgd.wkout_focus, wkgd.wkout_share_id as wkout_id, wkgd.wkout_color, wc.color_title, wkgd.wkout_title, wkf.folder_title,wkf.id as folder_id FROM wkout_share_gendata AS wkgd Left JOIN wkout_color AS wc ON wkgd.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON wkgd.wkout_focus=wf.focus_grp_id JOIN unit_status AS uns ON (wkgd.status_id=uns.status_id) JOIN roles AS ua ON wkgd.access_id=ua.id JOIN wkout_share_seq as wks ON (wkgd.wkout_share_id=wks.wkout_share_id AND wks.wkout_seq_status=0) LEFT JOIN wkout_folders as wkf ON (wks.parent_folder_id=wkf.id AND wkf.folder_status=0) LEFT JOIN sites s ON wks.site_id=s.id WHERE wkgd.status_id in ($status) AND s.is_deleted = 0 and wkgd.site_id=$site_id and wkgd.user_id=$id_user";
		
	  if(isset($_GET["wsid"]) && !empty($_GET["wsid"]) && $_GET["wsid"]){
		$sql .= " AND wkgd.wkout_share_id in (".$_GET["wsid"].")";
	  }else if(!empty($wkout_ids)){
		 $sql .= " AND wkgd.wkout_share_id in (".$wkout_ids.")"; 
	  }
      if ($futured_val == 1) {
         $sql .= " AND wkgd.featured =$futured_val";
      }
      if ($autosearch != '') {
         $sql .= " and wkgd.wkout_title LIKE '%" . $autosearch . "%' ";
      }
      if ($order_by == 1) {
         $sql .= ' order by wkgd.wkout_title asc';
      } elseif ($order_by == 2) {
         $sql .= ' order by wkgd.wkout_title desc';
      } elseif ($order_by == 3) {
         $sql .= ' order by wkgd.created desc';
      } elseif ($order_by == 4) {
         $sql .= ' order by wkgd.modified desc';
      } else {
         $sql .= ' order by wkgd.created desc';
      }
      if ($limitCurrent != '') {
         $limitStart = $offset;
         $sql .= " LIMIT " . $limitStart . " , " . $limitCurrent . "";
      }
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function updateSampleWkoutSeqOrder($parent_folder_id, $user_id)
   {
      $sql = "Update wkout_sample_seq set seq_order = seq_order+1 Where parent_folder_id ='" . $parent_folder_id . "' AND user_id ='" . $user_id . "' AND wkout_seq_status=0";
      return DB::query(Database::UPDATE, $sql)->execute();
   }
   public function insertSampleWorkoutDetails($array)
   {
      $workout_results = DB::insert('wkout_sample_gendata', array(
         'wkout_group',
         'wkout_title',
         'wkout_color',
         'wkout_order',
         'user_id',
         'status_id',
         'site_id',
         'access_id',
         'wkout_focus',
         'wkout_poa',
         'wkout_poa_time',
		 'date_created',
		 'date_modified'
      ))->values(array(
         $array['wkout_group'],
         $array['wkout_title'],
         $array['wkout_color'],
         $array['wkout_order'],
         $array['user_id'],
         $array['status_id'],
         $array['site_id'],
         $array['access_id'],
         $array['wkout_focus'],
         $array['wkout_poa'],
         $array['wkout_poa_time'],
		 $array['created_date'],
         $array['modified_date']
      ))->execute();
      $this->updateSampleWkoutSeqOrder($array['parent_folder_id'], $array['user_id']);
      $seg_results = DB::insert('wkout_sample_seq', array(
         'parent_folder_id',
         'folder_id',
         'wkout_sample_id',
         'seq_order',
         'site_id',
         'user_id',
         'created_date',
         'modified_date'
      ))->values(array(
         '0',
         '0',
         $workout_results[0],
         '1',
         $array['site_id'],
         $array['user_id'],
         $array['created_date'],
         $array['modified_date']
      ))->execute();
      return $workout_results[0];
   }
   public function addSampleWorkoutSetFromworkout($array, $keyvalue, $userId)
   {
      if ((isset($array['s_row_count']) || isset($array['s_row_count_xr'])) && isset($array['wkout_id'])) {
         //foreach ($array['s_tb_title'][$keyvalue] as $key => $value) {
         $timehh       = '';
         $timemm       = '';
         $timess       = '';
         $restmm       = '';
         $restss       = '';
         $distance     = '';
         $distanceid   = '';
         $repitation   = '';
         $resistance   = '';
         $resistanceid = '';
         $rate         = '';
         $rateid       = '';
         $angle        = '';
         $angleid      = '';
         $intdrive     = '';
         $remark       = '';
         $title        = '';
         $type         = '';
         if (isset($array['exercise_title_new'][$keyvalue]) && !empty($array['exercise_title_new'][$keyvalue])) {
            $title = $array['exercise_title_new'][$keyvalue];
         }
         $unit_id         = '0';
         $goal_title_self = '1';
         if (isset($array['exercise_unit_new'][$keyvalue])) {
            if ($array['exercise_unit_new'][$keyvalue] == '0')
               $goal_title_self = '1';
            else
               $goal_title_self = '0';
            $unit_id = $array['exercise_unit_new'][$keyvalue];
         }
         if (isset($array['exercise_time_new'][$keyvalue]) && !empty($array['exercise_time_new'][$keyvalue])) {
            $timesplit = explode(':', $array['exercise_time_new'][$keyvalue]);
            $timehh    = $timesplit[0];
            $timemm    = $timesplit[1];
            $timess    = $timesplit[2];
         }
         if (isset($array['exercise_rest_new'][$keyvalue]) && !empty($array['exercise_rest_new'][$keyvalue])) {
            $restsplit = explode(':', $array['exercise_rest_new'][$keyvalue]);
            $restmm    = $restsplit[0];
            $restss    = $restsplit[1];
         }
         if (isset($array['exercise_distance_new'][$keyvalue]) && !empty($array['exercise_distance_new'][$keyvalue])) {
            $distance = $array['exercise_distance_new'][$keyvalue];
         }
         if (isset($array['exercise_unit_distance_new'][$keyvalue]) && !empty($array['exercise_unit_distance_new'][$keyvalue])) {
            $distanceid = $array['exercise_unit_distance_new'][$keyvalue];
         }
         if (isset($array['exercise_repetitions_new'][$keyvalue]) && !empty($array['exercise_repetitions_new'][$keyvalue])) {
            $repitation = $array['exercise_repetitions_new'][$keyvalue];
         }
         if (isset($array['exercise_resistance_new'][$keyvalue]) && !empty($array['exercise_resistance_new'][$keyvalue])) {
            $resistance = $array['exercise_resistance_new'][$keyvalue];
         }
         if (isset($array['exercise_unit_resistance_new'][$keyvalue]) && !empty($array['exercise_unit_resistance_new'][$keyvalue])) {
            $resistanceid = $array['exercise_unit_resistance_new'][$keyvalue];
         }
         if (isset($array['exercise_rate_new'][$keyvalue]) && !empty($array['exercise_rate_new'][$keyvalue])) {
            $rate = $array['exercise_rate_new'][$keyvalue];
         }
         if (isset($array['exercise_unit_rate_new'][$keyvalue]) && !empty($array['exercise_unit_rate_new'][$keyvalue])) {
            $rateid = $array['exercise_unit_rate_new'][$keyvalue];
         }
         if (isset($array['exercise_angle_new'][$keyvalue]) && !empty($array['exercise_angle_new'][$keyvalue])) {
            $angle = $array['exercise_angle_new'][$keyvalue];
         }
         if (isset($array['exercise_unit_angle_new'][$keyvalue]) && !empty($array['exercise_unit_angle_new'][$keyvalue])) {
            $angleid = $array['exercise_unit_angle_new'][$keyvalue];
         }
         if (isset($array['exercise_innerdrive_new'][$keyvalue]) && !empty($array['exercise_innerdrive_new'][$keyvalue])) {
            $intdrive = $array['exercise_innerdrive_new'][$keyvalue];
         }
         if (isset($array['exercise_remark_new'][$keyvalue]) && !empty($array['exercise_remark_new'][$keyvalue])) {
            $remark = $array['exercise_remark_new'][$keyvalue];
         }
         $goal_results         = DB::insert('wkout_sample_goal_vars', array(
            'goal_iso',
            'goal_alt',
            'primary_time',
            'primary_dist',
            'primary_reps',
            'primary_resist',
            'primary_rate',
            'primary_angle',
            'primary_rest',
            'primary_int',
            'goal_time_hh',
            'goal_time_mm',
            'goal_time_ss',
            'goal_dist',
            'goal_dist_id',
            'goal_reps',
            'goal_resist',
            'goal_resist_id',
            'goal_rate',
            'goal_rate_id',
            'goal_angle',
            'goal_angle_id',
            'goal_int_id',
            'goal_rest_mm',
            'goal_rest_ss',
            'goal_remarks'
         ))->values(array(
            0,
            0,
            $array['primary_time_new'][$keyvalue],
            $array['primary_dist_new'][$keyvalue],
            $array['primary_reps_new'][$keyvalue],
            $array['primary_resist_new'][$keyvalue],
            $array['primary_rate_new'][$keyvalue],
            $array['primary_angle_new'][$keyvalue],
            $array['primary_rest_new'][$keyvalue],
            $array['primary_int_new'][$keyvalue],
            $timehh,
            $timemm,
            $timess,
            $distance,
            $distanceid,
            $repitation,
            $resistance,
            $resistanceid,
            $rate,
            $rateid,
            $angle,
            $angleid,
            $intdrive,
            $restmm,
            $restss,
            $remark
         ))->execute();
         $goal_gendata_results = DB::insert('wkout_sample_goal_gendata', array(
            'wkout_id',
            'goal_unit_id',
            'goal_group',
            'goal_title',
            'goal_title_self',
            'goal_order',
            'user_id',
            'status_id'
         ))->values(array(
            $array['wkout_id'],
            $unit_id,
            '0',
            $title,
            $goal_title_self,
            (!empty($array['goal_order_new'][$keyvalue]) ? $array['goal_order_new'][$keyvalue] : $keyvalue),
            $userId,
            '1'
         ))->execute();
         //}
         return $goal_gendata_results[0];
      }
   }
   /**************** Create Worout to Shared Workout ******************************* Starts Here******************/
   public function checkSharedWkout($wkout_id, $shared_by, $shared_for)
   {
      $sql   = "SELECT * FROM wkout_share_gendata where status_id=1 and wkout_id='$wkout_id' and  user_id = '$shared_for'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function createSharedWkout($type, $shared_by, $shared_for, $shared_message, $siteid, $wkoutId, $moveAction = '') 
   {
		$user_access      = $this->getUserRole();
		if($type=="myworkout"){
			$records          = $this->getworkoutById('', $wkoutId);
			$curSeqOrderArray = $this->getCurrentSeqorderByWkoutId($wkoutId);
			$exerciseRecords  = $this->getExerciseSet($wkoutId);
			$f_wkout = 0;
		}elseif($type=="sample-workout" || $type=="default-workout"){
			$records          = $this->getsampleworkoutById('', $wkoutId);
			$curSeqOrderArray = $this->getCurrentSeqorderBySampleWkoutId($wkoutId);
			$exerciseRecords  = $this->getExerciseSampleSet($wkoutId);
			$f_wkout = 2;
		}
		$datetime = Helper_Common::get_default_datetime();
		if (is_array($records) && count($records) > 0) {
         $records['parent_folder_id'] = 0;
         $records['created_date']     = $records['created'] = $datetime;
         $records['modified_date']    = $records['modified'] = $datetime;
         $records['wkout_title']      = $records['wkout_title'];
         $currentWkseqOrder           = ($curSeqOrderArray['seq_order']) ? $curSeqOrderArray['seq_order'] : 1;
         if ($moveAction == 'down') {
            $records['seq_order'] = $currentWkseqOrder + 1;
         } else {
            $records['seq_order'] = (($currentWkseqOrder > 1) ? $currentWkseqOrder - 1 : '1');
         }
			$records["from_wkout"]     = (isset($f_wkout))?$f_wkout:0;
         $records["wkout_id"]       = $wkoutId;
         $records["shared_by"]      = $shared_by;
         $records["shared_for"]     = $shared_for;
         $records["shared_message"] = $shared_message;
         $records["site_id"]        = $siteid;
         $records["access_id"]      = $user_access;
         /*//Block-------*/
         $insertId                  = $this->insertSharedWorkoutDetailsByseqOrder($records);
         if (is_array($exerciseRecords) && count($exerciseRecords) > 0) {
            foreach ($exerciseRecords as $keys => $values) {
               if ($values['goal_title'] != 'Click_to_Edit') {
                  $unit_results         = array(
                     0
                  );
                  $unit_results[0]      = $values["goal_unit_id"];
                  $goal_results         = DB::insert('wkout_share_goal_vars', array(
                     'goal_iso',
                     'goal_alt',
                     'primary_time',
                     'primary_dist',
                     'primary_reps',
                     'goal_time_hh',
                     'goal_time_mm',
                     'goal_time_ss',
                     'goal_dist',
                     'goal_dist_id',
                     'goal_reps',
                     'goal_resist',
                     'goal_resist_id',
                     'goal_rate',
                     'goal_rate_id',
                     'goal_angle',
                     'goal_angle_id',
                     'goal_int_id',
                     'goal_rest_mm',
                     'goal_rest_ss',
                     'goal_remarks'
                  ))->values(array(
                     $values['goal_iso'],
                     $values['goal_alt'],
                     $values['primary_time'],
                     $values['primary_dist'],
                     $values['primary_reps'],
                     $values['goal_time_hh'],
                     $values['goal_time_mm'],
                     $values['goal_time_ss'],
                     $values['goal_dist'],
                     $values['goal_dist_id'],
                     $values['goal_reps'],
                     $values['goal_resist'],
                     $values['goal_resist_id'],
                     $values['goal_rate'],
                     $values['goal_rate_id'],
                     $values['goal_angle'],
                     $values['goal_angle_id'],
                     $values['goal_int_id'],
                     $values['goal_rest_mm'],
                     $values['goal_rest_ss'],
                     $values['goal_remarks']
                  ));
                  /*//Block-------*/
                  $goal_results         = $goal_results->execute();
                  $goal_gendata_results = DB::insert('wkout_share_goal_gendata', array(
                     'wkout_share_id',
                     'goal_unit_id',
                     'goal_group',
                     'goal_title',
                     'goal_title_self',
                     'goal_order',
                     'user_id',
                     'status_id'
                  ))->values(array(
                     $insertId,
                     $unit_results[0],
                     $values['goal_group'],
                     $values['goal_title'],
                     $values['goal_title_self'],
                     $values['goal_order'],
                     $shared_for,
                     '1'
                  ));
                  /*//Block-------*/
                  $goal_gendata_results = $goal_gendata_results->execute();
               }
            }
         }
      }
      return $insertId;
   }
   public function insertSharedWorkoutDetailsByseqOrder($array)
   {
	   $datetime = Helper_Common::get_default_datetime();
      $workout_results = DB::insert('wkout_share_gendata', array(
         'wkout_group',
         'wkout_title',
         'wkout_color',
         'wkout_order',
         'status_id',
         'access_id',
         'wkout_focus',
         'wkout_poa',
         'wkout_poa_time',
         'wkout_id',
         'site_id',
         'user_id',
         'created',
         'modified',
         'modified_by',
			'from_wkout'
      ))->values(array(
         $array['wkout_group'],
         $array['wkout_title'],
         $array['wkout_color'],
         $array['wkout_order'],
         $array['status_id'],
         $array['access_id'],
         $array['wkout_focus'],
         $array['wkout_poa'],
         $array['wkout_poa_time'],
         $array['wkout_id'],
         $array['site_id'],
         $array['shared_for'],
         $datetime,
         $datetime,
         $array['shared_for'],
			$array['from_wkout']
      ));
      /*//Block-------*/
      $workout_results = $workout_results->execute();
      $seg_results     = DB::insert('wkout_share_seq', array(
         'parent_folder_id',
         'folder_id',
         'wkout_share_id',
         'seq_order',
         'shared_for',
         'site_id',
         'shared_by',
         'shared_msg',
         'created_date',
         'modified_date'
			
      ))->values(array(
         '0',
         '0',
         $workout_results[0],
         $array['seq_order'],
         $array['shared_for'],
         $array['site_id'],
         $array['shared_by'],
         $array['shared_message'],
         $datetime,
         $datetime
			
      ));
      /*//Block-------*/
      $seg_results     = $seg_results->execute();
      //if ($array['parent_folder_id'] != 0) {
         //$sres = $this->getShareSeq($array['parent_folder_id'], $array['shared_for'], $array['shared_by'], $array['shared_message'], $array['site_id'],$array['from_wkout']);
      //}
      //$this->updateSharedWkoutSeqOrderOtherthanFirstSeq($array['parent_folder_id'],  $seg_results[0], $array['shared_for']);
      return $workout_results[0];
   }
   public function getShareSeq($fid, $uid, $uidby, $msg, $site_id,$from_wkout)
   {
		if($from_wkout==0)
			$sql   = "SELECT * from wkout_seq as wkf Where wkf.folder_id ='" . $fid . "' AND wkout_seq_status=0";
		else if($from_wkout==2)
			$sql   = "SELECT * from wkout_sample_seq as wkf Where wkf.folder_id ='" . $fid . "' AND wkout_seq_status=0";
			
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
	  $datetime = Helper_Common::get_default_datetime();
      if ($list) {
         $list        = $list[0];
         //echo "$fid<br>";print_r($list);
         $seg_results = DB::insert('wkout_share_seq', array(
            'parent_folder_id',
            'folder_id',
            'wkout_share_id',
            'seq_order',
            'shared_for',
            'shared_by',
            'shared_msg',
            'site_id',
            'created_date',
            'modified_date'
         ))->values(array(
            $list['parent_folder_id'],
            $fid,
            0,
            $list['seq_order'],
            $uid,
            $uidby,
            $msg,
            $site_id,
            $datetime,
            $datetime
         ));
         //echo "<br>$seg_results<br>";
         $seg_results = $seg_results->execute();
         if ($list["parent_folder_id"] != 0) {
            $this->getShareSeq($list["parent_folder_id"], $uid, $uidby, $msg,$from_wkout);
         }
      }
   }
   public function getCurrentShareSeqorder($wkseqId)
   {
      $sql   = "SELECT seq_order from wkout_share_seq as wkf Where wkf.id ='" . $wkseqId . "' AND wkout_seq_status=0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return (isset($list[0]['seq_order']) ? $list[0]['seq_order'] : '0');
   }
   public function updateSharedWkoutSeqOrderOtherthanFirstSeq($parent_folder_id, $wkseq_ids, $user_id)
   {
      //echo "$parent_folder_id, $wkseq_ids, $user_id<br>";
      $currentseqorder = $this->getCurrentShareSeqorder($wkseq_ids);
      //print_r($currentseqorder);
      /*
      $currentseqorder = $this->getCurrentSeqorder($wkseq_ids);
      $sql             = "Update wkout_share_seq set seq_order = seq_order+1 Where parent_folder_id ='" . $parent_folder_id . "' AND user_id ='" . $user_id . "' AND wkout_seq_status=0 AND id not in (" . $wkseq_ids . ") and  seq_order >= " . $currentseqorder;
      return DB::query(Database::UPDATE, $sql)->execute();
      */
   }
   /**************** Create Worout to Shared Workout ******************************* Ends Here******************/
   /**************** Create Sample Worout from Normal & Shared Workout ******************************* Starts Here******************/
   public function checkSampleWkout($wkout_id, $method,$default=0)
   {
		$site_id     = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '1');
		if ($method == "mywkout"){
			 $sql = "SELECT * FROM wkout_sample_gendata where status_id=1 and wkout_id='$wkout_id' and from_wkout=0 AND site_id ='".$site_id."' AND default_status=".(($default)?$default:0);
			}elseif ($method == "mysharedwkout"){
			 $sql = "SELECT * FROM wkout_sample_gendata where status_id=1 and wkout_id='$wkout_id' and from_wkout=1 AND site_id ='".$site_id."' AND default_status=".(($default)?$default:0);
		  }
		$query = DB::query(Database::SELECT, $sql);
		$list  = $query->execute()->as_array();
		return (isset($list) && is_array($list) && count($list) > 0) ? 1 : 0;
   }
   public function createSampleWkout($type, $shared_by, $siteid, $wkoutId, $moveAction = '', $default)
   {
      $user_access = $this->getUserRole();
      $user_access = 2;
      if ($type == 'mywkout')
		{
         $records          = $this->getworkoutById('', $wkoutId);
         $curSeqOrderArray = $this->getCurrentSeqorderByWkoutId($wkoutId);
         $exerciseRecords  = $this->getExerciseSet($wkoutId);
		 $datetime = Helper_Common::get_default_datetime();
         if (is_array($records) && count($records) > 0) {
            $records['parent_folder_id'] = ($curSeqOrderArray['parent_folder_id']) ? $curSeqOrderArray['parent_folder_id'] : 0;
            $records['created_date']     = $datetime;
            $records['modified_date']    = $datetime;
            $records['wkout_title']      = $records['wkout_title'];
            $currentWkseqOrder           = ($curSeqOrderArray['seq_order']) ? $curSeqOrderArray['seq_order'] : 1;
            if ($moveAction == 'down') {
               $records['seq_order'] = $currentWkseqOrder + 1;
            } else {
               $records['seq_order'] = (($currentWkseqOrder > 1) ? $currentWkseqOrder - 1 : '1');
            }
            $records["wkout_id"]            = $wkoutId;
            $records["site_id"]             = $siteid;
            $records["user_id"]             = $shared_by;
				$records["default_status"]             = $default;
            $records["access_id"]           = $user_access;
            $records["from_wkout"]          = 0;
            /*//Block-------*/
            $insertId                       = $this->insertSampleWorkoutDetailsByseqOrder($records);
            /*********************Activiy Feed**********************/
				$activity_feed      = array();
				$activity_feed["user"]  = $shared_by;
				$activity_feed["site_id"]= $siteid;
				$activity_feed["feed_type"]   = '2';
				$activity_feed["action_type"] = '22';
				$activity_feed["type_id"]     = $wkoutId;
				if($default == '1')
					$activity_feed["json_data"]   = json_encode(array('wkoutdefault'=>$insertId));
				else
					$activity_feed["json_data"]   = json_encode(array('wkoutsample'=>$insertId));
				Helper_Common::createActivityFeed($activity_feed);
			/*********************Activiy Feed**********************/
            if (is_array($exerciseRecords) && count($exerciseRecords) > 0) {
               foreach ($exerciseRecords as $keys => $values) {
                  if ($values['goal_title'] != 'Click_to_Edit') {
                     $unit_results         = array(
                        0
                     );
                     $unit_results[0]      = $values["goal_unit_id"];
                     $goal_results         = DB::insert('wkout_sample_goal_vars', array(
                        'goal_iso',
                        'goal_alt',
                        'primary_time',
                        'primary_dist',
                        'primary_reps',
                        'goal_time_hh',
                        'goal_time_mm',
                        'goal_time_ss',
                        'goal_dist',
                        'goal_dist_id',
                        'goal_reps',
                        'goal_resist',
                        'goal_resist_id',
                        'goal_rate',
                        'goal_rate_id',
                        'goal_angle',
                        'goal_angle_id',
                        'goal_int_id',
                        'goal_rest_mm',
                        'goal_rest_ss',
                        'goal_remarks'
                     ))->values(array(
                        $values['goal_iso'],
                        $values['goal_alt'],
                        $values['primary_time'],
                        $values['primary_dist'],
                        $values['primary_reps'],
                        $values['goal_time_hh'],
                        $values['goal_time_mm'],
                        $values['goal_time_ss'],
                        $values['goal_dist'],
                        $values['goal_dist_id'],
                        $values['goal_reps'],
                        $values['goal_resist'],
                        $values['goal_resist_id'],
                        $values['goal_rate'],
                        $values['goal_rate_id'],
                        $values['goal_angle'],
                        $values['goal_angle_id'],
                        $values['goal_int_id'],
                        $values['goal_rest_mm'],
                        $values['goal_rest_ss'],
                        $values['goal_remarks']
                     ));
                     /*//Block-------*/
                     $goal_results         = $goal_results->execute();
                     $goal_gendata_results = DB::insert('wkout_sample_goal_gendata', array(
                        'wkout_sample_id',
                        'goal_unit_id',
                        'goal_group',
                        'goal_title',
                        'goal_title_self',
                        'goal_order',
                        'status_id'
                     ))->values(array(
                        $insertId,
                        $unit_results[0],
                        $values['goal_group'],
                        $values['goal_title'],
                        $values['goal_title_self'],
                        $values['goal_order'],
                        '1'
                     ));
                     /*//Block-------*/
                     $goal_gendata_results = $goal_gendata_results->execute();
                  }
               }
            }
         }
      }
		
		else if ($type == 'mysharedwkout')
		{
         $records          = $this->getShareworkoutById('', $wkoutId);
         $curSeqOrderArray = $this->getCurrentSeqorderByShareWkoutId($wkoutId);
         $exerciseRecords  = $this->getExerciseSetShare($wkoutId);
         $datetime = Helper_Common::get_default_datetime();
         if (is_array($records) && count($records) > 0) {
            $records['parent_folder_id'] = (isset($curSeqOrderArray['parent_folder_id']) && $curSeqOrderArray['parent_folder_id']) ? $curSeqOrderArray['parent_folder_id'] : 0;
            $records['created_date']     = $datetime;
            $records['modified_date']    = $datetime;
            $records['wkout_title']      = $records['wkout_title'];
            $currentWkseqOrder           = (isset($curSeqOrderArray['seq_order']) && $curSeqOrderArray['seq_order']) ? $curSeqOrderArray['seq_order'] : 1;
            if ($moveAction == 'down') {
               $records['seq_order'] = $currentWkseqOrder + 1;
            } else {
               $records['seq_order'] = (($currentWkseqOrder > 1) ? $currentWkseqOrder - 1 : '1');
            }
            $records["wkout_id"]            = $wkoutId;
            $records["site_id"]             = $siteid;
				$records["user_id"]             = $shared_by;
            $records["access_id"]           = $user_access;
            $records["from_wkout"]          = 1;
				$records["default_status"]             = $default;
            /*//Block-------*/
            $insertId                       = $this->insertSampleWorkoutDetailsByseqOrder($records);
            /*********************Activiy Feed**********************/
				$activity_feed      = array();
				$activity_feed["user"]  = $shared_by;
				$activity_feed["site_id"]= $siteid;
				$activity_feed["feed_type"]   = '12';
				$activity_feed["action_type"] = '22';
				$activity_feed["type_id"]     = $wkoutId;
				if($default == '1')
					$activity_feed["json_data"]   = json_encode(array('wkoutdefault'=>$insertId));
				else
					$activity_feed["json_data"]   = json_encode(array('wkoutsample'=>$insertId));
				Helper_Common::createActivityFeed($activity_feed);
            /*********************Activiy Feed**********************/
            if (is_array($exerciseRecords) && count($exerciseRecords) > 0) {
               foreach ($exerciseRecords as $keys => $values) {
                  if ($values['goal_title'] != 'Click_to_Edit') {
                     $unit_results         = array(
                        0
                     );
                     $unit_results[0]      = $values["goal_unit_id"];
                     $goal_results         = DB::insert('wkout_sample_goal_vars', array(
                        'goal_iso',
                        'goal_alt',
                        'primary_time',
                        'primary_dist',
                        'primary_reps',
                        'goal_time_hh',
                        'goal_time_mm',
                        'goal_time_ss',
                        'goal_dist',
                        'goal_dist_id',
                        'goal_reps',
                        'goal_resist',
                        'goal_resist_id',
                        'goal_rate',
                        'goal_rate_id',
                        'goal_angle',
                        'goal_angle_id',
                        'goal_int_id',
                        'goal_rest_mm',
                        'goal_rest_ss',
                        'goal_remarks'
                     ))->values(array(
                        $values['goal_iso'],
                        $values['goal_alt'],
                        $values['primary_time'],
                        $values['primary_dist'],
                        $values['primary_reps'],
                        $values['goal_time_hh'],
                        $values['goal_time_mm'],
                        $values['goal_time_ss'],
                        $values['goal_dist'],
                        $values['goal_dist_id'],
                        $values['goal_reps'],
                        $values['goal_resist'],
                        $values['goal_resist_id'],
                        $values['goal_rate'],
                        $values['goal_rate_id'],
                        $values['goal_angle'],
                        $values['goal_angle_id'],
                        $values['goal_int_id'],
                        $values['goal_rest_mm'],
                        $values['goal_rest_ss'],
                        $values['goal_remarks']
                     ));
                     /*//Block-------*/
                     $goal_results         = $goal_results->execute();
                     $goal_gendata_results = DB::insert('wkout_sample_goal_gendata', array(
                        'wkout_sample_id',
                        'goal_unit_id',
                        'goal_group',
                        'goal_title',
                        'goal_title_self',
                        'goal_order',
                        'status_id'
                     ))->values(array(
                        $insertId,
                        $unit_results[0],
                        $values['goal_group'],
                        $values['goal_title'],
                        $values['goal_title_self'],
                        $values['goal_order'],
                        '1'
                     ));
                     /*//Block-------*/
                     $goal_gendata_results = $goal_gendata_results->execute();
                  }
               }
            }
         }
      }
   }
   public function insertSampleWorkoutDetailsByseqOrder($array)
   {
      $workout_results = DB::insert('wkout_sample_gendata', array(
         'wkout_group',
         'wkout_title',
         'wkout_color',
         'wkout_order',
         'status_id',
         'access_id',
         'user_id',
         'site_id',
         'wkout_focus',
         'wkout_poa',
         'wkout_poa_time',
         'wkout_id',
         'from_wkout',
         'default_status',
		 'date_created',
		 'date_modified'
      ))->values(array(
         $array['wkout_group'],
         $array['wkout_title'],
         $array['wkout_color'],
         $array['wkout_order'],
         $array['status_id'],
         $array['access_id'],
         $array['user_id'],
         $array['site_id'],
         $array['wkout_focus'],
         $array['wkout_poa'],
         $array['wkout_poa_time'],
         $array['wkout_id'],
         $array['from_wkout'],
         (isset($array["default_status"]) && $array["default_status"] == 1) ? 1 : 0,
		 $array['created_date'],
         $array['modified_date']
		));
      /*//Block-------*/
      $workout_results = $workout_results->execute();
      $seg_results     = DB::insert('wkout_sample_seq', array(
         'parent_folder_id',
         'folder_id',
         'wkout_sample_id',
         'seq_order',
         'user_id',
         'site_id',
         'created_date',
         'modified_date',
         'default_status'
      ))->values(array(
         '0',
         '0',
         $workout_results[0],
         $array['seq_order'],
         $array['user_id'],
         $array['site_id'],
         $array['created_date'],
         $array['modified_date'],
         (isset($array["default_status"]) && $array["default_status"] == 1) ? 1 : 0
      ));
      /*//Block-------*/
      $seg_results     = $seg_results->execute();
      //if ($array['parent_folder_id'] != 0) {
        // $default = (isset($array["default_status"]) && $array["default_status"] == 1) ? 1 : 0;
         //$sres    = $this->getSampleSeq($array['parent_folder_id'], $array['from_wkout'], $array['site_id'],$array['user_id'], $default);
      //}
      //exit;
      return $workout_results[0];
   }
   public function getSampleSeq($fid, $type, $site_id,$userid, $default)
   {
      //echo $type; die;
      if ($type == 0)
         $sql = "SELECT * from wkout_seq as wkf Where wkf.folder_id ='" . $fid . "' AND wkout_seq_status=0";
      else if ($type == 1)
         $sql = "SELECT * from wkout_share_seq as wkf Where wkf.folder_id ='" . $fid . "' AND wkout_seq_status=0";
      else
         $sql = "SELECT * from wkout_sample_seq as wkf Where wkf.folder_id ='" . $fid . "' AND wkout_seq_status=0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
	  $datetime = Helper_Common::get_default_datetime();
      if ($list) {
         $list        = $list[0];
         $seg_results = DB::insert('wkout_sample_seq', array(
            'parent_folder_id',
            'folder_id',
            'wkout_sample_id',
            'seq_order',
            'site_id' ,
				'user_id',
				'created_date',
            'modified_date',
            'default_status'
         ))->values(array(
            $list['parent_folder_id'],
            $fid,
            0,
            $list['seq_order'],
            $site_id,
				$userid,
            $datetime,
            $datetime,
            $default
         ));
         //echo "<br>$seg_results<br>";
         $seg_results = $seg_results->execute();
         if ($list["parent_folder_id"] != 0) {
            $this->getSampleSeq($list["parent_folder_id"], $type, $default);
         }
      }
   }
   public function get_status()
   {
      $statusselect = "SELECT * FROM status";
      $query        = DB::query(Database::SELECT, $statusselect);
      $status       = $query->execute()->as_array();
      return $status;
   }
   public function get_status_by_id($wkoutid)
   {
      $statusselect = "SELECT * FROM wkout_gendata WHERE wkout_id=" . $wkoutid;
      $query        = DB::query(Database::SELECT, $statusselect);
      $status       = $query->execute()->as_array();
      return $status;
   }
   public function get_sample_wk_status_by_id($wkoutid)
   {
      $statusselect = "SELECT * FROM wkout_sample_gendata WHERE wkout_sample_id=" . $wkoutid;
      $query        = DB::query(Database::SELECT, $statusselect);
      $status       = $query->execute()->as_array();
      return $status;
   }
   public function wkout_update_status($wkoutid, $wk_status, $featured)
   {
      $sql    = "Update wkout_gendata set status_id = " . $wk_status . " , featured = " . $featured . "   Where wkout_id ='" . $wkoutid . "'";
      //echo $sql; die;
      $result = DB::query(Database::UPDATE, $sql)->execute();
      return $result;
   }
   public function sample_wkout_update_status($wkoutid, $wk_status, $featured)
   {
      $sql    = "Update wkout_sample_gendata set status_id = " . $wk_status . " , featured = " . $featured . "   Where wkout_sample_id ='" . $wkoutid . "'";
      //echo $sql; die;
      $result = DB::query(Database::UPDATE, $sql)->execute();
      return $result;
   }
   public function hideDefaultRecords($userId, $siteId, $record_id ,$record_type){
	   $datetime = Helper_Common::get_default_datetime();
	  return DB::insert('default_site_mod_map', array('site_id','record_id','record_type_id','modified_by','modified_date','created_date'))->values(array($siteId, $record_id, $record_type,$userId, $datetime,$datetime))->execute();
   }
   /**************** Create Sample Worout from Normal & Shared Workout ******************************* Ends Here******************/
	public function updateReadStatus($replace_val ,$array = array()){
		if(empty($replace_val)){
			$workout_results = DB::insert('track_read_status', array('wkoutids', 'wkout_type', 'read_by', 'site_id', 'status_id', 'created_date', 'modified_date' , 'is_from'))
			->values(array('#'.$array['wkoutids'], $array['wkout_type'], $array['read_by'], $array['site_id'], $array['status_id'], $array['created_date'], $array['modified_date'] , '2'))->execute();
			return true;
		}else{
			$sql 	= "Update track_read_status set wkoutids = '".$array['wkoutids']."' where wkoutids like '".$replace_val."' AND wkout_type='".$array['wkout_type']."' AND status_id='1' AND is_from='2' AND read_by='".$array['read_by']."'";
			return DB::query(Database::UPDATE,$sql)->execute();
		}
	}
	public function getSampleWkoutunreadDetails($id_user,$parent_fold_id = 0){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "Select group_concat(wsg.wkout_sample_id separator '#') as wkoutids, trs.wkoutids as wkoutidsreplace from wkout_sample_gendata wsg JOIN wkout_sample_seq as wss on (wss.wkout_sample_id =wsg.wkout_sample_id) join sites s on wss.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = wsg.wkout_sample_id AND ds.record_type_id=1 ) Left Join track_read_status as trs on (trs.wkoutids like CONCAT('%#', wsg.wkout_sample_id ,'#%') AND trs.wkout_type='2' AND trs.status_id='1' AND trs.read_by='".$id_user."' AND (trs.site_id in (".$site_id.") OR trs.site_id is NULL) AND trs.is_from=2) where wsg.status_id ='1' AND s.is_active = 1 AND s.is_deleted = 0 AND wss.folder_id = '0' AND wss.wkout_seq_status='0' AND ((wss.site_id in (".$site_id.") AND wss.default_status='0' AND wsg.default_wkout_id ='0') ".(Helper_Common::hasAccessBySampleWkouts($site_id) ? " OR (wss.default_status='1' AND s.sample_workouts=1 AND (ds.record_mod_action!=2 OR ds.id is NULL)) " : '').")";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]) ? $list[0] : $list);
	}
	public function getSharedunreadDetails($id_user = 0, $siteid,  $parent_fold_id = 0){
		$sql 	= "Select group_concat(wsg.wkout_share_id separator '#') as wkoutids, trs.wkoutids as wkoutidsreplace from wkout_share_gendata wsg JOIN wkout_share_seq as wss on (wss.wkout_share_id =wsg.wkout_share_id) JOIN sites s ON wss.site_id=s.id  Left Join track_read_status as trs on (trs.wkoutids like CONCAT('%#', wsg.wkout_share_id ,'#%') AND trs.wkout_type='1' AND trs.status_id='1' AND trs.read_by='".$id_user."'  AND trs.is_from=2) where wsg.user_id='".$id_user."' AND wss.shared_for='".$id_user."' AND wsg.status_id ='1' AND wss.folder_id = '0' AND wss.wkout_seq_status='0' AND s.is_active = 1 AND s.is_deleted = 0 and wss.site_id=$siteid";
		
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return (isset($list[0]) ? $list[0] : $list);
	}
	public function getSharedunreadCnt($id_user = 0,$siteid){
		$sql 	= "Select count(wsg.wkout_share_id) as totalshare, trs.wkoutids as totalreadids  from wkout_share_gendata wsg JOIN wkout_share_seq as wss on (wss.wkout_share_id =wsg.wkout_share_id) JOIN sites s ON wss.site_id=s.id Left Join track_read_status as trs on (trs.wkoutids like CONCAT('%#', wsg.wkout_share_id ,'#%') AND trs.wkout_type='1' AND trs.status_id='1' AND trs.read_by='".$id_user."'  AND trs.is_from=2) where wsg.user_id='".$id_user."' AND wss.shared_for='".$id_user."' AND wsg.status_id ='1' AND wss.folder_id = '0' AND wss.wkout_seq_status='0' AND s.is_active = 1 AND s.is_deleted = 0 and wss.site_id=$siteid";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list[0];
	}
	public function getSampleWkoutunreadCnt($id_user){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql 	= "Select count(wsg.wkout_sample_id) as totalsample, trs.wkoutids as totalreadids  from wkout_sample_gendata wsg JOIN wkout_sample_seq as wss on (wss.wkout_sample_id =wsg.wkout_sample_id) join sites s on wss.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = wsg.wkout_sample_id AND ds.record_type_id=1 ) Left Join track_read_status as trs on (trs.wkoutids like CONCAT('%#', wsg.wkout_sample_id ,'#%') AND trs.wkout_type='2' AND trs.status_id='1' AND trs.read_by='".$id_user."' AND (trs.site_id in (".$site_id.") OR trs.site_id is NULL)  AND trs.is_from=2) where wsg.status_id ='1' AND wss.folder_id = '0' AND s.is_active = 1 AND s.is_deleted = 0 AND wss.wkout_seq_status='0' AND ((wss.site_id in (".$site_id.") AND wss.default_status='0' AND wsg.default_wkout_id ='0') ".(Helper_Common::hasAccessBySampleWkouts($site_id) ? " OR (wss.default_status='1' AND s.sample_workouts=1 AND (ds.record_mod_action!=2 OR ds.id is NULL)) " : '').")";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list[0];
	}
	public function insertShareAssign($assignArray){
		$datetime = Helper_Common::get_default_datetime();
		$user_id  = Auth::instance()->get_user()->pk();
		$site_id  = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$result = DB::insert('assign_from_share', array('wkout_share_id','assign_date','shared_user_id','assigned_user_id','site_id', 'created_date', 'modified_date'))->values(array($assignArray['wkout_share_id'], $assignArray['assign_date'], $user_id, $assignArray['assigned_user_id'], $site_id, $datetime, $datetime))->execute();
		return $result[0];
	}
}
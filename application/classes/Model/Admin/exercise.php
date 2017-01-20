<?php
defined('SYSPATH') OR die('No direct access allowed.');
class Model_Admin_exercise extends Model
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
	public function get_exerciseRecordGallery($defaultstatus, $filters, $limitCurrent, $offset , $xrids = 0)
	{
		$site_id     = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		
		$user        = Auth::instance()->get_user()->pk();
		$files       = array();
		$viewStatus  = 1;
		$user_access = 2; // for later use: [test] user's access level matches the record access id
		// $user       = 1;     // for later use: user login id configured in a $_SESSION
		$orderBy     = 'DESC'; // for later use: controls for sort order of retrieved records
		$limitStart  = $offset;
		//$limitCurrent   = 10;
		if (empty($filters['autosearch'])) {
			$search_x = 0;
		} else {
			$search_x = 1;
			$search   = $this->escapeStr($filters['autosearch']);
		}
		;
		if (empty($filters['sortby'])) {
			$sort_x = 0;
		} else {
			$sort_x = 1;
			$sort   = $this->escapeStr($filters['sortby']);
		}
		;
		if (empty($filters['musprim'])) {
			$muscle_x = 0;
		} else {
			$muscle_x = 1;
			$muscle   = $this->escapeStr($filters['musprim']);
		}
		;
		if (empty($filters['exercisetypes'])) {
			$type_x = 0;
		} else {
			$type_x = 1;
			$type   = $filters['exercisetypes'];
		}
		;
		if (empty($filters['exerciseequips'])) {
			$equip_x = 0;
		} else {
			$equip_x = 1;
			$equip   = $filters['exerciseequips'];
		}
		;
		if (empty($filters['exerciselevels'])) {
			$level_x = 0;
		} else {
			$level_x = 1;
			$level   = $filters['exerciselevels'];
		}
		;
		if (empty($filters['exercisesports'])) {
			$sport_x = 0;
		} else {
			$sport_x = 1;
			$sport   = $filters['exercisesports'];
		}
		;
		if (empty($filters['exerciseactions'])) {
			$force_x = 0;
		} else {
			$force_x = 1;
			$force   = $filters['exerciseactions'];
		}
		;
		if (empty($filters['exercisetags'])) {
			$tags_x = 0;
		} else {
			$tags_x = 1;
			$tag    = $filters['exercisetags'];
		}
		;
		if (empty($filters['futured_filter'])) {
			$futured_x = 0;
		} else {
			$futured_x = 1;
			$futured   = $filters['futured_filter'];
		}
		;
		if (!empty($filters['statusfilter'])) { 
			$status_x   = 1;
			$status_val = '';
			foreach ($filters['statusfilter'] as $key => $value) {
				$status_val .= $value . ',';
			}
			$status_val = rtrim($status_val, ',');
		} else {
			$status_x   = 0;
			$status_val = 1;
		}
		//if(empty( $_POST['tags'] ) ){ $tags_x=0;         }else{ $tags_x=1; $tags = $_POST['tags']; };
		/* Count of the all filtered items */
		$c_filters = $search_x + $muscle_x + $type_x + $equip_x + $level_x + $sport_x + $force_x;
		+$tags_x;
		+$status_x;
		if ($c_filters > 0) {
			$f_start = 'AND (';
			$f_end   = ')';
		} else {
			$f_start = '';
			$f_end   = '';
		}
		/*
		function for escaping
		each of the array items
		*/
		/*
		function escape_str($item)
		{
		$item    = Database::instance()->escape($item);  
		return $item;
		}*/
		$f = '';
		$e = 0;
		/* $f_search */
		if ($search_x == 0) {
		} else {
			$e = 1;
			$f .= '(xrgd.title LIKE "%' . $search . '%")';
		}
		/* $f_muscles */
		if ($muscle_x == 0) {
		} else {
			/* Gate_1 */
			if ($e == 1) { // there's an AND from before this
				$f .= ' AND ';
			} else {
			} // no AND from before this  
			$e = 1;
			$f .= 'xrgd.musprim_id=' . $muscle;
		}
		/* $f_types */
		if ($type_x == 0) {
		} else {
			/* Gate_2 */
			if ($e == 1) { // there's an AND from before this
				$f .= ' AND ';
			} else {
			} // no AND from before this
			$e = 1;
			$f .= '(';
			$c_type = count($type);
			for ($i = 0; $i < $c_type; ++$i) {
				$type_i = $this->escape_str($type[$i]);
				if ($i != ($c_type - 1)) {
					$f .= 'xrgd.type_id=' . $type_i . ' OR ';
				} else {
					$f .= 'xrgd.type_id=' . $type_i . ')';
				}
			}
		}
		/* $f_equips */
		if ($equip_x == 0) {
		} else {
			/* Gate_3*/
			if ($e == 1) { // there's an AND from before this
				$f .= ' AND ';
			} else {
			} // no AND from before this
			$e = 1;
			$f .= '(';
			$c_equip = count($equip);
			for ($i = 0; $i < $c_equip; ++$i) {
				$equip_i = $this->escape_str($equip[$i]);
				if ($i != ($c_equip - 1)) {
					$f .= 'xrgd.equip_id=' . $equip_i . ' OR ';
				} else {
					$f .= 'xrgd.equip_id=' . $equip_i . ')';
				}
			}
		}
		/* $f_levels */
		if ($level_x == 0) {
		} else {
			/* Gate_4 */
			if ($e == 1) { // there's an AND from before this
				$f .= ' AND ';
			} else {
			} // no AND from before this
			$e = 1;
			$f .= '(';
			$c_level = count($level);
			for ($i = 0; $i < $c_level; ++$i) {
				$level_i = $this->escape_str($level[$i]);
				if ($i != ($c_level - 1)) {
					$f .= 'xrgd.level_id=' . $level_i . ' OR ';
				} else {
					$f .= 'xrgd.level_id=' . $level_i . ')';
				}
			}
		}
		/* $f_sports */
		if ($sport_x == 0) {
		} else {
			/* Gate_5 */
			if ($e == 1) { // there's an AND from before this
				$f .= ' AND ';
			} else {
			} // no AND from before this
			$e = 1;
			$f .= '(';
			$c_sport = count($sport);
			for ($i = 0; $i < $c_sport; ++$i) {
				$sport_i = $this->escape_str($sport[$i]);
				if ($i != ($c_sport - 1)) {
					$f .= 'xrgd.sport_id=' . $sport_i . ' OR ';
				} else {
					$f .= 'xrgd.sport_id=' . $sport_i . ')';
				}
			}
		}
		/* $f_forces */
		if ($force_x == 0) {
		} else {
			/* Gate_6 */
			if ($e == 1) { // there's an AND from before this
				$f .= ' AND ';
			} else {
			} // no AND from before this
			$e = 1;
			$f .= '(';
			$c_force = count($force);
			for ($i = 0; $i < $c_force; ++$i) {
				$force_i = $this->escape_str($force[$i]);
				if ($i != $c_force - 1) {
					$f .= 'xrgd.force_id=' . $force_i . ' OR ';
				} else {
					$f .= 'xrgd.force_id=' . $force_i . ')';
				}
			}
		}
		if ($tags_x == 0) {
		} else {
			/* Gate_7 */
			if ($e == 1) { // there's an AND from before this
				$f .= ' AND ';
			} else {
				$f .= ' AND ';
			} // no AND from before this
			$e = 1;
			$f .= '(';
			$c_tag = count($tag);
			for ($i = 0; $i < $c_tag; ++$i) {
				$tag_i = $this->escape_str($tag[$i]);
				if ($i != ($c_tag - 1)) {
					$f .= 'ut.tag_id=' . $tag_i . ' OR ';
				} else {
					$f .= 'ut.tag_id=' . $tag_i . ')';
				}
			}
		}
		$shared_select = $shared_from = '';
		if(!empty($defaultstatus) && $defaultstatus == '3'){
			$shared_select = ' ,ugs.unit_share_id, ugs.shared_by, ugs.shared_msg, concat(usr.user_fname," ",usr.user_lname) as user_name ';
			$shared_from = ' LEFT JOIN unit_gendata_shared AS ugs ON xrgd.unit_id=ugs.unit_id LEFT JOIN users AS usr ON usr.id=ugs.shared_by ';
		}
		// Query the exercise record data
		$sql = "SELECT xrgd.unit_id, xrgd.title,g4.img_url,g3.muscle_title,g5.equip_title ,g6.status_title,g7.type_title,g8.name,group_concat(tag.tag_title SEPARATOR '@@') as tagdetails,xrgd.default_status,xrgd.access_id, xrgd.created_by ".$shared_select." FROM unit_gendata AS xrgd 
			 LEFT JOIN unit_muscle AS g3 ON xrgd.musprim_id=g3.muscle_id 
			 LEFT JOIN img AS g4 ON xrgd.feat_img=g4.img_id 
			 LEFT JOIN unit_equip AS g5 ON xrgd.equip_id=g5.equip_id 
			 LEFT JOIN unit_status AS g6 ON xrgd.status_id=g6.status_id
			 LEFT JOIN unit_type AS g7 ON xrgd.type_id=g7.type_id
			 LEFT JOIN roles AS g8 ON xrgd.access_id=g8.id
			 LEFT JOIN unit_tag AS ut ON (xrgd.unit_id=ut.unit_id AND ut.created_by='".$user."')
			 LEFT JOIN tag AS tag ON tag.tag_id=ut.tag_id 
			 JOIN sites s ON (xrgd.site_id=s.id AND s.is_active = 1 AND s.is_deleted = 0) ".(!empty($defaultstatus) ? " 
	 		 LEFT JOIN default_site_mod_map as ds ON (ds.record_id = xrgd.unit_id AND ds.record_type_id=2 AND ds.site_id in ($site_id)) " : "" ).
			 $shared_from."
			  WHERE 1 " . $f_start . " " . $f . " " . $f_end;
		if(Helper_Common::hasAccessByDefaultXr($site_id) && Helper_Common::is_admin() && ($defaultstatus =='2' || $defaultstatus =='1')){
			if($defaultstatus=='2'){
				$sql .= " AND ((xrgd.default_status=1 AND (ds.record_mod_action!=2 OR ds.id is NULL)) ".(!empty($site_id) && $defaultstatus=='2' ? " OR (xrgd.site_id in ($site_id) AND xrgd.default_status=2)" : '').") ";
			}else if($defaultstatus=='1'){
				$sql .= " AND (xrgd.default_status=1 AND (ds.record_mod_action!=2 OR ds.id is NULL)) ";
			}else{
				$sql .= " AND xrgd.default_status=0 AND xrgd.created_by in (" . $user . ") ";
			}
		}else if(Helper_Common::hasAccessByDefaultXr($site_id) && !Helper_Common::is_admin() && $defaultstatus =='2'){
			$sql .= " AND ((xrgd.default_status=1 AND (ds.record_mod_action!=2 OR ds.id is NULL)) ".(!empty($site_id) && $defaultstatus=='2' ? " OR (xrgd.site_id in ($site_id) AND xrgd.default_status=2)" : '').") ";
		}else if($defaultstatus == '1' && Helper_Common::hasAccessByDefaultXr($site_id) != true){
			$sql .= " AND xrgd.default_status=1 ";
		}else if($defaultstatus == '2' && Helper_Common::hasAccessByDefaultXr($site_id) != true){
			$sql .= " AND xrgd.default_status=2 ".(!empty($site_id) ? " AND xrgd.site_id in ($site_id) " : '');
		}else if(Helper_Common::is_admin() && empty($defaultstatus)){
			$sql .= " AND xrgd.default_status=0 AND xrgd.created_by in (" . $user . ") ";
		}else if(!empty($defaultstatus) && $defaultstatus == '3'){
			$sql .= " AND xrgd.default_status=3 AND xrgd.created_by in (" . $user . ") AND xrgd.site_id in ($site_id)";
		}else{
			if($defaultstatus=='all' && !empty($defaultstatus)){
				$sql .= " AND ((".(!empty($site_id) ? "xrgd.site_id in ($site_id) AND " : '')." xrgd.default_status='2') OR (xrgd.default_status='1' AND (ds.record_mod_action!=2 OR ds.id is NULL)))";
			}else{
				$sql .= " AND xrgd.default_status='0' AND xrgd.created_by in (" . $user . ")";
			}
		}
		if (isset($status_val)) {
			$sql .= " AND xrgd.status_id in ($status_val)";
		}
		if (isset($futured)) {
			$sql .= " AND xrgd.featured =$futured";
		}
		
		/****For Export purpose only***/
		if( isset($_GET["exe"]) && $_GET["exe"]!=''){  //$this->input->get("exe")
			 $sql .= ' AND xrgd.unit_id in (' .$_GET["exe"].') ';
		}elseif(!empty($xrids)){
			 $sql .= ' AND xrgd.unit_id in (' .$xrids.') ';
		}
		/****For Export purpose only***/
		if (isset($sort)) {
			if ($sort == 'asc' || $sort == 'desc')
				$sql .= ' group by xrgd.unit_id ORDER BY  xrgd.title ' . strtoupper($sort);
			else
				$sql .= ' group by xrgd.unit_id ORDER BY xrgd.' . $sort . ' DESC';
		} else {
			$sql .= " group by xrgd.unit_id
			 ORDER BY   xrgd.unit_id " . $orderBy . "";
		}
		if ($limitCurrent != '') {
			$sql .= " LIMIT " . $limitStart . " , " . $limitCurrent . "";
		}
	   //echo $sql;die();
		$data = DB::query(Database::SELECT, $sql)->execute();
		// Run the recursive function 
		if ($data != null && count($data) > 0) {
			foreach ($data as $row) {
				$url1           = substr($row['img_url'], strripos($row['img_url'], "img_"));
				// $urlPrefix_thmb = $INDEX.IMG_XRCISETHMB.IMG_THUMBPREFIX;
				//$urlPrefix_thmb = 'assets/images/dynamic/exercise/thumb/thumb_';
				$sd             = explode("/", $url1);
				$urlPrefix_thmb = '';
				if (count($sd) == 1) {
					$urlPrefix_thmb = 'assets/images/dynamic/exercise/thumb/thumb_';
				}
				if (!empty($url1) && file_exists($urlPrefix_thmb . $url1)) {
					$image = URL::base_lang() . $urlPrefix_thmb . $url1;
				} else {
					//$image = $INDEX.constant('DEFAULT_IMG_ICON');
					$image = URL::base_lang() . 'assets/images/navimage.png';
				}
				$recUrl  = '?unit_id=' . $row['unit_id'];
				// Pack file listings into array
				//echo "<br>".$row['title']."----------<img src='$image'>-------------------".$urlPrefix_thmb.$url1;
				$sharedby_role = (isset($row['shared_by']) && !empty($row['shared_by']) ? $this->getRoleNameByUserId($row['shared_by']) : '');
				$files[] = array(
					"id" => $row['unit_id'],
					"name" => $row['title'],
					"featimg" => $image,
					"default_status" => $row['default_status'],
					"access_id" => $row['access_id'],
					"user_id" => $row['created_by'],
					"muscle" => $row['muscle_title'],
					"equip" => $row['equip_title'],
					"path" => $recUrl,
					"status" => $row['status_title'],
					"type" => $row['type_title'],
					"access" => $row['name'],
					"tagdetails" => $row['tagdetails'],
					"shared_by" => (isset($sharedby_role['name']) && !empty($sharedby_role) ? ucwords($sharedby_role['name']) : ''),
					"shared_msg" => (isset($row['shared_msg']) ? $row['shared_msg'] : ''),
					"user_name" => (isset($row['user_name']) ? ucwords($row['user_name']) : '')
				);
			}
		}
		// print_r($files) ;
		//exit;
		return $files;
	}
	public function getRoleNameByUserId($userId){
		$sql = "SELECT name FROM roles WHERE id = ".$this->getUserRoleById($userId);
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
		return $list[0];
	}
	public function getSharedXrUnreadDetails($user_id){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql = "SELECT GROUP_CONCAT(ugs.unit_share_id separator '#') AS unitids, trs.wkoutids AS unitidsreplace 
			FROM unit_gendata_shared AS ugs JOIN unit_gendata AS xrgd ON (xrgd.unit_id = ugs.unit_id) JOIN sites AS s ON ugs.site_id = s.id
			LEFT JOIN track_read_status AS trs ON (trs.wkoutids LIKE CONCAT('%#', ugs.unit_share_id, '#%') AND trs.xr_type = '1' AND trs.status_id = '1' AND trs.read_by = '".$user_id."' AND trs.is_from = '2') 
			WHERE xrgd.created_by = '".$user_id."' AND ugs.shared_for = '".$user_id."' AND xrgd.default_status=3 AND xrgd.site_id = ".$site_id." AND xrgd.status_id = '1' AND ugs.status_id = '1' AND s.is_active = 1 AND s.is_deleted = 0";
		$rescnt = DB::query(Database::SELECT,$sql)->execute()->as_array();
		return (isset($rescnt[0]) ? $rescnt[0] : $rescnt);
	}
	public function getSharedXrUnreadCount($user_id){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql = "SELECT COUNT(ugs.unit_share_id) AS totalsharedxr, trs.wkoutids AS totalxrreadids
			FROM unit_gendata_shared AS ugs JOIN unit_gendata AS xrgd ON (xrgd.unit_id = ugs.unit_id) JOIN sites AS s ON ugs.site_id = s.id
			LEFT JOIN track_read_status AS trs ON (trs.wkoutids LIKE CONCAT('%#', ugs.unit_share_id, '#%') AND trs.xr_type = '1' AND trs.status_id = '1' AND trs.read_by = '".$user_id."' AND trs.is_from = '2') 
			WHERE xrgd.created_by = '".$user_id."' AND ugs.shared_for = '".$user_id."' AND xrgd.default_status=3 AND xrgd.site_id = ".$site_id." AND xrgd.status_id = '1' AND ugs.status_id = '1' AND s.is_active = 1 AND s.is_deleted = 0";
		$rescnt = DB::query(Database::SELECT,$sql)->execute()->as_array();
		return (isset($rescnt[0]) ? $rescnt[0] : $rescnt);
	}
	public function updateUnitReadStatus($replace_val ,$array = array()){
		if(empty($replace_val)){
			$workout_results = DB::insert('track_read_status', array('wkoutids', 'xr_type', 'read_by', 'site_id', 'status_id', 'created_date', 'modified_date', 'is_from'))
			->values(array('#'.$array['wkoutids'], $array['xr_type'], $array['read_by'], $array['site_id'], $array['status_id'], $array['created_date'], $array['modified_date'], '2'))->execute();
			return true;
		}else{
			$sql = "UPDATE track_read_status SET wkoutids = '".$array['wkoutids']."' WHERE wkoutids LIKE '".$replace_val."' AND xr_type='".$array['xr_type']."' AND status_id='1' AND is_from='2' AND read_by='".$array['read_by']."'";
			return DB::query(Database::UPDATE,$sql)->execute();
		}
	}
	public function escape_str($item)
	{
		$item = Database::instance()->escape($item);
		return $item;
	}
	public function checkdefault($unitid, $siteid)
	{
		$sql   = "SELECT * FROM unit_gendata_default WHERE unit_id = '" . $unitid . "' AND site_id = '" . $siteid . "'";
		$query = DB::query(Database::SELECT, $sql);
		$list  = $query->execute()->as_array();
		return $list;
	}
	public function get_user_created_tags($userid, $tag_catid)
	{
		// $sql   = "SELECT * FROM tag WHERE tag_cat_id = '" . $tag_catid . "'";
		$sql   = "SELECT * FROM tag WHERE status_id=1 ORDER BY tag_id ASC";
		$query = DB::query(Database::SELECT, $sql);
		$list  = $query->execute()->as_array();
		return $list;
	}
	public function get_exercise_tags($unit_id, $siteid)
	{
		$userid = Auth::instance()->get_user()->pk();
		if (is_array($unit_id) && count($unit_id) > 1) {
			$cnt     = count($unit_id);
			$unit_id = implode(',', $unit_id);
			$sql     = "SELECT ut.tag_id,t.tag_title, count(*) as cnt FROM unit_tag as ut join tag as t WHERE t.tag_id=ut.tag_id and ut.unit_id in (" . $unit_id . ") AND ut.created_by= ".$userid." group by ut.tag_id having cnt = $cnt";
		} else {
			$unit_id = (is_array($unit_id)) ? implode(',', $unit_id) : $unit_id;
			$sql     = "SELECT ut.tag_id,t.tag_title FROM unit_tag as ut join tag as t WHERE t.tag_id=ut.tag_id and ut.unit_id in (" . $unit_id . ") AND ut.created_by= ".$userid."";
		}
		//echo $sql."<br><br><br>"; die;
		$query = DB::query(Database::SELECT, $sql);
		$list  = $query->execute()->as_array();
		return $list;
	}
	public function add_exercise_tag($tagid, $unit_id, $siteid)
	{
		$userid = Auth::instance()->get_user()->pk();
		if (is_array($unit_id)) {
			foreach ($unit_id as $k => $v) {
				$id[] = DB::insert('unit_tag', array(
					'tag_id',
					'unit_id',
					'status_id',
					'created_by'
				))->values(array(
					$tagid,
					$v,
					'1',
					$userid
				))->execute();
			}
		} else {
			list($id) = DB::insert('unit_tag', array(
				'tag_id',
				'unit_id',
				'status_id',
				'created_by'
			))->values(array(
				$tagid,
				$unit_id,
				'1',
				$userid
			))->execute();
		}
		return $id;
	}
	public function delete_exercise_tag($unit_id, $tagid)
	{
		$userid = Auth::instance()->get_user()->pk();
		//$sql = "DELETE FROM wkout_tags WHERE tag_id = ".$tagid." and wkout_id in (".$unit_id.")"; //echo $sql;
		$unit_id = (is_array($unit_id)) ? implode(',', $unit_id) : $unit_id;
		$sql     = "DELETE FROM unit_tag WHERE unit_id in (" . $unit_id . ") AND created_by= ".$userid." ";
		if ($tagid) {
			$tag_id = (is_array($tagid)) ? implode(',', $tagid) : $tagid;
			$sql .= " AND tag_id in(" .$tag_id. ")";
		}
		$query = DB::query(Database::DELETE, $sql)->execute();
		return true;
	}
	public function get_listexercise_tags($exerciseid)
	{
		$userid = Auth::instance()->get_user()->pk();
		$exerciseid = (is_array($exerciseid)) ? implode(',', $exerciseid) : $exerciseid;
		$sql        = "SELECT ut.unit_id,group_concat(t.tag_title) as tag_title FROM unit_tag as ut join tag as t WHERE t.tag_id=ut.tag_id and ut.unit_id in (" . $exerciseid . ") AND ut.created_by= ".$userid." group by ut.unit_id";
		//echo $sql."<br><br><br>";
		$query      = DB::query(Database::SELECT, $sql);
		$list       = $query->execute()->as_array();
		return $list;
	}
	public function add_tag($data)
	{
		$values = array(
			$data['tag_title'],
			$this->random_color(),
			$data['tag_cat_id'],
			$data['created_by'],
			Helper_Common::get_default_datetime()
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
	public function random_color()
	{
		return "#" . $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
	}
	public function random_color_part()
	{
		return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
	}
	public function approveRateXrData($approved_by, $rid)
	{
		$sql = "UPDATE unit_gendata_rating SET is_active=1, approval_by=$approved_by WHERE rate_id = " . $rid . "";
		return $res = DB::query(Database::UPDATE, $sql)->execute();
	}
	
	public function DefaultExerciseRecById($exerciseRecord, $xrRecIdArr, $site_id, $method, $type,$default_status)
	{
		if ($type == 'multiple'){
			for ($cnt = 0; $cnt < count($xrRecIdArr); $cnt++) {
				$sql = "UPDATE unit_gendata SET default_status=$default_status WHERE  unit_id= " . $xrRecIdArr[$cnt] . "";
				$res = DB::query(Database::UPDATE, $sql)->execute();
			}
		}else{
			$sql = "UPDATE unit_gendata SET default_status=$default_status WHERE  unit_id= " . $xrRecIdArr . "";
			$res = DB::query(Database::UPDATE, $sql)->execute();
		}
		return true;
	}
	public function escapeStr($str){
		if($str != "'"){
			$newstr = trim(Database::instance()->escape(trim($str)), "'");
			return $newstr;
		}
		return $str;
	}
	public function  CopyDeleteExerciseRecById($exerciseRecord, $xrRecIdArr, $site_id, $method, $type)
	{
		$datetime = Helper_Common::get_default_datetime();
		$userid   = Auth::instance()->get_user()->pk();
		$user_access = $this->getUserRole();
		$imagelibrary = ORM::factory('admin_imagelibrary');
		$feat_imgid = '0'; $xrRecId  = '';
		if ($type == 'multiple') {
			if(is_array($xrRecIdArr)){
				$xrRecId = implode(',', $xrRecIdArr);
			}else{
				$xrRecId = $xrRecIdArr;
			}
		} elseif ($type == 'single') {
			$xrRecId = $xrRecIdArr;
		}
		// echo $xrRecId;die();
		if ($exerciseRecord == 'exerciseRecord' && ( $method == 'copy' || $method == 'de_copy' || $method == 'sample' || $method == 'default')) { 
			$getmusothdata = $this->getMusOthByUnitId($xrRecId);
			$getequipothdata = $this->getEquipOthByUnitId($xrRecId);
			$getseqdata = $this->getSequencesByUnitId($xrRecId);
			$gettaglist = $this->getUnitTagsById($xrRecId, 1);
			if ($type == 'single') {
				$sql = "SELECT * FROM unit_gendata WHERE unit_id=".$xrRecId ;
				$XrRecdata = DB::query(Database::SELECT,$sql)->execute()->as_array();
				if ($XrRecdata != null && count($XrRecdata) > 0) {
					foreach ($XrRecdata as $values) {
						if($method == 'default')
							$values["default_status"]=1;
						elseif($method == 'sample')
							$values["default_status"]=2;
						else
							$values["default_status"]=0;

						$sqlimg = "SELECT * FROM img WHERE img_id=".$values['feat_img'];
						$Xrimg = DB::query(Database::SELECT,$sqlimg)->execute()->as_array();
						if(isset($Xrimg[0]) && !empty($Xrimg[0])){
							$Xrimg = $Xrimg[0];
							$feat_imgid = $imagelibrary->InsertImg($Xrimg['img_title'], $Xrimg['img_url'], $Xrimg['parentfolder_id'], $Xrimg['subfolder_id'], $site_id, $Xrimg['default_status'], 1);
						}
						$unit_results = DB::insert('unit_gendata', array('title', 'date_created', 'date_modified', 'created_by', 'site_id', 'access_id', 'urlkey', 'feat_img', 'feat_vid', 'type_id', 'musprim_id', 'equip_id', 'mech_id', 'level_id', 'sport_id', 'force_id', 'descbr', 'descfull', 'hits', 'default_status'))->values(array($this->escapeStr($values['title']), $datetime, $datetime, $userid, $site_id, $user_access, $values['urlkey'], $values['feat_img'], $feat_imgid, $values['type_id'], $values['musprim_id'], $values['equip_id'], $values['mech_id'], $values['level_id'], $values['sport_id'], $values['force_id'], $values['descbr'], $values['descfull'], $values['hits'], $values["default_status"]))->execute();
						$newunitid = $unit_results[0] ? $unit_results[0] : '';
						if($method == 'default' && $newunitid){
							$sqlupdate = "Update unit_gendata set default_unit_id = '".$newunitid."' Where unit_id ='" . $xrRecIdArr[$cnt] . "'";
							DB::query(Database::UPDATE, $sqlupdate)->execute();
						}
					}
				} else {
					return false;
				}
				if(!empty($newunitid)){
					if($method == 'copy' || $method == 'de_copy' || $method == 'sample' || $method == 'default'){
						$this->insertActivityFeed(5, 22, $xrRecId);
					}elseif($method=='new_create'){
						$this->insertActivityFeed(5, 1, $newunitid);
					}else{
					}
					if(!empty($getmusothdata) && count($getmusothdata)>0){
						foreach($getmusothdata as $muscvalues){
							$unit_results = DB::insert('unit_musoth', array('unit_id', 'musoth_id'))->values(array($newunitid, $muscvalues['musoth_id']))->execute();
						}
					}
					if(!empty($getequipothdata) && count($getequipothdata)>0){
						foreach($getequipothdata as $equipvalues){
							$unit_results = DB::insert('unit_equipoth', array('unit_id', 'equipoth_id'))->values(array($newunitid, $equipvalues['equipoth_id']))->execute();
						}
					}
					if(!empty($getseqdata) && count($getseqdata)>0){
						foreach($getseqdata as $seqvalues){
							$unit_results = DB::insert('unit_seq', array('unit_id', 'seq_img', 'seq_desc', 'seq_order'))->values(array($newunitid, $seqvalues['seq_img'], $seqvalues['seq_desc'], $seqvalues['seq_order']))->execute();
						}
					}
					if(!empty($gettaglist) && count($gettaglist)>0){
						foreach($gettaglist as $tagvalues){
							$unit_results = DB::insert('unit_tag', array('tag_id', 'unit_id', 'created_by'))->values(array($tagvalues['tag_id'], $newunitid, $userid))->execute();
						}
					}
				}
				return true;
			} elseif ($type == 'multiple') {
				for ($cnt = 0; $cnt < count($xrRecIdArr); $cnt++) {
					$sql = "SELECT * FROM unit_gendata WHERE unit_id=" . $xrRecIdArr[$cnt];
					$XrRecdata = DB::query(Database::SELECT, $sql)->execute()->as_array();
					if ($XrRecdata != null && count($XrRecdata) > 0) {
						foreach ($XrRecdata as $values) {
							if($method == 'default')
								$values["default_status"]=1;
							elseif($method == 'sample')
								$values["default_status"]=2;
							else
								$values["default_status"]=0;

							$sqlimg = "SELECT * FROM img WHERE img_id=".$values['feat_img'];
							$Xrimg = DB::query(Database::SELECT,$sqlimg)->execute()->as_array();
							if(isset($Xrimg[0]) && !empty($Xrimg[0])){
								$Xrimg = $Xrimg[0];
								$feat_imgid = $imagelibrary->InsertImg($Xrimg['img_title'], $Xrimg['img_url'], $Xrimg['parentfolder_id'], $Xrimg['subfolder_id'], $site_id, $Xrimg['default_status'], 1);
							}
							$unit_results = DB::insert('unit_gendata', array('title', 'date_created', 'date_modified', 'created_by', 'site_id', 'access_id', 'urlkey', 'feat_img', 'feat_vid', 'type_id', 'musprim_id', 'equip_id', 'mech_id', 'level_id', 'sport_id', 'force_id', 'descbr', 'descfull', 'hits', 'default_status'))->values(array($this->escapeStr($values['title']), $datetime, $datetime, $userid, $site_id, $user_access, $values['urlkey'], $feat_imgid, $values['feat_vid'], $values['type_id'], $values['musprim_id'], $values['equip_id'], $values['mech_id'], $values['level_id'], $values['sport_id'], $values['force_id'], $values['descbr'], $values['descfull'], $values['hits'], $values["default_status"]))->execute();
							$newunitid = $unit_results[0] ? $unit_results[0] : '';
							if($method == 'default' && $newunitid){
								$sqlupdate = "Update unit_gendata set default_unit_id = '".$newunitid."' Where unit_id ='" . $xrRecIdArr[$cnt] . "'";
								DB::query(Database::UPDATE, $sqlupdate)->execute();
							}
						}
					}else{
						return false;
					}
					if(!empty($newunitid)){
						if($method == 'copy' || $method == 'de_copy' || $method == 'sample' || $method == 'default'){
							$this->insertActivityFeed(5, 22, $xrRecIdArr[$cnt]);
						}elseif($method=='new_create'){
							$this->insertActivityFeed(5, 1, $newunitid);
						}else{
						}
						if(!empty($getmusothdata) && count($getmusothdata)>0){
							foreach($getmusothdata as $muscvalues){
								$unit_results = DB::insert('unit_musoth', array('unit_id', 'musoth_id'))->values(array($newunitid, $muscvalues['musoth_id']))->execute();
							}
						}
						if(!empty($getequipothdata) && count($getequipothdata)>0){
							foreach($getequipothdata as $equipvalues){
								$unit_results = DB::insert('unit_equipoth', array('unit_id', 'equipoth_id'))->values(array($newunitid, $equipvalues['equipoth_id']))->execute();
							}
						}
						if(!empty($getseqdata) && count($getseqdata)>0){
							foreach($getseqdata as $seqvalues){
								$unit_results = DB::insert('unit_seq', array('unit_id', 'seq_img', 'seq_desc', 'seq_order'))->values(array($newunitid, $seqvalues['seq_img'], $seqvalues['seq_desc'], $seqvalues['seq_order']))->execute();
							}
						}
						if(!empty($gettaglist) && count($gettaglist)>0){
							foreach($gettaglist as $tagvalues){
								$unit_results = DB::insert('unit_tag', array('tag_id', 'unit_id', 'created_by'))->values(array($tagvalues['tag_id'], $newunitid, $userid))->execute();
							}
						}
					}
				}
				return true;
			}
		} elseif ($exerciseRecord == 'exerciseRecord' && $method == 'delete') {
			if ($type == 'single') {
				$sql = "UPDATE unit_gendata SET status_id = 4, date_modified='".$datetime."' WHERE unit_id = " . $xrRecId . "";
			} else {
				$sql = "UPDATE unit_gendata SET status_id = 4, date_modified='".$datetime."' WHERE unit_id IN ( " . $xrRecId . " )";
			}
			$recdel = DB::query(Database::UPDATE,$sql)->execute();
	  		if($recdel){
	  			if(is_array($xrRecIdArr)){
					foreach ($xrRecIdArr as $key => $value) {
						$this->insertActivityFeed(5, 2, $value);
					}
				}else{
					$this->insertActivityFeed(5, 2, $xrRecIdArr);
				}
				return true;
			}
			return false;
		}
	}
	public function get_status()
	{
		$statusselect = "SELECT * FROM status";
		$query        = DB::query(Database::SELECT, $statusselect);
		$status       = $query->execute()->as_array();
		return $status;
	}
	public function get_status_by_id($unitid)
	{
		$statusselect = "SELECT * FROM unit_gendata WHERE unit_id=" . $unitid;
		$query        = DB::query(Database::SELECT, $statusselect);
		$status       = $query->execute()->as_array();
		return $status;
	}
	public function exercise_update_status($unitid, $unit_status, $featured)
	{
		$sql    = "Update unit_gendata set status_id = " . $unit_status . " , featured = " . $featured . "   Where unit_id ='" . $unitid . "'";
		//echo $sql; die;
		$result = DB::query(Database::UPDATE, $sql)->execute();
		return $result;
	}
	public function getMusOthByUnitId($unitId){
		$musothsql = "SELECT um.* FROM `unit_musoth` um LEFT JOIN `unit_muscle` ums ON um.musoth_id = ums.muscle_id LEFT JOIN `unit_gendata` ug ON um.unit_id = ug.unit_id WHERE um.unit_id=".$unitId." AND um.status_id=1";
		$query 	= DB::query(Database::SELECT,$musothsql);
		$list 	= $query->execute()->as_array();
		return $list;
  }
  public function getEquipOthByUnitId($unitId){
		$equipothsql = "SELECT ueo.* FROM `unit_equipoth` ueo LEFT JOIN `unit_equip` ue ON ueo.equipoth_id = ue.equip_id LEFT JOIN `unit_gendata` ug ON ueo.unit_id = ug.unit_id WHERE ueo.unit_id=".$unitId." AND ueo.status_id=1";
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
  public function getUnitTagsById($unit_id, $copyflag=0) {
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		if(($useraccess==6 || $useraccess==7) && $copyflag==0){
			$itemsql = "SELECT u.unit_id, t.tag_id, t.tag_title, ut.created_by FROM `unit_gendata` u JOIN `unit_tag` ut ON u.unit_id=ut.unit_id JOIN `tag` t ON ut.tag_id=t.tag_id WHERE u.unit_id=".$unit_id." AND ut.created_by=".$userid." AND u.site_id=".$site_id." ORDER BY u.unit_id DESC";
		}else{
			$itemsql = "SELECT u.unit_id, t.tag_id, t.tag_title, ut.created_by FROM `unit_gendata` u JOIN `unit_tag` ut ON u.unit_id=ut.unit_id JOIN `tag` t ON ut.tag_id=t.tag_id WHERE u.unit_id=".$unit_id." AND u.site_id=".$site_id." ORDER BY u.unit_id DESC";
		}
		$query 	= DB::query(Database::SELECT,$itemsql);
		$itemlist = $query->execute()->as_array();
		return $itemlist;
  }
  	/* Activity Feed */
	public function insertActivityFeed($feedtype, $actiontype, $typeid, $activityjson = array()){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$activity_feed = array();
		$activity_feed["feed_type"] = $feedtype; // This get from feed_type table
		$activity_feed["action_type"] = $actiontype; // This get from action_type table  
		$activity_feed["type_id"] = $typeid; // Workout Id or User id or Exercise setid or image id or workout folder id or tag id
		$activity_feed["site_id"] = $site_id;
		$activity_feed["user"] = Auth::instance()->get_user()->pk();
		if(!empty($activityjson) && count($activityjson) > 0){
			$activity_feed["json_data"] = json_encode($activityjson);    // if need to encode data and store
		}
		$activity_result = Helper_Common::createActivityFeed($activity_feed);
		return true;
	}
}
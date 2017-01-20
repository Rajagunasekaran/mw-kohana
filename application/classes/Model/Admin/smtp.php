<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class Model_Admin_smtp extends Model {
	public function insertSmtpData($array)
	{
		$results = DB::insert('smtp', array('smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass','smtp_from','smtp_replyto','site_id'))
				->values(array($array['smtphost'], $array['smtpport'], $array['smtpuser'], $array['smtppass'],$array['smtpfrom'],$array['smtpreplyto'],$array['site_id']))->execute();
		return $results[0];
	}
	public function insertDeliveryData($array)
	{
		$results = DB::insert('email_template_delivery', array('delivery_name', 'template_id', 'is_rightaway', 'send_date','triggerby_days','triggerby_hours','is_active','site_id','created_date','modified_date'))
				->values(array($array['delivery_name'], $array['template_id'], $array['is_rightaway'], $array['send_date'],$array['triggerby_days'],$array['triggerby_hours'],$array['is_active'],$array['created_date'],$array['site_id'],$array['modified_date']))->execute();
		return $results[0];
	}
	public function insertTemplateName($array)
	{
		$results = DB::insert('email_template', array('template_name'))
				->values(array($array['templatename']))->execute();
		return $results[0];
	}
	public function getSmtpDetails($field,$condition=1)
	{
		$sql = "SELECT ".$field." FROM smtp where ".$condition;
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
        return $list;
	}
	public function updateSmtp($array,$id){
		$query = DB::update('smtp')->set(array(
											'smtp_host' 	=> $array['smtphost'], 
											'smtp_port' 	=> $array['smtpport'], 
											'smtp_user' 	=> $array['smtpuser'], 
											'smtp_pass' 	=> $array['smtppass'],
											'smtp_from' 	=> $array['smtpfrom'],
											'smtp_replyto'	=> $array['smtpreplyto'],
											'site_id'		=> $array['site_id'],
											)
										)->where('smtp_id', '=', $id)->execute();
		return $query[0];
	}
	public function updateDelivery($array,$id){
		$query = DB::update('email_template_delivery')->set(array(
											'delivery_name' 	=> $array['delivery_name'], 
											'template_id' 	=> $array['template_id'], 
											'is_rightaway' 	=> $array['is_rightaway'], 
											'send_date' 	=> $array['send_date'],
											'triggerby_days' 	=> $array['triggerby_days'],
											'triggerby_hours'	=> $array['triggerby_hours'],
											'is_active'	=> $array['is_active'],
											'site_id' => $array['site_id'],
											'modified_date'	=> $array['modified_date']
											)
										)->where('delivery_id', '=', $id)->execute();
		return $query[0];
	}
	public function deleteSmtp($id,$site_id){
		$query = DB::update('smtp')->set(array('smtp_active' => '0','site_id' => $site_id))->where('smtp_id', '=', $id)->execute();
		return $query[0];
	}
	public function deleteDelivery($id){
		$query = DB::update('email_template_delivery')->set(array('is_delete' => '1'))->where('delivery_id', '=', $id)->execute();
		return $query[0];
	}
	public function insertEmailTemplate($array)
	{
		$results = DB::insert('email_template', array('template_name','subject','body','smtp_id','site_id','status'))
				->values(array($array['template_name'],addslashes($array['subject']),addslashes($array['body']),$array['smtp_id'],$array['site_id'],$array['status']))->execute();
		return $results[0];
	}
	
	public function sharetEmailTemplate($share_site_id, $site_id, $template_id)
	{ 
		$share_cnt = 0;
		$sel_val  = DB::query(Database::SELECT, "SELECT * FROM email_template  WHERE template_id = ".$template_id." AND site_id = ".$site_id."")->execute()->as_array();
		if(!empty($sel_val)){
			for($i=0;$i<count($share_site_id); $i++){
				$chk_tmp  = DB::query(Database::SELECT, "SELECT * FROM email_template  WHERE template_name = '".htmlspecialchars($sel_val[0]['template_name'], ENT_QUOTES)."' AND site_id = ".$share_site_id[$i]." AND status = 1")->execute()->as_array();
				if(empty($chk_tmp) ){
					DB::insert('email_template', array('template_name','subject','body','smtp_id','site_id','status'))
						->values(array($sel_val[0]['template_name'],$sel_val[0]['subject'],$sel_val[0]['body'],$sel_val[0]['smtp_id'],$share_site_id[$i],$sel_val[0]['status']))->execute();
					$share_cnt++;	
				}		
			}			
		return $share_cnt;
		}	
	}	
	public function getEmailTemplate($field,$condition=1)
	{
		$sql = "SELECT ".$field." FROM email_template where ".$condition;
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
		if(isset($list[0]['body']))
			$list[0]['body'] = stripslashes($list[0]['body']);
		if(isset($list[0]['subject']))
			$list[0]['subject'] = stripslashes($list[0]['subject']);
        return $list;
	}
	
	public function getEmailvariable($field,$condition=1)
	{
		$sql = "SELECT ".$field." FROM email_variable as emv left join users as usr on usr.id = created_by  where ".$condition; //echo $sql; //die;
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
        return $list;
	}
	public function updateEmailTemplate($updateStr,$condtnStr){
		$sql = "update email_template set ".$updateStr." WHERE ".$condtnStr; 
		$query = DB::query(Database::UPDATE,$sql);						
		return $query->execute();
	}
	
	public function updateEmailVariable($updateStr,$condtnStr){
		$sql = "update email_variable set ".$updateStr." WHERE ".$condtnStr;				 
		$query = DB::query(Database::UPDATE,$sql);						
		return $query->execute();
	}
	
	public function getEmailTemplateType($field,$condition=1)
	{
		$sql = "SELECT ".$field." FROM email_template_type where ".$condition;
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
        return $list;
	}
	public function insertEmailTemplateType($array)
	{
		$results = DB::insert('email_template_type', array('type_name','template_id','site_id'))
				->values(array($array['type_name'],$array['template_id'],$array['site_id']))->execute();
		return $results[0];
	}
	public function updateEmailTemplateType($updateStr,$condtnStr){
		$sql = "update email_template_type set ".$updateStr." WHERE ".$condtnStr;				 
		$query = DB::query(Database::UPDATE,$sql);						
		return $query->execute();
	}
	public function getTestEmails($field,$condition=1)
	{
		$sql = "SELECT ".$field." FROM test_email where ".$condition;
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
        return $list;
	}
	public function insertTestEmail($array)
	{
		$results = DB::insert('test_email', array('user_id','test_email','site_id'))
				->values(array($array['user_id'],$array['test_email'],$array['site_id']))->execute();
		return $results[0];
	}
	public function getSendingMailTemplate($whereArray = array())
	{
		$whereClause = '';
		if(count($whereArray)>0)
			foreach($whereArray as $keys => $val)
				$whereClause .= " AND ett.".$keys." ='".$val."'";

		$sql = "SELECT et.*,smtp.* FROM email_template_type as ett join email_template as et on et.template_id = ett.template_id left join smtp as smtp on et.smtp_id = smtp.smtp_id and smtp.smtp_active=1 where et.status=1 ".$whereClause." limit 1";
		
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
		if(isset($list[0]['body']))
			$list[0]['body'] = stripslashes($list[0]['body']);
		if(isset($list[0]['subject']))
			$list[0]['subject'] = stripslashes($list[0]['subject']);
        return (isset($list[0]) ? $list[0] : $list );
	}
	public function getSMTPbyMailTemplate($whereArray = array())
	{
		$whereClause = '';
		if(count($whereArray)>0)
			foreach($whereArray as $keys => $val)
				$whereClause .= " AND ".$keys." ='".$val."'";

		$sql = "SELECT et.*,s.* FROM email_template as et left join smtp as s on et.smtp_id = s.smtp_id  where et.status=1 and 	s.smtp_active=1 ".$whereClause." limit 1";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
        if(isset($list) && count($list)>0) {
			if(isset($list[0]['body']))
				$list[0]['body'] = stripslashes($list[0]['body']);
			if(isset($list[0]['subject']))
				$list[0]['subject'] = stripslashes($list[0]['subject']);
			return $list[0];
		} else {
			return false;
		}
		
	}
	public function getDeliveryDetails($field,$condition=1)
	{
		$sql = "SELECT ".$field." FROM email_template_delivery as etd join email_template as et on et.template_id = etd.template_id where ".$condition;
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
		if(isset($list[0]['body']))
			$list[0]['body'] = stripslashes($list[0]['body']);
		if(isset($list[0]['subject']))
			$list[0]['subject'] = stripslashes($list[0]['subject']);
        return $list;
	}
	function array_value_recursive($key, array $arr){
		$val = array();
		array_walk_recursive($arr, function($v, $k) use($key, &$val){
		if($k == $key) array_push($val, $v);
		});
		return count($val) > 1 ? $val : array_pop($val);
	}
	
	public function insertEmailvariable($array,$site_id,$loginUserId)
	{	
		$name_arr = str_split($array['name']) ;
		//echo $name_arr[count($name_arr)-1]; die;
		if($name_arr[0]=="[" && $name_arr[count($name_arr)-1] =="]"){
			$name = $array['name'];
		}else{
			$name = "[".$array['name']."]";
		}	//echo $name ; die;
		$sel_sql = "SELECT * FROM email_variable WHERE name='".htmlspecialchars($array['name'],ENT_QUOTES)."'";
		$query = DB::query(Database::SELECT,$sel_sql);
		$sel_val = $query->execute()->as_array(); //print_r($sel_val); die;
		if(!empty($sel_val)){
			/*$variable_name = substr_replace(htmlspecialchars($name),"_copy]",-1); //die;
			//if($sel_val[0]['variable_id'] == 1){
				$results = DB::insert('email_variable', array('name','variable_content','site_id','created_by'))
					->values(array( $variable_name , htmlspecialchars($array['variable_content'],ENT_QUOTES) ,$site_id,$loginUserId))->execute();
			return $results[0];
			/*}else{
				return 0;
			}	*/
			
			return 0;
		}else{	
			$results = DB::insert('email_variable', array('name','variable_content','site_id','created_by'))
					->values(array( htmlspecialchars($name,ENT_QUOTES) , htmlspecialchars($array['variable_content'],ENT_QUOTES) ,$site_id,$loginUserId))->execute();
			return $results[0];
		}
	}
	public function merge_keywordsByuser($messageArray,$user_list)
	{
		$content = str_replace( array("[","]"), array("<b>", "</b>"), $messageArray );
		preg_match_all ("/<b>(.*)<\/b>/U", $content, $pat_array);
		foreach($pat_array[1] as $key => $val)
		{   
		   
			$mergekeys = '<b>'.$val.'</b>';  
			$val = '['.$val.']';
			$mergevalues = $this -> Get_merge_valueByuser($val,$user_list);
			if(!empty($mergevalues)){
				$content = str_replace( $mergekeys,htmlspecialchars_decode($mergevalues,ENT_QUOTES), $content );
			}else{
				$content = str_replace( $mergekeys,$val, $content );
			}
			
		}
		return $content;
	}
	public function Get_merge_valueByuser($item,$user_list)
	{
		$sel_sql = "SELECT variable_content FROM email_variable WHERE name='".$item."' AND created_by in(".$user_list.") and status != 1"; //echo $sel_sql; 
		$query = DB::query(Database::SELECT,$sel_sql);
		$sel_val = $query->execute()->as_array();
		if(!empty($sel_val)){
			$userDetails = array();
			$site_slug = Session::instance()->get('current_site_slug');
			if(Auth::instance()->logged_in()){
				$userDetails = Auth::instance()->get_user();
				$encryptedmessage = Helper_Common::encryptPassword($userDetails->user_email.'####'.$userDetails->security_code.'####'); 
				$homePageUrl = URL::site(NULL, 'http').(!empty($site_slug) ? $site_slug.'/' : '')."index/autoredirect/".$userDetails->pk()."/".$encryptedmessage;
			}else{
				$homePageUrl = (!empty($site_slug) ? str_replace($site_slug.'/','',URL::base(True)) : URL::base(True)).(!empty($site_slug) ? 'site/'.$site_slug.'/contact/' : '');
			}
			return str_replace('[homePageUrl]',$homePageUrl,$sel_val[0]['variable_content']);
			//return $sel_val[0]['variable_content'];
		}
	}
	public function merge_keywords($messageArray,$siteid)
	{
		$content = str_replace( array("[","]"), array("<b>", "</b>"), $messageArray );
		preg_match_all ("/<b>(.*)<\/b>/U", $content, $pat_array);
		foreach($pat_array[1] as $key => $val)
		{   
		   
			$mergekeys = '<b>'.$val.'</b>';  
			$val = '['.$val.']';
			$siteid = (!empty($siteid) ? $siteid : '1');
			$mergevalues = $this -> Get_merge_value($val,$siteid);
			if(!empty($mergevalues)){
				$content = str_replace( $mergekeys,htmlspecialchars_decode($mergevalues,ENT_QUOTES), $content );
			}else{
				$content = str_replace( $mergekeys,$val, $content );
			}
			
		}
		return $content;
	}
	public function Get_merge_value($item,$siteid)
	{
		$sel_sql = "SELECT variable_content FROM email_variable WHERE name='".$item."' AND site_id in(".$siteid.") and status != 1"; //echo $sel_sql; 
		$query = DB::query(Database::SELECT,$sel_sql);
		$sel_val = $query->execute()->as_array();
		if(!empty($sel_val)){
			$userDetails = array();
			$site_slug = Session::instance()->get('current_site_slug');
			if(Auth::instance()->logged_in()){
				$userDetails = Auth::instance()->get_user();
				$encryptedmessage = Helper_Common::encryptPassword($userDetails->user_email.'####'.$userDetails->security_code.'####'); 
				$homePageUrl = URL::site(NULL, 'http').(!empty($site_slug) ? $site_slug.'/' : '')."index/autoredirect/".$userDetails->pk()."/".$encryptedmessage;
			}else{
				$homePageUrl = (!empty($site_slug) ? str_replace($site_slug.'/','',URL::base(True)) : URL::base(True)).(!empty($site_slug) ? 'site/'.$site_slug.'/contact/' : '');
			}
			return str_replace('[homePageUrl]',$homePageUrl,$sel_val[0]['variable_content']);
			//return $sel_val[0]['variable_content'];
		}else{
			$sel_sql = "SELECT variable_content FROM email_variable WHERE name='".$item."' AND site_id in('1') and status != 1";
			$query = DB::query(Database::SELECT,$sel_sql);
			$sel_val = $query->execute()->as_array();
			if(!empty($sel_val)){
				$userDetails = array();
				$site_slug = Session::instance()->get('current_site_slug');
				if(Auth::instance()->logged_in()){
					$userDetails = Auth::instance()->get_user();
					$encryptedmessage = Helper_Common::encryptPassword($userDetails->user_email.'####'.$userDetails->security_code.'####'); 
					$homePageUrl = URL::site(NULL, 'http').(!empty($site_slug) ? $site_slug.'/' : '')."index/autoredirect/".$userDetails->pk()."/".$encryptedmessage;
				}else{
					$homePageUrl = (!empty($site_slug) ? str_replace($site_slug.'/','',URL::base(True)) : URL::base(True)).(!empty($site_slug) ? 'site/'.$site_slug.'/contact/' : '');
				}
				return str_replace('[homePageUrl]',$homePageUrl,$sel_val[0]['variable_content']);
			}
		}
	}
	public function get_admin_user(){
		
		$sql = "SELECT * FROM roles_users where role_id=2 ";
		$query = DB::query(Database::SELECT,$sql);
		$user = $query->execute()->as_array();
		return $user;
	}

	public function insertDevice($array)
	{ //print_r($array); die;
		$sql  = "SELECT * FROM device_integrations WHERE name='".$array['name']."'"; 
		$query = DB::query(Database::SELECT,$sql);
		$device = $query->execute()->as_array();  
		if(count($device) == 0 ){
			$results = DB::insert('device_integrations', array('name'))
					->values(array($array['name']))->execute();
			return $results[0];
		}else{
			
			if($device[0]['status'] == 2){
				$sql = "update device_integrations set status=0 WHERE id=".$device[0]['id'];		 
				$query = DB::query(Database::UPDATE,$sql);						
				$results = $query->execute();
				return $results; 
			}else{
				return 0;		
			}	
		}	
	}	
	
	public function getDevice($field,$condition=1)
	{
		$sql = "SELECT ".$field." FROM device_integrations where ".$condition;
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
        return $list;
	}
	
	public function updateDevice($updateStr,$condtnStr){
		$sql = "update device_integrations set ".$updateStr." WHERE ".$condtnStr;		 
		$query = DB::query(Database::UPDATE,$sql);						
		return $query->execute();
	}
}
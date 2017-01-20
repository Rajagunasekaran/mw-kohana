<?php

defined('SYSPATH') or die('No direct access allowed.');

class Model_homepage extends Model
{
	public function subscribe($post){
		$results = DB::insert('sitesubscriber', array('site_id', 'subscriber_email'))
				->values(array($post["siteid"],$post["email"]))->execute();
		return $results[0];
	}
	public function get_site_number_of_users($siteid='')
	{
		return 4500;	
	}
	public function get_site_number_of_trainers($siteid='')
	{
		return 500;	
	}
	public function get_site_number_of_exercisesets($siteid='')
	{
		return 750;
	}
	public function get_sites($slug_id){
		$sql 	= "SELECT * FROM sites WHERE slug='$slug_id' ";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		if($list)
			return $list[0];
        return FALSE;
	}
	public function get_sitesby_id($site_id){
		$sql 	= "SELECT * FROM sites WHERE id='$site_id' AND is_active=1 AND is_deleted=0 ";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		if($list)
			return $list[0];
        return FALSE;
	}
	
	public function getSliderDetails($search_title='')
	{
		$sql 	= "SELECT * From cms_homepage_slider where is_delete=0 ".((isset($search_title) && !empty($search_title)) ? ' AND s_title like "'.$search_title.'%"' : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getSliderUnique($id = '0')
	{
		$sql 	= "SELECT * FROM cms_homepage_slider WHERE is_delete = 0  ".((isset($id) && !empty($id)) ? ' AND id = "'.$id.'"' : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		if($list)
			return $list[0];
        return FALSE;
	}
	
	public function insertSliderData($array)
	{
		$results = DB::insert('cms_homepage_slider', array('s_title', 's_content', 's_url', 's_image', 'is_active', 'date_created', 'date_modified'))
				->values(array($array['s_title'], $array['s_content'], $array['s_url'], $array['s_image'], $array['is_active'], $array['date_created'],$array['date_modified']))->execute();
		return $results[0];
	}
	public function updateSliderData($updateArray,$sliderId)
	{
		return DB::update('cms_homepage_slider')->set(array('s_title' =>$updateArray['s_title'],'s_content' =>$updateArray['s_content'],'s_url'=>$updateArray['s_url'],'s_image'=>$updateArray['s_image'],'is_active'=>$updateArray['is_active'],'date_modified'=>$updateArray['date_modified']))->where('id', '=', $sliderId)->execute();
	}
	public function deleteSlider($sliderId){
		$sql = "update cms_homepage_slider set is_delete = '1' WHERE id = ".$sliderId;				 
		$query = DB::query(Database::UPDATE,$sql);						
		return $query->execute();
	}
	/* homepage block table*/
	public function getBlockDetails($search_title = '')
	{
		$sql 	= "SELECT * From cms_homepage_block where is_delete=0 ".((isset($search_title) && !empty($search_title)) ? ' AND b_title like "'.$search_title.'%"' : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getBlockUnique($blockId)
	{
		$sql 	= "SELECT * FROM cms_homepage_block WHERE is_delete = 0  ".((isset($blockId) && !empty($blockId)) ? ' AND id = "'.$blockId.'"' : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		if($list)
			return $list[0];
        return FALSE;
	}
	
	
	
	
	
	public function insertTestimonialData($array)
	{
		$results = DB::insert('cms_homepage_testimonial', array('t_title','t_user', 't_description', 'is_active', 'date_created', 'date_modified'))
				->values(array($array['t_title'],$array['t_user'],   $array['t_description'], $array['is_active'], $array['date_created'], $array['date_modified']))->execute();
		return $results[0];
	}
	public function insertBlockData($array)
	{
		$results = DB::insert('cms_homepage_block', array('b_title', 'b_url', 'b_image', 'b_description', 'is_active', 'date_created', 'date_modified'))
				->values(array($array['b_title'], $array['b_url'], $array['b_image'], $array['b_description'], $array['is_active'], $array['date_created'], $array['date_modified']))->execute();
		return $results[0];
	}
	public function updateBlockData($updateArray,$blockId)
	{
		return DB::update('cms_homepage_block')->set(array('b_title' =>$updateArray['b_title'],'b_url'=>$updateArray['b_url'],'b_image'=>$updateArray['b_image'],'b_description'=>$updateArray['b_description'],'is_active'=>$updateArray['is_active'],'date_modified'=>$updateArray['date_modified']))->where('id', '=', $blockId)->execute();
	}
	public function deleteBlock($blockId){
		$sql = "update cms_homepage_block set is_delete = '1' WHERE id = ".$blockId;				 
		$query = DB::query(Database::UPDATE,$sql);						
		return $query->execute();
	}
	/*partner table*/
	public function getPartnerDetails($search_title = '')
	{
		$sql 	= "SELECT * From cms_homepage_partner where is_delete=0 ".((isset($search_title) && !empty($search_title)) ? ' AND p_title like "'.$search_title.'%"' : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	public function getPartnerUnique($id = '0')
	{
		$sql 	= "SELECT * FROM cms_homepage_partner WHERE is_delete = 0  ".((isset($id) && !empty($id)) ? ' AND id = "'.$id.'"' : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		if($list)
			return $list[0];
        return FALSE;
	}
	
	public function insertPartnerData($array)
	{
		$results = DB::insert('cms_homepage_partner', array('p_title', 'p_url', 'p_image', 'is_active', 'date_created', 'date_modified'))
				->values(array($array['p_title'], $array['p_url'], $array['p_image'], $array['is_active'], $array['date_created'],$array['date_modified']))->execute();
		return $results[0];
	}
	public function updatePartnerData($updateArray,$patnerId)
	{
		return DB::update('cms_homepage_partner')->set(array('p_title' =>$updateArray['p_title'],'p_url'=>$updateArray['p_url'],'p_image'=>$updateArray['p_image'],'is_active'=>$updateArray['is_active'],'date_modified'=>$updateArray['date_modified']))->where('id', '=', $patnerId)->execute();
	}
	public function deletePartner($patnerId){
		$sql = "update cms_homepage_partner set is_delete = '1' WHERE id = ".$patnerId;				 
		$query = DB::query(Database::UPDATE,$sql);						
		return $query->execute();
	}
	
	
	
	public function deleteChallenger($patnerId){
		$sql = "update cms_homepage_testimonial set is_delete = '1' WHERE id = ".$patnerId;				 
		$query = DB::query(Database::UPDATE,$sql);						
		return $query->execute();
	}
	
	public function get_all_partnerlogo()
	{
		$sql 	= "SELECT * From cms_homepage_partner where is_delete=0 and is_active=1";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	
	public function get_block_content()
	{
		$sql 	= "SELECT * From cms_homepage_block where is_delete=0 and is_active=1";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	
	
	
	public function get_site_block_content($site_id)
	{
		//$sql 	= "SELECT * From site_cms_homepage_blocks where is_delete=0 and is_active=1 and site_id=$site_id ";
		$sql 	= "SELECT * From siteblocks where is_delete=0 and is_active=1 and site_id=$site_id ";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	
	
	public function site_cms_homepage_testimonials($site_id)
	{
		//$sql 	= "SELECT * From site_cms_homepage_testimonials where is_delete=0 and is_active=1 and site_id=$site_id ";
		$sql 	= "SELECT * From sitetestimonials where is_delete=0 and is_active=1 and site_id=$site_id ";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	
	
	public function site_cms_homepage_content($site_id)
	{
		//$sql 	= "SELECT * From site_cms_homepages where is_delete=0  and site_id=$site_id limit 0,1";
		$sql 	= "SELECT * From sitehomepages where is_delete=0  and site_id=$site_id limit 0,1";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	
	public function site_cms_social_content($site_id)
	{
		$sql 	= "SELECT * From sitesocaialpages where is_delete=0  and site_id=$site_id limit 0,1";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	
	public function site_slider($site_id)
	{
		//$sql 	= "SELECT * From site_cms_homepage_sliders where is_delete=0 and  is_active=1  and site_id=$site_id ";
		$sql 	= "SELECT * From sitesliders where is_delete=0 and  is_active=1  and site_id=$site_id ";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	
	public function site_partnerlogo($site_id)
	{
		//$sql 	= "SELECT * From site_cms_homepage_partners where is_delete=0  and site_id=$site_id ";
		$sql 	= "SELECT * From sitepartners where is_delete=0  and site_id=$site_id ";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	
	public function get_all_slider()
	{
		$sql 	= "SELECT * From cms_homepage_slider where is_delete=0 and is_active=1";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	
	
	public function get_number_of_subscriber()
	{
	    $sql 	= "SELECT COUNT(*) AS total  From newsletters";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
			
        return $list[0]['total'] ;
	}
	
	public function get_number_of_users()
	{
	    $sql 	= "SELECT COUNT(*) AS total  From users ";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
			
        return $list[0]['total'] ;
	}
	
	
	
	
	/*
	public function get_site_number_of_users($site_id)
	{
	    $sql 	= "SELECT COUNT(*) AS total  From users where site_id=$site_id";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
			
        return $list[0]['total'] ;
	}*/
	public function get_goals_achived($site_id)
	{
	    $sql 	= "SELECT SUM(g.achieved) AS total From user_goals as g, users as u where u.id=g.user_id and u.site_id=$site_id ";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		
        return $list[0]['total'] ;
	}
	
	public function get_kg_losts($site_id)
	{
	    $sql 	= "
		SELECT SUM(g.kg_lost) AS total From user_weights as g, users as u where u.id=g.user_id and u.site_id=$site_id ";
		//"SELECT SUM(kg_lost) AS total From user_weights";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		
        return $list[0]['total'] ;
	}
	
	public function getTestimonial($search_title = '')
	{
		$sql 	= "SELECT * From cms_homepage_testimonial where is_delete=0 ".((isset($search_title) && !empty($search_title)) ? ' AND p_title like "'.$search_title.'%"' : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	
	
	  public function updateTestimonialData($updateArray,$patnerId)
	{
		return DB::update('cms_homepage_testimonial')->set(array('t_title' =>$updateArray['t_title'],'t_description'=>$updateArray['t_description'],'t_user'=>$updateArray['t_user'],'is_active'=>$updateArray['is_active'],'date_modified'=>$updateArray['date_modified']))->where('id', '=', $patnerId)->execute();
	}
	  
	  
	public function getTestimonialUnique($id = '0')
	{
		 $sql 	= "SELECT * FROM cms_homepage_testimonial WHERE is_delete = 0  ".((isset($id) && !empty($id)) ? ' AND id = "'.$id.'"' : '');
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		if($list)
			return $list[0];
        return FALSE;
	}
	
	
	
	
	
	
	
	
	
	public function get_all_testimonial()
	{
		$sql 	= "SELECT * From cms_homepage_testimonial where is_delete=0 and is_active=1";
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
        return $list;
	}
	
	
	public function getHomepageUnique($id = '0')
	{
		 $sql 	= "SELECT * FROM cms_homepage WHERE id =".$id;
		$query 	= DB::query(Database::SELECT,$sql);
		$list 	= $query->execute()->as_array();
		if($list)
			return $list[0];
        return FALSE;
	}
	 
	
	 public function updateHomepageData($updateArray,$patnerId)
	{
		return DB::update('cms_homepage')->set(array('video'=>$updateArray['video'],'footer_content'=>$updateArray['footer_content'],'description'=>$updateArray['description'],'social_twitter_url'=>$updateArray['social_twitter_url'],'social_facebook_url'=>$updateArray['social_facebook_url'],'social_linkedin_url'=>$updateArray['social_linkedin_url'],'date_modified'=>$updateArray['date_modified']))->where('id', '=', $patnerId)->execute();
	}
	  
	
	
}
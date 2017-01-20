<?php
defined('SYSPATH') or die('No direct access allowed.');

class Model_imagelibrary extends Model
{
	public function getParentImgFolder(){
		$pfoldsql = "SELECT * FROM `img_folders` WHERE folder_status=0 AND sub_folder_level='0' and folder_id!=6 ORDER BY folder_id ASC";
		$query = DB::query(Database::SELECT,$pfoldsql);
		$pfoldlist = $query->execute()->as_array();
		return $pfoldlist;
	}
	public function getSubImgFolder($fid=0){
		$sfoldsql = "SELECT * FROM `img_folders` WHERE folder_status=0 AND sub_folder_level='1' ".($fid =='2' ? ' AND folder_id !=4' : '')." ORDER BY folder_id ASC";
		$query = DB::query(Database::SELECT,$sfoldsql);
		$sfoldlist = $query->execute()->as_array();
		return $sfoldlist;
	}	
	public function getImgFolderName($fid=0){
		$namesql = "SELECT sub_folder_level, folder_id, folder_title FROM `img_folders` WHERE folder_status=0 AND folder_id=".$fid;
		$query = DB::query(Database::SELECT,$namesql);
		$itemname = $query->execute()->as_array();
		return $itemname;
	}
	public function getImgCountByFolder($sfid=0, $fid=0){
		$userid = Auth::instance()->get_user()->pk();
		$siteid = Session::instance()->get('current_site_id');
		$fid    = (($fid==0 || $fid=='') ? '0' : $fid);
		$subfid = (($sfid==0 || $sfid=='') && $fid=='1' ? '4,5' : (($sfid==0 || $sfid=='') && !empty($fid) ? '5' : $sfid));
		$site_tabl = $cond = '';
		if(Helper_Common::hasAccessBySampleImage($siteid) && $fid=='2'){
			$site_tabl = 'JOIN sites s ON img.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = img.img_id AND ds.record_type_id=3 AND ds.site_id in ('.$siteid.'))';
			$cond = ' AND (ds.record_mod_action!=2 OR ds.id is NULL) AND s.is_active = 1 AND s.is_deleted = 0';
		}
		$sql = "SELECT count(*) as imgcnt FROM `img` img LEFT JOIN `img_folders` ifo ON ifo.folder_id=img.parentfolder_id ".$site_tabl."WHERE img.status_id=1 AND img.subfolder_id in (".$subfid.") AND ((img.parentfolder_id=".(Helper_Common::hasAccessBySampleImage($siteid) && $fid==2 ? $fid.' AND img.site_id in ('.$siteid.')) OR img.parentfolder_id=6 ' : (($fid=='1' || $fid=='6') ? $fid : $fid.' AND img.site_id in ('.$siteid.')' ).")" ).' ) '.$cond.($fid=='1' ? ' AND img.user_id='.$userid : '');
		$rescnt = DB::query(Database::SELECT,$sql)->execute()->as_array();
		return $rescnt[0]['imgcnt'];
	}
	public function getFolderImages($sfid=0, $fid=0, $slimit=0, $elimit=10){
		$userid = Auth::instance()->get_user()->pk();
		$siteid = Session::instance()->get('current_site_id');
		$fid    = (($fid==0 || $fid=='') ? '0' : $fid);
		$subfid = (($sfid==0 || $sfid=='') && $fid=='1' ? '4,5' : (($sfid==0 || $sfid=='') && !empty($fid) ? '5' : $sfid));
		$site_tabl = $cond = '';
		if(Helper_Common::hasAccessBySampleImage($siteid) && $fid=='2'){
			$site_tabl = 'JOIN sites s ON img.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = img.img_id AND ds.record_type_id=3 AND ds.site_id in ('.$siteid.'))';
			$cond = ' AND (ds.record_mod_action!=2 OR ds.id is NULL) AND s.is_active = 1 AND s.is_deleted = 0';
		}
		$sql = "SELECT count(*) as imgcnt FROM `img` img LEFT JOIN `img_folders` ifo ON ifo.folder_id=img.parentfolder_id ".$site_tabl."WHERE img.status_id=1 AND img.subfolder_id in (".$subfid.") AND ((img.parentfolder_id=".(Helper_Common::hasAccessBySampleImage($siteid) && $fid==2 ? $fid.' AND img.site_id in ('.$siteid.')) OR img.parentfolder_id=6 ' : (($fid=='1' || $fid=='6') ? $fid : $fid.' AND img.site_id in ('.$siteid.')' ).")" ).' ) '.$cond.($fid=='1' ? ' AND img.user_id='.$userid : '');
		$rescnt = DB::query(Database::SELECT,$sql)->execute()->as_array();

		$itemsql = "SELECT img.*, ifo.folder_title FROM `img` img LEFT JOIN `img_folders` ifo ON ifo.folder_id=img.parentfolder_id ".$site_tabl."WHERE img.status_id=1 AND img.subfolder_id in (".$subfid.") AND ((img.parentfolder_id=".(Helper_Common::hasAccessBySampleImage($siteid) && $fid==2 ? $fid.' AND img.site_id in ('.$siteid.')) OR img.parentfolder_id=6 ' : (($fid=='1' || $fid=='6') ? $fid : $fid.' AND img.site_id in ('.$siteid.')' ).")" ).' ) '.$cond.($fid=='1' ? ' AND img.user_id='.$userid : '')." ORDER BY img.date_modified DESC, img.img_id DESC limit ".$slimit.", ".$elimit;
		$query 	= DB::query(Database::SELECT,$itemsql);
		$itemlist = $query->execute()->as_array();
		$imgfiles=array();
		if($itemlist!=null && count($itemlist)>0){
			foreach($itemlist as $row){
				if(!empty($row['img_url']) && file_exists($row['img_url'])){
					$image = $row['img_url'];
				}else{
					$image ='';
				}
				$imagetags = $this->getImageTags($row['img_id']);
				$default_status = ($row['parentfolder_id'] == 1 && $row['subfolder_id'] == 4 ? 'from Profile Images' : ($row['parentfolder_id'] == 1 && $row['subfolder_id'] == 5 ? 'from Exercise Images' : ($row['parentfolder_id'] == 6 ? (Helper_Common::is_admin() || Helper_Common::is_manager() ? 'from Default Images' : 'from Sample Images') : ($row['parentfolder_id'] == 2 ? 'from Sample Images' : ($row['parentfolder_id'] == 3 ? 'from Shared Images' : '' )))));
				// Packing img listings into array
				$imgfiles[] = array(
					"access_id"			=> $row['access_id'],
					"folder_title"		=> $row['folder_title'],
					"img_id"				=> $row['img_id'],
					"img_title"			=> $row['img_title'],
					"img_type"			=> $row['img_type'],
					"img_url"			=> $image,
					"parentfolder_id"	=> $row['parentfolder_id'],
					"site_id"			=> $row['site_id'],
					"status_id"			=> $row['status_id'],
					"subfolder_id"		=> $row['subfolder_id'],
					"user_id"			=> $row['user_id'],
					"taglist"			=> $imagetags,
					"default"			=> $default_status
				);
			}
		}
		$resArr = array('itemlist'=>$imgfiles, 'rescnt'=>(int)$rescnt[0]['imgcnt'], 'itemcnt'=>count($itemlist));
		return $resArr;
	}
	public function getunitsbytable($tableName){
		$sql = "SELECT * FROM $tableName";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
		return $list;
	}
	public function get_Imageslistbyfilter($filterval){
		$userid = Auth::instance()->get_user()->pk();
		$siteid = Session::instance()->get('current_site_id');
		$imgtitle = $filterval['search_title']; 
		$imgtag = $filterval['search_tag'];
		$imgsort = $filterval['search_sort'];
		$fid = $filterval['fid'];
		$sfid = $filterval['subfid'];
		$fid    = (($fid==0 || $fid=='') ? '0' : $fid);
		$subfid = (($sfid==0 || $sfid=='') && $fid=='1' ? '4,5' : (($sfid==0 || $sfid=='') && !empty($fid) ? '5' : $sfid));
		$site_tabl = $cond = '';
		$slimit = $filterval['slimit'];
		$elimit = $filterval['elimit'];
		$searchval=''; $searchtext=''; $searchtag='';
		if(!empty($imgtitle)){
			$searchtext = $imgtitle; $title_x=1;
		}
		else{
			$searchtext = ''; $title_x=0;
		}
		if(!empty($imgtag)){
			$searchtag = $imgtag; $tag_x=1;
		}
		else{
			$searchtag = ''; $tag_x=0;
		}
		if(empty($imgsort)){
			$imgsort = 'asc';
		}
		if($imgsort == 'asc' || $imgsort == 'desc'){
			$orderBy = 'img.img_title '.strtoupper($imgsort);
		}
		else{
			$orderBy = 'img.'.$imgsort.' DESC';
		}
		$c_filters = $title_x + $tag_x;
		if($c_filters>0){
			$f_start='AND (';
			$f_end=')';
		}else{
			$f_start='';
			$f_end='';
		}

		$f='';
		$e=0;
		if ( $title_x==0 ) {
		}else{  
			$e=1;
			$f.= '(img.img_title LIKE "%'.$searchtext.'%")';
		}		
		
		if ( $tag_x==0 ) {}else{ 
			if(	$e==1 ){ // there's an AND from before this
				$f.=' OR ';
			}else{} // no AND from before this	
			$e=1;
			$f.= 't.tag_title in('.$searchtag.') AND it.created_by='.$userid;
		}
		if(Helper_Common::hasAccessBySampleImage($siteid) && $fid=='2'){
			$site_tabl = 'JOIN sites s ON img.site_id=s.id LEFT JOIN default_site_mod_map as ds ON (ds.record_id = img.img_id AND ds.record_type_id=3 AND ds.site_id in ('.$siteid.'))';
			$cond = ' AND (ds.record_mod_action!=2 OR ds.id is NULL) AND s.is_active = 1 AND s.is_deleted = 0';
		}
		$sql = "SELECT count(*) FROM `img` img LEFT JOIN `img_folders` ifo ON ifo.folder_id=img.parentfolder_id LEFT JOIN `img_tags` it ON img.img_id=it.img_id LEFT JOIN `tag` t ON it.tag_id=t.tag_id ".$site_tabl."WHERE img.status_id=1 AND img.subfolder_id in (".$subfid.") AND ((img.parentfolder_id=".(Helper_Common::hasAccessBySampleImage($siteid) && $fid==2 ? $fid.' AND img.site_id in ('.$siteid.')) OR img.parentfolder_id=6 ' : (($fid=='1' || $fid=='6') ? $fid : $fid.' AND img.site_id in ('.$siteid.')' ).")" ).' ) '.$cond.($fid == '1' ? ' AND img.user_id='.$userid : '')." ".$f_start." ".$f." ".$f_end." GROUP BY img.img_id";
		$rescnt = DB::query(Database::SELECT,$sql)->execute()->as_array();

		$itemsql = "SELECT img.*, ifo.folder_title FROM `img` img LEFT JOIN `img_folders` ifo ON ifo.folder_id=img.parentfolder_id LEFT JOIN `img_tags` it ON img.img_id=it.img_id LEFT JOIN `tag` t ON it.tag_id=t.tag_id ".$site_tabl."WHERE img.status_id=1 AND img.subfolder_id in (".$subfid.") AND ((img.parentfolder_id=".(Helper_Common::hasAccessBySampleImage($siteid) && $fid==2 ? $fid.' AND img.site_id in ('.$siteid.')) OR img.parentfolder_id=6 ' : (($fid=='1' || $fid=='6') ? $fid : $fid.' AND img.site_id in ('.$siteid.')' ).")" ).' ) '.$cond.($fid == '1' ? ' AND img.user_id='.$userid : '')." ".$f_start." ".$f." ".$f_end." GROUP BY img.img_id ORDER BY ".$orderBy.", img.img_id DESC limit ".$slimit.", ".$elimit;
		$query = DB::query(Database::SELECT,$itemsql);
		$itemlist = $query->execute()->as_array();
		$imgfiles = array();
		if($itemlist!=null && count($itemlist)>0){
			foreach($itemlist as $row){
				if(!empty($row['img_url']) && file_exists($row['img_url'])){
					$image = $row['img_url'];
				}else{
					$image = '';
				}
				$imagetags = $this->getImageTags($row['img_id']);
				$default_status = ($row['parentfolder_id'] == 1 && $row['subfolder_id'] == 4 ? 'from Profile Images' : ($row['parentfolder_id'] == 1 && $row['subfolder_id'] == 5 ? 'from Exercise Images' : ($row['parentfolder_id'] == 6 ? (Helper_Common::is_admin() || Helper_Common::is_manager() ? 'from Default Images' : 'from Sample Images') : ($row['parentfolder_id'] == 2 ? 'from Sample Images' : ($row['parentfolder_id'] == 3 ? 'from Shared Images' : '' )))));
				// Packing img listings into array
				$imgfiles[] = array(
					"access_id"			=> $row['access_id'],
					"folder_title"		=> $row['folder_title'],
					"img_id"				=> $row['img_id'],
					"img_title"			=> $row['img_title'],
					"img_type"			=> $row['img_type'],
					"img_url"			=> $image,
					"parentfolder_id"	=> $row['parentfolder_id'],
					"site_id"			=> $row['site_id'],
					"status_id"			=> $row['status_id'],
					"subfolder_id"		=> $row['subfolder_id'],
					"user_id"			=> $row['user_id'],
					"taglist"			=> $imagetags,
					"default"			=> $default_status
				);
			}
		}
		$resArr = array('itemlist'=>$imgfiles, 'rescnt'=>count($rescnt), 'itemcnt'=>count($itemlist));
		return $resArr;
	}
	public function get_tagnames(){
		$sql = "SELECT tag_id, tag_title FROM tag WHERE status_id=1 ORDER BY tag_id ASC";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
		return $list;
	}
	public function getImgType($folderid){
		$typesql = "SELECT * FROM `img_type` WHERE folder_id=".$folderid." limit 1";
		$typeres = DB::query(Database::SELECT,$typesql)->execute()->as_array();
		return $typeres[0]['img_type_id'];
	}
	public function copyRawImage($imgUrl){
		$rootdir = DOCROOT.'assets/images/dynamic/exercise/';
		$imgdir = $rootdir.'img/';
		$thumbdir = $rootdir.'thumb/';
		$data = file_get_contents(trim($imgUrl));
		$file_ext = pathinfo($imgUrl, PATHINFO_EXTENSION);
		$file_ext = strtolower($file_ext);
		if(!empty($data)){
			$now = '_'.(strtotime(Helper_Common::get_default_datetime())+rand()).'.'.$file_ext;
			$imgname = 'img'.$now;
			$thumbname = 'thumb_img'.$now;
			$imgfile = $imgdir.$imgname;
			$thumbfile = $thumbdir.$thumbname;
			$imgfileurl = 'assets/images/dynamic/exercise/img/'.$imgname;
			if(file_put_contents($imgfile, $data)){
				$flag = 1;
			}else{
				$flag = 0;
			}
			if($flag == 1){
				Image::factory($imgfile)->resize(100, 100, Image::AUTO)->save($thumbfile);
			}
			return $imgfileurl;
		}
		return false;
	}
	public function escapeStr($str){
		if($str != "'"){
			$newstr = trim(Database::instance()->escape(trim($str)), "'");
			return $newstr;
		}
		return $str;
	}
	public function InsertImg($imgtitle, $imgurl, $fid, $subfid, $default_status=0, $copyflag=0, $opt_userid=''){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$datetime = Helper_Common::get_default_datetime();
		$userid = !empty($opt_userid) ? $opt_userid : Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		$imgtypeid = $this->getImgType($subfid);
		if($copyflag!=0){
			$imgurl = $this->copyRawImage($imgurl);
		}
		if(!empty($imgurl)){
			$sqlimg = "INSERT INTO `img` (img_title, img_type, img_url, subfolder_id, parentfolder_id, access_id, user_id, site_id, default_status, date_created, date_modified) VALUES ('".$this->escapeStr($imgtitle)."', ".($subfid!=0 ? $imgtypeid : '2').", '".trim($imgurl)."', $subfid, ".(!empty($fid) ? $fid : "'0'").", $useraccess, $userid, $site_id, $default_status, '$datetime', '$datetime')";
			$imgquery = DB::query(Database::INSERT,$sqlimg);
			$imginsert = $imgquery->execute();
			$imgnewid = $imginsert[0] ? $imginsert[0] : '';
			if($imgnewid && $copyflag==0){
				$this->insertActivityFeed(9, 1, $imgnewid);
			}
			if($copyflag==0){
				$itemsql = "SELECT img.*, ifo.folder_title FROM `img` img LEFT JOIN `img_folders` ifo ON ifo.folder_id=img.parentfolder_id WHERE img.status_id=1 AND img.site_id=".$site_id." AND img.subfolder_id=".$subfid." AND img.parentfolder_id=".$fid." AND img.img_id=".$imgnewid." ORDER BY img.date_created DESC";
				$query = DB::query(Database::SELECT,$itemsql);
				$itemlist = $query->execute()->as_array();
				return $itemlist;
			}
			return $imgnewid;
		}
		return false;
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
	public function getImageTagsNotinByTitle($tags, $imgid) {
		$userid = Auth::instance()->get_user()->pk();
		$itemsql = "SELECT img.img_id, t.tag_id, t.tag_title FROM `img` img JOIN `img_tags` it ON img.img_id=it.img_id JOIN `tag` t ON it.tag_id=t.tag_id WHERE img.status_id=1 AND BINARY t.tag_title NOT IN (".$tags.") AND img.img_id=".$imgid." AND it.created_by=".$userid." ORDER BY img.img_id DESC";
		$itemlist = DB::query(Database::SELECT,$itemsql)->execute()->as_array();
		$tagidarry = array();
		if(!empty($itemlist) && count($itemlist)>0){
			foreach ($itemlist as $key => $value) {
				$tagidarry[] = $value['tag_id'];
			}
		}
		return $tagidarry;
	}
	public function getImageTags($imgid, $copyflag=0) {
		$userid = Auth::instance()->get_user()->pk();
		if($copyflag==0){
			$itemsql = "SELECT img.img_id, t.tag_id, t.tag_title, it.created_by FROM `img` img JOIN `img_tags` it ON img.img_id=it.img_id JOIN `tag` t ON it.tag_id=t.tag_id WHERE img.img_id=".$imgid." AND it.created_by=".$userid." ORDER BY img.img_id DESC";
		}else{
			$itemsql = "SELECT img.img_id, t.tag_id, t.tag_title, it.created_by FROM `img` img JOIN `img_tags` it ON img.img_id=it.img_id JOIN `tag` t ON it.tag_id=t.tag_id WHERE img.img_id=".$imgid." ORDER BY img.img_id DESC";
		}
		$query 	= DB::query(Database::SELECT,$itemsql);
		$taglist = $query->execute()->as_array();
		return $taglist;
	}
	public function deleteImageTagsByIds($tagids, $imgids) {
		$userid = Auth::instance()->get_user()->pk();
		if(!empty($tagids) && !empty($imgids)){
			$imgtagdelsql = "DELETE FROM `img_tags` WHERE tag_id IN (SELECT tag_id FROM `tag` WHERE tag_id IN (".$tagids.")) AND created_by=".$userid." AND img_id IN (".$imgids.")";
			$delres = DB::query(Database::DELETE,$imgtagdelsql)->execute();
			return true;
		}
		return false;
	}
	public function updataImgDataById($imgdata){
		$datetime = Helper_Common::get_default_datetime();
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		$imgstatssql = "SELECT status_id FROM img WHERE img_id=".$imgdata['curr_imgid'];
		$imgstaus = DB::query(Database::SELECT,$imgstatssql)->execute()->as_array();

		$updateseq = "UPDATE `img` SET img_title='".$this->escapeStr($imgdata['imgdata-title'])."', status_id=".$imgdata['imgdata-status'].", user_id=".$userid.", access_id=".$useraccess.", date_modified='".$datetime."' WHERE img_id=".$imgdata['curr_imgid'];
		$imgres = DB::query(Database::UPDATE,$updateseq)->execute();
		if($imgres){
			$this->insertActivityFeed(16, 26, $imgdata['curr_imgid']);
			// if($imgdata['imgdata-status']!=$imgstaus[0]['status_id']){
			// 	$this->insertActivityFeed(9, 26, $imgdata['curr_imgid'], array($imgdata['imgdata-status']));
			// }
		}
		$insertimgtags = $this->insertImgTagById($imgdata);
		return true;
	}
	public function insertImgTagById($postdata){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		$successflag = false;
		if(isset($postdata['insertimgtag']) && $postdata['insertimgtag']=='inserttag'){
			$posttags = $postdata['imgtag-input'];
		}elseif(isset($postdata['saveimgdata']) && ($postdata['saveimgdata']=='savecontinue' || $postdata['saveimgdata'] == 'saveclose')){
			$posttags = $postdata['imgdata-tag'];
		}else{
			$posttags = '';
		}
		if(isset($posttags) && !empty($posttags)){
			$explodtag = explode(',', $posttags);
			$taglist = $this->addQuotes($posttags);
			if(count($explodtag > 0)){
				$deletetag = $this->getImageTagsNotinByTitle($taglist, $postdata['curr_imgid']);
				if(count($deletetag) > 0){
					$this->deleteImageTagsByIds(implode(',', $deletetag), $postdata['curr_imgid']);
					$feedjson = array();
					$feedjson["text"] = "from image";
					$feedjson["tag_id"] = $deletetag;
					$this->insertActivityFeed(8, 2, $postdata['curr_imgid'], $feedjson);
				}
				$taggedids = array();
				foreach ($explodtag as $key => $tagvalue) {
					$sql = "SELECT * FROM tag WHERE BINARY tag_title='".$tagvalue."' limit 1";
					$query = DB::query(Database::SELECT,$sql);
					$list = $query->execute()->as_array();
					if(count($list) > 0 && !empty($list)){
						$img_tagsql = "SELECT img.img_id, t.tag_id, t.tag_title FROM `img` img JOIN `img_tags` it ON img.img_id=it.img_id JOIN `tag` t ON it.tag_id=t.tag_id WHERE img.status_id=1 AND t.tag_id=".$list[0]['tag_id']." AND img.img_id=".$postdata['curr_imgid']." AND it.created_by=".$userid." limit 1";
						$img_tagres = DB::query(Database::SELECT,$img_tagsql)->execute()->as_array();
						if(count($img_tagres) > 0 && !empty($img_tagres)){
							$successflag = true;
						} else {
							$sqlimgtag = "INSERT INTO `img_tags`(`tag_id`, `img_id`, `created_by`) VALUES (".$list[0]['tag_id'].", ".$postdata['curr_imgid'].", ".$userid.")";
							$imgtagquery = DB::query(Database::INSERT,$sqlimgtag)->execute();
							$imgtagnewid = $imgtagquery[0] ? $imgtagquery[0] : $imgtagquery;
							if($imgtagnewid){
								$taggedids[] = $list[0]['tag_id'];
								$successflag = true;
							}else{
								$successflag = false;
							}
						}
					} else {
						$sqltag = "INSERT INTO `tag`(`tag_title`, `tag_color`, `tag_cat_id`, `access_id`, `created_by`, `created`, `hits`) VALUES ('".$this->escapeStr($tagvalue)."', '".$this->random_color()."', 3, ".$useraccess.", ".$userid.", '".Helper_Common::get_default_datetime()."', 0)";
						$tagquery = DB::query(Database::INSERT,$sqltag)->execute();
						$tagnewid = $tagquery[0] ? $tagquery[0] : '';
						if($tagnewid){
							$sqlimgtag = "INSERT INTO `img_tags`(`tag_id`, `img_id`, `created_by`) VALUES (".$tagnewid.", ".$postdata['curr_imgid'].", ".$userid.")";
							$imgtagquery = DB::query(Database::INSERT,$sqlimgtag)->execute();
							$imgtagnewid = $imgtagquery[0] ? $imgtagquery[0] : $imgtagquery;
							if($imgtagnewid){
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
					$feedjson["text"] = "for image";
					$feedjson["tag_id"] = $taggedids;
					$this->insertActivityFeed(8, 1, $postdata['curr_imgid'], $feedjson);
				}
				if($successflag){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		} else {
			$deletetag = $this->getImageTagsNotinByTitle("''", $postdata['curr_imgid']);
			if(count($deletetag) > 0){
				$this->deleteImageTagsByIds(implode(',', $deletetag), $postdata['curr_imgid']);
				$feedjson = array();
				$feedjson["text"] = "from image";
				$feedjson["tag_id"] = $deletetag;
				$this->insertActivityFeed(8, 2, $postdata['curr_imgid'], $feedjson);
				return true;
			}else{
				return 'no-tag';
			}
		}
		return true;
	}
	public function insertImgTagforMultiple($posttagdata){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		$successflag = false;
		if(!empty($posttagdata['check_act'])) {
			$imgtags = $this->ajaxGetImageCommonTagsById($posttagdata['check_act']);
			$it = array();
			if (count($imgtags) > 0) {
				foreach ($imgtags as $imgtag) {
					$it[$imgtag['tag_id']] = $imgtag['tag_title'];
				}
			}
			$explodtag = explode(',', $posttagdata['imgtag-input']);
			$diffarry = array_diff($it, $explodtag);
			$deltdtag = implode(',', array_keys($diffarry));
			if(!empty($deltdtag)){
				$this->deleteImageTagsByIds($deltdtag, implode(',', $posttagdata['check_act']));
				foreach ($posttagdata['check_act'] as $key => $value) {
					$feedjson = array();
					$feedjson["text"] = "from image";
					$feedjson["tag_id"] = array_keys($diffarry);
					$this->insertActivityFeed(8, 2, $value, $feedjson);
				}
			}
			foreach($posttagdata['check_act'] as $check) {
				$taggedids = array();
				if(isset($posttagdata['imgtag-input']) && !empty($posttagdata['imgtag-input'])){
					$explodtag = explode(',', $posttagdata['imgtag-input']);
					$taglist = $this->addQuotes($posttagdata['imgtag-input']);
					if(count($explodtag > 0)){
						foreach ($explodtag as $key => $tagvalue) {
							$sql = "SELECT * FROM tag WHERE BINARY tag_title='".$tagvalue."' limit 1";
							$query = DB::query(Database::SELECT,$sql);
							$list = $query->execute()->as_array();
							if(count($list) > 0 && !empty($list)){
								$img_tagsql = "SELECT img.img_id, t.tag_id, t.tag_title FROM `img` img JOIN `img_tags` it ON img.img_id=it.img_id JOIN `tag` t ON it.tag_id=t.tag_id WHERE img.status_id=1 AND BINARY t.tag_id='".$list[0]['tag_id']."' AND img.img_id=".$check." AND it.created_by=".$userid." limit 1";
								$img_tagres = DB::query(Database::SELECT,$img_tagsql)->execute()->as_array();
								if(count($img_tagres) > 0 && !empty($img_tagres)){
									$successflag = true;
								} else {
									$sqlimgtag = "INSERT INTO `img_tags`(`tag_id`, `img_id`, `created_by`) VALUES (".$list[0]['tag_id'].", ".$check.", ".$userid.")";
									$imgtagquery = DB::query(Database::INSERT,$sqlimgtag)->execute();
									$imgtagnewid = $imgtagquery[0] ? $imgtagquery[0] : $imgtagquery;
									if($imgtagnewid){
										$taggedids[] = $list[0]['tag_id'];
										$successflag = true;
									}else{
										$successflag = false;
									}
								}
							} else {
								$sqltag = "INSERT INTO `tag`(`tag_title`, `tag_color`, `tag_cat_id`, `access_id`, `created_by`, `created`, `hits`) VALUES ('".$this->escapeStr($tagvalue)."', '".$this->random_color()."', 3, ".$useraccess.", ".$userid.", '".Helper_Common::get_default_datetime()."', 0)";
								$tagquery = DB::query(Database::INSERT,$sqltag)->execute();
								$tagnewid = $tagquery[0] ? $tagquery[0] : '';
								if($tagnewid){
									$sqlimgtag = "INSERT INTO `img_tags`(`tag_id`, `img_id`, `created_by`) VALUES (".$tagnewid.", ".$check.", ".$userid.")";
									$imgtagquery = DB::query(Database::INSERT,$sqlimgtag)->execute();
									$imgtagnewid = $imgtagquery[0] ? $imgtagquery[0] : $imgtagquery;
									if($imgtagnewid){
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
					}else{
						return false;
					}
				}else{
					return false;
				}
				if(count($taggedids) > 0 && !empty($taggedids)){
					$feedjson = array();
					$feedjson["text"] = "for image";
					$feedjson["tag_id"] = $taggedids;
					$this->insertActivityFeed(8, 1, $check, $feedjson);
				}
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
	public function updateImgStatusforMultiple($poststatus){
		$successflag = false;
		$datetime = Helper_Common::get_default_datetime();
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		if(!empty($poststatus['check_act'])) {
			 foreach($poststatus['check_act'] as $check) {
				if(isset($poststatus['imgchecked-status']) && !empty($poststatus['imgchecked-status'])){
					$updateimg = "UPDATE `img` SET status_id=".$poststatus['imgchecked-status'].", user_id=".$userid.", access_id=".$useraccess.", date_modified='".$datetime."' WHERE img_id=".$check;
					$imgres = DB::query(Database::UPDATE,$updateimg)->execute();
					if($imgres){
						$this->insertActivityFeed(9, 26, $check);
						$successflag = true;
					}else{
						$successflag = false;
					}
				}else{
					return false;
				}
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
	public function deleteImgById($imgdelete){
		$userid = Auth::instance()->get_user()->pk();
		$seqselect = "SELECT count(*) as total FROM `unit_seq` us LEFT JOIN `img` AS img ON us.seq_img=img.img_id WHERE us.seq_img=".$imgdelete['curr_imgid'];
		$seqcount = DB::query(Database::SELECT,$seqselect)->execute()->as_array();

		$featselect = "SELECT count(*) as total FROM `unit_gendata` AS ugd LEFT JOIN `img` AS img ON ugd.feat_img=img.img_id WHERE ugd.feat_img=".$imgdelete['curr_imgid'];
		$featcount 	= DB::query(Database::SELECT,$featselect)->execute()->as_array();
		if($seqcount[0]['total'] > 0 || $featcount[0]['total'] > 0){
			return false;
		}else{
			/*select img*/
			$imgselect = "SELECT * FROM `img` WHERE img_id=".$imgdelete['curr_imgid']." limit 1";
			$imgrow = DB::query(Database::SELECT,$imgselect)->execute()->as_array();
			/*select tags*/
			$imgtagselect = "SELECT * FROM `img_tags` WHERE img_id=".$imgdelete['curr_imgid'];
			$imgtagrow = DB::query(Database::SELECT,$imgtagselect)->execute()->as_array();
			/*delete tags*/
			if(!empty($imgtagrow) && count($imgtagrow) > 0){
				$deltag = "DELETE FROM `img_tags` WHERE img_id=".$imgdelete['curr_imgid'];
				$deltagrow = DB::query(Database::DELETE,$deltag)->execute();
			}
			/*delete img from folder*/
			if(!empty($imgrow) && count($imgrow) > 0){
				$delimg = "DELETE FROM `img` WHERE img_id=".$imgdelete['curr_imgid'];
				$delimgrow = DB::query(Database::DELETE,$delimg)->execute();
				if($delimgrow){
					$url1 = substr($imgrow[0]['img_url'], strripos( $imgrow[0]['img_url'],"img_" ));
					$urlPrefix_thmb = 'assets/images/dynamic/exercise/thumb/';
					if(!empty($url1) && file_exists($urlPrefix_thmb.'thumb_'.$url1)) {
						unlink($urlPrefix_thmb.'thumb_'.$url1);
					} else {
						if(!empty($imgrow[0]['img_url'])){
							$url2 = str_replace('exercise/img/', '', substr($imgrow[0]['img_url'], strripos($imgrow[0]['img_url'], "exercise/img/")));
							if(!empty($url2) && file_exists($urlPrefix_thmb.$url2)){
								unlink($urlPrefix_thmb.$url2);
							}
						}
					}
					if(!empty($imgrow[0]['img_url']) && file_exists($imgrow[0]['img_url'])) {
						unlink($imgrow[0]['img_url']);
					}
					$feedjson = array();
					$feedjson['text'] = $imgrow[0]['img_title'];
					$this->insertActivityFeed(9, 2, $imgdelete['curr_imgid'], $feedjson);
				}
			}
			return true;
		}
	}
	public function updateImgUrlById($imgurldata){
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		$rootdir = DOCROOT.'assets/images/dynamic/exercise/';
		$imgdir = $rootdir.'img/';
		$thumbdir = $rootdir.'thumb/';
		$data = $imgurldata['croppedData'];
		$now = '_'.(strtotime(Helper_Common::get_default_datetime())+rand()).'.png';
		$imgname='img'.$now;
		$thumbname = 'thumb_img'.$now;
		$imgfile = $imgdir.$imgname;
		$thumbfile = $thumbdir.$thumbname;

		$data = substr($data,strpos($data,",")+1);
		$data = base64_decode($data);
		if(file_put_contents($imgfile, $data)){
			$flag = 1;
		}
		else{
			$flag = 0;
		}
		if($flag == 1){
			Image::factory($imgfile)
				->resize(100, 100, Image::AUTO)
				->save($thumbfile);
			/*select img*/
			$imgselect = "SELECT * FROM `img` WHERE img_id=".$imgurldata['curr_imgid']." limit 1";
			$imgrow = DB::query(Database::SELECT,$imgselect)->execute()->as_array();
			/*delete img from folder*/
			if(!empty($imgrow) && count($imgrow) > 0){
				$datetime = Helper_Common::get_default_datetime();
				$imgurl = 'assets/images/dynamic/exercise/img/'.$imgname;
				$updateimgsql = "UPDATE `img` SET img_url='".trim($imgurl)."', user_id=".$userid.", access_id=".$useraccess.", date_modified='".$datetime."' WHERE img_id=".$imgurldata['curr_imgid'];
				$updateimg = DB::query(Database::UPDATE,$updateimgsql)->execute();
				if($updateimg){
					$url1 = substr ( $imgrow[0]['img_url'] ,strripos( $imgrow[0]['img_url'],"img_" ) );
					$urlPrefix_thmb = 'assets/images/dynamic/exercise/thumb/';
					if( !empty($url1) && file_exists ( $urlPrefix_thmb.'thumb_'.$url1 ) ) {
						unlink($urlPrefix_thmb.'thumb_'.$url1);
					} else {
						if(!empty($imgrow[0]['img_url'])){
							$url2 = str_replace('exercise/img/','',substr($imgrow[0]['img_url'],strripos($imgrow[0]['img_url'],"exercise/img/")));
							if( !empty($url2) && file_exists ( $urlPrefix_thmb.$url2 )){
								unlink($urlPrefix_thmb.$url2);
							}
						}
					}
					if( !empty($imgrow[0]['img_url']) && file_exists ( $imgrow[0]['img_url'] ) ) {
						unlink($imgrow[0]['img_url']);
					}
					$this->insertActivityFeed(9, 26, $imgurldata['curr_imgid']);
				}else{
					return false;
				}
			}
			return true;
		}
		else{
			return false;
		}
	}
	public function ReplaceImg($imgtitle, $imgurl, $fid, $subfid, $imgid){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$datetime = Helper_Common::get_default_datetime();
		$userid = Auth::instance()->get_user()->pk();
		$useraccess = $this->getUserRole();
		/*select img*/
		$imgselect = "SELECT * FROM `img` WHERE img_id=".$imgid." limit 1";
		$imgrow = DB::query(Database::SELECT,$imgselect)->execute()->as_array();
		/*delete img from folder*/
		if(!empty($imgrow) && count($imgrow) > 0){
			$updateimgsql = "UPDATE `img` SET img_title='".$this->escapeStr($imgtitle)."', img_url='".trim($imgurl)."', access_id=".$useraccess.", user_id=".$userid.", date_modified='".$datetime."' WHERE img_id=".$imgid;
			$updateimg = DB::query(Database::UPDATE,$updateimgsql)->execute();
			if($updateimg){
				$url1 = substr ( $imgrow[0]['img_url'] ,strripos( $imgrow[0]['img_url'],"img_" ) );
				$urlPrefix_thmb = 'assets/images/dynamic/exercise/thumb/';
				if( !empty($url1) && file_exists ( $urlPrefix_thmb.'thumb_'.$url1 ) ) {
					unlink($urlPrefix_thmb.'thumb_'.$url1);
				} else {
					if(!empty($imgrow[0]['img_url'])){
						$url2 = str_replace('exercise/img/','',substr($imgrow[0]['img_url'],strripos($imgrow[0]['img_url'],"exercise/img/")));
						if( !empty($url2) && file_exists ( $urlPrefix_thmb.$url2 )){
							unlink($urlPrefix_thmb.$url2);
						}
					}
				}
				if( !empty($imgrow[0]['img_url']) && file_exists ( $imgrow[0]['img_url'] ) ) {
					unlink($imgrow[0]['img_url']);
				}
				$this->insertActivityFeed(9, 18, $imgid);
			}
		}
		$itemsql = "SELECT img.*, ifo.folder_title FROM `img` img LEFT JOIN `img_folders` ifo ON ifo.folder_id=img.parentfolder_id WHERE img.status_id=1 AND img.site_id=".$site_id." AND img.subfolder_id=".$subfid." AND img.parentfolder_id=".$fid." AND img.img_id=".$imgid." ORDER BY img.img_id DESC";
		$query = DB::query(Database::SELECT,$itemsql);
		$itemlist = $query->execute()->as_array();
		return $itemlist;
	}
	public function ajaxGetImageCommonTagsById($imgids) {
		$userid = Auth::instance()->get_user()->pk();
		if(is_array($imgids) && count($imgids)>1){
			$cnt = count($imgids);
			$imgids = implode(',',$imgids);
			$sql = "SELECT it.tag_id,t.tag_title, count(*) AS cnt FROM img_tags AS it JOIN tag AS t WHERE t.tag_id=it.tag_id AND it.img_id IN (".$imgids.") AND it.created_by=".$userid." GROUP BY it.tag_id HAVING cnt = $cnt";
		}else{
			$imgids = (is_array($imgids))?implode(',',$imgids):$imgids;
			$sql = "SELECT it.tag_id,t.tag_title FROM img_tags AS it JOIN tag AS t WHERE t.tag_id=it.tag_id AND it.img_id IN (".$imgids.") AND it.created_by=".$userid;
		}
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
      return $list;
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
			$activity_feed["json_data"] = json_encode($activityjson); // if need to encode data and store
		}
		$activity_result = Helper_Common::createActivityFeed($activity_feed);
		return true;
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
	public function getSiteTableData($siteid){
		$sitesql = "SELECT * FROM sites WHERE id=".$siteid;
		$sitedata = DB::query(Database::SELECT, $sitesql)->execute()->as_array();
		return $sitedata;
	}
	public function doCopyAndDuplicateImages($postdata){
		$userid = Auth::instance()->get_user()->pk();
		$siteid = Session::instance()->get('current_site_id');
		/*select img*/
		$imgselect = "SELECT * FROM `img` WHERE img_id=".$postdata['curr_imgid']." limit 1";
		$imgrow = DB::query(Database::SELECT,$imgselect)->execute()->as_array();
		$imgtags = $this->getImageTags($postdata['curr_imgid'], 1);
		// print_r($postdata);exit;
		/*copy img*/
		if(!empty($imgrow) && count($imgrow) > 0){
			if(!empty($postdata['duplicateimg'])){
				$copyimgid = $this->InsertImg($imgrow[0]['img_title'], $imgrow[0]['img_url'], 1, 5, 0, 1);
			}
			if(!empty($copyimgid)){
				if(!empty($imgtags) && count($imgtags)>0){
					foreach($imgtags as $tagvalues){
						$imgresults = DB::insert('img_tags', array('tag_id', 'img_id', 'created_by'))->values(array($tagvalues['tag_id'], $copyimgid, $userid))->execute();
					}
				}
				$this->insertActivityFeed(9, 22, $postdata['curr_imgid']);
			}
			return true;
		}
		return false;
	}
}
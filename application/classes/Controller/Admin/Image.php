<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Image extends Controller_Admin_Website {

	public function _Construct() {
		parent::__construct($request, $response);
		
	}
	public function action_exerciseimages()
	{
		$this->template->title = 'Upload Settings';
		$this->render();
		$usermodel = ORM::factory('admin_user');
		$imagelibrary = ORM::factory('admin_imagelibrary');
		$siteid = Session::instance()->get('current_site_id');
		$mainid = $this->request->param('id');
		$mainidarray = explode('/',$mainid);
		if(isset($mainidarray[0]) && $mainidarray[0]!=''){$folderid = urldecode($mainidarray[0]);}else{$folderid = '';}
		if(isset($mainidarray[1]) && $mainidarray[1]!=''){$subfolderid = urldecode($mainidarray[1]);}else{$subfolderid = '';}
		$saveact = (isset($_GET['action']) && !empty($_GET['action'])) ? $_GET['action'] : '';
		$saveactid = (isset($_GET['imgid']) && !empty($_GET['imgid'])) ? $_GET['imgid'] : '';
		$getsubfolders=array(); $getfolderitem=array(); $imgdatamethod='';
		if (HTTP_Request::POST == $this->request->method()){
			// echo "<pre>"; print_r($_POST);exit;
			$imgdatamethod = $this->request->post('saveimgdata');
			$imgdelete = $this->request->post('delete_btn');
			$imgtaginsert = $this->request->post('insertimgtag');
			$changestatus = $this->request->post('changeimgstatus');
			$copyimg = $this->request->post('copyimg');
			$duplicateimg = $this->request->post('duplicateimg');
			if(!empty($imgdatamethod) && ($imgdatamethod == 'savecontinue' || $imgdatamethod == 'saveclose')){
				if(isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid'])){
					$saveid = $_POST['curr_imgid'];
					if(isset($_POST['croppedData']) && !empty($_POST['croppedData'])){
						if($imagelibrary->updateImgUrlById($_POST)){
							$this->session->set('success','Image successfully updated!!!');
						}else{
							$this->session->set('error','Error occurred while updating image!!!');
						}
					}
					elseif(!empty($_POST['imgdata-title']) && !empty($_POST['imgdata-status'])){
						if($imagelibrary->updataImgDataById($_POST)){
							$this->session->set('success','Image data successfully updated!!!');
						}
						else{
							$this->session->set('error','Error occurred while updating image data!!!');
						}
					}
					else{
						$this->session->set('error','Error occurred, please check the given details!!!');
					}
				}
			}
			if(!empty($imgdelete) && $imgdelete == 'delete'){
				if(isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid'])){
					if($imagelibrary->deleteImgById($_POST)){
						$this->session->set('success','Image successfully deleted!!!');
					}else{
						$this->session->set('error','This image used by some other record(s), cannot delete this image!!!');
					}
				}
			}
			if(!empty($imgtaginsert) && $imgtaginsert == 'inserttag'){
				if(!isset($_POST['check_act']) && isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid'])){
					if($imagelibrary->insertImgTagById($_POST)==='no-tag'){
						$this->session->set('error','No tags inserted for this image!!!');
					}elseif($imagelibrary->insertImgTagById($_POST)){
						$this->session->set('success','Tagged successfully!!!');
					}else{
						$this->session->set('error','Error occurred while tagging!!!');
					}
				}elseif(isset($_POST['check_act']) && !empty($_POST['check_act'])){
					if(!empty($_POST['imgtag-input'])){
						if($imagelibrary->insertImgTagforMultiple($_POST)){
							$this->session->set('success','Tagged successfully!!!');
						}else{
							$this->session->set('error','Error occurred while tagging!!!');
						}
					}
					else{
						$this->session->set('error','Please enter any tag!!!');
					}
				}
			}
			if(!empty($changestatus) && $changestatus == 'changestatus'){
				if(isset($_POST['check_act']) && !empty($_POST['check_act'])){
					if(!empty($_POST['imgchecked-status'])){
						if($imagelibrary->updateImgStatusforMultiple($_POST)){
							$this->session->set('success','Status successfully updated!!!');
						}else{
							$this->session->set('error','Error occurred while updated status!!!');
						}
					}
					else{
						$this->session->set('error','Please select any one status!!!');
					}
				}
			}
			if(!empty($copyimg) && ($copyimg == '2' || $copyimg == '6')){
				if(isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid'])){
					if($imagelibrary->doCopyAndDuplicateImages($_POST)){
						$this->session->set('success','Image successfully copied!!!');
					}else{
						$this->session->set('error','Error occurred while copying image!!!');
					}
				}
			}
			if(!empty($duplicateimg)){
				if(isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid'])){
					if($imagelibrary->doCopyAndDuplicateImages($_POST)){
						$this->session->set('success','Image successfully duplicated!!!');
					}else{
						$this->session->set('error','Error occurred while duplicating image!!!');
					}
				}
			}
			if(!empty($imgdatamethod) && $imgdatamethod == 'savecontinue'){
				if(isset($_POST['croppedData']) && !empty($_POST['croppedData'])){
					$this->redirect('admin/image/exerciseimages/'.$folderid.'/'.$subfolderid.'/?action=editImg&imgid='.$saveid);
				}else{
					$this->redirect('admin/image/exerciseimages/'.$folderid.'/'.$subfolderid.'/?action=editImgData&imgid='.$saveid);
				}
			}else{
				$this->redirect('admin/image/exerciseimages/'.$folderid.'/'.$subfolderid);
			}
		}
		if(!empty($subfolderid) && !empty($folderid)){
			$getfolderitem = $imagelibrary->getFolderImages($subfolderid, $folderid, $siteid);
			$this->template->content->subfolders = '';
			$this->template->content->folderitem = $getfolderitem;
			$this->template->content->foldername = $imagelibrary->getImgFolderName($subfolderid);
		}elseif(!empty($folderid)){
			if($folderid != '2' && $folderid != '6'){
				$getsubfolders = $imagelibrary->getSubImgFolder($folderid);
				if(count($getsubfolders)>0){
					$this->template->content->subfolders = $getsubfolders;
					$this->template->content->foldername = $imagelibrary->getImgFolderName($folderid);
				}
				if(empty($subfolderid) && count($getsubfolders)<=0){
					$getfolderitem = $imagelibrary->getFolderImages($subfolderid, $folderid, $siteid);
					$this->template->content->subfolders = '';
					$this->template->content->folderitem = $getfolderitem;
					$this->template->content->foldername = $imagelibrary->getImgFolderName($folderid);
				}
			}else{
				$getfolderitem = $imagelibrary->getFolderImages('5', $folderid, $siteid);
				$this->template->content->subfolders = '';
				$this->template->content->folderitem = $getfolderitem;
				$this->template->content->foldername = $imagelibrary->getImgFolderName($folderid);
			}
		}else{
			$this->template->content->partentfolder = $imagelibrary->getParentImgFolder();
			if(isset($this->template->content->partentfolder) && count($this->template->content->partentfolder) > 0){
				$parentFoldertemp = $this->template->content->partentfolder;
				foreach($parentFoldertemp as $keys => $values){
					if($values['folder_id'] == '1')
						$this->template->content->partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 1);
					else if($values['folder_id'] == '2')
						$this->template->content->partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 2);
					else if($values['folder_id'] == '3')
						$this->template->content->partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 3);
					else if($values['folder_id'] == '6')
						$this->template->content->partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 6);
				}
			}
		}
		if(!isset($this->template->content->partentfolder) && !empty($folderid) && empty($subfolderid)){
			$this->template->content->profileimgcnt = $imagelibrary->getImgCountByFolder(4, $folderid);
			$this->template->content->exerciseimgcnt = $imagelibrary->getImgCountByFolder(5, $folderid);
		}
		$this->template->content->parentFolderId	= $folderid;
		$this->template->content->subFolderId		= $subfolderid;
		$this->template->content->exerciseStatus	= $imagelibrary->getunitsbytable('unit_status');
		$this->template->content->saveaction		= $saveact;
		$this->template->content->siteid				= $siteid;
		$this->template->content->saveactionid		= $saveactid;
		$this->template->js_bottom = array('assets/js/SimpleAjaxUploader.js','assets/plugins/cropper/dist/cropper.min.js','assets/plugins/cropper/demo/js/imglib-main.js','assets/js/bootstrap-tagsinput.min.js');
		$this->template->css = array('assets/css/pages/admin/imgupload.css','assets/css/bootstrap-tagsinput.css','assets/plugins/cropper/dist/cropper.min.css','assets/plugins/cropper/demo/css/main.css');
	}
	function formatBytes($size, $precision = 2){
		$base = log($size, 1024);
		$suffixes = array('', 'K', 'M', 'G', 'T');
		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}
	public function compress_imgsize($source, $destination, $quality) {
		$info = getimagesize($source);
		if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);
		elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source);
		elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);
		if(imagejpeg($image, $destination, $quality)){
			return true;
		}
		return false;
	}
	public function action_uploadImg() {
		$this->auto_render = FALSE;
		$imagelibrary = ORM::factory('admin_imagelibrary');
		$siteid = Session::instance()->get('current_site_id');
		$userid = Auth::instance()->get_user()->pk();
		if($_GET['action'] == 'upload'){
			if(isset($_POST) && !empty($_POST)){
				if($_POST['upfolder'] != 0){
					$subfid = $_POST['upfolder'];
				}else{
					$subfid = $_POST['subfolder'];
				}
				if($_POST['parentfolder']!=0){
					$fid = $_POST['parentfolder'];
				}else{
					$fid = $_POST['currfolder'];
				}
				if($_POST['replaceflag']=='replace' && !empty($_POST['imageid'])){
					$replaceflag = true;
				}else{
					$replaceflag = false;
				}
				if(isset($_POST['uploadfrom']) && $_POST['uploadfrom']=='template'){
					$funcprefx = 'popup'; $classprefx = 'mdl_';
				}else{
					$funcprefx = ''; $classprefx = '';
				}
			}
			// print_r($_FILES['uploadfile']);exit;
			if(isset($_FILES['uploadfile'])){
				if(!$_FILES['uploadfile']['error']){
					$imgchecked = Session::instance()->get('imgchecked');
					$valid_file = true;
					$rootdir = DOCROOT.'assets/images/dynamic/exercise/';
					$imgdir = $rootdir.'img';
					$thumbdir = $rootdir.'thumb';
					$now = '_'.(strtotime(Helper_Common::get_default_datetime())+rand()) . $imgchecked;
					$file_name = $_FILES['uploadfile']['name'];
					$file_size = $_FILES['uploadfile']['size'];
					$file_tmp = $_FILES['uploadfile']['tmp_name'];
					$file_type= $_FILES['uploadfile']['type'];
					$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$file_ext = strtolower($file_ext);
					$imgname ='img'.$now.'.'.$file_ext;
					$thumbname = 'thumb_img'.$now.'.'.$file_ext;
					$imgfile = $imgdir.'/'.$imgname;
					$thumbfile = $thumbdir.'/'.$thumbname;
					$expensions = array("jpeg","jpg","png");
					if(!in_array($file_ext, $expensions)){
						$valid_file = false;
						echo json_encode(array('success' => false , 'divImage' => 'Extension not allowed, please choose JPEG, JPG and PNG file.'));
						return;
					}
					if($file_size > (2560000)){ //can't be larger than 2mb
						$valid_file = false;
						echo json_encode(array('success' => false , 'divImage' => 'Oops! File\'s size is too large.'));
						return;
					}
					if($valid_file){
						Session::instance()->set('imgchecked', $imgchecked++);
						if($file_size > (1536000)){ //if larger than 1.5 kb
							if($this->compress_imgsize($file_tmp, $imgfile, 20)){
								$file_size = filesize($imgfile);
								$flag = 1;
							}else{
								$flag = 0;
							}
						}elseif($file_size > (512000)){ //if larger than 500 kb
							if($this->compress_imgsize($file_tmp, $imgfile, 30)){
								$file_size = filesize($imgfile);
								$flag = 1;
							}else{
								$flag = 0;
							}
						}else{ //if lesser than 500 kb
							if($this->compress_imgsize($file_tmp, $imgfile, 70)){
								$file_size = filesize($imgfile);
								$flag = 1;
							}else{
								$flag = 0;
							}
						}
						// echo $this->formatBytes($file_size,2);exit;
						if($flag == 1){
							/*resize for thumb image*/
							Image::factory($imgfile)->resize(100, 100, Image::AUTO)->save($thumbfile);
							/*image insertion and ui formation*/
							if(!empty($imgname) && !empty($file_name)){
								$info = pathinfo($file_name);
								$file_title = basename($file_name,'.'.$info['extension']);
								$img_url = 'assets/images/dynamic/exercise/img/'.$imgname;
								if($replaceflag){
									$imglist = $imagelibrary->ReplaceImg($file_title, $img_url, $fid, $subfid, $_POST['imageid']);
								}else{
									$imglist = $imagelibrary->InsertImg($file_title, $img_url, $fid, $subfid, $siteid);
								}
								foreach ($imglist as $key => $value) {
									if(!empty($funcprefx)){
										$checkbox = '';
										$jsfunction = $funcprefx.'triggerImgOptionModal(this);';
									}else{
										$checkbox = '<div class="checkbox-checker col-xs-2 col-sm-2" style="display: none;">
											<div class="checkboxcolor">
												<label>
													<input data-role="none" data-ajax="false" type="checkbox" class="checkhidden" name="check_act[]" value="'.$value['img_id'].'">
													<span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span>
												</label>
											</div>
										</div>';
										$jsfunction = "triggerImgOptionModal(this, '".$value['parentfolder_id']."', '".(($value['user_id'] == $userid) ? '1' : '')."');";
									}
									$attribute = 'data-itemid="'.$value['img_id'].'" data-itemname="'.$value['img_title'].'" data-itemurl="'.$value['img_url'].'" data-itemtype="upload"';
									echo json_encode(array('success' => true ,
										'divImage' => '<li class="imgRecord" id="'.$value['img_id'].'">
											<div class="imgRecordDataFrame col-xs-12 col-sm-12">
												<a href="javascript:void(0);" class="col-xs-10 col-sm-10 imgFrame-left" data-ajax="false" data-role="none">
													'.$checkbox.'
													<div class="'.(empty($checkbox) ? 'col-xs-4 col-sm-4' : 'col-xs-3 col-sm-3').' '.$classprefx.'thumb-img" '.$attribute.' onclick="'.$funcprefx.'triggerImgPrevModal(this);" style="background-image: url('.URL::base().$value['img_url'].');"></div>
													<div class="'.(empty($checkbox) ? 'col-xs-8 col-sm-8' : 'col-xs-7 col-sm-7').' '.$classprefx.'img-itemname text-left">
														<div class="altimgtitle break-img-name">'.$value['img_title'].'</div>
														<div class="img-info">'.$this->formatBytes($file_size, 2).'&nbsp;&nbsp;'.Helper_Common::UserDateFormat().'</div>
													</div>
												</a>
												<a href="javascript:void(0);" class="col-xs-2 col-sm-2 imgFrame-right '.$classprefx.'upload-imgrow" '.$attribute.' onclick="'.$funcprefx.'triggerImgOptionModal(this);" title="'.__("Options").'" data-ajax="false" data-role="none">
													<div class="col-sm-12 col-xs-12"><i class="fa fa-chevron-right iconsize2"></i></div>
												</a>
											</div>
										</li>'
									));
								}
								unlink($file_tmp);
								return;
							} else {
								echo json_encode(array('success' => false , 'divImage' => 'Ooops! Your upload triggered error. Please upload image with valid file name'));
								unlink($file_tmp);
								return;
							}
						}
					}else{
						unlink($file_tmp);
					}
				}else{
					echo json_encode(array('success' => false , 'divImage' => 'Ooops! Your upload triggered the following error: '.$_FILES['uploadfile']['error']));
					return;
				}
			}
		}else{
			echo json_encode(array('success' => true));
		}
		return;
	}
	public function action_getAjaxShowMoreImages(){
		$imagelibrary 	= ORM::factory('admin_imagelibrary');
		$siteid = Session::instance()->get('current_site_id');
		$folderid 		= (isset($_GET['fid']) && !empty($_GET['fid']) ? $_GET['fid'] : '');
		$subfolderid 	= (isset($_GET['subfid']) && !empty($_GET['subfid']) ? $_GET['subfid'] : '');
		$slimit 		= (isset($_GET['slimit']) && !empty($_GET['slimit']) ? $_GET['slimit'] : '0');
		$elimit 		= (isset($_GET['elimit']) && !empty($_GET['elimit']) ? $_GET['elimit'] : '10');
		$moreitems = '';
		if(!empty($folderid) || !empty($subfolderid)){
			$moreitems = $imagelibrary->getFolderImages($subfolderid, $folderid,$siteid,$slimit, $elimit);
		}else{
		}
		echo json_encode( array("items"=>$moreitems) );
	}
	public function action_defaulthide(){
		$workoutsmodel = ORM::factory('admin_workouts');
		$siteid = Session::instance()->get('current_site_id');
		if (HTTP_Request::POST == $this->request->method()) {
			$this->globaluser = Auth::instance()->get_user();
			$method           = $this->request->post('f_method');
			$FolderId         = ($this->request->post('FolderId')) ? $this->request->post('FolderId') : 0;
			$imageid          = $this->request->post('imageid');
			$workoutsmodel->hideDefaultRecords($this->globaluser->pk(), $siteid, $imageid, '3');
			$this->session->set('success','Successfully Hided Sample Image record on this Site!!!');
		}
   }
}

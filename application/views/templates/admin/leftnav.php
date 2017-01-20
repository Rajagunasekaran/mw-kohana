<?php defined('SYSPATH') OR die('No direct access allowed.'); 
	$current_url = Helper_Common::currentAdminUrl();
	$siteId 	 = $current_site_id;
	$totalwkout = $samplewkout = $defaultwkout = $sharedwkout = 0;
	$totalxr = $samplexr = $defaultxr = $sharedxr = 0;
	$userId 	= Auth::instance()->get_user()->pk();
	//31-Aug-2016 - Sample & Share Workout plans read status update & get count - Starts Here
	$workoutModel 		= ORM::factory('admin_workouts');
	$exercisemodel 		= ORM::factory('admin_exercise');
	$datevalue 			= Helper_Common::get_default_datetime();
	if($current_url=="admin/workout/sample"){
		$parentFolderId = '';
		$updateReadCountArr = $workoutModel->getSampleWkoutunreadDetails($userId,$parentFolderId);
		if(!empty($updateReadCountArr)){
			$replaceContent = $updateReadCountArr['wkoutidsreplace'];
			$replaceArr 	= explode('#',$updateReadCountArr['wkoutidsreplace']);
			$appendArr 		= explode('#',$updateReadCountArr['wkoutids']);
			$appendResArr 	= array_diff($appendArr, $replaceArr);
			$appendContent 	= implode('#',$appendResArr);
			if(!empty($appendContent)){
				$insertArr['wkoutids'] = $replaceContent.$appendContent.'#';
				$insertArr['wkout_type'] = '2';
				$insertArr['read_by'] = $userId;
				$insertArr['site_id'] = $siteId;
				$insertArr['status_id'] = 1;
				$insertArr['created_date'] = $insertArr['modified_date'] = $datevalue;
				$workoutModel->updateReadStatus($replaceContent,$insertArr);
			}
		}
	}
	elseif($current_url=="admin/workout/shared"){
		$parentFolderId = '';
		$updateReadCountArr = $workoutModel->getSharedunreadDetails($userId,$siteId,$parentFolderId);
		if(!empty($updateReadCountArr)){
			$replaceContent = $updateReadCountArr['wkoutidsreplace'];
			$replaceArr 	= explode('#',$updateReadCountArr['wkoutidsreplace']);
			$appendArr 		= explode('#',$updateReadCountArr['wkoutids']);
			$appendResArr 	= array_diff($appendArr, $replaceArr);
			$appendContent 	= implode('#',$appendResArr);
			if(!empty($appendContent)){
				$insertArr['wkoutids'] = $replaceContent.$appendContent.'#';
				$insertArr['wkout_type'] = '1';
				$insertArr['read_by'] = $userId;
				$insertArr['site_id'] = $siteId; 
				$insertArr['status_id'] = 1;
				$insertArr['created_date'] = $insertArr['modified_date'] = $datevalue;
				$workoutModel->updateReadStatus($replaceContent,$insertArr);
			}
		}
	}
	elseif($current_url == 'admin/exercise/browse' && isset($_GET['d']) && $_GET['d'] == '3'){// update the xr read count
		$sharedxrReadArr = $exercisemodel->getSharedXrUnreadDetails($userId);
		if(!empty($sharedxrReadArr)){
			$replaceContent = $sharedxrReadArr['unitidsreplace'];
			$replaceArr = explode('#',$sharedxrReadArr['unitidsreplace']);
			$appendArr = explode('#',$sharedxrReadArr['unitids']);
			$appendResArr = array_diff($appendArr, $replaceArr);
			$appendContent = implode('#',$appendResArr);
			if(!empty($appendContent)){
			   $insertArr['wkoutids'] = $replaceContent.$appendContent.'#';
			   $insertArr['xr_type'] = '1';
			   $insertArr['read_by'] = $userId;
			   $insertArr['site_id'] = $siteId;
			   $insertArr['status_id'] = 1;
			   $insertArr['created_date'] = $insertArr['modified_date'] = $datevalue;
			   $exercisemodel->updateUnitReadStatus($replaceContent, $insertArr);
			}
		}
	}
	$samplecntArray = $workoutModel->getSampleWkoutunreadCnt($userId);
	$sharedcntArray	= $workoutModel->getSharedunreadCnt($userId,$siteId);
	$sharedCntread  = (!empty($sharedcntArray['totalreadids']) ? explode('#',$sharedcntArray['totalreadids']) : array());
	//echo "<pre>";print_r($sharedCntread);die();
	$sampleCntread  = (!empty($samplecntArray['totalreadids']) ? explode('#',$samplecntArray['totalreadids']) : array());
	$sharedcnt		= $sharedcntArray['totalshare'] - (count($sharedCntread)>0 ? (count($sharedCntread) - 2) : 0);
	$samplecnt		= $samplecntArray['totalsample'] - (count($sampleCntread)>0 ? (count($sampleCntread) - 2) : 0);
	$samplewkout 	= (isset($samplecnt) ? $samplecnt : 0 );
	$sharedwkout	= (isset($sharedcnt) ? $sharedcnt : 0 );
	$totalwkout 	= $samplewkout + $sharedwkout;
	//31-Aug-2016 - Sample & Share Workout plans read status update & get count - Ends Here
	/*shared xr unread count*/
	$sharedxrcntArray = $exercisemodel->getSharedXrUnreadCount($userId);
	$sharedxrCntread = (!empty($sharedxrcntArray['totalxrreadids']) ? explode('#',$sharedxrcntArray['totalxrreadids']) : array());
	$sharedxr_unreadcnt = (!empty($sharedxrcntArray['totalsharedxr']) ? $sharedxrcntArray['totalsharedxr'] : 0);
	if(count($sharedxrCntread) > 0){
		$sharedxr_unreadcnt = ($sharedxrcntArray['totalsharedxr'] > (count($sharedxrCntread) - 2) ? $sharedxrcntArray['totalsharedxr'] - (count($sharedxrCntread) - 2) : (count($sharedxrCntread) - 2) - $sharedxrcntArray['totalsharedxr']);
	}
	$sharedxr = (isset($sharedxr_unreadcnt) ? $sharedxr_unreadcnt : 0);
	$totalxr = $sharedxr;

?>
<div class="collapse navbar-collapse navbar-ex1-collapse">
	<ul class="nav navbar-nav side-nav">
		<li <?php if($current_url=='admin/dashboard' || $current_url=='admin/dashboard/index' || $current_url=='admin/dashboard/sharerecords') { ?>class="active" <?php } ?> >
			<a href="<?php echo URL::base().'admin/dashboard'; ?>"><i class="fa fa-dashboard"></i> <?php echo I18n::get('Dashboard');?></a>
      </li>
		<?php
		if(Helper_Common::is_admin() || Helper_Common::is_manager()) // || Helper_Common::is_trainer()
		{
		?>
		<li <?php if($current_url=='admin/mailbox' || $current_url=='admin/mailbox/index') { ?>class="active" <?php } ?> >
			<a href="<?php echo URL::base().'admin/mailbox'; ?>"><i class="fa fa-envelope"></i> <?php echo I18n::get('Mailbox');?>
				<?php
				if($mail_cnt>0){ ?>
					<small class="label pull-right label-success" id='mailbox_unread'><?php echo $mail_cnt; ?></small><?php
				} ?>
			</a>
		</li><?php
		}
		//Sites Menu
		if(Helper_Common::is_admin()) {
			$sites_sub_menus = array('admin/sites/create','admin/sites/browse','admin/sites/edit'); ?>
			<li <?php if(in_array($current_url,$sites_sub_menus)) { ?> class="active"<?php }?>>
				<a href="javascript:;" data-toggle="collapse" data-target="#sitesmenu" <?php if(in_array($current_url,$sites_sub_menus)) { ?> aria-expanded="true" <?php } ?>>
					<i class="fa fa-list-alt"></i> <?php echo I18n::get('Sites');?> <i class="fa fa-fw fa-caret-down"></i>
				</a>
            <ul id="sitesmenu" class="collapse <?php if(in_array($current_url,$sites_sub_menus)) { ?> in <?php } ?>">
					<li>
						<a href="javascript:void(0);" data-toggle="collapse" data-target="#sitessubmenu" <?php if(in_array($current_url,$sites_sub_menus)) { ?> aria-expanded="true" <?php } ?>><i class="fa fa-list-alt"></i> <?php echo I18n::get('Create Sites');?> <i class="fa fa-fw fa-caret-down"></i></a>
                  <ul id="sitessubmenu" class="collapse <?php if(in_array($current_url,$sites_sub_menus)) { ?> in <?php } ?>">
							<li <?php if($current_url=='admin/sites/create' || $current_url=='admin/sites/edit') { ?>class="sub-active" <?php } ?>>
								<a href="<?php echo URL::base().'admin/sites/create'; ?>"><?php echo I18n::get('New Site');?></a>
							</li>
							<li <?php if($current_url=='admin/sites/browse' && isset($_GET['get']) && $_GET["get"]=='exists') { ?>class="sub-active" <?php } ?>>
								<a href="<?php echo URL::base().'admin/sites/browse?get=exists'; ?>"><?php echo I18n::get('From Existing');?></a>
							</li>
						</ul>
               </li>
               <li <?php if($current_url=='admin/sites/browse'  && isset($_GET['get']) && $_GET["get"]=='all') { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/sites/browse?get=all'; ?>"><?php echo I18n::get('Browse Sites');?></a>
               </li>
				</ul>
         </li><?php
		}
		
		//if(!(Helper_Common::is_trainer())) { 
      if(Helper_Common::is_admin() || Helper_Common::is_manager()) // || Helper_Common::is_trainer()
		{
			//Email Manager Menu
			$email_sub_menus = array('admin/email/create','admin/email/templatename','admin/email/smtpsettings','admin/email/templatetype','admin/email/smtp','admin/email/testemail','admin/email/setdelivery', 'admin/email/delivery','admin/email/deliverysettings','admin/email/emailvariables', 'admin/email/variablename');	?>
			<li <?php if(in_array($current_url,$email_sub_menus)) { ?> class="active"<?php }?>>
				<a href="javascript:;" data-toggle="collapse" data-target="#demo" <?php if(in_array($current_url,$email_sub_menus)) { ?> aria-expanded="true" <?php } ?>><i class="fa fa-fw fa-envelope"></i> <?php  echo I18n::get('Email Manager');?> <i class="fa fa-fw fa-caret-down"></i></a>
				<ul id="demo" class="collapse <?php if(in_array($current_url,$email_sub_menus)) { ?> in <?php } ?>">
					<?php
					if(Helper_Common::hasAccess('Create Template'))
					{ ?>
						<li <?php if($current_url=='admin/email/create') { ?>class="sub-active" <?php } ?>>
							<a href="<?php echo URL::base().'admin/email/create/'.$siteId; ?>"><?php echo I18n::get('Create Template');?></a>
						</li><?php
					} ?>
					<li <?php if($current_url=='admin/email/templatename') { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/email/templatename/'.$siteId; ?>"><?php echo I18n::get('Browse Templates');?></a>
               </li><?php
					if(Helper_Common::hasAccess('Manage Templates'))
					{ 	?>
						<li <?php if($current_url=='admin/email/templatetype') { ?>class="sub-active" <?php } ?>>
							<a href="<?php echo URL::base().'admin/email/templatetype/'.$siteId; ?>"><?php echo I18n::get('Email Settings');?></a>
						</li><?php
					} ?><?php // if(Helper_Common::hasAccess('Manage Templates')) { ?>
					<li <?php if($current_url=='admin/email/emailvariables') { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/email/emailvariables/'.$siteId; ?>"><?php echo I18n::get('Create Email Variables');?></a>
					</li>		<?php //} ?>
					<li <?php if($current_url=='admin/email/variablename') { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/email/variablename/'.$siteId; ?>"><?php echo I18n::get('Browse Email Variables');?></a>
               </li>
					<li <?php if($current_url=='admin/email/smtpsettings' || $current_url=='admin/email/smtp') { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/email/smtpsettings/'.$siteId; ?>"><?php echo I18n::get('SMTP');?></a>
               </li>
					<li <?php if($current_url=='admin/email/testemail') { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/email/testemail/'.$siteId; ?>"><?php echo I18n::get('Test Email');?></a>
               </li>
					<!--
					<li <?php //if($current_url=='admin/email/delivery' || $current_url=='admin/email/deliverysettings') { ?> class="sub-active" <?php //} ?>>
						<a href="<?php //echo URL::base().'admin/email/delivery/'.$siteId; ?>"><?php //echo I18n::get('Set Delivery');?></a>
               </li> -->
            </ul>
         </li><?php
			//CMS Menu
			$cms_sub_menus = array('admin/cms/create','admin/cms/pagelist','admin/sites/slidercreate','admin/sites/slider_edit','admin/sites/sliderbrowse','admin/sites/blockcreate','admin/sites/blockbrowse','admin/sites/blockedit','admin/sites/partnercreate','admin/sites/partneredit','admin/sites/partnerbrowse','admin/sites/testimonialcreate','admin/sites/testimonialedit','admin/sites/testimonialbrowse','admin/sites/homecontent','admin/sites/advanced_css','admin/sites/footermenu', 'admin/cms/common_create', 'admin/cms/common_pagelist','admin/questions','admin/questions/index','admin/sites/sociallinks','admin/sites/socialpage','admin/trainer/trainer_profile','admin/commonquestions','admin/commonquestions/index',
										  );
			if(Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer())
			{ ?>
				<li <?php if(in_array($current_url,$cms_sub_menus)) { ?> class="active"<?php }?>>
					<a href="javascript:;" data-toggle="collapse" data-target="#cmsmenu" <?php if(in_array($current_url,$cms_sub_menus)) { ?> aria-expanded="true" <?php } ?>><i class="fa fa-list-alt"></i> <?php echo I18n::get('CMS');?> <i class="fa fa-fw fa-caret-down"></i></a>
					<ul id="cmsmenu" class="collapse <?php if(in_array($current_url,$cms_sub_menus)) { ?> in <?php } ?>">
					<?php
					unset($cms_sub_menus[(count($cms_sub_menus))]);
					unset($cms_sub_menus[(count($cms_sub_menus)-1)]);
					?>
						<li>
							<a href="javascript:;" data-toggle="collapse" data-target="#sitecmsmenu" <?php if(in_array($current_url,$cms_sub_menus)) { ?> aria-expanded="true" <?php } ?>><i class="fa fa-list-alt"></i> <?php echo I18n::get('SITE CMS');?> <i class="fa fa-fw fa-caret-down"></i></a>
							<ul id="sitecmsmenu" class="collapse <?php if(in_array($current_url,$cms_sub_menus)) { ?> in <?php } ?>">
								<?php
								if(!(Helper_Common::is_trainer()))
								{
									$cms_sub_homepage_menus = array('admin/sites/slidercreate','admin/sites/slider_edit','admin/sites/sliderbrowse','admin/sites/blockcreate','admin/sites/blockbrowse','admin/sites/blockedit','admin/sites/partnercreate','admin/sites/partneredit','admin/sites/partnerbrowse','admin/sites/testimonialcreate','admin/sites/testimonialedit','admin/sites/testimonialbrowse','admin/sites/homecontent','admin/sites/advanced_css','admin/sites/footermenu','admin/sites/sociallinks','admin/sites/socialpage');
									if(Helper_Common::hasAccess('Manage Home Page'))
									{ ?>
										<li <?php if(in_array($current_url,$cms_sub_homepage_menus)) { ?> class="active"<?php }?>>
											<a aria-expanded="false" href="javascript:void(0);" data-toggle="collapse" data-target="#homeslideinner" class="collapsed"><?php echo I18n::get('Promo Page');?> <i class="fa fa-fw fa-caret-down"></i></a>
											<ul class="collapse <?php if(in_array($current_url,$cms_sub_homepage_menus)) { ?> in <?php } ?>" id="homeslideinner" aria-expanded="false">
												<?php $cms_sub_homepage_slider_menus = array('admin/sites/slidercreate','admin/sites/slider_edit','admin/sites/sliderbrowse');?>
												<li <?php if(in_array($current_url,$cms_sub_homepage_slider_menus)) { ?> class="active"<?php }?>>
													<a aria-expanded="false" href="javascript:void(0);" data-toggle="collapse" data-target="#inside1" class="collapsed"><?php  echo I18n::get('Slide Banner');?> <i class="fa fa-fw fa-caret-down"></i></a>
													<ul id="inside1" aria-expanded="false" class="collapse <?php if(in_array($current_url,$cms_sub_homepage_slider_menus)) { ?> in <?php } ?>">
														<li <?php if($current_url=='admin/sites/slidercreate' || $current_url=='admin/sites/slider_edit') { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/slidercreate/'.$siteId;?>"><?php echo I18n::get('Create a Slide');?></a></li>
														<li <?php if($current_url=='admin/sites/sliderbrowse') { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/sliderbrowse/'.$siteId;?>"><?php echo I18n::get('Browse Slides');?></a></li>
													</ul>
												</li>
												<?php 
												$cms_sub_homepage_homecontent_menus = array('admin/sites/homecontent');
												?>
												<li <?php if(in_array($current_url,$cms_sub_homepage_homecontent_menus)) { ?> class="active"<?php }?>>
													<a aria-expanded="false" href="javascript:void(0);" data-toggle="collapse" data-target="#inside5" class="collapsed"><?php echo I18n::get('Homepage Settings');?> <i class="fa fa-fw fa-caret-down"></i></a>
													<ul id="inside5" aria-expanded="false" class="collapse <?php if(in_array($current_url,$cms_sub_homepage_homecontent_menus)) { ?> in <?php } ?>">
														<li <?php if($current_url=='admin/sites/homecontent') { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/homecontent/'.$siteId;?>"><?php echo I18n::get('Homepage Settings');?></a></li>
													</ul>
												</li>
												<?php 
												$cms_sub_socialpage_socialcontent_menus = array('admin/sites/socialpage');
												?>
												<li <?php if(in_array($current_url,$cms_sub_socialpage_socialcontent_menus)) { ?> class="active"<?php }?>>
													<a aria-expanded="false" href="javascript:void(0);" data-toggle="collapse" data-target="#insidesocial" class="collapsed"><?php echo I18n::get('Social Page');?> <i class="fa fa-fw fa-caret-down"></i></a>
													<ul id="insidesocial" aria-expanded="false" class="collapse <?php if(in_array($current_url,$cms_sub_socialpage_socialcontent_menus)) { ?> in <?php } ?>">
														<li <?php if($current_url=='admin/sites/socialpage') { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/socialpage/'.$siteId;?>"><?php echo I18n::get('Social Page');?></a></li>
													</ul>
												</li>
												<?php $cms_sub_homepage_browse_menus = array('admin/sites/blockcreate','admin/sites/blockbrowse','admin/sites/blockedit');?>
												<li <?php if(in_array($current_url,$cms_sub_homepage_browse_menus)) { ?> class="active"<?php }?>>
													<a aria-expanded="false" href="javascript:void(0);" data-toggle="collapse" data-target="#inside2" class="collapsed"><?php echo I18n::get('Feature Blocks');?><i class="fa fa-fw fa-caret-down"></i></a>
													<ul id="inside2" aria-expanded="false" class="collapse <?php if(in_array($current_url,$cms_sub_homepage_browse_menus)) { ?> in <?php } ?>">
														<li <?php if($current_url=='admin/sites/blockcreate' || $current_url=='admin/sites/blockedit') { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/blockcreate/'.$siteId;?>"><?php echo I18n::get('Create a Block');?></a></li>
														<li <?php if($current_url=='admin/sites/blockbrowse') { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/blockbrowse/'.$siteId;?>"><?php echo I18n::get('Browse Blocks');?></a></li>
													</ul>
												</li>
												<?php $cms_sub_homepage_partner_menus = array('admin/sites/partnercreate','admin/sites/partneredit','admin/sites/partnerbrowse');	?>
												<li <?php if(in_array($current_url,$cms_sub_homepage_partner_menus)) { ?> class="active"<?php }?>>
													<a aria-expanded="false" href="javascript:void(0);" data-toggle="collapse" data-target="#inside3" class="collapsed"><?php echo I18n::get('Partners');?> <i class="fa fa-fw fa-caret-down"></i></a>
													<ul id="inside3" aria-expanded="false" class="collapse <?php if(in_array($current_url,$cms_sub_homepage_partner_menus)) { ?> in <?php } ?>">
														<li <?php if($current_url=='admin/sites/partnercreate' || $current_url=='admin/sites/partneredit') { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/partnercreate/'.$siteId;?>"><?php echo I18n::get('Create a Partner');?></a></li>
														<li <?php if($current_url=='admin/sites/partnerbrowse') { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/partnerbrowse/'.$siteId;?>"><?php echo I18n::get('Browse Partners');?></a></li>
													</ul>
												</li>
												<?php $cms_sub_homepage_testimonial_menus = array('admin/sites/testimonialcreate','admin/sites/testimonialbrowse','admin/sites/testimonialedit');	?>
												<li <?php if(in_array($current_url,$cms_sub_homepage_testimonial_menus)) { ?> class="active"<?php }?>>
													<a aria-expanded="false" href="javascript:void(0);" data-toggle="collapse" data-target="#inside4" class="collapsed"><?php echo I18n::get('Testimonials');?> <i class="fa fa-fw fa-caret-down"></i></a>
													<ul id="inside4" aria-expanded="false" class="collapse <?php if(in_array($current_url,$cms_sub_homepage_testimonial_menus)) { ?> in <?php } ?>">
														<li <?php if($current_url=='admin/sites/testimonialcreate' || $current_url=='admin/sites/testimonialedit') { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/testimonialcreate/'.$siteId;?>"><?php echo I18n::get('Create a Testimonial');?></a></li>
														<li <?php if($current_url=='admin/sites/testimonialbrowse') { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/testimonialbrowse/'.$siteId;?>"><?php echo I18n::get('Browse Testimonials');?></a></li>
													</ul>
												</li>
												<?php $cms_sub_homepage_footer_menus = array('admin/sites/footermenu','admin/sites/sociallinks');	?>
												<li <?php if(in_array($current_url,$cms_sub_homepage_footer_menus)) { ?> class="active"<?php }?>>
													<a aria-expanded="false" href="javascript:void(0);" data-toggle="collapse" data-target="#inside7" class="collapsed"><?php echo I18n::get('Footer Settings');?> <i class="fa fa-fw fa-caret-down"></i></a>
													<ul id="inside7" aria-expanded="false" class="collapse <?php if(in_array($current_url,$cms_sub_homepage_footer_menus)) { ?> in <?php } ?>">
														<li <?php if($current_url=='admin/sites/footermenu')  { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/footermenu/'.$siteId;?>"><?php echo I18n::get('Footer Menu'); //echo isset($site_language['Footer Menu']) ? $site_language['Footer Menu'] : 'Footer Menu';?></a></li>
														<li <?php if($current_url=='admin/sites/sociallinks')  { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/sociallinks/'.$siteId;?>"><?php echo I18n::get('Social links'); //echo isset($site_language['Footer Menu']) ? $site_language['Footer Menu'] : 'Footer Menu';?></a></li>
													</ul>
												</li>
												<?php $cms_sub_homepage_advanced_menus = array('admin/sites/advanced_css');	?>
												<li <?php if(in_array($current_url,$cms_sub_homepage_advanced_menus)) { ?> class="active"<?php }?>>
													<a aria-expanded="false" href="javascript:void(0);" data-toggle="collapse" data-target="#inside6" class="collapsed"><?php echo I18n::get('Advanced CSS');?> <i class="fa fa-fw fa-caret-down"></i></a>
													<ul id="inside6" aria-expanded="false" class="collapse  <?php if(in_array($current_url,$cms_sub_homepage_advanced_menus)) { ?> in <?php } ?>">
														<li <?php if($current_url=='admin/sites/advanced_css') { ?>class="sub-active" <?php } ?>><a href="<?php echo URL::base().'admin/sites/advanced_css/'.$siteId;?>"><?php echo I18n::get('Advanced CSS');?></a></li>
													</ul>
												</li>
											</ul>
										</li><?php
									} ?><?php
								} ?><?php 
								$cms_sub_otherpage_menus = array('admin/cms/create','admin/cms/pagelist'); ?>
								<li <?php if(in_array($current_url,$cms_sub_otherpage_menus)) { ?> class="active"<?php }?>>
									<a aria-expanded="false" href="javascript:void(0);" data-toggle="collapse" data-target="#otherpageinner" class="collapsed"><?php echo I18n::get('Other Pages');?> <i class="fa fa-fw fa-caret-down"></i></a>
									<ul class="collapse <?php if(in_array($current_url,$cms_sub_otherpage_menus)) { ?> in <?php } ?>" id="otherpageinner" aria-expanded="false">
										<?php
										if(Helper_Common::hasAccess('Create Pages'))
										{ ?>
											<li <?php if($current_url=='admin/cms/create') { ?>class="sub-active" <?php } ?>>
												<a href="<?php echo URL::base().'admin/cms/create/'.$siteId; ?>"><?php echo I18n::get('Create Page');?></a>
											</li><?php
										} ?>
										<li <?php if($current_url=='admin/cms/pagelist') { ?>class="sub-active" <?php } ?>>
											<a href="<?php echo URL::base().'admin/cms/pagelist/'.$siteId; ?>"><?php echo I18n::get('Browse Pages');?></a>
										</li>
									</ul>	
								</li><?php
								if(Helper_Common::is_admin() || Helper_Common::is_manager()) { ?>
									<li <?php if($current_url=='admin/questions' || $current_url=='admin/questions/index') { ?>class="sub-active" <?php } ?>>
										<a href="<?php echo URL::base().'admin/questions/index'; ?>"><?php echo I18n::get('Site Questions');?></a>
									</li>
									
									<li <?php if($current_url=='admin/trainer/trainer_profile') { ?>class="sub-active" <?php } ?>>
										<a href="<?php echo URL::base().'admin/trainer/trainer_profile'; ?>"><?php echo I18n::get('Trainer Promo List');?></a>
									</li>
									
									<?php
								} ?>
							</ul>
						</li>
						
						<?php 
						if((Helper_Common::is_admin())) {
							$cms_sub_menus = array('admin/cms/common_create','admin/cms/common_pagelist','admin/commonquestions','admin/commonquestions/index'); 
							?>
							<li>
								<a href="javascript:;" data-toggle="collapse" data-target="#sitecommoncmsmenu" <?php if(in_array($current_url,$cms_sub_menus)) { ?> aria-expanded="true" <?php } ?>><i class="fa fa-list-alt"></i> <?php echo I18n::get('SITE COMMON CMS');?> <i class="fa fa-fw fa-caret-down"></i></a>
								<ul id="sitecommoncmsmenu" class="collapse <?php if(in_array($current_url,$cms_sub_menus)) { ?> in <?php } ?>">
									<li <?php if($current_url=='admin/cms/common_create') { ?>class="sub-active" <?php } ?>>
										<a href="<?php echo URL::base().'admin/cms/common_create/'.$siteId; ?>"><?php  echo I18n::get('Create Common Page');?></a>
									</li>
									<li <?php if($current_url=='admin/cms/common_pagelist') { ?>class="sub-active" <?php } ?>>
										<a href="<?php echo URL::base().'admin/cms/common_pagelist/'.$siteId; ?>"><?php echo I18n::get('Browse Common Pages');?></a>
									</li>
									<li <?php if($current_url=='admin/commonquestions' || $current_url=='admin/commonquestions/index') { ?>class="sub-active" <?php } ?>>
										<a href="<?php echo URL::base().'admin/commonquestions/index'; ?>"><?php echo I18n::get('Common Questions');?></a>
									</li>
									
								</ul>
							</li><?php
						} ?>
					</ul>
				</li>
				<?php
			}
		} ?><?php 
		$user_sub_menu = array('admin/subscriber/browse','admin/subscriber/create','admin/manager/browse','admin/manager/create','admin/trainer/browse','admin/trainer/create','admin/user/create','admin/user/edit');
		$user_sub_menu1 = array('admin/trainer/browse','admin/trainer/create');
		if(Helper_Common::is_admin() || Helper_Common::is_manager()) // || Helper_Common::is_trainer()
		{	?>
			<li <?php if(in_array($current_url,$user_sub_menu)) { ?> class="active"<?php }?>>
				<a href="javascript:;" data-toggle="collapse" data-target="#userslist" <?php if(in_array($current_url,$user_sub_menu)) { ?> aria-expanded="true" <?php } ?>><i class="fa fa-fw fa-users"></i> <?php echo I18n::get('Users');?> <i class="fa fa-fw fa-caret-down"></i></a>
            <ul id="userslist" class="collapse <?php if(in_array($current_url,$user_sub_menu)) { ?> in <?php } ?>">
					<?php
					if(!(Helper_Common::is_trainer()))
					{ ?>
						<li <?php if($current_url=='admin/user/create' || $current_url=='admin/user/edit'){ ?>class="sub-active" <?php } ?>>
							<a href="<?php echo URL::base().'admin/user/create/register'; ?>"><?php echo I18n::get('Create New User');?></a>
						</li>
						<?php
						if(!(Helper_Common::is_trainer()) && !(Helper_Common::is_manager()))
						{ ?>
							<li <?php if($current_url=='admin/manager/browse' || $current_url=='admin/manager/create') { ?>class="sub-active" <?php } ?>>
								<a href="<?php echo URL::base().'admin/manager/browse'; ?>"><?php echo I18n::get('Managers');?></a>
							</li><?php
						} ?>
						<!--li <?php if(in_array($current_url,$user_sub_menu1)) { ?> class="active"<?php }?>>
							<a href="javascript:;" data-toggle="collapse" data-target="#userslist1" <?php if(in_array($current_url,$user_sub_menu1)) { ?> aria-expanded="true" <?php } ?>><?php echo I18n::get('Trainers');?> <i class="fa fa-fw fa-caret-down"></i></a>
							<ul id="userslist1" class="collapse <?php if(in_array($current_url,$user_sub_menu1)) { ?> in <?php } ?>" -->		  
								<li <?php if($current_url=='admin/trainer/browse' || $current_url=='admin/trainer/create') { ?>class="sub-active" <?php } ?>>
									<a href="<?php echo URL::base().'admin/trainer/browse'; ?>"><?php echo I18n::get('Trainers');?></a>
								</li>
								<!--li <?php if($current_url=='admin/trainer/trainer_profile') { ?>class="sub-active" <?php } ?>>
									<a href="<?php echo URL::base().'admin/trainer/trainer_profile'; ?>"><?php echo I18n::get('Trainers Profile');?></a>
								</li-->
							<!--/ul>
						</li--><?php
					} ?>
               <li <?php if($current_url=='admin/subscriber/browse' || $current_url=='admin/subscriber/create') { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/subscriber/browse'; ?>"><?php echo I18n::get('Subscribers');?></a>
					</li>
            </ul>
         </li><?php
		}
		$exercise_sub_menu = array('admin/exercise/create','admin/exercise/edit','admin/exercise/browse','admin/exercise/sample');?>
		<li <?php if(in_array($current_url,$exercise_sub_menu)) { ?> class="active"<?php }?>>
			<a href="javascript:;" data-toggle="collapse" data-target="#exercises" <?php if(in_array($current_url,$exercise_sub_menu)) { ?> aria-expanded="true" <?php } ?> ><i class="fa fa-fw fa-folder-open"></i>
			<?php if($totalxr>0){
				echo '<small class="label pull-right label-success">'.$totalxr.'</small>';
			}
			echo I18n::get('Exercise'); ?> <i class="fa fa-fw fa-caret-down"></i></a>
			<ul id="exercises" class="collapse <?php if(in_array($current_url,$exercise_sub_menu)) { ?> in <?php } ?>">
				<?php
				if(Helper_Common::hasAccess('Create Exercise'))
				{ ?>
					<li <?php if($current_url=='admin/exercise/create' || $current_url=='admin/exercise/edit'){ ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/exercise/create?act=lib'; ?>"><?php echo I18n::get('Create New Exercise');?></a>
					</li><?php
				} ?>
				<li <?php if($current_url=='admin/exercise/browse' && (!isset($_GET['d']) || empty($_GET["d"]))){ ?>class="sub-active" <?php } ?>>
					<a href="<?php echo URL::base().'admin/exercise/browse'; ?>"><?php echo I18n::get('My Exercises');?></a>
				</li><?php
				if(Helper_Common::is_admin()){ ?>
					<li <?php if($current_url=='admin/exercise/sample' && isset($_GET['d']) && $_GET["d"]==2){ ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/exercise/sample?d=2'; ?>"><?php echo __("Sample Exercises");  ?></a>
					</li>
					<li <?php if($current_url=='admin/exercise/sample' && isset($_GET['d']) && $_GET["d"]==1){ ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/exercise/sample?d=1'; ?>"><?php echo I18n::get('Default Exercises');?></a>
					</li>
					<?php
				}else{ ?>
					<li <?php if($current_url=='admin/exercise/sample' && (!isset($_GET['d']) || empty($_GET["d"]))){ ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/exercise/sample'; ?>"><?php echo __("Sample Exercises");  ?></a>
					</li><?php
				} ?>
				<li <?php if($current_url=='admin/exercise/browse' && isset($_GET['d']) && $_GET["d"]==3){ ?>class="sub-active" <?php } ?>>
					<a href="<?php echo URL::base().'admin/exercise/browse?d=3'; ?>">
					<?php if($sharedxr>0){
						echo '<small class="label pull-right label-success">'.$sharedxr.'</small>';
					} 
					echo I18n::get('Shared Exercises'); ?></a>
				</li>
			</ul>
      </li>
		<?php 
		$workout_sub_menu = array('admin/workout/create','admin/workout/browse','admin/workout/sample','admin/workout/shared','admin/workout/edit','admin/workout/sampleedit');
		?>
      <li <?php if(Helper_Common::checkKeyexistsInArray($current_url,$workout_sub_menu)){ ?> class="active"<?php }?>>
			<a href="javascript:;" data-toggle="collapse" data-target="#workouts" <?php if(Helper_Common::checkKeyexistsInArray($current_url,$workout_sub_menu)){ ?> aria-expanded="true" <?php } ?>><i class="fa fa-fw fa-folder-open"></i>
			<?php
			if($totalwkout>0){
				echo '<small class="label pull-right label-success">'.$totalwkout.'</small>';
			}
			?>
			<?php echo I18n::get('Workouts');?> <i class="fa fa-fw fa-caret-down"></i></a>
			<ul id="workouts" class="collapse <?php if(Helper_Common::checkKeyexistsInArray($current_url,$workout_sub_menu)){ ?> in <?php } ?>">
            <li <?php if($current_url=='admin/workout/browse' || $current_url=='admin/workout/edit') { ?>class="sub-active" <?php } ?>>
					<a href="<?php echo URL::base().'admin/workout/browse'; ?>"><?php echo I18n::get('My Workout Plans');?>
					</a>
            </li>
				<li <?php if(($current_url=='admin/workout/sample' && (!isset($_GET["d"]) || $_GET["d"]==0)) || ($current_url=='admin/workout/sampleedit' && (!isset($_GET["d"]) || $_GET["d"]==0) )) { ?>class="sub-active" <?php } ?>>
					<a href="<?php echo URL::base().'admin/workout/sample'; ?>"><?php echo I18n::get('Sample Workout Plans');?>
						<?php
						if($samplewkout>0){
							echo '<small class="label pull-right label-success">'.$samplewkout.'</small>';
						}
						?>
					</a>
            </li>
				<?php
				if(Helper_Common::is_admin())
				{ ?>
					<li <?php if(($current_url=='admin/workout/sample' && isset($_GET["d"]) && $_GET["d"]==1) || ($current_url=='admin/workout/sampleedit' && isset($_GET["d"]) && $_GET["d"]==1)) { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/workout/sample?d=1'; ?>"><?php echo I18n::get('Default Workout Plans');?></a>
					</li><?php
				}?>
			<li <?php if($current_url=='admin/workout/shared') { ?>class="sub-active" <?php } ?>>
				<a href="<?php echo URL::base().'admin/workout/shared'; ?>"><?php echo I18n::get('Shared Workout Plans');?>
					<?php
					if($sharedwkout>0){
						echo '<small class="label pull-right label-success">'.$sharedwkout.'</small>';
					}
					?>
				</a></li>                           
         </ul>
      </li><?php 
		$image_setting = array('admin/image/exerciseimages','admin/image/exerciseimages/1','admin/image/exerciseimages/2','admin/image/exerciseimages/4');
		?>
		<li <?php if(in_array($current_url,$image_setting)) { ?> class="active"<?php }?>>
			<a href="javascript:;" data-toggle="collapse" data-target="#imageupload"  <?php if(in_array($current_url,$image_setting)) { ?> aria-expanded="true" <?php } ?>><i class="fa fa-upload"></i> <?php echo I18n::get('Image Uploads');?> <i class="fa fa-fw fa-caret-down"></i></a>
			<ul id="imageupload" class="collapse <?php if(in_array($current_url,$image_setting)) { ?> in <?php } ?>" >
				<li <?php if($current_url=='admin/image/exerciseimages'  && Request::current()->param('id') == '') { ?>class="sub-active" <?php } ?>>
					<a href="<?php echo URL::base().'admin/image/exerciseimages'; ?>"><?php echo I18n::get('Upload Images');?></a>
				</li>
				<li <?php  if($current_url=='admin/image/exerciseimages' && Request::current()->param('id') == 1) { ?>class="sub-active" <?php } ?>>
					<a href="<?php echo URL::base().'admin/image/exerciseimages/1'; ?>"><?php echo I18n::get('My Images');?></a>
				</li>
				<li <?php if($current_url=='admin/image/exerciseimages'  && Request::current()->param('id') == 2) { ?>class="sub-active" <?php } ?>>
					<a href="<?php echo URL::base().'admin/image/exerciseimages/2'; ?>"><?php echo I18n::get('Sample Images');?></a>
				</li>
				<!--<li <?php //if($current_url=='admin/image/exerciseimages'  && Request::current()->param('id') == 3) { ?>class="sub-active" <?php //} ?>>
					<a href="<?php //echo URL::base().'admin/image/exerciseimages/3'; ?>"><?php //echo I18n::get('Shared Images');?></a>
				</li> -->
				<?php
				if(Helper_Common::is_admin())
				{ ?>
					<li <?php if($current_url=='admin/image/exerciseimages' && Request::current()->param('id') == 6) { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/image/exerciseimages/6'; ?>"><?php echo I18n::get('Default Images');?></a>
					</li>
					<?php
				}?>
			</ul>
		</li>
		<?php 
		$setting_sub_menu = array('admin/role/access_settings','admin/settings/preference_settings','admin/language/browse');
		if(Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer())
		{?>
			<li <?php if(in_array($current_url,$setting_sub_menu)) { ?> class="active"<?php }?>>
				<a href="javascript:;" data-toggle="collapse" data-target="#settingsmenu" <?php if(in_array($current_url,$setting_sub_menu)) { ?> aria-expanded="true" <?php } ?>><i class="fa fa-cogs"></i> <?php echo I18n::get('Settings');?> <i class="fa fa-fw fa-caret-down"></i></a>
				<ul id="settingsmenu" class="collapse <?php if(in_array($current_url,$setting_sub_menu)) { ?> in <?php } ?>">
					<?php
					if(!Helper_Common::is_trainer())
					{ ?>
						<li <?php if($current_url=='admin/role/access_settings') { ?>class="sub-active" <?php } ?>>
							<a href="<?php echo URL::base().'admin/role/access_settings/'; ?>"><?php echo I18n::get('Permissions');?></a>
						</li>
						<?php
					} ?>
					<li <?php if($current_url=='admin/settings/preference_settings') { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/settings/preference_settings'; ?>"><?php echo I18n::get('Preference Presets');?></a>
					</li>
					<!--li <?php if($current_url=='admin/language/browse') { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/language/browse'; ?>"><?php echo I18n::get('Language');?></a>
					</li--> 
				</ul>
         </li><?php
		}
		if(Helper_Common::is_admin())
		{ 	
			$device_sub_menu = array('admin/devicemanager/create','admin/devicemanager/browse');	?>
			<li <?php if(in_array($current_url,$device_sub_menu)) { ?> class="active"<?php }?>>
				<a href="javascript:;" data-toggle="collapse" data-target="#devicemenu" <?php if(in_array($current_url,$device_sub_menu)) { ?> aria-expanded="true" <?php } ?>><i class="fa fa-cogs"></i> <?php echo I18n::get('Device Manager');?> <i class="fa fa-fw fa-caret-down"></i></a>
				<ul id="devicemenu" class="collapse <?php if(in_array($current_url,$device_sub_menu)) { ?> in <?php } ?>">
               <li <?php if($current_url=='admin/devicemanager/create') { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/devicemanager/create'; ?>"><?php echo I18n::get('Create Device');?></a>
               </li> 
					<li <?php if($current_url=='admin/devicemanager/browse') { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/devicemanager/browse'; ?>"><?php echo I18n::get('Browse Device');?></a>
               </li> 
            </ul>
         </li><?php
		}
		if((Helper_Common::is_admin()))
		{
			//echo Request::current()->param('id');
			$device_sub_menu = array('admin/dashboard/error');
			?>
			<li <?php if($current_url=='admin/dashboard/error') { ?>class="active" <?php } ?> >
				<a href="javascript:void(0);" data-toggle="collapse" data-target="#errorfeed" <?php if(in_array($current_url,$device_sub_menu)) { ?> aria-expanded="true" <?php } ?> ><i class="fa fa-exclamation-triangle"></i> <?php echo I18n::get('Error');?>
				
				<?php
				if(isset($e_cnt) && $e_cnt>0){ ?>
					<small class="label pull-right label-success" id='e_unread'><?php echo $e_cnt; ?></small><?php
				}?>
				<i class="fa fa-fw fa-caret-down"></i></a>
				<ul id="errorfeed" class="collapse <?php if(in_array($current_url,$device_sub_menu)) { ?> in <?php } ?>">
               <li <?php if($current_url=='admin/dashboard/error' && Request::current()->param('id')==1) { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/dashboard/error/1'; ?>"><?php echo I18n::get('PHP Errors');?>
							<?php
							if(isset($php_cnt) && $php_cnt>0){ ?>
								<small class="label pull-right label-success" id='php_unread'><?php echo $php_cnt; ?></small><?php
							}?>
						</a> 
               </li> 
					<li <?php if($current_url=='admin/dashboard/error' && Request::current()->param('id')==2) { ?>class="sub-active" <?php } ?>>
						<a href="<?php echo URL::base().'admin/dashboard/error/2'; ?>"><?php echo I18n::get('Mysql Errors');?>
						<?php
						if(isset($mysql_cnt) && $mysql_cnt>0){ ?>
							<small class="label pull-right label-success" id='mysql_unread'><?php echo $mysql_cnt; ?></small><?php
						}?>
						</a>
               </li> 
            </ul>
         </li><?php
		}	?>
			<?php //start dh added for site Language
					 /*if((Helper_Common::is_admin())) { ?>
						  <li <?php if($current_url=='admin/setlanguage') { ?>class="active" <?php } ?> >
							<a href="<?php echo URL::base().'admin/setlanguage'; ?>"><i class="fa fa-language"></i> <?php echo (isset($site_language['Set Language'])) ? $site_language['Set Language'] : 'Set Language';?></a>
						  </li>
			<?php }*///End dh added for site Language ?>
	</ul>
</div>
<!-- /.navbar-collapse -->
</nav>
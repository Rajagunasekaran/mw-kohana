<div id="wrap-index">
  <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <div class="container" id="home">
	<form action="" method="post" data-ajax="false" data-role="none">
	<input type="hidden" id="s_row_count" name="s_row_count" value="<?php echo (isset($myworkouts) ? count($myworkouts) : '0');?>"/>
	<div class="row" id="successmsgdiv">
		<?php $session = Session::instance();
			if ($session->get('success')): ?>
		  <div class="banner success alert alert-success">
			<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<span class="successmsg"><?php echo $session->get_once('success') ?></span>
		  </div>
		 <?php endif ?>
	</div>
	<div class="row">
		<div class="border">
			<div class="col-xs-3 aligncenter">
				<a data-ajax='false' data-role="none" href="<?php echo (!empty($parentFolder) ? URL::base(TRUE).'exercise/myworkout/'.(!empty($parentFolder['parent_folder_id']) ? $parentFolder['parent_folder_id'] : '') : URL::base(TRUE).'exercise/index/'); ?>">
					<i class="fa fa-caret-left iconsize"></i>
				</a>
			</div>
			<div class="col-xs-6 aligncenter">
				<?php echo (isset($parentFolder['folder_title']) && !empty($parentFolder['folder_title']) ? __('Workout Folder').':<br>'.$parentFolder['folder_title'] : __('My Workout Plans'));?> <span class="editmode hide"> : <?php echo __('Edit Data'); ?></span>
			</div>
			<div class="col-xs-3" id="save-icon-button"></div>
		</div>
	</div>
	<?php //if(Helper_Common::hasAccess('Manage Workouts')){ ?>
	<div class="row">
		<div class="">
			<div class="">
				<div class="mobpadding">
					<div class="border full optionmenu">
						<?php if(Helper_Common::hasAccess('Create Workouts')){ ?>
							<div class="createwkout col-xs-4 menuactive tour-step tour-step-1"><button data-ajax='false' data-role="none" class="btn btn-default" type="button" onclick="createFolderModel('','addfolder','<?php echo $parentFolderId;?>');" ><i class="fa fa-folder-o"></i></button><br><span class="inactivedatacol">+folder</span></div>
							<div class="createwkout col-xs-4 menuactive tour-step tour-step-2"><button data-ajax='false' data-role="none" class="btn btn-default" type="button" onclick="addAssignWorkoutsByDate('','0','0','');" ><i class="fa fa-plus plussign"></i></button><br><span class="inactivedatacol">+workout</span></div>
						<?php } ?>
						<div class="<?php echo (Helper_Common::hasAccess('Create Workouts') ? 'hide' :'');?> allowhide"><button data-ajax='false' data-role="none" name="f_method" class="btn btn-default" type=="button" onclick="return checkallItems(this)"><i class="fa fa-check-circle-o"></i></button><br><span class="inactivedatacol">all/none</span></div>	
						<div class="<?php echo (Helper_Common::hasAccess('Create Workouts') ? 'hide' :'');?> allowhide"><button data-ajax='false' data-role="none" name="f_method" class="btn btn-default" type="submit" onclick="return doCopyWorkoutSubmit();" value="copy"><i class="fa fa-files-o datacol allowActive"></i></button><br><span class="inactivedatacol">clone</span></div>	
						<div class="<?php echo (Helper_Common::hasAccess('Create Workouts') ? 'hide' :'');?> allowhide"><button data-ajax='false' data-role="none" name="f_method" class="btn btn-default" type="submit" onclick="return doDeleteWorkoutSubmit()" value="delete"><i class="fa fa-times datacol allowActive"></i></button><br><span class="inactivedatacol">delete</span></div>
						<?php if(Helper_Common::hasAccess('Create Workouts')){ ?>
							<div class="borderright"></div>
							<div class="tour-step tour-step-3"><button data-ajax='false' data-role="none" name="f_method" id="editxr" onclick="return editWorkoutPlans(this);" class="btn btn-default"><i class="fa fa-list-ul"></i></button><button data-ajax='false' data-role="none" name="f_method" id="refresh" onclick="return editWorkout(this);" class="btn btn-default hide"><i class="fa fa-refresh"></i></button><br><span class="inactivedatacol">plans/list</span></div>
						<?php }  ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php //} ?>
 	<br>
 	<div class="row"><div class="col-xs-12"><div class="col-xs-9 tour-step tour-step-4"></div><div class="col-xs-3 tour-step tour-step-5"></div></div></div>
	
	<ul class="sTreeBase bgC4 col-xs-12 scrollwkout" style="border:1px solid #ededed;<?php if(isset($parentFolder) && !empty($parentFolder)){ ?> margin-top:0px; <?php } ?>" >
		<div id="sticky-anchor" class="<?php if(isset($parentFolder) && empty($parentFolder)){ ?> hide <?php } ?>"></div>
		<li id="sticky-header" class="row sticky <?php if(isset($parentFolder) && empty($parentFolder)){ ?> hide <?php } ?>">
			<ul class="sTreeBaseparent bgC4 col-xs-12 <?php if(isset($parentFolder) && empty($parentFolder)){ ?> hide <?php } ?>">
				<?php if(isset($parentFolder) && !empty($parentFolder)){ ?>
					<li class="row col-xs-12 bgC4 item_parent_noclick" data-id='itemparent_<?php echo $parentFolder['parent_folder_id'];?>' data-module="item_parent" id="itemparent_<?php echo $parentFolder['parent_folder_id'];?>">
						<div class="row">
							<div class="mobpadding">
								<a data-ajax='false' data-role="none" class="foldericon" href="<?php echo (!empty($parentFolder) ? URL::base(TRUE).'exercise/myworkout/'.(!empty($parentFolder['parent_folder_id']) ? $parentFolder['parent_folder_id'] : '') : URL::base(TRUE).'exercise/index/'); ?>">
								<div class="border full">
									<div class="colorchoosen col-xs-2 alignright">	
										<i class="fa fa-folder-open-o"></i>
									</div>
									<div class="col-xs-7 editrecordlist">
										<?php echo __('Parent Folder'); ?>:<br><span><?php echo (isset($parentFolder['parent_folder_name']) && !empty($parentFolder['parent_folder_name']) ? $parentFolder['parent_folder_name'] : __('My Workout Plans'));?></span>
									</div>
									<div class="col-xs-3" id="save-icon-button"></div>
								</div>
								</a>
							</div>
						</div>
						<ul class="bgC4_ul_parent"></ul>
					</li>
				<?php } ?>
			</ul>
		</li>
	<?php if(isset($myworkouts) && count($myworkouts) > 0){ 
	foreach($myworkouts as $keys => $values){
		$order = $keys+1;
		if(isset($values['wkout_id']) && ($values['wkout_id'] != '0' )){ ?>
			<li class="bgC4 item_workout_noclick" data-id='itemworkout_<?php echo $values['wkout_id'].'_'.$values['wksid'].'_'.$order;?>' data-module="item_workout" id="itemworkout_<?php echo $values['wkout_id'].'_'.$values['wksid'].'_'.$order;?>">
				<div class="row item_workout_click" id="wkout_<?php echo $values['wkout_id'].'_'.$values['wksid'];?>">
					<div class="mobpadding">
					<div class="border full">
						<div class="checkboxchoosen col-xs-2" style="display:none;">
							<div class="checkboxcolor">
								<label>
									<input data-role="none" data-ajax="false" onclick="enableButtons();"  type="checkbox" class="checkhidden" name="workouts[]" value="<?php echo $values['wkout_id'];?>">
									<span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span>
								</label>
							</div>
						</div>
						<div class="row-pad editoption col-xs-9" style="cursor:pointer" onclick="getworkoutpreview('<?php echo $values['wkout_id'];?>');">
							<div class="colorchoosen col-xs-2">
								<i class="colorcenternew glyphicon <?php echo $values['color_title'];?>"></i>
							</div>
							<div class="titlechoosen col-xs-9 wrapword">
								<div><?php echo $values['wkout_title'] ;?></div>
								<div class="wkoutfocus"><?php echo ucfirst($values['wkout_focus']);?></div>
							</div>
						</div>
						<div class="row-pad col-xs-0">
							<div class="editchoosen">
								<div class="editchoosenIconOne">
									<i class="fa fa-ellipsis-h iconsize" onclick="getTemplateOfWorkoutAction('<?php echo $values['wkout_id'];?>','<?php echo $values['wksid'];?>','<?php echo addslashes($values['wkout_title']) ;?>');"></i>
								</div>
								<div class="editchoosenIconTwo hide">
									<i class="fa fa-bars panel-draggable" style="cursor: move;"></i>
								</div>
							</div>
						</div>
						<div class="navmenu hide">
						</div>
					</div>
					</div>
				</div>
			</li>
		<?php }else{ ?>
			<li class="bgC4" data-id="itemfolder_<?php echo $values['folder_id'].'_'.$values['wksid'].'_'.$order;?>" data-module="item_folder" id="itemfolder_<?php echo $values['folder_id'].'_'.$values['wksid'].'_'.$order;?>">
				<div class="row" id="wkoutfold_<?php echo $values['folder_id'].'_'.$values['wksid'];?>">
					<div class="mobpadding">
						<div class="border full">
							<div class="checkboxchoosen col-xs-2">
								<div class="checkboxcolor">
									<label>
										<input data-role="none" data-ajax="false" onclick="enableButtons();" type="checkbox" class="checkhidden" name="folders[]" value="<?php echo $values['folder_id'];?>">
										<span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span>
									</label>
								</div>
							</div>
							<div class="row-pad editoption col-xs-9">
								<a data-ajax='false' data-role="none" href="<?php echo URL::base(TRUE).'exercise/myworkout/'.$values['folder_id']; ?>" title="<?php echo ucfirst($values['folder_title']);?>">
									<div class="colorchoosen col-xs-2 alignright">
										<i class="fa fa-folder-o"></i>
									</div>
									<div class="foldertitle col-xs-9 wrapword">
											<?php echo ucfirst($values['folder_title'])."<br>";?>
											<span class="navimgdet3"><?php echo(($values['totalRecords'] > 0 )? (($values['totalRecords'] > 1 ) ? __('Records').' : <span class="itemfolder_'.$values['folder_id'].'_'.$values['wksid'].'_'.$order.'">'.$values['totalRecords'].'</span>' : __('Record').' : <span class="itemfolder_'.$values['folder_id'].'_'.$values['wksid'].'_'.$order.'">'.$values['totalRecords'].'</span>') : __('Record').' : <span class="itemfolder_'.$values['folder_id'].'_'.$values['wksid'].'_'.$order.'">0</span>');?></span>
									</div>
								</a>
							</div>
							<div class="row-pad col-xs-0">
								<div class="editchoosen">
									<div class="editchoosenIconOne">
										<a data-ajax='false' data-role="none" href="javascript:void(0);"  onclick="createFolderModel('<?php echo $values['folder_id'];?>','editfolder','<?php echo $parentFolderId;?>');" ><i class="fa fa-pencil-square-o"></i></a>
									</div>
									<div class="editchoosenIconTwo hide">
										<i class="fa fa-bars panel-draggable" style="cursor: move;"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<ul class="bgC4_ul"></ul>
			</li>
		<?php 	}
			} 
		}
	?>
	</ul>
	<input type="hidden" value="<?php echo $parentFolderId;?>" name="parent_folder_id" id="parent_folder_id"/>
	<input type="hidden" id="newlyAddedXr" name="newlyAddedXr" value="0"/>
	<div class="removedIds"></div>
	</form>
  </div>
  <div id="myModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="myOptionsModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="mypopupModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="myOptionsModalAjax" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="FolderModal" class="modal fade" role="dialog" tabindex="-1"></div>
  <div id="myOptionsModalExerciseRecord" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>  
  <script type="text/javascript">
	function editWorkoutPlans(selector){
		if($('.editmode').hasClass('hide'))
			$('.editmode').removeClass('hide');
		$(selector).addClass('hide');
		$('#refresh').removeClass('hide');
		$('.createwkout').addClass('hide');
		$('.optionmenu div.allowhide').removeClass('hide');
		$('.checkboxchoosen').show();
		$('.editchoosenIconTwo').removeClass('hide');
		$('.editchoosenIconOne').addClass('hide');
		$('.activelink').attr('disabled','disabled');
		$('.sTreeBase').attr('id', 'sTree2');
		$("ul#sTree2, ul.bgC4_ul, ul.sTreeBaseparent , ul.bgC4_ul_parent").sortable({
			tolerance: 'pointer',
			revert: 'invalid',
			cursor: "move",
			forceHelperSize: true,
			forcePlaceholderSize: true,
			connectWith: "ul#sTree2 > li > ul.bgC4_ul, ul.sTreeBaseparent > li > ul.bgC4_ul_parent",
			placeholder: "sortableListsHint",
			axis: 'y',
			handle: '.panel-draggable',
			dropOnEmpty: true,
			opacity: 0.8,
			helper: 'clone',
			start:function( event, ui ) {
				$('ul.bgC4_ul').hide();
				if($('li.sortableListsHint.ui-sortable-helper').closest("li").find('ul.bgC4_ul')){
					$('ul.bgC4_ul').show();
				}else if($('li.ui-sortable-helper').closest("li").find('ul.bgC4_ul_parent')){
					$('ul#sTree2').css('overflow-x','scroll');
					$('ul.bgC4_ul').hide();
				}
				$('ul.bgC4_ul_parent').show();
				$('#sticky-anchor').height($('#sticky-header').outerHeight()); // resize sticky-anchor height
			},
			change:function( event, ui ) {
				$('ul.bgC4_ul').hide();
				if($('li.sortableListsHint.ui-sortable-helper').closest("li").find('ul.bgC4_ul')){
					$('ul.bgC4_ul').show();
				}else if($('li.ui-sortable-helper').closest("li").find('ul.bgC4_ul_parent')){
					$('ul.bgC4_ul').hide();
				}
				$('ul.bgC4_ul_parent').show();
			},
			update: function(event, ui) {
				//console.log(myOrder);
				$('ul.bgC4_ul').hide();
				$('ul.bgC4_ul_parent').hide();
				if(ui.item.closest('ul').hasClass('bgC4_ul')){
					ui.item.closest('ul').hide();
				}
				if(ui.item.closest('ul').hasClass('bgC4_ul_parent')){
					ui.item.closest('ul').hide();
				}
				//console.log(sortedIDs);
				//console.log(myOrder);
			},
			stop:function(event, ui) {
				$('ul.bgC4_ul_parent').hide();
				var myOrder = resultArr = new Array();
				if(ui.item.closest('ul').hasClass('bgC4_ul') && ui.item.closest('ul.bgC4_ul').find('li')){
					var myOrder = [{key: ui.item.closest('ul').parent('li').attr('id'), value: ui.item.attr('id')}];
					console.log('===');
					$('ul.bgC4_ul li').remove();
				}else if(ui.item.closest('ul').hasClass('bgC4_ul_parent') && ui.item.closest('ul.bgC4_ul_parent').find('li')){
					var myOrder = [{key: ui.item.closest('ul').parent('li').attr('id'), value: ui.item.attr('id')}];
					console.log('===');
					$('ul.bgC4_ul_parent li').remove();
				}
				var sortedIDs = $( "ul#sTree2" ).sortable( "toArray",{attribute: 'id'} );
				var sortedmodules = $( "ul#sTree2" ).sortable( "toArray",{attribute: 'data-module'} );
				for (var i=0; i < sortedIDs.length; i++){
					if($('ul.bgC4_ul_parent').length > 0 && i == 0){
						var sortedparentIDs = $( "ul.sTreeBaseparent" ).sortable( "toArray",{attribute: 'id'} );
						console.log(sortedparentIDs[i]);
						if(sortedparentIDs[i] != 'sticky-anchor' && sortedparentIDs[i] != 'sticky-header'){
							resultArr[i] ={id:sortedparentIDs[i],module:'item_parent'};
							if(typeof(myOrder[0]) != 'undefined' && myOrder[0].key == sortedparentIDs[i]){
								resultArr[i]['children'] ={id:myOrder[0].value};
							}
						}
					}
					if(sortedIDs[i] != 'sticky-anchor' && sortedIDs[i] != 'sticky-header'){
						resultArr[i+1] ={id:sortedIDs[i],module:sortedmodules[i]};
						if(typeof(myOrder[0]) != 'undefined' && myOrder[0].key == sortedIDs[i]){
							resultArr[i+1]['children'] ={id:myOrder[0].value};
						}
					}
				}
				$('#sticky-anchor').height($('#sticky-header').outerHeight()); // resize sticky-anchor height
				console.log(resultArr);
				console.log(myOrder);
				$.ajax({
					url : siteUrl+"ajax/wkoutorder",
					cache: false,
					type: "POST",
					data : {
						action : 'seq_order',
						data : resultArr,
						parentid : <?php echo (is_numeric($parentFolderId) ? $parentFolderId : '0');?>
					},
					success : function(content){
						//console.log(content);
						var parsed = JSON.parse(content);
						var arr = [];
						for(var x in parsed){						  
							$('.'+x).text(parsed[x]);
						}
						$('div#successmsgdiv').html('<div class="banner success alert alert-success"><a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><span class="successmsg">Updated succesfully!!!</span></div>');
					}
				});
			},
		}).disableSelection();
		getWkoutPlanInstruction();
		return false;
	}
	/*for foating header*/
	function sticky_relocate() {
		var ul_top = $('ul.sTreeBase').scrollTop();
		if (ul_top >= 0) {
		 	$('#sticky-header').addClass('sticky');
		 	$('#sticky-header').css('top', ul_top);
			$('#sticky-anchor').height($('#sticky-header').outerHeight());
		} else {
			$('#sticky-header').removeClass('sticky');
			$('#sticky-header').css('top', ul_top);
			$('#sticky-anchor').height(0);
		}
	}
	$(function() {
		$('ul.sTreeBase').scroll(sticky_relocate);
			sticky_relocate();
	});
  </script>
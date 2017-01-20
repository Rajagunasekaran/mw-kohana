<div id="wrap-index">
  <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <?php $user = Auth::instance()->get_user();?>
  <div class="container" id="home">
	<div class="row">
		<?php $session = Session::instance();
			if ($session->get('success')): ?>
		  <div class="banner success alert alert-success">
			<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $session->get_once('success') ?>
		  </div>
		 <?php endif ?>
	</div>
	<div class="row">
		<div class="border">
			<div class="col-xs-3 aligncenter">
				<a data-ajax="false" data-role="none" href="<?php echo URL::base(TRUE).'dashboard/index'; ?>" title="Back">
					<i class="fa fa-caret-left iconsize"></i>
				</a>
			</div>
			<div class="col-xs-6 aligncenter">
				<?php echo __('Workout Plans'); ?>
			</div>
			<div class="col-xs-3 aligncenter">
				<form data-ajax="false" data-role="none" style="display:none" role="form" class="search-form-2"> 
					<a data-ajax="false" data-role="none" href="javascript:void(0);" data-toggle="modal" data-target="#searchmodel"><i class="fa fa-search iconsize2"></i></a>
				</form>
			</div>
		</div>
	</div>
	<hr>
	<?php if(Helper_Common::hasAccess('Create Workouts') && $user->user_profile!=1){ ?>
	<div class="row tour-step tour-step-1">
		<a onclick="addAssignWorkoutsByDate('','0','0','');" data-ajax="false" data-role="none" href="javascript:void(0)" title="My Workout Plans">
            <div class="col-xs-12">
                <div class="col-xs-3 aligncenter">
                    <i class="fa fa-plus iconsize2 activedatacol"></i>
                </div>
                <div class="col-xs-9 activedatacol">
                   <?php echo __('Create New Workout Plan'); ?>
                </div>
            </div>
		</a>
	</div>
	<hr>
	<?php }
		  if($wkoutcount > 0){ //applying if count is greater to enable the my workouts folder ?>
	<div class="row tour-step tour-step-2">
		<a data-ajax="false" data-role="none" href="<?php echo URL::base(TRUE).'exercise/myworkout'; ?>" title="My Workout Plans">
            <div class="col-xs-12">
                <div class="col-xs-3 aligncenter">
                    <i class="fa fa-folder-o iconsize2 activedatacol"></i>
                </div>
                <div class="col-xs-9 activedatacol">
                    <?php echo __('My Workout Plans'); ?> (<?php echo $wkoutcount;?>)
                </div>
            </div>
		</a>
	</div>
	<?php }
		  if($user->user_profile!=1){ ?>
	<hr>
	<div class="row tour-step tour-step-3">
		<a data-ajax="false" data-role="none" href="<?php echo ($samplecount==0) ? 'javascript:void(0);' : URL::base(TRUE).'exercise/sampleworkout'; ?>" title="Sample Workout Plans">
			<div class="col-xs-12">
                <div class="col-xs-3 aligncenter">
                    <i class="fa fa-folder-o iconsize2 <?php echo ($samplecount==0) ? 'inactivedatacol' : 'activedatacol'; ?>"></i>
                </div>
                <div class="col-xs-9 <?php echo ($samplecount==0) ? 'inactivedatacol' : 'activedatacol'; ?>">
                    <?php echo __('Sample Workout Plans'); ?> (<?php echo $samplecount;?>) <?php if(isset($samplecnt) && $samplecnt>0){ ?>
							<span class="actioncount"><?php echo '&nbsp;&nbsp;'.$samplecnt.'&nbsp;&nbsp;';?></span>
						<?php } ?>
                </div>
			</div>
		</a>
	</div>
    <hr>
	<div class="row  tour-step tour-step-4">
		<a data-ajax="false" data-role="none" href="<?php echo ($sharedcount==0) ? 'javascript:void(0);' : URL::base(TRUE).'exercise/sharedworkout'; ?>" title="Shared Workout Plans">
            <div class="col-xs-12">
                <div class="col-xs-3 aligncenter">
                    <i class="fa fa-folder-o iconsize2 <?php echo ($sharedcount==0) ? 'inactivedatacol' : 'activedatacol'; ?>"></i>
                </div>
                <div class="col-xs-9 <?php echo ($sharedcount==0) ? 'inactivedatacol' : 'activedatacol'; ?>">
                    <?php echo __('Shared Workout Plans'); ?> (<?php echo $sharedcount;?>) <?php if(isset($sharedcnt) && $sharedcnt>0){ ?>
							<span class="actioncount"><?php echo '&nbsp;&nbsp;'.$sharedcnt.'&nbsp;&nbsp;';?></span>
						<?php } ?>
                </div>
            </div>
		</a>
	</div>
	<?php } ?>
	<br>
  </div>
  <div id="searchmodel" class="modal fade" role="dialog" tabindex="-1">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content aligncenter">
		  <div class="modal-header" style="border-bottom:0">
			<button data-ajax="false" data-role="none" type="button" class="close" onclick="$('#search-workplan').val('')" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?php echo __('Search Workout Plans'); ?></h4>
		  </div>
		  <div class="modal-body">
			<form data-ajax="false" data-role="none" action="#" method="post">
				<div class="aligncenter" style="width:75%;margin: 0 auto 0 auto">
					<input type="text" id="search-workplan" name="q" onkeypress="getWorkoutsByajax();"  autocomplete="off" class="form-control input-lg" value="">
				</div>
			</form>
		  </div>
		  <div class="modal-footer" style="border-top:0">
			<button data-ajax="false" data-role="none" type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#search-workplan').val('')"><?php echo __('Cancel'); ?></button>
		  </div>
		</div>

	  </div>
  </div>
  <input type="hidden" id="newlyAddedXr" name="newlyAddedXr" value="0"/>
  <div id="myModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="myOptionsModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="FolderModal" class="modal fade" role="dialog" tabindex="-1"></div>
  <div id="mypopupModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="myOptionsModalAjax" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="myOptionsModalExerciseRecord" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>  
  <script>
function getWorkoutsByajax(){
	if($('#search-workplan').length){
		$('#search-workplan').autocomplete({
			source : function(requete, reponse){ // les deux arguments représentent les données nécessaires au plugin
				$.ajax({
					url : "<?php echo URL::base(TRUE).'search/getajax';?>",
					dataType : 'json', // on spécifie bien que le type de données est en JSON
					data : {
						action : 'workoutplan',
						title : $('#search-workplan').val(), // on donne la chaîne de caractère tapée dans le champ de recherche
						maxRows : 5
					},
					success : function(donnee){
						if(donnee){
							reponse($.map(donnee, function(item){
								return {
									url: item.weburl,
									titre: item.titre,
									color: item.color
								}
							}));
						}

					}
				});
			},

			select: function( event, ui ) {
				window.location = ui.item.weburl;
			}
		}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			if(item.color.length)
				return $( "<li>" ).append( "<a href='"+item.url+"' target='_parent'><div class='col-xl-6 colorchoosen'><i class='glyphicon' style='background-color:"+item.color+";'></i></div><div class='col-xl-6'>" + item.titre + "</div></a>" ).appendTo( ul );
			else
				return $( "<li>" ).append( "<a href='"+item.url+"' target='_parent'>" + item.titre + "</div></a>" ).appendTo( ul );
         };
	}
}
function isNumberKey(evt, act) {
	var keyCode = (evt.which?evt.which:(evt.keyCode?evt.keyCode:0));
	if(act && act == 'codePromo')
		if ((keyCode == 44) || (keyCode == 46)) return false;
	if(act && act == 'trackStats' && keyCode > 36 && keyCode < 41) return true;
	if ((keyCode == 8) || (keyCode == 9) || (keyCode == 46)) return true;
	if ((keyCode < 48) || (keyCode > 57) || (keyCode == 46) || (keyCode == 34) || (keyCode == 37)) return false;
	return true;
}

function strip_tags(input, allowed) {
	allowed = (((allowed || '') + '')
	.toLowerCase()
	.match(/<[a-z][a-z0-9]*>/g) || [])
	.join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	return input.replace(commentsAndPhpTags, '')
	.replace(tags, function($0, $1) {
	  return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	});
}
</script>
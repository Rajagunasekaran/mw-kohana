<div id="wrap-index">
  <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <div class="container" id="home">
  <form action="" method="post" data-ajax="false">
  <input data-role="none" type="hidden" name="getdate" id="getdate" value="<?php echo $getdate; ?>" />
  <input data-role="none" type="hidden" name="todaydate" id="todaydate" value="<?php echo date('Y-m-d'); ?>" />
	<div class="row">
		<div class="mobpadding">
		<div class="border full calendartoolbar">
			<div class="col-xs-10 aligncenter">
				<div class="btn-group btn-toggle">
					<button id="assigned" data-ajax="false" data-role="none" data-id="assigned" class="assigned-cal btn btn-lg selectbutton btn-primary active" type="button" style="border-top-right-radius: 5px;border-bottom-right-radius: 5px;"><?php echo __('Assignments'); ?></button>
					<button id="logged" data-ajax="false" data-role="none" data-id="journal" type="button" class="journal-cal btn btn-lg btn-default" style="border-bottom-left-radius: 5px;border-top-left-radius: 5px;"><?php echo __('Journals'); ?></button>
				</div>
			</div>
			<div class="col-xs-0 aligncenter borderright"></div>
			<div class="col-xs-1 aligncenter pointers" onclick="addNewWorkoutOption();"><i class="fa fa-plus iconsize2"></i></div>
            <!--
			<div class="col-xs-3 aligncenter">
				<a style="display:none;" href="javascript:void(0);" data-role="none" data-ajax="false" data-toggle="modal" data-target="#searchmodel"><i class="fa fa-search iconsize2"></i></a>
			</div>
            -->
		</div>
		</div>
	</div>
	<?php $session = Session::instance(); ?>
	<div class="row">
		<?php $session = Session::instance();
			if ($session->get('success')): ?>
		<div class="banner success alert alert-success">
			<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $session->get_once('success'); ?>
		</div>
		<?php endif ?>
	</div>
	<div class="row">
		<div class="mobpadding">
			<div class="border calendarpage">
				<div class="btn-group">
					<div class="col-xs-3 aligncenter">
						<button type="button" data-role="none" data-ajax="false" class="btn btn-primary calerdarprev" data-calendar-nav="prev"><i class="fa fa-caret-left iconsize"></i></button>
					</div>
					<div class="col-xs-6 aligncenter">
						<button type="button"  data-role="none" data-ajax="false" class="btn btn-default calendardate" data-calendar-nav="today" onclick="resizeDiv();"><?php echo "<span class='calendartitle'></span>";	?></button>
					</div>
					<div class="col-xs-3 aligncenter">
						<button type="button"  data-role="none" data-ajax="false" class="btn btn-primary calerdarnext" data-calendar-nav="next"><i class="fa fa-caret-right iconsize"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- <br> -->
	<div class="row">
		<div class="col-md-12">
			<div id="calendar"></div>
		</div>
		
	</div>
	<br>
  </div>
  <div id="searchmodel" class="modal fade" role="dialog" tabindex="-1">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content aligncenter">
		  <div class="modal-header" style="border-bottom:0">
			<button data-role="none" type="button" class="close" onclick="$('#search-workplan').val('')" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?php echo __('Search Assigned Workout Plans'); ?></h4>
		  </div>
		  <div class="modal-body">
			<form action="" method="post">
				<div class="aligncenter col-xs-12 grid" style="width:100%;margin: 0 auto 0 auto">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="line-height:40px;">
						<?php echo __('Assigned date'); ?> :
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<input data-role="none" class="min-date form-control" value="<?php echo date('d M Y');?>" type="text" />
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<input data-role="none" class="max-date form-control" value="<?php echo date('d M Y');?>" type="text" />
					</div>
				</div>
				<div class="aligncenter col-xs-12" style="width:100%;margin: 0 auto 0 auto">
					<div class="col-xs-12">
						&nbsp;
					</div>
				</div>
				<div class="aligncenter" style="width:100%;margin: 0 auto 0 auto">
					<input data-role="none" type="text" id="search-workplan" name="q" onkeypress="getAssignedWorkoutsByajax();"  autocomplete="off" placeholder="search by title" class="form-control input-lg" value="">
				</div>
			</form>
		  </div>
		  <div class="modal-footer" style="border-top:0">
			<button data-role="none" type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#search-workplan').val('')"><?php echo __('Cancel'); ?></button>
		  </div>
		</div>

	  </div>
  </div>
  </form>
  <div id="myModalpreV" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="myModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="FolderModal" class="modal fade" role="dialog" tabindex="-1"></div>
  <div id="mypopupModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="myOptionsModalAjax" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="FolderModalpopup" class="modal fade" role="dialog" tabindex="-1"></div>
  <div id="FolderModalpopupNew" class="modal fade" role="dialog" tabindex="-1"></div>
  <div id="FolderModalpopupOption" class="modal fade" role="dialog" tabindex="-1"></div>
  <div id="myOptionsModalExerciseRecord" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
<?php 
	if(Session::instance()->get('todayReminder') != ''){
?>
	 <script>
		$(document).ready(function(){
			setTimeout(function(){
			<?php echo Session::instance()->get_once('todayReminder');?>
			}, 300);
		});
	 </script>
<?php	 
	}
?>
<script>
var optionsassign = {
	events_source: siteUrl+"ajax/wkoutassign/",
	view: 'month',
	tmpl_path: siteUrl+'assets/tmpls/',
	tmpl_cache: false,
	day: $("#getdate").val(),
	fromDate:$("#getdate").val(),
	toDate: $('#todaydate').val(),
	onAfterEventsLoad: function(events) {
		if(!events) {
			return;
		}
		var list = $('#eventlist');
		list.html('');

		$.each(events, function(key, val) {
			$(document.createElement('li'))
				.html('<a href="' + val.url + '">' + val.title + '</a>')
				.appendTo(list);
		});
	},
	onAfterViewLoad: function(view) {
		$('.calendardate .calendartitle').text(this.getTitle());
		$('.btn-group button').removeClass('active');
		$('button[data-calendar-view="' + view + '"]').addClass('active');
	},
	classes: {
		months: {
			general: 'label'
		}
	}
};
var optionsjournal = {
	events_source: siteUrl+"ajax/wkoutjournal/",
	view: 'month',
	tmpl_path: siteUrl+'assets/tmpls/',
	tmpl_cache: false,
	day: $("#getdate").val(),
	fromDate:$("#getdate").val(),
	toDate: $('#todaydate').val(),
	onAfterEventsLoad: function(events) {
		if(!events) {
			return;
		}
		var list = $('#eventlist');
		list.html('');

		$.each(events, function(key, val) {
			$(document.createElement('li'))
				.html('<a href="' + val.url + '">' + val.title + '</a>')
				.appendTo(list);
		});
	},
	onAfterViewLoad: function(view) {
		$('.calendardate .calendartitle').text(this.getTitle());
		$('.btn-group button').removeClass('active');
		$('button[data-calendar-view="' + view + '"]').addClass('active');
	},
	classes: {
		months: {
			general: 'label'
		}
	}
};
$('div.btn-toggle').click(function() {
    $(this).find('.btn').removeClass('selectbutton');
    if ($(this).find('.btn-primary').size()>0) {
    	$(this).find('.btn').toggleClass('btn-primary');
		$(this).find('.btn-primary').addClass('selectbutton');
    }
    $(this).find('.btn').toggleClass('btn-default');
	var actVar = '';
	if($(this).find('.btn-primary').attr('data-id') == 'journal'){
		var actVar = '?act=log';
	}
	window.location = siteUrl_Front+'exercise/myactioncalendar/'+$('#getdate').val()+actVar;
});
$( document ).ready( function() {
		var mpFrom = $( ".min-date" ).mobipick();
		var mpTo   = $( ".max-date" ).mobipick();
		mpFrom.on( "change", function() {
			mpTo.mobipick( "option", "minDate", mpFrom.mobipick( "option", "date" ) );
		});
		mpTo.on( "change", function() {
			mpFrom.mobipick( "option", "maxDate", mpTo.mobipick( "option", "date" ) );
		});
		<?php if(isset($flag) && $flag == 'log'){ ?>
			$('div.btn-toggle').find('.btn').removeClass('selectbutton');
			if ($('div.btn-toggle').find('.btn-primary').size()>0) {
				$('div.btn-toggle').find('.btn').toggleClass('btn-primary');
				$('div.btn-toggle').find('.btn-primary').addClass('selectbutton');
			}
			$('div.btn-toggle').find('.btn').toggleClass('btn-default');
			if($('div.btn-toggle').find('.btn-primary').attr('data-id') == 'assigned'){
				changeCalenderView(optionsassign);
			}else if($('div.btn-toggle').find('.btn-primary').attr('data-id') == 'journal'){
				changeCalenderView(optionsjournal);
			}
		<?php }else{ ?>
				changeCalenderView(optionsassign);
		<?php } ?>
});
</script>
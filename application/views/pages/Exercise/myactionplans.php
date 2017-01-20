<div id="wrap-index">
<!-- Login header nav !-->
<?php echo $topHeader;?>
	<div class="container" id="home">
		<?php $session = Session::instance(); ?>
		<div class="row">
			<?php if ($session->get('success')): ?>
				<div class="banner success alert alert-success">
					<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<?php echo $session->get_once('success'); ?>
				</div>
			<?php endif ?>
		</div>
		<form action="" method="post" data-ajax="false" id="myactionplans-form">
			<input data-role="none" type="hidden" name="getdate" id="getdate" value="<?php echo $getdate; ?>" />
			<input data-role="none" type="hidden" name="todaydate" id="todaydate" value="<?php echo date('Y-m-d'); ?>" />
			<div class="row">
				<div class="mobpadding">
					<div class="border full calendartoolbar">
						<div class="col-xs-3 aligncenter">
							<button type="button" data-role="none" data-ajax="false" class="btn btn-primary calerdarprev" data-calendar-nav="prev"><i class="fa fa-caret-left iconsize"></i></button>
						</div>
						<div class="col-xs-5 aligncenter">
							<button type="button" data-role="none" data-ajax="false" class="btn btn-default calendardate hide" data-calendar-nav="today" onclick="resizeDiv();"></button>
							<button type="button" data-role="none" data-ajax="false" class="btn btn-default calendargoto hide" data-calendar-nav="goto" data-calendar-goto="" onclick="resizeDiv();"></button>
							<button type="button" class="btn btn-default calendartitle activedatacol" onclick="dateSelectModal();" data-role="none" data-ajax="false"></button>
						</div>
						<div class="col-xs-3 aligncenter">
							<button type="button" data-role="none" data-ajax="false" class="btn btn-primary calerdarnext" data-calendar-nav="next"><i class="fa fa-caret-right iconsize"></i></button>
						</div>
						<div class="col-xs-0 aligncenter borderright"></div>
						<div class="col-xs-1 aligncenter pointers btn-add-event" onclick="addNewWorkoutOption();"><i class="fa fa-plus iconsize2"></i></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div id="calendar"></div>
				</div>
			</div>
			<br>
		</div>
		<input type="hidden" name="clickedId" value="" id="clickedId"/>
		<input type="hidden" name="typeName" value="" id="typeName"/>
		<input type="hidden" name="curFlag" value="" id="curFlag"/>
		<input type="hidden" name="attachId" value="" id="attachId"/>
		<input type="hidden" id="newlyAddedXr" name="newlyAddedXr" value="0"/>
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
var optionsall = {
	events_source: siteUrl+"ajax/wkoutall/",
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
		$('button.calendartitle').text(this.getTitle());
		$('.btn-group button').removeClass('active');
		$('button[data-calendar-view="' + view + '"]').addClass('active');
	},
	classes: {
		months: {
			general: 'label'
		}
	}
};

$( document ).ready( function() {
		var mpFrom = $( ".min-date" ).mobipick();
		var mpTo   = $( ".max-date" ).mobipick();
		mpFrom.on( "change", function() {
			mpTo.mobipick( "option", "minDate", mpFrom.mobipick( "option", "date" ) );
		});
		mpTo.on( "change", function() {
			mpFrom.mobipick( "option", "maxDate", mpTo.mobipick( "option", "date" ) );
		});
		changeCalenderView(optionsall);
});
</script>
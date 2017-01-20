<!--- Top nav && left nav--->
<?php echo $topnav.$leftnav;?>
<!--- Top nav && left nav --->
<!-- Content Wrapper. Contains page content -->
<div id="page-wrapper" class="mailboxright">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo __("Mailbox "); ?> <small><?php echo ($mail_cnt>0)?$mail_cnt." new messages":''; ?> </small></h1>
				<!--ol class="breadcrumb">
					<li><i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a></li>
					<li class="active"><i class="fa fa-edit"></i> Edit Workout Records</li>
				</ol-->
         </div>
		</div>
      <!-- /.row -->
		<?php $session = Session::instance();
					if ($session->get('flash_success')){ ?>
				   <div class="banner alert alert-success">
					<?php echo $session->get_once('flash_success') ?>
				  </div>
				<?php }
				?>
<?php /*********************************************** Edit Section **************************************************/ ?>
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-2">
          <a href="#" class="btn btn-primary btn-block margin-bottom">Compose</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <!--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>-->
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="<?php echo URL::base()."admin/mailbox" ?>"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right"><?php echo ($mail_cnt>0)?$mail_cnt:''; ?></span></a></li>
                <!--li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
                <li><a href="#"><i class="fa fa-file-text-o"></i> Drafts</a></li>
                <li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right"></span></a></li-->
                <li><a href="#"><i class="fa fa-trash-o"></i> Trash</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          <!--<div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Labels</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
              </ul>
            </div>
            
          </div>--><!-- /.box-body -->
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-10">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Inbox</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
						<?php // if(isset($mail) && is_array($mail) && count($mail)>0){ ?>
						<form method='get' name='search' id='search'>
							<input type="hidden" class="form-control input-sm" name='page' id='page' value='<?php echo (isset($_GET["page"]))?$_GET["page"]:''; ?>' >
                  <input type="hidden" class="form-control input-sm" name='sortby' id='sortby' value='<?php echo (isset($_GET["sortby"]))?$_GET["sortby"]:''; ?>' >
						<input type="text" value='<?php echo (isset($_GET["searchtxt"]))?$_GET["searchtxt"]:''; ?>' class="form-control input-sm" placeholder="Search Mail" name='searchtxt' id='searchtxt' >
						<input type="button" onclick="$('#search').submit();" id="searchmail" name="searchmail"><span class="glyphicon glyphicon-search form-control-feedback"></span></input>
						</form>
						<?php //} ?>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
				<?php
				
				if(isset($mail) && is_array($mail) && count($mail)>0){ ?>
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm" onclick='delete_all()' title='Delete'><i class="fa fa-trash-o"></i></button>
						<button type="button" class="btn btn-default btn-sm" onclick='unread_all()' title='Unread'><i class="fa fa-envelope-o"></i></button>
                  <!--button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button-->
                  <!--button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button-->
						<button type="button" class="btn btn-default btn-sm" onclick="window.location.href='<?php echo URL::base()."admin/mailbox"; ?>';" title='Refresh'><i class="fa fa-refresh"></i></button>
                </div>
					 
					 
					 
					 <div class="btn-group">
                  <button class="btn btn-default" type="button">Sort By</button>
                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><span class="caret"></span></button>
                  <ul role="menu" class="dropdown-menu">
						  <li><a href="javascript:void(0)" onclick="$('#sortby').val('');$('#search').submit();" >Most Recent</a></li>
                    <li><a href="javascript:void(0)" onclick="$('#sortby').val('name');$('#search').submit();">Name</a></li>
                    <li><a href="javascript:void(0)" onclick="$('#sortby').val('date');$('#search').submit();">Date</a></li>
						  <li><a href="javascript:void(0)" onclick="$('#sortby').val('site');$('#search').submit();">Site</a></li>                    
                  </ul>
                </div>
					 <div class="btn-group">
                  <button class="btn btn-default" type="button">More</button>
                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><span class="caret"></span></button>
                  <ul role="menu" class="dropdown-menu">
						  <li><a href="javascript:void(0)" onclick="$('#sortby').val('read');$('#search').submit();" >Read</a></li>
						  <li><a href="javascript:void(0)" onclick="$('#sortby').val('unread');$('#search').submit();" >Unread</a></li>
                  </ul>
                </div>
					 
					 
                <!-- /.btn-group -->
					 
                
							<div class="pull-right">
							  <?php echo "$offset - $lim / $tot"; ?>
							  <div class="btn-group">
								  
								 <!--a href='<?php echo URL::base()."admin/mailbox".(($prev!=0)?"?page=$prev":""); ?>' class="btn btn-default btn-sm" title='Previous'><i class="fa fa-chevron-left"></i></a>
								 <a href='<?php echo URL::base()."admin/mailbox".(($next!=0)?"?page=$next":""); ?>' class="btn btn-default btn-sm" title="Next"><i class="fa fa-chevron-right"></i></a-->
								 
								 
								 <a href='javascript:void(0);' onclick="$('#page').val('<?php echo ($prev!=0)?$prev:''; ?>');$('#search').submit();"  class="btn btn-default btn-sm" title='Previous'><i class="fa fa-chevron-left"></i></a>
								 <a href='javascript:void(0);' onclick="$('#page').val('<?php echo ($next!=0)?$next:''; ?>');$('#search').submit();" class="btn btn-default btn-sm" title="Next"><i class="fa fa-chevron-right"></i></a>
								 
								 
								 
								 <!--button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
								 <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button-->
								 
							  </div>
							  <!-- /.btn-group -->
							</div>
							<!-- /.pull-right -->
						 </div>
							<div class="table-responsive mailbox-messages">
							  <table class="table table-hover table-striped">
								 <tbody>
								 <?php
								 foreach($mail as $k=>$v){
									 //$v["message"] = (isset($v["subject"]))?$v["subject"]." ".$v["message"]:$v["message"];
									 $v["message"] = (strlen($v["message"])>50)?substr($v["message"],0,47):$v["message"];
									 $v["message"] = $v["message"]."...";
									 ?>
									 <tr <?php echo ($v["read_status"]==0)?"class='unread_mail info-icon'":"class='info-icon'"; ?> onclick='preview_mail("<?php echo $v['contact_id'];  ?>")' >
										<td><input type="checkbox" class='wkoutselect' value="<?php echo $v['smid'];  ?>"></td>
										<!--td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td-->
										<td class="mailbox-name"><?php echo $v["firstname"]." ".$v["lastname"]; ?></td>
										<td class="mailbox-subject">
										 <?php echo ucfirst($v["sitename"]); ?>
										</td>
										<td class="mailbox-subject">
										 <?php echo $v["message"]; ?>
										</td>
										<td class="mailbox-attachment"></td>
										<td class="mailbox-date"><?php echo Helper_Common::time_ago($v['dated']); ?></td>
									 </tr>
									 <?php
								 }
								 ?>
								 
								 </tbody>
							  </table>
							  <!-- /.table -->
							</div>
					  <!-- /.mail-box-messages -->
					</div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm" onclick='delete_all()'><i class="fa fa-trash-o"></i></button>
                  <!--button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button-->
                  <!--button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button-->
						<button type="button" class="btn btn-default btn-sm" onclick='unread_all()' title='Unread'><i class="fa fa-envelope-o"></i></button>
						<button type="button" class="btn btn-default btn-sm" onclick="window.location.href='<?php echo URL::base()."admin/mailbox"; ?>';" title='Refresh'><i class="fa fa-refresh"></i></button>
                </div>
                <!-- /.btn-group -->
                
                <div class="pull-right">
                  <?php echo "$offset - $lim / $tot"; ?>
                  <div class="btn-group">
                    <a href='<?php echo URL::base()."admin/mailbox".(($prev!=0)?"?page=$prev":""); ?>' class="btn btn-default btn-sm" title='Previous'><i class="fa fa-chevron-left"></i></a>
                    <a href='<?php echo URL::base()."admin/mailbox".(($next!=0)?"?page=$next":""); ?>' class="btn btn-default btn-sm" title="Next"><i class="fa fa-chevron-right"></i></a>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
				<?php }else{ ?>
				<div class="mailbox-read-message text-center">No items in your inbox</div>
				<?php }?>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
	
<?php /*********************************************** Edit Section **************************************************/ ?>			
	</div>
</div>
<link rel="stylesheet" href="http://versatile-25:82/assets/plugins/iCheck/flat/blue.css"></script>
<script>
  $(function () {
    //Enable iCheck plugin for checkboxes
    //iCheck for checkbox and radio inputs
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });

    //Handle starring for glyphicon and font awesome
    $(".mailbox-star").click(function (e) {
      e.preventDefault();
      //detect type
      var $this = $(this).find("a > i");
      var glyph = $this.hasClass("glyphicon");
      var fa = $this.hasClass("fa");

      //Switch states
      if (glyph) {
        $this.toggleClass("glyphicon-star");
        $this.toggleClass("glyphicon-star-empty");
      }

      if (fa) {
        $this.toggleClass("fa-star");
        $this.toggleClass("fa-star-o");
      }
    });
  });
</script>
<script src="http://versatile-25:82/assets/plugins/iCheck/icheck.min.js"></script>

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
              <h3 class="box-title"><?php echo __("Read Mail"); ?>
					</h3>

              <div class="box-tools pull-right">
					
                <a title="Previous" data-toggle="tooltip" class="btn btn-box-tool" href="<?php echo (isset($next))?URL::base()."admin/mailbox/preview/$next":"#"; ?>"><i class="fa fa-chevron-left"></i></a>
                <a title="Next" data-toggle="tooltip" class="btn btn-box-tool" href="<?php echo (isset($prev))?URL::base()."admin/mailbox/preview/$prev":"#"; ?>"><i class="fa fa-chevron-right"></i></a>
					 
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h3><?php echo $mail["firstname"]." ".$mail["lastname"]; ?></h3>
                <h5><?php echo __("From"); ?>: <?php echo $mail["email"]; ?>
                  <span class="mailbox-read-time pull-right">
						<?php
						echo date("d M. Y h:i A", strtotime($mail["dated"]));
						?>
						</span></h5>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-controls with-border text-center">
                <div class="btn-group">
						<button type="button" class="btn btn-default btn-sm" onclick='unread_one(<?php echo $mail["smid"]; ?>)' title='Unread'><i class="fa fa-envelope-o"></i>
					</button>
                  <button title="Delete" data-container="body" data-toggle="tooltip" class="btn btn-default btn-sm" type="button" onclick='delete_one(<?php echo $mail["smid"]; ?>)'>
                    <i class="fa fa-trash-o"></i></button>
						  
                  <button title="Reply" data-toggle="collapse" data-target="#reply" class="btn btn-default btn-sm btn-email-reply" type="button">
                    <i class="fa fa-reply" ></i></button>
                  <!--button title="Forward" data-container="body" data-toggle="tooltip" class="btn btn-default btn-sm" type="button">
                    <i class="fa fa-share"></i></button-->
                
                <!-- /.btn-group -->
                <button title="Print" data-toggle="tooltip" class="btn btn-default btn-sm" type="button">
                  <i class="fa fa-print"></i></button>
					 </div>
              </div>
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                <?php echo (isset($mail["mailcontent"]) && $mail["mailcontent"]!='')?$mail["mailcontent"]: $mail["message"]; ?>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
				<?php /*
            <div class="box-footer">
              <ul class="mailbox-attachments clearfix">
                <li>
                  <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a class="mailbox-attachment-name" href="#"><i class="fa fa-paperclip"></i> Sep2014-report.pdf</a>
                        <span class="mailbox-attachment-size">
                          1,245 KB
                          <a class="btn btn-default btn-xs pull-right" href="#"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
                <li>
                  <span class="mailbox-attachment-icon"><i class="fa fa-file-word-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a class="mailbox-attachment-name" href="#"><i class="fa fa-paperclip"></i> App Description.docx</a>
                        <span class="mailbox-attachment-size">
                          1,245 KB
                          <a class="btn btn-default btn-xs pull-right" href="#"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
                <li>
                  <span class="mailbox-attachment-icon has-img"><img alt="Attachment" src="http://localhost/admin/dist/img/photo1.png"></span>

                  <div class="mailbox-attachment-info">
                    <a class="mailbox-attachment-name" href="#"><i class="fa fa-camera"></i> photo1.png</a>
                        <span class="mailbox-attachment-size">
                          2.67 MB
                          <a class="btn btn-default btn-xs pull-right" href="#"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
                <li>
                  <span class="mailbox-attachment-icon has-img"><img alt="Attachment" src="http://localhost/admin/dist/img/photo2.png"></span>

                  <div class="mailbox-attachment-info">
                    <a class="mailbox-attachment-name" href="#"><i class="fa fa-camera"></i> photo2.png</a>
                        <span class="mailbox-attachment-size">
                          1.9 MB
                          <a class="btn btn-default btn-xs pull-right" href="#"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
              </ul>
            </div>
            */
            ?>
            <!-- /.box-footer -->
            <div class="box-footer  text-center">
              <button type="button" class="btn btn-default " onclick='unread_one(<?php echo $mail["smid"]; ?>)' title='Unread'><i class="fa fa-envelope-o"></i>
					<?php echo __("Unread"); ?></button>
				  <div  class="btn-group">
					
                <button class="btn btn-default btn-email-reply" type="button" data-toggle="collapse" data-target="#reply"><i class="fa fa-reply"></i> <?php echo __("Reply"); ?></button>
                <!--button class="btn btn-default" type="button"><i class="fa fa-share"></i> Forward</button-->
              </div>
				  
              <button class="btn btn-default" type="button" onclick='delete_one(<?php echo $mail["smid"]; ?>)'><i class="fa fa-trash-o"  ></i> <?php echo __("Delete"); ?></button>
              <button class="btn btn-default" type="button"><i class="fa fa-print"></i> Print</button>
            </div>
				
				
				<div class="collapse box-footer text-center" id='reply' >
					<form method='post'>
						<div class="form-group">
						   <textarea rows="3" class="form-control" style='resize:none' required="true"  name='message' id="reply-textarea"></textarea>
						 </div>  
						<button class="btn btn-primary" type="submit" ><i class="fa fa-envelope"></i> Send</button>
					</form>
            </div>
				
            <!-- /.box-footer -->
          </div>
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
  $('.mailboxright #reply').on('shown.bs.collapse', function () {
    $('#reply-textarea').trigger('touchstart'); //trigger touchstart
  });
  $('textarea#reply-textarea').on('touchstart', function() {
    $(this).focus();
  });
</script>
<script src="http://versatile-25:82/assets/plugins/iCheck/icheck.min.js"></script>

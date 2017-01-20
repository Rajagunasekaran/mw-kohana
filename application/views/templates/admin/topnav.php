<?php defined('SYSPATH') OR die('No direct access allowed.'); 
	
?>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <!--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>-->
                <a class="navbar-brand" href="<?php echo URL::base().'admin/dashboard'; ?>">My Workouts</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
					 
					 
<?php /****************************Mailbox Notification ****************************************/ ?>
<li class="dropdown" id='mailbox_notify'>
	 <a <?php //if($notify_cnt>0){ ?> href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="update_notification_status();" <?php // } else{ ?>
		 <?php //} ?>
		 ><i class="fa fa-envelope"></i>
		  <b class="caret"></b>
		  <?php if($notify_cnt>0){ ?>								
		  <span class="label label-success" id='mailbox_notify_unread' ><?php echo $notify_cnt; ?></span>
		  <?php } ?>
	 </a>
	 <ul class="dropdown-menu message-dropdown" id='notify_mailbox'>
		  
	 </ul>
	 <input type='hidden' value='0' id='notifylim'>
</li>
<?php /****************************Mailbox Notification ****************************************/ ?>
				
					 <!--
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu alert-dropdown">
                        <li>
                            <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">View All</a>
                        </li>
                    </ul>
                </li> -->
                <?php if(is_array($user_sites) && count($user_sites)>0){?>
                <li class="dropdown">
                	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-sitemap"></i> <?php echo $current_site_name;?> <b class="caret"></b></a>
                     <ul class="dropdown-menu">
                     	<?php foreach($user_sites as $site){?>
                        <li><a href="<?php echo URL::base().'admin/index/switchsite/'.$site['site_id'];?>"><?php echo $site['name'];?></a></li>
						<?php } ?>
                     </ul>
                </li>
                <?php } ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $user->user_fname.' '.$user->user_lname; ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <!-- <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li> -->
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo URL::base().'admin/index/logout';?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            
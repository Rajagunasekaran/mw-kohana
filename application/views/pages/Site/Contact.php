<?php echo $header;?> 
<?php 
	$firstName = $lastName = $email = $phone = '';
	if(Auth::instance()->logged_in()){
		$user 	= Auth::instance()->get_user();
		$firstName 	= ucfirst($user->user_fname);
		$lastName 	= ucfirst($user->user_lname);
		$email		= $user->user_email;
		$phone 		= $user->user_mobile;
	}
?>
<div class="main-wrapper after-nav">
<div class="container dynamicpage">
	<div class="content pagecontent">
    	<h1 class="dynamic-page-title"><?php
		echo __("Contact Us");
		?></h1>
		<div class="page_content">
		 <?php $session = Session::instance();
					if ($session->get('flash_success')){ ?>
				   <div class="banner alert alert-success">
					<?php echo $session->get_once('flash_success') ?>
				  </div>
				<?php }
				if ($session->get('flash_error')){ ?>
				   <div class="banner alert alert-error">
					<?php echo $session->get_once('flash_error') ?>
				  </div>
				<?php }
				?>
				
<?php
$r[] = array("firstname"	=> "Mani", "lastname"=> "A", "email"=> "manikandanA@versatile-soft.com", "phone"=>time(), "message" => time()."--Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged." );
$r[] = array("firstname"	=> "Mani", "lastname"=> "DH", "email"=> "manikandan@versatile-soft.com", "phone"=>time(), "message" => time()."--Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged." );
$r[] = array("firstname"	=> "Mani", "lastname"=> "M", "email"=> "manikandanm@versatile-soft.com", "phone"=>time(), "message" => time()."--Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged." );
$r[] = array("firstname"	=> "Prabakaran", "lastname"=> "R", "email"=> "prabakaran@versatile-soft.com", "phone"=>time(), "message" => time()."--Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged." );
$r[] = array("firstname"	=> "Raja", "lastname"=> "R", "email"=> "raja@versatile-soft.com", "phone"=>time(), "message" => time()."--Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged." );
$r[] = array("firstname"	=> "Latchaprabhu", "lastname"=> "Prabhu", "email"=> "prabhu@versatile-soft.com", "phone"=>time(), "message" => time()."--Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged." );
$r[] = array("firstname"	=> "Gopi", "lastname"=> "Krishnan", "email"=> "gopi@versatile-soft.com", "phone"=>time(), "message" => time()."--Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged." );
$rand_keys = array_rand($r,1);
$s = $r[$rand_keys];
			$firstName = $lastName = $email = $phone = '';
			if(Auth::instance()->logged_in()){
				$user 	= Auth::instance()->get_user();
				$firstName 	= ucfirst($user->user_fname);
				$lastName 	= ucfirst($user->user_lname);
				$email		= $user->user_email;
				$phone 		= $user->user_mobile;
			}
?>
			<div class='row col-lg-6 '>
				<form method='post'>
				<div class="form-group">
					<label><?php echo __("First Name"); ?>*</label>
					<input data-role="none" data-ajax="false" class="form-control" required="true" type='text' name='firstname' value="<?php echo $firstName; ?>">
				</div>
				
				<div class="form-group">
					<label><?php echo __("Last Name"); ?></label>
					<input data-role="none" data-ajax="false" class="form-control" name='lastname' value="<?php echo $lastName; ?>">
				</div>
				
				<div class="form-group">
					<label><?php echo __("Email"); ?>*</label>
					<input data-role="none" data-ajax="false" class="form-control" type='email' required="true"  name='email' value="<?php echo $email; ?>">
				</div>
				
				<div class="form-group">
					<label><?php echo __("Phone");?></label>
					<input data-role="none" data-ajax="false" class="form-control" name='phone' value="<?php echo $phone; ?>">
				</div>
				
				<div class="form-group">
					<label><?php echo __("Message"); ?>*</label>
					<textarea data-role="none" data-ajax="false" class="form-control" rows="3" required="true" name='message'></textarea>
				</div>
				<div class="form-group">
					<button data-role="none" data-ajax="false" class="btn btn-primary" type="submit">Contact Us</button>
				</div>
				</form>
			</div>
		</div>
    </div>
</div>
<?php echo $footer;?>
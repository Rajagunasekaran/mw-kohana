<?php
//print_r($listsliders); exit;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <!--  SEO STUFF START HERE -->
        <title><?php echo "My workouts - $sitetitle"?></title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="robots" content="follow, index" />
        <!--  SEO STUFF END -->
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		  <?php echo HTML::style("assets/media/css/bootstrap.css"); ?>
		  <?php echo HTML::style("assets/media/css/custom.css"); ?>
		  <?php echo HTML::style("assets/media/css/css3-menu.css"); ?>
		  <?php echo HTML::style("assets/media/css/isotope.css"); ?>
		  <?php echo HTML::style("assets/media/css/color_scheme.css"); ?>
		  <?php //echo HTML::style("assets/media/css/color_scheme.css"); ?>
		  <?php echo HTML::style("assets/media/css/flickerplate.css"); ?>
		  <?php echo HTML::style("assets/media/css/font-awesome.css"); ?>		
		  <?php echo HTML::style("assets/media/css/flexslider.css"); ?>
		  <?php echo HTML::style("assets/media/css/slick.css"); ?>
		  <?php echo HTML::style("assets/media/css/jquery.fancybox.css"); ?>
		  <?php echo HTML::style("assets/media/css/component.css"); ?>
		  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"> -->

		  <?php 
				//$session = Session::instance();    
				if(!empty($slug_id)&& ($slug_id!='')){	
					 $siteurl=URL::site().'site/'.$slug_id.'/';
				}else{
					 $siteurl=URL::site();
				}
		  ?>
		  <script>
		  var site_url='<?php echo $siteurl;?>';
		  
		  </script>
		  <style><?php echo (isset($sitecontent[0]['advanced_css']) ? $sitecontent[0]['advanced_css'] : '');?></style>
    </head>
	 <body>
        <!-- HEADER AREA -->
        <header class="navbar-fixed-top header_section" >
            <div class="row">
					 <!-- HEADER: LOGO AREA -->
					 <div class="span4 logo" id="logo">
						  <a class="logo" href="<?php echo $siteurl;?>">
								<?php if( !empty($sitecontent[0]['site_logo'])):?>
								<img alt="logo" title="ReDesigns" src="<?php echo URL::site('assets/uploads/logo/'.$sitecontent[0]['site_logo']);?>">
								<?php else:?>
								<img alt="logo" title="ReDesign" src="<?php echo URL::site('assets/img/moblogo.png');?>">
								<?php endif;?>
						  </a>
					 </div>
					 <div class="span5 top-nav">
						  <div class="navbar-wrapper" >
								<div class="">
									 <div class="navbar" id="navbar">
										  <div class="navbar-inner">
												<div class="buttons-container"></div>
												<ul class="blue nav" id="css3-menu" style='background: #009DE0;'>
													 <?php /*if($userid): ?>
													 <li><a href="<?php echo $siteurl.'login/logout';?>" class="openform">Logout</a></li>
													 <li><a href="<?php echo $siteurl.'account/';?>">My Account</a></li>
													 <?php else:*/?>
													 <!--li><a href="#"><i class='icon-align-justify'></i></a></li> -->
													 
													 <li><a href="<?php echo $siteurl.'register/';?>"><?php echo __('Register'); ?></a></li>
													 <li><a href="#login-div" class="openform"><?php echo __('Login'); ?></a></li> 
													 <?php
													 /*endif;*/
													 ?>
													 <!--li><a href="<?php //echo $siteurl.'contact/';?>">Contact Us</a></li>   -->                         
												</ul>
										  </div>
									 </div>
								</div>
						  </div>
						  <div id="dl-menu" class="dl-menuwrapper">
							<button><?php echo __('Open Menu'); ?></button>
							<ul class="dl-menu">
								<li> <a href="<?php echo $siteurl.'register/';?>"><?php echo __('Register'); ?></a>
								<li> <a href="javascript:void(0);" onclick="openLoginForm();"><?php echo __('Login'); ?></a>
							</ul>
						  </div>
					 </div>
				</div>
				<?php
				//exit;
				?>
				<a id="navloginopen" class="openform" href="#login-div" style="display:none;">&nbsp;</a>
				<div id="login-div" class="form" style="display:none;">
					 <div class="container">
						  <h1><?php echo __('Login'); ?></h1>
						  <div class="registerform"><div class="loginmessage"></div>
								<form action="" method="post" id="login-form">
									 <div id="form-content">
										  <fieldset>
												<div class="fieldgroup"><label for="email"><?php echo __('Email'); ?></label><input type="email" required="" name="email" id="email" ></div>
												<div class="fieldgroup"><label for="password"><?php echo __('Password'); ?></label><input type="password" required="" name="password" id="password" ></div>
												<div class="fieldgroup registerfield"><input type="submit" value="Login" class="submit"></div>
										  </fieldset>
									 </div>
								</form>
						  </div>
					 </div>
				</div>
        </header>
		  
		  <?php
		  
		  //echo $listsliders;
		  
		  
		  
		  ?>
		  <!-- MAIN CONTENT AREA: SLIDER BANNER (REVOLUTION SLIDER) -->
    <div class="flicker-example flickerplate animate-transform-slide flicker-theme-light slider_section" data-flick-position="1">
		  <!--div class="dot-navigation center">
				<ul>
					 <li><div class="dot active"></div></li>
					 <li><div class="dot"></div></li>
					 <li><div class="dot"></div></li>
				</ul>
        </div-->
        <div class="arrow-navigation left"></div>
        <div class="arrow-navigation right"></div>
		  <ul class="flicks" style="-webkit-transform:translate3d(-0%, 0, 0);-o-transform:translate3d(-0%, 0, 0);-moz-transform:translate3d(-0%, 0, 0);transform:translate3d(-0%, 0, 0)">
				<?php
				if($listsliders){
					 foreach ($listsliders as $list){
						  ?>
						  <li data-background="<?php echo URL::site('assets/uploads/manage/homepage/slider/'.$list['s_image']);?>">
								<div class="flick-content-box" style="border:<?php echo $list['content_border'];?>;background:<?php echo $list['content_bgcolor'];?>">
									 <div class="flick-title" style="color:#<?php echo $list['tile_color'];?> ">
										  <span class="flick-block-text" style="text-shadow:<?php echo $list['text_shadow'];?> "><?php echo $list['s_title'];?></span>
									 </div>
									 <div class="flick-sub-text" style="color:#<?php echo $list['content_color'];?> "><?php echo $list['s_content'];?></div>
								</div>
						  </li>
						  <?php
					 }
				}else{
					 for($ix=1;$ix<=3;$ix++){
						  ?>
						  <li data-background="<?php echo URL::site('assets/img/slide/slide'.$ix.'.png');?>" style='border:none;'>
								<div class="flick-inner" style='display:none;'>
									 <div style="border:;background:rgba(0,0,0,0.6)" class="flick-content">
										  <div style="color:#0084BC " class="flick-title">
												<span   class="flick-block-text" style="text-shadow:1px 2px #000000 "><?php echo __('SLIDER IMAGE 1 TITLE'); ?></span><!--/span-->
										  </div>
										  <div style="color:#0084BC " class="flick-sub-text">
												<span class="flick-block-text">
													 <?php echo __("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard"); ?> 
												</span>
										  </div>
									 </div>
								</div>
						  </li>
						  <?php
					 }
				}	?>
		  </ul>
	 </div>
	 <!-- MAIN CONTENT AREA: SLIDER BANNER (REVOLUTION SLIDER) -->
	 
	 <!-- MAIN CONTENT AREA -->
	 <?php
	 //echo $content;
	 ?>
		
		
		
		
		
		
		
		
		
		
		
		

<div class="main-wrapper">
         <div class="main-content">
 <div class="container">
                

                <div class="row main-block block_section">
                    <div class="span12">
					<?php if(isset($homecontent[0]['description']))
					{
						
					?>
                        <div class="page-content"><?php echo (isset($homecontent[0]['description']) ? $homecontent[0]['description'] : '');?></div>
						
						<?php }else{
						  $desctemp ="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
						  echo "<div class=\"page-content\">".__($desctemp)."</div>";
						}
						
						?>
						<!-- MAIN CONTENT AREA: REDESIGN CUSTOM - HERO LIST -->
                        <div class="row show-grid hero-list features-list">
						
						
						<?php 
				if(isset($blockcontent) && count($blockcontent)>0){
					 foreach ($blockcontent as $list)
					 {	?>
										  <div class="span3">
												<h2><?php echo $list['b_title'];?></h2>
									 <div class="image-wrapper">
													 <img alt="" src="<?php echo URL::site('assets/uploads/manage/homepage/block/'.$list['b_image']);?>" />
												</div>
												
												<p><?php echo $list['b_description'];?></p>
										  </div>
										  
										 <?php
					 }
				} else{
					 $block = array('',"EXCERCISES","WORKOUTS","INTEGRATED","EXPERTS");
					 $icon = array('',"fa-h-square","fa-paste","fa-chain","fa-users");
					 for($bl=1;$bl<=4;$bl++){
						  ?>
						  <div class="span3">
												<h2><?php echo __($block[$bl]); ?></h2>
									 <div class="image-wrapper">
													 <!--img alt="" src="<?php echo URL::site('assets/img/block/block'.$bl.'.png');?>" /-->
													 <i class="fa <?php echo $icon[$bl]; ?> fa-5x"></i>
												</div>
												
												<p><?php echo __("Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type"); ?>.</p>
										  </div>
						  <?php
					 }
				}?> 
                            
                        </div>
                    </div>
                </div>
                
                

            </div>
            
            <div class="section ratingbar row-fluid statistic_section">
            	<div class="container">
                	<div class="row-fluid span4"><div class="rate"><?php echo $tot_users ;?></div><div class="bar"></div><div class="ratedes"><?php echo __('Users'); ?></div></div>
                    <div class="row-fluid span4"><div class="rate"><?php echo $tot_exercisesets ;?></div><div class="bar"></div><div class="ratedes"><?php echo __('Exercises'); ?></div></div>
                    <div class="row-fluid span4"><div class="rate"><?php echo $tot_trainers ;?></div><div class="bar"></div><div class="ratedes"><?php echo __('Trainers'); ?></div></div>
                </div>
            </div>
            
			
			<div class="section row-fluid youtubechallenge">
            	<div class="container">
                	<div class="row-fluid span6 video_section">
						<h2><?php echo __('What We Say'); ?>...</h2>
						<div class="youtubevideo">
							 
							 <?php if(!empty($homecontent[0]['video'])):?>
							 <iframe src="<?php echo (isset($homecontent[0]['video']) ? $homecontent[0]['video'] : '');?>" width="380" height="180" frameborder="0" allowfullscreen></iframe>
							 <?php else:	 ?>
							 <iframe src="https://www.youtube.com/embed/18vJ1uhK3PU" width="380" height="180" frameborder="0" allowfullscreen></iframe>
							 <?php endif; ?>
						</div>
					</div>
                    <div class="row-fluid span6 tesimonial_section">
						<h2><?php echo __('What Our Challengers Say'); ?>...</h2>
						<div class="ourchallengers">
							<div class="testimonial">
							
							<?php
							
							if(isset($tesimonials) && count($tesimonials)>0){
								
							foreach($tesimonials as $testimonial) {?>
							
								<div class="testimodes1">
									<div class="testimotext"><?php echo $testimonial['t_description'];?></div>
									<div class="testarrow"></div>
									<div class="testimoauthor"><?php echo $testimonial['t_user'];?></div>
								</div>
								
								
								
								<?php }
								
								}else{
									 for($te=1; $te<=10; $te++){
										?>
										<div class="testimodes1">
										  <div class="testimotext"><?php echo __("Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type"); ?></div>
										  <div class="testarrow"></div>
										  <div class="testimoauthor"><?php echo __('Demo User'); ?> <?php echo $te; ?></div>
									  </div>
										<?php
									 }
								}?>
								
							</div>
						</div>
					</div>
                </div>
            </div>
            
			<style>
			.main-wrapper { margin-top: 50px; }
			</style>
		
		
		
			<!-- </div>
			</div> -->
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	 <div class="section row-fluid partnerlogo partners_section" >
		  <div class="container">
				<h2><?php echo __('Our Partners'); ?></h2>
				<div class="row-fluid span12 partner">
					 <?php
					 if(isset($listpartners) && count($listpartners)>0){
						  foreach ($listpartners as $list){?>
						  <div class="row-fluid partnerslogo"><img src="<?php echo URL::site('assets/uploads/manage/homepage/partner/'.$list['p_image']);?>" alt="partnerlogo" ></div>
						  <?php
						  }
					 }else{
						  for($re=1; $re<=5; $re++){
						  echo "<div class=\"row-fluid partnerslogo\">
									 <img src='".URL::site('assets/img/partner/logo.png')."' alt=\"partnerlogo\" ></div>";
						  }
					 }?>
				</div>
				
				
				
		  </div>
		  
	 </div>
	 <?php  $userid=Auth::instance()->get_user();
                ?>
                <?php if(!$userid): ?>

                <!-- FREE TRIAL PROMO BOX -->
               <!-- <div class="hero-unit dark-hero reg_section">
                    <a class="btn btn-large btn-primary" href="<?php //echo URL::base();?>">Join Now</a>
                </div> -->
				<div class="section row-fluid signup">
					<div class="container">
						<!-- FREE TRIAL PROMO BOX -->
						<div class="hero-unit dark-hero reg_section">
							<h1><span><?php echo __('Join with Us'); ?></h1>
							<a class="btn btn-large btn-primary" href="<?php echo URL::base();?>"><?php echo __('Join Now'); ?></a>
						</div>
					</div>
				</div>
             <?php endif;?>
	 </div>
	 
	 
	 <!-- FOOTER STARTS HERE -->
    <footer id="footer" class='footer_section'>
		  <div class="footer-wrapper">
            <div class="container">
					 <div class="row show-grid">
						  <div class="span12">
								<div class="row show-grid">
									 <div class="span3 footer-left">
										  <ul>
												<li><a href="#"><?php echo __('Help'); ?></a></li>
												<li><a href="#"><?php echo __('FAQs'); ?></a></li>
												<li><a href="<?php echo $siteurl.'contact/';?>"><?php echo __('Contact Us'); ?></a></li>
												<li><a href="#"><?php echo __('Site Map'); ?></a></li>
										  </ul>
                            </div>
                            <div class="span2 footer-center">
										  <ul>
												<li><a href="#"><?php echo __('PRIVACY'); ?></a></li>
										  </ul>
                            </div>
									 <div class="span3 footer-center">
										  <ul>
												<li><a href="#"><?php echo __('T&Cs'); ?></a></li>
										  </ul>
                            </div>
									 <!-- FOOTER: NAVIGATION LINKS -->
                            <div class="span4 footer-right">
										  <h4 class="center-title"><?php echo __('Connect With Us'); ?></h4>
										  <ul class="social-links">
												<li>
													 <a href="<?php echo (isset($sitecontent[0]['social_facebook_url']) ? $sitecontent[0]['social_facebook_url'] : '');?>"><i class="icon-facebook"></i></a>
												</li>
												<li>
													 <a href="<?php echo (isset($sitecontent['social_twitter_url']) ? $sitecontent[0]['social_twitter_url'] : '');?>"><i class="icon-instagram"></i></a>
												</li>
												<li>
													 <a href="<?php echo (isset($sitecontent['footer_content']) ? $sitecontent[0]['footer_content'] : '');?>"><i class="icon-pinterest"></i></a>
												</li>
										  </ul>
										  <div class="news_message"></div>
										  <!--form class="subscribe-form" id="subscribe" action="" method="post"-->
												<input type="email" id="subemail" name="subemail" required placeholder="email" value="" />
												<input type="button" name="subscribe"  value="subscribe" onclick='site_subscriber()' />
										  <!--/form-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
				<div class="container">
					 <div class="row show-grid">
						  <!-- FOOTER: COPYRIGHT TEXT -->
                    <div class="span12">
								<p><?php
								
								$demo = "Results may vary. Exercise and healthy diet are necessary to achieve and maintain weight loss. Please consult your healthcare professional before starting our program.";
								echo (isset($sitecontent[0]['footer_content']) ? $sitecontent[0]['footer_content'] : __($demo));?></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
	</div>
    <!-- END FOOTER -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php echo HTML::script('assets/media/js/jquery.min.js'); ?>
	 <?php //echo HTML::script('assets/media/js/slick.js'); ?>
	 <?php echo HTML::script('assets/media/js/classie.js'); ?>
	 <?php echo HTML::script('assets/media/js/slick.min.js'); ?>
	 <?php echo HTML::script('assets/media/js/bootstrap.js'); ?>
	 <?php echo HTML::script('assets/media/js/jquery.flexslider-min.js'); ?>
	 <?php echo HTML::script('assets/media/js/jquery.isotope.js'); ?>
	 <?php echo HTML::script('assets/media/js/jquery.imagesloaded.min.js'); ?>
	 <?php echo HTML::script('assets/media/js/jquery.fancybox.pack.js?v=2.1.0'); ?>
	 <?php echo HTML::script('assets/media/rs-plugin/js/jquery.themepunch.plugins.min.js'); ?>
	 <?php echo HTML::script('assets/media/rs-plugin/js/jquery.themepunch.revolution.min.js'); ?>
	 <?php echo HTML::script('assets/media/js/revolution.custom.js'); ?>
	 <?php echo HTML::script('assets/media/js/jquery.validate.min.js'); ?>
	 <?php echo HTML::script('assets/media/js/modernizr.custom.js'); ?>
	 <?php echo HTML::script('assets/media/js/jquery.dlmenu.js'); ?>
	 <?php echo HTML::script('assets/media/js/custom.js'); ?>
	 <?php echo HTML::script('assets/media/js/min/modernizr-custom-v2.7.1.min.js'); ?>
	 <?php echo HTML::script('assets/media/js/min/hammer-v2.0.3.min.js'); ?>
	 <?php echo HTML::script('assets/media/js/min/flickerplate.min.js'); ?>
	 
	 <script type="text/javascript">
    /*function init() {
        window.addEventListener('scroll', function(e){
            var distanceY = window.pageYOffset || document.documentElement.scrollTop,
                shrinkOn = 300,
                header = document.querySelector("header");
            if (distanceY > shrinkOn) {
                classie.add(header,"smaller");
            } else {
                if (classie.has(header,"smaller")) {
                    classie.remove(header,"smaller");
                }
            }
        });
    }
    window.onload = init();
     initgraph();*/
	 
	 
function site_subscriber(){
	 var em = jQuery("#subemail").val();
	 if (em) {
		  jQuery.ajax({
				url: "<?php echo URL::site(); ?>ajax/subscribe",
				method: 'post',
				data: {	email: em,siteid:'<?php echo $siteid; ?>'	},
				success: function(content) {
					jQuery(".news_message").html("<span style='color:green'><?php echo __('Subscribed successfully'); ?>...!</apan>");
					jQuery("#subemail").val('');
				}
			});
	 }else{
		  jQuery(".news_message").html("Please enter your email to subscribe...!");
	 }
	 
}
	 </script>
    </body>   
</html>
<?php //echo HTML::style("media/js/classie.js"); ?>
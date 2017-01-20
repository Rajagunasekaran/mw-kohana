<?php
echo $header;
$bg_class = 'bg-class';
$font_class = 'font-class';

?>
	 <!-- MAIN CONTENT AREA: SLIDER BANNER (REVOLUTION SLIDER) -->
     <div class="flicker-example flickerplate animate-transform-slide flicker-theme-light slider_section after-nav" data-flick-position="1"> 
        <div class="arrow-navigation left"></div>
        <div class="arrow-navigation right"></div>
		  <ul class="flicks">
				<?php
				$imgArray = array();
				if($listsliders){
					 foreach ($listsliders as $list){
						  $imgArray[] = URL::site('assets/uploads/manage/homepage/slider/'.$list['s_image']);
						  ?>
						  <li class="slider-img" data-background="<?php echo URL::site('assets/uploads/manage/homepage/slider/'.$list['s_image']);?>">
								<div class="flick-content-box " style="border:<?php echo $list['content_border'];?>;background:<?php echo $list['content_bgcolor'];?>">
									 <div class="flick-title" style="color:#<?php echo $list['tile_color'];?> ">
										  <span style="text-shadow:<?php echo $list['text_shadow'];?> "><?php echo __($list['s_title']);?></span>
									 </div>
									 <div class="flick-sub-text" style="color:#<?php echo $list['content_color'];?> "><?php echo __($list['s_content']);?></div>
								</div>
						  </li>
						  <?php
					 }
				}else{
					 for($ix=1;$ix<=3;$ix++){
						  $imgArray[] = URL::site('assets/img/slide/slide'.$ix.'.png');
						  ?>
						  <li class="slider-img" data-background="<?php echo URL::site('assets/img/slide/slide'.$ix.'.png');?>" style='border:none;'>
							  <div class="flick-title">
									<span><?php echo __('SLIDER IMAGE 1 TITLE'); ?></span>
							  </div>
							  <div  class="flick-sub-text">
									<span>
										 <?php echo __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard'); ?> 
									</span>
							  </div>
						  </li>
						  <?php
					 }
				}	?>
		  </ul>
	 </div> 
	 <?php if(isset($imgArray) && count($imgArray)>0) { ?>
		<div style="visibility:hidden;height:0;" >
			<?php foreach($imgArray as $key => $value) { ?>
				<img class="sample-img" style="display:none;width:100%" src="<?php echo $value;?>" />
			<?php } ?>
		</div>
	 <?php } ?>
	  
	 <!-- MAIN CONTENT AREA: SLIDER BANNER (REVOLUTION SLIDER) -->
	 <!-- MAIN CONTENT AREA -->
		
<div class="main-wrapper">
         <div class="main-content">
 <div class="container">
                
                <div class="row main-block block_section">
                    <div class="span12 col-lg-12">
					<?php if(isset($homecontent[0]['description']))
					{
						
					?>
                        <div class="page-content <?php echo $font_class;?>"><?php echo (isset($homecontent[0]['description']) ? __($homecontent[0]['description']) : ''); ?></div>
						
						<?php }else{
						  $desctemp ="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
						  echo "<div class=\"page-content\">".__($desctemp)."</div>";
						}
						
						?>
						  
						  
				<?php  	$userid=Auth::instance()->get_user();
						if(!$userid){
				?>

                <!-- FREE TRIAL PROMO BOX -->
               <!-- <div class="hero-unit dark-hero reg_section">
                    <a class="btn btn-large btn-primary" href="<?php //echo URL::base();?>">Join Now</a>
                </div> -->
				<div class="section signup">
					<div class="container">
						<!-- FREE TRIAL PROMO BOX -->
						<div class="hero-unit dark-hero reg_section">
							<h1><span><?php echo __('Get Started'); ?></h1>
							<a class="btn btn-large btn-primary <?php echo $bg_class.' '.$font_class;?>" href="javascript:void(0);" data-toggle="modal" data-target="#joinModal"><?php echo __('Register Here'); ?></a>
						</div>
					</div>
				</div>
             <?php } ?>
						<!-- MAIN CONTENT AREA: REDESIGN CUSTOM - HERO LIST -->
                        <div class="row show-grid hero-list features-list">
						
						<?php 
				if(isset($blockcontent) && count($blockcontent)>0){
					 foreach ($blockcontent as $key => $list)
					 {	
						if($key!=0 && $key%4==0) { ?>
							</div>
							<div class="row show-grid hero-list features-list">
						<?php } ?>
										  <div class="span3 col-sm-3">
												<h2 class="grid-title"><?php echo __($list['b_title']);?></h2>
									 <div class="image-wrapper">
													 <img alt="" src="<?php echo URL::site('assets/uploads/manage/homepage/block/'.$list['b_image']);?>" />
												</div>
												
												<div class="grid-desc <?php echo $font_class;?>"><?php echo __($list['b_description']);?></div>
										  </div>
										  
										 <?php
					 }
				} else{
					 $block = array('',"EXCERCISES","WORKOUTS","INTEGRATED","EXPERTS");
					 $icon = array('',"fa-h-square","fa-paste","fa-chain","fa-users");
					 for($bl=1;$bl<=4;$bl++){
						  ?>
						  <div class="span3 col-sm-3">
												<h2><?php echo __($block[$bl]);?></h2>
									 <div class="image-wrapper">
													 <!--img alt="" src="<?php echo URL::site('assets/img/block/block'.$bl.'.png');?>" /-->
													 <i class="fa <?php echo $icon[$bl]; ?> fa-5x"></i>
												</div>
												
												<p class="<?php echo $font_class;?>"><?php echo __("Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type"); ?>.</p>
										  </div>
						  <?php
					 }
				}?> 
                            
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="section ratingbar row-fluid statistic_section <?php echo $bg_class;?>">
            	<div class="container">
                	<div class="row-fluid span4 col-lg-4"><div class="rate <?php echo $font_class;?>"><?php echo $tot_users ;?></div><div class="bar"></div><div class="ratedes <?php echo $font_class;?>"><?php echo __('Users'); ?></div></div>
                    <div class="row-fluid span4 col-lg-4"><div class="rate <?php echo $font_class;?>"><?php echo $tot_exercisesets ;?></div><div class="bar"></div><div class="ratedes <?php echo $font_class;?>"><?php echo __('Exercises'); ?></div></div>
                    <div class="row-fluid span4 col-lg-4"><div class="rate <?php echo $font_class;?>"><?php echo $tot_trainers ;?></div><div class="bar"></div><div class="ratedes <?php echo $font_class;?>"><?php echo __('Trainers'); ?></div></div>
                </div>
            </div>
            
			<div class="section row-fluid youtubechallenge">
            	<div class="container">
            		<?php 
            		if(isset($settinghome_url[0]['video_status']) && $settinghome_url[0]['video_status'] == 1){ ?>
                	<div class="row-fluid span6 col-lg-6 video_section">
						<h2 class="video-sec-title"><?php echo __('What We Say'); ?>...</h2>
						<div class="youtubevideo">
							 
							 <?php if(!empty($settinghome_url[0]['video'])):?>
							 <iframe data-role="none" data-ajax="false" src="<?php echo (isset($settinghome_url[0]['video']) ? $settinghome_url[0]['video'] : '');?>" width="380" height="180" frameborder="0" allowfullscreen></iframe>
							 <?php else:	 ?>
							 <iframe data-role="none" data-ajax="false" src="https://www.youtube.com/embed/18vJ1uhK3PU" width="380" height="180" frameborder="0" allowfullscreen></iframe>
							 <?php endif; ?>
						</div>
					</div>
					<?php } ?>
                    <div class="row-fluid <?php if(!isset($settinghome_url[0]['video_status']) || $settinghome_url[0]['video_status'] != 1){ echo "span12 col-lg-12 text-center"; }else{ echo "span6 col-lg-6"; } ?> tesimonial_section">
						<h2 class="test-sec-title"><?php echo __('What Our Subscribers Say'); ?>...</h2>
						<div class="ourchallengers">
							<div class="testimonial">
							
							<?php
							
							if(isset($tesimonials) && count($tesimonials)>0){
								
							foreach($tesimonials as $testimonial) {?>
							
								<div class="testimodes1">
									<div class="testimotext <?php echo $font_class;?>"><?php echo __($testimonial['t_description']);?></div>
									<div class="testarrow"></div>
									<div class="testimoauthor <?php echo $font_class;?>"><?php echo __($testimonial['t_user']);?></div>
								</div>
								<?php }
								}else{
									 for($te=1; $te<=10; $te++){
										?>
										<div class="testimodes1">
										  <div class="testimotext <?php echo $font_class;?>"><?php echo __("Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type"); ?></div>
										  <div class="testarrow"></div>
										  <div class="testimoauthor <?php echo $font_class;?>"><?php echo __('Demo User').' '. $te; ?></div>
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
				<h2 class="partner-title"><?php echo __('Our Partners'); ?></h2>
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
            if(!$userid){
	 ?>

			<!-- FREE TRIAL PROMO BOX -->
		   <!-- <div class="hero-unit dark-hero reg_section">
				<a class="btn btn-large btn-primary" href="<?php //echo URL::base();?>">Join Now</a>
			</div> -->
			<div class="section row-fluid signup">
				<div class="container">
					<!-- FREE TRIAL PROMO BOX -->
					<div class="hero-unit dark-hero reg_section">
						<h1><span><?php echo __('Get Started'); ?></h1>
						<a data-role="none" data-ajax="false" class="btn btn-large btn-primary <?php echo $bg_class.' '.$font_class;?>" href="javascript:void(0);" data-toggle="modal" data-target="#joinModal"><?php echo __('Register Here'); ?></a>
					</div>
				</div>
			</div>
     <?php } ?>
	 </div>
	 
	 
	
<?php 
$session	= Session::instance();
if ($session->get('flash_activation_popup')):
?>
	<script type="text/javascript">
		
		$(document).ready(function($){
			$("#myModal").modal('show');
		});
	</script>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="vertical-alignment-helper">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header" style="border-bottom:0">
	        <button data-role="none" data-ajax="false" type="button" class="close" data-dismiss="modal">&times;</button>
	      </div>
	      <div class="modal-body">
	        <p style="padding:10px;"><?php echo $session->get_once('flash_activation_popup');?></p>
	      </div>
	      <div class="modal-footer" style="border-top:0">
	        <button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
	      </div>
	    </div>
    </div>
  </div>
</div>
<?php endif ;
if ($session->get('flash_error_message')):
?>
	<script type="text/javascript">
		
		$(document).ready(function($){
			$("#errorModal").modal('show');
		});
	</script>
<!-- Modal -->
<div id="errorModal" class="modal fade" role="dialog">
	<div class="vertical-alignment-helper">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header" style="border-bottom:0">
	        <button data-role="none" data-ajax="false" type="button" class="close" data-dismiss="modal">&times;</button>
	      </div>
	      <div class="modal-body">
	        <p style="padding:10px;color:red;"><?php echo $session->get_once('flash_error_message');?></p>
	      </div>
	      <div class="modal-footer" style="border-top:0">
	        <button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
	      </div>
	    </div>
    </div>
  </div>
</div>
<?php endif ; 
//dh change to user site
if($session->get('changeto_site_id') != ''){
	$session->set('changeto_site_id','') ; ?>
	<script type="text/javascript">
		$(document).ready(function($){
			$("#popupnotinsite").modal('show');
		});
		$(document).on("click","#siteredirectyes",function(){
			window.location.href="<?php echo URL::base(true).'site/'.$session->get('current_site_slug');?>";
		});
	</script>
<div id="popupnotinsite" class="modal fade" role="dialog">
	<div class="vertical-alignment-helper">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header" style="border-bottom:0">
	        <button data-role="none" data-ajax="false" type="button" class="close" data-dismiss="modal">&times;</button>
	      </div>
	      <div class="modal-body">
	      <?php echo __('redirectUsersitepopup',array("[sitename]" => $session->get('current_site_name')));?>
	      </div>
	      <div class="modal-footer" style="border-top:0">
	      	<button data-role="none" data-ajax="false" type="button" class="btn btn-default" id="siteredirectyes"><?php echo __('Yes'); ?></button>
	        <button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('No'); ?></button>
	      </div>
	    </div>
	  </div>
	</div>
</div>
<?php } echo $footer;
//echo HTML::style("media/js/classie.js"); ?>
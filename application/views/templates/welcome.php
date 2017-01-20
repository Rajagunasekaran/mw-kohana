<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <!--  SEO STUFF START HERE -->
        <title><?php echo __('Health and Wellness program'); ?></title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="robots" content="follow, index" />
        <!--  SEO STUFF END -->
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <?php echo HTML::style("assets/css/bootstrap.min.css"); ?>
		<?php echo HTML::style("assets/css/font-awesome.min.css"); ?>
		<?php echo HTML::style("assets/css/jquery-ui.css"); ?>
		<?php echo HTML::style("assets/css/font.css"); ?>
		<?php echo HTML::style("assets/css/bootstrap-switch.css"); ?>
		<style>
			body {
			    background-color: #b84d45;
			    background-image: url("/assets/img/bg.png");
			    color: rgba(255, 255, 255, 0.8);
			    margin-bottom: 40px;
			    padding-left: 15px;
			    padding-right: 15px;
			    position: relative;
			}
		</style>
	</head>
    <body>
    	  <header>
		    <div class="container">
		        <h1><?php echo __('Health and Wellness program'); ?></h1>
		        <!--p>A collection of free, Bootstrap built landing page and home page themes and templates.</p-->
		    </div>
		  </header>
          <div class="container">
				<div class="row previews">
					<div class="col-lg-12 col-sm-12">
           				<?php echo $content; ?>
           			</div>
           		</div>
           </div> 
           <footer>
			    <div class="container">
			        <hr>
			        <div class="row">
			            <div class="col-lg-12 footer-below">
			                <p><?php echo __('e30 CompassIT'); ?>.</p>
			            </div>
			        </div>
			    </div>
			</footer>
    </body>   
</html>

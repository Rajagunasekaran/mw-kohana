<?php echo $header;?> 
<div class="main-wrapper after-nav">
<div class="container dynamicpage">
	<div class="content pagecontent">
    	<h1 class="dynamic-page-title"><?php if(isset($data['page_title'])) echo $data['page_title'];?></h1>
		<div class="page_content"><?php if(isset($data['page_content'])) echo $data['page_content'];?></div>
    </div>
</div>
<?php echo $footer;?>

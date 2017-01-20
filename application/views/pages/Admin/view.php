<link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item("site_url");?>assets/css/uploadifive.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item("site_url");?>assets/uploadify/uploadify.css" />
<!--script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script-->
<script src="<?=$this->config->item("site_url")?>assets/jsnew/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("site_url");?>assets/js/jquery.uploadifive.min.js" ></script>
<script type="text/javascript" src="<?php echo $this->config->item("site_url");?>assets/uploadify/jquery.uploadify.min.js" ></script>

<link rel="stylesheet" type="text/css" href="<?=$this->config->item("site_url");?>assets/fancybox-popup/source/jquery.fancybox.css?v=2.1.5" media="screen" />


<style type='text/css'>
[class*='close-'] {color: #777;font: bold 16px/100% arial, sans-serif;position: absolute;right: -133px;top: -112px;text-decoration: none;text-shadow: 0 1px 0 #fff;border-radius: 100%;}
.close-thik:after {content: '✖'; }
.dialog {  background: #ddd;  border: 0px solid #ccc;  float: left;  position: relative;}
</style>
<?php
//echo "<pre>";print_r($content["details"]);
?>
<script type="text/javascript">

$('head').append( '<meta http-equiv="refresh">' );

function checkFile() {
document.getElementById("filesizecheck").innerHTML=""
if (typeof FileReader !== "undefined") {
var size = document.getElementById('pdfproof').files[0].size;
if (size > 15000000) {alert('File you selected is too large to upload, please choose a file smaller than 15MB');resetFormElement($('#pdfproof'));} else {document.getElementById("filesizecheck").innerHTML="File Size: " + readableFileSize(size);}
}
//var oInput = document.getElementById('pdfproof');
//ValidateSingleInput(oInput);
}


var _validFileExtensions = [".pdf",".PDF"];    
function ValidateSingleInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
            if (!blnValid) {
                //alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
					 alert("Please upload pdf file only")
                oInput.value = "";
					 document.getElementById("filesizecheck").innerHTML="";
                return false;
            }
        }
    }
    return true;
}

function readableFileSize(size) {
    var units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    var i = 0;
    while(size >= 1024) {size /= 1024;++i;}
    return size.toFixed(1) + ' ' + units[i];
}
function resetFormElement(e) {
e.wrap('<form>').parent('form').trigger('reset');
e.unwrap();
}

<?
$formdata = '\'signid\'   : \'' . $content["details"]->signid . '\'';
//builduploader('file_upload_EmailedImages','15MB',$formdata,'true',base_url() . 'index.php/home/addphoto');
builduploader('file_upload_EmailedImages','15MB',$formdata,'true',base_url() . 'index.php/home/addphoto/'.$content["order_details"]->orderid);
?>

   
                                 
                            response = $.parseJSON(data);
                        
                            if (!response.FileExist) {
                             
                            if (response.quality == true) {
                                goodbad = '<img src="<?php echo $this->config->item("site_url");?>assets/img/tick.gif">';
                            } else {
                                goodbad = '<img src="<?php echo $this->config->item("site_url");?>assets/img/cross.gif">';
                            }
                            
                            //$('#file_upload_EmailedImages').uploadifive('clearQueue');
                            $('#files2').append('<div class="upimg" id="trimage-'+response.id+'" style=""><table class="inner-table"><tbody><tr><td valign="bottom" align="center" class="tdimg"><div class="fs"><a class=\'xfancybox-button\' rel="xfancybox-button" target="_blank" href="'+response.embed+'" title= "Up:'+response.uploadedby+'">'+
                             '<img src="' + response.thumbembed + '" height="100px" width="100px" class="src"></a><br />'+
                             
                             <?
                                if ($content['details']->proofaddon) {
                                ?>
                                'Sign Use: '+response.usefor+'<br>'+
                                'Bro Use: '+response.usefor2+'<br>'+
                                <?    
                                } else {
                                ?>    
                                'Sign Use: '+response.usefor+'<br>'+
										  'Bro Use: '+response.usefor2+'<br>'+
                                <?    
                                }
                                ?>
                             
                             'Up: ' + response.uploadedby +'<br>Up Time:'+response.uptime+'<br><input type="hidden" name="filename-'+response.id+'" id="filename-'+response.id+'" value="' + response.filename + '">'+
                             
                             ''+goodbad+'&nbsp;&nbsp;&nbsp;&nbsp;<a class="deleter" onclick="deleteImage(\'trimage-'+response.id+'\')"><img border="0" src="<?=IMG?>deleter.jpg"></a></div></td></tr></tbody></table></div>');
                             } else {alert('The File aleady exists so it was not uploaded again')}

                        
                    }
                });
});

function deleteImage(theID) {
    filename = $('#'+theID.replace('trimage-','filename-')).val();
    if (confirm('Are you sure you want to delete this image?')) {
        $.post("<?=base_url();?>index.php/home/deletesignimage/<?=$content['details']->signid ?>/"+filename, function( data ) {
            response = $.parseJSON(data);
            if (response != 'done') {alert("error: "+response);} else {$('#'+theID).remove();}
            
        });
    }

}
function changeStatus()
{
	$('#chStatusBtn').hide();
	$('#chStatus').show();
	return true;	
}
</script>
<?php if($this->input->get('sign_type') == 'dyo'){?>
<script>
/*$(document).ready(function(){
	var viewportwidth;
 	var viewportheight;
 	// the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
	 if (typeof window.innerWidth != 'undefined')
	 {
	      viewportwidth = window.innerWidth,
	      viewportheight = window.innerHeight
	 }
	// IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)
 	else if (typeof document.documentElement != 'undefined'
	     && typeof document.documentElement.clientWidth !=
	     'undefined' && document.documentElement.clientWidth != 0)
	 {
	       viewportwidth = document.documentElement.clientWidth,
	       viewportheight = document.documentElement.clientHeight
	 }
	 // older versions of IE
	 else
	 {
	       viewportwidth = document.getElementsByTagName('body')[0].clientWidth,
	       viewportheight = document.getElementsByTagName('body')[0].clientHeight
	 }
	viewportheight = viewportheight-55;
	document.getElementById('viewportHeights').value = viewportheight;
});*/
function editSigns(signId)
{
	document.frmSignEdit.action= '<?php echo UPLOADS_DYO.'agent/editDyoSign/?sid=';?>'+signId+'&viewport=<?php echo $content['details']->viewport;?>';//+document.getElementById('viewportHeights').value;
	document.frmSignEdit.submit();
	return true;
}
</script>
<?php }?>
<?
	$image_url = $this->config->item('new_base_url');
	?>
<div id="wrapper">

    <?php
	 
	 require_once __DIR__ .'/../'.'admin_global_header.php';
	 
	 //require_once __DIR__ .'/../superadmin/'.'designer_home.php';
	 ?>

		  
		  
		  
        <div id="container">
        	<?php if($this->session->userdata('success')){?>
	            <div id="flashdata" class="flashdata"><?=$this->session->userdata('success');?><? $this->session->unset_userdata('success'); ?></div>
            <?php } ?>
            
             <div align="right" style="height:35px; margin:10px 20px 0 0">
                 <form action="<?=base_url()?>index.php/home/getorders" method="post" class="src-form">
                 <table class="inner-table">
                 <tr>
                 	<td>
                    <? 
                    	$mode = $this->session->userdata('searchmode');
                    ?>
                    <div style="margin-top:3px; padding:1px; float:left"><label for="searchsuburb">Suburb</label></div>
                    <div style="margin-top:-2px; padding:1px; float:left"> <input type="radio" class="searchby" id="searchsuburb" name="searchby" <? if($mode=='suburb'){echo' checked="checked"';}?> value="suburb" /></div>
                    <div style="margin-top:3px; padding:1px; float:left"><label for="searchsignid">Sign ID</label></div>
                    <div style="margin-top:-2px; padding:1px; float:left"> <input type="radio" class="searchby" id="searchsignid" name="searchby" <? if($mode=='signid'){echo' checked="checked"';}?> value="signid" /></div>
                    <div style="margin-top:3px; padding:1px; float:left"><label for="searchagent">Agent</label></div> 
                    <div style="margin-top:-2px; padding:1px; float:left"><input type="radio" class="searchby" id="searchagent" name="searchby"<? if($mode=='agent'){echo' checked="checked"';}?> value="agent" /></div>
                    <div style="margin-top:3px; padding:1px; float:left"><label for="searchstreet">Street</label></div> 
                    <div style="margin-top:-2px; padding:1px; float:left"><input type="radio" checked="checked" class="searchby" id="searchstreet" name="searchby"<? if($mode=='street'){echo' checked="checked"';}?> value="street" /></div>
                    
                    <div style="margin-top:3px; padding:1px; float:left">&nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;Entire Website</div> 
                        <div style="margin-top:-2px; padding:1px; float:left"><input type="checkbox" class="scope" name="scope"<? if($mode=='all'){echo' checked="checked"';}?> value="all" /></div>
                        
                        <div style="margin-top:3px; padding:1px; float:left">&nbsp;&nbsp;Include Archives</div> 
                        <div style="margin-top:-2px; padding:1px; float:left"><input type="checkbox" class="archive" name="archive"<? if($mode=='archive'){echo' checked="checked"';}?> value="archive" /></div>
                                    
                    </td>
                    <td><input class="input" style="width:100px;" type="text" name="searchtxt" id="searchtxt" autocomplete='off' /></td>
                    <td><input type="submit" value="search" class="button" name="Submit"  /></td>
                    </tr>
                 </table>
                 </form>
            </div>
				
				 
            <div id="form-table-container">
            	<div class="form-top-02 png"> </div>
                <div class="form-mid-02 png">
					<h2>Adjust Status of Sign Order</h2>
							<table align='right'><tr><td>
                    <form name="reprint" action="<?php echo base_url();?>index.php/admin/reprint/<?=$content['order_details']->signid ?>/<?=$content['details']->agencyid; ?>/<?=$content['order_details']->orderid."/".$this->input->get('sign_type');  ?>" style="float:right;">
                    <input type="submit" value="Re-Print" class="button" />
                    </form>
                    </td>
                    <?php 
						  if($content['order_details']->ordertype=="Overlay Artwork Being Printed" || $content['order_details']->ordertype=="Order Overlay Request" || 
	$content['order_details']->ordertype=="Overlay Artwork Pending Delivery" || $content['order_details']->ordertype=="Overlay Artwork In Progress" ){	?>
                     <td><form name="reprint" action="<?php echo base_url();?>index.php/admin/reprint/<?=$content['order_details']->signid ?>/<?=$content['details']->agencyid; ?>/<?=$content['order_details']->orderid."/".$this->input->get('sign_type').'/OVERLAY';  ?>" style="float:right;">
                    <input type="submit" value="Re-Print Overlay" class="button" />
                                  </form></td>
                    <?php }else if($content['order_details']->ordertype=="Sign Doctor Request"){?>
                    <td><form name="reprint" action="<?php echo base_url();?>index.php/admin/reprint/<?=$content['order_details']->signid ?>/<?=$content['details']->agencyid; ?>/<?=$content['order_details']->orderid."/".$this->input->get('sign_type').'/SIGNDOCTOR';  ?>" style="float:right;">
                    <input type="submit" value="Re-Print Sign Doctor" class="button" />
                    </form></td>
                    <?php } ?>
                    </tr></table>

                    <?php
					//var_dump($content);
					//print_r($content);
					?>
                    <table class="table-container2" width="100%"  border="0" cellspacing="0" cellpadding="0" style="margin:0 auto">
                        <tr>
                            <th width="50%">Order Details</th><th width="50%">Uploaded Photos </th>
                        </tr>
                        <tr>
                            <td align="center" valign="top">
                                <table cellspacing="1" cellpadding="2"  width="100%" class="inner-table" border="0">
                                   <tbody>
                                   <?  if($content['details']->agency_status == "New" || $content['details']->agency_status == "Provisional"){ ?>
                                    <tr align="center" bgcolor="#990000">
                                       <td colspan="2" nowrap class="tobold"><h3>NOTE: Agency status: <?=$content['details']->agency_status?>!</h3></td>
                                    </tr>
                                    <? } ?>
                                    <tr>
                                       <td width="10%" nowrap=""><span class="boldy">Sign Id:</span></td>
                                       <td>
									   <?php echo $content['order_details']->signid;?>
													<?php
													 if($content['details']->sign_missing==1){ 	//if($content['details']->sign_missing==1 && $content['details']->sign_missing_reason!=""){
														 echo "<span style='color:red'>(MISSING)</span>";
														 ?>
														<a href="<?=base_url()?>index.php/home/unsetsetmissingsign/<?=$content['order_details']->signid?>/<?=($this->input->get('sign_type')== 'dyo')?$this->input->get('sign_type'):""; ?>">Unset Sign Missing</a>
														<?php
													 }
													 ?>
													</td>
                                    </tr>
                                    <tr>
                                       <td width="10%" nowrap=""><span class="boldy">Franchise:</span></td>
                                       <td><?=str_replace('Digital Central - ', '', $content['details']->franchisename)?></td>
                                    </tr>
                                    <tr>
                                       <td nowrap=""><span class="boldy">Order Type: </span></td>
                                       <td><?=$content['order_details']->ordertype?></td>
                                    </tr>
                                    <tr>
                                       <td nowrap=""><span class="boldy">Date Entered: </span></td>
                                       <td><?=$content['order_details']->entry_date?></td>
													
                                    </tr>

									<tr>
                                       <td nowrap=""><span class="boldy">Installation Date: </span></td>
                                       <td>
	                                       <form method="post" name="frm_installation_date" action="<?=base_url()?>index.php/home/change_installationdate" >
	                                       		<input type="text" name="installation_date" placeholder="Install ASAP" id="datepicker" value="<?php if(empty($content['details']->installation_date)){ echo 'Install ASAP';} else{ echo $content['details']->installation_date;}?>">
                                            <input type="hidden" name="signtype" value="<?=($this->input->get('sign_type')== 'dyo')?$this->input->get('sign_type'):"";?>" >
	                                       		<input type="hidden" name="signid" value="<?=$content['details']->signid; ?>">
	                                       		<input type="hidden" name="orderid" value="<?=$content['order_details']->orderid?>">
	                                       		<input type="hidden" name="agencyid" value="<?=$content['details']->agencyid?>">  
	                                            <input type="submit" id="btn-instldate-save" value="Save change" >
	                                       </form>
                                       </td>
                                    </tr>
                                    <?php if(!empty($content['details']->installation_date) && $content['details']->installation_date != 'Install ASAP'){
										 $insdate = $this->db->query("Select date_format(date,'%b %d, %Y') AS installdate from historylog where heading like 'Installation Date updated%' and signid = '".$content['details']->signid."' and sign_type = '".$this->input->get('sign_type')."' limit 0,1");
										 if($insdate->num_rows()>0)
										 	$installDates = $insdate->row();
										 else
										 	$installDates->installdate = $content['details']->requestdate;
									?>
                                    <tr>
                                       <td nowrap=""><span class="boldy">Installation<br />Requested Date: </span></td>
                                       <td valign="bottom"><?php echo $installDates->installdate;?></td>
                                    </tr>
                                    <?php }?>
                                    <tr>
                                       <td nowrap=""><span class="boldy">Quoted: </span></td>
                                       <td>$<?=$content['details']->quoted?></td>
                                    </tr>
                                    <tr valign="top">
                                      <td nowrap=""><span class="boldy">Description:</span></td>
                                      <td><?= str_replace("|","<br />",$content['order_details']->description); ?></td>
                                    </tr>  
                                    <? if ($content['details']->specialinstructions != ""){ ?>
                                    <tr valign="top">
                                      <td nowrap=""><span class="boldy">Special Instruction:</span></td>
                                      <td>
												  <?php
                                      $content['details']->specialinstructions= str_replace("\r\n","<br>",$content['details']->specialinstructions);
												  ?>
												  <?= stripslashes($content['details']->specialinstructions); ?></td>
                                    </tr>
                                    <? } ?>									
                                    <tr valign="top">
                                        <td nowrap=""><span class="boldy">Brochure:</span></td>
                                    <?php if(!empty($content['has_brochure']->bid)){?>                                    
                                        
                                        <?php //var_dump($content['has_brochure']);?>
                                        <td>
                                         <a href="<?=base_url()?>index.php/home/brochure/<?=$content['has_brochure']->bid?>/<?=$content['details']->signid?>" >Cilck here to view details</a>                                        
                                         </td>
									  <?php }else if ($content['details']->status == "Waiting for Approval" || $content['details']->status == "Pending Order" || $content['details']->status == "Proof in Progress" || $content['details']->status == "Pending Printing"){?>  
									      		<td>
                                         			<a href="<?=base_url()?>index.php/home/addbrochuretosign/<?=$content['details']->signid?>/<?=$content['details']->agencyid?>" >Add Brochures</a>
                                         		 </td>
									  <?php }?>                              
                                     
                                     </tr>
                                    <? if ($content['order_details']->ordertype == "Move Sign Request"){ ?>
                                     <tr>
                                       <td nowrap><span class="boldy">New Address:</span></td>
                                       <td><?=$content['order_details']->address?></td>
                                     </tr>
                                     <tr>
                                       <td nowrap><span class="boldy">New Address 2: </span></td>
                                       <td><?=$content['order_details']->address2?></td>
                                     </tr>
                                     <tr>
                                       <td nowrap><span class="boldy">New Suburb:</span></td>
                                       <td><?=$content['order_details']->suburb?></td>
                                     </tr>
                                     <tr>
                                       <td nowrap><span class="boldy">New State:</span></td>
                                       <td><?=$content['order_details']->state?></td>
                                     </tr>
                                     <tr>
                                       <td nowrap><span class="boldy">New Postcode:</span></td>
                                       <td><?=$content['order_details']->postcode?></td>
                                     </tr>
                                     <? } // end if type is move location ?>
                
                                      <tr>
                                        <td align="left" nowrap><span class="boldy">DYO Sign:</span></td>
                                        <td align="left" nowrap><?php if($this->input->get('sign_type') == 'dyo'){ echo 'Yes';} else{ echo 'No';}?></td>
                                      </tr>
                                      <?php if($this->input->get('sign_type') == 'dyo'){?>
                                      <tr>
                                        <td align="left" nowrap><span class="boldy">P.O. / Name:</span></td>
                                        <td align="left" nowrap><?php echo $content['details']->po_name; ?></td>
                                      </tr>
                                    
                                      <tr>
                                        <td align="left" nowrap><span class="boldy">Requested Help:</span></td>
                                        <td align="left" nowrap><?php if($content['details']->status == 'DYO Help Requested'){ echo 'Yes';} else{ echo 'No';}?></td>
                                      </tr>
												  
												  <?php if($content['details']->comments !=""){ ?>
												  <tr>
                                        <td align="left" nowrap><span class="boldy">Comments:</span></td>
                                        <td align="left"><?php echo wordwrap($content['details']->comments);?></td>
                                      </tr>
												  <?php } ?>
                                      <tr>
                                        <td align="left" nowrap>&nbsp;</td>
                                        <td align="left" nowrap><form id="frmSignEdit" name="frmSignEdit" method="post" action="">
                                          <input type="hidden" name="sessionId" value="<?php echo $tst_dec = session_id();//base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, 'sayoiyoiotiweoitoywopyt', , MCRYPT_MODE_ECB, $this->iv));?>" />
                                          <input type="hidden" name="login_admin" value="<?php echo $this->session->userdata('login_admin');?>" />
                                          <input type="hidden" name="accessfranchise" value="<?php echo $this->session->userdata('accessfranchise');?>" />
                                          <input type="hidden" name="login_usermode" value="<?php echo $this->session->userdata('login_usermode');?>" />
                                          <input type="hidden" name="headerData" value="<?php echo $this->session->userdata('LoggedIn').'||'.$this->session->userdata('login_fullname').'||'.$this->session->userdata('LoginType').'||'.$this->session->userdata('UserName').'||'.$content['details']->agencyid;?>" />
                                          <input type="button" value="Edit Sign" class="button" style='background-color: #00A7FF;border: 2px outset #00A7FF;float: left;margin-top:5px;' onclick="return editSigns('<?php echo $content['order_details']->signid;?>');" />
                                          <input type="hidden" id="viewportHeights" /><input type="hidden" name="sessinfo" value="<?php echo base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(chiperkey), $this->session->userdata('username')."||".$this->session->userdata('islogged')."||".$this->session->userdata('pass'), MCRYPT_MODE_CBC, md5(md5(chiperkey))));?>"/></form></td>
                                      </tr>
                                      <?php }?>
                                     <? if(substr($content['order_details']->status,0,10)=="(On Hold)"){?>
                                      <tr>
                                       <td colspan="2" align="center" nowrap><p><span class="boldy"><font color="#CC0000">THIS SIGN IS ON HOLD</font></span></p></td>
                                      </tr>
                                     <? } ?>
                                    </tbody>
                                </table>
                                <br /><br />
                                <hr noshade />                                
                               
<?php 
if(/*$this->session->userdata("login_admin")=='yes' &&*/ ($content['details']->status == 'Installed' || $content['details']->status == 'Overlay Requested' || $content['details']->status == 'Sign Doctor Requested' ) ){?>
                                                                    
                                    <table class="inner-table" width="100%"  border="0" cellpadding="0" cellspacing="0">							
									  <tr>									        
                                             <td nowrap><span class="boldy">Extension:</span></td>
											 <td nowrap>
											   <form id="installed_sign" action="<?=base_url()?>index.php/home/extend_sign" method="post" name="installed_sign">
											   
											         <input type="hidden" name="signid" value="<?=$content['details']->signid; ?>">		                                           
		                                             <input type="hidden" name="signtypeid" value="<?=$content['details']->signtypeid?>"> 	                                           
		                                             <input type="hidden" name="sign_type" value="<?php echo $this->input->get('sign_type');?>"> 
		                                             <input type="hidden" name="signmodelid" value="<?=$content['details']->signmodelid?>"> 
		                                             <input type="hidden" name="templateid" value="<?=$content['details']->templateid?>"> 
		                                             <input type="hidden" name="auctionInstalled" value="<? //$content['details']->auctionInstalled?>">
		                                             <input type="hidden" name="status" value="<?=$content['details']->status?>"> 
		                                             <input type="hidden" name="orderid" value="<?=$content['order_details']->orderid?>">       
		                                             <input type="hidden" name="ordertype" value="<?=$content['order_details']->ordertype ?>">       
		                                             <input type="hidden" name="orderdescription" value="<?=$content['order_details']->description ?>">       
		                                             <input type="hidden" name="agencyid" value="<?=$content['details']->agencyid?>">       
		                                             <input type="hidden" name="agentid" value="<?=$content['details']->agentid?>">       
		                                             <input type="hidden" name="price" value="<?=$content['order_details']->price?>">											   
											       
													<input style="height:12px;" class="extension_period input" id="m1" name="extension_period" type="radio" value="1" />1 Month ($<?=$content['details']->month_one?>)
													<input style="height:12px;" class="extension_period input" id="m2" name="extension_period" type="radio" value="3" /> 3 Months ($<?=$content['details']->month_three?>)
													<input style="height:12px;" class="extension_period input" id="m3" name="extension_period" type="radio" value="6" /> 6 Months ($<?=$content['details']->month_six?>)
													<br/><label><input type="checkbox" id="gfe" name="gotfree" value="yes" /> Grant Free Extension</label><br/>	
													<input type="submit" style="margin-left:10px" value="Extend Sign"class="button" />
											   </form>												
											</td>												
                                      </tr>
                                       
                                       <tr>
                                           <td nowrap><span class="boldy">Extension Expired:</span></td>
                                            <td nowrap><?php $days = $content['details']->leasedays + $content['details']->exempt_days + 30 * $content['details']->paid_extensions;
                                                              $exdate =  date('d M Y', strtotime($content['details']->dateinstalled." + $days days"));
                                                              echo $exdate;                                                             
                                             ?></td>
                                       </tr>
                                       
                                       
      <tr>
          <td nowrap><br/><br/><span class="boldy">More actions:</span></td>
          <td nowrap><br/><br/>
              <input type="button" id="set_unset_rmdate" style="margin-left:10px" value="Set / Unset Removal Date" class="button" onclick= "parent.location='<?=base_url()?>index.php/agencies/removesign/<?=$content['details']->signid?>/<?=$content['details']->agencyid?><?php if($this->input->get('sign_type')=='dyo') echo '/dyo';?>'" /> 
             <? 
				 if(empty($content['details']->removaldate) || $content['details']->removaldate=='0000-00-00'){ 
				 //$Readable_Removal_Date	=	$content['details']->removaldate;								
				 //$nowdate			=	strtotime(date("Y-m-d",time()));
				 // echo $content['details']->removaldate."--$nowdate---$Readable_Removal_Date----".strtotime($Readable_Removal_Date);
				 //if($content['details']->removaldate<=$nowdate  && strtotime($Readable_Removal_Date) < $nowdate){
//	if($content['details']->status == 'Installed' && $content['details']->status != 'Overlay Requested' && $content['details']->status != 'Sign Doctor Requested' ) {
				 ?>                                              
             <input type="button" id="sign_doctor" style="margin-left:10px" value="Sign Doctor" class="button" onclick= "parent.location='<?=base_url()?>index.php/agencies/signdoctor/<?=$content['details']->signid?>/<?=$content['details']->agencyid?><?php if($this->input->get('sign_type')=='dyo') echo '/dyo';?>'"/>                                              
             <input type="button" id="order_overlay" style="margin-left:10px" value="Order Overlay" class="button" onclick= "parent.location='<?=base_url()?>index.php/agencies/orderoverlay/<?=$content['details']->signid?>/<?=$content['details']->agencyid?><?php if($this->input->get('sign_type')=='dyo') echo '/dyo';?>'"/> 
             <?php }?>                                          
          </td>                                           
      </tr>  
                                       
                                        
                                                                              
                              </table> <br/><br/>                                       
                                       <hr noshade />
                                    <?php } ?>
												
												<?php /*
												$flag_holder = 2;
												if($content["details"]->sign_flag_holder==1 && $content["details"]->sign_flag_holder==1){
													 $flag_holder=1;	 
													 ?>
													 <table class="inner-table" width="400"  border="0" cellpadding="0" cellspacing="0">
													 <tr><td><div class="errorbox" id="errorbox" style="display:none; width:400px; color:#fff; text-align:center"></div></td></tr> 	                                	
													 <tr><td><span class="boldy">Flag Holder:</span></td></tr>
													 <tr><td colspan= 4 bgcolor="#F4F4F4"><table >
														  <tr style="font-weight:bold"><td>Price </td><td> $<?php echo $content["details"]->sign_price_flag_holder; ?></td></tr>
														  <!--tr><td><span id="base-price">$34</span></td><td><span id="base-desc"></span></td></tr-->
														  </table></td></tr>
													 </table>
													 <br /><br />
													 <hr noshade />
													 <?php
													 $content['details']->quoted = $content['details']->quoted+$content["details"]->sign_price_flag_holder;
												}	*/?>
												
                                   
                                        <table class="inner-table" width="100%"  border="0" cellpadding="0" cellspacing="0">
	                                	<tr><td><div class="errorbox" id="errorbox" style="display:none; width:100%; color:#fff; text-align:center"></div></td></tr> 	                                	
	                                	<tr><td><span class="boldy">Pricing and Addons:</span>
												
												</td></tr>
	                                	<?php if($this->session->userdata('login_admin') == 'yes'){?>
		                                	<tr><td><span style="margin-left: 15px;"><a class="button" onclick="changeSignPrice()">Change Base Price</a></span></td>
		                                	<td><span><a class="button" onclick="addNewAddons()">Add New Addons</a></span></td></tr>
	                                	<?php }?>
	                                	<tr><td class="boldy">Base Price: </td></tr>
	                                	<?php 
	                                		$sumOfPriceCustomer=0;
	                                		$sumOfFranchiseCharge=0;
	                                		$basePrice = $content['details']->quoted;
	                                		
	                                		foreach ($addons as $addon){	                                			
	                                			if($addon->addon == 'no'){
	                                				$basePrice = $addon->baseprice;
	                                				$desc =  $addon->notes;
	                                			}
	                                		}
	                                		?>
	                                		
													 <tr><td colspan= 4 bgcolor="#F4F4F4"><table >
													 <tr style="font-weight:bold"><td>Price </td><td>Franchisee Print Cost</td><td>Description </td></tr>
	                                	    <tr><td><span id="base-price">$<?=$basePrice?></span></td>
														  <td>$<?php
														  //if($content['details']->printcost && $content['details']->printcost!=0){$basePrice = $basePrice+$content['details']->printcost;}
														  echo $content['details']->printcost;
														  
														  
														  
														  ?></td>
														  <td><span id="base-desc"><?=$desc?></span></td></tr>                           	                     
	                                    
													 </table></td></tr>
	                                    
	                                	<tr><td class="boldy">Add ons: </td></tr>	
	                                	<tr><td colspan= 4 bgcolor="#F4F4F4"><table >
	                                	                      <tr style="font-weight:bold"><td>Addons desc </td><td>Price to customer </td><td>Franchisee Charge</td><td>Notes</td></tr>
																			 
																			 
																			 
																			 
																			 
																			 
	                                	                     <?php 
																			$flag_holder = 2;$sign_solar =2;
																			$brochure_holder = 2;$sign_floodlight =2;
																			$wings=2;
																			foreach ($addons as $addon){
	                                	                     	   if($addon->addon == 'yes'){
	                                	                           $sumOfPriceCustomer = $sumOfPriceCustomer + $addon->price_customer;
	                                	                           $sumOfFranchiseCharge = $sumOfFranchiseCharge + $addon->franchisee_charge;
	                                	                           if($addon->addonid==3){$flag_holder=1;	}
																					if($addon->addonid==5){$sign_solar=1;	} 
																					if($addon->addonid==13){$brochure_holder=1;	} 
																					if($addon->addonid==14){$sign_floodlight=1;	}
																					if($addon->addonid==15){$wings=1;
																					
																					$wingtypes =  $this->db->from('signaddons_wingtype')->where('wtid', $content['details']->sign_wing_type)->get()->row();
									 												 $addon->addonname =  $addon->addonname."(".$wingtypes->wing_type.")";
																					} 
	                                	                     	?>
	                                	                        <tr><td><?=$addon->addonname?></td><td>$<?=$addon->price_customer?></td><td>$<?=$addon->franchisee_charge?></td><td><?=$addon->notes?></td></tr>
	                                	                     <?php }
	                                	                     }
																			
																			if($content['details']->printcost && $content['details']->printcost!=0){$sumOfFranchiseCharge = $sumOfFranchiseCharge+$content['details']->printcost;}
																			
																			?>
	                                	                     
	                                	                     <tr style="font-weight:bold" ><td>Total </td><td>$<?=number_format($basePrice+$sumOfPriceCustomer,2)?> </td><td>$<?=number_format($sumOfFranchiseCharge,2)?></td></tr>
	                                	            </table>	                                	            
	                                	</td></tr>
	                                	
	                                	
	                                	
                                        </table>
                                        <hr noshade />                                  
                                    
                                    <table class="inner-table" width="100%"  border="0" cellpadding="0" cellspacing="0">
                                	<tr><td><div class="errorbox" id="errorbox" style="display:none; width:100%; color:#fff; text-align:center"></div></td></tr>                                	
                                	<?=form_open('',array('id'=>'addnotesform'))?>
                                	<tr><td><span class="boldy">Add Notes:</span></td></tr>
                                    <tr><td><textarea name="notes" id="notes" rows="3" class="input" cols="30" style="width:100%; margin-top:5px; height:100px"><?=$content['details']->notes?></textarea></td></tr>
                                    <tr><td align="right"><input type="button"  id="addnotes" class="button" value="Add Notes" /></td></tr>
                                    <?=form_close()?>
                                </table>
                              <script type="text/javascript">
									$('#addnotes').live('click',function(){
										var txt = $('#notes').val();
										jConfirm('Are you sure you want to add this notes?', 'Add Notes', function(r) {
											if(r==true){
												$.ajax({
												  url: "<?=base_url()?>index.php/admin/addnotes",
												  type: "POST",
												  data: "notes="+encodeURIComponent(txt)+"&signid="+<?=$content['details']->signid?>+"&sign_type="+encodeURIComponent("<?php echo $this->input->get('sign_type');?>"),
												  success: function(data){
												    $('#errorbox').css('display','block');
													$('#errorbox').html(data);
													$("#errorbox").delay(10000).fadeOut();
												  }
												});
											}
										});
									});
								</script>
                                <hr noshade />
                                <br /><br />
                                <table cellspacing="1" cellpadding="2" border="0" width="100%" class="inner-table" >
                                <?=form_open('home/logentry',array('id'=>'addtologform'))?>
                                <tr>
                                 	<td><?php if($this->session->flashdata('logform')){?>
                                            <div id="flashdata" class="flashdata" style="width:100%; color:#fff"><p class="text"><?=$this->session->flashdata('logform');?></p></div>
                                        <?php } ?>
                                  </td>
                                 </tr>
                                 <tr><td><span class="boldy">Add Log Entry</span><br />
                                     <input type="hidden" name="act" value="addlogentry" />
                                     <input type="hidden" name="signid" value="<?=$content['details']->signid?>" />
                                     <input type="hidden" name="agencyid" value="<?=$content['details']->agencyid?>" />
                                     <input type="hidden" name="orderid" value="<?=$content['order_details']->orderid ?>" />
                                     <input type="hidden" name="sign_type" value="<?php echo $this->input->get('sign_type');?>" />
                                     
                                     <textarea name="details" rows="3" class="input" cols="30" style="width:100%; margin-top:5px; height:100px"></textarea>
                                     <br />
                                     <input name="Submit" type="submit" class="button" value="add" style="float:right; margin-top:5px" />
                                     </td>
                                  </tr>
                                  <tr><td height="20"></td></tr>
                                  <tr><td bgcolor="#F4F4F4"><span class="boldy">Changes log:</span><br /><div class="clear spacer-5"></div>
									<?
                                        if(count($content['logs']) > 0){
                                            foreach($content['logs'] as $log){
												

												
												$datestring = "%M %d - %h:%i %A";
												//$time = $item->dateentered;
												//echo mdate($datestring, mysql_to_unix($time));
												$log->details = str_replace("\r\n","<br />",$log->details);
												//echo $log->details;
                                    ?>
                                                <div class="clear"></div>
                                                <div class="loghead"><?=$log->Readable_Date?> - <span class="boldy"><?=$log->heading?></span></div>
                                                <p class="logreply"><?=stripslashes(wordwrap($log->details))?></p>
                                    <?      }
                                        }else{
                                    ?>
                                             <div class="logreply">No log entries found</div>
                                    <? } ?>
                                  </td></tr>
                                 </form>
							  </table>
<!--  Installer Block Photos -->
<?php //if($this->session->userdata("login_admin")=='yes'){ ?>
<?php
$signid = $content['order_details']->signid;
$arcfolder = 'arc' . substr($signid, 0, 2) . "0000";
         
$img_path    =   UPLOADS_files.'signimages/'.$content['order_details']->signid.'/image/';
$url_img_path    =   $this->config->item("site_url").'signimages/'.$content['order_details']->signid.'/image/';
if (!file_exists($img_path)) {
    $img_path = UPLOADS_files.'signimages/Archive/'.$arcfolder.'/'.$content['order_details']->signid.'/image/';
    $url_img_path = $this->config->item("site_url").'signimages/Archive/'.$arcfolder.'/'.$content['order_details']->signid.'/image/';
}
function expandDirectories($base_dir) {
   $directories = array();
   foreach(scandir($base_dir) as $file) {
       if($file == '.' || $file == '..') continue;
       $dir = $base_dir.DIRECTORY_SEPARATOR.$file;
       if(is_dir($dir)) {
           $directories []= $dir;
           $directories = array_merge($directories, expandDirectories($dir));
       }
   }
   return $directories;
}
$directories = expandDirectories(dirname($img_path));
function files($path,&$files = array()){
   $dir = opendir($path."/.");
   while($item = readdir($dir))
       if(is_file($sub = $path."/".$item))
           $files[] = $item;else
           if($item != "." and $item != "..")
   return($files);
}
function filesonly($path,&$files = array()){
   $dir = opendir($path."/.");
   while($item = readdir($dir))
       if(is_file($sub = $path."/".$item))
           $files[] = $item;else
           if($item != "." and $item != "..")
               files($sub,$files); 
   return($files);
}
$tr=0;
?>
<hr noshade />

<br /><br />
<table cellspacing="1" cellpadding="2" border="0" width="100%" class="inner-table">
       <tr id='ins_head' <?=$style?> ><td bgcolor="#043465" align="center"><span class="boldy white">Installer Photos</span></td></tr>
       
       <!--  Installed Block Photos -->
       <?php
       $temp = array();
       $img_path1_thumb    	=   $img_path.'installed/thumb/';
       $img_path1    			=   $img_path.'installed/';
       $url_img_path1_thumb	=	 $url_img_path."installed/thumb/";
       $url_img_path1	=	 $url_img_path."installed/";
       $x=0;
       if ($handle = opendir($img_path1)) {
           while (false !== ($entry = readdir($handle))) {
               if ($entry != "." && $entry != "..") {
                   if(file_exists($img_path1_thumb.$entry) && file_exists($img_path1.$entry)){
                       $r = explode(".",$entry);
                       $temp[$x]["image"] 			= $entry;
                       $temp[$x]["imageid"] 			= str_replace(" ","",$r[0]);
                       $temp[$x]["img"] 			= $url_img_path1.$entry;
                       $temp[$x]["img_thumb"] 	= $url_img_path1_thumb.$entry;
                       $temp[$x]["uptime"]  = date ("F d Y H:i:s.", filemtime($img_path1.$entry));
                       $x++;
                   }
               }
           }
           $tr=$tr+$x;
       }
       sort($temp);
       if(count($temp)>0){	?>
           <tr>
               <td align="left"><span class="boldy" id='installed' >Installed</span><br><span id="installed_inner"><?php
               foreach($temp as $r=>$t){
                   echo "<div id='installed_".$t["imageid"]."' style='padding: 5px 5px 5px 5px;border:0px solid red;width:30%;float:left;text-align:center;'>";
                   echo "<a class='fancybox' rel='fancybox-button' href='".$t["img"]."' title='Installed : ".$t["uptime"]."'>";
                   echo "<img src='".$t["img_thumb"]."' alt='".$t["uptime"]."' title='".$t["uptime"]."' width='100px' height='100px'>";
                   echo "</a>";
                   echo "<br>	<span>".$t["uptime"]."</span>";
				   if($this->session->userdata("login_admin")=='yes'){ 
                   		echo "<br><div class='dialog'><a href='javascript:;' title='Remove' onclick='remove_img(".$signid.",\"installed\",\"installed_".$t["imageid"]."\",\"".$t["image"]."\")' class='close-thik'></a></div>";
				   }
                   echo "</div>";
                   
               }	?>
               </span>
               </td>
           </tr><?php
       }else{	?>
         <tr><td align="left"><span class="boldy" id='installed' style='display:none'>Installed</span><br><span id="installed_inner"></span></td></tr>
         <?php 
       }?>
       
       <!--  Overlay Photos -->
       <?php
        $temp = array();
		  $x=0;
		  for($s=0;$s<=10;$s++){
				if($s==0){
					 $img_path1_thumb    	=   $img_path.'overlayinstall/thumb/';
					 $img_path1    			=   $img_path.'overlayinstall/';
					 $url_img_path1_thumb	=	 $url_img_path."overlayinstall/thumb/";
					 $url_img_path1			=	 $url_img_path."overlayinstall/";
				}else{
					 $img_path1_thumb    	=   $img_path."overlayinstall_$s/thumb/";
					 $img_path1    			=   $img_path."overlayinstall_$s/";
					 $url_img_path1_thumb	=	 $url_img_path."overlayinstall_$s/thumb/";
					 $url_img_path1			=	 $url_img_path."overlayinstall_$s/";
				}
				if ($handle = opendir($img_path1)) {
					 while (false !== ($entry = readdir($handle))) {
						  if ($entry != "." && $entry != "..") {
								if(file_exists($img_path1_thumb.$entry) && file_exists($img_path1.$entry)){
									 $r = explode(".",$entry);
									 $temp[$x]["image"] 			= $entry;
									 $temp[$x]["imageid"] 			= str_replace(" ","",$r[0]);
									 $temp[$x]["img"] 			= $url_img_path1.$entry;
									 $temp[$x]["img_thumb"] 	= $url_img_path1_thumb.$entry;
									 $temp[$x]["uptime"]  = date ("F d Y H:i:s.", filemtime($img_path1.$entry));
									 $x++;
								}
						  }
					 }
					 $tr=$tr+$x;
				}
		  }
       sort($temp);
       if(count($temp)>0){	?>
           <tr>
               <td align="left"><span class="boldy" id='overlayinstall'>Overlay Install</span><br><span id="overlayinstall_inner"><?php
               foreach($temp as $r=>$t){
                   echo "<div id='overlayinstall_".$t["imageid"]."' style='padding: 5px 5px 5px 5px;border:0px solid red;width:30%;float:left;text-align:center;'>";
                   echo "<a class='fancybox' rel='fancybox-button' href='".$t["img"]."' title='Installed : ".$t["uptime"]."'>";
                   echo "<img src='".$t["img_thumb"]."' alt='".$t["uptime"]."' title='".$t["uptime"]."' width='100px' height='100px'>";
                   echo "</a>";
                   echo "<br>	<span>".$t["uptime"]."</span>";
				   if($this->session->userdata("login_admin")=='yes'){ 
                   		echo "<br><div class='dialog'><a href='javascript:;' title='Remove' onclick='remove_img(".$signid.",\"overlayinstall\",\"overlayinstall_".$t["imageid"]."\",\"".$t["image"]."\")' class='close-thik'></a></div>";
				   }
                   echo "</div>";
               }	?></span>
               </td>
           </tr><?php
       }else{	?>
         <tr><td align="left"><span class="boldy" id='overlayinstall' style='display:none'>Overlay Install</span><br><span id="overlayinstall_inner"></span></td></tr>
         <?php 
       }?>
       
       <!--  Sign Doctor Block Photos -->
       <?php
       $temp = array();
		  $x=0;
		  for($s=0;$s<=10;$s++){
				$img_path1_thumb    	=   $img_path.'signdoctor/thumb/';
				$img_path1    			=   $img_path.'signdoctor/';
				$url_img_path1_thumb	=	 $url_img_path."signdoctor/thumb/";
				$url_img_path1			=	 $url_img_path."signdoctor/";
				if($s==0){
					 $img_path1_thumb    	=   $img_path.'signdoctor/thumb/';
					 $img_path1    			=   $img_path.'signdoctor/';
					 $url_img_path1_thumb	=	 $url_img_path."signdoctor/thumb/";
					 $url_img_path1			=	 $url_img_path."signdoctor/";
				}else{
					 $img_path1_thumb    	=   $img_path."signdoctor_$s/thumb/";
					 $img_path1    			=   $img_path."signdoctor_$s/";
					 $url_img_path1_thumb	=	 $url_img_path."signdoctor_$s/thumb/";
					 $url_img_path1			=	 $url_img_path."signdoctor_$s/";
				}
				if ($handle = opendir($img_path1)) {
					 while (false !== ($entry = readdir($handle))) {
						  if ($entry != "." && $entry != "..") {
								if(file_exists($img_path1_thumb.$entry) && file_exists($img_path1.$entry)){
									 $r = explode(".",$entry);
									 $temp[$x]["image"] 			= $entry;
									 $temp[$x]["imageid"] 			= str_replace(" ","",$r[0]);
									 $temp[$x]["img"] 			= $url_img_path1.$entry;
									 $temp[$x]["img_thumb"] 	= $url_img_path1_thumb.$entry;
									 $temp[$x]["uptime"]  = date ("F d Y H:i:s.", filemtime($img_path1.$entry));
									 $x++;
								}
						  }
					 }
					 $tr=$tr+$x; 
				}
		  }
       sort($temp);
	   
       if(count($temp)>0){	?>
           <tr>
               <td align="left"><span class="boldy" id='signdoctor'>Sign Doctor</span><br><span id="signdoctor_inner"><?php
               foreach($temp as $r=>$t){
                   echo "<div id='signdoctor_".$t["imageid"]."' style='padding: 5px 5px 5px 5px;border:0px solid red;width:30%;float:left;text-align:center;'>";
                   echo "<a class='fancybox' rel='fancybox-button' href='".$t["img"]."' title='Installed : ".$t["uptime"]."'>";
                   echo "<img src='".$t["img_thumb"]."' alt='".$t["uptime"]."' title='".$t["uptime"]."' width='100px' height='100px'>";
                   echo "</a>";
                   echo "<br>	<span>".$t["uptime"]."</span>";
				   if($this->session->userdata("login_admin")=='yes'){ 
                   		echo "<br><div class='dialog'><a href='javascript:;' title='Remove' onclick='remove_img(".$signid.",\"signdoctor\",\"signdoctor_".$t["imageid"]."\",\"".$t["image"]."\")' class='close-thik'></a></div>";
				   }
                   echo "</div>";
               }	?></span>
               </td>
           </tr><?php
       }else{	?>
         <tr><td align="left"><span class="boldy" id='signdoctor' style='display:none'>Sign Doctor</span><br><span id="signdoctor_inner"></span></td></tr>
         <?php 
       }
       ?>
       <!--  Install Instructions Photos -->
       <?php
        $temp = array();
		  $x=0;
		  for($s=0;$s<=10;$s++){
				if($s==0){
					 $img_path1_thumb    	=   $img_path.'installinstructions/thumb/';
					 $img_path1    			=   $img_path.'installinstructions/';
					 $url_img_path1_thumb	=	 $url_img_path."installinstructions/thumb/";
					 $url_img_path1			=	 $url_img_path."installinstructions/";
				}else{
					 $img_path1_thumb    	=   $img_path."installinstructions_$s/thumb/";
					 $img_path1    			=   $img_path."installinstructions_$s/";
					 $url_img_path1_thumb	=	 $url_img_path."installinstructions_$s/thumb/";
					 $url_img_path1			=	 $url_img_path."installinstructions_$s/";
				}
				if ($handle = opendir($img_path1)) {
					 while (false !== ($entry = readdir($handle))) {
						  if ($entry != "." && $entry != "..") {
								if(file_exists($img_path1_thumb.$entry) && file_exists($img_path1.$entry)){
									 $r = explode(".",$entry);
									 $temp[$x]["image"] 			= $entry;
									 $temp[$x]["imageid"] 			= str_replace(" ","",$r[0]);
									 $temp[$x]["img"] 			= $url_img_path1.$entry;
									 $temp[$x]["img_thumb"] 	= $url_img_path1_thumb.$entry;
									 $temp[$x]["uptime"]  = date ("F d Y H:i:s.", filemtime($img_path1.$entry));
									 $x++;
								}
						  }
					 }
					 $tr=$tr+$x;
				}
		  }
       sort($temp);
	   
       if(count($temp)>0){	?>
           <tr>
               <td align="left"><span class="boldy" id='installinstructions'>Install Instructions</span><br><span id="installinstructions_inner"><?php
               foreach($temp as $r=>$t){
                   echo "<div id='overlayinstall_".$t["imageid"]."' style='padding: 5px 5px 5px 5px;border:0px solid red;width:30%;float:left;text-align:center;'>";
                   echo "<a class='fancybox' rel='fancybox-button' href='".$t["img"]."' title='Installed : ".$t["uptime"]."'>";
                   echo "<img src='".$t["img_thumb"]."' alt='".$t["uptime"]."' title='".$t["uptime"]."' width='100px' height='100px'>";
                   echo "</a>";
                   echo "<br>	<span>".$t["uptime"]."</span>";
				   if($this->session->userdata("login_admin")=='yes'){ 
                   		echo "<br><div class='dialog'><a href='javascript:;' title='Remove' onclick='remove_img(".$signid.",\"installinstructions\",\"installinstructions_".$t["imageid"]."\",\"".$t["image"]."\")' class='close-thik'></a></div>";
				   }
                   echo "</div>";
               }	?></span>
               </td>
           </tr><?php
       }else{	?>
         <tr><td align="left"><span class="boldy" id='installinstructions' style='display:none'>Install Instructions</span><br><span id="InstallInstructions_inner"></span></td></tr>
         <?php 
       }?>
       
	   
	   <?php
       
       if($tr==0){ ?><script type="text/javascript">$("#ins_head").show();</script><?php	}	?>
       
   </table>
   <?php

?>
<?php if($this->session->userdata("login_admin")=='yes'){?>
<br>
<div style="display: block;" id="uploadbulka">
<style type="text/css">.upimg{float:left; margin:5px; min-height:200px; overflow:visible}.upimg table td{height:175px}td.tdimg{height:175px; max-width:100px; overflow:hidden}td.tdimg .fs{height:198px; max-width:100px; overflow:hidden}td.tdimg .fs .src{max-height:135px; max-width:100px; overflow:hidden}</style>
<?php print form_open_multipart('agencies/broorderupload',array('id'=>'addphotoform')) ?>
<input name="UploadEmailedImages1[]" id="file_upload_EmailedImages1" multiple=multiple type="file"  />
</form>
</div>
<script type='text/javascript'>
<?
$formdata = "'signid'   : '". $content["details"]->signid."','ordertype'   : 'installed'";
builduploader('file_upload_EmailedImages1','15MB',$formdata,'true',base_url() . 'index.php/home/addphoto/'.$content["order_details"]->orderid,"Upload Installed Photo",200);
?>
		response = $.parseJSON(data);
		if(response && response.FileExist!=true){
				$("#installed").show();
				var res = response.filename.split("."); 
				var imgid = 'installed_'+res[0].replace(/\s+/g, ''); 
				var str = "";
				 str += "<div id='"+imgid+"' style='padding: 5px 5px 5px 5px;border:0px solid red;width:30%;float:left;text-align:center;'>";
				 str += "<a class='fancybox' rel='fancybox-button' href='"+response.embed+"' title='Installed : "+response.uptime+"'>";
				 str += "<img src='"+response.thumbembed+"' alt='"+response.uptime+"' title='"+response.uptime+"' width='100px' height='100px'>";
				 str += "</a>";
				 str += "<br>	<span>"+response.uptime+"</span>";
				 str += "<br><div class='dialog'><a href='javascript:;' title='Remove' onclick='remove_img(<?php echo $signid; ?>,\"installed\",\""+imgid+"\",\""+response.filename+"\")' class='close-thik'></a></div>";
				 str += "</div>";	
			   $('#installed_inner').append(str);	
		}else{
			alert("The File aleady exists so it was not uploaded again")
		}
 	}
});
});
</script>
<br>
<div style="display: block;" id="uploadbulka">
<style type="text/css">.upimg{float:left; margin:5px; min-height:200px; overflow:visible}.upimg table td{height:175px}td.tdimg{height:175px; max-width:100px; overflow:hidden}td.tdimg .fs{height:198px; max-width:100px; overflow:hidden}td.tdimg .fs .src{max-height:135px; max-width:100px; overflow:hidden}</style>
<input name="UploadEmailedImages3[]" id="file_upload_EmailedImages3" multiple=multiple type="file"  />
</div>
<script type='text/javascript'>
<?
$formdata = "'signid'   : '". $content["details"]->signid."','ordertype'   : 'overlayinstall'";
builduploader('file_upload_EmailedImages3','15MB',$formdata,'true',base_url() . 'index.php/home/addphoto/'.$content["order_details"]->orderid,"Upload Overlay Photo",200);
?>
response = $.parseJSON(data);
		if(response && response.FileExist!=true){
				$("#overlayinstall").show();
				var res = response.filename.split("."); 
				var imgid = 'overlayinstall_'+res[0].replace(/\s+/g, ''); 
				var str = "";
				 str += "<div id='"+imgid+"' style='padding: 5px 5px 5px 5px;border:0px solid red;width:30%;float:left;text-align:center;'>";
				 str += "<a class='fancybox' rel='fancybox-button' href='"+response.embed+"' title='Overlay Install : "+response.uptime+"'>";
				 str += "<img src='"+response.thumbembed+"' alt='"+response.uptime+"' title='"+response.uptime+"' width='100px' height='100px'>";
				 str += "</a>";
				 str += "<br>	<span>"+response.uptime+"</span>";
				 str += "<br><div class='dialog'><a href='javascript:;' title='Remove' onclick='remove_img(<?php echo $signid; ?>,\"overlayinstall\",\""+imgid+"\",\""+response.filename+"\")' class='close-thik'></a></div>";
				 str += "</div>";
				 $('#overlayinstall_inner').append(str);
				//window.location.reload();				
		}else{
			alert("The File aleady exists so it was not uploaded again")
		}
   }
});
});
</script>
<br>
<div style="display: block;" id="uploadbulka">
<style type="text/css">.upimg{float:left; margin:5px; min-height:200px; overflow:visible}.upimg table td{height:175px}td.tdimg{height:175px; max-width:100px; overflow:hidden}td.tdimg .fs{height:198px; max-width:100px; overflow:hidden}td.tdimg .fs .src{max-height:135px; max-width:100px; overflow:hidden}</style>
<input name="UploadEmailedImages2[]" id="file_upload_EmailedImages2" multiple=multiple type="file"  />
</div>
<script type='text/javascript'>
<?
$formdata = "'signid'   : '". $content["details"]->signid."','ordertype'   : 'signdoctor'";
builduploader('file_upload_EmailedImages2','15MB',$formdata,'true',base_url() . 'index.php/home/addphoto/'.$content["order_details"]->orderid,"Upload Sign Doctor Photo",200);
?>
response = $.parseJSON(data);
		if(response && response.FileExist!=true){
				$("#signdoctor").show();
				var res = response.filename.split("."); 
				var imgid = 'signdoctor_'+res[0].replace(/\s+/g, ''); 
				var str = "";
				 str += "<div id='"+imgid+"' style='padding: 5px 5px 5px 5px;border:0px solid red;width:30%;float:left;text-align:center;'>";
				 str += "<a class='fancybox' rel='fancybox-button' href='"+response.embed+"' title='Sign Doctor : "+response.uptime+"'>";
				 str += "<img src='"+response.thumbembed+"' alt='"+response.uptime+"' title='"+response.uptime+"' width='100px' height='100px'>";
				 str += "</a>";
				 str += "<br>	<span>"+response.uptime+"</span>";
				 str += "<br><div class='dialog'><a href='javascript:;' title='Remove' onclick='remove_img(<?php echo $signid; ?>,\"signdoctor\",\""+imgid+"\",\""+response.filename+"\")' class='close-thik'></a></div>";
				 str += "</div>";
				 $('#signdoctor_inner').append(str);
				//window.location.reload();				
		}else{
			alert("The File aleady exists so it was not uploaded again")
		}
   }
});
});
</script>
<br>
<div style="display: block;" id="uploadbulka">
<style type="text/css">.upimg{float:left; margin:5px; min-height:200px; overflow:visible}.upimg table td{height:175px}td.tdimg{height:175px; max-width:100px; overflow:hidden}td.tdimg .fs{height:198px; max-width:100px; overflow:hidden}td.tdimg .fs .src{max-height:135px; max-width:100px; overflow:hidden}</style>
<input name="UploadEmailedImages4[]" id="file_upload_EmailedImages4" multiple=multiple type="file"  />
</div>
<script type='text/javascript'>
<?
$formdata = "'signid'   : '". $content["details"]->signid."','ordertype'   : 'installinstructions'";
builduploader('file_upload_EmailedImages4','15MB',$formdata,'true',base_url() . 'index.php/home/addphoto/'.$content["order_details"]->orderid,"Upload Install Instruction Photo",200);
?>
response = $.parseJSON(data);
		if(response && response.FileExist!=true){
				$("#installinstructions").show();
				var res = response.filename.split("."); 
				var imgid = 'installinstructions_'+res[0].replace(/\s+/g, ''); 
				var str = "";
				 str += "<div id='"+imgid+"' style='padding: 5px 5px 5px 5px;border:0px solid red;width:30%;float:left;text-align:center;'>";
				 str += "<a class='fancybox' rel='fancybox-button' href='"+response.embed+"' title='Sign Doctor : "+response.uptime+"'>";
				 str += "<img src='"+response.thumbembed+"' alt='"+response.uptime+"' title='"+response.uptime+"' width='100px' height='100px'>";
				 str += "</a>";
				 str += "<br>	<span>"+response.uptime+"</span>";
				 str += "<br><div class='dialog'><a href='javascript:;' title='Remove' onclick='remove_img(<?php echo $signid; ?>,\"installinstructions\",\""+imgid+"\",\""+response.filename+"\")' class='close-thik'></a></div>";
				 str += "</div>";
				 $('#InstallInstructions_inner').append(str);
				//window.location.reload();				
		}else{
			alert("The File aleady exists so it was not uploaded again")
		}
   }
});
});
function remove_img(sid,arg,imgid,img){
	var r=confirm("Are you sure to remove?");
	if(r){
		$.ajax({
               type : 'post',
               url : '<?=base_url()?>index.php/home/removeinstallerphotos',
               data : {signid : sid,type:arg,image:img},
               success : function(data){
						//(imgid)
						$("#"+imgid).remove();
						var sd =$("#"+arg+"_inner").html();
						//alert(sd)
						if(!$.trim(sd)){
							$("#"+arg).hide();
						}
					}
		});
	}
}
</script>

<?php }?>
<?php //} ?>
<!--  Installer Block Photos -->
                          </td>
									 
									 
									 
									 
                            <td align="left" valign="top">
                            <?php if($this->input->get('sign_type') != 'dyo'){ 
										$q = "SELECT * FROM templates WHERE templateid = '".$content['details']->templateid."'";
										$template_details = $this->db->query($q);
										$template_detail = $template_details->row(); 
							?>
                            <div style="width:100%; margin:0 auto; text-align:left">
                                <?php
										  //echo "###".$template_detail->bypassproof;
										  if($template_detail->bypassproof != '1'){	?>
                                    <span class="boldy">Uploaded Photos:</span> <span style="font-style:italic">* Please upload any emailed images</span>
												<div class="clear"></div>
                                        <?
													 $ext = explode(".",$content['details']->fullimagename);
                                                $Files = array_filter(ReturnFileArrayFromDB(-1,$content['details']->signid));
																$image_src= array();
                                                foreach ($Files as $FileinDirectory) {
                                                    if ($FileinDirectory['quality'] == true || $ext[1] == 'pdf') {
                                                        $goodbad = '<img src="'.$this->config->item("site_url").'assets/img/tick.gif">';
                                                    } else {
                                                        $goodbad = '<img src="'.$this->config->item("site_url").'assets/img/cross.gif">';
                                                    }
                                                    
                                                    if($FileinDirectory['fileNoExt'] == 'pdf'){
                                                    	$src = IMG.'pdf_preview_not_available.jpg';
                                                    }else{
                                                    	$src = $FileinDirectory['thumbembed'];
                                                    }                                                    
                                                    
																	 $image_src[]["href"] = $FileinDirectory['embed'];
																	 ///$image_src["title"] = $src;
                                                    
																	 ?>
                                                    <div class="upimg" id="trimage-<?=$FileinDirectory['id']?>" style="">
																	 <table class="inner-table"><tbody><tr><td valign="bottom" align="center" class="tdimg">
																	 <div class="fs">
																		  
																	 <?php
																	 $img_info = "";
                                                    if ($content['details']->proofaddon) {
																		  $img_info .="Sign Use: ".$FileinDirectory['usefor']."<br>";
																		  $img_info .="Bro Use: ".$FileinDirectory['usefor2']."<br>";
                                                    } else {
																		  $img_info .="Sign Use: ".$FileinDirectory['usefor']."<br>";
																		  $img_info .="Bro Use: ".$FileinDirectory['usefor2']."<br>";
                                                    }
																	 $img_info .="Up: ".$FileinDirectory['uploadedby']."<br>";
                                                    $img_info .="Up Time: ";
																	 if(isset($FileinDirectory['uplaodedtime'])){
																		  $img_info .=date("d M Y h:i:s a", strtotime($FileinDirectory['uplaodedtime']));
																	 }else{
																				$img_info .="N/A";
																	 }
																	 
																	 //echo $FileinDirectory['embed']
																	 //echo $FileinDirectory['fileNoExt'] ;
																	 ?>
																		  
																    <?php
																	 //if($FileinDirectory['fileNoExt']=="pdf"){
																	 /*
																	 ?>
																	 <a class="fancybox-button"  rel="fancybox-button" data-fancybox-type="iframe" href="<?=$FileinDirectory['embed']?>" title="<?php echo $img_info;?>">
																		  <img src="<?=$src?>" height="120px" width="120px" class="src">
																	 </a><br/>
																	 <?php //}else{ */?>
																		  <a target="_blank" href="<?=$FileinDirectory['embed']?>">
																	 <?php /*<a class="fancybox-button" rel="fancybox-button" href="<?=$FileinDirectory['embed']?>" title="<?php echo $img_info;?>"> */ ?>
																		  <img src="<?=$src?>" height="100px" width="100px" class="src">
																	 </a><br/>
																		  <?php
																	 //}?>
																	 
																	 
																		  
                                                    <?php echo $img_info;?>
                                                    <input type="hidden" name="filename-<?=$FileinDirectory['id']?>" id="filename-<?=$FileinDirectory['id']?>" value="<?=$FileinDirectory['filename']?>">
                                                    <br /><?=$goodbad?><a class="deleter"  onclick="deleteImage('trimage-<?=$FileinDirectory['id']?>')"><img border="0" src="<?=IMG?>deleter.jpg"></a></div></td></tr></tbody></table></div>
																	 <?
                                                }
                                                
                                            
                                            ?>
														  
														  <!--a class="fancybox" href="javascript:;">Open gallery</-->
														  
                                        <div id="files2"></div>
                                            <div class="clear spacer-20"></div>
                                        <div style="display: block;" id="uploadbulk">
                                            <style type="text/css">
                                                .upimg{float:left; margin:5px; min-height:200px; overflow:visible}
                                                .upimg table td{height:175px}
                                                td.tdimg{height:175px; max-width:100px; overflow:hidden}
                                                td.tdimg .fs{height:198px; max-width:100px; overflow:hidden}
                                                td.tdimg .fs .src{max-height:135px; max-width:100px; overflow:hidden}
                                            </style>
                                            
                                            <?php print form_open_multipart('agencies/broorderupload',array('id'=>'addphotoform')) ?>
                                            <input name="UploadEmailedImages[]" id="file_upload_EmailedImages" multiple=multiple type="file" />
                                            </form>
                                        </div>
                                        <?php
										  } ?>
                                </div>
                            <?php } ?>
                            	<table cellspacing="1" cellpadding="2" border="0" width="100%" class="inner-table">
                                	
                                    <tr>
                                      <td>
                                      	

                                      </td>
                                    </tr>
                                    <tr height="20"><td></td></tr>
                                    <tr><td bgcolor="#043465" align="center"><span class="boldy white">Sign Details</span></td></tr>
                                    <tr><td>
									<form name="editordersform" id="editordersform" enctype="multipart/form-data" method="post" action="<?=base_url()?>index.php/home/qryeditorders">
									<?php /*?><?=form_open_multipart('home/qryeditorders',array('id'=>'editordersform'))?><?php */?>
									<!--form method="POST" action="index.php?page=qryeditorders.php" enctype="multipart/form-data" id="editordersform" name="editordersform"-->
									<table width="100%" cellpadding="2" cellspacing="3" align="center">
                                        	
                                         <input type="hidden" name="txt_changes" id="txt_changes" >
                                             <input type="hidden" name="signid" value="<?=$content['details']->signid; ?>">
                                             <input type="hidden" name="numberofimages" value="<?=$content['details']->numberofimages; ?>"> 
                                             <input type="hidden" name="includeagentdetails" value="<?=$content['details']->includeagentdetails?>"> 
                                             <input type="hidden" name="signtypeid" value="<?=$content['details']->signtypeid?>"> 
                                             <input type="hidden" name="signmodelid" value="<?=$content['details']->signmodelid?>"> 
                                             <input type="hidden" name="templateid" value="<?=$content['details']->templateid?>"> 
                                             <input type="hidden" name="auctionInstalled" value="<? //$content['details']->auctionInstalled?>">
                                             <input type="hidden" name="status" value="<?=$content['details']->status?>"> 
                                             <input type="hidden" name="orderid" value="<?=$content['order_details']->orderid?>">       
                                             <input type="hidden" name="ordertype" value="<?=$content['order_details']->ordertype ?>">       
                                             <input type="hidden" name="orderdescription" value="<?=$content['order_details']->description ?>">       
                                             <input type="hidden" name="agencyid" value="<?=$content['details']->agencyid?>">       
                                             <input type="hidden" name="agentid" value="<?=$content['details']->agentid?>">
															<input type="hidden" name="agentid2" value="<?=$content['details']->agentid2?>">       
                                             <input type="hidden" name="price" value="<?=$content['order_details']->price?>">
                                             <input type="hidden" name="sign_type" value="<?php echo $this->input->get('sign_type');?>" />
                                        	<tr>
                                            	<td style="border:5px #fff solid" align="center" bgcolor="#F4F4F4" width="49%"><span class="boldy">Sign Model: </span></td>
                                                <td style="border:5px #fff solid" align="center" bgcolor="#F4F4F4" width="49%"><span class="boldy">Template:</span></td>
                                           </tr>
                                           	<tr>
                                           		<td style="border:5px #fff solid; vertical-align:middle" align="center" bgcolor="#F4F4F4"  valign="middle">
													 
													 <?php
																	 if($content['details']->custom_image){
																		  $imgsrc = "data:image/jpeg;base64,".base64_encode($content['details']->custom_image);
																	 }else{
																		  $imgsrc =  $this->config->item("site_url")."signmodels/".$content['details']->signmodelid."/".$content['details']->modelimage;
																	 }
																	 ?>
																	 <img src="<?=$imgsrc?>" border="0">
																	 <!--img src="<?=$this->config->item("site_url");?>signmodels/<?=$content['details']->signmodelid; ?>/<?=$content['details']->modelimage?>" border="0"-->
													 <br><?=$content['details']->signmodel; ?>
													 
													 
													 <?php if($this->session->userdata('login_admin') == "yes"){?>
                                               <br/><br/><p><a class="button" onclick="changeSignModel(<?=$content['order_details']->signid?>)">Change</a></p>
                                             <?php }?></td>
                                                <td style="border:5px #fff solid" align="center" bgcolor="#F4F4F4"  valign="middle"><?php if($this->input->get('sign_type') != 'dyo'){?><img src="<?=$this->config->item("site_url");?>templates/<?= $content['details']->templateid; ?>/example/<?=$content['details']->examplename?>" border="0"><?php } else{?><img src="<?=PATH_DYO;?>_imagefiles/templates/<?=$content['details']->examplename?>/mockupimage/mockup_preview.<?php echo $content['details']->fileextension;?>" border="0" width="150"><?php }?></td>
                                           </tr>
                                           	<tr height="20">
										   		<td colspan="2"></td>
											</tr>
                                           <?
$q = "SELECT * FROM signfields WHERE signtypeid = '".$content['details']->signtypeid."' AND signmodelid = '".$content['details']->signmodelid."'";
											$signfields = $this->db->query($q);
											$signfield = $signfields->row();
											//var_dump($signfield);
										   ?>
                                           <tr>
                                             <td nowrap><span class="boldy">Sign Status: </span></td>
                                             <td nowrap><?=$content['details']->status?></td>
                                           </tr>

                                           <tr>
                                             <td nowrap><span class="boldy">Agency:</span></td>
                                             <td nowrap><a href="<?=base_url()?>index.php/agencies/viewagency/<?=$content['details']->agencyid?>"><?=$content['details']->agencyname?></a></td>
                                           </tr>
                                        

                                           <?php /*?><tr>
                                             <td nowrap><span class="boldy">Agent:</span></td>
                                             <td nowrap><a href="mailto:<?=$content['details']->agentemail?>" title="Email this agent"><?=$content['details']->agentname." ".$content['details']->agentsurname ?></a><input type="hidden" name="Agent_Name" value="<?=$content['details']->agentname." ".$content['details']->agentsurname?>"></td>
                                           </tr>
														 
														 <? if($signfield->display_agents_details == '1') { ?>
                                           <tr>
                                             <td nowrap><span class="boldy">Agents details to appear on sign: </span><td><?=$content['details']->includeagentdetails?></td>
                                            </tr>
                                           
                                        <? } ?>
														 <?php */?>
<!--  /******Get Agents Details***/-->
<?php 

//echo "<pre>";print_r($content["agentdetails"]);echo "</pre>";

if($this->input->get('sign_type') != 'dyo'){?>
   <tr>
      <td nowrap><span class="boldy">Agent:</span></td>
      <td nowrap>
      	<a href="mailto:<?=$content['details']->agentemail?>" title="Email this agent">
			<?=$content['details']->agentname." ".$content['details']->agentsurname ?></a>
         <input type="hidden" name="Agent_Name" value="<?=$content['details']->agentname." ".$content['details']->agentsurname?>"></td>
   </tr><?
	$includeagentdetails = str_replace("<br>","",$content['details']->includeagentdetails);
	if($signfield->display_agents_details == '1' && trim($includeagentdetails)!='') { ?>
      <tr>
         <td nowrap><span class="boldy">Agentsdetails to appear on sign: </span><td><?=$content['details']->includeagentdetails?></td>
      </tr><? 
   }  
	if($content['agentdetails']){ ?>
      <tr>
         <td nowrap><span class="boldy">Agent2:</span></td>
         <td nowrap>
            <a href="mailto:<?=$content['agentdetails']->email?>" title="Email this agent">
            <?=$content['agentdetails']->name." ".$content['agentdetails']->surname ?></a>
            <input type="hidden" name="Agent_Name2" value="<?=$content['agentdetails']->name." ".$content['agentdetails']->surname?>"></td>
      </tr><?
		$includeagentdetails2 = str_replace("<br>","",$content['details']->includeagentdetails2);
      if($signfield->display_agents_details == '1' && $includeagentdetails2!='') { ?>
         <tr>
            <td nowrap><span class="boldy">Agent2 details to appear on sign: </span><td><?=$content['details']->includeagentdetails2?></td>
         </tr><? 
      }
	}
}else{ 
	 if($content['agentdetails'][0]){
		  ?>
		  <tr>
			  <td nowrap><span class="boldy">Agent:</span></td>
			  <td nowrap>
				  <a href="mailto:<?=$content['agentdetails'][0]->email?>" title="Email this agent">
				  <?=$content['agentdetails'][0]->name." ".$content['agentdetails'][0]->surname ?></a>
				  <input type="hidden" name="Agent_Name" value="<?=$content['agentdetails'][0]->name." ".$content['agentdetails'][0]->surname?>"></td>
		  </tr>
		  <?
	 }
	 if($content['agentdetails'][1]){
		  ?>
		  <tr>
			  <td nowrap><span class="boldy">Agent2:</span></td>
			  <td nowrap>
				  <a href="mailto:<?=$content['agentdetails'][1]->email?>" title="Email this agent">
				  <?=$content['agentdetails'][1]->name." ".$content['agentdetails'][1]->surname ?></a>
				  <input type="hidden" name="Agent_Name" value="<?=$content['agentdetails'][1]->name." ".$content['agentdetails'][1]->surname?>"></td>
		  </tr>
		  <? 
	 }
?>

<?php } ?>
<!--  /******Get Agents Details***/-->                                  
                                           
											<tr>
                                             <td nowrap><span class="boldy">Sign Type: </span></td>
                                             <td nowrap><?=$content['details']->signtype?></td>                                             
                                           </tr>
                                           <?
														 if($this->input->get('sign_type') != 'dyo'){
														 
														 if($signfield->website_url == '1' || $signfield->website_id == '1'){?>
														 <tr>
                                             <td colspan="2" nowrap>
																<?php //print_r($signfield); ?>
																<HR noshade></td>
                                            </tr>
                                           
                                                <?
														}
														
														
														 
                                                 if($signfield->website_url == '1'){?>
                                                <tr>
                                                  <td nowrap><span class="boldy">Show Website URL?</span>
                                                  <br>
                                                  
                                                  <?php
																  $we =  stripslashes(htmlspecialchars( $content['details']->website));
																  ?>
                                                  
                                                  </td>
                                                  <td nowrap><input type="text" class="input" name="Website" size="30" id="vf#" value="<?php echo $we; ?>"></td>
                                                </tr>
                                                <? } ?>
                                                <? if($signfield->website_id == '1'){?>
                                                <tr>
                                                  <td nowrap><span class="boldy">Website ID#: </span>
																  <?php
																  $weid = stripslashes(htmlspecialchars($content['details']->websiteid));
																  ?>
																  </td>
                                                  <td nowrap><input type="text" class="input" name="WebsiteID" size="30" maxlength='255' id="vf#" value="<?php echo $weid; ?>"></td>
                                                </tr>
                                                <? }
														 }?>
                                           
                                           
        <?
		  if($this->input->get('sign_type') != 'dyo'){
				if($signfield->property_address == '1') { ?>
					 <tr><td colspan="2" nowrap><HR noshade></td></tr>
					 <tr><td nowrap><span class="boldy">Property Address:</span></td><td nowrap><?php
							  //$content['details']->streetaddress = stripslashes(htmlentities($content['details']->streetaddress,ENT_QUOTES));
							  $content['details']->streetaddress = stripslashes(htmlspecialchars($content['details']->streetaddress));	?>
							  <input type="text" class="input" name="StreetAddress" size="30" value="<?=($content['details']->streetaddress)?>" id="vf#m">
							  <font COLOR="#990000">*</font></td></tr><?
				}
				if($signfield->property_address_2 == '1') { ?>
					 <tr><td nowrap><span class="boldy">Property Address 2:</span></td>
						  <td nowrap>
								<input type="text" class="input" name="StreetAddress2" size="30" value="<?=stripslashes(htmlspecialchars($content['details']->streetaddress2))?>" id="vf#"></td>
					 </tr><?
				}
				if($signfield->property_suburb == '1') { ?>
					 <tr><td nowrap><span class="boldy">Property Suburb:</span></td>
						  <td nowrap><input type="text" class="input" name="StreetSuburb" size="30" value="<?=stripslashes(htmlspecialchars($content['details']->streetsuburb))?>" id="vf#m"> <font COLOR="#990000">*</font></td>
					 </tr><?
				}
				if($signfield->property_postcode == '1') { ?>
					 <tr><td nowrap><span class="boldy">Property Postcode:</span></td>
						  <td nowrap><input name="StreetPostcode" type="text" class="input" id="vf#n" value="<?=$content['details']->streetpostcode?>" size="4" MAXLENGTH="4"></td>
					 </tr><?
				}
		  }else{	?>
				<tr><td colspan="2" nowrap><HR noshade></td></tr>
				<tr><td nowrap><span class="boldy">Property Address:</span></td><td nowrap><?php
						 //$content['details']->streetaddress = stripslashes(htmlentities($content['details']->streetaddress,ENT_QUOTES));
						 $content['details']->streetaddress = stripslashes(htmlspecialchars($content['details']->streetaddress));	?>
						 <input type="text" class="input" name="StreetAddress" size="30" value="<?=($content['details']->streetaddress)?>" id="vf#m">
						 <font COLOR="#990000">*</font></td></tr>
				<tr><td nowrap><span class="boldy">Property Address 2:</span></td>
					 <td nowrap>
						  <input type="text" class="input" name="StreetAddress2" size="30" value="<?=stripslashes(htmlspecialchars($content['details']->streetaddress2))?>" id="vf#"></td>
				</tr>
				<tr><td nowrap><span class="boldy">Property Suburb:</span></td>
					 <td nowrap><input type="text" class="input" name="StreetSuburb" size="30" value="<?=stripslashes(htmlspecialchars($content['details']->streetsuburb))?>" id="vf#m"> <font COLOR="#990000">*</font></td>
				</tr>
				<tr><td nowrap><span class="boldy">Property Postcode:</span></td>
					 <td nowrap><input name="StreetPostcode" type="text" class="input" id="vf#n" value="<?=$content['details']->streetpostcode?>" size="4" MAXLENGTH="4"></td>
				</tr><?php
		  }
		  ?>
		  
		  
		  <? if($signfield->header_title == '1'  && $this->input->get('sign_type') != 'dyo') { ?>
                                           <tr>
                                             <td colspan="2" nowrap><HR noshade></td>
                                           </tr>
                                           <tr>
                                             <td nowrap><span class="boldy">HeaderTitle :</span>

                                             </td>
                                             <td nowrap><input type="text" class="input" name="HeaderTitle" size="30" value="<?=stripslashes(htmlspecialchars($content['details']->headertitle))?>" id="vf#m" <?php if($this->input->get('sign_type') == 'dyo'){echo 'readonly="true"';}?>>
                                                 <font COLOR="#990000">*</font></td>
                                           </tr>
                                        <? } ?>
                                           
                                         
                                         <?  if($this->input->get('sign_type') != 'dyo'){  
									   if($signfield->four_point_description == '1') { 
												list($description1,$description2,$description3,$description4) = split("<br>",$content['details']->description);
                                                //list($description1,$description2,$description3,$description4) = explode("<br />",$content['details']->description);
										 ?>
											<tr>
											  <td colspan="2"><span class="boldy">Four Point Description:</span> <font COLOR="#990000">*</font>
												<table  border="0" cellpadding="1" cellspacing="2" class="tableresults">
												  <tr>
													<td><span class="boldy" style="color: #fff">1</span></td>
													<td><input name="Description1" id="Description1" type="text" class="input" value="<?= $description1; ?>" size="50" onBlur="make_description();"></td>
												  </tr>
												  <tr>
													<td><span class="boldy" style="color: #fff">2</span></td>
													<td><input name="Description2" id="Description2" type="text" class="input" value="<?= $description2; ?>" size="50" onBlur="make_description();"></td>
												  </tr>
												  <tr>
													<td><span class="boldy" style="color: #fff">3</span></td>
													<td><input name="Description3" id="Description3" type="text" class="input" value="<?= $description3; ?>" size="50" onBlur="make_description();"></td>
												  </tr>
												  <tr>
													<td><span class="boldy" style="color: #fff">4</span></td>
													<td><input name="Description4" id="Description4" type="text" class="input" value="<?= $description4; ?>" size="50" onBlur="make_description();"></td>
												  </tr>
												</table>
												  <input name="Description" id="Description" type="hidden" value="<?=$content['details']->description ?>"></td>
											</tr>
									<? } ?>
									<? if($signfield->description == '1') { ?>
									   <tr>
										 <td colspan="2" nowrap><span class="boldy">Description:</span><font COLOR="#990000">*</font><br /><div class="spacer-5"></div>
										   <textarea name="Description" cols="60" rows="20" wrap="VIRTUAL" style="height:100px" class="input" id="vf#mdsc"><?=stripslashes(htmlspecialchars($content['details']->description)) ?></textarea>
										   <br>
										  <div align="center" style="display:none"><a href="#" onClick="copyText(document.getElementById('vf#mdsc')); return false;">click here to copy this text to the clipboard</a></div></td>
										</tr>
									<? } ?>
									<? if($signfield->open_times == '1') { ?>
									   <tr>
										 <td colspan="2" nowrap><span class="boldy">Open Times:</span><br />  <div class="spacer-5"></div>
										  <textarea name="OpenTimes" cols="60" rows="3" style="height:100px" wrap="VIRTUAL" class="input" id="vf#"><?=stripslashes(htmlspecialchars($content['details']->opentimes)) ?></textarea>                  </td>
										</tr>
									<? } ?>
									<? if($signfield->number_of_images == '1') { ?>				
									   <tr>
										 <td nowrap><span class="boldy">Number of images: </span></td>
										 <td nowrap><?=$content['details']->numberofimages?></td>
									   </tr>
									<? } ?>
									<? if($signfield->bedroom_no == '1') { ?>				
									   <tr>
										 <td nowrap><span class="boldy">Bedroom No:</span></td>
										 <td nowrap><input name="BedroomNo" type="text" class="input" id="vf#n" value="<?=$content['details']->bedroomno ?>" size="1" MAXLENGTH="1"></td>
									   </tr>
									<? } ?>
									<? if($signfield->bathroom_no == '1') { ?>				
									   <tr>
										 <td nowrap><span class="boldy">Bathroom No:</span></td>
										 <td nowrap><input name="BathroomNo" type="text" class="input" id="vf#n" value="<?=$content['details']->bathroomno?>" size="1" MAXLENGTH="1"></td>
									   </tr>
									<? } ?>
									<? if($signfield->carspace_no == '1') { ?>				
									   <tr>
										 <td nowrap><span class="boldy">Carspace No:</span></td>
										 <td nowrap><input
									  name="CarspaceNo" type="text" class="input" id="vf#n" value="<?=$content['details']->carspaceno?>" size="1" MAXLENGTH="1">                  </td>
									   </tr>
									<? } }?>
									
									<? if($signfield->tender_date == '1') { ?>				
									
                                       <? //$tenderdate = split("-",$content['details']->tenderdate); 
									   	  $tenderdate = preg_split("/[-,]+/",$content['details']->tenderdate);
									   ?>
									   <tr>
										 <td colspan="2" nowrap><HR noshade></td>
									   </tr>
									   <tr>
										 <td nowrap><span class="boldy">Tender date:</span></td>
										 <td nowrap><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="text">
											 <tr>
											   <td nowrap>
                                               <SELECT name="Tender_Day" class="input" id="vf#">
												   <OPTION value="" selected>Day</OPTION>
												   <?
												for($i=1; $i<=32; $i++){
													echo "<option value=\"$i\"";
													echo ($i==$tenderdate[2])?" selected":"";
													echo ">$i</option>\n";
													}
												?>
												</SELECT>
                                               </td>
											   <td nowrap>
                                               <SELECT name="Tender_Month" class="input" id="vf#">
												   <OPTION value="" selected>Month</OPTION>
												   <?
												for($i=1; $i<=12; $i++){
													echo "<option value=\"$i\"";
													echo ($i==$tenderdate[1])?" selected":"";
													echo ">$i</option>\n";
													}
												?>
											   </SELECT>
                                               </td>
											   <td nowrap><SELECT name="Tender_Year" class="input" id="vf#">
												   <OPTION value="" selected>Year</OPTION>
												   <?
												for($i=2005; $i<=2020; $i++){
													echo "<option value=\"$i\"";
													echo ($i==$tenderdate[0])?" selected":"";
													echo ">$i</option>\n";
													}
												?>
												 </SELECT>
                                                 </td>
											 </tr>
										 </table></td>
									   </tr>
									<? } ?>
									
									<? if($this->input->get('sign_type') != 'dyo'){  
									if($signfield->auction_date == '1') { ?>				
									   <? //$auctiondate = split("-",$content['details']->auctiondate); 
									   	  $auctiondate = preg_split("/[-,]+/",$content['details']->auctiondate);
									   ?>
									   <tr>
										 <td colspan="2" nowrap><HR noshade></td>
									   </tr>
									   <tr>
										 <td nowrap><span class="boldy">Auction date:</span></td>
										 <td nowrap><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="text">
											 <tr>
											   <td nowrap><SELECT name="Auction_Day" class="input" id="vf#">
												   <OPTION value="" selected>Day</OPTION>
												   <?
												for($i=1; $i<=32; $i++){
													echo "<option value=\"$i\"";
													echo ($i==$auctiondate[2])?" selected":"";
													echo ">$i</option>\n";
													}
												?>
												 </SELECT>                       </td>
											   <td nowrap><SELECT name="Auction_Month" class="input" id="vf#">
												   <OPTION value="" selected>Month</OPTION>
												   <?
												for($i=1; $i<=12; $i++){
													echo "<option value=\"$i\"";
													echo ($i==$auctiondate[1])?" selected":"";
													echo ">$i</option>\n";
													}
												?>
											   </SELECT></td>
											   <td nowrap><SELECT name="Auction_Year" class="input" id="vf#">
												   <OPTION value="" selected>Year</OPTION>
												   <?
												for($i=2005; $i<=2020; $i++){
													echo "<option value=\"$i\"";
													echo ($i==$auctiondate[0])?" selected":"";
													echo ">$i</option>\n";
													}
												?>
												 </SELECT>
                                                 </td>
											 </tr>
										 </table></td>
									   </tr>
									<? } ?>
									<? if($signfield->auction_time == '1') { ?>				
									   <tr>
										 <td nowrap><span class="boldy">Auction time: </span></td>
										 <td nowrap><input type="text" class="input" name="AuctionTime" size="30" value="<?=$content['details']->auctiontime ?>" id="vf#"></td>
									   </tr>
									<? } ?>
									<? if($signfield->auction_address == '1') { ?>				
									  <tr>
										 <td nowrap><span class="boldy">Auction address:</span></td>
										 <td nowrap><input type="text" class="input" name="AuctionAddress" size="30" value="<?=stripslashes(htmlspecialchars($content['details']->auctionaddress)) ?>" id="vf#">                  </td>
									   </tr>
									<? } ?>
									<? if($signfield->auction_address_2 == '1') { ?>				
									   <tr>
										 <td nowrap><span class="boldy">Auction address 2:</span></td>
										 <td nowrap><input type="text" class="input" name="AuctionAddress2" size="30" value="<?=stripslashes(htmlspecialchars($content['details']->auctionaddress2)) ?>" id="vf#"></td>
									   </tr>
									<? } ?>
									<? if($signfield->auction_suburb == '1') { ?>				
									   <tr>
										 <td nowrap><span class="boldy">Auction Suburb:</span></td>
										 <td nowrap><input type="text" class="input" name="AuctionSuburb" size="30" value="<?=stripslashes(htmlspecialchars($content['details']->auctionsuburb))?>" id="vf#"></td>
									   </tr>
									<? } }?>
                                         
                                    <?php										
									
									 if($content['details']->status!="Pending Order") { ?>
                                   <tr>
                                     <td colspan="2" nowrap><HR noshade></td>
                                   </tr>
                                  
                                    <?
                                    if($template_detail->bypassproof != '1'){
                                    ?>
                                 <?php /*?>  <tr>
                                     <td nowrap><span class="boldy">Upload Proof JPEG: </span><br></td>
                                     <td nowrap><input name="image" type="file" class="input" size="20" style="height:21px" accept="image/jpeg" id="jpgproof"></td>
                                   </tr><?php */?>
                                
                                   <tr align="center">
                                     <td colspan="2" nowrap><? 
                                                $uploaddir = UPLOADS_files.'signimages/'.$content['details']->signid;
												$subuploaddir = $uploaddir.'/proof/';
												$thumbuploaddir = $uploaddir."/thumb/";
												$path = "$subuploaddir";
										
										if (!empty($content['details']->imagename)){
										$arcfolder = 'arc' . substr($content['details']->signid, 0, 2) . "0000";
         
                                        if (file_exists(UPLOADS_files.'signimages/'.$content['details']->signid."/image/proof/".$content['details']->imagename)) {
                                        ?>
                                         <img src="<?=$this->config->item("site_url").'signimages/'.$content['details']->signid."/image/proof/".$content['details']->imagename?>?d=<?=time();?>" width="300"  border="0" alt="#">
                                         <?
                                         } elseif (file_exists(UPLOADS_files.'signimages/Archive/'.$arcfolder.'/'.$content['details']->signid."/image/proof/".$content['details']->imagename)) {
                                          ?>
                                         <img src="<?=$this->config->item("site_url").'signimages/Archive/'.$arcfolder.'/'.$content['details']->signid."/image/proof/".$content['details']->imagename?>?d=<?=time();?>" width="300"  border="0" alt="#">
                                         <?
                                         }
                                        }
                                        else if($this->input->get('sign_type') == 'dyo' && file_exists(DYO_PDF."proof/agent-proof-".$content['details']->signid.".jpg"))
                                        {?>
											<img src="<?php echo PATH_DYO; ?>temppdf/proof/agent-proof-<?php echo $content['details']->signid?>.jpg" width="300"  border="0" alt="#">
										<?php }
                                        else
                                        {
                                        ?>
                                         <br />
                                         <img src="<?=IMG?>/images/artworkinprogress.jpg" width="300" height="210">
                                         <?
                                        }
                                    ?></td>
                                   </tr>
                                   <?php
									 //if($this->input->get('sign_type') != 'dyo'){
									 if(!file_exists(DYO_PDF.'agent-proof-'.$content["details"]->signid.'.pdf')){	?>
                                   <tr>
                                     <td colspan="2" nowrap><HR noshade></td>
                                   </tr>
                                   <tr>
                                     <td nowrap><span class="boldy">Upload Proof PDF:</span></td>
                                     <td nowrap><input name="pdf" type="file" class="input" size="20" style="height:21px" accept="application/pdf" id="pdfproof" onchange="checkFile();ValidateSingleInput(this);"></td>
                                   </tr>
                                   <tr>
                                     <td nowrap><span class="boldy">&nbsp;</span></td>
                                     <td nowrap><span class="boldy" id="filesizecheck">&nbsp;</span></td>
                                   </tr>
                                   <tr align="center">
                                     <td colspan="2" nowrap class="text">
										<? if ($content['details']->fullimagename != ""){
											print('<a href="'.base_url().'index.php/admin/downloadpdf/'.$content["details"]->signid.'" target="_blank">Click here to view current Full PDF</a>');
                                            print('<br><br><a href="'.base_url().'index.php/admin/downloadagencypdf/'.$content["details"]->signid.'" target="_blank">Click here to view current AGENCY PDF</a>');
											//print('<a href="'.base_url().'/home/downloadpdf/'.$signid.'/'.$file.'" target="_blank">Click here to view current Full Image</a>');
                                        	//print("<a href=\"index.php?page=viewpdf.php&id=".$content['details']->signid."\" target=\"_blank\">Click here to view current Full Image</a>");
                                        	}
                                        ?>
                                    </td>
                                  </tr>
                                    
                                       <?
									 }
								} else {  if($this->input->get('sign_type') != 'dyo'){// else if bypassproof ?>
                                   <tr align="center">
                                     <td colspan="2" nowrap class="text"><h1>NO PROOF AVAILABLE</h1></td>
                                    </tr>
                                       
                                       <? }} ?>

									  <?php if(file_exists(DYO_PDF.'agent-proof-Notes-'.$content["details"]->signid.'.pdf')){?>
                                      <tr>
                                        <td colspan="2" align="left" nowrap>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="20%" align="left"><span class="boldy">PDF Proof:</span></td>
                                              <?php if(file_exists(DYO_PDF.'agent-proof-'.$content["details"]->signid.'.pdf')){?>
                                              <td width="30%"><a href="<?php echo PATH_DYO.'temppdf/agent-proof-'.$content["details"]->signid.'.pdf';?>" target="_blank">PDF for Brochures</a></td>
                                              <?php }?><?php if(file_exists(DYO_PDF.'agent-proof-Notes-'.$content["details"]->signid.'.pdf')){?>
                                              <td width="30%"><a href="<?php echo PATH_DYO.'temppdf/agent-proof-Notes-'.$content["details"]->signid.'.pdf';?>" target="_blank">Click here to view PDF Proof with Notes</a></td>
                                              <?php }?>
                                              <td width="20%" align="left"><a href="<?php echo base_url();?>index.php/home/regenerateDYOPdf/<?php echo $content["details"]->signid;?>">Regenerate PDF</a></td>
                                            </tr>
                                        </table></td>
                                      </tr>
                                      <?php }?>
                                   <? } // endif not Pending Order 
                    					else if ($content['details']->ordermode == 'create'){
                    				?>
                                   <tr align="center">
                                     <td colspan="2" nowrap><? 
                                        if (!empty($content['details']->imagename)){
                                        ?>
                                         <IMG src="<?=$this->config->item("site_url")?>signimages/<?= $signid; ?>/proof/<?=$content['details']->imagename?>" width="300" border="0">
                                         <?
                                        }
                                        else
                                        {
                                        ?>
                                         <br />
                                         <IMG src="<?=IMG?>/images/artworkinprogress.jpg" width="300" height="210">
                                        <?
                                        }
                                    	?>
                                       </td>
                                   </tr>
                                   <tr>
                                     <td colspan="2" nowrap><HR noshade></td>
                                   </tr>
                                    
                    
                                
                                   <tr align="center">
                                     <td colspan="2" nowrap class="text">
													 
													 
									<?php
									
									
									
									if ($content['details']->fullimagename != "")
                                    {
                                    	print("<a href=\"index.php?page=viewpdf.php&id=$signid\" target=\"_blank\">Click here to view generated Full Image</A>");
                                    }
                                    ?>
                                    </td>
                                    </tr>
                                    <? } // endif not Pending Order ?>
                                    <?php
												//if($this->input->get('sign_type') != 'dyo'){ ?>
                                    <tr id="inputs">
                                    	<td colspan="2" align="center">
                                        	<? 
											 if(substr($content['details']->status,0,10)=="(On Hold)"){	
											?> 
                                            <p><span class="boldy"><font color="#CC0000">THIS SIGN IS ON HOLD</font></span></p>
											<?
											} else {
												if($content['details']->status=="Pending Order"){
												  if($template_detail->bypassproof == '1'){ ?>
													  <input class="button" type="submit" value="No Proof Available, send to Printing." />
											<?	  } 
												  else if ($content['details']->ordermode == 'create'){
											?>
											<select name="modechoice">
												<option value='Accept' selected>Accept Generated Image - Send to Print</option>
												<option value='Proof'>Reject Generated Image - Mark Proof in Progress</option>
											</select>
											<input class="button wait_button" type="submit" value="Continue" />
											<?
												  }
												  else {
											?>
                                            <input type="hidden" name="sign_status" value="progress" />
											<input class="button wait_button" type="submit" value="Set sign status to 'Proof in Progress'" />
											<?
												  }
											  }
											
											   if($content['details']->status=="Pending Delivery"){
											?>
											<input class="button wait_button" type="submit" value="Set sign status to 'Installed'" />
											<? } 
											   if($content['details']->status=="Proof in Progress"){
											?>
											<input class="button wait_button" type="submit" value="Upload Proof" />
											<? }
											   if($content['details']->status=="Pending Printing"){ ?>
												  <input class="button wait_button" type="submit" value="Click here when sign has been printed" />
											<? }
											
											
											   if($content['details']->status!="Pending Order" && $content['details']->status!="Pending Printing" &&
													$content['details']->status!="Pending Delivery" && $content['details']->status!="Proof in Progress" &&
													$content['order_details']->ordertype!="Order Overlay Request" && $content['order_details']->ordertype!="Overlay Artwork In Progress" &&
													$content['order_details']->ordertype!="Overlay Artwork Being Printed" &&
													$content['order_details']->ordertype!="Overlay Artwork Pending Delivery"
													&& $content['order_details']->ordertype!="Sign Doctor Request" && $content['order_details']->ordertype!="Remove Sign"
													&& $content['order_details']->ordertype!="Waiting for Approval" && $content['order_details']->ordertype!="Pending Printing"
													
													&& $content['details']->status!="DYO Stock Check"
													//&& $content['order_details']->ordertype=="DYO Help Requested"
													//&& $content['details']->status=="Proof Declined" && $content['order_details']->ordertype=="Proof Declined"
													
													){ ?>
												<input class="button wait_button" type="submit" value="Save Changes" onMouseDown="if((document.getElementById('jpgproof').value != '' && document.getElementById('pdfproof').value == '') || (document.getElementById('jpgproof').value == '' && document.getElementById('pdfproof').value != '')){ alert('You must upload a JPEG proof and a PDF proof together. You cannot upload one without the other.')} else {if(verifyform('editordersform','verify','0')){ this.value='Please Wait'; this.disabled=true; document.getElementById('editordersform').submit();  }}">
											<?
											 }
											 
											 if($content['details']->status=="Overlay Requested" || $content['order_details']->ordertype=="Order Overlay Request"){ ?>
												  <input class="button wait_button" type="submit" value="Confirm overlay artwork is now in progress" />
												  <?
												  }
											
												  if($content['details']->status=="Overlay In Progress" || $content['order_details']->ordertype=="Overlay Artwork In Progress"){
												  ?>
												  <input class="button wait_button" type="submit" value="Confirm overlay artwork ready to be printed" />
												  <?
												  }
											
												  if($content['details']->status=="Overlay Being Printed" && $content['order_details']->ordertype=="Overlay Artwork Being Printed"){
												  ?>
												  <input class="button wait_button" type="submit" value="Confirm overlay artwork printed and ready for delivery" />
												  <?
												  }
												  
												  if($content['details']->status=="Overlay Pending Delivery" && $content['order_details']->ordertype=="Overlay Artwork Pending Delivery"){
												  ?>
												  <input class="button wait_button" type="submit" value="Confirm overlay artwork has been delivered and installed" />
												  <?
												  }
											
											
												  if($content['details']->status=="Sign Doctor Requested" && $content['order_details']->ordertype=="Sign Doctor Request"){
												  ?>
												  <input class="button wait_button" type="submit" value="Confirm Sign Doctor Performed" />

												  <?
												  }
												  
												  if($content['details']->status=="Installed" && $content['order_details']->ordertype=="Sign Doctor Request"){ ?>
												  	<input class="button wait_button" type="submit" value="Confirm Sign Repair" />
												  <?
												  }
												 
												  if($content['details']->status=="Installed" && $content['order_details']->ordertype=="Remove Sign"){
													$currdate = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
													//list($y,$m,$d) = split("-",$content['order_details']->actiondate);
													list($y,$m,$d) = preg_split("/[-,]+/",$content['order_details']->actiondate);
													$actiondate = mktime(0, 0, 0, date($m)  , date($d), date($y));
													//echo "Today date: $currdate<br>Action date: $actiondate<br>";
													if($actiondate <= $currdate){
														?>
														<input class="button" type="submit" value="Confirm Sign Removal" /><?
														} else {
														?>
														<input class="button" type="submit" value="Sign cannot be removed until <?= date("D d M Y", strtotime($content['order_details']->actiondate)); ?>" disabled />
														<?
														}
												  }
												  }
												   ?>
                                        
                                        
                                        </td>
                                    </tr> 
									 <?php //} ?>
                                      </table>
									</form>   
									<? if($this->session->userdata('login_admin') == "yes"){?>  
                                   
									<table width="100%" cellpadding="2" cellspacing="3">
									<tr>
                                     	<td colspan="2" nowrap><hr noshade /></td>
                                   	</tr>    
                                    
									<tr>
                                    	<td colspan="2"><span class="boldy">Sign Status: &nbsp;&nbsp;&nbsp; <?=$content['details']->status?></span>
													<?php
													 if($content['details']->sign_missing==1){ 	//if($content['details']->sign_missing==1 && $content['details']->sign_missing_reason!=""){
														 echo "<span style='color:red'>(MISSING)</span>";
														?>
														<a href="<?=base_url()?>index.php/home/unsetsetmissingsign/<?=$content['order_details']->signid?>/<?=($this->input->get('sign_type')== 'dyo')?$this->input->get('sign_type'):""; ?>">Unset Sign Missing</a>
														<?php
													 }
													 ?>
													</td>
                                    </tr>
                                   	<tr>
                                    	<td colspan="2">
                                        <form name="signstatusback" method="post" action="<?=base_url()?>index.php/home/signback">
                                        	<input type="hidden" name="signid" value="<?=$content['details']->signid; ?>">
                                            <input type="hidden" name="oldstat" value="<?=$content['details']->status; ?>">
                                            <input type="hidden" name="agencyid" value="<?=$content['details']->agencyid?>" />
		                                    <input type="hidden" name="orderid" value="<?=$content['order_details']->orderid ?>" />
                                            <input type="hidden" name="agentid" value="<?=$content['details']->agentid?>"> 
                                            <input type="hidden" name="ordertype" value="<?=$content['order_details']->ordertype?>" />
                                            <input name="sign_type" type="hidden" id="sign_type" value="<?php echo $this->input->get('sign_type');?>" />
                                            
                                        <div style="position:relative">
                                        	 <div style="margin-top:3px; padding:1px; float:left">
											 <?php
                                             	$select_data = "select * from instock where agency_id = '". $content['details']->agencyid."' and agent_id = '". $content['details']->agentid."' and agent_id2 = '". $content['details']->agentid2."' and signmodelid = '". $content['details']->signmodelid."' and signtype = '". $content['details']->signtypeid."'";
										
												$installed_qty_count = 0;
												$total_quantity = 0;
												$check_stock = $this->db->query($select_data);
												foreach ($check_stock->result() as $row)
												{
												$total_quantity = $row->quantity;
												$sid = $row->stock_id;
												}
												
												$installed_qty = "select * from signstock where stockid = '".$sid."'";
												$db_ins_qty = $this->db->query($installed_qty);
												
												$installed_qty_count = count($db_ins_qty->result());
												if($total_quantity-$installed_qty_count>0 && $content['details']->held_in_stock==1 && ($content['details']->status=='Pending Order' || $content['details']->status=='DYO Stock Check')){
													echo '<div id="chStatusBtn"><span class="boldy">You cannot change the status here as the sign is set as In Stock. Please use sign orders page</span><br/><br/>';
													$cstatus = 1;
													echo '<input type="button" value="Okay I understand, let me change the status" id="chStatusBtn" class="button" onclick="return changeStatus();"/></div>';
												}
												else{
													$cstatus = 0;
												}?>
                                                </div>
                                                <div id="chStatus" style="display:<?php if($cstatus == 1){ echo 'none';} else{ echo 'block';}?>">
                                                <span class="boldy">Set Status</span>
                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                 <?php $stat = $content['details']->status;
                                                    
                                                 if (	$content['details']->status == 'Overlay Requested' || $content['details']->status == 'Overlay In Progress' ||
                                                                    $content['details']->status == 'Overlay Artwork Being Printed' || $content['details']->status == 'Overlay Artwork Pending Delivery' ) {
                                                    ?>
                                                 <select name="signback" class="input">
                                                    <option value="">--</option>
                                                    <option <? if($stat=='Pending Order'){echo' selected="selected"';}?> value="Pending Order">Pending Order</option>
                                                                    <!--option <? if($stat=='Waiting for Approval'){echo' selected="selected"';}?> value="Waiting for Approval">Waiting for Approval</option-->
                                                    <?php 
                                                                    if($this->input->get('sign_type')=="dyo"){
                                                                        ?>
                                                       <option <? if($stat=='DYO Help Requested'){echo' selected="selected"';}?> value="DYO Help Requested">DYO Help Requested</option>
                                                                        <option <? if($stat=='DYO Stock Check'){echo' selected="selected"';}?> value="DYO Stock Check">DYO Stock Check</option>
                                                       <?php	
                                                                    }
                                                                    ?>
                                                    <option <? if($stat=='Order Overlay Request'){echo' selected="selected"';}?> value="Order Overlay Request">Order Overlay Request</option>
                                                    <option <? if($stat=='Overlay In Progress'){echo' selected="selected"';}?> value="Overlay In Progress">Overlay In Progress</option>
                                                    <option <? if($stat=='Overlay Artwork Being Printed'){echo' selected="selected"';}?> value="Overlay Artwork Being Printed">Overlay Artwork Being Printed</option>
                                                    <option <? if($stat=='Overlay Artwork Pending Delivery'){echo' selected="selected"';}?> value="Overlay Artwork Pending Delivery">Overlay Artwork Pending Delivery</option>
                                                    <option <? if($stat=='Installed'){echo' selected="selected"';}?> value="Installed">Installed</option>
                                                 </select>

                                                 <?php
                                             } else {
                                             
                                              ?>
                                             <select name="signback" class="input">
                                             	<option value="">--</option>
                                             	<option <? if($stat=='Pending Order'){echo' selected="selected"';}?> value="Pending Order">Pending Order</option>
                                                
                                                
                                                                                                <?php 
																if($this->input->get('sign_type')=="dyo"){
																	?>
                                                   <option <? if($stat=='DYO Help Requested'){echo' selected="selected"';}?> value="DYO Help Requested">DYO Help Requested</option>
																	<option <? if($stat=='DYO Stock Check'){echo' selected="selected"';}?> value="DYO Stock Check">DYO Stock Check</option>
                                                   <?php	
																}
																?>

                                                
																<option <? if($stat=='Waiting for Approval'){echo' selected="selected"';}?> value="Waiting for Approval">Waiting for Approval</option>
                                                <option <? if($stat=='Proof in Progress'){echo' selected="selected"';}?> value="Proof in Progress">Proof in Progress</option>
                                                <option <? if($stat=='Proof Declined'){echo' selected="selected"';}?> value="Proof Declined">Proof Declined</option>
                                                <option <? if($stat=='Pending Printing'){echo' selected="selected"';}?> value="Pending Printing">Pending Printing</option>
                                                <option <? if($stat=='Pending Delivery'){echo' selected="selected"';}?> value="Pending Delivery">Pending Delivery</option>
                                                <option <? if($stat=='Installed'){echo' selected="selected"';}?> value="Installed">Installed</option>
                                             </select>
                                             
                                             <?php }?>
                                             <input type="submit" name='form_update' value="Set Status" class="button" />
                                             </div>
                                           <?php //}?>
                                        </div>
                                        </form>
                                        </td>
                                    </tr>
								
										
									
									<script type="text/javascript">
									 
									 
									 
										var d = $('input:radio[name=extension_period]:checked').val();
										
									  	$('.extension_period').live('click',function(){
											d = $(this).val();
										});
										$('#extend').live('click',function(){

											jConfirm('Do  you want to leave the sign on the property for now?', 'Extend Sign', function(r) {
												if(r==true){
													
													window.location='<?=base_url()?>index.php/agency/extend_install_admin/<?=$content["details"]->signid?>/<?=$content["details"]->agencyid?>/'+ d;
												}
											});
																				
											return false;
										});
										
									  </script>
									
									</table>
                                    <? } ?>
                                      
                                        
                                    </td></tr>
												<?php if($this->session->userdata("login_admin")=='yes'){ ?>
									<tr>
                                     	<td colspan="2" nowrap><hr noshade />
													<input type="button" id='txt_change' value="Save Text Changes" class="button" style='background-color: #00A7FF;border: 2px outset #00A7FF;float: right;margin-top:5px;' />
													</td>
                                   	</tr>
												<?php } ?>
                                </table>
                            </td>
                        </tr>
									
                    </table>
						  
                    
                </div><!--end of form-mid-->
                <div class="form-bottom-02 png"> </div>
                
                <div class="clear"></div>
            </div>
             
	<? //var_dump($content['details']);?>
	
    <br><br>
    <div id="alert"></div>
    <? //var_dump($content['order_details']);?>
    <script type='text/javascript'>
		  $(document).ready(function() {
				$("#txt_change").click(function() {
					 $("#txt_changes").val(true);
					 //alert("ghghg")
					 $("#editordersform").submit();
				});
		  });
		  
		  
        $(".wait_button").click(function () {
          $(this).val("Please Wait");
        });

        var $j = jQuery.noConflict();
        $j(function() {
            $j( "#datepicker" ).datepicker({
        	dateFormat:'dd-mm-yy',
        	minDate: 2,
        	onSelect: function(dateText) {            	
               // $j('#btn-instldate-save').css('display','inline');
             }
        	}
        	);
          });

        
        function changeSignModel(signid){
        	var $j = jQuery.noConflict();

        	$.ajax({
               type : 'post',
               url : '<?=base_url()?>index.php/home/getSignModelAjax',
               data : {signid : signid},
               success : function(data){
                  data = JSON.parse(data);
                  html = '<table><tr><td>Sign Model: </td><td><select name="signmodel" id="smodelid" >';
                  $.each(data, function(i, item ){
                      if(item.signmodelid == '<?=$content['details']->signmodelid?>'){
                    	  html = html + '<option value="'+item.signmodelid+'" selected="selected">'+item.signmodel+'</option>';
                       }else{
                	  		html = html + '<option value="'+item.signmodelid+'">'+item.signmodel+'</option>';
                       }
                  });
              	  
              	  html = html + '</select></td></tr></table>';
              	
	              	$j("#alert").html(html).dialog({
	            	        title: "Change sign model",
	            	        resizable: false,
	            	        modal : true,	            	       
	            	        width : 450,
	            	        height : 200,    	       
	            	        buttons: {
	            	            "Save": function() 
	            	            {	            	            	
	            	            	var signmodelid =    $j('#smodelid').val();	            	            	 

	            	            	 $.ajax({
				            	           type : 'post',
	                                       url : '<?=base_url()?>index.php/home/changeSignModelAjax',
	                                       data : {signid : signid, signmodelid : signmodelid },
	                                       success : function(data){     
		                                      // alert(data);                                   
	                                         location.reload();

	                                        }

				            	        });
	            	                $j( this ).dialog( "close" );      	               
	            	            },
	            	            "Cancel": function() 
	            	            {
	            	                $j( this ).dialog( "close" );      	               
	            	            }
	            	        }
	            	    });

               }
 

             });
        	
      	    return false;
        } 



         function changeSignPrice(){
        	 var $j = jQuery.noConflict();
        	

        	//var html = '<div id="addons-error" style="color:red; font-size: 12px;"></div><table><tr><td>Sign Price: </td><td><input name="signprice" id="signprice" value="<?=$basePrice?>" ></td></tr>';
			var bprice = $j('#base-price').html();
			bprice = bprice.replace("$",""); 
					  var html = '<div id="addons-error" style="color:red; font-size: 12px;"></div><table><tr><td>Sign Price: </td><td><input name="signprice" id="signprice" value="'+bprice+'" ></td></tr>';
        	     html = html + '<tr><td>Descriptions: </td><td><textarea name="description" id="price-change-description" rows="8" cols="40"><?=$desc?></textarea></td></tr></table>';

        	 $j("#alert").html(html).dialog({
     	        title: "Change sign price",
     	        resizable: false,
     	        modal : true,	            	       
     	        width : 550,
     	        height : 350,    	       
     	        buttons: {
     	            "Save": function() 
     	            {	

     	            	var basedesc = $j('#price-change-description').val();
     	            	var baseprice =  $j('#signprice').val();
     	            	var err = '';

                        if(baseprice && basedesc){
	     	            	$.ajax({
		            	           type : 'post',
	                             url : '<?=base_url()?>index.php/home/changeBasePriceAjax',
	                             data : {signid : <?=$content['details']->signid?>, 
	                                      baseprice : baseprice, 
	                                      basedesc: basedesc                                      
		                                 },
	                             success : function(data){    		                                                                         
	                               location.reload();	
	                              }	
		            	        });	         	                       	 
	     	                   $j( this ).dialog( "close" ); 
                        }else{

                        	if(!baseprice){
	            	            	err = err + '- Base price is empty </br>';
 	            	        }

	            	        if(!basedesc){
            	            	err = err + '- Description is empty </br>';
	            	        }
         	            	$j('#addons-error').html(err+'</br>');

                       }     	               
     	            },
     	            "Cancel": function() 
     	            {
     	                $j( this ).dialog( "close" );      	               
     	            }
     	        }
     	    });

         } 
		  
		  function getflag(v){
				
				var af = "<?php echo $content["details"]->agency_flag_holder; ?>";
				//alert(af+"-"+v)
				if (af==1 && v==3) {
					 //alert("<?php echo $content["details"]->agency_price_flag_holder."---".$content["details"]->pricing_flag_holder; ?>")
					 $("#price_customer").val(<?php echo ($content["details"]->agency_price_flag_holder!=0)?$content["details"]->agency_price_flag_holder:$content["details"]->pricing_flag_holder; ?>);
					 $("#franchisee_charge").val(0);
					 //$('#price_customer').attr('readonly', true);$('#franchisee_charge').attr('readonly', true);
				}
				/*
				else if (af!=1 && v==3) {
					 $("#price_customer").val('<?php echo $content["details"]->pricing_flag_holder; ?>');
					 $("#franchisee_charge").val(0);
					 //$('#price_customer').removeAttr('readonly');$('#franchisee_charge').removeAttr('readonly');$('#price_customer').attr('readonly', true);$('#franchisee_charge').attr('readonly', true);
				}
				*/
				var bf = "<?php echo $content["details"]->agency_solar; ?>";
				//alert(bf+"--"+v)
				if (bf==1 && v==5) {
					 $("#price_customer").val(<?php echo ($content["details"]->agency_solar_price!=0)?$content["details"]->agency_solar_price:$content["details"]->solar_price; ?>);
					 $("#franchisee_charge").val(0);
					 //$('#price_customer').attr('readonly', true);$('#franchisee_charge').attr('readonly', true);
				}
				/*
				else if (bf!=1 && v==5) {
					 $("#price_customer").val('<?php echo $content["details"]->solar_price; ?>');
					 $("#franchisee_charge").val(0);
					 //$('#price_customer').removeAttr('readonly');$('#franchisee_charge').removeAttr('readonly');$('#price_customer').attr('readonly', true);$('#franchisee_charge').attr('readonly', true);
				}
				*/
				var cf = "<?php echo $content["details"]->agency_brochure_holder; ?>";
				//alert(cf+"-"+v)
				if (cf==1 && v==13) {
					 $("#price_customer").val(<?php echo ($content["details"]->agency_price_brochure_holder!=0)?$content["details"]->agency_price_brochure_holder:$content["details"]->pricing_brochure_holder; ?>);
					 $("#franchisee_charge").val(0);
					 //$('#price_customer').attr('readonly', true);$('#franchisee_charge').attr('readonly', true);
				}
				/*
				else if (cf!=1 && v==13) {
					 $("#price_customer").val('<?php echo $content["details"]->pricing_brochure_holder; ?>');
					 $("#franchisee_charge").val(0);
					 //$('#price_customer').removeAttr('readonly');$('#franchisee_charge').removeAttr('readonly');$('#price_customer').attr('readonly', true);$('#franchisee_charge').attr('readonly', true);
				}
				*/
				var df = "<?php echo $content["details"]->agency_floodlight; ?>";
				//alert(df+"--"+v)
				if (df==1 && v==14) {
					 $("#price_customer").val(<?php echo ($content["details"]->agency_floodlight_price!=0)?$content["details"]->agency_floodlight_price:$content["details"]->floodlight_price; ?>);
					 $("#franchisee_charge").val(0);
					 //$('#price_customer').attr('readonly', true);$('#franchisee_charge').attr('readonly', true);
				}
				/*
				else if (df!=1 && v==14) {
					 $("#price_customer").val('<?php echo $content["details"]->floodlight_price; ?>');
					 $("#franchisee_charge").val(0);
					 //$('#price_customer').removeAttr('readonly');$('#franchisee_charge').removeAttr('readonly');$('#price_customer').attr('readonly', true);$('#franchisee_charge').attr('readonly', true);
				}
				*/
				var w = "<?php echo $content["details"]->agency_wings; ?>";
				//alert(w+"-"+v)
				if (w==1 && v==15) {
					 $("#price_customer").val(<?php echo ($content["details"]->agency_price_wings!=0)?$content["details"]->agency_price_wings:$content["details"]->pricing_wings; ?>);
					 $("#franchisee_charge").val(0);
					 //$('#price_customer').attr('readonly', true);$('#franchisee_charge').attr('readonly', true);
				}
				/*
				else if (w!=1 && v==15) {
					 $("#price_customer").val('<?php echo $content["details"]->pricing_wings; ?>');
					 $("#franchisee_charge").val(0);
					 //$('#price_customer').removeAttr('readonly');$('#franchisee_charge').removeAttr('readonly');$('#price_customer').attr('readonly', true);$('#franchisee_charge').attr('readonly', true);
				}
				*/
				
				if (v==7) {
					 $("#price_customer").val(<?php echo ($content["details"]->RoofJobFee!=0)?$content["details"]->RoofJobFee:0; ?>);
					 $("#franchisee_charge").val(0);
				}
				
				if(v!=3 && v!=5 && v!=13 && v!=14 && v!=15 && v!=7){
					$("#price_customer").val('');
					 $("#franchisee_charge").val('');
				}
		  }
		  
         function addNewAddons(){
		   var flag_holder_set = "<?php echo $flag_holder; ?>";
		   var solar_set = "<?php echo $sign_solar; ?>";
			var brochure_holder_set = "<?php echo $brochure_holder; ?>";
			var floodlight_set = "<?php echo $sign_floodlight; ?>";
			var wings_set = "<?php echo $wings; ?>";
			//alert(wings_set+"---"+flag_holder_set);
		   $.ajax({
		      type: 'post',
		      url: '<?=base_url()?>index.php/home/getAddonsAjax',
		      success: function(data) {
		         data = JSON.parse(data);
		         html = '<div id="addons-error" style="color:red; font-size: 12px;"></div><table><tr><td>Add ons to Sign: </td><td><select name="addons" id="addons" onchange="getflag(this.value)" >';
		         $.each(data, function(i, item) {
				var st =0;
				//if ( (flag_holder_set==1 && item.addonid==3) || (solar_set==1 && item.addonid==5)) {		st=1;		}
				if ( (flag_holder_set==1 && item.addonid==3)) {		st=1;		}
				if ( (brochure_holder_set==1 && item.addonid==13)) {		st=1;		}
				if ( (wings_set==1 && item.addonid==15)) {		st=1;		}
				if (item.addonid == '1') {
               //html = html + '<option value="' + item.addonid + '" selected="selected">' +st+"-"+item.addonname + '</option>';
					html = html + '<option value="' + item.addonid + '" selected="selected">' +item.addonname + '</option>';
            }else if(st==1){
					//html = html + '<option value="' + item.addonid + '">' +st+"-"+item.addonname + '</option>';
				}else {
               //html = html + '<option value="' + item.addonid + '">' +st+"-"+item.addonname + '</option>';
					 if(item.addonid==7){
						  html = html + '<option value="' + item.addonid + '">' +item.addonname + ' ($<?php echo $content["details"]->RoofJobFee; ?>)</option>';
					 }else{
						  html = html + '<option value="' + item.addonid + '">' +item.addonname + '</option>';
					 }
            }
         });
         html = html + '</select></td></tr>';
         html = html + '<tr><td>Price to Customer:</td><td>$<input name="price_customer" id="price_customer"></td></tr>';
         html = html + '<tr><td>Franchisee Charge:</td><td>$<input name="franchisee_charge" id="franchisee_charge"></td></tr>';
         html = html + '<tr><td>Notes:</td><td><textarea name="addnos_notes" id="addons_notes" rows=8 cols=50></textarea></td></tr></table>';

         $j("#alert").html(html).dialog({
            title: "Add New Addons",
            resizable: false,
            modal: true,
            width: 600,
            height: 400,
            buttons: {
               "Save": function() {
						var addons = $j('#addons').val();
                  var price_customer = $j('#price_customer').val();
                  var franchisee_charge = $j('#franchisee_charge').val();
                  var addon_notes = $j('#addons_notes').val();
                  var err = '';
                  if (addons && price_customer && franchisee_charge) {
                     $.ajax({
                        type: 'post',
                        url: '<?=base_url()?>index.php/home/addNewAddonsAjax',
                        data: {
                           signid: <?=$content['details']->signid ?> ,
                           addons: addons,
						   sign_type: '<?php echo $this->input->get('sign_type');?>',
                           price_customer: price_customer,
                           franchisee_charge: franchisee_charge,
                           addon_notes: addon_notes
                        },
                        success: function(data) {
                           location.reload();
                        }
                     });
                     $j(this).dialog("close");
                  } else {
                     if (!addons) {
                        err = err + '- Add ons to sign is empty </br>';
                     }
                     if (!price_customer) {
                        err = err + '- Price to customer is empty </br>';
                     }
                     if (!franchisee_charge) {
                        err = err + '- Franchisee charge is empty </br>';
                     }
                     $j('#addons-error').html(err + '</br>');
                  }
               },
               "Cancel": function() {
                  $j(this).dialog("close");
               }
            }
         });
			//alert("dfgd")$j('#addons').each(function() {alert($j(this).val())});
      }
   });
   
}
        
        //test  
    </script>
	 	<!-- Add jQuery library -->
	<!--script type="text/javascript" src="<?=base_url();?>assets/fancybox-popup/lib/jquery-1.10.1.min.js"></script-->
	
	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="<?=$this->config->item("site_url");?>assets/fancybox-popup/source/jquery.fancybox.js?v=2.1.5"></script>
	
	<?php /*
	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="<?=base_url();?>assets/fancybox-popup/jquery.mousewheel-3.0.6.pack.js"></script>
	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/fancybox-popupsource/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="<?=base_url();?>assets/fancybox-popupsource/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/fancybox-popupsource/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="<?=base_url();?>assets/fancybox-popupsource/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="<?=base_url();?>assets/fancybox-popupsource/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
	 */ ?>
<script type="text/javascript">
var $sd = jQuery.noConflict();
$sd(document).ready(function() {
	 $sd(".fancybox-button").fancybox({
		  openEffect  : 'none',
		  closeEffect : 'none',
		  prevEffect		: 'none',
		  nextEffect		: 'none',
		  closeBtn		: true,
		  helpers		: {
				title	: { type : 'inside' },
				buttons	: {}
		  }
	 });
	 
	 $sd(".fancybox").fancybox({
		  openEffect  : 'none',
		  closeEffect : 'none',
		  width : 800,
		  iframe : {
				preload: false
		  }
	 });

});
</script> 
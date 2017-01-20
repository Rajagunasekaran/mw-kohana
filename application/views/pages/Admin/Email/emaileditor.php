  <!--- Top nav && left nav--->
  <?php echo $topnav.$leftnav;?>
  <!--- Top nav && left nav --->
  <!-- Content Wrapper. Contains page content -->
        <!--[if lt IE 9]>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
        <![endif]-->

        <!--  wrapper / INIZIO -->
  <div id="page-wrapper">
            <!-- Sidebar -->
            <div id="sidebar-wrapper">
                <br>
                <ul class="sidebar-nav" id="sidebar">
                    <li><a href="javascript:void(0);" class="em_menu" data-group="title"><i
                                style="color: #ffffff !important" class="fa fa-header fa-2x"></i></a></li>
                    <li><a href="javascript:void(0);" class="em_menu" data-group="text"><i
                                style="color: #ffffff !important" class="fa fa-font fa-2x"></i></a></li>
                    <li><a href="javascript:void(0);" class="em_menu" data-group="tools"> <i
                                class="fa fa-ellipsis-h fa-2x" aria-hidden="true"></i></a></li>
                    <li><a href="javascript:void(0);" class="em_menu" data-group="image"><i
                                class="fa fa-file-image-o fa-2x"></i></a></li>
                    <li><a href="javascript:void(0);" class="em_menu"  data-group="video"><i
                                class="fa fa-youtube-play fa-2x" aria-hidden="true"></i></a></li>
                    <li><a href="javascript:void(0);" class="em_menu"  data-group="social"><i class="fa fa-facebook fa-2x"  aria-hidden="true"></i></a></li>
                    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#credits"><i class="fa fa-life-ring fa-2x" aria-hidden="true"></i></a></li>

                </ul>
            </div>

            <!-- sidebar with elements -->
            <div id="sidebar-opzioni" class="sidebar-nav">
              <a href="javascript:void(0);" id="closeSideBarnav"> <i class="pull-right fa fa-times" aria-hidden="true"   style="padding: 10px"></i></a> <br>

                <div id="em_elements">
                    <ul class="nav nav-list accordion-group">
                        <li class="rows" id="estRows">
                             <!-- load elements -->
                        </li>
                    </ul>
                </div>
            </div>
              <!-- sidebar with elements -->


            <div class="row" style="padding-top:10px">
              <div class="col-md-3">
                <a style="margin-left:70px" onclick="return confirm('message for exit action');" class="btn btn-warning" href="#"><i class="fa fa-arrow-left"></i> back</a>
              </div>
              <div class="col-md-5">
                    <div id="messagefromphp"></div>
                    <div id="messagefromphp2"></div>
              </div>

              <div class="col-md-4">
                <div class="pull-right">
                  <div class="btn-group" data-toggle="buttons-radio">
                      <button type="button" class="btn btn btn-default" id="sourcepreview"><i class="glyphicon-eye-open glyphicon"></i> Preview</button>
                  </div>
                  <div class="btn-group">
                      <a class="btn btn btn-success" href="#save" id="save" ><i class="glyphicon glyphicon-floppy-disk"></i> Save</a>
                  </div>
                </div>
              </div>
            </div>

            <br />


            <div id="tosave" data-id=""  data-paramone="11" data-paramtwo="22" data-paramthree="33">


        
                <!-- inizio parte html da salvare -->
               <table  width="100%" border="0" cellspacing="0" cellpadding="0" style="background: #eeeeee" >
                   <tr>
                       <td width="100%" id="primary" class="main demo" align="center" valign="top" >
                           <!-- inizio contentuto      -->

                            <div class="lyrow">
                               <div class="view">
                                   <div class="row clearfix">
                                       <!-- Content starts here-->
                                       <table width="640" class="preheader" align="center" cellspacing="0" cellpadding="0" border="0">
                                           <tr>
                                               <td align="left" class="preheader-text" width="420" style="padding: 15px 0px; font-family: Arial; font-size: 11px; color: #666666"></td>
                                               <td class="preheader-gap" width="20"></td>
                                               <td class="preheader-link" align="right" width="200" style="padding: 15px 0px; font-family: Arial; font-size: 11px; color: #666666">
                                                    Non vedi le immagini? [linkversioneonline]
                                               </td>
                                           </tr>
                                       </table>
                                   </div>
                               </div>
                           </div>

                            <div class="column">

                                <!-- default element text -->
                               <div class="lyrow">
                                   <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                                   <span class="drag label label-default"><i class="glyphicon glyphicon-move"></i></span>
                                   <div class="view">

                                        <div class="row clearfix">
                                           <table width="640" class="main" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center" data-type='text-block' style="background-color: #FFFFFF;">
                                               <tbody>
                                                   <tr>
                                                       <td  class="block-text" align="left" style="padding:10px 50px 10px 50px;font-family:Arial;font-size:13px;color:#000000;line-height:22px">
                                                           <p style="margin:0px 0px 10px 0px;line-height:22px">
                                                               <center>
                                                                   <i class="fa fa-arrow-up fa-3x"></i> <br><br>
                                                                Modify me or drag the content of email in top or bottom <br><br>
                                                               <i class="fa fa-arrow-down fa-3x"></i>
                                                               </center>
                                                           </p>

                                                        </td>
                                                   </tr>
                                               </tbody>
                                           </table>
                                       </div>
                                   </div>
                               </div>


                                <div class="lyrow">
                                   <div class="view">
                                       <div class="row clearfix">
                                           <!-- Content starts here-->
                                           <table width="640" class="preheader" align="center" cellspacing="0" cellpadding="0" border="0">
                                               <tr>
                                                   <td align="left" class="preheader-text" width="420" style="padding: 15px 0px; font-family: Arial; font-size: 11px; color: #666666"></td>
                                                   <td class="preheader-gap" width="20"></td>
                                                   <td class="preheader-link" align="right" width="200" style="padding: 15px 0px; font-family: Arial; font-size: 11px; color: #666666">
                                                       [linkcancellazione]
                                                   </td>
                                               </tr>
                                           </table>
                                       </div>
                                   </div>
                               </div>

                            </div>

                        </td>
                   </tr>
               </table>



            </div>
            <div id="download-layout">

            </div>
        </div>
        <!--/row-->

        <br />
        <a style="margin-left:80px" href="#" class="btn btn-info btn-xs" id="edittamplate">Edit background</a>
        <br /><br />

        <!-- Modal test device-->
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="min-width:120px">
                    <div class="modal-header">
                        <input id="httphref" type="text" name="href" value="http://" class="form-control" />
                    </div>
                    <div class="modal-body" align="center">
                        <div class="btn-group  previewActions">
                            <a class="btn btn-default btn-sm " href="#">iphone</a>
                            <a class="btn btn-default btn-sm " href="#">smalltablet</a>
                            <a class="btn btn-default btn-sm " href="#">ipad</a>
                        </div>
                        <iframe id="previewFrame"  class="iphone"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <textarea id="imageid" class="hide"></textarea>
        <textarea id="download" class="hide"></textarea>
        <textarea id="selector" class="hide"></textarea>
        <textarea id="path" class="hide"></textarea>

        <!-- Modal test device-->


        <!-- Modal image gallery-->
        <div class="modal fade" id="previewimg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="min-width:120px">
                    <div class="modal-header">
                        Imagegallery
                    </div>
                    <div class="modal-body" align="center">
                        

<!--

  <input type="text" name="nome" value="" placeholder="name of images"><input class="btn btn-info" type="submit" value="search">

-->

<div id="contenutoimmagini"></div>



<form enctype="multipart/form-data" id="form-id">
    <input name="nomefile" type="file" />
    <input class="button" type="button" value="Upload" />
</form>
<progress value="0"></progress>

    <br>

<script>
    $(document).ready(function(){

    //html5 uploader
    $('.button').click(function(){
        var formx = document.getElementById('form-id');
        var formData = new FormData(formx);
        $.ajax({
            url: 'media-popup.php?op=newfile',  //Server script to process data
            type: 'POST',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){ // Check if upload property exists
                    myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
                }
                return myXhr;
            },
            //Ajax events
            success: completeHandler,
            error: errorHandler,
            // Form data
            data: formData,
            //Options to tell jQuery not to process data or worry about content-type.
            cache: false,
            contentType: false,
            processData: false
        });

        function completeHandler(){
            loadimages();
        }
        function errorHandler(){
            alert('errore caricamento');
        }
        function progressHandlingFunction(e){
            if(e.lengthComputable){
                $('progress').attr({value:e.loaded,max:e.total});
            }
        }
    });

        loadimages();

    });

    function inserisci(elemento){
        var link = $(elemento);
        var image = link.data('image');
        $('#image-url').val(image);


        var id= $('#image-url').data('id');
        $('#'+id).attr('src', $('#image-url').val()) ;
        $('#'+id).attr('width', $('#image-w').val()) ;
        $('#'+id).attr('height', $('#image-h').val()) ;

        $('#previewimg').modal('hide');
    }

    function loadimages(){

        var request = $.ajax({
            url: "media-popup.php?immagini=1",
            method: "POST",
            data: { nome : '' },
            dataType: "html"
        });

        request.done(function( msg ) {
            $( "#contenutoimmagini" ).html( msg );
        });

    }
</script>




                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal image gallery-->



        <!-- Modal credits / INIZIO -->
        <div id="credits" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Email editor</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                          EmailEditor by Persefone is a drag & drop email editor script in javascript Jquery and php
                          built for developer. You can simply integrate this script in your web project and create
                           custom email template with drag & drop.
                        </p>
                        Email: <a href="mailto:support@emaileditor.net">support@emaileditor.net</a> <br />
                        Website: <a href="http://www.emaileditor.net/">emaileditor.net</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal / FINE -->



        <!--  sidebar-editor / INIZIO -->
        <div id="sidebar-editor">
               <a href="javascript:void(0);" id="closeSideBar"> <i class="pull-right fa fa-times" aria-hidden="true"   style="padding: 10px"></i></a> <br>
                  <div class="hide" id="settings">

                      <ul class="nav nav-tabs">
                        <li><a data-toggle="tab" href="#padding">Padding</a></li>
                        <li><a data-toggle="tab" href="#style">Style</a></li>
                        <li><a data-toggle="tab" href="#contentuto">Content</a></li>
                      </ul>

                      <div class="tab-content">
                        <div id="padding" class="tab-pane active">

                          <br />
                          <form class="form-inline" id="common-settings">
                                     <center>
                                         <table>
                                             <tbody>
                                                 <tr>
                                                     <td></td>
                                                     <td><input type="text" class="form-control" placeholder="top" value="15px" id="ptop" name="ptop" style="width: 60px; margin-right: 5px"></td>
                                                     <td></td>
                                                 </tr>
                                                 <tr>
                                                     <td><input type="text" class="form-control" placeholder="left" value="15px" id="pleft" name="mtop" style="width: 60px; margin-right: 5px"></td>
                                                     <td></td>
                                                     <td><input type="text" class="form-control" placeholder="right" value="15px" id="pright" name="mbottom" style="width: 60px; margin-right: 5px"></td>
                                                 </tr>
                                                 <tr>
                                                     <td></td>
                                                     <td><input type="text" class="form-control" placeholder="bottom" value="15px" id="pbottom" name="pbottom" style="width: 60px; margin-right: 5px"></td>
                                                     <td></td>
                                                 </tr>
                                             </tbody>
                                         </table>
                                     </center>

                             </form>

                        </div>
                        <div id="style" class="tab-pane">

                          <br />
                          <form id="background"  class="form-inline">
                              <div class="form-group">
                                  <label for="bgcolor">Background</label>
                                  <div class="color-circle" id="bgcolor"></div>
                              </div>
                          </form>

                          <form class="form-inline" id="font-settings" style="margin-top:5px">
                              <div class="form-group">
                                  <label for="fontstyle">Font style</label>
                                  <div id="fontstyle" class="color-circle"><i class="fa fa-font"></i></div>
                              </div>
                          </form>

                          <div class="hide" id='font-style'>
                              <div id="mainfontproperties" >
                                  <div class="input-group" style="margin-bottom: 5px">
                                      <span class="input-group-addon" style="min-width: 60px;">Color</span>
                                      <input type="text" class="form-control picker" id="colortext" >
                                      <span class="input-group-addon"></span>
                                      <script type="text/javascript">
                                          $('#colortext').colpick({
                                              layout: 'hex',
                                              // colorScheme: 'dark',
                                              onChange: function (hsb, hex, rgb, el, bySetColor) {
                                                  if (!bySetColor)
                                                      $(el).val('#' + hex);
                                              },
                                              onSubmit: function (hsb, hex, rgb, el) {
                                                  $(el).next('.input-group-addon').css('background-color', '#' + hex);
                                                  $(el).colpickHide();
                                              }

                                          }).keyup(function () {
                                              $(this).colpickSetColor(this.value);
                                          });
                                      </script>
                                  </div>
                                  <div class="input-group" style="margin-bottom: 5px">
                                      <span class="input-group-addon" style="min-width: 60px;">Font</span>
                                      <input type="text" class="form-control " id="fonttext" readonly>
                                  </div>
                                  <div class="input-group" style="margin-bottom: 5px">
                                      <span class="input-group-addon" style="min-width: 60px;">Size</span>
                                      <input type="text" class="form-control " id="sizetext" style="width: 100px">
                                      &nbsp;
                                      <a class="btn btn-default plus" href="#">+</a>
                                      <a class="btn btn-default minus" href="#">-</a>
                                  </div>

                                  <hr/>
                                  <div class="text text-right">
                                      <a class="btn btn-info" id="confirm-font-properties">OK</a>
                                  </div>
                              </div>

                              <div id="fontselector" class="hide" style="min-width: 200px">
                                  <ul class="list-group" style="overflow: auto ;display: block;max-height: 200px" >
                                      <li class="list-group-item" style="font-family: arial">Arial</li>
                                      <li class="list-group-item" style="font-family: verdana">Verdana</li>
                                      <li class="list-group-item" style="font-family: helvetica">Helvetica</li>
                                      <li class="list-group-item" style="font-family: times">Times</li>
                                      <li class="list-group-item" style="font-family: georgia">Georgia</li>
                                      <li class="list-group-item" style="font-family: tahoma">Tahoma</li>
                                      <li class="list-group-item" style="font-family: pt sans">PT Sans</li>
                                      <li class="list-group-item" style="font-family: Source Sans Pro">Source Sans Pro</li>
                                      <li class="list-group-item" style="font-family: PT Serif">PT Serif</li>
                                      <li class="list-group-item" style="font-family: Open Sans">Open Sans</li>
                                      <li class="list-group-item" style="font-family: Josefin Slab">Josefin Slab</li>
                                      <li class="list-group-item" style="font-family: Lato">Lato</li>
                                      <li class="list-group-item" style="font-family: Arvo">Arvo</li>
                                      <li class="list-group-item" style="font-family: Vollkorn">Vollkorn</li>
                                      <li class="list-group-item" style="font-family: Abril Fatface">Abril Fatface</li>
                                      <li class="list-group-item" style="font-family: Playfair Display">Playfair Display</li>
                                      <li class="list-group-item" style="font-family: Yeseva One">Yeseva One</li>
                                      <li class="list-group-item" style="font-family: Poiret One">Poiret One</li>
                                      <li class="list-group-item" style="font-family: Comfortaa">Comfortaa</li>
                                      <li class="list-group-item" style="font-family: Marck Script">Marck Script</li>
                                      <li class="list-group-item" style="font-family: Pacifico">Pacifico</li>
                                  </ul>
                              </div>
                          </div>

                        </div>
                        <div id="contentuto" class="tab-pane">

                          <!-- videopropoerties -->
                          <div id="videoproperties" style="margin-top:5px">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-8">
                                        Youtube url: <input type="text" id="youtube-image-link-url" class="form-control" data-id="none">
                                    </div>
                                    <div class="col-xs-4">
                                      <br />
                                        <input type="button" id="generayoutube" class="form-control" value="genera">
                                    </div>
                                </div>
                            </div>

                              <div class="form-group">
                                  <div class="row">
                                      <div class="col-xs-12">
                                          Image url: <input type="text" id="youtube-image-url" class="form-control" data-id="none"/>
                                      </div>
                                  </div>
                              </div>

                              <div class="form-group">
                                  <div class="row">
                                      <div class="col-md-4">
                                          W: <input type="text" id="youtube-image-w" class="form-control" name="director" />
                                      </div>

                                      <div class="col-md-4">
                                          H: <input type="text" id="youtube-image-h"class="form-control" name="writer" />
                                      </div>

                                      <div class="col-md-4">
                                        <br />
                                        <a class="btn btn-warning" href="#" id="youtube-change-image"><i class="fa fa-edit"></i>&nbsp;Apply</a>
                                      </div>

                                  </div>
                              </div>

                          </div>
                          <!-- videopropoerties -->

                          <!-- imageproperties -->
                          <div id="imageproperties" style="margin-top:5px">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12">
                                        Link url: <input type="text" id="image-link-url" class="form-control" data-id="none">
                                    </div>
                                </div>
                            </div>

                              <div class="form-group">
                                  <div class="row">
                                      <div class="col-xs-9">
                                          Image url: <input type="text" id="image-url" class="form-control" data-id="none"/>
                                      </div>
                                      <div class="col-xs-3"><br />
                                          <a id="popupimg" class="btn btn-default" data-toggle="modal" data-target="#previewimg">...</a>
                                      </div>
                                  </div>
                              </div>

                              <div class="form-group">
                                  <div class="row">
                                      <div class="col-md-4">
                                          W: <input type="text" id="image-w" class="form-control" name="director" />
                                      </div>

                                      <div class="col-md-4">
                                          H: <input type="text" id="image-h"class="form-control" name="writer" />
                                      </div>

                                      <div class="col-md-4">
                                        <br />
                                          <a class="btn btn-warning" href="#" id="change-image"><i class="fa fa-edit"></i>&nbsp;Apply</a>
                                      </div>

                                  </div>
                              </div>

                          </div>
                          <!-- imageproperties -->

                          <form id="editor" style="margin-top:5px">
                            Text content:
                              <div class="panel panel-body panel-default html5editor" id="html5editor"></div>
                          </form>

                          <form id="editorlite" style="margin-top:5px">
                              <br />
                                  <input type="text" style="width:100%" class="panel panel-body panel-default html5editorlite" id="html5editorlite">
                                  Alignment: <select id="allineamento">
                                  <option value=""></option>
                                  <option value="left">left</option>
                                  <option value="right">right</option>
                                  <option value="center">center</option>
                              </select>
                          </form>

                          <div id="social-links">
                              <ul class="list-group" id="social-list">
                                  <li>
                                      <div class="input-group">
                                          <span class="input-group-addon" ><i class="fa fa-2x fa-facebook-official"></i></span>
                                          <input type="text" class="form-control social-input" name="facebook" style="height:48px"/>
                                          <span class="input-group-addon" ><input  type="checkbox" checked="checked" name="facebook" class="social-check"/></span>
                                      </div>
                                  </li>
                                  <li>
                                      <div class="input-group">
                                          <span class="input-group-addon" ><i class="fa fa-2x fa-twitter"></i></span>
                                          <input type="text" class=" form-control social-input" name="twitter" style="height:48px"/>
                                          <span class="input-group-addon" ><input type="checkbox" checked="checked" name="twitter" class="social-check"/></span>
                                      </div>
                                  </li>
                                  <li>
                                      <div class="input-group">
                                          <span class="input-group-addon" ><i class="fa fa-2x fa-linkedin"></i></span>
                                          <input type="text" class=" form-control social-input" name="linkedin" style="height:48px"/>
                                          <span class="input-group-addon" ><input type="checkbox" checked="checked" name="linkedin" class="social-check"/></span>
                                      </div>
                                  </li>
                                  <li>
                                      <div class="input-group">
                                          <span class="input-group-addon" ><i class="fa fa-2x fa-youtube"></i></span>
                                          <input type="text" class=" form-control social-input" name="youtube" style="height:48px"/>
                                          <span class="input-group-addon" ><input type="checkbox" checked="checked" name="youtube" class="social-check" /></span>
                                      </div>
                                  </li>
                              </ul>
                          </div>

                          <div id="buttons" style="max-width: 400px">
                              <div class="form-group">
                                Alignment:
                                  <select class="form-control">
                                      <option value="center">Align buttons to Center</option>
                                      <option value="left">Align buttons to Left</option>
                                      <option value="right">Align buttons to Right</option>
                                  </select>
                              </div>
                              <ul id="buttonslist" class="list-group">
                                  <li class="hide" style="padding:10px; border:1px solid #DADFE1; border-radius: 4px">

                                    <!--
                                      <span class="orderbutton"><i class="fa fa-bars"></i></span>
                                      <span class="pull-right trashbutton"><i class="fa fa-trash"></i></span>
                                    -->
                                      <div class="form-group">
                                          <input type="text" class="form-control" placeholder="Enter Button Title" name="btn_title"/>
                                      </div>
                                      <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-paperclip"></i></span>
                                        <input type="text" class="form-control"  placeholder="Add link to button" aria-describedby="basic-addon1" name="btn_link"/>
                                      </div>
                                      <br />
                                      <div class="input-group" style="margin-top:10px">
                                          <label for="buttonStyle">Button Style</label>
                                          <div   class="color-circle buttonStyle" data-original-title="" title="">
                                              <i class="fa fa-font"></i>
                                          </div>
                                          <div class="stylebox hide" style="width:400px">
                                              <!--
                                           <div class="input-group " style="margin-bottom: 5px">
                                               <span class="input-group-addon"><i class="fa fa-font"></i></span>
                                               <input type="text" class="form-control fontstyle" name="fontstyle" readonly style="cursor:pointer;background-color: #fff"/>
                                           </div>-->
                                              <label> Button Size</label>
                                              <div class="input-group " style="margin-bottom: 5px">
                                                  <span class="input-group-addon button"  ><i class="fa fa-plus" style="  cursor : pointer;"></i></span>
                                                  <input type="text" class="form-control text-center"  placeholder="Button Size"  name="ButtonSize"/>
                                                  <span class="input-group-addon button"  ><i class="fa fa-minus" style="  cursor : pointer;"></i></span>
                                              </div>
                                              <label> Font Size</label>
                                              <div class="input-group " style="margin-bottom: 5px">

                                                  <span class="input-group-addon font"  ><i class="fa fa-plus" style="  cursor : pointer;"></i></span>
                                                  <input type="text" class="form-control text-center"  placeholder="Font Size"  name="FontSize"/>
                                                  <span class="input-group-addon font"  ><i class="fa fa-minus" style="  cursor : pointer;"></i></span>
                                              </div>
                                              <div class="input-group background" style="margin-bottom: 5px">
                                                  <span class="input-group-addon " style="width: 50px;">Background Color</span>
                                                  <span class="input-group-addon picker" data-color="bg"></span>
                                              </div>

                                              <div class="input-group fontcolor" style="margin-bottom: 5px" >
                                                  <span class="input-group-addon" style="width: 50px;">Font Color</span>
                                                  <span class="input-group-addon picker" data-color="font"></span>
                                                  <script type="text/javascript">
                                                      $('.picker').colpick({
                                                          layout: 'hex',
                                                          // colorScheme: 'dark',
                                                          onChange: function (hsb, hex, rgb, el, bySetColor) {
                                                              if (!bySetColor)
                                                                  $(el).css('background-color', '#' + hex);

                                                              var color = $(el).data('color');
                                                              var indexBnt = getIndex($(el).parent().parent().parent().parent().parent(), $('#buttonslist li')) - 1;
                                                              if (color === 'bg') {
                                                                  $($('#' + $('#path').val()).find('table tbody tr td:eq(' + indexBnt + ') a')).css('background-color', '#' + hex);
                                                                  $(el).parent().parent().parent().parent().find('div.color-circle').css('background-color', '#' + hex);
                                                                  //fix td in email
                                                                  $($('#' + $('#path').val()).find('table tbody tr td:eq(' + indexBnt + ')')).css('background-color', '#' + hex);
                                                              } else {
                                                                  $($('#' + $('#path').val()).find('table tbody tr td:eq(' + indexBnt + ') a')).css('color', '#' + hex);
                                                                  $(el).parent().parent().parent().parent().find('div.color-circle').css('color', '#' + hex);
                                                              }

                                                          },
                                                          onSubmit: function (hsb, hex, rgb, el) {
                                                              $(el).css('background-color', '#' + hex);
                                                              $(el).colpickHide();
                                                              var color = $(el).data('color');
                                                              var indexBnt = getIndex($(el).parent().parent().parent().parent().parent(), $('#buttonslist li')) - 1;
                                                              if (color === 'bg') {
                                                                  $($('#' + $('#path').val()).find('table tbody tr td:eq(' + indexBnt + ') a')).css('background-color', '#' + hex);
                                                              } else {
                                                                  $($('#' + $('#path').val()).find('table tbody tr td:eq(' + indexBnt + ') a')).css('color', '#' + hex);
                                                              }


                                                          }

                                                      }).keyup(function () {
                                                          $(this).colpickSetColor(this.value);
                                                      });
                                                  </script>

                                              </div>
                                              <div class="text text-right">
                                                  <a href="#" class="btn btn-xs btn-default confirm">Ok</a>
                                              </div>
                                          </div>
                                          <div class="fontselector" class="hide" style="min-width: 200px">
                                              <ul class="list-group" style="overflow: auto ;display: block;max-height: 200px" >
                                                  <li class="list-group-item" style="font-family: arial">Arial</li>
                                                  <li class="list-group-item" style="font-family: verdana">Verdana</li>
                                                  <li class="list-group-item" style="font-family: helvetica">Helvetica</li>
                                                  <li class="list-group-item" style="font-family: times">Times</li>
                                                  <li class="list-group-item" style="font-family: georgia">Georgia</li>
                                                  <li class="list-group-item" style="font-family: tahoma">Tahoma</li>
                                                  <li class="list-group-item" style="font-family: pt sans">PT Sans</li>
                                                  <li class="list-group-item" style="font-family: Source Sans Pro">Source Sans Pro</li>
                                                  <li class="list-group-item" style="font-family: PT Serif">PT Serif</li>
                                                  <li class="list-group-item" style="font-family: Open Sans">Open Sans</li>
                                                  <li class="list-group-item" style="font-family: Josefin Slab">Josefin Slab</li>
                                                  <li class="list-group-item" style="font-family: Lato">Lato</li>
                                                  <li class="list-group-item" style="font-family: Arvo">Arvo</li>
                                                  <li class="list-group-item" style="font-family: Vollkorn">Vollkorn</li>
                                                  <li class="list-group-item" style="font-family: Abril Fatface">Abril Fatface</li>
                                                  <li class="list-group-item" style="font-family: Playfair Display">Playfair Display</li>
                                                  <li class="list-group-item" style="font-family: Yeseva One">Yeseva One</li>
                                                  <li class="list-group-item" style="font-family: Poiret One">Poiret One</li>
                                                  <li class="list-group-item" style="font-family: Comfortaa">Comfortaa</li>
                                                  <li class="list-group-item" style="font-family: Marck Script">Marck Script</li>
                                                  <li class="list-group-item" style="font-family: Pacifico">Pacifico</li>
                                              </ul>
                                          </div>

                                      </div>


                                  </li>
                              </ul>

                          </div>
                          </div>


                        </div>

                      <br />
                      <center> <a href="#" id="saveElement" class="btn btn-info">Apply and close</a> </center>

                      </div> <!-- settings -->

        </div>   <!--  sidebar-editor / FINE -->



    </body>
</html>

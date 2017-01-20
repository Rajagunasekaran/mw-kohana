var m_prefix = '';
var $download = '';

function triggerEditingOption(tis)
{
   m_prefix = $(tis).attr('data-prefix');
   $('#' + m_prefix + 'popupfinalact-modal').modal();
   $download = $('#' + m_prefix + 'croppedData');
}
$(function()
{
   'use strict';
   var console = window.console ||
   {
      log: function() {}
   };
   var $image = $('#image');
   var options = {
      aspectRatio: 1 / 1,
      preview: '.img-preview',
      minCropBoxWidth: 200,
      minCropBoxHeight: 200,
      strict: true,
      cropBoxResizable: false,
      crop: function(e) {}
   };
   // Tooltip
   // $('[data-toggle="tooltip"]').tooltip();
   // Cropper
   $image.on(
   {
      'build.cropper': function(e)
      {
         console.log(e.type);
      },
      'built.cropper': function(e)
      {
         console.log(e.type);
      },
      'cropstart.cropper': function(e)
      {
         console.log(e.type, e.action);
      },
      'cropmove.cropper': function(e)
      {
         console.log(e.type, e.action);
      },
      'cropend.cropper': function(e)
      {
         console.log(e.type, e.action);
      },
      'crop.cropper': function(e)
      {
         console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
      },
      'zoom.cropper': function(e)
      {
         console.log(e.type, e.ratio);
      }
   }).cropper(options);
   // Buttons
   if (!$.isFunction(document.createElement('canvas').getContext))
   {
      $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
   }
   if (typeof document.createElement('cropper').style.transition === 'undefined')
   {
      $('button[data-method="rotate"]').prop('disabled', true);
      $('button[data-method="scale"]').prop('disabled', true);
   }
   // Methods
   $('.docs-buttons').on('click', '[data-method]', function()
   {
      var $this = $(this);
      var data = $this.data();
      var $target;
      var result;
      if ($this.prop('disabled') || $this.hasClass('disabled'))
      {
         return;
      }
      if ($image.data('cropper') && data.method)
      {
         data = $.extend(
         {}, data); // Clone a new one
         if (typeof data.target !== 'undefined')
         {
            $target = $(data.target);
            if (typeof data.option === 'undefined')
            {
               try
               {
                  data.option = JSON.parse($target.val());
               }
               catch (e)
               {
                  console.log(e.message);
               }
            }
         }
         result = $image.cropper(data.method, data.option, data.secondOption);
         switch (data.method)
         {
            case 'scaleX':
            case 'scaleY':
               $(this).data('option', -data.option);
               break;
            case 'getCroppedCanvas':
               if (result)
               {
                  $download.val('');
                  if ($download.val() == '')
                  {
                     $download.val(result.toDataURL());
                  }
               }
               break;
         }
         if ($.isPlainObject(result) && $target)
         {
            try
            {
               $target.val(JSON.stringify(result));
            }
            catch (e)
            {
               console.log(e.message);
            }
         }
      }
   });
   // Keyboard
   $(document.body).on('keydown', function(e)
   {
      if ($("#popupimgeditor-model").is(':visible'))
      {
         if (!$image.data('cropper') || this.scrollTop > 300)
         {
            return;
         }
         switch (e.which)
         {
            case 37:
               e.preventDefault();
               $image.cropper('move', -1, 0);
               break;
            case 38:
               e.preventDefault();
               $image.cropper('move', 0, -1);
               break;
            case 39:
               e.preventDefault();
               $image.cropper('move', 1, 0);
               break;
            case 40:
               e.preventDefault();
               $image.cropper('move', 0, 1);
               break;
         }
      }
   });
   // Import image
   var $inputImage = $('#inputImage');
   var URL = window.URL || window.webkitURL;
   var blobURL;
   if (URL)
   {
      $inputImage.change(function()
      {
         var files = this.files;
         var file;
         if (!$image.data('cropper'))
         {
            return;
         }
         if (files && files.length)
         {
            file = files[0];
            if (/^image\/\w+$/.test(file.type))
            {
               blobURL = URL.createObjectURL(file);
               $image.one('built.cropper', function()
               {
                  // Revoke when load complete
                  URL.revokeObjectURL(blobURL);
               }).cropper('reset').cropper('replace', blobURL);
               $inputImage.val('');
            }
            else
            {
               window.alert('Please choose an image file.');
            }
         }
      });
   }
   else
   {
      $inputImage.prop('disabled', true).parent().addClass('disabled');
   }
   $(document).on('click', '.trigger_crop', function()
   {
      $('.crop-btn').trigger('click');
   });
   $(document).on('click', '.thumb-img, .img-itemname, .upload-imgrow, .mdl_thumb-img, .mdl_img-itemname, .mdl_upload-imgrow', function()
   {
      $currentElement = $(this);
      if (!$('#popupimgeditor-model').is(':visible'))
      {
         $image.cropper('destroy').cropper(options);
      }
   });
   $('#popupimgeditor-model').on('shown.bs.modal', function()
   {
      if (!$image.data('cropper'))
      {
         return;
      }
      if ($currentElement.attr('data-itemurl') != '')
      {
         var str = 'admin/';
         if (siteUrl.indexOf(str) > -1)
         {
            var imgsiteUrl = siteUrl.replace(str, "");
         }
         else
         {
            var imgsiteUrl = siteUrl;
         }
         $('.preloader').show();
         var xhr = new XMLHttpRequest();
         xhr.open("POST", imgsiteUrl + $currentElement.attr('data-itemurl'), true);
         xhr.responseType = "arraybuffer";
         xhr.onload = function(e)
         {
            var arrayBufferView = new Uint8Array(this.response);
            var blob = new Blob([arrayBufferView],
            {
               type: "image/png"
            });
            blobURL = URL.createObjectURL(blob);
            $image.one('built.cropper', function()
            {
               // Revoke when load complete
               URL.revokeObjectURL(blobURL);
            }).cropper('reset').cropper('replace', blobURL);
            $('.crop-btn').prop('disabled', false);
            $('.preloader').hide();
         };
         xhr.send();
      }
      else
      {
         window.alert('Please choose valid image file.');
      }
   }).on('hidden.bs.modal', function()
   {
      $image.cropper('destroy').cropper(options);
   });

   function dataURItoBlob(dataURI)
   {
      // convert base64/URLEncoded data component to raw binary data held in a string
      var byteString;
      if (dataURI.split(',')[0].indexOf('base64') >= 0) byteString = atob(dataURI.split(',')[1]);
      else byteString = unescape(dataURI.split(',')[1]);
      // separate out the mime component
      var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
      // write the bytes of the string to a typed array
      var ia = new Uint8Array(byteString.length);
      for (var i = 0; i < byteString.length; i++)
      {
         ia[i] = byteString.charCodeAt(i);
      }
      return new Blob([ia],
      {
         type: mimeString
      });
   }
   /* $(document).on('click','.upload-imgrow',function(){
   	if($('#popupimgeditor-model').css('display')=='none'){
   		$image.cropper('destroy').cropper(options);
   	}
   	if($('#image').attr('src')==''){
   		$('.crop-btn').prop('disabled', true);
   	}
   	if (!$image.data('cropper')) {
   		return;
   	}
   	var b64Data = $(this).attr('data-itemurl');
   	var blob = dataURItoBlob(b64Data);
   	blobURL = URL.createObjectURL( blob );
   	$image.one('built.cropper', function () {
   		// Revoke when load complete
   		URL.revokeObjectURL(blobURL);
   	}).cropper('reset').cropper('replace', blobURL);
   });*/
});
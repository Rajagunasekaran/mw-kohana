$(function () {

  'use strict';

  var console = window.console || { log: function () {} };
  var $image = $('#image');
  var $download = $('#download');
  var $dragndropimage = $("#dragndropimage");
  var $editimg = $('a.edit-img');
  var $dataX = $('#dataX');
  var $dataY = $('#dataY');
  var $dataHeight = $('#dataHeight');
  var $dataWidth = $('#dataWidth');
  var $dataRotate = $('#dataRotate');
  var $dataScaleX = $('#dataScaleX');
  var $dataScaleY = $('#dataScaleY');
  var options = {
        aspectRatio: 1 / 1,
        preview: '.img-preview',
        crop: function (e) {
          $dataX.val(Math.round(e.x));
          $dataY.val(Math.round(e.y));
          $dataHeight.val(Math.round(e.height));
          $dataWidth.val(Math.round(e.width));
          $dataRotate.val(e.rotate);
          $dataScaleX.val(e.scaleX);
          $dataScaleY.val(e.scaleY);
        }
      };


  // Tooltip
  $('[data-toggle="tooltip"]').tooltip();


  // Cropper
  $image.on({
    'build.cropper': function (e) {
      console.log(e.type);
    },
    'built.cropper': function (e) {
      console.log(e.type);
    },
    'cropstart.cropper': function (e) {
      console.log(e.type, e.action);
    },
    'cropmove.cropper': function (e) {
      console.log(e.type, e.action);
    },
    'cropend.cropper': function (e) {
      console.log(e.type, e.action);
    },
    'crop.cropper': function (e) {
      console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
    },
    'zoom.cropper': function (e) {
      console.log(e.type, e.ratio);
    }
  }).cropper(options);


  // Buttons
  if (!$.isFunction(document.createElement('canvas').getContext)) {
    $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
  }

  if (typeof document.createElement('cropper').style.transition === 'undefined') {
    $('button[data-method="rotate"]').prop('disabled', true);
    $('button[data-method="scale"]').prop('disabled', true);
  }


  // Download
  // if (typeof $download[0].download === 'undefined') {
  //   $download.addClass('disabled');
  // }


  // Options
  $('.docs-toggles').on('change', 'input', function () {
    var $this = $(this);
    var name = $this.attr('name');
    var type = $this.prop('type');
    var cropBoxData;
    var canvasData;

    if (!$image.data('cropper')) {
      return;
    }

    if (type === 'checkbox') {
      options[name] = $this.prop('checked');
      cropBoxData = $image.cropper('getCropBoxData');
      canvasData = $image.cropper('getCanvasData');

      options.built = function () {
        $image.cropper('setCropBoxData', cropBoxData);
        $image.cropper('setCanvasData', canvasData);
      };
    } else if (type === 'radio') {
      options[name] = $this.val();
    }

    $image.cropper('destroy').cropper(options);
    $('.crop-btn').prop('disabled', true);
  });


  // Methods
  $('.docs-buttons').on('click', '[data-method]', function () {
    var $this = $(this);
    var data = $this.data();
    var $target;
    var result;

    if ($this.prop('disabled') || $this.hasClass('disabled')) {
      return;
    }

    if ($image.data('cropper') && data.method) {
      data = $.extend({}, data); // Clone a new one

      if (typeof data.target !== 'undefined') {
        $target = $(data.target);

        if (typeof data.option === 'undefined') {
          try {
            data.option = JSON.parse($target.val());
          } catch (e) {
            console.log(e.message);
          }
        }
      }

      result = $image.cropper(data.method, data.option, data.secondOption);

      switch (data.method) {
        case 'scaleX':
        case 'scaleY':
          $(this).data('option', -data.option);
          break;

        case 'getCroppedCanvas':
          if (result) {
            if(result.length){
              // Bootstrap's Modal
              $('#getCroppedCanvasModal').modal().find('.modal-body .cropped-img').html('');
              $('.crop-msg').text('Please crop the image and try to save!').addClass('crop-error').removeClass('crop-success').show();
              $('#download').prop('disabled', true);
            }else{
              // Bootstrap's Modal
              $('#getCroppedCanvasModal').modal().find('.modal-body .cropped-img').html(result);
              if (!$download.hasClass('disabled')) {
                $download.attr('data-cropped', result.toDataURL());
              }
              $('.crop-msg').text('').removeClass('crop-error crop-success').hide();
              $('#download').prop('disabled', false);
            }
          }
        break;
      }

      if ($.isPlainObject(result) && $target) {
        try {
          $target.val(JSON.stringify(result));
        } catch (e) {
          console.log(e.message);
        }
      }

    }
  });


  // Keyboard
  $(document.body).on('keydown', function (e) {

    if (!$image.data('cropper') || this.scrollTop > 300) {
      return;
    }

    switch (e.which) {
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

  });


  // Import image
  $('#inputImage').fileupload({
    url: imgupurl,
    dataType: 'json',
    add: function (e, data) {
        data.submit();
        console.log('File uploaded');
    }
  });
  var $inputImage = $('#inputImage');
  var URL = window.URL || window.webkitURL;
  var blobURL;

  if (URL) {
    $inputImage.change(function () {
      var files = this.files;
      var file;

      if (!$image.data('cropper')) {
        return;
      }

      if (files && files.length) {
        file = files[0];
        $('#inputImgName').val('');
        $('#inputImgName').val(file.name);
        $('.crop-btn').prop('disabled', false);
        if (/^image\/\w+$/.test(file.type)) {
          blobURL = URL.createObjectURL(file);
          $image.one('built.cropper', function () {

            // Revoke when load complete
            URL.revokeObjectURL(blobURL);
          }).cropper('reset').cropper('replace', blobURL);
          $inputImage.val('');
        } else {
          window.alert('Please choose an image file.');
        }
      }
    });
  } else {
    $inputImage.prop('disabled', true).parent().addClass('disabled');
  }
  
  jQuery.event.props.push('dataTransfer');

  $dragndropimage.on('drop', function(event) {
      event.preventDefault();
      var files = event.dataTransfer.files;
      var file;

      if (!$image.data('cropper')) {
        return;
      }

      if (files && files.length) {
        file = files[0];
        $('#inputImgName').val('');
        $('#inputImgName').val(file.name);
        $('.crop-btn').prop('disabled', false);
        if (/^image\/\w+$/.test(file.type)) {
          blobURL = URL.createObjectURL(file);
          $image.one('built.cropper', function () {

            // Revoke when load complete
            URL.revokeObjectURL(blobURL);
          }).cropper('reset').cropper('replace', blobURL);
          $inputImage.val('');
        } else {
          window.alert('Please drop an image file.');
        }
      }
  });

  $(document).on('click','.img-itemname',function(){
    if (!$image.data('cropper')) {
      return;
    }

    if($(this).attr('data-itemurl')!=''){
      var xhr = new XMLHttpRequest();
      xhr.open( "POST", '../../../'+$(this).attr('data-itemurl'), true );
      xhr.responseType = "arraybuffer";

      xhr.onload = function( e ) {
          var arrayBufferView = new Uint8Array( this.response );
          var blob = new Blob( [ arrayBufferView ], { type: "image/png" } );
          blobURL = URL.createObjectURL( blob );
          $image.one('built.cropper', function () {

            // Revoke when load complete
            URL.revokeObjectURL(blobURL);
          }).cropper('reset').cropper('replace', blobURL);
          $inputImage.val('');
          $('.crop-btn').prop('disabled', false);
      };

      xhr.send();
    }
    else {
      window.alert('Image file not available.');
    }
  });

  $(document).on('click','a.edit-img',function(){
    if($('#imgeditormodel').css('display')=='none'){
      $image.cropper('destroy').cropper(options);
    }
    if($('#image').attr('src')==''){
      $('.crop-btn').prop('disabled', true);
    }
  });

});

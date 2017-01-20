function openSequencePopup(xrId){
	$('#myOptionsModalExerciseRecord').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'relatedRecords',
			method :  'sequenceRecords',
			id    : xrId,
			modelType : 'myOptionsModalExerciseRecord'
		},
		success : function(content){
			$('#myOptionsModalExerciseRecord').html(content);
			$('#myOptionsModalExerciseRecord').modal();
		}
	});
}
function openSequencePopupMore(xrId,start,lim){
	$.ajax({
		url : siteUrl+"search/getSequenceMore",
		data : {
			id 	   : xrId,
			start  : start,
			lim    : lim
		},
		success : function(content){
			 var JSONArray = $.parseJSON( content );
			 var response = '';
			if(JSONArray.length>0) {
			 for(var i=0; i<JSONArray.length; i++){
			 var seqOrder = start+i;					
				response += '<div class="row" style="margin-bottom:10px;"><div class="mobpadding">';
				response += '<label for="workout-name" class="control-label">Sequence '+seqOrder+'</label>';
				response += '<div class="border full"><div class="col-xs-3" style="border-right:1px solid #ddd;border-radius:0px">';
				if(JSONArray[i].img){				
					response += '<img width="50px;" id="exerciselibimg" class="img-thumbnail" style="cursor:pointer;';
					response += '" src=\'../../../'+JSONArray[i].img+'\'';
					response += '/>';
				}else{
					response += '<i style="font-size:50px;" class="fa fa-file-image-o datacol"></i>';
				}
				response += '</div>';
				response += '<div class="col-xs-9">'+JSONArray[i].img_title+'</div>';
				response += '</div>';
				response += '</div></div>';
				
			 }
			 start = start+lim;
			 response += '<div id="view_more" class="row"><div class="mobpadding"><div class="border full">';
			 response += '<div class="col-xs-12"><center><a data-role="none" data-ajax="false" class="pointers" onclick="openSequencePopupMore('+xrId+','+start+','+lim+')"><i class="fa fa-chevron-down"></i> Show More Records</a></center></div></div></div></div>';	
			 $("#view_more").remove();
			 $("#relatedexc").append(response).css('overflow-y', 'scroll');
		  }else{
			 $("#view_more").remove();
		  }		  
		}
	});
}
function openVideoPopup(url){
	if(url !=''){
		$('#myOptionsModalExerciseRecord').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'xrRecordactions',
				method :  YouTubeUrlNormalize(url), 
				modelType : 'myOptionsModalExerciseRecord'
			},
			success : function(content){
				$('#myOptionsModalExerciseRecord').html(content);
				$('#myOptionsModalExerciseRecord').modal();
			}
		});
	}
}
var getVidId = function(url)
{
	var vidId;
	if(url.indexOf("youtube.com/watch?v=") !== -1)//https://m.youtube.com/watch?v=e3S9KINoH2M
	{
		vidId = url.substr(url.indexOf("youtube.com/watch?v=") + 20);
	}
	else if(url.indexOf("youtube.com/watch/?v=") !== -1)//https://m.youtube.com/watch/?v=e3S9KINoH2M
	{
		vidId = url.substr(url.indexOf("youtube.com/watch/?v=") + 21);
	}
	else if(url.indexOf("youtu.be") !== -1)
	{
		vidId = url.substr(url.indexOf("youtu.be") + 9);
	}
	else if(url.indexOf("www.youtube.com/embed/") !== -1)
	{
		vidId = url.substr(url.indexOf("www.youtube.com/embed/") + 22);
	}
	else if(url.indexOf("?v=") !== -1)// http://m.youtube.com/?v=tbBTNCfe1Bc
	{
		vidId = url.substr(url.indexOf("?v=")+3, 11);
	}
	else
	{
		console.warn("YouTubeUrlNormalize getVidId not a youTube Video: "+url);
		vidId = null;
	}

	if(vidId.indexOf("&") !== -1)
	{
		vidId = vidId.substr(0, vidId.indexOf("&") );
	}
	return vidId;
};

var YouTubeUrlNormalize = function(url)
{
	var rtn = url;
	if(url)
	{
		var vidId = getVidId(url);
		if(vidId)
		{
			rtn = "https://www.youtube.com/embed/"+vidId;
		}
		else
		{
			rtn = url;
		}
	}

	return rtn;
};

YouTubeUrlNormalize.getThumbnail = function(url, num)
{
	var rtn, vidId = getVidId(url);
	if(vidId)
	{
		if(!isNaN(num) && num <= 4 && num >= 0)
		{
			rtn = "http://img.youtube.com/vi/"+vidId+"/"+num+".jpg";
		}
		else
		{
			rtn = "http://img.youtube.com/vi/"+getVidId(url)+"/default.jpg";
		}
	}
	else
	{
		return null;
	}
	return rtn;
};

YouTubeUrlNormalize.getFullImage = function(url)
{
	var vidId = getVidId(url);
	if(vidId)
	{
		return "http://img.youtube.com/vi/"+vidId+"/0.jpg";
	}
	else
	{
		return null;
	}
};

if ( typeof exports !== "undefined" ) {
	module.exports = YouTubeUrlNormalize;
}
else if ( typeof define === "function" ) {
	define( function () {
		return YouTubeUrlNormalize;
	} );
}
else {
	window.YouTubeUrlNormalize = YouTubeUrlNormalize;
}
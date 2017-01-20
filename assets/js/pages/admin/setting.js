$(".integrationsaction").tinyToggle({          
	 onCheck: function() {
		  var device_integrations = $(this).val();
		  //var time_to_send_email = $("#timeofday").val();
	 },
	 onUncheck: function() { //alert("Uncheck");
		  var device_integrations = $(this).val();
		  //var time_to_send_email = $("#timeofday").val();
	 }
});
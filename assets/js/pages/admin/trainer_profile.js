//setTimeout(function(){get_trainers_without_profile1();},500);


function get_trainers_without_profile(){
	$("#search-user-modal").modal("show");
	$('#roles').multipleSelect({
		placeholder: "Choose Roles",
		selectAllText: '<span class="ckbox">Choose Users</span>',
		onClick: function(view) {
			//$(".ms-drop").hide();
			//alert('Selected texts: ' + $('#roles').multipleSelect('getSelects'));
			var role = $('#roles').multipleSelect('getSelects');
			if (role=='') {
				$("#sel_users").hide();
			}else{
				$("#sel_users").fadeIn();
				var str = $("#users").val();
				var subscriberid = new Array();
				if (str) {
					for (var f = 0; f < str.length; f++) {
						subscriberid.push(str[f]);
					}
				}
				console.log("Subscribers-----------"+subscriberid)
				$.ajax({
					type: "post",
					url: siteUrl+"admin/trainer/user_filter",
					data:{
							'role':role,
							'subscribers':subscriberid
					},
					success: function (response) {
						if (response) {
							$("#sel_users").html(response);
							$("#users").select2();
						}
					}
				});
				
			}
		}
	});
	var role = $('#roles').multipleSelect('getSelects');
	if (role=='') {
		$("#sel_users").hide();
	}else{
		$("#sel_users").fadeIn();
	}
	$('[data-name="selectGroup"]').prop("checked", true);
	$("#users").select2();
}

function get_subscribers(){
	var str = $("#users").val();
	var subscriberid = new Array();
	if (str) {
		for (var f = 0; f < str.length; f++) {
			subscriberid.push(str[f]);
		}
	}
	console.log("Save Subscribers-----------"+subscriberid)
	if (subscriberid && subscriberid.length>0) {
		$.ajax({
			type: "post",
			url: siteUrl+"admin/user/save_profile",
			data:{
				'subscribers':subscriberid
			},
			success: function (response) {
				if (response==1) {
					window.location.href = siteUrl+"admin/trainer/trainer_profile";
				}
			}
		});
	}else{
		$("#alert_info").fadeIn();
		$("#alert_info").html("");
		var role = $('#roles').multipleSelect('getSelects');
		if (role.length==0) {
			$("#alert_info").html("Please Choose Roles...!");
		}else if (role.length>0) {
			$("#alert_info").html("Please Choose Users...!");
		}
		setTimeout(function(){
			$("#alert_info").fadeOut();
		},2500);
	}
}

function goto_trainer_profile(id){
	window.location.href = siteUrl+"admin/user/profile/"+id;
}
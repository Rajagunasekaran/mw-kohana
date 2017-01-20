var __slice = [].slice;
(function($, window) {
	var Starrr;
	Starrr = (function() {
		Starrr.prototype.defaults = {
			rating: void 0,
			numStars: 5,
			change: function(e, value) {}
		};

		function Starrr($el, options) {
			var i, _, _ref,
				_this = this;
			this.options = $.extend({}, this.defaults, options);
			this.$el = $el;
			_ref = this.defaults;
			for (i in _ref) {
				_ = _ref[i];
				if (this.$el.data(i) != null) {
					this.options[i] = this.$el.data(i);
				}
			}
			this.createStars();
			this.syncRating();
			this.$el.on('mouseover.starrr', 'i', function(e) {
				return _this.syncRating(_this.$el.find('i').index(e.currentTarget) + 1);
			});
			this.$el.on('mouseout.starrr', function() {
				return _this.syncRating();
			});
			this.$el.on('click.starrr', 'i', function(e) {
				return _this.setRating(_this.$el.find('i').index(e.currentTarget) + 1);
			});
			this.$el.on('starrr:change', this.options.change);
		}
		Starrr.prototype.createStars = function() {
			var _i, _ref, _results;
			_results = [];
			for (_i = 1, _ref = this.options.numStars; 1 <= _ref ? _i <= _ref : _i >= _ref; 1 <= _ref ? _i++ : _i--) {
				_results.push(this.$el.append("<i class='fa fa-star-o'></i>"));
			}
			return _results;
		};
		Starrr.prototype.setRating = function(rating) {
			if (this.options.rating === rating) {
				rating = void 0;
			}
			this.options.rating = rating;
			this.syncRating();
			return this.$el.trigger('starrr:change', rating);
		};
		Starrr.prototype.syncRating = function(rating) {
			var i, _i, _j, _ref;
			rating || (rating = this.options.rating);
			if (rating) {
				for (i = _i = 0, _ref = rating - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
					this.$el.find('i').eq(i).removeClass('fa-star-o').addClass('fa-star');
				}
			}
			if (rating && rating < 5) {
				for (i = _j = rating; rating <= 4 ? _j <= 4 : _j >= 4; i = rating <= 4 ? ++_j : --_j) {
					this.$el.find('i').eq(i).removeClass('fa-star').addClass('fa-star-o');
				}
			}
			if (!rating) {
				return this.$el.find('i').removeClass('fa-star').addClass('fa-star-o');
			}
		};
		return Starrr;
	})();
	return $.fn.extend({
		starrr: function() {
			var args, option;
			option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
			return this.each(function() {
				var data;
				data = $(this).data('star-rating');
				if (!data) {
					$(this).data('star-rating', (data = new Starrr($(this), option)));
				}
				if (typeof option === 'string') {
					return data[option].apply(data, args);
				}
			});
		}
	});
})(window.jQuery, window);
$(function() {
	return $(".starrr").starrr();
});
$(document).ready(function() {
	$('#stars').on('starrr:change', function(e, value) {
		//alert("1-----------"+value)
		$('#count').html(value);
	});
	$('.stars-existing').on('starrr:change', function(e, value) {
		var spanId = $(this).attr('id');
		var uId = $(this).attr('data-id');
		$('span.'+spanId).html(value);
		//alert("2-----------"+value+"--------------"+uId)
		if (uId && value) {
			$.ajax({
				type: 'POST',
				url: siteUrl + 'users/addrating',
				data: {
					'userid': uId,
					'rating': value
				},
				success: function(data) {
					if (data) {
						//$('').data-rating
						//alert(spanId)
						$('#'+spanId).attr("data-rating",data);
						$('span.'+spanId).html(data);
						//$(".starrr").starrr();
						//alert("Your rating added successfully...!")
					}else{
						//alert("You have already rated...!")
					}
					window.location.reload();
				}
			});
		}
		
	});
});
			
$(function() {
	setTimeout( function() {
		 $('ul#contact-list').scrollTop(0);
	}, 200 );
	$(".c-search>div").removeClass("ui-input-text");
	$(".c-search>div").removeClass("ui-shadow-inset");
	$(".c-search>div").removeClass("ui-corner-all ");
	$(".c-search>div").removeClass("ui-body-inherit");
	$(".c-search>div").removeClass("ui-shadow-inset");
	$(".c-search>div>input").css("height","44px");
	$('.dot').dotdotdot();
	var $dot5 = $('.dot5');
	$dot5.append( '<a class=\"toggle\" href=\"#\"><span class=\"open\">[ + ]</span><span class=\"close\">[ - ]</span></a>');
	function createDots()
	{
		$dot5.dotdotdot({
		after: 'a.toggle'
	});
	}
	function destroyDots() {
		$dot5.trigger( 'destroy' );
	}
	createDots();
	$dot5.on(
		'click',
		'a.toggle',
		function() {
			$dot5.toggleClass( 'opened' );
			if ($dot5.hasClass( 'opened' ) ) {
				destroyDots();
			} else {
				createDots();
			}
			return false;
		}
	);
});

function view_trainer_profile(id){
	$.ajax({
		type: 'POST',
		url: siteUrl + 'users/viewtrainerprofile',
		data: {
			'userid': id
		},
		success: function(data) {
			$("#trainer_profile_modal").html(data);
			$("#trainer_profile_modal").modal();
		}
	});
}
function goto_contact(url){
	contactUsModal();
	//window.location.href = url;
}

$(function() {
	$('[data-toggle="tooltip"]').tooltip();
	$('a[href="#add-a-user"]').on('click', function(event) {
		event.preventDefault();
		$('#add-a-user').modal('show');
	})
	$('[data-command="toggle-search"]').on('click', function(event) {
		event.preventDefault();
		$(this).toggleClass('hide-search');
		if ($(this).hasClass('hide-search')) {
			$('.c-search').closest('.row').slideUp(100);
		} else {
			$('.c-search').closest('.row').slideDown(100);
		}
	})
	$('#contact-list').searchable({
		searchField: '#contact-list-search',
		selector: 'li',
		childSelector: '.usercard',
		show: function(elem) {
			elem.slideDown(100);
		},
		hide: function(elem) {
			elem.slideUp(100);
		}
	})
});
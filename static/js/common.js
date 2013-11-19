$(document).ready(function() {
	/* Search */
	var go_search = function(link, search) {
		var use_search_page = ($('[name="use_search_page"]').length == 1) ? $('[name="use_search_page"]').val() : 1;
		if (use_search_page == 1) {
			location = link;
		} else {
			location = window.location.href + '/search/' + Func.GetName(search);
		}
	}
	$('.button-search').bind('click', function() {
		var url = web.host + 'search/';
		var search = $('input[name="search"]').val();
		if (search) {
			url += Func.GetName(search);
		}
		go_search(url, search);
	});
	$('.button-search-mobile').bind('click', function() {
		var url = web.host + 'search/';
		var search = $('.button-search-mobile').prev().val();
		if (search) {
			url += Func.GetName(search);
		}
		go_search(url, search);
	});
	$('#header input[name="search"]').bind('keydown', function(e) {
		if (e.keyCode == 13) {
			var url = web.host + 'search/'; 
			var search = $(this).val();
			if (search) {
				url += Func.GetName(search);
			}
			go_search(url, search);
		}
	});
	
	/* Ajax Cart */
	$('#cart > .heading a').live('click', function() {
		$('#cart').addClass('active');
		
		$('#cart').load('index.php?route=module/cart #cart > *');
		
		$('#cart').live('mouseleave', function() {
			$(this).removeClass('active');
		});
	});
	/* Ajax Cart mobile detect*/
	$('#cart-m > .heading a').live('click', function() {
		$('#cart-m').addClass('active');
		
		$('#cart-m').load('index.php?route=module/cart #cart > *');
		
		$('#cart-m').live('mouseleave', function() {
			$(this).removeClass('active');
		});
	});
	
	/* Mega Menu */
	$('#menu ul > li > a + div').each(function(index, element) {
		// IE6 & IE7 Fixes
		if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 6)) {
			var category = $(element).find('a');
			var columns = $(element).find('ul').length;
			
			$(element).css('width', (columns * 143) + 'px');
			$(element).find('ul').css('float', 'left');
		}		
		
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();
		
		i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());
		
		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});

	// IE6 & IE7 Fixes
	if ($.browser.msie) {
		if ($.browser.version <= 6) {
			$('#column-left + #column-right + #content, #column-left + #content').css('margin-left', '195px');
			
			$('#column-right + #content').css('margin-right', '195px');
		
			$('.box-category ul li a.active + ul').css('display', 'block');	
		}
		
		if ($.browser.version <= 7) {
			$('#menu > ul > li').bind('mouseover', function() {
				$(this).addClass('active');
			});
				
			$('#menu > ul > li').bind('mouseout', function() {
				$(this).removeClass('active');
			});	
		}
	}
	
	$('.success img, .warning img, .attention img, .information img').live('click', function() {
		$(this).parent().fadeOut('slow', function() {
			$(this).remove();
		});
	});	
});

function getURLVar(key) {
	var value = [];
	
	var query = String(document.location).split('?');
	
	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');
			
			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}
		
		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
} 

function addToCart(product_id, quantity) {
	quantity = typeof(quantity) != 'undefined' ? quantity : 1;

	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: 'product_id=' + product_id + '&quantity=' + quantity,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();
			
			if (json['redirect']) {
				location = json['redirect'];
			}
			
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				$('.success').fadeIn('slow');
				
				$('#cart-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
			}	
		}
	});
}
function addToWishList(product_id) {
	$.ajax({
		url: 'index.php?route=account/wishlist/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();
						
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				$('.success').fadeIn('slow');
				
				$('#wishlist-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}	
		}
	});
}

function addToCompare(product_id) { 
	$.ajax({
		url: 'index.php?route=product/compare/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();
						
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				$('.success').fadeIn('slow');
				
				$('#compare-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
			}	
		}
	});
}

function display_item(view) {
	if (view == 'list') {
		$('.product-grid').attr('class', 'product-list');
		$('.products-block .product-block').each(function(index, element) {
			$(element).parent().addClass("col-fullwidth");
		});
		
		$('.product-filter .list').addClass('active');
		$('.product-filter .grid').removeClass('active');
	} else {
		$('.product-list').attr('class', 'product-grid');
		$('.products-block .product-block').each(function(index, element) {
			$(element).parent().removeClass("col-fullwidth");  
		});
		
		$('.product-filter .grid').addClass('active');
		$('.product-filter .list').removeClass('active');
	}
	
	set_local('view_type', view);
}

function get_local(name) {
	var result = '';
	
	if (typeof(localStorage) != 'undefined') {
		if (typeof(localStorage[name]) == 'undefined') {
			if (name == 'view_type') {
				localStorage[name] = 'list';
			} else {
				localStorage[name] = '';
			}
		}
		
		result = localStorage[name];
	}
	
	return result;
}

function set_local(name, value) {
	if (typeof(localStorage) != 'undefined') {
		localStorage[name] = value;
	}
}

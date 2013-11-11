<?php $this->load->view( 'website/common/meta' ); ?>
<body id="offcanvas-container" class="offcanvas-container layout-fullwidth fs12 page-product">
<section id="page" class="offcanvas-pusher" role="main">
	<?php $this->load->view( 'website/common/header' ); ?>
	
	<section id="columns" class="offcanvas-siderbars">
		<?php $this->load->view( 'website/common/breadcrumb' ); ?>
		
		<div class="container">
			<div class="row">
				<section class="col-lg-9 col-md-9 col-sm-12 col-xs-12 main-column">
					<div id="content" class="product-detail">
						<div class="product-info">
							<div class="row">
								<div class="col-lg-5 col-sm-5 col-xs-12 image-container">
									<span class="product-label-special"><span>Sale</span></span>
									<div class="image">
										<a href="http://#/opencart/image/cache/data/demo/product_02-500x500.jpg" class="colorbox cboxElement">
											<img src="<?php echo base_url('static/img/product_11-500x510.jpg'); ?>">
										</a>
									</div>
								</div>
								<div class="col-lg-7 col-sm-7 col-xs-12">
									<h1>Opencart Red Furniture Set</h1>
									<div class="description">
										<p><b>Product Code:</b>SAM9</p>
										<p>
											<b>Availability:</b>
											<span class="availability">In Stock</span>
										</p>
									</div>
									<div class="price">
										<div class="price-gruop">
											<span class="text-price">Price:</span>
											<span class="price-old">$407.38</span>
											<span class="price-new">$96.00</span>
										</div>
										<div class="other-price"><br /></div>
									</div>
									<div class="review">
										<div><a onclick="$('a[href=\'#tab-review\']').trigger('click');">0 reviews</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');">Write a review</a></div>
									</div>
									<div class="tags">
										<b>Tags:</b>
										<a href="http://#/opencart/index.php?route=product/search&amp;tag=Opencart%20Red%20Furniture%20Set">Opencart Red Furniture Set</a>
									</div>
								</div>
							</div>
						</div>
						<div class="tabs-group box">
							<div id="tabs" class="htabs">
								<ul class="nav nav-tabs box-heading clearfix">
									<li class="first"><a class="selected" href="#tab-description">Description</a></li>
									<li><a href="#tab-review">Reviews (0)</a></li>
									<li class="last"><a href="#tab-customtab">Custom Block Tab</a></li>
								</ul>
							</div>
							<div style="display: block;" id="tab-description" class="tab-content">
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit, omnis, temporibus, aut ullam minima consequuntur molestias reiciendis neque fuga autem dolorum nisi deserunt sequi voluptatibus cum dolore dolores quae aperiam quos at eveniet cumque incidunt dolor repudiandae amet sed ad nam corporis quibusdam aspernatur possimus quo velit mollitia.</p>
								<p>Ea, harum reiciendis iste quas ratione aliquid inventore autem exercitationem nisi aliquam eveniet eius molestias esse incidunt veritatis voluptas quam pariatur odio fugiat beatae ipsam nam ullam ad! Quo, molestias, ea, eius ex expedita eligendi atque nihil voluptates officiis ut voluptatibus itaque impedit rem enim animi. Quidem dignissimos aperiam minus aliquid illum.</p>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolor, adipisci, eligendi, et, tempora distinctio obcaecati ut quas ducimus accusamus est aperiam aut nisi nam illum porro impedit nemo modi non quo quibusdam praesentium enim cumque totam expedita sequi repudiandae officiis natus necessitatibus at. Perferendis eaque et doloribus eligendi officia quaerat!</p>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, modi delectus provident voluptate necessitatibus ipsum mollitia expedita voluptatum odio nostrum dolorem consequatur ullam adipisci suscipit voluptas sunt asperiores. Eum, deleniti, esse, minima est quaerat veritatis veniam quisquam voluptas ipsam pariatur maiores delectus asperiores tempora illo quos quo mollitia accusamus sunt doloribus nesciunt voluptatum aut excepturi recusandae? Hic veritatis quis id ab corrupti non inventore nisi facere. Sit, dicta, libero, officia, cumque odit dignissimos quasi nemo earum quae ipsa ex consectetur cupiditate enim vitae beatae optio recusandae illum molestias voluptatum porro quos alias corrupti unde iure perspiciatis quia aspernatur repudiandae numquam!</p>
							</div>
							<div style="display: none;" id="tab-review" class="tab-content no-margin">
								<div id="review">
									<div class="content">There are no reviews for this product.</div>
								</div>
								<h2 id="review-title">Write a review</h2>
								<div class="form-group">
									<label>Your Name:</label>
									<p><input name="name" type="text"></p>
								</div>
								<div class="form-group">
									<label>Your Review:</label>
									<p><textarea name="text" cols="50" rows="8" class="form-control"></textarea></p>
								</div>
								<div class="form-group">
									<p><span style="font-size: 11px;"><span style="color: #FF0000;">Note:</span>HTML is not translated!</span></p>
									<p>
										<strong>Rating:</strong>
										<span>Bad</span>
										<input name="rating" value="1" type="radio">
										<input name="rating" value="2" type="radio">
										<input name="rating" value="3" type="radio">
										<input name="rating" value="4" type="radio">
										<input name="rating" value="5" type="radio">
										<span>Good</span>
									</p>
									<p><strong>Enter the code in the box below:</strong></p>
									<p><input name="captcha" type="text"></p>
								</div>
								<div class="buttons no-padding">
									<div class="pull-right"><a id="button-review" class="button">Continue</a></div>
								</div>
							</div>
							<div style="display: none;" id="tab-customtab" class="tab-content custom-tab">
								<div class="inner">
									<p>
										<strong>Proin facilisis ipsum quis enim lobortis lacinias</strong><br />
										Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
									</p>
									<p><embed class="embed-video" src="shop/iFFiOPjMEZ8.htm" type="application/x-shockwave-flash" height="250" width="400"></p>
									<p>
										<strong>Proin facilisis ipsum quis enim lobortis lacinias</strong><br />
										Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
									</p>
									
									<ul>
										<li class="first"><strong>Sed ut perspiciatis unde omnis</strong></li>
										<li>Cras mattis consectetur purus sit amet fermentum. Etiam porta sem malesuada magna mollis euismod.</li>
										<li>Cras mattis consectetur purus sit amet fermentum. Etiam porta sem malesuada magna mollis euismod.</li>
										<li class="last">Cras mattis consectetur purus sit amet fermentum. Etiam porta sem malesuada magna mollis euismod.</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
<script type="text/javascript">
$('select[name="profile_id"], input[name="quantity"]').change(function(){
    $.ajax({
    url: 'index.php?route=product/product/getRecurringDescription',
    type: 'post',
    data: $('input[name="product_id"], input[name="quantity"], select[name="profile_id"]'),
    dataType: 'json',
        beforeSend: function() {
            $('#profile-description').html('');
        },
    success: function(json) {
      $('.success, .warning, .attention, information, .error').remove();
            
      if (json['success']) {
                $('#profile-description').html(json['success']);
      } 
    }
  });
});
    
$('#button-cart').bind('click', function() {
  $.ajax({
    url: 'index.php?route=checkout/cart/add',
    type: 'post',
    data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
    dataType: 'json',
    success: function(json) {
      $('.success, .warning, .attention, information, .error').remove();
      
      if (json['error']) {
        if (json['error']['option']) {
          for (i in json['error']['option']) {
            $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
          }
        }
                
                if (json['error']['profile']) {
                    $('select[name="profile_id"]').after('<span class="error">' + json['error']['profile'] + '</span>');
                }
      } 
      
      if (json['success']) {
        $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/shopermarket_megashop/image/close.png" alt="" class="close" /></div>');
          
        $('.success').fadeIn('slow');
          
        $('#cart-total').html(json['total']);
        
        $('html, body').animate({ scrollTop: 0 }, 'slow'); 
      } 
    }
  });
});

$('#review .pagination a').live('click', function() {
  $('#review').fadeOut('slow');
    
  $('#review').load(this.href);
  
  $('#review').fadeIn('slow');
  
  return false;
});     

$('#button-review').bind('click', function() {
  $.ajax({
    url: 'index.php?route=product/product/write&product_id=53',
    type: 'post',
    dataType: 'json',
    data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
    beforeSend: function() {
      $('.success, .warning').remove();
      $('#button-review').attr('disabled', true);
      $('#review-title').after('<div class="attention"><img src="catalog/view/theme/shopermarket_megashop/image/loading.gif" alt="" />Please Wait!</div>');
    },
    complete: function() {
      $('#button-review').attr('disabled', false);
      $('.attention').remove();
    },
    success: function(data) {
      if (data['error']) {
        $('#review-title').after('<div class="warning">' + data['error'] + '</div>');
      }
      
      if (data['success']) {
        $('#review-title').after('<div class="success">' + data['success'] + '</div>');
                
        $('input[name=\'name\']').val('');
        $('textarea[name=\'text\']').val('');
        $('input[name=\'rating\']:checked').attr('checked', '');
        $('input[name=\'captcha\']').val('');
      }
    }
  });
});

$('#tabs a').tabs();

$(document).ready(function() {
  if ($.browser.msie && $.browser.version == 6) {
    $('.date, .datetime, .time').bgIframe();
  }

  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
				</section>
				
				<aside id="oc-column-right" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 offcanvas-sidebar">
					<div id="column-right" class="sidebar">
						<?php $this->load->view( 'website/common/sort_brand' ); ?>
						
						<div class="box box-product bestseller">
							<div class="box-heading"><span>Bestsellers</span></div>
							<div class="box-content">
								<div class="product-grid">
									<div class="row"><div class="col-lg-3 col-sm-3 col-xs-12"><div class="product-block">
										<div class="image">
											<span class="product-label-special"><span>text_sale</span></span>
											<div class="img-overlay"></div>
										</div>
										<div class="product-meta">
											<h3 class="name"><a href="http://#/opencart/index.php?route=product/product&amp;product_id=59">Vintage sofas</a></h3>
											<div class="price">
												<span class="price-old">$273.43</span><span class="price-new">$88.95</span>
											</div>
											<div class="action">
												<div class="cart">
													<input value="?" onclick="addToCart('59');" class="product-icon icon-shopping-cart" data-placement="top" data-toggle="tooltip" data-original-title="Add to Cart" type="button">
												</div>
												<div class="wishlist">
													<a onclick="addToWishList('59');" title="" class="icon-heart product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Wish List">&nbsp;</a>
												</div>
												<div class="compare">
													<a onclick="addToCompare('59');" title="" class="icon-exchange product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Compare">&nbsp;</a>
												</div>
											</div>
										</div>
									</div></div></div>
									
									<div class="row last">
										<div class="col-lg-3 col-sm-3 col-xs-12">
											<div class="product-block">
												<div class="image">
													<div class="img-overlay"></div>
												</div>
												<div class="product-meta">
													<h3 class="name"><a href="http://#/opencart/index.php?route=product/product&amp;product_id=40">iPhone</a></h3>
													<div class="price">$120.68</div>
													<div class="action">
														<div class="cart">
															<input value="?" onclick="addToCart('40');" class="product-icon icon-shopping-cart" data-placement="top" data-toggle="tooltip" data-original-title="Add to Cart" type="button">
														</div>
														<div class="wishlist">
															<a onclick="addToWishList('40');" title="" class="icon-heart product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Wish List">&nbsp;</a>
														</div>
														<div class="compare">
															<a onclick="addToCompare('40');" title="" class="icon-exchange product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Compare">&nbsp;</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="banner0" class="box banner">&nbsp;</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#banner0 div:first-child').css('display', 'block');
});
</script>
					</div>
				</aside>
			</div>
		</div>
	</section>
	
	<?php $this->load->view( 'website/common/footer' ); ?>
</section>
</body>
</html>
<?php
	// default data
	$review_alias = get_review_alias($_SERVER['REQUEST_URI']);
	
	// get item
	preg_match('/item\/([a-z0-9\-]+)/i', $_SERVER['REQUEST_URI'], $match);
	$item_alias = (isset($match[1])) ? $match[1] : '';
	$item = $this->Item_model->get_by_id(array( 'alias' => $item_alias ));
	$item = $this->Item_model->get_by_id(array( 'id' => $item['id'], 'tag_include' => true ));
	
	// build breadcrumb
	$param_breadcrumb = array( 'title_list' => array( ) );
	$param_breadcrumb['title_list'][] = array( 'link' => base_url(), 'title' => 'Home', 'class' => 'first' );
	$param_breadcrumb['title_list'][] = array( 'link' => $item['category_link'], 'title' => $item['category_name'], 'class' => '' );
	$param_breadcrumb['title_list'][] = array( 'link' => $item['category_sub_link'], 'title' => $item['category_sub_name'], 'class' => '' );
	$param_breadcrumb['title_list'][] = array( 'link' => $item['item_link'], 'title' => $item['name'], 'class' => '' );
	
	// multi title
	$array_multi_title = $this->Item_Multi_Title_model->get_array(array( 'item_id' => $item['id'] ));
	
	// review
	$array_item_review = $this->Item_Review_model->get_array(array( 'item_id' => $item['id'] ));
	$review = $this->Item_Review_model->get_by_id(array( 'item_id' => $item['id'], 'alias' => $review_alias ));
?>
<?php $this->load->view( 'website/common/meta' ); ?>
<body id="offcanvas-container" class="offcanvas-container layout-fullwidth fs12 page-product">
<section id="page" class="offcanvas-pusher" role="main">
	<?php $this->load->view( 'website/common/header' ); ?>
	
	<section id="columns" class="offcanvas-siderbars">
		<?php $this->load->view( 'website/common/breadcrumb', $param_breadcrumb ); ?>
		
		<div class="container">
			<div class="row">
				<section class="col-lg-9 col-md-9 col-sm-12 col-xs-12 main-column">
					<div id="content" class="product-detail">
						<div class="product-info">
							<div class="row">
								<div class="col-lg-5 col-sm-5 col-xs-12 image-container">
									<span class="product-label-special"><span>Sale</span></span>
									<div class="image">
										<a href="<?php echo $item['item_link']; ?>" class="colorbox cboxElement">
											<img src="<?php echo $item['image']; ?>">
										</a>
									</div>
								</div>
								<div class="col-lg-7 col-sm-7 col-xs-12">
									<h1><?php echo $item['name']; ?></h1>
									<div class="description">
										<p><b>Product Code:</b><?php echo $item['code']; ?></p>
										<p>
											<b>Availability:</b>
											<span class="availability"><?php echo $item['status_stock']; ?></span>
										</p>
									</div>
									<div class="price">
										<div class="price-gruop">
											<span class="text-price">Price:</span>
											<?php if (!empty($item['price_old'])) { ?>
											<span class="price-old"><?php echo $item['price_old_text']; ?></span>
											<?php } ?>
											<span class="price-new"><?php echo $item['price_show_text']; ?></span>
										</div>
										<div class="other-price"><br /></div>
									</div>
									<div class="review">
										<div>
											<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo count($array_item_review); ?> reviews</a> |
											<a onclick="$('a[href=\'#tab-review\']').trigger('click');">Write a review</a>
										</div>
									</div>
									<?php if (count($item['array_tag']) > 0) { ?>
									<div class="tags">
										<b>Tags:</b>
										<?php foreach ($item['array_tag'] as $row) { ?>
										<a href="<?php echo $row['tag_link']; ?>"><?php echo $row['tag_name']; ?></a>
										<?php } ?>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
						
						<?php if (count($review) > 0) { ?>
						<div style="margin: 10px 0;">
							<div style="background: #FFFFFF; padding: 10px;">
								<div style="padding: 0 0 10px 0;"><strong><?php echo $review['name']; ?></strong> by <strong><?php echo $review['user']; ?></strong></div>
								<div><?php echo $review['desc']; ?></div>
							</div>
						</div>
						<?php } ?>
						
						<div class="tabs-group box">
							<div id="tabs" class="htabs">
								<ul class="nav nav-tabs box-heading clearfix">
									<li class="first"><a class="selected" href="#tab-description">Description</a></li>
									<li><a href="#tab-review">Reviews (<?php echo count($array_item_review); ?>)</a></li>
									<li class="last"><a href="#tab-customtab">Custom Block Tab</a></li>
								</ul>
							</div>
							<div style="display: block;" id="tab-description" class="tab-content"><?php echo $item['desc']; ?></div>
							
							<!-- review -->
							<div style="display: none;" id="tab-review" class="tab-content no-margin">
								<div id="review">
									<?php if (count($array_item_review) == 0) { ?>
									<div class="content">There are no reviews for this product.</div>
									<?php } else { ?>
									<?php foreach ($array_item_review as $row) { ?>
									<div style="padding: 0 0 25px 0;">
										<h4><a href="<?php echo $row['link']; ?>"><?php echo $row['name']; ?></a></h4>
										<div><?php echo get_length_char($row['desc_limit'], 250, ' ... <a href="'.$row['link'].'">more</a>'); ?></div>
									</div>
									<?php } ?>
									<?php } ?>
								</div>
								
								<h2 id="review-title">Write a review</h2>
								<form id="form-review">
									<input type="hidden" name="item_id" value="<?php echo $item['id']; ?>" />
									<div class="form-group">
										<label>Your Name:</label>
										<p><input name="user" type="text"></p>
									</div>
									<div class="form-group">
										<label>Review Title:</label>
										<p><input name="name" type="text"></p>
									</div>
									<div class="form-group">
										<label>Your Review:</label>
										<p><textarea name="desc" cols="50" rows="8" class="form-control"></textarea></p>
									</div>
									<div class="form-group">
										<p><span style="font-size: 11px;"><span style="color: #FF0000;">Note:</span>HTML is not translated!</span></p>
										<p>
											<strong>Rating:</strong>
											<span>Bad</span>
											<input name="rating" value="1" type="radio" />
											<input name="rating" value="2" type="radio" />
											<input name="rating" value="3" type="radio" checked />
											<input name="rating" value="4" type="radio" />
											<input name="rating" value="5" type="radio" />
											<span>Good</span>
										</p>
										<p><strong>Enter the code in the box below:</strong></p>
										<img src="<?php echo base_url('static/img/captcha/'); ?>" />
										<p><input name="captcha" type="text"></p>
									</div>
									<div class="buttons no-padding">
										<div class="pull-right"><a id="button-review" class="button">Continue</a></div>
									</div>
								</form>
							</div>
							<!-- end review -->
							
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
$('#tabs a').tabs();

// review
$('#form-review').submit(function(event) {
	$('#button-review').click();
	event.preventDefault();
});
$('#form-review').validate({
	rules: {
		user: { required: true },
		name: { required: true },
		desc: { required: true },
		captcha: { required: true }
	}
});
$('#button-review').click(function(event) {
	event.preventDefault();
	if (! $('#form-review').valid()) {
		return false;
	}
	
	var param = {
		action: 'send_review',
		item_id: $('[name="item_id"]').val(),
		user: $('[name="user"]').val(),
		name: $('[name="name"]').val(),
		desc: $('[name="desc"]').val(),
		rating: $('input[name=rating]:checked', '#form-review').val(),
		captcha: $('[name="captcha"]').val()
	}
	Func.ajax({ url: web.host + 'ajax', param: param, callback: function(result) {
		if (result.status) {
			$.notify(result.message, "success");
			$('#form-review')[0].reset();
		} else {
			$.notify(result.message, "error");
		}
	} });
});
</script>
				</section>
				
				<aside id="oc-column-right" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 offcanvas-sidebar">
					<div id="column-right" class="sidebar">
						<?php $this->load->view( 'website/common/sort_brand' ); ?>
						<?php $this->load->view( 'website/common/best_seller' ); ?>
						<?php $this->load->view( 'website/common/banner_right' ); ?>
					</div>
				</aside>
			</div>
		</div>
	</section>
	
	<?php $this->load->view( 'website/common/footer' ); ?>
</section>
</body>
</html>
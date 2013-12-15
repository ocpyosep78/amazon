<?php
	// popular item
	$param_item_popular = array(
		'date_update' => date("Y-m-d", strtotime("-2 Weeks")),
		'item_status_id' => ITEM_STATUS_APPROVE,
		'sort' => '[{"property":"total_view","direction":"DESC"}]',
		'limit' => 4
	);
	$array_item_popular = $this->Item_model->get_array($param_item_popular);
	
	// history item
	$item_cookie = $this->Item_model->get_cookie();
	$param_item_history = array(
		'item_list' => (count($item_cookie) >= 4) ? implode(',', $item_cookie) : '',
		'sort' => '{"is_custom":"1","query":"RAND()"}',
		'limit' => 4
	);
	$array_item_history = $this->Item_model->get_array($param_item_history);
	
	// tag samsung
	$tag = $this->Tag_model->get_by_id(array( 'alias' => 'samsung' ));
	$param_item_samsung = array(
		'tag_id' => $tag['id'],
		'item_status_id' => ITEM_STATUS_APPROVE,
		'sort' => '{"is_custom":"1","query":"RAND()"}',
		'limit' => 4
	);
	$array_item_samsung = $this->Item_Tag_model->get_array($param_item_samsung);
	
	// merge item
	$array_item = array_merge($array_item_popular, $array_item_history);
	foreach ($array_item_samsung as $row) {
		$temp = array(
			'name' => $row['item_name'],
			'item_link' => $row['item_link'],
			'desc_limit' => $row['desc_limit'],
			'image_link' => $row['image_link'],
			'price_show_text' => $row['price_show_text']
		);
		$array_item[] = $temp;
	}
?>
<?php $this->load->view( 'website/common/meta' ); ?>
<body id="offcanvas-container" class="offcanvas-container layout-fullwidth fs12 page-common-home ">
	<section id="page" class="offcanvas-pusher" role="main">
		<?php $this->load->view( 'website/common/header' ); ?>
		
		<section class="pav-promotion" id="pav-promotion">
			<div class="container">
				<div class="in-border"><div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-lg-offset-3 col-md-offset-3"><div class="row">
					<div class="col-lg-9 col-md-9">
						<div id="pavcontentslider9" class="carousel slide pavcontentslider hidden-xs">
							<div class="carousel-inner">
								<div class="item active">
									<a href="#"><img src="http://shoperindo.com/shopermarket/shop/layer-slider-4-698x369.png" alt="spring"></a>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-lg-3 col-md-3"><div class="box pav-custom  custom-block hidden-xs hidden-sm">
						<div class="box-heading"><span class="t-color">Biarin</span> Saja</div>
						<div class="box-content"><ul class="custom">
							<li class="first">
								<i class="icon-legal">&nbsp;</i>
								<h3>Best price</h3>
								<p>Lorem Ipsum roin gra nibh vel velit</p>
							</li>
							<li>
								<i class="icon-rocket">&nbsp;</i>
								<h3>Ontime delivery</h3>
								<p>Aenean sollicitudin lorem quis bibendum</p>
							</li>
							<li>
								<i class="icon-money">&nbsp;</i>
								<h3>Secure payment</h3>
								<p>Duis sed odio sit amet nibh vulputate</p>
							</li>
							<li class="last">
								<i class="icon-shopping-cart">&nbsp;</i>
								<h3>Free shipping</h3>
								<p>Lorem Ipsum roin gra nibh vel velit</p>
							</li>
						</ul></div>
					</div></div>
				</div></div></div></div>
			</div>
		</section>
		
		<section id="sys-notification">
			<div class="container">
				<div id="notification"></div>
			</div>
		</section>
		
		<section id="columns" class="offcanvas-siderbars">
			<div class="row visible-xs"><div class="container"> 
				<div class="offcanvas-sidebars-buttons">
					<button style="display: none;" type="button" data-for="column-left" class="pull-left btn btn-danger"><i class="glyphicon glyphicon-indent-left"></i> Sidebar Left</button>
					<button style="display: block;" type="button" data-for="column-right" class="pull-right btn btn-danger">Sidebar Right <i class="glyphicon glyphicon-indent-right"></i></button>
				</div>
			</div></div>
			<div class="container">
				<div class="row">
					<section class="col-lg-9 col-md-9 col-sm-12 col-xs-12 main-column">         
						<div id="content">
							<div class="content-top"><div class=" box productcarousel">
								<div class="box-heading"><span>Tampilkan di bawah ini : baris 1 popular item, baris ke 2 // tag samsung, dan baris ke 3 // history item</span></div>
								<div class="box-content"><div class="box-products slide" id="productcarousel6"><div class="carousel-inner">
									<div class="item active product-grid no-margin"><div class="row">
										<?php foreach ($array_item as $row) { ?>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 col-fullwidth">
											<div class="product-block">
												<div class="image">
													<span class="product-label-special"><span>Sale</span></span>
													<a href="<?php echo $row['item_link']; ?>"><img src="<?php echo $row['image_link']; ?>" title="<?php echo $row['name']; ?>" alt="<?php echo $row['name']; ?>" class="img-responsive"></a>
													<a href="<?php echo $row['item_link']; ?>" class="info-view colorbox product-zoom cboxElement" rel="colorbox" title="<?php echo $row['name']; ?>"><i class="icon-zoom-in"></i></a>
													<div class="img-overlay"></div>
												</div>
												<div class="product-meta">
													<div class="left">
														<h3 class="name"><a href="<?php echo $row['item_link']; ?>"><?php echo get_length_char($row['name'], 40, ' ...'); ?></a></h3>
														<p class="description"><?php echo $row['desc_limit']; ?></p>
													</div>
													<div class="right">
														<div class="price">
															<?php if (!empty($row['price_old'])) { ?>
															<span class="price-old"><?php echo $row['price_old_text']; ?></span>
															<?php } ?>
															<span class="price-new"><?php echo $row['price_show_text']; ?></span>
														</div>
														<div class="action">
															<div class="cart">
																<input value="?" onclick="addToCart('43');" class="product-icon icon-shopping-cart" data-placement="top" data-toggle="tooltip" data-original-title="Add to Cart" type="button" />
															</div>
															<div class="wishlist">
																<a onclick="addToWishList('43');" title="" class="icon-heart product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Wish List">&nbsp;</a>
															</div>
															<div class="compare">
																<a onclick="addToCompare('43');" title="" class="icon-exchange product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Compare">&nbsp;</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php } ?>
									</div></div>
								</div></div></div>
							</div></div>
							<h1 style="display: none;">shopermarket Mega Shop</h1>
						</div>
					</section>
					
					<aside id="oc-column-right" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 offcanvas-sidebar">
						<div id="column-right" class="sidebar">
							<div id="productdeals" class="box productdeals box-highlight">
								<div id="banner0" class="box banner">
									<div style="display: block;"><img src="http://shoperindo.com/shopermarket/shop/banner-macbook-270x230.jpg" alt="Macbook Pro" title="Macbook Pro"></div>
								</div>
								<div class="box-heading">
									<span>Latest Deals</span>
								</div>
								<div class="box-content">&nbsp;</div> 
							</div>
						</div>
					</aside>
				</div>
			</div>
	
	<?php $this->load->view( 'website/common/footer' ); ?>
</section>
</body>
</html>
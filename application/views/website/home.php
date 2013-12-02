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
<body id="offcanvas-container" class="offcanvas-container layout-fullwidth fs12 page-product">
<section id="page" class="offcanvas-pusher" role="main">
	<?php $this->load->view( 'website/common/header' ); ?>
	
	<section id="columns" class="offcanvas-siderbars">
		<?php $this->load->view( 'website/common/breadcrumb' ); ?>
		
		<div class="container">
			<div class="row">
				<section class="col-lg-9 col-md-9 col-sm-12 col-xs-12 main-column">
					<div id="content">
						<div class="product-filter clearfix">
							<div class="display">
								<span style="float: left;">Display:</span>
								<a class="list active" onclick="display_item('list');">List</a>
								<a class="grid" onclick="display_item('grid');">Grid</a>
							</div>
						</div>
						
						<div class="product-list"><div class="products-block"><div class="row">
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
											<h3 class="name"><a href="<?php echo $row['item_link']; ?>"><?php echo get_length_char($row['name'], 45, ' ...'); ?></a></h3>
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
						</div></div></div>
					</div>
					<script type="text/javascript">
						var view_type = get_local('view_type');
						display_item(view_type);
					</script>
				</section>
				
				<aside id="oc-column-right" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 offcanvas-sidebar">
					<div id="column-right" class="sidebar">
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
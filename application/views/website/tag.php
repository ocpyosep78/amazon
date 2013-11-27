<?php
	// get tag
	preg_match('/tag\/([^\/]+)/i', $_SERVER['REQUEST_URI'], $match);
	$tag_name = (isset($match[1])) ? $match[1] : '';
	$tag = $this->Tag_model->get_by_id(array( 'alias' => $tag_name ));
	
	// redirect
	if (count($tag) == 0) {
		header("HTTP/1.1 301 Moved Permanently");
		header('Location: '.base_url());
		exit;
	}
	
	// build breadcrumb
	$param_breadcrumb['title_list'][] = array( 'link' => base_url(), 'title' => 'Home', 'class' => 'first' );
	$param_breadcrumb['title_list'][] = array( 'link' => 'href', 'title' => 'Tag : '.$tag['name'], 'class' => '' );
	
	// page info
	$page_sort = (isset($_POST['page_sort'])) ? $_POST['page_sort'] : '[{"property":"date_update","direction":"DESC"},{"property":"Item.id","direction":"DESC"}]';
	$page_active = (isset($_POST['page_active'])) ? $_POST['page_active'] : 1;
	$page_limit = (isset($_POST['page_limit'])) ? $_POST['page_limit'] : 12;
	$page_offset = ($page_active * $page_limit) - $page_limit;
	
	$param_item = array(
		'tag_id' => $tag['id'],
		'item_status_id' => ITEM_STATUS_APPROVE,
		'sort' => $page_sort,
		'start' => $page_offset,
		'limit' => $page_limit
	);
	$array_item = $this->Item_Tag_model->get_array($param_item);
	$total_item = $this->Item_Tag_model->get_count($param_item);
	$page_total = ceil($total_item / $page_limit);
	
	/* region form */
	
	$array_sort[] = array( 'value' => '[{"property":"date_update","direction":"DESC"},{"property":"Item.id","direction":"DESC"}]', 'label' => 'Default' );
	$array_sort[] = array( 'value' => '[{"property":"price_show","direction":"ASC"}]', 'label' => 'Price (Low &gt; High)' );
	$array_sort[] = array( 'value' => '[{"property":"price_show","direction":"DESC"}]', 'label' => 'Price (High &gt; Low)' );
	
	$array_limit = array( array( 'value' => 4 ), array( 'value' => 8 ), array( 'value' => 12 ), array( 'value' => 25 ), array( 'value' => 50 ), array( 'value' => 75 ), array( 'value' => 100 ) );
	
	/* end region form */
	
	/*
	$page_active = 10;
	$page_total = 20;
	/*	*/
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
					<div id="content">
						<div class="hidden">
							<form id="form-hidden" method="post">
								<input type="hidden" name="page_sort" value="<?php echo htmlentities($page_sort); ?>" />
								<input type="hidden" name="page_active" value="<?php echo 1; ?>" />
								<input type="hidden" name="page_limit" value="<?php echo $page_limit; ?>" />
							</form>
						</div>
						
						<div class="product-filter clearfix">
							<div class="display">
								<span style="float: left;">Display:</span>
								<a class="list active" onclick="display_item('list');">List</a>
								<a class="grid" onclick="display_item('grid');">Grid</a>
							</div>
							
							<div class="sort">
								<span>Sort By:</span>
								<select class="change_sort">
									<?php echo ShowOption(array( 'Array' => $array_sort, 'ArrayID' => 'value', 'ArrayTitle' => 'label', 'Selected' => $page_sort, 'WithEmptySelect' => false )); ?>
								</select>
							</div>
							
							<div class="limit">
								<span>Show:</span>
								<select class="change_limit">
									<?php echo ShowOption(array( 'Array' => $array_limit, 'ArrayID' => 'value', 'ArrayTitle' => 'value', 'Selected' => $page_limit, 'WithEmptySelect' => false )); ?>
								</select>
							</div>
						</div>
						
						<div class="product-list"><div class="products-block"><div class="row">
							<?php foreach ($array_item as $row) { ?>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 col-fullwidth">
								<div class="product-block">
									<div class="image">
										<span class="product-label-special"><span>Sale</span></span>
										<a href="<?php echo $row['item_link']; ?>"><img src="<?php echo $row['item_image']; ?>" title="<?php echo $row['item_name']; ?>" alt="<?php echo $row['item_name']; ?>" class="img-responsive"></a>
										<a href="<?php echo $row['item_link']; ?>" class="info-view colorbox product-zoom cboxElement" rel="colorbox" title="<?php echo $row['item_name']; ?>"><i class="icon-zoom-in"></i></a>
										<div class="img-overlay"></div>
									</div>
									<div class="product-meta">
										<div class="left">
											<h3 class="name"><a href="<?php echo $row['item_link']; ?>"><?php echo get_length_char($row['item_name'], 50, ' ...'); ?></a></h3>
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
						
						<div class="pagination">
							<div class="links">
								<?php if ($page_active > 1) { ?>
								<?php $page_counter = $page_active - 1; ?>
								<a data-page-value="<?php echo 1; ?>">|&lt;</a>
								<a data-page-value="<?php echo $page_counter; ?>">&lt;</a>
								<?php } ?>
								
								<?php for ($i = -4; $i <= 4; $i++) { ?>
									<?php $page_counter = $page_active + $i; ?>
									<?php if ($page_counter > 0 && $page_counter <= $page_total) { ?>
									<?php if ($i == 0) { ?>
									<b><?php echo $page_counter; ?></b>
									<?php } else { ?>
									<a data-page-value="<?php echo $page_counter; ?>"><?php echo $page_counter; ?></a>
									<?php } ?>
									<?php } ?>
								<?php } ?>
								
								<?php if ($page_active < $page_total) { ?>
								<?php $page_counter = $page_active + 1; ?>
								<a data-page-value="<?php echo $page_counter; ?>">&gt;</a>
								<a data-page-value="<?php echo $page_total; ?>">&gt;|</a>
								<?php } ?>
							</div>
							<div class="results"><?php echo "Showing ".($page_offset + 1)." to ".($page_offset + count($array_item))." of $total_item ($page_total Pages)"; ?></div>
						</div>
					</div>
					<script type="text/javascript">
						var view_type = get_local('view_type');
						display_item(view_type);
						
						// search
						$('.change_sort').change(function() {
							$('#form-hidden [name="page_sort"]').val($(this).val());
							$('#form-hidden').submit();
						});
						$('.change_limit').change(function() {
							$('#form-hidden [name="page_limit"]').val($(this).val());
							$('#form-hidden').submit();
						});
						$('.pagination .links a').click(function() {
							$('#form-hidden [name="page_active"]').val($(this).data('page-value'));
							$('#form-hidden').submit();
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
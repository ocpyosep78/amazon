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
						<h1>Desktops</h1>
						<div class="category-info clearfix">
							<div class="category-description wrapper">
								<p>Tempora, ducimus, asperiores repudiandae cupiditate natus velit corrupti! Labore, doloremque quis harum nemo nostrum facere unde esse nobis quo voluptatem ipsa iure cumque atque exercitationem voluptas animi adipisci cum maiores quidem nam eligendi maxime</p>
							</div>
						</div>
						
						<h4>Refine Search</h4>
						<div class="category-list clearfix">
							<ul class="links">
								<li class="first"><a href="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20_26">PC (5)</a></li>
								<li class="last"><a href="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20_27">Mac (1)</a></li>
							</ul>
						</div>
						
						<div class="product-filter clearfix">
							<div class="display">
								<span style="float: left;">Display:</span>
								<a class="list active">List</a>
								<a class="grid" onclick="display('grid');">Grid</a>
							</div>
							
							<div class="sort">
								<span>Sort By:</span>
								<select onchange="location = this.value;">
									<option value="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20&amp;sort=p.sort_order&amp;order=ASC" selected="selected">Default</option>
									<option value="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20&amp;sort=p.price&amp;order=ASC">Price (Low &gt; High)</option>
									<option value="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20&amp;sort=p.price&amp;order=DESC">Price (High &gt; Low)</option>
								</select>
							</div>
							
							<div class="limit">
								<span>Show:</span>
								<select onchange="location = this.value;">
									<option value="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20&amp;limit=12" selected="selected">12</option>
									<option value="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20&amp;limit=25">25</option>
									<option value="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20&amp;limit=50">50</option>
									<option value="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20&amp;limit=75">75</option>
									<option value="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20&amp;limit=100">100</option>
								</select>
							</div>
						</div>
						
						<div class="product-list"><div class="products-block"><div class="row">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 col-fullwidth">
								<div class="product-block">
									<div class="image">
										<span class="product-label-special"><span>Sale</span></span>
										<a href="#=43"><img src="<?php echo base_url('static/img/product_11-500x510.jpg'); ?>" title="MacBook" alt="MacBook" class="img-responsive"></a>
										<a href="http://parapekerja.com/opencart/image/data/demo/product_11.jpg" class="info-view colorbox product-zoom cboxElement" rel="colorbox" title="MacBook"><i class="icon-zoom-in"></i></a>
										<div class="img-overlay"></div>
									</div>
									<div class="product-meta">
										<div class="left">
											<h3 class="name"><a href="#=43">MacBook</a></h3>
											<p class="description">Intel Core 2 Duo processor Powered by an Intel Core 2 Duo processor at speeds up to 2.16GHz, th.....</p>
										</div>
										<div class="right">
											<div class="price">
												<span class="price-old">$589.50</span>
												<span class="price-new">$88.95</span>
												<span class="price-tax">Ex Tax: $74.00</span>
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
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 col-fullwidth">
								<div class="product-block">
									<div class="image">
										<a href="#=44"><img src="<?php echo base_url('static/img/product_11-500x510.jpg'); ?>" title="MacBook Air" alt="MacBook Air" class="img-responsive"></a>
										<a href="http://parapekerja.com/opencart/image/data/demo/product_23.jpg" class="info-view colorbox product-zoom cboxElement" rel="colorbox" title="MacBook Air"><i class="icon-zoom-in"></i></a>
										<div class="img-overlay"></div>
									</div>
									<div class="product-meta">
										<div class="left">
											<h3 class="name"><a href="#=44">MacBook Air</a></h3>
											<p class="description">MacBook Air is ultrathin, ultraportable, and ultra unlike anything else. But you don’t lose inches a.....</p>
										</div>
										<div class="right">
											<div class="price">
												<span class="special-price">$1,177.00</span>
												<span class="price-tax">Ex Tax: $1,000.00</span>
											</div>
											<div class="action">
												<div class="cart">
													<input value="?" onclick="addToCart('44');" class="product-icon icon-shopping-cart" data-placement="top" data-toggle="tooltip" data-original-title="Add to Cart" type="button">
												</div>
												<div class="wishlist">
													<a onclick="addToWishList('44');" title="" class="icon-heart product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Wish List">&nbsp;</a>
												</div>
												<div class="compare">
													<a onclick="addToCompare('44');" title="" class="icon-exchange product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Compare">&nbsp;</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 col-fullwidth">
								<div class="product-block">
									<div class="image">
										<span class="product-label-special"><span>Sale</span></span>
										<a href="#=29"><img src="<?php echo base_url('static/img/product_26-500x510.jpg'); ?>" title="Palm Treo Pro" alt="Palm Treo Pro" class="img-responsive"></a>
										<a href="http://parapekerja.com/opencart/image/data/demo/product_26.jpg" class="info-view colorbox product-zoom cboxElement" rel="colorbox" title="Palm Treo Pro"><i class="icon-zoom-in"></i></a>
										<div class="img-overlay"></div>
									</div>
									<div class="product-meta">
										<div class="left">
											<h3 class="name"><a href="#=29">Palm Treo Pro</a></h3>
											<p class="description">Redefine your workday with the Palm Treo Pro smartphone. Perfectly balanced, you can respond to busi.....</p>
										</div>
										<div class="right">
										<div class="price">
											<span class="price-old">$330.99</span>
											<span class="price-new">$96.00</span>
											<span class="price-tax">Ex Tax: $80.00</span>
										</div>
										<div class="action">
											<div class="cart">
												<input value="?" onclick="addToCart('29');" class="product-icon icon-shopping-cart" data-placement="top" data-toggle="tooltip" data-original-title="Add to Cart" type="button">
											</div>
											<div class="wishlist">
												<a onclick="addToWishList('29');" title="" class="icon-heart product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Wish List">&nbsp;</a>
											</div>
											<div class="compare">
												<a onclick="addToCompare('29');" title="" class="icon-exchange product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Compare">&nbsp;</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 col-fullwidth">
								<div class="product-block">
									<div class="image">
										<a href="#=33"><img src="<?php echo base_url('static/img/product_11-500x510.jpg'); ?>" title="Samsung SyncMaster 941BW" alt="Samsung SyncMaster 941BW" class="img-responsive"></a>
										<a href="http://parapekerja.com/opencart/image/data/demo/product_29.jpg" class="info-view colorbox product-zoom cboxElement" rel="colorbox" title="Samsung SyncMaster 941BW"><i class="icon-zoom-in"></i></a>
										<div class="img-overlay"></div>
									</div>
									<div class="product-meta">
										<div class="left">
											<h3 class="name"><a href="#=33">Samsung SyncMaster 941BW</a></h3>
											<p class="description">Imagine the advantages of going big without slowing down. The big 19" 941BW monitor combines wide as.....</p>
										</div>
										<div class="right">
											<div class="price">
												<span class="special-price">$237.00</span>
												<span class="price-tax">Ex Tax: $200.00</span>
											</div>
											<div class="action">
												<div class="cart">
													<input value="?" onclick="addToCart('33');" class="product-icon icon-shopping-cart" data-placement="top" data-toggle="tooltip" data-original-title="Add to Cart" type="button">
												</div>
												<div class="wishlist">
													<a onclick="addToWishList('33');" title="" class="icon-heart product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Wish List">&nbsp;</a>
												</div>
												<div class="compare">
													<a onclick="addToCompare('33');" title="" class="icon-exchange product-icon" data-placement="top" data-toggle="tooltip" data-original-title="Add to Compare">&nbsp;</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div></div></div>
						
						<div class="pagination">
							<div class="links">
								<b>1</b>
								<a href="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20&amp;page=2">2</a>
								<a href="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20&amp;page=2">&gt;</a>
								<a href="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20&amp;page=2">&gt;|</a>
							</div>
							<div class="results">Showing 1 to 12 of 15 (2 Pages)</div>
						</div>
					</div>
<script type="text/javascript">
function display(view) {
	if (view == 'list') {
		$('.product-grid').attr('class', 'product-list');
		
		$('.products-block  .product-block').each(function(index, element) {
 
			 $(element).parent().addClass("col-fullwidth");
		});		
		
		$('.display').html('<span style="float: left;">Display:</span><a class="list active"><i class="icon-th-list"></i><em>List</em></a><a class="grid" onclick="display(\'grid\');"><i class="icon-th"></i><em>Grid</em></a>');
	
		$.totalStorage('display', 'list'); 
	} else {
		$('.product-list').attr('class', 'product-grid');
		
		$('.products-block  .product-block').each(function(index, element) {
			 $(element).parent().removeClass("col-fullwidth");  
		});	
					
		$('.display').html('<span style="float: left;">Display:</span><a class="list" onclick="display(\'list\');"><i class="icon-th-list"></i><em>List</em></a><a class="grid active"><i class="icon-th"></i><em>Grid</em></a>');
	
		$.totalStorage('display', 'grid');
	}
}

/*
view = $.totalStorage('display');

if (view) {
	display(view);
} else {
	display('grid');
}
/*	*/
</script>
				</section>
				
				<aside id="oc-column-right" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 offcanvas-sidebar">
					<div id="column-right" class="sidebar">
						<?php $this->load->view( 'website/common/sort_brand' ); ?>
						
						<div class="box box-product bestseller">
							<div class="box-heading"><span>Bestsellers</span></div>
							<div class="box-content">
								<div class="product-list">pasang widget disini</div>
							</div>
						</div>
						
						<div id="banner0" class="box banner">
							<div style="display: block;"><img src="<?php echo base_url('static/img/banner-macbook-270x230.jpg'); ?>" alt="Macbook Pro" title="Macbook Pro"></div>
						</div>
					</div>
				</aside>
			</div>
		</div>
	</section>
	
	<?php $this->load->view( 'website/common/footer' ); ?>
</section>
</body>
</html>
<?php
	// build breadcrumb
	$param_breadcrumb['title_list'][] = array( 'link' => base_url(), 'title' => 'Home', 'class' => 'first' );
	$param_breadcrumb['title_list'][] = array( 'link' => 'href', 'title' => $page_static['name'], 'class' => '' );
	
	// print_r($page_static['page_link']); exit;
	
	// meta
	$array_seo = array(
		'title' => $this->config->item('site_title').' - '.$page_static['name'],
		'array_meta' => array( ),
		'array_link' => array( )
	);
	$array_seo['array_meta'][] = array( 'name' => 'Title', 'content' => $page_static['name'] );
	$array_seo['array_meta'][] = array( 'name' => 'Description', 'content' => $page_static['desc_limit'] );
	$array_seo['array_meta'][] = array( 'name' => 'Keywords', 'content' => $this->config->item('site_title').', '.$page_static['name'] );
	$array_seo['array_link'][] = array( 'rel' => 'canonical', 'href' => $page_static['page_link'] );
	$array_seo['array_link'][] = array( 'rel' => 'image_src', 'href' => base_url('static/img/shop.png') );
?>
<?php $this->load->view( 'website/common/meta', $array_seo ); ?>
<body id="offcanvas-container" class="offcanvas-container layout-fullwidth fs12 page-product">
<section id="page" class="offcanvas-pusher" role="main">
	<?php $this->load->view( 'website/common/header' ); ?>
	
	<section id="columns" class="offcanvas-siderbars">
		<?php $this->load->view( 'website/common/breadcrumb', $param_breadcrumb ); ?>
		
		<div class="container">
			<div class="row">
				<section class="col-lg-9 col-md-9 col-sm-12 col-xs-12 main-column">
					<div class="product-detail" id="content">
						<div class="product-info">
							<div class="row"><?php echo $page_static['desc']; ?></div>
						</div>
					</div>
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
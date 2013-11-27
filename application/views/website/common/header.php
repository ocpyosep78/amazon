<?php
	// search
	$namelike = get_search($_SERVER['REQUEST_URI']);
	
	$array_category = $this->Category_model->get_array_with_sub();
?>
<header id="header">
	<section id="topbar">
		<div class="container">
			<div class="navbar-header pull-left hidden-xs hidden-sm">
				<div class="clearfix">
					<div class="login links">SHOPER MARKET - The large market online</div>
				</div>
			</div>
			
			<div class="show-mobile hidden-lg hidden-md">
				<div id="search_mobile" class="search pull-left">
					<div class="quickaccess-toggle">Find</div>
					<div class="inner-toggle">
						<input name="search" placeholder="Search" class="form-control input-search" type="text" value="<?php echo $namelike; ?>" />
						<div class="button-search-mobile"><span class="icon-search"></span></div>
					</div>
				</div>
			</div>
			
			<div class="support pull-right hidden-xs hidden-sm">
				<div class="pull-right right"></div>
				<div class="pull-right left">
					<ul class="links hidden-xs hidden-sm hidden-md">
						<li class="last first"><i class="icon-skype">&nbsp;</i>Welcome Sign in or Register</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	
	<section id="header-main">
		<div class="container">
			<div class="header-wrap">
				<div class="pull-left inner"><div id="logo">
					<a href="<?php echo base_url(); ?>">
						<img src="<?php echo base_url('static/img/logo.png'); ?>" title="shopermarket Mega Shop" alt="shopermarket Mega Shop" />
					</a>
				</div></div>
				
				<div class="pull-right inner">
					<section id="pav-mainnav">
						<div class="container"><div class="pav-megamenu"><div class="navbar navbar-default"><div id="mainmenutop" class="megamenu" role="navigation">
							<div class="navbar-header">
								<a href="javascript:;" data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</a>
								<div class="collapse navbar-collapse navbar-ex1-collapse">
									<ul class="nav navbar-nav megamenu">
										<li class="home first">
											<a href="<?php echo base_url(); ?>"><span class="menu-title">Home</span></a>
										</li>
										<li class="parent dropdown pav-parrent"></li>
										<li class="">
											<a href="#">
												<span class="menu-title">Holiday Shopping Guide</span>
											</a>
										</li>
										<li class="">
											<a href="#">
												<span class="menu-title">Deals</span>
											</a>
										</li>
										<li class="last">
											<a href="#">
												<span class="menu-title">Blog</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div></div></div></div>
					</section>
				</div>
			</div>
		</div>
	</section>
	
	<section id="header-bottom" class="hidden-xs hidden-sm">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-sm-3 col-md-3 hidden-xs hidden-sm">
					<div id="pav-verticalmenu" class="pav-dropdown">
						<div class="menu-heading d-heading">
							<h4>categories <span class="arrow-down pull-right"><span></span></span></h4>
						</div>
						<div class="menu-content d-content"><div class="pav-verticalmenu"><div class="navbar navbar-default"><div id="mainmenutop" class="verticalmenu" role="navigation"><div class="navbar-header"><div class="collapse navbar-collapse navbar-ex1-collapse">
							<ul class="nav navbar-nav verticalmenu">
								<?php foreach ($array_category as $row) { ?>
								<li class="parent dropdown pav-parrent">
									<a href="<?php echo $row['link']; ?>" class="dropdown-toggle" data-toggle="dropdown"><span class="menu-title"><?php echo $row['name']; ?></span><b class="caret"></b></a>
									<div class="dropdown-menu" style="width:700px"><div class="dropdown-menu-inner"><div class="row">
										<div class="mega-col col-md-6"><div class="mega-col-inner">
											<div class="pavo-widget" id="wid-7">
												<?php foreach ($row['category_sub'] as $child) { ?>
												<h3 class="menu-title"><a href="<?php echo $child['link']; ?>"><span><?php echo $child['name']; ?></span></a></h3>
												<?php } ?>
											</div>
										</div></div>
										<div class="mega-col col-md-6"><div class="mega-col-inner">
											<div class="pavo-widget" id="wid-10">
												<h3 class="menu-title"><span>Image Sub Verticalmenu</span></h3>
												<?php if (!empty($row['image_link'])) { ?>
												<div class="widget-image">
													<div class="widget-inner clearfix">
														<div><img src="<?php echo $row['image_link']; ?>" alt="" title=""></div>
													</div>
												</div>
												<?php } ?>
											</div>
										</div></div>
									</div></div></div>
								</li>
								<?php } ?>
							</ul>
						</div></div></div></div></div></div>
					</div>
				</div>
				<div class="col-lg-7 col-sm-6 col-md-6 col-xs-12">
					<div id="search">
						<input name="search" placeholder="Search" class="form-control input-search" type="text" value="<?php echo $namelike; ?>" />
						<div class="button-search"><span class="icon-search"></span></div>
					</div>
				</div>
				<div class="col-lg-2 col-sm-6 col-md-3 col-xs-12">
					<div class="cart-top"><div id="cart" class="clearfix"><div class="heading">
						<i class="icon-shopping-cart"></i>
						<!--
						<div class="cart-inner">
							<h4>Shdasdas Cart</h4>
							<a><span id="cart-total">0 item(s) - $0.00</span></a>
						</div>
						-->
					</div></div></div>
				</div>
			</div>
		</div>
	</section>
</header>

<section id="sys-notification">
	<div class="container">
		<div id="notification"></div>
	</div>
</section>
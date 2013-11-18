<?php
	$about_us = $this->Page_Static_model->get_by_id(array( 'alias' => 'widget-about-us-footer' ));
	$copyright = $this->Page_Static_model->get_by_id(array( 'alias' => 'widget-copyright' ));
?>
<footer id="footer">
	<div class="footer-top"><div class="container"><div class="custom"><div class="row">
		<div class="col-lg-12 col-md-12"><div id="pavcarousel9" class="carousel slide pavcarousel hidden-xs"><div class="carousel-inner"><div class="row item active">
			<div class="col-lg-2 col-xs-6 col-sm-2">
				<div class="item-inner">
					<a href="http://parapekerja.com/opencart/index.php?route=product/manufacturer/info&amp;manufacturer_id=10">
						<img src="<?php echo base_url('static/img/chanel-logo_min-207x73.jpg'); ?>" alt="Sony" class="img-responsive"></a>
				</div>
			</div>
			<div class="col-lg-2 col-xs-6 col-sm-2">
				<div class="item-inner">
					<a href="http://parapekerja.com/opencart/index.php?route=product/manufacturer/info&amp;manufacturer_id=6">
						<img src="<?php echo base_url('static/img/karaca_min-207x73.jpg'); ?>" alt="Palm" class="img-responsive"></a>
				</div>
			</div>
			<div class="col-lg-2 col-xs-6 col-sm-2">
				<div class="item-inner">
					<a href="http://parapekerja.com/opencart/index.php?route=product/manufacturer/info&amp;manufacturer_id=7">
						<img src="<?php echo base_url('static/img/puma_min-207x73.jpg'); ?>" alt="puma" class="img-responsive"></a>
				</div>
			</div>
			<div class="col-lg-2 col-xs-6 col-sm-2">
				<div class="item-inner">
					<a href="http://parapekerja.com/opencart/index.php?route=product/manufacturer/info&amp;manufacturer_id=7">
						<img src="<?php echo base_url('static/img/lanvin-logo_min-207x73.jpg'); ?>" alt="Lanvin" class="img-responsive"></a>
				</div>
			</div>
			<div class="col-lg-2 col-xs-6 col-sm-2">
				<div class="item-inner">
					<a href="http://parapekerja.com/opencart/index.php?route=product/manufacturer/info&amp;manufacturer_id=7">
						<img src="<?php echo base_url('static/img/mango-logo_min-207x73.jpg'); ?>" alt="mango" class="img-responsive"></a>
				</div>
			</div>
		</div></div></div></div>
	</div></div></div></div>
	<div class="footer-center"><div class="container"><div class="row">
		<div class="column col-xs-12 col-sm-12 col-lg-3">
			<div class="box about-us">
				<div class="box-heading"><span>About Us</span></div>
				<div class="box-content">
					<p><?php echo $about_us['desc']; ?></p>
					<p><a class="link-more" href="<?php echo base_url('about-us'); ?>" title="About Us"><i class="icon-expand-alt">&nbsp;</i><span>view more</span></a></p>
				</div>
				<div><strong>Subscribe</strong></div>
				<div><form id="form-subscribe">
					<input type="text" name="email" value="" />
					<input type="button" name="submit" value="Send" class="send-subscribe" />
				</form></div>
			</div>
			<script type="text/javascript">
			$('#form-subscribe').validate({
				rules: { email: { required: true, email: true } }
			});
			$('.send-subscribe').click(function() {
				if (! $('#form-subscribe').valid()) {
					return false;
				}
				
				var param = { action: 'mail_subscriber', email: $('#form-subscribe [name="email"]').val() }
				Func.ajax({ url: web.host + 'ajax', param: param, callback: function(result) {
					if (result.status) {
						$.notify(result.message, "success");
						$('#form-subscribe')[0].reset();
					} else {
						$.notify(result.message, "error");
					}
				} });
			});
			</script>
		</div>
		<div class="column col-xs-12 col-sm-6 col-lg-2">
			<div class="box info">
				<div class="box-heading"><span>Information</span></div>
				<div class="box-content">
					<ul class="list">
						<li class="first"><a href="<?php echo base_url('about-us'); ?>">About Us</a></li>
						<li><a href="<?php echo base_url('delivery-information'); ?>">Delivery Information</a></li>
						<li><a href="<?php echo base_url('privacy-policy'); ?>">Privacy Policy</a></li>
						<li><a href="<?php echo base_url('terms-conditions'); ?>">Terms &amp; Conditions</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="column col-xs-12 col-sm-6 col-lg-2">
			<div class="box extra">
				<div class="box-heading"><span>Extras</span></div>
				<div class="box-content">
					<ul class="list">
						<li class="first"><a href="<?php echo base_url('brands'); ?>">Brands</a></li>
						<li><a href="<?php echo base_url('gift-vouchers'); ?>">Gift Vouchers</a></li>
						<li><a href="<?php echo base_url('affiliates'); ?>">Affiliates</a></li>
						<li class="last"><a href="<?php echo base_url('specials'); ?>">Specials</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="column col-xs-12 col-sm-6 col-lg-3">
			<div class="box contact-us">
				<div class="box-heading"><span>Contact Us</span></div>
				<div class="box-content">
					<ul class="contact-us">
						<ul class="links social">
							<li class="first"><a class="icon-facebook" href="http://www.facebook.com/" title="Facebook">&nbsp;</a>Facebook</li>
							<li><a class="icon-google-plus" href="https://plus.google.com/" title="Google">&nbsp;</a>Google</li>
							<li><a class="icon-twitter" href="https://twitter.com/" title="Twitter">&nbsp;</a>Twitter</li>
							<li><a class="icon-dribbble" href="http://dribbble.com/" title="dribbble">&nbsp;</a>dribbble</li>
							<li class="last"><a class="icon-youtube" href="http://www.youtube.com/" title="Youtube">&nbsp;</a>Youtube</li>
						</ul>
					</ul>
				</div>
			</div>
		</div>
	</div></div></div>
	<p style="display: block;" id="back-top"><a href="#top"><i class="icon-caret-up"></i></a></p>
	<div id="powered"><div class="container">
		<div class="copyright pull-left">
			<?php echo $copyright['desc']; ?>
		</div>
		<div class="paypal pull-right"><a href="#"><img src="<?php echo base_url('static/img/paypal.png'); ?>" alt="payment"></a></div>
	</div></div>
</footer>
<?php
	// default patam
	$q = (isset($_GET['q'])) ? $_GET['q'] : '';
	$delay = $this->Configuration_model->get_by_id(array( 'name' => 'Link Delay Redirect' ));
	
	// update link out
	$item = $this->Item_model->get_by_id(array( 'link_source' => $q ));
	$this->Item_model->update_link_out(array( 'id' => $item['id'] ));
?>
<?php $this->load->view( 'website/common/meta' ); ?>
<body id="offcanvas-container" class="offcanvas-container layout-fullwidth fs12 page-product">
<section id="page" class="offcanvas-pusher" role="main">
	<?php $this->load->view( 'website/common/header' ); ?>
	
	<section id="columns" class="offcanvas-siderbars">
		<?php $this->load->view( 'website/common/breadcrumb' ); ?>
		
		<div class="container">
			<div class="row">
				<input type="hidden" name="delay" value="<?php echo $delay['value']; ?>" />
				<div style="margin: 0 0 20px 0; background: #FFFFFF;">
					<div style="padding: 10px; text-align: center;">
						<div style="padding: 0 0 8px 0">Halaman ini akan diredirect dalam <?php echo $delay['value']; ?> detik, atau silahkan klik link berikut :</div>
						<a class="link-out" href="<?php echo $q; ?>"><?php echo $q; ?></a>
						
						<div style="padding: 20px 0 0 0;">
							<a style="padding: 6px 15px; background: #e27f7a; color: #FFFFFF;" class="broken-link">Report Broken Link</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script type="text/javascript">
		var delay = $('[name="delay"]').val() * 1000;
		setTimeout(function() {
			window.location = $('.link-out').attr('href');
		}, delay);
		
		$('.broken-link').click(function() {
			var param = { action: 'report_broken_link', link: $('.link-out').attr('href') }
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
	
	<?php $this->load->view( 'website/common/footer' ); ?>
</section>
</body>
</html>
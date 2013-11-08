<?php
	$array_data['POST_TYPE_MULTI_LINK'] = POST_TYPE_MULTI_LINK;
	$array_js[] = base_url('static/panel/content/scrape.js');
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<?php $this->load->view( 'panel/common/loader', array( 'array_js' => $array_js ) ); ?>

<div class="wi">
	<div class="x-hidden">
		<div class="page-data"><?php echo json_encode($array_data); ?></div>
		<iframe name="iframe_thumbnail" src="<?php echo base_url('panel/upload?callback_name=scrape_thumbnail'); ?>"></iframe>
	</div>
	
	<div id="x-cnt">
		<div id="grid-member"></div>
	</div>
</div>

<?php $this->load->view( 'panel/common/footer' ); ?>
<?php
	$array_js[] = base_url('static/panel/master/category.js');
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<?php $this->load->view( 'panel/common/loader', array( 'array_js' => $array_js ) ); ?>

<div class="wi">
	<div class="x-hidden">
		<iframe name="iframe_image" src="<?php echo base_url('panel/upload?callback_name=category_image'); ?>"></iframe>
	</div>
	
	<div id="x-cnt">
		<div id="grid-member"></div>
	</div>
</div>

<?php $this->load->view( 'panel/common/footer' ); ?>
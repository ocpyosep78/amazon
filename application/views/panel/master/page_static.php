<?php
	$array_js[] = base_url('static/panel/master/page_static.js');
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<?php $this->load->view( 'panel/common/loader', array( 'array_js' => $array_js ) ); ?>

<div class="wi">
	<div id="x-cnt">
		<div id="grid-member"></div>
	</div>
</div>

<?php $this->load->view( 'panel/common/footer' ); ?>
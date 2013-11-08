<?php
	$array_js[] = base_url('static/panel/content/shink_link.js');
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<?php $this->load->view( 'panel/common/loader', array( 'array_js' => $array_js ) ); ?>

<div class="wi">
	<div id="x-cnt">
		<div><div id="link_fromED"></div></div>
		<div style="padding: 15px 0;">
			<div style="width: 200px; margin: 0 auto;">
				<div style="float: left; width: 100px; padding: 3px 5px 12px 0; text-align: right;">Link Shorter :</div>
				<div style="float: left; width: 100px;"><div id="link_shortED"></div></div>
				<div style="clear: both;"></div>
			</div>
			<div style="width: 100px; margin: 0 auto;"><div id="convertED"></div></div>
		</div>
		<div><div id="link_toED"></div></div>
	</div>
</div>

<?php $this->load->view( 'panel/common/footer' ); ?>
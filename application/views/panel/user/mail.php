<?php
	$array_js[] = base_url('static/panel/user/mail.js');
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<?php $this->load->view( 'panel/common/loader', array( 'array_js' => $array_js ) ); ?>

<div class="wi" style="padding: 20px;">
	<div style="float:left; width: 100px; padding: 3px 5px 12px 0; text-align: right;">To :</div>
	<div style="float:left; width: 500px;"><div id="toED"></div></div>
	<div class="clear"></div>
	<div style="float:left; width: 100px; padding: 3px 5px 12px 0; text-align: right;">Subject :</div>
	<div style="float:left; width: 500px;"><div id="subjectED"></div></div>
	<div class="clear"></div>
	<div style="float:left; width: 100px; padding: 3px 5px 12px 0; text-align: right;">Message :</div>
	<div style="float:left; width: 500px;"><div id="messageED"></div></div>
	<div class="clear"></div>
	<div style="float:left; width: 100px;">&nbsp;</div>
	<div style="float:left; width: 125px;"><div id="sendED"></div></div>
	<div style="float:left; width: 125px;"><div id="resetED"></div></div>
	<div class="clear"></div>
</div>

<?php $this->load->view( 'panel/common/footer' ); ?>
<?php
	$array_js[] = base_url('static/panel/dashboard.js');
	
	$param_comment['sort'] = '[{"property":"comment_time","direction":"DESC"}]';
	$param_comment['limit'] = 10;
	$array_comment = $this->Comment_model->get_array($param_comment);
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<?php $this->load->view( 'panel/common/loader', array( 'array_js' => $array_js ) ); ?>

<style>
.x-scroller-horizontal .x-scroller-ct { overflow-x: hidden; }
</style>

<div style="padding: 10px;">
	<h3 style="padding: 0 0 5px 0;">Komentar Terbaru</h3>
	<table id="table-comment" style="width: 100%;">
	<thead>
		<tr>
			<th style="width: 15%;">Time</th>
			<th style="width: 15%;">Name</th>
			<th style="width: 65%;">Comment</th>
			<th style="width: 5%;">Link</th></tr>
	</thead>
	<tbody>
		<?php foreach ($array_comment as $comment) { ?>
		<tr>
			<td><?php echo $comment['comment_time'] ?></td>
			<td><?php echo $comment['user_fullname'] ?></td>
			<td><?php echo $comment['comment'] ?></td>
			<td style="text-align: center;"><a href="<?php echo $comment['link'] ?>" target="_blank">
				<img src="<?php echo base_url('static/js/extjs/resources/link.png'); ?>" />
			</a></td>
		</tr>
		<?php } ?>
	</tbody>
	</table>
</div>

<?php $this->load->view( 'panel/common/footer' ); ?>
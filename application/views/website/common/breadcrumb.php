<?php
	// how to use
	/*
	$param_breadcrumb = array(
		'title_list' => array(
			array( 'link' => base_url(), 'title' => 'Home', 'class' => 'first' ),
			array( 'link' => base_url('dekstop'), 'title' => 'Dekstop 2', 'class' => 'last' )
		)
	);
	<?php $this->load->view( 'website/common/breadcrumb', $param_breadcrumb ); ?>
	/*	*/
?>
<div class="row visible-xs"><div class="container">
	<div class="offcanvas-sidebars-buttons">
		<button style="display: none;" type="button" data-for="column-left" class="pull-left btn btn-danger"><i class="glyphicon glyphicon-indent-left"></i>Sidebar Left</button>
		<button style="display: block;" type="button" data-for="column-right" class="pull-right btn btn-danger">Sidebar Right <i class="glyphicon glyphicon-indent-right"></i></button>
	</div>
</div></div>

<div id="breadcrumb"><ol class="breadcrumb container">
	<?php foreach ($title_list as $item) { ?>
		<li class="<?php echo $item['class']; ?>"><a href="<?php echo $item['link']; ?>"><span><?php echo $item['title']; ?></span></a></li>
	<?php } ?>
</ol></div>
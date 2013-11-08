<?php
	$page_title = (isset($page_title)) ? $page_title : '';
    $ext = base_url('static/js/extjs');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en-AU">
<head>
	<title><?php echo $page_title; ?></title>
	
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url('static/js/extjs/resources/css/ext-all.css'); ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url('static/js/extjs/examples/ux/grid/css/GridFilters.css'); ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url('static/js/extjs/examples/ux/grid/css/RangeMenu.css'); ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url('static/js/extjs/resources/css/admin.css'); ?>" />
	<script type="text/javascript">
		URLS = <?php echo json_encode( array( 'base' => base_url(), 'ext' => $ext ) ); ?>;
	</script>
	<script type="text/javascript" src="<?php echo base_url('static/js/jquery.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/extjs/ext-all.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/panel/ext.common.js'); ?>"></script>
</head>
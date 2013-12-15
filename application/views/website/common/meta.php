<?php
	$web['host'] = base_url();
	
	// title
	$title = (isset($title)) ? $title : 'shopermarket Mega Shop';
	
	/*	// array meta
		e.g. $array_meta = array(
			array( 'name' => 'Title', 'content' => 'Isi Title' ),
			array( 'name' => 'Description', 'content' => 'Isi Description' ),
			array( 'name' => 'Keywords', 'content' => 'Isi Keywords' )
		);
	/*	*/
	$array_meta = (isset($array_meta)) ? $array_meta : array();
	
	/*	// array link
		e.g. $array_link = array(
			array( 'rel' => 'canonical', 'href' => 'url item' ),
			array( 'rel' => 'image_src', 'href' => 'image default' )
		);
	/*	*/
	$array_link = (isset($array_link)) ? $array_link : array();
?>
<!DOCTYPE html>
<html dir="ltr" class="ltr" lang="en">
<head>
	<title><?php echo $title; ?></title>
	
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<!-- Mobile viewport optimized: h5bp.com/viewport -->
	<meta name="viewport" content="width=device-width">
	
	<!-- meta -->
	<?php foreach ($array_meta as $row) { ?>
	<meta name="<?php echo $row['name']; ?>" content="<?php echo $row['content']; ?>" />
	<?php } ?>
	
	<!-- link -->
	<?php foreach ($array_link as $row) { ?>
	<meta rel="<?php echo $row['rel']; ?>" href="<?php echo $row['href']; ?>" />
	<?php } ?>
	
	<link rel="stylesheet" href="<?php echo base_url('static/css/bootstrap.css'); ?>" />
	<link rel="stylesheet" href="<?php echo base_url('static/css/stylesheet.css'); ?>" />
	<link rel="stylesheet" href="<?php echo base_url('static/css/style.css'); ?>" />
	<link rel="shortcut icon" href="<?php echo base_url('static/img/shop.png'); ?>" type="image/x-icon" />
	
	<script>var web = <?php echo json_encode($web); ?></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/jquery-1.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/jquery-ui-1.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/jquery_003.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/common.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/common_002.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/common_003.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/bootstrap.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/tabs.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/jquery.validate.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/notify.min.js'); ?>"></script>
</head>
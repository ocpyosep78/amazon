<?php
	$message = (isset($message)) ? $message : '';
?>
<html>
<head>
	<?php if ($is_refresh && !empty($message)) { ?>
	<meta http-equiv="refresh" content="5">
	<?php } ?>
</head>
<body>
	<h2>Halaman Scrape ini akan refresh otomatis</h2>
	<h3>Hari dan Jam : <?php echo date("d F Y H:i:s"); ?></h3>
	------------------------------------------------------------------------------------------------------------------------------------------------------------
	<h3><?php echo $message; ?></h3>
</body>
</html>
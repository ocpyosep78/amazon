<?php
    $ext = base_url('static/js/extjs');
?>
<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<title>Administrator Home</title>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url('static/js/extjs/resources/css/ext-all.css'); ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url('static/js/extjs/resources/css/admin.css'); ?>" />
	<style>
		/* Background Image Login */
		img.bg {
			/* Set rules to fill background */
			min-height: 100%; min-width: 1024px;
			
			/* Set up proportionate scaling */
			width: 100%; height: auto;
			
			/* Set up positioning */
			position: fixed; top: 0; left: 0;
		}
		
		@media screen and (max-width: 1024px) {
			img.bg { left: 50%; margin-left: -512px; }
		}
		
        #MainPanel .x-tab-bar-strip {
            top: 30px !important; 							/* Default value is 20, we add 20 = 40 */
        }

        #MainPanel .x-tab-bar .x-tab-bar-body {
            height: 33px !important;						/* Default value is 23, we add 20 = 43 */
            border: 0 !important;							/* Overides the border that appears on resizing the grid */
        }

        #MainPanel .x-tab-bar .x-tab-bar-body .x-box-inner {
            height: 31px !important;						/* Default value is 21, we add 20 = 41 */
        }

        #MainPanel .x-tab-bar .x-tab-bar-body .x-box-inner .x-tab {
            height: 31px !important;						/* Default value is 21, we add 20 = 41 */
        }

        #MainPanel .x-tab-bar .x-tab-bar-body .x-box-inner .x-tab button {
            height: 23px !important;						/* Default value 13, we add 20 = 33 */
            line-height: 23px !important;					/* Default value 13, we add 20 = 33 */
        }
        #MainPanel .x-tab button { font-size:13px !important; }
	</style>
	
	<script type="text/javascript">
		URLS = <?php echo json_encode( array( 'base' => base_url(), 'ext' => $ext ) ); ?>;
	</script>
	<script type="text/javascript" src="<?php echo base_url('static/js/extjs/ext-all.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/extjs/app/app.js'); ?>"></script>
</head>
<body>
    <div id="loading">loading...</div>
	<div id="header" style="display: none;">
		<h1 id="logo"><a>SUEKAREA</a></h1>
		<div class="navigation"><ul>
			<li><a href="<?php echo base_url('panel/home/logout'); ?>"><div>Logout</div></a></li>
		</ul></div>
	</div>
	<div id="footer" style="display:none;">
		<p>&copy; <?php echo date("Y"); ?> <a href="">Suekarea</a></p>
	</div>
</body>
</html>
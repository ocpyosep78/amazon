<body class="framedBody">

<?php if (isset($array_js) && count($array_js) > 0) { ?>
<?php foreach ($array_js as $link) { ?>
<script type="text/javascript" src="<?php echo $link; ?>"></script>
<?php } ?>
<?php } ?>

<div id="loading_mask">
    <div class="loading">
        <p><img src="<?php echo base_url('static/img/loading.gif')?>"></p>
        <p>Loading...</p>
    </div>
</div>
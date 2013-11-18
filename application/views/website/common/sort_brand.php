<?php
	$array_brand = $this->Brand_model->get_array_with_count(array( 'item_status_id' => ITEM_STATUS_APPROVE ));
?>
<div class="box category highlights">
	<div class="box-heading"><span>Sort By Brand</span></div>
	<div class="box-content">
		<ul id="accordion" class="box-category">
			<!--
			<li class="haschild first">
				<a href="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20" class="active">Desktops (21)</a>
				<a class="subcart" data-toggle="collapse" data-parent="#accordion" href="#collapseOne1"></a>
				<ul id="collapseOne1" class="panel-collapse collapse">
					<li class="first">
						<a href="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20_26">PC <span class="">5</span></a>
					</li>
					<li class="last">
						<a href="http://parapekerja.com/opencart/index.php?route=product/category&amp;path=20_27">Mac <span class="">1</span></a>
					</li>
				</ul>
			</li>
			<!-- -->
			
			<?php foreach ($array_brand as $row) { ?>
			<li><a href="<?php echo $row['link']; ?>"><?php echo $row['name']; ?> (<?php echo $row['total']; ?>)</a></li>
			<?php } ?>
		</ul>
	</div>
</div>
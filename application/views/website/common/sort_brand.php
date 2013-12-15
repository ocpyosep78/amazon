<?php
	// page brand
	preg_match('/brand\/([a-z0-9\-]+)/i', $_SERVER['REQUEST_URI'], $match);
	$brand_alias = (isset($match[1])) ? $match[1] : '';
	$brand = $this->Brand_model->get_by_id(array( 'alias' => $brand_alias ));
	if (count($brand) > 0 && empty($category_id) && empty($category_sub_id)) {
		$brand_category = $this->Brand_model->get_category(array( 'id' => $brand['id'] ));
		$category_id = $brand_category['category_id'];
		$category_sub_id = $brand_category['category_sub_id'];
	}
	
	$param_brand = array(
		'category_id' => @$category_id,
		'category_sub_id' => @$category_sub_id,
		'item_status_id' => ITEM_STATUS_APPROVE
	);
	$array_brand = $this->Brand_model->get_array_with_count($param_brand);
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
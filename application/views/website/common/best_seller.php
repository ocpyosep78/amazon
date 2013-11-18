<?php
	$best_seller = $this->Page_Static_model->get_by_id(array( 'alias' => 'widget-best-seller' ));
?>
<div class="box box-product bestseller">
	<div class="box-heading"><span>Bestsellers</span></div>
	<div class="box-content">
		<div class="product-list"><?php echo $best_seller['desc']; ?></div>
	</div>
</div>
<!-- IN -->


<!-- OUT -->

<?php

$searchOrderId = rex_post('search_order', 'int');
$searchProduct = rex_post('search_product', 'string');

?>
<div class="frm site-frm">
	<form action="index.php" method="post">
	<input type="hidden" name="article_id" value="REX_ARTICLE_ID" />
	<input type="hidden" name="clang" value="REX_CLANG_ID" />
	
	<fieldset>
		<div class="f-fldst">
		<p class="f-txt">
			<span class="f-lgnd">Suche nach</span>
		
			<label for="srch-prdct">Produkt</label>
			<input id="srch-prdct" type="text" name="search_product" value="" />
			<input class="f-sbmt-img f-sbmt-lst" type="image" name="search" src="/layout_livendo/icon_sbmt.gif" value="Absenden" />
		</p>
		</div>
	</fieldset>
	</form>
</div>
<?php

if($searchOrderId > 0)
{
	$products = rex_shop_product::searchProductsByOrderId($searchOrderId);
	rex_shop_product_viewer::showProductList($products, true);
}
else if ($searchProduct != '')
{
	$products = rex_shop_product::searchProducts($searchProduct);
	rex_shop_product_viewer::showProductList($products, true);
}

?>
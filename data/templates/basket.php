<?php
$basket_article_id = $REX['ADDON']['simple_shop']['settings']['article_id_basket'];

$REX['SIMPLE_SHOP']['BASKET'] = '';

$basket_arr = rex_shop_basket::getBasket();
$count = count($basket_arr);

if ($count > 0) {
    $word_1 = 'befinden';
    $word_2 = 'Artikel';
    if ($count == 1) {
        $word_1 = 'befindet';
        $word_2 = 'Artikel';
    }



    $REX['SIMPLE_SHOP']['BASKET'] = '
	<ul class="simple-shop-basket-list">
	  <li class="simple-shop-go-to-basket">
		<a href="'.rex_getUrl($basket_article_id).'">
		  <span class="simple-shop-prefix">Es '.$word_1.' sich </span>
		  <span class="simple-shop-count">'.$count.'</span>
		  <span class="simple-shop-suffix"> '.$word_2.' im</span>
		  <span class="simple-shop-after"> Warenkorb</span>
		</a>
	  </li><li class="simple-shop-go-to-checkout"><a href="'.rex_getUrl($basket_article_id).'">zur Kasse</a></li>
	</ul>';
}


/*
<div class="shop-basket">
	<h3>Es <?php print $word; ?> sich <span><?php echo $count; ?></span> Artikel im Warenkorb.</h3>
	<p><a class="shop-link-basket" href="<?php echo rex_getUrl(73); ?>" title="Hier geht's zum Warenkorb bzw. zur Kasse">zur Kasse</a></p>
	
	
	<h4 class="clr-2">Produkte im Warenkorb</h4>

if (is_array($basket_arr) AND count($basket_arr) >= 1) {
echo '<ul class="list-v2">';
$i = 0;
foreach($basket_arr as $id => $amount)
{
	$i++;
	if ($i>5)
	{
		echo '<li>...</li>';
		break;
	}
	
	$a = new rex_shop_product($id);
	if ($a->isValid()) echo '<li><a href="'. $a->getDetailUrl(true) .'">'.$a->getName().' ('. $amount .')</a></li>';

}
echo '</ul>';
}


</div>
*/
?>
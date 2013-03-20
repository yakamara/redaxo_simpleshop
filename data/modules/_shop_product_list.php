<?php
$output = '';
$product_id = rex_request('product_id', 'int');
$page       = rex_request('page', 'string');
$popup      = rex_get('popup', 'boolean');

if ($page != 'list' && $product_id > 0) {

  // ******************************** PRODUKTDETAIL

  if ($product = new rex_shop_product($product_id))
  {
    if ($popup == true) {
      rex_shop_product_viewer :: showPreview($product);
    }
    else {
      echo '<p class="back"><a href="'.rex_getUrl('','').'">zurück zur Auswahl</a></p>';

      rex_shop_product_viewer :: showProduct($product);

      echo '<p class="back"><a href="'.rex_getUrl('', '').'">zurück zur Auswahl</a></p>';
    }
  }
  else {
    echo '<p>Dieses Produkt wurde leider nicht gefunden</p>';
  }
}
else {

  // ******************************** PRODUKTLISTE

  $category_id = (int) "REX_VALUE[1]";
  $order = "REX_VALUE[2]";

  // verwendet in class.xform.simple_shop_basket.inc.php 
  $_SESSION['SIMPLE_SHOP']['LAST_PRODUCT_LIST_ART'] = 'REX_ARTICLE_ID';

  $products = rex_shop_category :: getProductList($category_id, FALSE, $order);

  echo rex_shop_product_viewer :: showProductList($products);
}

?>
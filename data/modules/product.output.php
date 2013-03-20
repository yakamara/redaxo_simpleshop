<?php

$product_id = rex_request('product_id', 'int');
$page       = rex_request('page', 'string');
$popup      = rex_get('popup', 'boolean');

// $view         = 'REX_VALUE[5]';

$product_ids = explode(',', 'REX_VALUE[1]');
$category_id = (int)'REX_VALUE[3]';
$sort        = 'REX_VALUE[4]';

$out = '';

if ($page != 'list' && $product_id > 0) {

    // ******************************** PRODUKTDETAIL

    if ($product = new rex_shop_product($product_id)) {
        if ($popup == true) {
            $out .= rex_shop_product_viewer :: showPreview($product);
        }
        else {
            $out .= '<p class="back"><a href="'.rex_getUrl('','').'">zurück zur Auswahl</a></p>';

            $out .= rex_shop_product_viewer :: showProduct($product);

            $out .= '<p class="back"><a href="'.rex_getUrl('', '').'">zurück zur Auswahl</a></p>';
        }
    }
    else {
        $out .= '<p>Dieses Produkt wurde leider nicht gefunden</p>';
    }
}
else {

    $products = array();
    if (count($product_ids) > 0 && $product_ids[0] > 1) {
        foreach ($product_ids as $id) {
            if ($product = new rex_shop_product($id)) {
                $products[] = $product;
            }
        }

        if (count($products) > 1)
            $out .= '<h2 class="simple-shop-headline">Unsere Gutscheine</h2>';
        else
            $out .= '<h2 class="simple-shop-headline">Unser Gutschein</h2>';
    }
    elseif ($category_id > 0) {
        $products = rex_shop_category :: getProductList($category_id, false, $sort);
    }

    $out .= rex_shop_product_viewer :: showProductList($products);
}

echo $out;
?>
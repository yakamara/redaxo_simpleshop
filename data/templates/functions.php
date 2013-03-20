<?php
$basket_article_id = $REX['ADDON']['simple_shop']['settings']['article_id_basket'];

$REX['SIMPLE_SHOP']['MESSAGE'] = '';

$func = rex_request('func','string','');
$msg = '';

if ($func == 'add_product') {
    $product_id     = rex_request('product_id','int');
    $product_amount = rex_request('product_amount','int');

    if (rex_shop_basket::addToBasket($product_id, $product_amount)) {
        $msg = 'Der Artikel wurde dem <a href="'.rex_getUrl($basket_article_id).'">Warenkorb</a> hinzugefügt';
    }
}
elseif ($func == 'del_product') {
    $product_id = rex_request('product_id','int');
    if (rex_shop_basket::deleteFromBasket($product_id)) {
        $msg = 'Der Artikel wurde aus dem <a href="'.rex_getUrl($basket_article_id).'">Warenkorb</a> gelöscht';
    }
}
elseif (rex_post('refresh_cart', 'string', '') != '') {
    $amounts = rex_request('amount', 'array');
    foreach($amounts as $pid => $amount) {
        rex_shop_basket::setProductAmount($pid, $amount);
    }
}

if($msg != '') {
    $REX['SIMPLE_SHOP']['MESSAGE'] =  '<div class="simple-shop-info"><p>'. $msg .'</p></div>';
}

?>
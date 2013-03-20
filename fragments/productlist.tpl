<?php

global $REX;

$shipping_rates       = rex_shop_utils::formatPrice($REX['ADDON']['simple_shop']['settings']['shipping_rates']);
$shipping_article_id  = $REX['ADDON']['simple_shop']['settings']['article_id_shipping_rates'];


$products   = $this->products;
$article_id = $this->article_id;

$c = 0;
$out = array();
if (count($products) > 0) {
    foreach ($products as $product) {

        // detail link <a href="'.$product->getDetailUrl($article_id).'"></a>

// ------------------------------------------------------- Preis ab
        $price = '';
        $price .= '<dl class="simple-shop-price">';

        $arr = $product->getPricesArray();
        if (count($arr) > 1) {
            $price .= '<dt>Staffelpreis:</dt><dd><ul>';
            foreach ($arr as $pamount => $amount_price) {
                $price .= '<li>ab ' . $pamount . ' VE - ' . rex_shop_utils::formatPrice($amount_price) . '*</li>';
            }
            $price .= '</ul></dd>';
        } else {
            $price .= '<dt>Preis:</dt>
                 <dd class="simple-shop-product-price">' . rex_shop_utils::formatPrice($product->getPrice()) . '</dd>';
        }
        $price .= '<dd class="simple-shop-note"><span class="simple-shop-tax">inkl. MwSt.,</span><span class="simple-shop-shipping"> zzgl. <a href="javascript:void(0);" onclick="getPopup(\'' . rex_getUrl($shipping_article_id) . '\');">Versand</a> von ' . $shipping_rates . '</dd>';
        $price .= '</dl>';


// ------------------------------------------------------- Anzahl
        $amount = '';
        $amount .= '<dl class="simple-shop-amount"><dt><label for="simple-shop-amount">Menge:</label></dt><dd>';

        $arr = $product->getAmountsArray();
        if (count($arr) == 0) {
            $amount .= '<input type="text" name="product_amount" value="1" maxlength="3" />';
        } else {
            $sel = new rex_select();
            $sel->setId('simple-shop-amount');
            $sel->setAttribute('class', 'simple-shop-amount');
            $sel->setName('product_amount');
            $sel->setSize(1);

            foreach ($arr as $number => $description) {
                $option = $number;
                if (trim($description) != '') {
                    $option .= ' ' . trim($description);
                }
                $sel->addOption($option, $number);
            }
            $amount .= $sel->get();
        }
        $amount .= '</dd></dl>';



// ------------------------------------------------------- Produktname
        $headline = '<h3 class="simple-shop-name">' . nl2br(htmlspecialchars($product->getName())) . '</h3>';



// ------------------------------------------------------- Bilder
        $images = '';
        $files = $product->getImages();
        if (count($files) < 1) {
            $files = array( $product->getImage() );
        }
        if (count($files) > 0) {
            foreach ($files as $file) {
                $images .= pool_view::image($file, 'productlist');
            }
            $images = '<p class="simple-shop-images">' . $images . '</p>';
        }



// ------------------------------------------------------- Format
        $format = '';
        if ($product->getValue('description_format') != '') {
            $format = '<div class="simple-shop-format">
                  ' . pool_string::textile($product->getValue('description_format')) . '
                 </div>';
        }



// ------------------------------------------------------- Description Short
        $description_short = '';
        if ($product->getValue('description_short') != '') {
            $description_short = '<div class="simple-shop-description-short">
                              ' . pool_string::textile($product->getValue('description_short')) . '
                             </div>';
        }



// ------------------------------------------------------- Description Long
        $description_long = '';
        if ($product->getValue('description_long') != '') {
            $description_long = '<div class="simple-shop-description-long">
                            <h4>Beschreibung</h4>
                            ' . pool_string::textile($product->getValue('description_long')) . '
                           </div>';
        }



// ------------------------------------------------------- Bestellnummer
        $code = '';
        if ($product->getValue('article_number') != '') {
            $code = '<dl class="simple-shop-code">
                <dt>Best.-Nr.</dt>
                <dd>' . $product->getValue('article_number') . '</dd>
               </dl>';
        }

        $c++;
        $class = ($c <= 2) ? ' class="simple-shop-top"' : '';

        $echo = '';
        $echo .= '<li' . $class . '>';
        $echo .= $images;
        $echo .= '<div class="simple-shop-product">';

        $echo .= $headline;
        $echo .= $description_short;
        $echo .= $format;

        $echo .= '<form action="' . rex_getUrl() . '" method="post">
              <fieldset>
                <input type="hidden" name="product_id" value="' . $product->getId() . '>" />
                <input type="hidden" name="func" value="add_product" />
                <input type="hidden" name="page" value="list" />
                ' . $price . $amount . '
                <input type="submit" class="form-submit" name="add" value="In den Warenkorb legen" />
              </fieldset>
              </form>';

        // $echo .= $code;
        // $echo .= $description_long;

        $echo .= '</div></li>';



        $out[] = $echo;
    }

    echo '<ul class="simple-shop-productlist">' . implode('', $out) . '</ul>';
} else {
    echo '<p class="simple-shop-error">Keine Produkte gefunden</p>';
}

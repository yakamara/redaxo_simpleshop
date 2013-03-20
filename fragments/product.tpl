<?php
global $REX;

$shipping_rates       = rex_shop_utils::formatPrice($REX['ADDON']['simple_shop']['settings']['shipping_rates']);
$shipping_article_id  = $REX['ADDON']['simple_shop']['settings']['article_id_shipping_rates'];

$product = $this->product;


$out = '';


if (is_object($product) && $product->getValue('status') > 0) {


// ------------------------------------------------------- Preis ab
  $price = '';
  $arr = $product->getPricesArray();
  if(count($arr) > 1) {
    $price .= '<dl class="simple-shop-price">';
    $price .= '<dt>Staffelpreis:</dt><dd><ul>';
    foreach ($arr as $pamount => $amount_price) {
      $price .= '<li>ab '. $pamount .' VE - '. rex_shop_utils::formatPrice($amount_price) .'*</li>';
    }
    $price .= '</ul></dd>';
    $price .= '</dl>';
  }
  else {
    $price .= '<dl class="simple-shop-price">';
    $price .= '<dt>Preis:</dt><dd>'. rex_shop_utils::formatPrice($product->getPrice()) .'*</dd>';
    $price .= '</dl>';
  }


// ------------------------------------------------------- Anzahl
  $amount = '';
  $amount .= '<dl class="simple-shop-amount"><dt><label for="simple-shop-amount">Menge:</label></dt><dd>';

  $arr = $product->getAmountsArray();
  if (count($arr) == 0) {
    $amount .= '<input type="text" name="product_amount" value="1" maxlength="3" />';
  }
  else {
    $sel = new rex_select();
    $sel->setId('simple-shop-amount');
    $sel->setAttribute('class', 'simple-shop-amount');
    $sel->setName('product_amount');
    $sel->setSize(1);

    foreach($arr as $number => $description) {
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
  $headline = '<h3 class="simple-shop-name">'.nl2br(htmlspecialchars($product->getName())).'</h3>';



// ------------------------------------------------------- Bilder
  $images = '';
  $files = $product->getImages();
  if (count($files) < 1) {
    $files = array( $product->getImage() );
  }
  if (count($files) > 0) {
    foreach ($files as $file) {
      $images .= pool_view::image($file, 'product');
    }
  }



// ------------------------------------------------------- Format
  $format = '';
  if ($product->getValue('description_format') != '') {
    $format = '<div class="simple-shop-format">
              <h4>Format</h4>
              '.pool_string::textile($product->getValue('description_format')).'
             </div>';
  }



// ------------------------------------------------------- Description Long
  $description_long = '';
  if ($product->getValue('description_long') != '') {
    $description_long = '<div class="simple-shop-description-long">
                        <h4>Beschreibung</h4>
                        '.pool_string::textile($product->getValue('description_long')).'
                       </div>';
  }



// ------------------------------------------------------- Bestellnummer
  $code = '';
  if ($product->getValue('article_number') != '') {
    $code = '<dl class="simple-shop-code">
            <dt>Best.-Nr.</dt>
            <dd>'.$product->getValue('article_number').'</dd>
           </dl>';
  }




  $out .= '<div class="simple-shop-product">';

  $out .= $headline;
  $out .= $images;
  $out .= $code;

  $out .= '<form action="'.rex_getUrl().'" method="post">
          <fieldset>
            <input type="hidden" name="product_id" value="'.$product->getId().'>" />
            <input type="hidden" name="func" value="add_product" />
            ' . $price . $amount . '
            <input type="submit" class="form-submit" name="add" value="In den Warenkorb legen" />
            <p class="simple-shop-price-shipping">* Preis zzgl. <a href="javascript:void(0);" onclick="getPopup(\''.rex_getUrl($shipping_article_id).'\');">Versandkosten</a> von ' . $shipping_rates . '</p>
          </fieldset>
          </form>';

  $out .= $description_long;
  $out .= $format;

  $out .= '</div>';
}
else {
  $out .= '<p class="simple-shop-error">Keine Produkte gefunden</p>';
}

echo $out;
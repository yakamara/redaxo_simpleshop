<?php

class rex_xform_simple_shop_basket extends rex_xform_abstract
{

  function enterObject()
  {
    global $REX;

    // wird verwendet in > class.xform.simple_shop_showhide.inc.php
    $this->params['simple_shop_warning'] = false;

    if ($this->params['send'] == 1 && rex_request('refresh_cart', 'string') != '') {

      $amount = rex_request('amount', 'array');
      if (count($amount) > 0) {
        foreach ($amount as $product_id => $product_amount) {
          rex_shop_basket::setProductAmount($product_id, $product_amount);
        }
      }

      // Error. Fehlermeldung ausgeben
      $msg = rex_i18n::msg('simple_shop_basket_updated');
      $this->params['warning'][] = $msg;
      $this->params['warning_messages'][] = $msg;
    }

    // überprüft Mindestbestellmengengruppen
    // inkorrekte Produkte werden als Array zurückgegeben
    $wrong_products = rex_shop_basket::checkAmountValues();

    if (count($wrong_products) > 0) {
      $msg = '';
      foreach ($wrong_products as $amount_group_id => $p) {
        if ($msg != '') {
          $msg .= '<br />';
        }

        $ag = rex_shop::getAmountGroupValue($amount_group_id);
        $msg .= rex_i18n::msg('simple_shop_basket_error_amount_group', $ag['name'], $ag['amount']);

        foreach ($p as $pid) {
          $a = new rex_shop_product($pid);
          $msg .= '<br />* ' . $a->getName();
        }
      }
      $this->params['warning'][] = $msg;
      $this->params['warning_messages'][] = $msg;
      $this->params['simple_shop_warning'] = true;

    }

    if (count(rex_shop_basket::getBasket()) < 1) {
      $output = '';
      $msg = rex_i18n::msg('simple_shop_basket_is_empty');
      $this->params['warning'][] = $msg;
      $this->params['warning_messages'][] = $msg;

      $this->params['simple_shop_warning'] = true;
    } else {

      foreach (rex_shop_basket :: getBasket() as $pid => $pamount) {
        $product = new rex_shop_product($pid);
        if (!$product->checkOrderValue($pamount)) {
          $msg = '';
          $max = $product->getMaxOrder();
          if ($max > 0 && $product->getMaxOrder() < $pamount) {
            $msg .= rex_i18n::msg('simple_shop_basket_error_max_order', $max, $product->getName());
          }
          $min = $product->getMinOrder();
          if ($min > 0 && $product->getMinOrder() > $pamount) {
            $msg .= rex_i18n::msg('simple_shop_basket_error_min_order', $min, $product->getName());
          }
          $this->params['warning'][] = $msg;
          $this->params['warning_messages'][] = $msg;
          }
      }



      $productlist_id = (isset($_SESSION['SIMPLE_SHOP']['LAST_ARTICLE_ID']) && $_SESSION['SIMPLE_SHOP']['LAST_ARTICLE_ID'] > 0) ? $_SESSION['SIMPLE_SHOP']['LAST_ARTICLE_ID'] : $REX['ADDON']['simple_shop']['settings']['root_category_id'] ;

      $output = '<p><a href="' . rex_getUrl($productlist_id) . '" class="back">' . rex_i18n::msg('simple_shop_back_to_overview') . '</a></p>' .
                 rex_shop_basket_viewer::getBasket();
    }

    $this->params['form_output'][$this->getId()] = $output;

  }

  function getDescription()
  {
    return 'simple_shop_basket -> Beispiel: simple_shop_basket';
  }
}

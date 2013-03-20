<?php

$success = '';
$error   = '';
$func = rex_request('func', 'string');



if ($func == 'update') {
    $req = rex_request('simple_shop_root_category_id', 'int');
    if ($req > 0) {
        $REX['ADDON']['simple_shop']['settings']['root_category_id'] = $req;
    }

    $req = rex_request('LINK', 'array');
    if (isset($req[1]) && $req[1] > 0) {
        $REX['ADDON']['simple_shop']['settings']['article_id_basket'] = $req[1];
    }
    if (isset($req[2]) && $req[2] > 0) {
        $REX['ADDON']['simple_shop']['settings']['article_id_terms_and_conditions'] = $req[2];
    }
    if (isset($req[3]) && $req[3] > 0) {
        $REX['ADDON']['simple_shop']['settings']['article_id_shipping_rates'] = $req[3];
    }
    if (isset($req[4]) && $req[4] > 0) {
        $REX['ADDON']['simple_shop']['settings']['article_id_cancellation_policy'] = $req[4];
    }

    $req = rex_request('simple_shop_shipping_rates', 'string');
    if ($req != '') {
        $REX['ADDON']['simple_shop']['settings']['shipping_rates'] = htmlspecialchars($req);
    }

    $req = rex_request('simple_shop_tax_rates', 'string');
    if ($req != '') {
        $REX['ADDON']['simple_shop']['settings']['tax_rates'] = htmlspecialchars($req);
    }

    $req = rex_request('simple_shop_gross_prices', 'bool', 0);
    $REX['ADDON']['simple_shop']['settings']['gross_prices'] = $req;

    $req = rex_request('simple_shop_refresh_seconds', 'int');
    if ($req >= 0 && $req <= 5) {
        $REX['ADDON']['simple_shop']['settings']['refresh_seconds'] = $req;
    }

    $req = rex_request('simple_shop_module_relationlist_scheme', 'string');
    if ($req != '') {
        $REX['ADDON']['simple_shop']['settings']['module_relationlist_scheme'] = htmlspecialchars($req);
    }

    $content = '
$REX[\'ADDON\'][\'simple_shop\'][\'settings\'][\'root_category_id\']                = ' . $REX['ADDON']['simple_shop']['settings']['root_category_id'] . ';
$REX[\'ADDON\'][\'simple_shop\'][\'settings\'][\'article_id_basket\']               = \'' . $REX['ADDON']['simple_shop']['settings']['article_id_basket'] . '\';
$REX[\'ADDON\'][\'simple_shop\'][\'settings\'][\'article_id_terms_and_conditions\'] = \'' . $REX['ADDON']['simple_shop']['settings']['article_id_terms_and_conditions'] . '\';
$REX[\'ADDON\'][\'simple_shop\'][\'settings\'][\'article_id_shipping_rates\']       = \'' . $REX['ADDON']['simple_shop']['settings']['article_id_shipping_rates'] . '\';
$REX[\'ADDON\'][\'simple_shop\'][\'settings\'][\'article_id_cancellation_policy\']  = \'' . $REX['ADDON']['simple_shop']['settings']['article_id_cancellation_policy'] . '\';
$REX[\'ADDON\'][\'simple_shop\'][\'settings\'][\'shipping_rates\']                  = \'' . $REX['ADDON']['simple_shop']['settings']['shipping_rates'] . '\';
$REX[\'ADDON\'][\'simple_shop\'][\'settings\'][\'tax_rates\']                       = \'' . $REX['ADDON']['simple_shop']['settings']['tax_rates'] . '\';
$REX[\'ADDON\'][\'simple_shop\'][\'settings\'][\'gross_prices\']                    = \'' . $REX['ADDON']['simple_shop']['settings']['gross_prices'] . '\';
$REX[\'ADDON\'][\'simple_shop\'][\'settings\'][\'refresh_seconds\']                 = \'' . $REX['ADDON']['simple_shop']['settings']['refresh_seconds'] . '\';
$REX[\'ADDON\'][\'simple_shop\'][\'settings\'][\'module_relationlist_scheme\']      = \'' . $REX['ADDON']['simple_shop']['settings']['module_relationlist_scheme'] . '\';
  ';

    $config_file = $REX['INCLUDE_PATH'] . '/addons/simple_shop/config.inc.php';

    if ($error == '' && rex_replace_dynamic_contents($config_file, $content) !== false) {
        echo rex_info($I18N->msg('simple_shop_config_updated'));
    } else {
        echo rex_warning($I18N->msg('simple_shop_config_update_failed', $config_file));
    }

}


$sel_cat = new rex_category_select(false, false, false, false);
$sel_cat->setSize(1);
$sel_cat->setAttribute('class', 'rxshp-wdth');
$sel_cat->setName('simple_shop_root_category_id');
$sel_cat->setId('rex-simple-shop-root-category-id');
$sel_cat->setSelected($REX['ADDON']['simple_shop']['settings']['root_category_id']);

$sel_ref = new rex_select();
$sel_ref->setSize(1);
$sel_ref->setAttribute('style', 'width: 100px;');
$sel_ref->setName('simple_shop_refresh_seconds');
$sel_ref->setId('rex-simple-shop-refresh-seconds');
$sel_ref->addOptions(range(0, 5));
$sel_ref->setSelected($REX['ADDON']['simple_shop']['settings']['refresh_seconds']);

$sel_pri = new rex_select();
$sel_pri->setSize(1);
$sel_pri->setAttribute('style', 'width: 100px;');
$sel_pri->setName('simple_shop_gross_prices');
$sel_pri->setId('rex-simple-shop-gross-prices');
$sel_pri->addOption($I18N->msg('simple_shop_net_prices'), 0);
$sel_pri->addOption($I18N->msg('simple_shop_gross_prices'), 1);
$sel_pri->setSelected($REX['ADDON']['simple_shop']['settings']['gross_prices']);


if ($error != '') {
    echo rex_warning($error);
}

echo '
  <div class="rex-form">
    <form action="index.php" method="post">

      <fieldset class="rex-form-col-1">
        <legend>' . $I18N->msg('simple_shop_settings') . '</legend>

        <div class="rex-form-wrapper">
          <input type="hidden" name="page" value="simple_shop" />
          <input type="hidden" name="subpage" value="settings" />
          <input type="hidden" name="func" value="update" />

          <div class="rex-form-row">
            <p class="rex-form-col-a">
              <label for="rex-simple-shop-root-category-id">' . $I18N->msg('simple_shop_root_category_id') . '</label>
              ' . $sel_cat->get() . '
            </p>
          </div>

          <div class="rex-form-row">
            <div class="rex-form-col-a rex-form-text">
              <label for="rex-simple-shop-article-id-basket">' . $I18N->msg('simple_shop_article_id_basket') . '</label>
              ' . rex_var_link::getLinkButton(1, $REX['ADDON']['simple_shop']['settings']['article_id_basket']) . '
            </div>
          </div>

          <div class="rex-form-row">
            <div class="rex-form-col-a rex-form-text">
              <label for="rex-simple-shop-article-id-terms-and-conditions">' . $I18N->msg('simple_shop_article_id_terms_and_conditions') . '</label>
              ' . rex_var_link::getLinkButton(2, $REX['ADDON']['simple_shop']['settings']['article_id_terms_and_conditions']) . '
            </div>
          </div>

          <div class="rex-form-row">
            <div class="rex-form-col-a rex-form-text">
              <label for="rex-simple-shop-article-id-shipping-rates">' . $I18N->msg('simple_shop_article_id_shipping_rates') . '</label>
              ' . rex_var_link::getLinkButton(3, $REX['ADDON']['simple_shop']['settings']['article_id_shipping_rates']) . '
            </div>
          </div>

          <div class="rex-form-row">
            <div class="rex-form-col-a rex-form-text">
              <label for="rex-simple-shop-article-id-cancellation-policy">' . $I18N->msg('simple_shop_article_id_cancellation_policy') . '</label>
              ' . rex_var_link::getLinkButton(4, $REX['ADDON']['simple_shop']['settings']['article_id_cancellation_policy']) . '
            </div>
          </div>

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="rex-simple-shop-shipping-rates">' . $I18N->msg('simple_shop_shipping_rates_with_example') . '</label>
              <input id="rex-simple-shop-shipping-rates" class="rex-form-text" type="text" name="simple_shop_shipping_rates" value="' . htmlspecialchars($REX['ADDON']['simple_shop']['settings']['shipping_rates']) . '" />
            </p>
          </div>
        </div>

      </fieldset>

      <fieldset>
        <legend>' . $I18N->msg('simple_shop_forms') . '</legend>

        <div class="rex-form-wrapper">
          <div class="rex-form-row">
            <p class="rex-form-col-a">
              <label for="rex-simple-shop-refresh-seconds">' . $I18N->msg('simple_shop_refresh_seconds') . '</label>
              ' . $sel_ref->get() . '
            </p>
          </div>

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="rex-simple-shop-tax-rates">' . $I18N->msg('simple_shop_tax_rates_with_example') . '</label>
              <input id="rex-simple-shop-tax-rates" class="rex-form-text" type="text" name="simple_shop_tax_rates" value="' . htmlspecialchars($REX['ADDON']['simple_shop']['settings']['tax_rates']) . '" />
            </p>
          </div>

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="rex-simple-shop-gross-prices">' . $I18N->msg('simple_shop_price_type') . '</label>
              ' . $sel_pri->get() . '
            </p>
          </div>
        </div>

      </fieldset>

      <fieldset>
        <legend>' . $I18N->msg('simple_shop_modules') . '</legend>

        <div class="rex-form-wrapper">

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="rex-simple-shop-module-relationlist-scheme">' . $I18N->msg('simple_shop_module_relationlist_scheme') . '</label>
              <input id="rex-simple-shop-module-relationlist-scheme" class="rex-form-text" type="text" name="simple_shop_module_relationlist_scheme" value="' . htmlspecialchars($REX['ADDON']['simple_shop']['settings']['module_relationlist_scheme']) . '" />
              <span class="rex-form-notice">' . $I18N->msg('simple_shop_module_relationlist_scheme_note') . '</span>
            </p>
          </div>
        </div>

      </fieldset>




      <fieldset class="rex-form-col-1">
        <div class="rex-form-wrapper">
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-submit">
              <input type="submit" class="rex-form-submit" value="' . $I18N->msg('simple_shop_save_settings') . '" title="' . $I18N->msg('simple_shop_save_settings') . '" />
            </p>
          </div>
        </div>
      </fieldset>
    </form>
  </div>
  ';

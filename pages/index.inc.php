<?php


// TODO. Sprachunabhaengigkeit einbauen
// REX_PRODUCT_BUTTON
// REX_PRODUCT[id="1"]
// ...

$subpages = array();
if ($REX_USER->isValueOf('rights', 'admin[]') || $REX_USER->isValueOf('rights', 'simple_shop[admin]')) {
  $subpages[] = array ('', $I18N->msg('simple_shop_product_management'));
}

if ($REX_USER->isValueOf('rights', 'admin[]') || $REX_USER->isValueOf('rights', 'simple_shop[admin]')) {
  $subpages[] = array ('discount_groups', $I18N->msg('simple_shop_discount_groups'));
}

if ($REX_USER->isValueOf('rights', 'admin[]') || $REX_USER->isValueOf('rights', 'simple_shop[admin]')) {
  $subpages[] = array ('amount_groups', $I18N->msg('simple_shop_amount_groups'));
}

if ($REX_USER->isValueOf('rights', 'admin[]')  || $REX_USER->isValueOf('rights', 'simple_shop[admin]')
    || $REX_USER->isValueOf('rights', 'simple_shop[orders]')) {
  $subpages[] = array ('orders', $I18N->msg('simple_shop_order_management'));
}

if ($REX_USER->isValueOf('rights', 'admin[]') || $REX_USER->isValueOf('rights', 'simple_shop[admin]')) {
  $subpages[] = array ('settings', $I18N->msg('simple_shop_settings'));
}


if ($REX_USER->isValueOf('rights', 'simple_shop[orders]') && !$REX_USER->isValueOf('rights', 'simple_shop[admin]')) {
	$_REQUEST['subpage'] = 'orders';
	$subpage = $_REQUEST['subpage'];
}


$mypage = 'simple_shop';

switch(rex_request('subpage'))
{
  case 'relations':
    // popup fuer verknuepfung
    include $REX['INCLUDE_PATH'].'/addons/simple_shop/pages/relation.inc.php';
    break;

  case 'discount_groups':
    // rabattstufen
    include $REX['INCLUDE_PATH'].'/layout/top.php';
    rex_title($I18N->msg('simple_shop'), $subpages);
    include $REX['INCLUDE_PATH'].'/addons/simple_shop/pages/discount_groups.inc.php';
    include $REX['INCLUDE_PATH'].'/layout/bottom.php';
    break;
  case 'amount_groups':
    // mindestestellmengengruppen
    include $REX['INCLUDE_PATH'].'/layout/top.php';
    rex_title($I18N->msg('simple_shop'), $subpages);
    include $REX['INCLUDE_PATH'].'/addons/simple_shop/pages/amount_groups.inc.php';
    include $REX['INCLUDE_PATH'].'/layout/bottom.php';

    break;
  case 'setup':
    // grundeinstellung
    include $REX['INCLUDE_PATH'].'/layout/top.php';
    rex_title($I18N->msg('simple_shop'), $subpages);
    include $REX['INCLUDE_PATH'].'/addons/simple_shop/pages/setup.inc.php';
    include $REX['INCLUDE_PATH'].'/layout/bottom.php';
    break;
  case 'settings':
    // grundeinstellung
    include $REX['INCLUDE_PATH'].'/layout/top.php';
    rex_title($I18N->msg('simple_shop'), $subpages);
    include $REX['INCLUDE_PATH'].'/addons/simple_shop/pages/settings.inc.php';
    include $REX['INCLUDE_PATH'].'/layout/bottom.php';
    break;
  case 'orders':
    // Bestellungen
    include $REX['INCLUDE_PATH'].'/layout/top.php';
    rex_title($I18N->msg('simple_shop'), $subpages);
    include $REX['INCLUDE_PATH'].'/addons/simple_shop/pages/orders.inc.php';
    include $REX['INCLUDE_PATH'].'/layout/bottom.php';
    break;
  default:
    // Produktverwaltung
    include $REX['INCLUDE_PATH'].'/layout/top.php';
    rex_title($I18N->msg('simple_shop'), $subpages);
    include $REX['INCLUDE_PATH'].'/addons/simple_shop/pages/articles.inc.php';
    include $REX['INCLUDE_PATH'].'/layout/bottom.php';

}

?>

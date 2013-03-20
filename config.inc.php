<?php

// Todo
// - Relationen
// - Mindestbestellmenge
// - Rabattstufen nach Kategorien
// - Rabattstufen f�r jedes Produkt


// --- DYN
$REX['ADDON']['simple_shop']['settings']['root_category_id']                = 56;
$REX['ADDON']['simple_shop']['settings']['article_id_basket']               = '64';
$REX['ADDON']['simple_shop']['settings']['article_id_terms_and_conditions'] = '66';
$REX['ADDON']['simple_shop']['settings']['article_id_shipping_rates']       = '68';
$REX['ADDON']['simple_shop']['settings']['article_id_cancellation_policy']  = '69';
$REX['ADDON']['simple_shop']['settings']['shipping_rates']                  = '2.50';
$REX['ADDON']['simple_shop']['settings']['tax_rates']                       = '19%=19;7%=7';
$REX['ADDON']['simple_shop']['settings']['gross_prices']                    = '1';
$REX['ADDON']['simple_shop']['settings']['refresh_seconds']                 = '0';
$REX['ADDON']['simple_shop']['settings']['module_relationlist_scheme']      = '###name### (###description_short###, ###description_format###)';
// --- /DYN


if(!$REX['REDAXO']) {
  if (session_id() == '')
    session_start();

  // letzte History Artikel Id merken, um vom Warenkorb zurueck zu gelangen
  if (isset($_SESSION['SIMPLE_SHOP']['HISTORY']) && $_SESSION['SIMPLE_SHOP']['HISTORY'] > 0) {
    if ($_SESSION['SIMPLE_SHOP']['HISTORY'] != $REX['ARTICLE_ID']) {
      $_SESSION['SIMPLE_SHOP']['LAST_ARTICLE_ID'] = $_SESSION['SIMPLE_SHOP']['HISTORY'];
    }
  }
  else {
    $_SESSION['SIMPLE_SHOP']['LAST_ARTICLE_ID'] = $REX['START_ARTICLE_ID'];
  }

  $_SESSION['SIMPLE_SHOP']['HISTORY'] = $REX['ARTICLE_ID'];
}




$mypage = 'simple_shop'; // only for this file

if($REX['REDAXO'])
{
	if(rex_get('css', 'string') == 'addons/'. $mypage)
	{
		$cssfile = $REX['INCLUDE_PATH'] .'/addons/'. $mypage .'/css/'.$mypage.'.css';
		rex_send_file($cssfile, 'text/css');
		exit();
	}
  /*
	rex_register_extension('PAGE_HEADER',
		create_function('$params', 'return $params[\'subject\'] .\'  <link rel="stylesheet" type="text/css" href="index.php?css=addons/'. $mypage .'" />\'."\n";')
	);
  */
}

// XForm Erweiterungen einbinden
function rex_shop_xform($params){
	global $REX;
	$REX['ADDON']['xform']['classpaths']['value'][]  = dirname(__FILE__) . '/xform/classes/value/';
	$REX['ADDON']['xform']['classpaths']['action'][] = dirname(__FILE__) . '/xform/classes/action/';
}
rex_register_extension('ADDONS_INCLUDED', 'rex_shop_xform');

include_once dirname(__FILE__) . '/classes/class.rex_shop.inc.php';
include_once dirname(__FILE__) . '/classes/class.rex_shop_utils.inc.php';
include_once dirname(__FILE__) . '/classes/class.rex_shop_product.inc.php';
include_once dirname(__FILE__) . '/classes/class.rex_shop_product_viewer.inc.php';
include_once dirname(__FILE__) . '/classes/class.rex_shop_category.inc.php';
include_once dirname(__FILE__) . '/classes/class.rex_shop_order.inc.php';
include_once dirname(__FILE__) . '/classes/class.rex_shop_basket.inc.php';
include_once dirname(__FILE__) . '/classes/class.rex_shop_basket_viewer.inc.php';
include_once dirname(__FILE__) . '/functions/function.rex_shop_blaettern.inc.php';

if ($REX['REDAXO']) {
	// only backend
  $I18N->appendFile(dirname(__FILE__) .'/lang/');
	//$I18N_SIMPLE_SHOP = new i18n($REX["LANG"],$REX["INCLUDE_PATH"]."/addons/$mypage/lang/"); 	// CREATE LANG OBJ FOR THIS ADDON
	$REX["ADDON"]["page"][$mypage] = $mypage;			// pagename/foldername
	$REX["ADDON"]["name"][$mypage] = $I18N->msg('simple_shop');
	$REX["ADDON"]["perm"][$mypage] = 'simple_shop[]'; 		// permission
	$REX["ADDON"]["version"][$mypage] = '1.0';
	$REX["ADDON"]["author"][$mypage] = 'Jan Kristinus, jan.kristinus@yakamara.de';
	$REX["PERM"][] = 'simple_shop[]';
	$REX["PERM"][] = 'simple_shop[admin]';
	$REX["PERM"][] = 'simple_shop[orders]';

}



// Defaultwerte:
/* JMK: Vorerst mal rausgenommen
if($page=="simple_shop" && $function == "edit_article" && $send!=1 && $aid<1){

	$article['deliverprice']	= 13;
	$article['tax']				= 16;

}
*/

?>

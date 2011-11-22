<?php

// Todo
// - Relationen
// - Mindestbestellmenge
// - Rabattstufen nach Kategorien
// - Rabattstufen für jedes Produkt

$mypage = "simple_shop"; // only for this file

if($REX['REDAXO'])
{
	if(rex_get('css', 'string') == 'addons/'. $mypage)
	{
		$cssfile = $REX['INCLUDE_PATH'] .'/addons/'. $mypage .'/css/'.$mypage.'.css';
		rex_send_file($cssfile, 'text/css');
		exit();
	}
	rex_register_extension('PAGE_HEADER',
		create_function('$params', 'return $params[\'subject\'] .\'  <link rel="stylesheet" type="text/css" href="index.php?css=addons/'. $mypage .'" />\'."\n";')
	);
}

// XForm Erweiterungen einbinden
function rex_shop_xform($params)
{
	global $REX;
	$REX['ADDON']['xform']['classpaths']['value'][] = $REX['INCLUDE_PATH'].'/addons/simple_shop/xform/classes/value/';
	$REX['ADDON']['xform']['classpaths']['action'][] = $REX['INCLUDE_PATH'].'/addons/simple_shop/xform/classes/action/';
}
rex_register_extension('ADDONS_INCLUDED', 'rex_shop_xform');

include_once $REX["INCLUDE_PATH"]."/addons/$mypage/classes/class.rex_shop.inc.php";
include_once $REX["INCLUDE_PATH"]."/addons/$mypage/classes/class.rex_shop_utils.inc.php";
include_once $REX["INCLUDE_PATH"]."/addons/$mypage/classes/class.rex_shop_product.inc.php";
include_once $REX["INCLUDE_PATH"]."/addons/$mypage/classes/class.rex_shop_product_viewer.inc.php";
include_once $REX["INCLUDE_PATH"]."/addons/$mypage/classes/class.rex_shop_category.inc.php";
include_once $REX["INCLUDE_PATH"]."/addons/$mypage/classes/class.rex_shop_order.inc.php";
include_once $REX["INCLUDE_PATH"]."/addons/$mypage/classes/class.rex_shop_basket.inc.php";
include_once $REX["INCLUDE_PATH"]."/addons/$mypage/classes/class.rex_shop_basket_viewer.inc.php";
include_once $REX["INCLUDE_PATH"]."/addons/$mypage/functions/function.rex_shop_blaettern.inc.php";

if ($REX['REDAXO'])
{
	// only backend
	$I18N_SIMPLE_SHOP = new i18n($REX["LANG"],$REX["INCLUDE_PATH"]."/addons/$mypage/lang/"); 	// CREATE LANG OBJ FOR THIS ADDON
	$REX["ADDON"]["page"][$mypage] = "$mypage";			// pagename/foldername
	$REX["ADDON"]["name"][$mypage] = $I18N_SIMPLE_SHOP->msg("simple_shop");
	$REX["ADDON"]["perm"][$mypage] = "simple_shop[]"; 		// permission
	$REX["ADDON"]["version"][$mypage] = "1.0";
	$REX["ADDON"]["author"][$mypage] = "Jan Kristinus, jan.kristinus@yakamara.de";
	$REX["PERM"][] = "simple_shop[]";
	$REX["PERM"][] = "simple_shop[admin]";
	$REX["PERM"][] = "simple_shop[orders]";
	

}

// backend and frontend

$REX["ADDON"]["tbl"]["art"][$mypage] = "rex_shop_article"; // article tabelle
$REX["ADDON"]["tbl"]["ord"][$mypage] = "rex_shop_order";
$REX["ADDON"]["tbl"]["ord_product"][$mypage] = "rex_shop_order_product";

// Defaultwerte:
/* JMK: Vorerst mal rausgenommen
if($page=="simple_shop" && $function == "edit_article" && $send!=1 && $aid<1){

	$article['deliverprice']	= 13;
	$article['tax']				= 16;

}
*/

?>

<?php



// TODO. Sprachunabhaengigkeit einbauen
// REX_PRODUCT_BUTTON
// REX_PRODUCT[id="1"]
// ...

$subpages = array();
if ($REX_USER->isValueOf("rights","admin[]") || $REX_USER->isValueOf("rights","simple_shop[admin]")) $subpages[] = array ('',$I18N_SIMPLE_SHOP->msg("catverwaltung"));
if ($REX_USER->isValueOf("rights","admin[]") || $REX_USER->isValueOf("rights","simple_shop[admin]")) $subpages[] = array ('discountgroups',"Rabattstufen");
if ($REX_USER->isValueOf("rights","admin[]") || $REX_USER->isValueOf("rights","simple_shop[admin]")) $subpages[] = array ('amountgroups',"Mindestbestellmengengruppen");
if ($REX_USER->isValueOf("rights","admin[]") || $REX_USER->isValueOf("rights","simple_shop[orders]") || $REX_USER->isValueOf("rights","simple_shop[admin]")) $subpages[] = array ('orders',$I18N_SIMPLE_SHOP->msg("bestverwaltung"));


$mypage = "simple_shop";
$subpage = rex_request('subpage', 'string');

if ($REX_USER->isValueOf("rights","simple_shop[orders]") && !$REX_USER->isValueOf("rights","simple_shop[admin]"))
{
	$subpage = 'orders';
}



switch($subpage)
{
	case "relations":
		// popup für verknüpfung
		include $REX["INCLUDE_PATH"]."/addons/$mypage/pages/relation.inc.php";
	break;
	case "discountgroups":
		// rabattstufen
	    include $REX["INCLUDE_PATH"]."/layout/top.php";
	    rex_title($I18N_SIMPLE_SHOP->msg("simple_shop"), $subpages);
		include $REX["INCLUDE_PATH"]."/addons/$mypage/pages/discountgroups.inc.php";
		include $REX["INCLUDE_PATH"]."/layout/bottom.php";
	break;
	case "amountgroups":
		// mindestestellmengengruppen
	    include $REX["INCLUDE_PATH"]."/layout/top.php";
	    rex_title($I18N_SIMPLE_SHOP->msg("simple_shop"), $subpages);
		include $REX["INCLUDE_PATH"]."/addons/$mypage/pages/amountgroups.inc.php";
		include $REX["INCLUDE_PATH"]."/layout/bottom.php";
	break;
	case "setup":
		// grundeinstellung
	    include $REX["INCLUDE_PATH"]."/layout/top.php";
	    rex_title($I18N_SIMPLE_SHOP->msg("simple_shop"), $subpages);
		include $REX["INCLUDE_PATH"]."/addons/$mypage/pages/setup.inc.php";
		include $REX["INCLUDE_PATH"]."/layout/bottom.php";
	break;
	case "orders":
		// Bestellungen
	    include $REX["INCLUDE_PATH"]."/layout/top.php";
	    rex_title($I18N_SIMPLE_SHOP->msg("simple_shop"), $subpages);
		include $REX["INCLUDE_PATH"]."/addons/$mypage/pages/orders.inc.php";
		include $REX["INCLUDE_PATH"]."/layout/bottom.php";
	break;
	default:
		// Produktverwaltung
	    include $REX["INCLUDE_PATH"]."/layout/top.php";
	    rex_title($I18N_SIMPLE_SHOP->msg("simple_shop"), $subpages);
		include $REX["INCLUDE_PATH"]."/addons/$mypage/pages/articles.inc.php";
		include $REX["INCLUDE_PATH"]."/layout/bottom.php";

}

?>
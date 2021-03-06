<?php

$page = 'simple_shop';
$subpage = 'orders';



$summe_heute = 0;
$summe_monat = 0;
$summe_gesamt = 0;

// Umsatz heute

$gg = new rex_sql();
// $gg->debugsql = 1;

echo '<table class="rex-table"><tbody>';

$gg->setQuery('select SUM(price_overall) as p from rex_shop_order where date LIKE "'.date("Y-m-d").'%"');

if ($gg->getRows() > 0) {
	$summe_heute = $gg->getValue("p");
}

echo '<tr><td>Umsatz heute:</td><td><b>'.rex_shop_utils::formatPrice($summe_heute).'</b></td></tr>';


for($i=0;$i<date("m");$i++)
{
	$ii = str_pad($i+1, 2, "0", STR_PAD_LEFT);
	$gg->setQuery('select SUM(price_overall) as p from rex_shop_order where date LIKE "'.date("Y-").$ii.'%"');
	
	if ($gg->getRows() > 0) {
		$summe_monat = $gg->getValue("p");
	}

	echo '<tr><td>Umsatz ('.$ii.''.date(". Y").'):</td><td><b>'.rex_shop_utils::formatPrice($summe_monat).'</b></td></tr>';
}

$gg->setQuery('select SUM(price_overall) as p from rex_shop_order where date LIKE "'.date("Y").'%"');

if ($gg->getRows() > 0) {
	$summe_gesamt = $gg->getValue("p");
}

echo '<tr><td>Gesamtumsatz ('.date("Y").'):</td><td><b>'.rex_shop_utils::formatPrice($summe_gesamt).'</b></td></tr>';



echo '</table><br />';















$mypage = "simple_shop";

$function = rex_request('function', 'string');
$order_id = rex_request('order_id', 'int');
$status = rex_request('status', 'int');

$stats = array ();
$stats_range = range(0, 10);
foreach ($stats_range as $stat)
{
  $stats[$stat] = null;
  if($I18N_SIMPLE_SHOP->hasMsg("status_" . $stat))
  {
    $stats[$stat] = '<span class="status_' . $stat . '">' . $I18N_SIMPLE_SHOP->msg("status_" . $stat) . '</span>';
  }
}

$sql = new sql;

if ($function == "delete" && $order_id > 0)
{
  $sql->setTable("rex_shop_order");
  $sql->where("id=" . $order_id . "");
  $sql->delete();
  $sql->setTable("rex_shop_order_product");
  $sql->where("order_id=" . $order_id . "");
  $sql->delete();

  $function = "";
  
} else if ($function == "change_state" && $status >= 0 && $order_id > 0)
{
  $sql->setTable("rex_shop_order");
  $sql->setValue("status", ''. $status);
  $sql->where("id=" . $order_id . "");
  $sql->update();
  
  $function = "show_order";
}

if ($function == 'show_order' && $order_id > 0)
{
  $sql->setQuery("SELECT *, DATE_FORMAT(date, '%d.%m.%Y %H:%i') as datum FROM rex_shop_order WHERE id=". $order_id);
 
$funcs = ''; 
foreach($stats_range as $stat)
{
    if($stat != $sql->getValue('status') && $stats[$stat] != null)
    {
        $funcs .= ' [ <a href="index.php?page=simple_shop&amp;subpage=orders&amp;function=change_state&amp;order_id='. $order_id .'&amp;status='. $stat .'">-&gt; ' . $stats[$stat] . '</a> ] ';
    }
}
  
  echo '
<table class=rex-table border=0 cellpadding=5 cellspacing=1>
  <tr>
    <th>Order-Id</th><td>'. $sql->getValue('id') .'</td>
  </tr>
  <tr>
    <th>Session-Id</th><td>'. $sql->getValue('session_id') .'</td>
  </tr>
  <tr>
    <th>Besteller</th><td>'. $sql->getValue('name') .'</td>
  </tr>
  <tr>
    <th>E-Mail</th><td>'. $sql->getValue('mail_to') .'</td>
  </tr>
  <tr>
    <th>Datum</th><td>'. $sql->getValue('datum') .'</td>
  </tr>
  <tr>
    <th>Status</th><td><strong>'. $stats[$sql->getValue('status')] .'</strong> '. $funcs .'</td>
  </tr>
  <tr>
    <th>Gesamtwert</th><td>'. rex_shop_utils::formatPrice($sql->getValue('price_overall')) .'</td>
  </tr>
  <tr>
    <th>IP-Adresse</th><td>'. $sql->getValue('ip') .'</td>
  </tr>
  <tr>
    <th>Titel</th><td>'. $sql->getValue('mail_subject') .'</td>
  </tr>
  <tr>
    <th>Bestellung</th><td>'. $sql->getValue('mail_text') .'</td>
  </tr>
</table>		

<br />

<table class="rex-table"><tbody><tr><td><a href="index.php?page=simple_shop&amp;subpage=orders&amp;function=delete&amp;order_id='. $order_id .'" onclick="return confirm(\'Wirklich löschen\');">- Bestellung löschen</a></td></tr></table>
';

} else if ($function == "") {

  $sql->setQuery("SELECT *, DATE_FORMAT(date, '%d.%m.%Y %H:%i') as datum FROM rex_shop_order ORDER BY date desc");

  $params = array(
  	"page" => $page,
  	"subpage" => $subpage,
  	);
  
  echo rex_shop_blaettern($sql, 0, $params, 10);

  echo "
<table class=rex-table border=0 cellpadding=5 cellspacing=1>
  <tr>
    <th></th>
    <th>Datum</th>
    <th>Besteller</th>
    <th>Bestellsumme</th>
    <th>Status</th>
    <th>Func</th>
  </tr>";

  for ($i = 0; $i < $sql->rows; $i++)
  {
    $url = "index.php?page=" . $mypage . "&amp;subpage=" . $subpage . "&amp;function=show_order&amp;order_id=". $sql->getValue("id");
    echo "
    <tr>
	    <td class=icon><a href='". $url . "'><img src=\"media/document.gif\" border=\"0\" height=\"16\" width=\"16\"></a></td>
	    <td width=100>" . $sql->getValue("datum") . "</td>
	    <td>" . $sql->getValue("name") . "</td>
	    <td>" . rex_shop_utils::formatPrice($sql->getValue('price_overall')) . "</td>
	    <td width=100>" . $stats[$sql->getValue("status")] . "</td>
	    <td width=250 ><a href='". $url . "'>" . $I18N_SIMPLE_SHOP->msg("header_order_edit") . "</td>
    </tr>
    <tr>
        <td></td>
        <td colspan=5><i>". substr(strip_tags($sql->getValue('mail_text')), 0, 200) ."</i></td>
    </tr>";

    $sql->next();
  }
  echo "</table>";
}
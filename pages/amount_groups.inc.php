<?php

$refresh_seconds = $REX['ADDON']['simple_shop']['settings']['refresh_seconds'];
$page_url = 'index.php?page=simple_shop&subpage=' . rex_request('subpage', 'string');


$function = rex_request('function', 'string');
$group_id = rex_request('group_id', 'int');


echo '<div class="rex-addon-output">';


// ***************************************************************************** Produktdefinitionen

$form_data = '
fieldset||' . $I18N->msg('simple_shop_amount_group') . '

hidden|page|simpleshop|REQUEST|no_db
hidden|subpage|amount_groups|REQUEST|no_db

select|status|Status|Online=1,Offline=0|0

text|name|' . $I18N->msg('simple_shop_name') . '
textarea|description|' . $I18N->msg('simple_shop_description') . '

text|amount|' . $I18N->msg('simple_shop_amount_groups_amount') . '

validate|empty|amount|' . $I18N->msg('simple_shop_amount_groups_amount_error') . '
validate|empty|name|' . $I18N->msg('simple_shop_amount_groups_name_error') . '
validate|empty|description|' . $I18N->msg('simple_shop_amount_groups_description_error') . '

';

$form_data = trim(str_replace('<br />', '', rex_xform::unhtmlentities($form_data)));
$xform = new rex_xform;
//$xform->setDebug(TRUE);
$xform->setFormData($form_data);
$xform->objparams['actions'][] = array('type' => 'showtext','elements' => array('action', 'showtext', '', rex_info( $I18N->msg('simple_shop_message_amount_group_added') . '<br /><br />' . $I18N->msg('simple_shop_redirect', $refresh_seconds, $page_url) ),'',),);

$xform->setObjectparams('main_table', 'rex_shop_product_amount_group'); // fuer db speicherungen und unique abfragen


// ***************************************************************************** Produkt editieren

if ($function == 'edit') {

	$form_data .= '
hidden|function|edit|REQUEST|no_db
hidden|group_id|'.$group_id.'|REQUEST|no_db';

	$form_data = trim(str_replace("<br />","",rex_xform::unhtmlentities($form_data)));
	$xform = new rex_xform;
	// $xform->setDebug(TRUE);
	$xform->setFormData($form_data);

  $xform->objparams['actions'][] = array('type' => 'showtext','elements' => array('action', 'showtext', '', rex_info( $I18N->msg('simple_shop_message_amount_group_updated') . '<br /><br />' . $I18N->msg('simple_shop_redirect', $refresh_seconds, $page_url) ),'',),);
	$xform->objparams['actions'][] = array('type' => 'db', 'elements' => array('action', 'db', 'rex_shop_product_amount_group','id="' . $group_id .'"'),);

	$xform->setObjectparams('main_id', $group_id);
	$xform->setObjectparams('main_where','id="' . $group_id .'"');
	$xform->setObjectparams('main_table','rex_shop_product_amount_group'); // fuer db speicherungen und unique abfragen
	$xform->setObjectparams('getdata', true); // Dateien vorher auslesen

	echo $xform->getForm();

  if ($xform->objparams['postactions_executed'] == 1) {
    header('refresh:' . $refresh_seconds . '; url=' . $page_url);
    exit();
  }
}




// ***************************************************************************** Produkt hinzuf�gen

if ($function == 'add') {

	$form_data .= '
hidden|function|add|REQUEST|no_db';

	$form_data = trim(str_replace('<br />', '', rex_xform::unhtmlentities($form_data)));
	$xform = new rex_xform;
	// $xform->setDebug(TRUE);
	$xform->setFormData($form_data);

  $xform->objparams['actions'][] = array('type' => 'showtext','elements' => array('action', 'showtext', '', rex_info( $I18N->msg('simple_shop_message_amount_group_added') . '<br /><br />' . $I18N->msg('simple_shop_redirect', $refresh_seconds, $page_url) ),'',),);
	$xform->objparams['actions'][] = array('type' => 'db', 'elements' => array('action', 'db', 'rex_shop_product_amount_group'),);

	$xform->setObjectparams('main_table', 'rex_shop_product_amount_group'); // fuer db speicherungen und unique abfragen
	echo $xform->getForm();

  if ($xform->objparams['postactions_executed'] == 1) {
    header('refresh:' . $refresh_seconds . '; url=' . $page_url);
    exit();
  }
}







// ***************************************************************************** Produktformularausgabe







// ***************************************************************************** Produktliste


//---------------------------------- Online / Offline switch
if($function == 'online') {

  $sql = rex_sql::factory();
  $sql->setTable('rex_shop_product_amount_group');
  $sql->setWhere('id = '.$group_id);
  $sql->setValue('status', 1);
  $sql->update();
  $function = '';
}
if($function == 'offline'){

  $sql = rex_sql::factory();
  $sql->setTable('rex_shop_product_amount_group');
  $sql->setWhere('id = '.$group_id);
  $sql->setValue('status', 0);
  $sql->update();
  $function = '';
}

//----------------------------------- Gruppe löschen
if($function == 'delete_group') {

  $sql = rex_sql::factory();
  $sql->setTable('rex_shop_product_amount_group');
  $sql->setWhere('id = '.$group_id);
  if ($sql->delete()) {
    echo rex_info($I18N->msg('simple_shop_amount_group_deleted'));
  }
  else {
    echo rex_warning($I18N->msg('simple_shop_error') . ' ' . $I18N->msg('simple_shop_amount_group_has_not_been_deleted'));
  }

  $function = '';
}



if ($function == '') {

  echo	'
	<table class="rex-table">
	  <colgroup>
	    <col width="40" />
	    <col width="*" />
	    <col width="51" />
	    <col width="50" />
	    <col width="50" />
	  </colgroup>
	  <thead>
				<tr>
					<th class="rex-icon"><a class="rex-i-element rex-i-article-add" href="index.php?page=simple_shop&subpage=amount_groups&function=add"><span class="rex-i-element-text">' . $I18N->msg('simple_shop_amount_group_add') . '</span></a></th>
					<th>'.$I18N->msg('simple_shop_amount_group').'</th>
					<th colspan="3">'.$I18N->msg('simple_shop_header_status_function').'</th>
				</tr>
		</thead>
		<tbody>';

  $sql = rex_sql::factory();
  $sql->setQuery('SELECT * FROM rex_shop_product_amount_group ORDER BY name');


	if($sql->getRows() > 0) {
    $results = $sql->getArray();

    foreach($results as $group) {
			echo '
				<tr>
				<td class="rex-icon"><a class="rex-i-element rex-i-article" href="index.php?page=simple_shop&subpage=amount_groups&function=edit&group_id='.$group["id"].'"><span class="rex-i-element-text">' . $I18N->msg('simple_shop_amount_group_edit') . '</span></a></td>
				<td>'.htmlspecialchars($group["name"]).'</td>
				<td><a href="index.php?page=simple_shop&subpage=amount_groups&function=edit&group_id='.$group["id"].'">' . $I18N->msg('simple_shop_edit') . '</td>';

      $group_status = '';
      if ($group['status'] == 0) {
        $group_status = '<a class="rex-offline" href="index.php?page=simple_shop&subpage=amount_groups&group_id='.$group["id"].'&function=online">' . $I18N->msg('simple_shop_status_offline') . '</a>';
      }
      else {
        $group_status = '<a class="rex-online" href="index.php?page=simple_shop&subpage=amount_groups&group_id='.$group["id"].'&function=offline">' . $I18N->msg('simple_shop_status_online') . '</a>';
      }
      echo '<td>'.$group_status.'</td>';

      echo '<td><a href="index.php?page=simple_shop&subpage=amount_groups&function=delete_group&group_id='.$group["id"].'" onclick="return confirm(\'' . $I18N->msg('simple_shop_amount_group_really_delete') . '\')">' .$I18N->msg('simple_shop_delete') . '</a></td>';

      echo '</tr>';
		}
	}
	echo '</table>';
}

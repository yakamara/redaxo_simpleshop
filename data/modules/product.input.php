<?php
// Relationlist Id .> Relationlist[ Id ]
$id = 1;

$root_category_id = $REX['ADDON']['simple_shop']['settings']['root_category_id'];
?>
    <script type="text/javascript">
        <!--


        function openREXRelationlist(rel) {
            newWindow( 'relations', 'index.php?page=simple_shop&subpage=relations&relation_id=' + rel, 800,600,',status=yes,resizable=yes');
        }

        function deleteREXRelationlist(id) {
            deleteREX(id, 'REX_RELATIONLIST_', 'REX_RELATIONLIST_SELECT_');
        }

        function selectRelationlist(id, name)
        {
            var relationlist = "REX_RELATIONLIST_SELECT_<?php echo $id; ?>";

            var source = document.getElementById(relationlist);
            var sourcelength = source.options.length;

            option = document.createElement("OPTION");
            option.text  = name;
            option.value = id;

            source.options.add(option, sourcelength);
            writeREXRelationlist(<?php echo $id; ?>);
        }

        function writeREXRelationlist(id){
            writeREX(id, 'REX_RELATIONLIST_', 'REX_RELATIONLIST_SELECT_');
        }


        //-->
    </script>

<?php

$r = new rex_select();
$r->setId('REX_RELATIONLIST_SELECT_' . $id);
$r->setName('VALUE[2][]');
$r->setSize(8);

$products = explode(',', 'REX_VALUE[1]');
if (count($products) > 0 && $products[0] != '') {
    foreach ($products as $product_id) {
        if ($product = new rex_shop_product($product_id, 0)) {
            $name = $product->getModuleProductName( $REX['ADDON']['simple_shop']['settings']['module_relationlist_scheme'] );
            $r->addOption($name, $product->getId());
        }
    }
}


$selected = 'REX_VALUE[3]';
if($selected == '') {
    $selected = REX_ARTICLE_ID;
}


$c = new rex_category_select(false, false, true, false);
$c->setName('VALUE[3]');
$c->setRootId($root_category_id);
$c->setSize(1);
$c->setStyle('width:200px;');
$c->setSelected($selected);


$s = new rex_select();
$s->setName("VALUE[4]");
$s->setSize(1);
$s->setStyle('width:200px;');
$s->addOption('Nach Titel/Name', 'name asc');
$s->addOption('Nach Preis', 'price asc');
$s->addOption('Nach Prio', 'prio asc');
$s->setSelected('REX_VALUE[4]');

/*
$o = new rex_select();
$o->setName("VALUE[5]");
$o->setSize(1);
$o->setStyle('width:200px;');
$o->addOption('Liste / Teaser', 'list');
$o->addOption('Detail', 'detail');
$o->setSelected('REX_VALUE[5]');

  <tr>
    <th colspan="2" style="text-align: center;">Allgemein</th>
  </tr>
  <tr>
    <th style="width: 150px;">Ausgabe</th>
    <td>' . $o->get() . '</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
*/


echo '

<table class="rex-table">

  <tr>
    <th colspan="2" style="text-align: center;">entweder</th>
  </tr>
  <tr>
    <th colspan="2" style="text-align: center;">automatisches Auslesen</th>
  </tr>
  <tr>
    <th>Kategorie auswählen</th>
    <td>' . $c->get() . '</td>
  </tr>
  <tr>
    <th>Sortierung festlegen</th>
    <td>' . $s->get() . '</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>

  <tr>
    <th colspan="2" style="text-align: center;">oder</th>
  </tr>
  <tr>
    <th colspan="2" style="text-align: center;">manuelles Zusammenstellen</th>
  </tr>
  <tr>
    <th>Produkte auswählen</th>
    <td>
      <div class="rex-widget">
        <div class="rex-widget-medialist">
          <input type="hidden" name="VALUE[1]" id="REX_RELATIONLIST_' . $id . '" value="" />
          <p class="rex-widget-field">
            ' . $r->get() . '
          </p>
          <p class="rex-widget-icons rex-widget-2col">
            <span class="rex-widget-column rex-widget-column-first">
              <a href="#" class="rex-icon-file-top"'    . ' onclick="moveREX(' . $id . ', \'REX_RELATIONLIST_\', \'REX_RELATIONLIST_SELECT_\', \'top\'); return false;" title="Ausgewähltes Produkt an den Anfang verschieben"></a>
              <a href="#" class="rex-icon-file-up"'     . ' onclick="moveREX(' . $id . ', \'REX_RELATIONLIST_\', \'REX_RELATIONLIST_SELECT_\', \'up\'); return false;" title="Ausgewähltes Produkt nach oben verschieben"></a>
              <a href="#" class="rex-icon-file-down"'   . ' onclick="moveREX(' . $id . ', \'REX_RELATIONLIST_\', \'REX_RELATIONLIST_SELECT_\', \'down\'); return false;" title="Ausgewähltes Produkt nach unten verschieben"></a>
              <a href="#" class="rex-icon-file-bottom"' . ' onclick="moveREX(' . $id . ', \'REX_RELATIONLIST_\', \'REX_RELATIONLIST_SELECT_\', \'bottom\'); return false;" title="Ausgewähltes Produkt an das Ende verschieben"></a>
            </span>
            <span class="rex-widget-column">
              <a href="#" class="rex-icon-file-open"'   . ' onclick="openREXRelationlist(' . $id . ', \'\'); return false;" title="Produkt auswählen"></a>
              <a href="#" class="rex-icon-file-delete"' . ' onclick="deleteREXRelationlist(' . $id . '); return false;" title="Ausgewähltes Produkt löschen"></a>
            </span>
          </p>
          <div class="rex-media-preview"></div>
        </div>
      </div>
      <div class="rex-clearer"></div>
    </td>
  </tr>
</table>';
?>
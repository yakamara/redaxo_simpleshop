<script language=Javascript>
  <!--

  function openREXShop(rel){
    // REX_SHOP_1_NAME REX_SHOP_1_ID
    newWindow( 'relations', 'index.php?page=simple_shop&subpage=relations&rel_id='+rel, 800,600,',status=yes,resizable=yes');
  }

  function deleteREXShop(rel){
    // id and name
    document.getElementById('REX_SHOP_'+rel+'_NAME').value = '';
    document.getElementById('REX_SHOP_'+rel+'_ID').value = '';
  }

  function setREXShop(rel,id,name)
  {
    document.getElementById('REX_SHOP_'+rel+'_NAME').value = name;
    document.getElementById('REX_SHOP_'+rel+'_ID').value = id;
  }

  //-->
</script>

<?php

if ($a = new rex_shop_product("REX_VALUE[1]",0)) {
  $rel_id = $a->getID();
  $rel_name = $a->getName();
}

?>

<input type="hidden" name="VALUE[1]" id="REX_SHOP_1_ID" value="<?php echo $rel_id; ?>" />
<table class="rex-table">
  <tr>
    <th>Produkt auswählen</th>
    <td>
      <input type="text" size="30" name="REX_SHOP_1_NAME" value="<?php echo $rel_name; ?>" class="inpgrey" id="REX_SHOP_1_NAME" readonly="readonly" />
      <a href="javascript:openREXShop(1);"><img src="media/file_open.gif" width="16" height="16" title="medienpool" /></a>
      <a href="javascript:deleteREXShop(1);"><img src="media/file_del.gif" width="16" height="16" title="-" /></a>
    </td>
  </tr>
</table>

<!-- OUT -->
<?php
$recalcArticleId = true;
$output = "";
if($product = new rex_shop_product("REX_VALUE[1]", $REX['CUR_CLANG'])) {
  if($product->getStatus() == 1) {

    $resize = 'shoplist';
    $description = '<span class="text">'.nl2br($product->getValue("description_short")).'</span>';
    if (REX_CTYPE_ID == 2) {
      $resize = 'shopteaser';
      $description = '';
    }
    $output .= '<ul class="simple-shop-productlist">
		            <li>
		              <dl>
		                <dt>
		                  <a href="'.$product->getDetailUrl($recalcArticleId).'">
		                    <img src="/index.php?rex_img_type='.$resize.'&rex_img_file='.$product->getImage().'" alt="'.htmlspecialchars($product->getName()).'" title="'.htmlspecialchars($product->getName()).'" />
		                  </a>
		                </dt>
		                <dd>
		                  <h3>
		                  <a class="title" href="'.$product->getDetailUrl($recalcArticleId).'">
		                  '.nl2br(htmlspecialchars($product->getName())).'
		                  </a>
		                  </h3>

		                  '.$description.'

		                  <span class="price">Preis: '.rex_shop_utils::formatPrice($product->getPrice()).'</span>

		                  <a class="detail" href="'.$product->getDetailUrl($recalcArticleId).'">Details</a>
		                </dd>
		              </dl>
		            </li>
		          </ul>
				     ';

  }
}
echo $output;
?>
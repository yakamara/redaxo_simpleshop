<?php

class rex_xform_simple_shop_category extends rex_xform_abstract
{

	function enterObject()
	{
		global $REX;

    $root_category_id = isset($REX['ADDON']['simple_shop']['settings']['root_category_id']) ? $REX['ADDON']['simple_shop']['settings']['root_category_id'] : 0;

    $sel_cat = new rex_category_select(false, false, false, false);
    $sel_cat->setAttribute('class', 'rxshp-wdth');
    $sel_cat->setName('root_category_id');
    $sel_cat->setId('rex-root-category-id');
    $sel_cat->setRootId($root_category_id);
    $sel_cat->setMultiple(1);
    $sel_cat->setSize(20);
    $sel_cat->setName($this->getFieldName() . '[]');


		if (!is_array($this->getValue())) {
      $this->setValue(explode('|', $this->getValue()));
		}

		$categories = '|';
		if (is_array($this->getValue())) {
			foreach($this->getValue() as $category) {
				if ($category != '') {
					$categories .= $category . '|';
					$sel_cat->setSelected($category);
				}
			}
		}

    $this->setValue($categories);


    $wc = "";
    if (isset($this->params['warning'][$this->getId()])) {
      $wc = $this->params['warning'][$this->getId()];
    }

		
		$sel_cat->setStyle(' class="select ' . $wc . '"');

    $this->params['form_output'][$this->getId()] = '
      <p class="formselect '.$this->getHTMLClass().'" id="'.$this->getHTMLId().'">
      <label class="select '.$wc.'" for="'.$this->getFieldId().'" >'.rex_translate($this->getElement(2)).'</label>'.
      $sel_cat->get().
      '</p>';

    $this->params['value_pool']['email'][$this->getElement(1)] = $this->getValue();
    if ($this->getElement(4) != 'no_db') {
      $this->params['value_pool']['sql'][$this->getElement(1)] = $this->getValue();
    }



	}
	
	function getDescription()
	{
		return "simple_shop_categories -> Beispiel: simple_shop_categories|";
	}
	


	// -----------------------> zugriff auf categorien
	function add_cat_options( &$select, &$cat, &$cat_ids, $groupName = '') {
	    if( empty( $cat)) {
	        return;
	    }
	
	    $cat_ids[] = $cat->getId();
	    $select->addOption($cat->getName(),$cat->getId(), $cat->getId(),$cat->getParentId());
	    $childs = $cat->getChildren();
	
	    if ( is_array( $childs)) {
	        foreach ( $childs as $child) {
	            add_cat_options( $select, $child, $cat_ids, $cat->getName());
	        }
	    }
	}

	
	
	
}

?>
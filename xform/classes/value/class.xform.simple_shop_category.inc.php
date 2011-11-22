<?php

class rex_xform_simple_shop_category extends rex_xform_abstract
{

	function enterObject()
	{
		global $REX,$I18N_SIMPLE_SHOP;
		
		$sel_cat = new rex_select();
		$sel_cat->setAttribute('class',"rxshp-wdth");
		$sel_cat->setStyle('height:250px;');
		$sel_cat->setMultiple(1);
		$sel_cat->setSize(20);
		$sel_cat->setName($this->getFieldName().'[]');
		// $SEL->setName();
		
		$cat_ids = array();
		
		if ((int)$this->getElement(3) > 0)
		{
			if ($rootCat = OOCategory::getCategoryById($this->getElement(3)))
			{
				$this->add_cat_options( $sel_cat, $rootCat, $cat_ids);			
			}
		}
		else
		{
			if ($rootCats = OOCategory::getRootCategories())
			{
				foreach( $rootCats as $rootCat) {
					$this->add_cat_options( $sel_cat, $rootCat, $cat_ids);
				}
			}
		
		}
		

		if (is_string($this->getValue()) and $this->getValue() != "")
		{
			$this->setValue(explode("|",$this->getValue()));
		}

		$categories = "|";
		if (is_array($this->getValue()))
		{		
			foreach($this->getValue() as $cat)
			{
				if ($cat != "")
				{
					$categories .= $cat."|";
					$sel_cat->setSelected($cat);
				}
			}
		}

		$this->setValue($categories) ;
		
		// echo "*******".$this->value."*******";
		
		
		$wc = "";
		if (isset($this->params["warning"][$this->getId()])) 
			$wc = $this->params["warning"][$this->getId()];
		
		$sel_cat->setStyle(' class="select ' . $wc . '"');
				
		$this->params["form_output"][$this->getId()] = '<p class="formselect">
			<label class="select ' . $wc . '" for="'.$this->getHTMLId().'" >' . $this->getElement(2) . '</label>' . 
			$sel_cat->get() . '
			</p>';
	
		
		
		$this->params["value_pool"]["email"][$this->getElement(1)] = stripslashes($this->getValue());
		
		if ($this->getElement(4) != "no_db")
		  $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();

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
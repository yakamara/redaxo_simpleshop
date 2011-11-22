<?php

class rex_xform_simple_shop_showhide extends rex_xform_abstract
{

	function enterObject()
	{	

    $output = '';

		if ($this->params["shop_warning"] && $this->getElement(1) == "start")
		{
			$output = '<div style="display:none">';
		
		}elseif ($this->params["shop_warning"] && $this->getElement(1) == "end")
		{
			$output = '</div>';
		
		}

		$this->params["form_output"][$this->getId()] = $output;
		
	}
	
	function getDescription()
	{
		return "simple_shop_showhide -> Beispiel: simple_shop_showhide|start/end";
	}
}

?>
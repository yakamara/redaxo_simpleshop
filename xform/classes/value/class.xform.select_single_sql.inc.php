<?php

class rex_xform_select_single_sql extends rex_xform_abstract
{

	function enterObject()
	{

		$SEL = new rex_select();
		$SEL->setName($this->getFieldName());
		$SEL->setId($this->getId());
		$SEL->setSize(1);

		$sql = $this->getElement(4);
		$teams = new rex_sql;
		$teams->debugsql = $this->params["debug"];
		$teams->setQuery($sql);
		$sqlnames = array();

		if ($this->getElement(3) != 1)
		{
			// mit --- keine auswahl ---
			$SEL->addOption($this->getElement(3), "0");
		}

		for ($t = 0; $t < $teams->getRows(); $t++)
		{
			$SEL->addOption($teams->getValue($this->getElement(6)), $teams->getValue($this->getElement(5)));

			if ($this->getElement(7))
			 $sqlnames[$teams->getValue($this->getElement(5))] = $teams->getValue($this->getElement(7));

			$teams->next();
		}

		$wc = "";
		if (isset($this->params["warning"][$this->getId()])) 
			$wc = $this->params["warning"][$this->getId()];
			

		$SEL->setStyle(' class="select ' . $wc . '"');

		if ($this->getValue() == "" && $this->getElement(7) != "")
		  $this->setValue($this->getElement(7));

		$SEL->setSelected($this->getValue());

		$this->params["form_output"][$this->getId()] = '
			<p class="formselect">
			<label class="select ' . $wc . '" for="'.$this->getHtmlId().'" >' . $this->getElement(2) . '</label>
			' . $SEL->get() . '
			</p>';


		$this->params["value_pool"]["email"][$this->getElement(1)] = stripslashes($this->getValue());
		if (isset($sqlnames[$this->getValue()]))
		  $email_elements[$this->getElement(1).'_SQLNAME'] = stripslashes($sqlnames[$this->getValue()]);
		
		if ($this->getElement(8) != "no_db")
		  $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();

		
	}
	
	function getDescription()
	{
		return "select_single_sql -> Beispiel: select_single_sql|stadt_id|BASE *:|1|select * from branding_rex_staedte order by name|id|name|default|[no_db]";
	}
}

?>
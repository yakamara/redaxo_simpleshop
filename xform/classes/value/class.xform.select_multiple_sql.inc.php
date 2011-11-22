<?php

class rex_xform_select_multiple_sql extends rex_xform_abstract
{

	function enterObject()
	{

		// ***** SELECT FESTLEGEN
		
		$SEL = new rex_select();
		$SEL->setName($this->getFieldName().'[]');
		$SEL->setId($this->getId());
		$SEL->setStyle('height: 50px;');
		$SEL->setMultiple(1);


		// ***** SQL - ROHDATEN ZIEHEN

		$sql = $this->getElement(5);
		$teams = new sql;
		if (isset($this->params["debugsql"]) AND $this->params["debugsql"]) $teams->debugsql = true;
		$teams->setQuery($sql);
		for ($t = 0; $t < $teams->getRows(); $t++)
		{
			$SEL->add_option($teams->getValue($this->getElement(7)), $teams->getValue($this->getElement(6)));
			$teams->next();
		}
		
		$wc = "";
		if (isset($this->params["warning"][$this->getId()])) 
			$wc = $this->params["warning"][$this->getId()];
			
		$SEL->setStyle(' class="select ' . $wc . '" style="height:100px;"');
		

		// ***** EINGELOGGT ODER NICHT SETZEN

		if ($this->params["send"]!=1)
		{
			// erster aufruf
			// Daten ziehen
			if (isset($this->params["main_id"]) AND $this->params["main_id"]>0)
			{
				$value_out = array();
				$g = new rex_sql;
				if ($this->params["debug"]) $g->debugsql = 1;
				$g->setQuery('select '.$this->getElement(3).' from '.$this->getElement(1).' where '.$this->getElement(2).'='.$this->params["main_id"]);
				$gg = $g->getArray();
				if (is_array($gg))
				{
					foreach($gg as $g)
					{
						$value_out[] = $g[$this->getElement(3)];
					}
				}
				$this->setValue($value_out);
			}
		}

		// ***** AUSWAHL SETZEN
		if (is_array($this->getValue()))
		{
			foreach($this->getValue() as $val) $SEL->setSelected($val);
		}
		

		// ***** AUSGEBEN

		$this->params["form_output"][$this->getId()] = '
			<p class="formselect">
				<label class="select ' . $wc . '" for="el_' . $this->id . '" >' . $this->getElement(4) . '</label>
				' . $SEL->get() . '
			</p>';
		
	}
	
	function postSQLAction () {
	
		// alte eintraege loeschen
		// neue eintraege setzen
		$g = new rex_sql;
		if ($this->params["debug"]) $g->debugsql = 1;
		$g->setQuery('delete from '.$this->getElement(1).' where '.$this->getElement(2).'='.$this->params["main_id"]);
		
		if (is_array($this->getValue()))
		{
			foreach($this->getValue() as $val)
			{
				$g->setQuery('insert into '.$this->getElement(1).' set '.$this->getElement(3).'="'.$val.'", '.$this->getElement(2).'='.$this->params["main_id"]);
			}
		}
		
	
	}
	
	function getDescription()
	{
		return "select_multiple_sql -> Beispiel: select_multiple_sql|
			rex_vk_user_city|user_id|city_id|
			BASE *:|
			select * from branding_rex_5_staedte where aktiv=1 order by name|id|name";
	}
}







		/*
		*******************
		abhaengig ob id vorhanden oder nicht
		$SEL->set_selected($this->value);
		*/


		/*
		$email_elements[$this->elements[1]] = stripslashes($this->value);
		$email_elements[$this->elements[1].'_SQLNAME'] = stripslashes($sqlnames[$this->value]);
		*/

		/*
		*******************
		als post aktion einbauen
		if ($this->elements[8] != "no_db") $sql_elements[$this->elements[1]] = $this->value;
		*/







?>
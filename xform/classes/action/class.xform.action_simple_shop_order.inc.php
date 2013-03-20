<?php

class rex_xform_action_simple_shop_order extends rex_xform_action_abstract
{

    function execute()
    {
        global $REX;

        $gt = new rex_sql();
        if ($this->params['debug'])
            $gt->debugsql = true;

        $gt->setQuery('select * from rex_xform_email_template where name="' . $this->getElement(2) . '"');
        if ($gt->getRows() == 1) {
            // --------------- Email verschicken
            $mail_to = $REX['ERROR_EMAIL'];
            if ($this->getElement(3) != '') {
                foreach ($this->params['value_pool']['email'] as $key => $value)
                    if ($this->getElement(3) == $key)
                        $mail_to = $value;
            }

            if ($this->getElement(4) != '')
                $mail_to = $this->getElement(4);

            $mail_from = $gt->getValue('mail_from');
            $mail_subject = $gt->getValue('subject');
            $mail_body = $gt->getValue('body');

            // Normale felder ersetzen
            foreach ($this->params['value_pool']['email'] as $search => $replace) {
                $mail_from = str_replace('###' . $search . '###', $replace, $mail_from);
                $mail_subject = str_replace('###' . $search . '###', $replace, $mail_subject);
                $mail_body = str_replace('###' . $search . '###', $replace, $mail_body);
            }

            $mail_body = nl2br($mail_body);

            // Warenkorb Daten ersetzen
            $shopItems = rex_shop_basket_viewer :: getPlainBasket();
            $item_mail_body = str_replace('###SimpleShopItems###', $shopItems, $mail_body);

            $shopItems = rex_shop_basket_viewer :: getHtmlBasket();
            $item_htmlmail_body = str_replace('###SimpleShopItems###', $shopItems, $mail_body);
            $item_htmlmail_body = '<style>a,p,td,body {font-family:Arial;} </style>' . $item_htmlmail_body;

            // $mail = new PHPMailer();
            $mail = new rex_mailer();
            $mail->AddAddress($mail_to, $mail_to);
            // $mail->AddAddress($REX['ERROR_EMAIL']);
            $mail->WordWrap = 100;
            $mail->FromName = $mail_from;
            $mail->From = $mail_from;
            $mail->Subject = $mail_subject;
            $mail->Body = $item_htmlmail_body;
            $mail->AltBody = strip_tags($item_mail_body);
            // $mail->IsHTML(true);
            if (!$mail->Send())
                echo 'FAILED';

        }

        if ($this->getElement(7) != 'no_db') {

            // --------------- Daten in DB speichern
            $sql = new rex_sql();
            if ($this->params['debug']) $sql->debugsql = true;

            $main_table = '';
            if ($this->getElement(5) != '')
                $main_table = $this->getElement(5);
            else
                $main_table = $this->params['main_table'];

            if ($main_table == '') {
                $this->params['form_show'] = true;
                $this->params['hasWarnings'] = true;
                $this->params['warning_messages'][] = $this->params['Error-Code-InsertQueryError'];
                return false;
            }

            $sql->setTable($main_table);

            $where = '';
            if (trim($this->getElement(6)) != '')
                $where = trim($this->getElement(6));

            // Warenkorb Daten ersetzen
            $shopItems = rex_shop_basket_viewer :: getHtmlBasket();
            $item_mail_body = str_replace('###SimpleShopItems###', $shopItems, $mail_body);

            // SQL Objekt mit Werten fuellen
            $sql->setValue('session_id', session_id());
            $sql->setValue('price_overall', rex_shop_basket :: getOverallPrice());
            $sql->setValue('status', '0');
            $sql->setValue('date', date('Y-m-d H:i'));
            $sql->setValue('name', addslashes($this->params['value_pool']['sql']['name'] . ' ' . $this->params['value_pool']['sql']['vorname']));
            $sql->setValue('mail_to', addslashes($mail_to));
            $sql->setValue('mail_subject', addslashes($mail_subject));
            $sql->setValue('mail_text', addslashes($item_mail_body));
            $sql->setValue('ip', $_SERVER['REMOTE_ADDR']);

            foreach ($this->params['value_pool']['sql'] as $key => $value) {
                // $sql->setValue($key, $value);
                if ($where != '')
                    $where = str_replace('###' . $key . '###', addslashes($value), $where);
            }

            if ($where != '') {
                $sql->setWhere($where);
                $sql->update();
                $flag = 'update';
            } else {
                $sql->insert();
                $flag = 'insert';
                $id = $sql->getLastId();

                $this->params['value_pool']['email']['ID'] = $id;
                // $this->elements_sql["ID"] = $id;
                if ($id == 0) {
                    $this->params['form_show'] = true;
                    $this->params['hasWarnings'] = true;
                    $this->params['warning_messages'][] = $this->params['Error-Code-InsertQueryError'];
                } else {
                    rex_shop_basket::clearBasket();
                }
            }
        }
    }

    function getDescription()
    {
        return 'action|simple_shop_order|mailKey|emaillabel|[email@domain.de]|order_table_name|where_clause|[no_db]';
    }

}

<?php
class ModelOperatorProductUpdateRequest extends Model {

    public function approveRequest($request, $product_info, $stock_status_index) {
	    $product_id = (int) $request['product_id'];
        if(!$product_id) { return; }
	    $this->load->model("catalog/product");

	    $quantity = null;
        if($request['new_stock_status_id']) {
            $quantity = $stock_status_index[$request['new_stock_status_id']] === "In Stock" ? 100 : 0;
        }
        $sql = "UPDATE ". DB_PREFIX ."product SET date_modified = now(), ";
        $implode = array();
        if($quantity !== null) {
            $implode[] = "quantity = '". $quantity ."'";
        }

        if($request['new_stock_status_id']) {
            $implode[] = "stock_status_id = '". $request['new_stock_status_id'] ."'";
        }

        if($request['new_price']) {
            $implode[] = "price = '". $request['new_price'] ."'";
        }

        if($request['new_regular_price']) {
            $implode[] = "regular_price = '". $request['new_regular_price'] ."'";
        }

        if($request['new_status'] !== null) {
            $implode[] = "status = '". $request['new_status'] ."'";
        }

        if(!empty($request['new_sort_order'])) {
            $implode[] = "sort_order = '". (int) $request['new_sort_order'] ."'";
        }

        $sql .= implode(", ", $implode) . " WHERE product_id = '" . $product_id . "'";
        $this->db->query($sql);
        $sql = "INSERT INTO ". DB_PREFIX ."product_update_history SET product_id = '" . $product_info['product_id'] . "', operator_id = '" . $request['operator_id'] . "', stock_status_id = '" . $product_info['stock_status_id'] . "', status = '" . $product_info['status'] . "', price = '" . $product_info['price'];
        if($request['new_status']) {
            $sql .= "', new_status = '" . $request['new_status'];
        }
        if($request['new_stock_status_id']) {
            $sql .= "', new_stock_status_id = '" . $request['new_stock_status_id'];
        }
        if($request['new_price']) {
            $sql .= "', new_price = '" . $request['new_price'];
        }

        if($request['new_regular_price']) {
            $sql .= "', new_regular_price = '" . $request['new_regular_price'];
        }

        $sql .= "', date_added = now()";
        $this->db->query($sql);
    }

    public function addProductUpdateRequest($operator_id, $product_id, $new_status, $new_stock_status, $new_price, $new_regular_price, $new_sort_order) {
        $this->load->model("operator/operator");
        $this->load->model('catalog/product');
        $stock_status_index = array();
        $results = $this->model_catalog_product->getProductStockStatuses();
        foreach ($results as $result) {
            $stock_status_index[$result['stock_status_id']] = $result['name'];
        }

        $product_info = $this->model_catalog_product->getProduct($product_id);
        if(!$product_info) { return; }

        $operator = $this->model_operator_operator->getOperator($operator_id);
        if($operator['safe']) {
            $this->approveRequest(array(
                'operator_id' => $operator_id,
                'product_id' => $product_id,
                'new_status' => $new_status,
                'new_stock_status_id' => $new_stock_status ? $new_stock_status['stock_status_id'] : null,
                'new_price' => $new_price,
                'new_regular_price' => $new_regular_price,
                'new_sort_order' => $new_sort_order
            ), $product_info, $stock_status_index);
        } else {
            $sql = "INSERT INTO " . DB_PREFIX . "product_update_request SET " . "operator_id = '" . (int) $operator_id . "', product_id = '" . (int)$product_id;
            if($new_status !== null) {
                $sql .=  "', new_status = '" . (int) $new_status;
            }
            if($new_stock_status) {
                $sql .=  "', new_stock_status_id = '" . $new_stock_status['stock_status_id'];
            }
            if($new_price) {
                $sql .= "', new_price = '" . (float) $new_price;
            }
            if($new_regular_price) {
                $sql .= "', new_regular_price = '" . (float) $new_regular_price;
            }
            if($new_sort_order) {
                $sql .= "', new_sort_order = '" . (int) $new_sort_order;
            }
            $sql .= "', date_added = NOW()";
            $this->db->query($sql);
        }

        // Mail
        $this->load->language('mail/product_update_request');

        $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'), html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8'));

        $message  = $operator['safe'] ? $this->language->get('text_changed') . "\n" : $this->language->get('text_waiting') . "\n";
        $message .= sprintf($this->language->get('text_product'), html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')) . "\n";
        $message .= sprintf($this->language->get('text_operator'), html_entity_decode($operator['name'], ENT_QUOTES, 'UTF-8')) . "\n";

        if($product_info['status']) {
            $status = (int)$product_info['quantity'] > 0 ? "In Stock" : $product_info['stock_status'];
        } else {
            $status = "Disabled";
        }


        $message .= sprintf($this->language->get('text_price'), $product_info['price']) . "\n";
        $message .= sprintf($this->language->get('text_status'), $status) . "\n";
        $message .= sprintf($this->language->get('text_sort_order'), $product_info['sort_order']) . "\n";

        $message .= sprintf($this->language->get('text_new_price'), ($new_price ?: "")) . "\n";
        $message .= sprintf($this->language->get('text_new_regular_price'), ($new_regular_price ?: "")) . "\n";
        $message .= sprintf($this->language->get('text_new_status'), ($new_status != null ? "Delete" : ($new_stock_status ? $new_stock_status['name'] : ""))) . "\n";
        $message .= sprintf($this->language->get('text_new_sort_order'), ($new_sort_order ?: "")) . "\n";

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
//        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo("startech.com.bd@gmail.com");
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject($subject);
        $mail->setText($message);
        $mail->send();

        // Send to additional alert emails
        $emails = explode(',', $this->config->get('config_mail_alert'));

        foreach ($emails as $email) {
            if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
                $mail->setTo($email);
                $mail->send();
            }
        }
    }
}
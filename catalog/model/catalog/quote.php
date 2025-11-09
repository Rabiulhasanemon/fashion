<?php
class ModelCatalogQuote extends Model {
    protected $customer;
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->customer= new Customer($this->registry);
    }

    public function save($data) {
        
        $this->db->query("INSERT INTO `" . DB_PREFIX . "quote` SET customer_id = '" . (int) $data['customer_id']
            . "', customer_group_id = '" . (int) $data['customer_group_id']
            . "', operator_id = '" . (int) $data['operator_id']
            . "', firstname = '" . $this->db->escape($data['firstname'])
            . "', lastname = '" . $this->db->escape($data['lastname'])
            . "', email = '" . $this->db->escape($data['email'])
            . "', telephone = '" . $this->db->escape($data['telephone'])
            . "', fax = '" . $this->db->escape($data['fax'])
            . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR'])
            . "', date_added = NOW(), date_modified = NOW()");

        $quote_id = $this->db->getLastId();

        if (isset($data['products'])) {
            foreach ($data['products'] as $product) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "quote_product SET quote_id = '" . (int)$quote_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', tax = '" . (float)$product['tax'] . "'");
            }
        }
        return $quote_id;
    }

    public function getQuote($quote_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote` o WHERE o.quote_id = '" . (int) $quote_id . "'");

        return $query->row;
    }

    public function getQuotes($data, $start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        $sql = "SELECT *, (select CONCAT(o.firstname, ' ', o.lastname) FROM " . DB_PREFIX. "operator o WHERE o.operator_id = q.operator_id) as operator_name FROM `" . DB_PREFIX . "quote` q";
        if($data['filter_status'] && $data['filter_status'] != 'all') {
            $sql .= " WHERE q.status ='" . $data['filter_status'] . "'";
        }
        $sql .=  " ORDER BY q.quote_id DESC LIMIT " . (int)$start . "," . (int)$limit;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalQuotes($data) {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quote` o";
        if($data['filter_status']  && $data['filter_status'] != 'all') {
            $sql .= " WHERE o.status ='" . $data['filter_status'] . "'";
        }
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getQuoteProduct($quote_id, $quote_product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_product WHERE quote_id = '" . (int)$quote_id . "' AND quote_product_id = '" . (int)$quote_product_id . "'");

        return $query->row;
    }

    public function getQuoteProducts($quote_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_product WHERE quote_id = '" . (int)$quote_id . "'");

        return $query->rows;
    }

    public function getQuoteTotals($quote_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_total WHERE quote_id = '" . (int)$quote_id . "' ORDER BY sort_quote");

        return $query->rows;
    }

    public function getTotalQuoteProductsByQuoteId($quote_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "quote_product WHERE quote_id = '" . (int)$quote_id . "'");

        return $query->row['total'];
    }

    public function getQuoteHistories($quote_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_history WHERE quote_id = '" . (int)$quote_id . "' ORDER BY date_added DESC");
        return $query->rows;
    }

    public function requote($data) {
        $quote_products = $this->getQuoteProducts($data['quote_id']);
        $quote_prices = $data['quote_prices'];
        foreach ($quote_products as $product) {
            if (isset($quote_prices[$product['quote_product_id']])) {
                $quote_price = (double) $quote_prices[$product['quote_product_id']];
            } else {
                $quote_price = 0;
            }
            if($quote_price > 0) {
                $this->db->query("UPDATE " . DB_PREFIX . "quote_product set quote_price = ' " . $quote_price . "' WHERE quote_product_id = '" . (int)$product['quote_product_id'] . "'");
            }
        }
        $this->db->query("UPDATE " . DB_PREFIX . "quote SET status = 'sent', date_modified = now(), operator_id = '" . (int) $data['operator_id']. "',comment = '" . $this->db->escape($data['comment']) . "' WHERE quote_id = '" . (int)$data['quote_id'] . "'");
        $this->db->query("INSERT INTO ". DB_PREFIX. "quote_history set quote_id = ' " . (int)$data['quote_id'] . "', comment = '" . $this->db->escape("A Quotation has been sent by " . $data['operator_name']) . "', date_added = now()");
    }

    public function changeStatus($data) {
        $this->db->query("UPDATE " . DB_PREFIX . "quote SET status = '" . $data['status'] . "', date_modified = now(), operator_id = '" . (int) $data['operator_id'] . "' WHERE quote_id = '" . (int)$data['quote_id'] . "'");
    }

    public function mail($quote_id) {
        $quote_info = $this->getQuote($quote_id);

        // Load the language for any mails that might be required to be sent out
        $language = new Language();
        $language->load('english');
        $language->load('mail/quote');

        if($quote_info['telephone']) {
            $sms_text = sprintf($language->get('text_quote_create_sms'), $quote_info['quote_id'], $this->config->get("config_telephone"));
            $this->sms->send($quote_info['telephone'], $sms_text);
        }
        

        $subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $quote_id);

        // HTML Mail
        $data = array();

        $data['title'] = sprintf($language->get('text_subject'), $this->config->get('config_name'), $quote_id);

        $data['text_greeting'] = sprintf($language->get('text_greeting'), $this->config->get('config_name'));
        $data['text_quote_detail'] = $language->get('text_quote_detail');
        $data['text_customer_details'] = $language->get('text_customer_details');
        $data['text_store_details'] = $language->get('text_store_details');
        $data['text_quote_id'] = $language->get('text_quote_id');
        $data['text_date_modified'] = $language->get('text_date_modified');
        $data['text_email'] = $language->get('text_email');
        $data['text_telephone'] = $language->get('text_telephone');
        $data['text_ip'] = $language->get('text_ip');
        $data['text_product'] = $language->get('text_product');
        $data['text_model'] = $language->get('text_model');
        $data['text_discount'] = $language->get('text_discount');
        $data['text_quantity'] = $language->get('text_quantity');
        $data['text_price'] = $language->get('text_price');
        $data['text_total'] = $language->get('text_total');
        $data['text_subtotal'] = $language->get('text_subtotal');
        $data['text_website'] = $language->get('text_website');
        $data['text_instruction'] = $language->get('text_instruction');
        $data['text_footer'] = sprintf($language->get('text_footer'), $this->config->get("config_telephone"));

        $data['logo'] = $this->config->get('config_ssl') . '/image/' . $this->config->get('config_logo');
        $data['store_name'] = $this->config->get('config_name');
        $data['store_url'] = $this->config->get('config_url');
        $data['store_address'] = nl2br($this->config->get("config_address"));
        $data['store_telephone'] = $this->config->get("config_telephone");
        $data['store_email'] = $this->config->get("config_email");
        $data['customer_id'] = $quote_info['customer_id'];

        $data['quote_id'] = $quote_id;
        $data['date_modified'] = date($language->get('date_format_short'), strtotime($quote_info['date_modified']));
        $data['email'] = $quote_info['email'];
        $data['telephone'] = $quote_info['telephone'];
        $data['ip'] = $quote_info['ip'];
        $data['comment'] = $quote_info['comment'];

        // Products
        $data['products'] = array();

        $quote_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_product WHERE quote_id = '" . (int)$quote_id . "'");
        $subtotal = 0;
        $total_discount = 0;
        $total = 0;
        foreach ($quote_product_query->rows as $product) {
            if($product['quote_price'] && $product['quote_price'] > 0) {
                $product_total = $product['quote_price'] * $product['quantity'];
                $discount = $product['price'] - $product['quote_price'];
            } else {
                $product_total = $product['price'] * $product['quantity'];
                $discount = 0;

            }
            if($discount < 0) {
                $discount = 0;
            }
            $data['products'][] = array(
                'name'     => $product['name'],
                'model'    => $product['model'],
                'quantity' => $product['quantity'],
                'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0)),
                'discount' =>  $this->currency->format($discount),
                'total'    => $this->currency->format($product_total + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0))
            );
            $subtotal += $product['price'] * $product['quantity'];
            $total_discount += $discount;
            $total += $product_total;
        }

        $data['total'] = $this->currency->format($total);
        $data['total_discount'] = $this->currency->format($total_discount);
        $data['subtotal'] = $this->currency->format($subtotal);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/quote.tpl')) {
            $html = $this->load->view($this->config->get('config_template') . '/template/mail/quote.tpl', $data);
        } else {
            $html = $this->load->view('default/template/mail/quote.tpl', $data);
        }

        // Text Mail
        $text  = sprintf($language->get('text_greeting'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
        $text .= $language->get('text_quote_id') . ' ' . $quote_id . "\n";
        $text .= $language->get('text_date_modified') . ' ' . date($language->get('date_format_short'), strtotime($quote_info['date_modified'])) . "\n";


        // Products
        $text .= $language->get('text_products') . "\n";

        foreach ($quote_product_query->rows as $product) {
            if($product['quote_price'] && $product['quote_price'] > 0) {
                $product_total = $product['quote_price'] * $product['quantity'];
                $discount = $product['price'] - $product['quote_price'];
            } else {
                $product_total = $product['price'] * $product['quantity'];
                $discount = 0;

            }
            if($discount < 0) {
                $discount = 0;
            }
            $text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product_total + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0)), ENT_NOQUOTES, 'UTF-8') . "\n";

        }


        $text .= "\n";

        $text .= $language->get('text_quote_total') . "\n";

        $text .=   $language->get('text_subtotal'). ': ' . html_entity_decode($this->currency->format($subtotal), ENT_NOQUOTES, 'UTF-8') . "\n";
        $text .=   $language->get('text_discount'). ': ' . html_entity_decode($this->currency->format($total_discount), ENT_NOQUOTES, 'UTF-8') . "\n";
        $text .=   $language->get('text_total'). ': ' . html_entity_decode($this->currency->format($total), ENT_NOQUOTES, 'UTF-8') . "\n";

        $text .= "\n";

        $text .= sprintf($language->get('text_footer'), $this->config->get("config_telephone")) . "\n\n";

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($quote_info['email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml($html);
        $mail->setText($text);
        $mail->send();
    }
}
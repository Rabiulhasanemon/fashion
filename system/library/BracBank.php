<?php
class BracBank {

    public function __construct($registry) {
        $this->url = $registry->get("url");
    }

    public function encryptSaleTxn($data) {
        $IPG_client_server_ip = "127.0.0.1";
        $IPG_client_port = "10000";

        $error_no = "";
        $error_str = "";
        $socket_timeout = 2;
        $error_message = "";
        $invoice_sent_error = false;

        $encryption_error = false;
        $encrypted_invoice = "";

        $Invoice = "<req>".
            "<mer_id>" . $data['merchant_id'] . "</mer_id>".
            "<mer_txn_id>" . $data['payment_id'] . "</mer_txn_id>".
            "<action>SaleTxn</action>".
            "<txn_amt>" . $data['amount'] . "</txn_amt>".
            "<cur>BDT</cur>" .
            "<lang>en</lang>" .
            "<ret_url>" . $data['return_url'] . "</ret_url>" .
            "<mer_var1>" . $data['order_id'] . "</mer_var1>" .
            "</req>";


        $IPG_socket = fsockopen($IPG_client_server_ip, $IPG_client_port, $error_no, $error_str, $socket_timeout);
        if($IPG_socket) {
            socket_set_timeout($IPG_socket, $socket_timeout);
        }
        if(!$IPG_socket || fwrite($IPG_socket, $Invoice) === false) {
            $error_message = "Invoice could not be written to socket connection";
            $invoice_sent_error = true;
        }


        if(!$invoice_sent_error) {
            while (!feof($IPG_socket)) {
                $encrypted_invoice .= fread($IPG_socket, 8192);
            }
        }
        if($IPG_socket) {
            fclose($IPG_socket);
        }

        if (!(strpos($encrypted_invoice, '<error_code>') === false && strpos($encrypted_invoice, '</error_code>') === false && strpos($encrypted_invoice, '<error_msg>') === false && strpos($encrypted_invoice, '</error_msg>') === false)) {
            $encryption_error = true;
            $error_code = substr($encrypted_invoice, (strpos($encrypted_invoice, '<error_code>')+12), (strpos($encrypted_invoice, '</error_code>') - (strpos($encrypted_invoice, '<error_code>')+12)));
            $error_message = $error_code ." - " . substr($encrypted_invoice, (strpos($encrypted_invoice, '<error_msg>')+11), (strpos($encrypted_invoice, '</error_msg>') - (strpos($encrypted_invoice, '<error_msg>')+11)));
        }

        if($encryption_error || $invoice_sent_error) {
            return array("error" => $error_message);
        }
        return array('invoice' => $encrypted_invoice);
    }


    public function decrypt($EncryptedReceipt) {
        $IPGClientIP = "127.0.0.1";
        $IPGClientPort = "10000";

        $error_no = "";
        $error_str = "";
        $SOCKET_TIMEOUT = 2;

        $DecryptedReceipt = "";

        $encrypted_rcpt_sent_error = "";


        $IPGSocket = fsockopen($IPGClientIP, $IPGClientPort, $error_no, $error_str, $SOCKET_TIMEOUT);
        if($IPGSocket) {
            socket_set_timeout($IPGSocket, $SOCKET_TIMEOUT);
        }

        if(!$IPGSocket || fwrite($IPGSocket,$EncryptedReceipt) === false) {
            $encrypted_rcpt_sent_error = true;
        }

        if(!$encrypted_rcpt_sent_error) {
            while (!feof($IPGSocket)) {
                $DecryptedReceipt .= fread($IPGSocket, 8192);
            }
        }

        if(!$IPGSocket) {
            fclose($IPGSocket);
        }


        if (!(strpos($DecryptedReceipt, '<error_code>') === false && strpos($DecryptedReceipt, '</error_code>') === false && strpos($DecryptedReceipt, '<error_msg>') === false && strpos($DecryptedReceipt, '</error_msg>') === false)) {
            return null;
        }

        $data = array();
        if (!(strpos($DecryptedReceipt, '<acc_no>') === false && strpos($DecryptedReceipt, '</acc_no>') === false)) {
            $data["acc_no"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<acc_no>')+8), (strpos($DecryptedReceipt, '</acc_no>') - (strpos($DecryptedReceipt, '<acc_no>')+8)));
        }

        if (!(strpos($DecryptedReceipt, '<action>') === false && strpos($DecryptedReceipt, '</action>') === false)) {
            $data["action"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<action>')+8), (strpos($DecryptedReceipt, '</action>')-(strpos($DecryptedReceipt, '<action>')+8)));
        }

        if (!(strpos($DecryptedReceipt, '<bank_ref_id>') === false && strpos($DecryptedReceipt, '</bank_ref_id>') === false)) {
            $data["bank_ref_id"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<bank_ref_id>')+13), (strpos($DecryptedReceipt, '</bank_ref_id>')-(strpos($DecryptedReceipt, '<bank_ref_id>')+13)));
        }

        if (!(strpos($DecryptedReceipt, '<cur>') === false && strpos($DecryptedReceipt, '</cur>') === false)) {
            $data["currency"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<cur>')+5),(strpos($DecryptedReceipt, '</cur>')-(strpos($DecryptedReceipt, '<cur>')+5)) );
        }

        if (!(strpos($DecryptedReceipt, '<ipg_txn_id>') === false && strpos($DecryptedReceipt, '</ipg_txn_id>') === false)) {
            $data["ipg_txn_id"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<ipg_txn_id>')+12),(strpos($DecryptedReceipt, '</ipg_txn_id>')-(strpos($DecryptedReceipt, '<ipg_txn_id>')+12)) );
        }

        if (!(strpos($DecryptedReceipt, '<lang>') === false && strpos($DecryptedReceipt, '</lang>') === false)) {
            $data["lang"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<lang>')+6),(strpos($DecryptedReceipt, '</lang>')-(strpos($DecryptedReceipt, '<lang>')+6)) );
        }

        if (!(strpos($DecryptedReceipt, '<mer_txn_id>') === false && strpos($DecryptedReceipt, '</mer_txn_id>') === false)) {
            $data["mer_txn_id"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<mer_txn_id>')+12),(strpos($DecryptedReceipt, '</mer_txn_id>')-(strpos($DecryptedReceipt, '<mer_txn_id>')+12)) );
        }

        if (!(strpos($DecryptedReceipt, '<mer_var1>') === false && strpos($DecryptedReceipt, '</mer_var1>') === false)) {
            $data["mer_var1"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<mer_var1>')+10),(strpos($DecryptedReceipt, '</mer_var1>')-(strpos($DecryptedReceipt, '<mer_var1>')+10)) );
        }

        if (!(strpos($DecryptedReceipt, '<mer_var2>') === false && strpos($DecryptedReceipt, '</mer_var2>') === false)) {
            $data["mer_var2"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<mer_var2>')+10),(strpos($DecryptedReceipt, '</mer_var2>')-(strpos($DecryptedReceipt, '<mer_var2>')+10)) );
        }

        if (!(strpos($DecryptedReceipt, '<mer_var3>') === false && strpos($DecryptedReceipt, '</mer_var3>') === false)) {
            $data["mer_var3"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<mer_var3>')+10),(strpos($DecryptedReceipt, '</mer_var3>')-(strpos($DecryptedReceipt, '<mer_var3>')+10)) );
        }

        if (!(strpos($DecryptedReceipt, '<mer_var4>') === false && strpos($DecryptedReceipt, '</mer_var4>') === false)) {
            $data["mer_var42"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<mer_var4>')+10),(strpos($DecryptedReceipt, '</mer_var4>')-(strpos($DecryptedReceipt, '<mer_var4>')+10)) );
        }

        if (!(strpos($DecryptedReceipt, '<name>') === false && strpos($DecryptedReceipt, '</name>') === false)) {
            $data["name"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<name>')+6),(strpos($DecryptedReceipt, '</name>')-(strpos($DecryptedReceipt, '<name>')+6)) );
        }

        if (!(strpos($DecryptedReceipt, '<reason>') === false && strpos($DecryptedReceipt, '</reason>') === false)) {
            $data["reason"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<reason>')+8),(strpos($DecryptedReceipt, '</reason>')-(strpos($DecryptedReceipt, '<reason>')+8)) );
        }

        if (!(strpos($DecryptedReceipt, '<txn_amt>') === false && strpos($DecryptedReceipt, '</txn_amt>') === false)) {
            $data["txn_amt"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<txn_amt>')+9),(strpos($DecryptedReceipt, '</txn_amt>')-(strpos($DecryptedReceipt, '<txn_amt>')+9)) );
        }

        if (!(strpos($DecryptedReceipt, '<txn_status>') === false && strpos($DecryptedReceipt, '</txn_status>') === false)) {
            $data["txn_status"] = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<txn_status>')+12),(strpos($DecryptedReceipt, '</txn_status>')-(strpos($DecryptedReceipt, '<txn_status>')+12)) );
        }
        return $data;

    }
}
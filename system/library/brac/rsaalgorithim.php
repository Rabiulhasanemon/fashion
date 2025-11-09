<?php
namespace Brac;
class RSAAlgorithim {
	
	function decryptWithPublicKey($data, $publicKey){
		$data = base64_decode ( $data );
		$decData = '';
		$publicKey = "-----BEGIN PUBLIC KEY----- \n". $publicKey . "\n-----END PUBLIC KEY-----";
		//echo '<pre>'.$publicKey.'</pre>';
		$decr_result = openssl_public_decrypt($data, $decData, $publicKey, OPENSSL_PKCS1_PADDING);
		//$decr_result = openssl_pkey_get_private($data, $decData, $publicKey, OPENSSL_PKCS1_PADDING);
		
		if (!$decr_result) {
			$arr = array('response' => 'failure', 'message' => $decr_result);
			echo json_encode($arr);
			die();
		}
		return $decData;
	}	
}
?>
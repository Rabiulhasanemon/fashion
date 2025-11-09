<?php
namespace Brac;
class KeyStore {
	
	function parseKeyStore($keyStorePath){
		$myfile = fopen($keyStorePath."keystore.pooh","r") or die("Unable to open file!");
		$decData=$this->xor_this(fread($myfile,filesize($keyStorePath."keystore.pooh")));
		fclose($myfile);
		return $decData;
	}

	function xor_this($text) {
		$key = 'frtkj';
		$i = 0;
		$encrypted = '';
		foreach (str_split($text) as $char) {
			$encrypted .= chr(ord($char) ^ ord($key[$i++ % strlen($key)]));
		}
		return $encrypted;
	}
}
?>
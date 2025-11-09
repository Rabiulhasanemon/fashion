<?php
class iPayPipe {
	protected $id = "";
	protected $action = "";
	protected $transId = "";
	protected $amt = "";
	protected $responseURL = "";
	protected $trackId = "";
	protected $udf1 = "";
	protected $udf2 = "";
	protected $udf3 = "";
	protected $udf4 = "";
	protected $udf5 = "";
	protected $udf6 = "";
	protected $udf7 = "";
	protected $udf8 = "";
	protected $udf9 = "";
	protected $udf10 = "";
	protected $udf11 = "";
	protected $udf12 = "";
	protected $udf13 = "";
	protected $udf14 = "";
	protected $udf15 = "";
	protected $paymentPage = "";
	protected $paymentId = "";
	protected $result = "";
	protected $auth = "";
	protected $ref = "";
	protected $avr = "";
	protected $date = "";
	protected $currency = "";
	protected $errorURL = "";
	protected $language = "";
	protected $error = "";
	protected $error_text = "";
	protected $rawResponse = "";
	protected $alias = "";
	protected $debugMsg = "";
	protected $responseCode = "";
	protected $zip = "";
	protected $addr = "";
	protected $member = "";
	protected $cvv2 = "";
	protected $cvv2Verification = "";
	protected $type = "";
	protected $card = "";
	protected $expDay = "";
	protected $expMonth = "";
	protected $expYear = "";
	protected $eci = "";
	protected $cavv = "";
	protected $xid = "";
	protected $resourcePath = "";
	protected $acsurl = "";
	protected $pareq = "";
	protected $pares = "";
	protected $error_service_tag = "";
	protected $keystorePath = "";
	protected $seperator = "\\";
	protected $sep = "/";
	protected $webAddress = "";
	protected $key = "";
	protected $initializationVector = "";
	protected $ivrFlag = "";
	protected $npc356chphoneidformat = "";
	protected $npc356chphoneid = "";
	protected $npc356shopchannel = "";
	protected $npc356availauthchannel = "";
	protected $npc356pareqchannel = "";
	protected $npc356itpcredential = "";
	protected $authDataName = "";
	protected $authDatastrlen = "";
	protected $authDataType = "";
	protected $authDataLabel = "";
	protected $authDataPrompt = "";
	protected $authDataEncryptKey = "";
	protected $authDataEncryptType = "";
	protected $authDataEncryptMandatory = "";
	protected $ivrPasswordStatus = "";
	protected $ivrPassword = "";
	protected $itpauthtran = "";
	protected $itpauthiden = "";
	protected $url = "";
	protected $savedcard = "";
	protected $paymentdebitId = "";
	protected $paymentUrl = "";
	protected $publicKey ="";
	protected $custId = "";
	protected $siflag = "";

	protected $cardNumber = "";
	protected $authRespCode = "";

	protected $log;

	public function __construct() {
		$this->log = new Log("iPayPipe.log");
	}

	function getAuthRespCode() {
		return $this->authRespCode;
	}

	function getCardNumber() {
		return $this->cardNumber;
	}

	function getSiflag() {
		return $this->siflag;
	}

	function getid() {
		return $this->id;
	}
	function getaction() {
		return $this->action;
	}
	function gettransId() {
		return $this->transId;
	}
	function getamt() {
		return $this->amt;
	}
	function getresponseURL() {
		return $this->responseURL;
	}
	function gettrackId() {
		return $this->trackId;
	}
	function getudf1() {
		return $this->udf1;
	}
	function getudf2() {
		return $this->udf2;
	}
	function getudf3() {
		return $this->udf3;
	}
	function getudf4() {
		return $this->udf4;
	}
	function getudf5() {
		return $this->udf5;
	}
	function getudf6() {
		return $this->udf6;
	}
	function getudf7() {
		return $this->udf7;
	}
	function getudf8() {
		return $this->udf8;
	}
	function getudf9() {
		return $this->udf9;
	}
	function getudf10() {
		return $this->udf10;
	}
	function getudf11() {
		return $this->udf11;
	}
	function getudf12() {
		return $this->udf12;
	}
	function getudf13() {
		return $this->udf13;
	}
	function getudf14() {
		return $this->udf14;
	}
	function getudf15() {
		return $this->udf15;
	}

	function getpaymentPage() {
		return $this->paymentPage;
	}
	function getpaymentId() {
		return $this->paymentId;
	}
	function getresult() {
		return $this->result;
	}
	function getauth() {
		return $this->auth;
	}
	function getref() {
		return $this->ref;
	}
	function getavr() {
		return $this->avr;
	}
	function getDate() {
		return $this->date;
	}
	function getcurrency() {
		return $this->currency;
	}
	function geterrorURL() {
		return $this->errorURL;
	}
	function getlanguage() {
		return $this->language;
	}
	function geterror() {
		return $this->error;
	}
	function geterror_text() {
		return $this->error_text;
	}
	function getrawResponse() {
		return $this->rawResponse;
	}
	function getalias() {
		return $this->alias;
	}
	function getDebugMsg() {
		return $this->debugMsg;
	}
	function getresponseCode() {
		return $this->responseCode;
	}
	function getzip() {
		return $this->zip;
	}
	function getaddr() {
		return $this->addr;
	}
	function getmember() {
		return $this->member;
	}
	function getcvv2() {
		return $this->cvv2;
	}
	function getcvv2Verification() {
		return $this->cvv2Verification;
	}
	function getType() {
		return $this->type;
	}
	function getcard() {
		return $this->card;
	}
	function getexpDay() {
		return $this->expDay;
	}
	function getexpMonth() {
		return $this->expMonth;
	}
	function getexpYear() {
		return $this->expYear;
	}
	function geteci() {
		return $this->eci;
	}
	function getcavv() {
		return $this->cavv;
	}
	function getxid() {
		return $this->xid;
	}
	function getresourcePath() {
		return $this->resourcePath;
	}
	function getacsurl() {
		return $this->acsurl;
	}
	function getpareq() {
		return $this->pareq;
	}
	function getpares() {
		return $this->pares;
	}
	function geterror_service_tag() {
		return $this->error_service_tag;
	}
	function getkeystorePath() {
		return $this->keystorePath;
	}
	function getseperator() {
		return $this->seperator;
	}
	function getsep() {
		return $this->sep;
	}
	function getwebAddress() {
		return $this->webAddress;
	}
	function getkey() {
		return $this->key;
	}
	function getinitializationVector() {
		return $this->initializationVector;
	}
	function getivrFlag() {
		return $this->ivrFlag;
	}
	function getnpc356chphoneidformat() {
		return $this->npc356availauthchannel;
	}
	function getnpc356chphoneid() {
		return $this->npc356chphoneid;
	}
	function getnpc356shopchannel() {
		return $this->npc356shopchannel;
	}
	function getnpc356availauthchannel() {
		return $this->npc356availauthchannel;
	}
	function getnpc356pareqchannel() {
		return $this->npc356itpcredential;
	}
	function getnpc356itpcredential() {
		return $this->npc356itpcredential;
	}
	function getauthDataName() {
		return $this->authDataName;
	}
	function getauthDatastrlen() {
		return $this->authDatastrlen;
	}
	function getauthDataType() {
		return $this->authDataType;
	}
	function getauthDataLabel() {
		return $this->authDataLabel;
	}
	function getauthDataPrompt() {
		return $this->authDataPrompt;
	}
	function getauthDataEncryptKey() {
		return $this->authDataEncryptKey;
	}
	function getauthDataEncryptType() {
		return $this->authDataEncryptType;
	}
	function getauthDataEncryptMandatory() {
		return $this->authDataEncryptMandatory;
	}
	function getivrPasswordStatus() {
		return $this->ivrPasswordStatus;
	}
	function getivrPassword() {
		return $this->ivrPassword;
	}
	function getitpauthtran() {
		return $this->itpauthtran;
	}
	function getitpauthiden() {
		return $this->itpauthiden;
	}
	function geturl() {
		return $this->url;
	}
	function getsavedcard() {
		return $this->savedcard;
	}
	function getpaymentdebitId() {
		return $this->paymentdebitId;
	}
	function getpaymentUrl() {
		return $this->paymentUrl;
	}
	function getpublicKey() {
		return $this->publicKey;
	}
	function getcustId() {
		return $this->custId;
	}
	//standing instruction

	function setSiflag($val) {
		$this->siflag = $val;
	}


	function setid($val) {
		$this->id = $val;
	}
	function setaction($val) {
		$this->action = $val;
	}
	function settransId($val) {
		$this->transId = $val;
	}
	function setamt($val) {
		$this->amt = $val;
	}
	function setresponseURL($val) {
		$this->responseURL = $val;
	}
	function settrackId($val) {
		$this->trackId = $val;
	}
	function setudf1($val) {
		$this->udf1 = $val;
	}
	function setudf2($val) {
		$this->udf2 = $val;
	}
	function setudf3($val) {
		$this->udf3 = $val;
	}
	function setudf4($val) {
		$this->udf4 = $val;
	}
	function setudf5($val) {
		$this->udf5 = $val;
	}
	function setudf6($val) {
		$this->udf6 = $val;
	}
	function setudf7($val) {
		$this->udf7 = $val;
	}
	function setudf8($val) {
		$this->udf8 = $val;
	}
	function setudf9($val) {
		$this->udf9 = $val;
	}
	function setudf10($val) {
		$this->udf10 = $val;
	}
	function setudf11($val) {
		$this->udf11 = $val;
	}
	function setudf12($val) {
		$this->udf12 = $val;
	}
	function setudf13($val) {
		$this->udf13 = $val;
	}
	function setudf14($val) {
		$this->udf14 = $val;
	}
	function setudf15($val) {
		$this->udf15 = $val;
	}
	function setpaymentPage($val) {
		$this->paymentPage = $val;
	}
	function setpaymentId($val) {
		$this->paymentId = $val;
	}
	function setresult($val) {
		$this->result = $val;
	}
	function setauth($val) {
		$this->auth = $val;
	}
	function setref($val) {
		$this->ref = $val;
	}
	function setavr($val) {
		$this->avr = $val;
	}
	function setDate($val) {
		$this->date = $val;
	}
	function setcurrency($val) {
		$this->currency = $val;
	}
	function seterrorURL($val) {
		$this->errorURL = $val;
	}
	function setlanguage($val) {
		$this->language = $val;
	}
	function seterror($val) {
		$this->error = $val;
	}
	function seterror_text($val) {
		$this->error_text = $val;
	}
	function setrawResponse($val) {
		$this->rawResponse = $val;
	}
	function setalias($val) {
		$this->alias = $val;
	}
	function setDebugMsg($val) {
		$this->debugMsg = $val;
	}
	function setresponseCode($val) {
		$this->responseCode = $val;
	}
	function setzip($val) {
		$this->zip = $val;
	}
	function setaddr($val) {
		$this->addr = $val;
	}
	function setmember($val) {
		$this->member = $val;
	}
	function setcvv2($val) {
		$this->cvv2 = $val;
	}
	function setcvv2Verification($val) {
		$this->cvv2Verification = $val;
	}
	function setType($val) {
		$this->type = $val;
	}
	function setcard($val) {
		$this->card = $val;
	}
	function setexpDay($val) {
		$this->expDay = $val;
	}
	function setexpMonth($val) {
		$this->expMonth = $val;
	}
	function setexpYear($val) {
		$this->expYear = $val;
	}
	function seteci($val) {
		$this->eci = $val;
	}
	function setcavv($val) {
		$this->cavv = $val;
	}
	function setxid($val) {
		$this->xid = $val;
	}
	function setresourcePath($val) {
		$this->resourcePath = $val;
	}
	function setacsurl($val) {
		$this->acsurl = $val;
	}
	function setpareq($val) {
		$this->pareq = $val;
	}
	function setpares($val) {
		$this->pares = $val;
	}
	function seterror_service_tag($val) {
		$this->error_service_tag = $val;
	}
	function setkeystorePath($val) {
		$this->keystorePath = $val;
	}
	function setseperator($val) {
		$this->seperator = $val;
	}
	function setsep($val) {
		$this->sep = $val;
	}
	function setwebAddress($val) {
		$this->webAddress = $val;
	}
	function setkey($val) {
		$this->key = $val;
	}
	function setinitializationVector($val) {
		$this->initializationVector = $val;
	}
	function setivrFlag($val) {
		$this->ivrFlag = $val;
	}
	function setnpc356chphoneidformat($val) {
		$this->npc356availauthchannel = $val;
	}
	function setnpc356chphoneid($val) {
		$this->npc356chphoneid = $val;
	}
	function setnpc356shopchannel($val) {
		$this->npc356shopchannel = $val;
	}
	function setnpc356availauthchannel($val) {
		$this->npc356availauthchannel = $val;
	}
	function setnpc356pareqchannel($val) {
		$this->npc356itpcredential = $val;
	}
	function setnpc356itpcredential($val) {
		$this->npc356itpcredential = $val;
	}
	function setauthDataName($val) {
		$this->authDataName = $val;
	}
	function setauthDatastrlen($val) {
		$this->authDatastrlen = $val;
	}
	function setauthDataType($val) {
		$this->authDataType = $val;
	}
	function setauthDataLabel($val) {
		$this->authDataLabel = $val;
	}
	function setauthDataPrompt($val) {
		$this->authDataPrompt = $val;
	}
	function setauthDataEncryptKey($val) {
		$this->authDataEncryptKey = $val;
	}
	function setauthDataEncryptType($val) {
		$this->authDataEncryptType = $val;
	}
	function setauthDataEncryptMandatory($val) {
		$this->authDataEncryptMandatory = $val;
	}
	function setivrPasswordStatus($val) {
		$this->ivrPasswordStatus = $val;
	}
	function setivrPassword($val) {
		$this->ivrPassword = $val;
	}
	function setitpauthtran($val) {
		$this->itpauthtran = $val;
	}
	function setitpauthiden($val) {
		$this->itpauthiden = $val;
	}
	function seturl($val) {
		$this->url = $val;
	}
	function setsavedcard($val) {
		$this->savedcard = $val;
	}
	function setpaymentdebitId($val) {
		$this->paymentdebitId = $val;
	}
	function setpaymentUrl($val) {
		$this->paymentUrl = $val;
	}
	function setpublicKey($val) {
		$this->publicKey = $val;
	}
	function setcustId($val) {
		$this->custId = $val;
	}

	function setAuthRespCode($val) {
		$this->authRespCode = $val;
	}

	function setCardNumber($val) {
		$this->cardNumber = $val;
	}

	function buildHostRequest() {
		$strRequest = "";
		try {
			if (strlen ( $this->amt ) > 0) {
				$strRequest .= "amt=" . $this->amt . "&";
			}
			if (strlen ( $this->action ) > 0) {
				$strRequest .= "action=" . $this->action . "&";
			}
			if (strlen ( $this->responseURL ) > 0) {
				$strRequest .= "responseURL=" . $this->responseURL . "&";
			}
			if (strlen ( $this->errorURL ) > 0) {
				$strRequest .= "errorURL=" . $this->errorURL . "&";
			}
			if (strlen ( $this->trackId ) > 0) {
				$strRequest .= "trackid=" . $this->trackId . "&";
			}
			if (strlen ( $this->udf1 ) > 0) {
				$strRequest .= "udf1=" . $this->udf1 . "&";
			}
			if (strlen ( $this->udf2 ) > 0) {
				$strRequest .= "udf2=" . $this->udf2 . "&";
			}
			if (strlen ( $this->udf3 ) > 0) {
				$strRequest .= "udf3=" . $this->udf3 . "&";
			}
			if (strlen ( $this->udf4 ) > 0) {
				$strRequest .= "udf4=" . $this->udf4 . "&";
			}
			if (strlen ( $this->udf5 ) > 0) {
				$strRequest .= "udf5=" . $this->udf5 . "&";
			}
			if (strlen ( $this->udf6 ) > 0) {
				$strRequest .= "udf6=" . $this->udf6 . "&";
			}
			if (strlen ( $this->udf7 ) > 0) {
				$strRequest .= "udf7=" . $this->udf7 . "&";
			}
			if (strlen ( $this->udf8 ) > 0) {
				$strRequest .= "udf8=" . $this->udf8 . "&";
			}
			if (strlen ( $this->udf9 ) > 0) {
				$strRequest .= "udf9=" . $this->udf9 . "&";
			}
			if (strlen ( $this->udf10 ) > 0) {
				$strRequest .= "udf10=" . $this->udf10 . "&";
			}
			if (strlen ( $this->udf11 ) > 0) {
				$strRequest .= "udf11=" . $this->udf11 . "&";
			}
			if (strlen ( $this->udf12 ) > 0) {
				$strRequest .= "udf12=" . $this->udf12 . "&";
			}
			if (strlen ( $this->udf13 ) > 0) {
				$strRequest .= "udf13=" . $this->udf13 . "&";
			}
			if (strlen ( $this->udf14 ) > 0) {
				$strRequest .= "udf14=" . $this->udf14 . "&";
			}
			if (strlen ( $this->udf15 ) > 0) {
				$strRequest .= "udf15=" . $this->udf15 . "&";
			}
			if (strlen ( $this->currency ) > 0) {
				$strRequest .= "currencycode=" . $this->currency . "&";
			}
			if ($this->language != null && strlen ( $this->language ) > 0) {
				$strRequest .= "langid=" . $this->language . "&";
			}
			return $strRequest;
		} catch ( Exception $e ) {
			return null;
		}
	}

	function buildXMLRequest() {
		$requestbuffer = "";
		try {
			$requestbuffer . "<request>";
			if ($this->card != null)
				if (strlen ( $this->card ) > 0) {
					$requestbuffer .= "<card>" . $this->card . "</card>";
				}
			if ($this->cvv2 != null)
				if (strlen ( $this->cvv2 ) > 0) {
					$requestbuffer .= "<cvv2>" . $this->cvv2 . "</cvv2>";
				}
			if ($this->currency != null)
				if (strlen( $this->currency ) > 0) {
					$requestbuffer .= "<currencycode>" . $this->currency . "</currencycode>";
				}
			if ($this->expYear != null)
				if (strlen( $this->expYear ) > 0) {
					$requestbuffer .= "<expyear>" . $this->expYear . "</expyear>";
				}
			if ($this->expMonth != null)
				if (strlen ( $this->expMonth ) > 0) {
					$requestbuffer .= "<expmonth>" . $this->expMonth . "</expmonth>";
				}
			if ($this->expDay != null)
				if (strlen ( $this->expDay ) > 0) {
					$requestbuffer .= "<expday>" . "01" . "</expday>";
				}
			if ($this->type != null)
				if (strlen ( $this->type ) > 0) {
					$requestbuffer .= "<type>" . $this->type . "</type>";
				}
			if ($this->transId != null)
				if (strlen ( $this->transId ) > 0) {
					$requestbuffer .= "<transid>" . $this->transId . "</transid>";
				}
			if ($this->zip != null)
				if (strlen ( $this->zip ) > 0) {
					$requestbuffer .= "<zip>" . $this->zip . "</zip>";
				}
			if ($this->addr != null)
				if (strlen ( $this->addr ) > 0) {
					$requestbuffer .= "<addr>" . $this->addr . "</addr>";
				}
			if ($this->member != null)
				if (strlen ( $this->member ) > 0) {
					$requestbuffer .= "<member>" . $this->member . "</member>";
				}
			if ($this->amt != null)
				if (strlen ( $this->amt ) > 0) {
					$requestbuffer .= "<amt>" . $this->amt . "</amt>";
				}
			if ($this->action != null)
				if (strlen ( $this->action ) > 0) {
					$requestbuffer .= "<action>" . $this->action . "</action>";
				}
			if ($this->trackId != null)
				if (strlen ( $this->trackId ) > 0) {
					$requestbuffer .= "<trackid>" . $this->trackId . "</trackid>";
				}
			if ($this->udf1 != null)
				if (strlen ( $this->udf1 ) > 0) {
					$requestbuffer .= "<udf1>" . $this->udf1 . "</udf1>";
				}
			if ($this->udf2 != null)
				if (strlen ( $this->udf2 ) > 0) {
					$requestbuffer .= "<udf2>" . $this->udf2 . "</udf2>";
				}
			if ($this->udf3 != null)
				if (strlen ( $this->udf3 ) > 0) {
					$requestbuffer .= "<udf3>" . $this->udf3 . "</udf3>";
				}
			if ($this->udf4 != null)
				if (strlen ( $this->udf4 ) > 0) {
					$requestbuffer .= "<udf4>" . $this->udf4 . "</udf4>";
				}
			if ($this->udf5 != null)
				if (strlen ( $this->udf5 ) > 0) {
					$requestbuffer .= "<udf5>" . $this->udf5 . "</udf5>";
				}
			if ($this->udf6 != null)
				if (strlen ( $this->udf6 ) > 0) {
					$requestbuffer .= "<udf6>" . $this->udf6 . "</udf6>";
				}
			if ($this->udf7 != null)
				if (strlen ( $this->udf7 ) > 0) {
					$requestbuffer .= "<udf7>" . $this->udf7 . "</udf7>";
				}
			if ($this->udf8 != null)
				if (strlen ( $this->udf8 ) > 0) {
					$requestbuffer .= "<udf8>" . $this->udf8 . "</udf8>";
				}
			if ($this->udf9 != null)
				if (strlen ( $this->udf9 ) > 0) {
					$requestbuffer .= "<udf9>" . $this->udf9 . "</udf9>";
				}
			if ($this->udf10 != null)
				if (strlen ( $this->udf10 ) > 0) {
					$requestbuffer .= "<udf10>" . $this->udf10 . "</udf10>";
				}
			if ($this->udf11 != null)
				if (strlen ( $this->udf11 ) > 0) {
					$requestbuffer .= "<udf11>" . $this->udf11 . "</udf11>";
				}
			if ($this->udf12 != null)
				if (strlen ( $this->udf12 ) > 0) {
					$requestbuffer .= "<udf12>" . $this->udf12 . "</udf12>";
				}
			if ($this->udf13 != null)
				if (strlen ( $this->udf13 ) > 0) {
					$requestbuffer .= "<udf13>" . $this->udf13 . "</udf13>";
				}
			if ($this->udf14 != null)
				if (strlen ( $this->udf14 ) > 0) {
					$requestbuffer .= "<udf14>" . $this->udf14 . "</udf14>";
				}
			if ($this->udf15 != null)
				if (strlen ( $this->udf15 ) > 0) {
					$requestbuffer .= "<udf15>" . $this->udf15 . "</udf15>";
				}

			if ($this->currency != null)
				if (strlen ( $this->currency ) > 0) {
					$requestbuffer .= "<currencycode>" . $this->currency . "</currencycode>";
				}
			if ($this->eci != null)
				if (strlen ( $this->eci ) > 0) {
					$requestbuffer .= "<eci>" . $this->eci . "</eci>";
				}
			if ($this->errorURL != null)
				if (strlen ( $this->errorURL ) > 0) {
					$requestbuffer .= "<errorURL>" . $this->errorURL . "</errorURL>";
				}
			if ($this->responseURL != null)
				if (strlen ( $this->responseURL ) > 0) {
					$requestbuffer .= "<responseURL>" . $this->responseURL . "</responseURL>";
				}
			if ($this->ivrFlag != null)
				if (strlen ( $this->ivrFlag ) > 0) {
					$requestbuffer .= "<ivrFlag>" . $this->ivrFlag . "</ivrFlag>";
				}
				//standing instruction
				if ($this->siflag != null)
				if (strlen ( $this->siflag ) > 0) {
					$requestbuffer .= "<siflag>" . $this->siflag . "</siflag>";
				}



			if ($this->npc356chphoneidformat != null)
				if (strlen ( $this->npc356chphoneidformat ) > 0) {
					$requestbuffer .= "<npc356chphoneidformat>" . $this->npc356chphoneidformat . "</npc356chphoneidformat>";
				}
			if ($this->npc356chphoneid != null)
				if (strlen ( $this->npc356chphoneid ) > 0) {
					$requestbuffer .= "<npc356chphoneid>" . $this->npc356chphoneid . "</npc356chphoneid>";
				}
			if ($this->npc356shopchannel != null)
				if (strlen ( $this->npc356shopchannel ) > 0) {
					$requestbuffer .= "<npc356shopchannel>" . $this->npc356shopchannel . "</npc356shopchannel>";
				}
			if ($this->npc356availauthchannel != null)
				if (strlen ( $this->npc356availauthchannel ) > 0) {
					$requestbuffer .= "<npc356availauthchannel>" . $this->npc356availauthchannel . "</npc356availauthchannel>";
				}
			if ($this->npc356pareqchannel != null)
				if (strlen ( $this->npc356pareqchannel ) > 0) {
					$requestbuffer .= "<npc356pareqchannel>" . $this->npc356pareqchannel . "</npc356pareqchannel>";
				}
			if ($this->npc356itpcredential != null)
				if (strlen ( $this->npc356itpcredential ) > 0) {
					$requestbuffer .= "<npc356itpcredential>" . $this->npc356itpcredential . "</npc356itpcredential>";
				}
			if ($this->ivrPasswordStatus != null && $this->ivrPasswordStatus . strlen () > 0)
				$requestbuffer .= "<ivrPasswordStatus>" . $this->ivrPasswordStatus . "</ivrPasswordStatus>";
			if ($this->ivrPassword != null && strlen ( $this->ivrPassword ) > 0) {
				$requestbuffer .= "<ivrPassword>" . $this->ivrPassword . "</ivrPassword>";
			}
			if ($this->savedcard != null) {
				$requestbuffer .= "<savedcard>" . $this->savedcard . "</savedcard>";
			}

			if ($this->custId != null)
				if (strlen ( $this->custId ) > 0) {
					$requestbuffer .= "<custid>" . $this->custId . "</custid>";
			}
			return $requestbuffer;
		} catch ( Exception $e ) {
			return null;
		}
	}

	function performPaymentInitializationHTTP() {
		$request = null;
		$requestbuffer = null;
		$xmlData = null;

		try {
			$keyParser = new Brac\KeyStore();
			$this->key = $keyParser->parseKeyStore ($this->keystorePath);
			$xmlData = $this->parseResource ( $this->key, $this->resourcePath, $this->alias );
			if ($xmlData != null) {
				$xmlData = $this->parseXMLRequest ( $xmlData );
			} else {
				$error = "Alias name does not exits";
			}

			$this->key = $xmlData ['resourceKey'];
			$this->setId ( $xmlData ['id'] );
			$requestbuffer = $this->buildHostRequest ();
			$requestbuffer .= "id=" . $xmlData ['id'] . "&password=" . $xmlData ['password'] . "&";
			$this->webAddress = $xmlData ['webaddress'];
			$this->log->write( $requestbuffer );
			if(isset($xmlData ['publicKey'])){
				$this->publicKey = $xmlData ['publicKey'];
				if($this->publicKey !== null && strlen($this->publicKey) > 0){
					$rsaAlg = new Brac\RSAAlgorithim();
					$this->key = $rsaAlg->decryptWithPublicKey($this->key, $this->publicKey);
					$cipheredText = $this->encryptData ( $requestbuffer, $this->key );
				}
			}else {
				$cipheredText = $this->encryptData ( $requestbuffer, $this->key );
			}

			$request .= "&trandata=" . $cipheredText;
			$request .= "&errorURL=" . $this->errorURL . "&responseURL=" . $this->responseURL . "&tranportalId=" . $xmlData ['id'];
			$this->webAddress .= "/PaymentHTTP.htm?param=paymentInit" . $request;
			echo "webaddr: ".$this->webAddress;
		} catch( Exception $e ) {
			$this->error = "Problem while encrypting request data";
			return - 1;
		}
		return 0;
	}

	function performTransactionHTTP() {
		$request = null;
		$requestbuffer = null;
		$xmlData = null;
		try {
			$requestbuffer = $this->buildXMLRequest ();

			//echo $this->$requestbuffer;
			//die();


			$keyParser = new Brac\KeyStore();
			$this->key = $keyParser->parseKeyStore ( $this->keystorePath );
			$xmlData = $this->parseResource ( $this->key, $this->resourcePath, $this->alias );

			if ($xmlData == null) {
				$this->error = "Alias name does not exits";
				return - 1;
			} else {
				$xmlData = $this->parseXMLRequest( $xmlData );
			}

			$requestbuffer .= "<id>". $xmlData ['id'] ."</id>";
			$requestbuffer .= "<password>". $xmlData ['password'] ."</password>";
			$requestbuffer . "</request>";
			if ($this->responseURL == null || strlen ( trim ( $this->responseURL ) ) <= 0) {
				$this->error = "Response URL is Invalid or NULL";
				return - 1;
			}
			//$this->log->write( $requestbuffer );
			//error_log($requestbuffer, 3, $log_file);
			$this->key = $xmlData ['resourceKey'];
			$cipheredText = $this->encryptData($requestbuffer, $this->key);
			$request .= "&trandata=" . $cipheredText;
			$request .= "&errorURL=" . $this->errorURL;
			$request .= "&responseURL=" . $this->responseURL;
			$request .= "&tranportalId=" . $xmlData ['id'];
			$this->webAddress = $xmlData ['webaddress'];
			//$this->webAddress = "http://10.44.73.173:8080/iPayFSSNETDEV";

			$this->setid ( $xmlData ['id'] . "" );
			$this->webAddress .= "/tranPipeHTTP.htm?param=tranInit" . $request;
			return 0;
		} catch ( Exception $e ) {
			$this->error = "Error :(";
			return - 1;
		}
	}

	function parseResource($key, $resourcePath, $alias) {
		$xmlData = null;
		$key = null;

		try {
			$parseResouce = new Brac\ParseResource();
			$parseResouce->setResourcePath ( $resourcePath );
			$parseResouce->setKey ( $this->key );
			$parseResouce->setAlias ( $alias );
			//echo $alias;
			$parseResouce->createCGZFromCGN ();
			$xmlData = $parseResouce->readZip ();
			return $xmlData;
		} catch ( Exception $e ) {
			return null;
		}
	}

	function parseEncryptedResultHttp($response) {
		$xmlData = null;

		try {

			$keyParser = new Brac\KeyStore();
			$this->key = $keyParser->parseKeyStore ( $this->keystorePath );
			$xmlData = $this->parseResource ( $this->key, $this->resourcePath, $this->alias );
			if ($xmlData != null) {
				$hm = $this->parseXMLRequest ( $xmlData );
			} else {
				$this->error = "Alias name does not exits";
			}
			$this->key = $hm ['resourceKey'];
			$cipheredText = $this->decryptData ( $response, $this->key );
			if ($cipheredText == null) {
				$this->error = "Invalid response";
				return - 1;
			}
			return parsetrandata ( $cipheredText );
		} catch ( Exception $e ) {
			$this->error = "Internal Error: " + $e->getMessage ();
			return - 1;
		}
	}

	function performVbVTransaction() {
		$request = null;
		$xmlData = null;
		$requestbuffer = null;
		$hm = null;
		try {

			$requestbuffer = $this->buildXMLRequest ();
			$keyParser = new Brac\KeyStore();
			$this->key = $keyParser->parseKeyStore($this->keystorePath );
			$xmlData = $this->parseResource ( $this->key, $this->resourcePath, $this->alias );
			if ($xmlData == null) {
				$this->error = "Alias name does not exits";
				return - 1;
			} else {
				$hm = $this->parseXMLRequest ( $xmlData );
			}
			$requestbuffer .="<id>" . $hm ['id'] . "</id>";
			$requestbuffer .="<password>" . $hm ['password'] . "</password>";
			$requestbuffer . "</request>";
			$requestbuffer ="<request>" . $requestbuffer . "</request>";
			//$requestbuffer =
			//$this->log->write( $requestbuffer );
			if ($this->responseURL == null || strlen ( trim ( $this->responseURL ) ) == 0) {
				return - 1;
			}
			$this->key = $hm ['resourceKey'];

			$cipheredText = $this->encryptData ( $requestbuffer, $this->key );

			$request = "&trandata=" . $cipheredText . "&errorURL=" . $this->errorURL . "&responseURL=" . $this->responseURL . "&tranportalId=" . $hm ['id'];

			$this->webAddress = $hm ['webaddress'];
			$this->webAddress .= "/VPAS.htm?actionVPAS=VbvVEReqProcessHTTP" . $request;
			return 0;
		} catch ( Exception $e ) {
			$this->error = "Error! " . $e->getMessage ();
			return - 1;
		}
	}

	function parseEncryptedRequest($trandata) {
		$result = 0;
		$xmlData = null;
		$hm = null;
		try {

			if ($trandata == null) {
				return 0;
			}
			$keyParser = new Brac\KeyStore();
			$this->key = $keyParser->parseKeyStore ( $this->keystorePath );
			$xmlData = $this->parseResource ( $this->key, $this->resourcePath, $this->alias );
			if ($xmlData != null) {
				$hm = $this->parseXMLRequest ( $xmlData );
			} else {
				$this->error = "Alias name does not exits";
			}
			$this->key = $hm ['resourceKey'];
			$trandata = $this->decryptData ( $trandata, $this->key );
			(new Log("brac.log"))->write($trandata);
			$result = $this->parsetrandata($trandata);
			return $result;
		} catch ( Exception $e ) {
			return - 1;
		}
	}

	function parseEncryptedResult($response) {
		$xmlData = null;
		$hm = null;
		$resultMap = null;
		try {
			$keyParser = new Brac\KeyStore();
			$this->key = $keyParser->parseKeyStore ( $this->keystorePath );
			$xmlData = $this->parseResource ( $this->key, $this->resourcePath, $this->alias );
			if ($xmlData != null) {
				$hm = $this->parseXMLRequest ( $xmlData );
			} else {
				$this->error = "Alias name does not exits";
			}
			$this->key = $hm ['resourceKey'];
			$response = $this->decryptData($response, $this->key);
			$resultMap = $this->parseResponse($response);
			return $this->setTransactionData($resultMap);
		} catch ( Exception $e ) {
			$this->error = "Internal Error: " + e . getMessage ();
			return - 1;
		}
	}
	function parseXMLRequest($request) {
		try {
			$responseMap = null;
			$request = trim ($request );
			$request = substr ( $request, strpos ( $request, "<id>" ), strlen ( $request ) - strpos ( $request, "<id>" ) );
			$request = str_replace ( "</terminal>", "", $request );
			$pos = strpos ( $request, "<" ) == 0;
			if ($request == null || (strlen ( $request ) < 0) || $pos === false) {
				return null;
			} else {
				try {
					$responseMap = $this->parseResponse ( $request );
				} catch ( Exception $ex ) {
					return null;
				}
			}
			return $responseMap;
		} catch ( Exception $e ) {
			return null;
		}
	}
	function parseResponse($response) {
		$begin = 0;
		$end = 0;
		$start = null;
		$value = null;
		$map=[];
		$maps=[];
		$response = trim ( $response );

		$pos = strpos ( $response, "<" ) == 0;
		if ($response == null || (strlen ( $response ) < 0) || $pos === false) {
			return null;
		} else {
			do {

				if ((strpos ( $response, '<' ) !== false) && (strpos ( $response, '>' ) !== false)) {
					$start = substr ( $response, ($ind = strpos ( $response, "<" )) + 1, ((strpos ( $response, ">" ) - 1) - $ind) );
					$mapKey = substr ( $response, ($ind = strpos ( $response, ">" )) + 1, ((strpos ( $response, "</" . $start . ">" ) - 1) - $ind) );
					$response = substr ( $response, $from = strpos ( $response, "</" . $start . ">" ) + strlen ( $start ) + 3, strrpos ( $response, ">" ) - $from + 1 );
					$maps [$start] = $mapKey;
				} else {
					break;
				}
			} while ( strlen ( $response ) > 0 );
		}
		return $maps;
	}

	function setTransactionData($resultMap) {
		if (isset ( $resultMap ['error'] )) {
			$this->error = trim ( $resultMap ['error'] );
		}
		if (isset ( $resultMap ['url'] ))
			$this->acsurl = trim ( $resultMap ['url'] );
		if (isset ( $resultMap ['PAReq'] ))
			$this->pareq = trim ( $resultMap ['PAReq'] );
		if (isset ( $resultMap ['paymentid'] ))
			$this->paymentId = trim ( $resultMap ['paymentid'] );
		if (isset ( $resultMap ['payid'] ))
			$this->paymentId = trim ( $resultMap ['payid'] );
		if (isset ( $resultMap ['eci'] ))
			$this->eci = trim ( $resultMap ['eci'] );
		if (isset ( $resultMap ['cardNumber'] ))
			$this->cardNumber = trim ( $resultMap ['cardNumber'] );
		if (isset ( $resultMap ['result'] ))
			$this->result = trim ( $resultMap ['result'] );
		if (isset ( $resultMap ['auth'] ))
			$this->auth = trim ( $resultMap ['auth'] );
		if (isset ( $resultMap ['ref'] ))
			$this->ref = trim ( $resultMap ['ref'] );
		if (isset ( $resultMap ['avr'] ))
			$this->avr = trim ( $resultMap ['avr'] );
		if (isset ( $resultMap ['postdate'] ))
			$this->date = trim ( $resultMap ['postdate'] );
		if (isset ( $resultMap ['tranid'] ))
			$this->transId = trim ( $resultMap ['tranid'] );
		if (isset ( $resultMap ['amt'] ))
			$this->amt = trim ( $resultMap ['amt'] );
		if (isset ( $resultMap ['trackid'] ))
			$this->trackId = trim ( $resultMap ['trackid'] );
		if (isset ( $resultMap ['trackId'] ))
			$this->trackId = trim ( $resultMap ['trackId'] );
		if (isset ( $resultMap ['udf1'] ))
			$this->udf1 = trim ( $resultMap ['udf1'] );
		if (isset ( $resultMap ['udf2'] ))
			$this->udf2 = trim ( $resultMap ['udf2'] );
		if (isset ( $resultMap ['udf3'] ))
			$this->udf3 = trim ( $resultMap ['udf3'] );
		if (isset ( $resultMap ['udf4'] ))
			$this->udf4 = trim ( $resultMap ['udf4'] );
		if (isset ( $resultMap ['udf5'] ))
			$this->udf5 = trim ( $resultMap ['udf5'] );
		if (isset ( $resultMap ['udf6'] ))
			$this->udf6 = trim ( $resultMap ['udf6'] );
		if (isset ( $resultMap ['udf7'] ))
			$this->udf7 = trim ( $resultMap ['udf7'] );
		if (isset ( $resultMap ['udf8'] ))
			$this->udf8 = trim ( $resultMap ['udf8'] );
		if (isset ( $resultMap ['udf9'] ))
			$this->udf9 = trim ( $resultMap ['udf9'] );
		if (isset ( $resultMap ['udf10'] ))
			$this->udf10 = trim ( $resultMap ['udf10'] );
		if (isset ( $resultMap ['udf11'] ))
			$this->udf11 = trim ( $resultMap ['udf11'] );
		if (isset ( $resultMap ['udf12'] ))
			$this->udf12 = trim ( $resultMap ['udf12'] );
		if (isset ( $resultMap ['udf13'] ))
			$this->udf13 = trim ( $resultMap ['udf13'] );
		if (isset ( $resultMap ['udf14'] ))
			$this->udf14 = trim ( $resultMap ['udf14'] );
		if (isset ( $resultMap ['udf15'] ))
			$this->udf15 = trim ( $resultMap ['udf15'] );
		if (isset ( $resultMap ['error_code_tag'] ))
			$this->error = trim ( $resultMap ['error_code_tag'] );
		if (isset ( $resultMap ['error_service_tag'] ))
			$this->error_service_tag = trim ( $resultMap ['error_service_tag'] );
		if (isset ( $resultMap ['error_text'] ))
			$this->error_text = trim ( $resultMap ['error_text'] );
		if (isset ( $resultMap ['responsecode'] ))
			$this->responseCode = trim ( $resultMap ['responsecode'] );
		if (isset ( $resultMap ['cvv2response'] ))
			$this->cvv2Verification = trim ( $resultMap ['cvv2response'] );
		if (isset ( $resultMap ['paymentId'] ))
			$this->paymentdebitId = trim ( $resultMap ['paymentId'] );
		if (isset ( $resultMap ['custId'] ))
			$this->custId = trim ( $resultMap ['custId'] );
		//Added by Krishna to send issuer response code to merchant
		if (isset ( $resultMap ['authRespCode'] ))
			$this->authRespCode = trim ( $resultMap ['authRespCode'] );
		if (isset ( $resultMap ['paymenturl'] )) {
			$this->paymentUrl = trim ( $resultMap ['paymenturl'] );
			return 2;
		}
		return 0;
	}

	function parsetrandata($trandata) {
		try {

			$splitData = $this->splitData ( $trandata );

			if (isset ( $splitData ['paymentid'] )) {
				$this->paymentId = $splitData ['paymentid'];
				//echo 'Message: '.$this->paymentId;
			}
			if (isset ( $splitData ['result'] )) {
				$this->result = $splitData ['result'];
			}
			if (isset ( $splitData ['udf1'] )) {
				$this->udf1 = $splitData ['udf1'];
			}
			if (isset ( $splitData ['udf2'] )) {
				$this->udf2 = $splitData ['udf2'];
			}
			if (isset ( $splitData ['udf3'] )) {
				$this->udf3 = $splitData ['udf3'];
			}
			if (isset ( $splitData ['udf4'] )) {
				$this->udf4 = $splitData ['udf4'];
			}
			if (isset ( $splitData ['udf5'] )) {
				$this->udf5 = $splitData ['udf5'];
			}
			if (isset ( $splitData ['udf6'] )) {
				$this->udf6 = $splitData ['udf6'];
			}
			if (isset ( $splitData ['udf7'] )) {
				$this->udf7 = $splitData ['udf7'];
			}
			if (isset ( $splitData ['udf8'] )) {
				$this->udf8 = $splitData ['udf8'];
			}
			if (isset ( $splitData ['udf9'] )) {
				$this->udf9 = $splitData ['udf9'];
			}
			if (isset ( $splitData ['udf10'] )) {
				$this->udf10 = $splitData ['udf10'];
			}
			if (isset ( $splitData ['udf11'] )) {
				$this->udf11 = $splitData ['udf11'];
			}
			if (isset ( $splitData ['udf12'] )) {
				$this->udf12 = $splitData ['udf12'];
			}
			if (isset ( $splitData ['udf13'] )) {
				$this->udf13 = $splitData ['udf13'];
			}
			if (isset ( $splitData ['udf14'] )) {
				$this->udf14 = $splitData ['udf14'];
			}
			if (isset ( $splitData ['udf15'] )) {
				$this->udf15 = $splitData ['udf15'];
			}
			if (isset ( $splitData ['amt'] )) {
				$this->amt = $splitData ['amt'];
			}
			if (isset ( $splitData ['auth'] )) {
				$this->auth = $splitData ['auth'];
			}
			if (isset ( $splitData ['ref'] )) {
				$this->ref = $splitData ['ref'];
			}
			if (isset ( $splitData ['tranid'] )) {
				$this->transId = $splitData ['tranid'];
			}
			if (isset ( $splitData ['postdate'] )) {
				$this->date = $splitData ['postdate'];
			}
			if (isset ( $splitData ['trackId'] )) {
				$this->trackId = $splitData ['trackId'];
			}
			if (isset ( $splitData ['trackid'] )) {
				$this->trackId = $splitData ['trackid'];
			}
			if (isset ( $splitData ['action'] )) {
				$this->action = $splitData ['action'];
			}
			if (isset ( $splitData ['Error'] )) {
				$this->error = $splitData ['Error'];
			}
			if (isset ( $splitData ['ErrorText'] )) {
				$this->error_text = $splitData ['ErrorText'];
			}
			if (isset ( $splitData ['error_text'] )) {
				$this->error_text = $splitData ['error_text'];
			}
			if (isset ( $splitData ['custid'] )) {
				$this->custId = $splitData ['custid'];
			}
			if (isset ( $splitData ['eci'] )) {
				$this->eci = $splitData ['eci'];
			}
			if (isset ( $splitData ['cardNumber'] )) {
				$this->cardNumber = $splitData ['cardNumber'];
			}
			if (isset ( $splitData ['authRespCode'] )) {
				$this->authRespCode = $splitData ['authRespCode'];
			}

		} catch ( Exception $e ) {

			return - 1;
		}
		return 0;
	}

	function splitData($trandata) {
        $splitData = null;
        $data = explode("&", $trandata);
        foreach ($data as $value) {
            $temp = explode("=", $value);
			if(count($temp) > 1){
				$splitData[$temp[0]] = $temp[1];
			}
        }
		//echo 'Message: '.$data[0];
		return $splitData;
	}

	function encryptData($payload, $key) {
		//$block = mcrypt_get_block_size ( 'tripledes', 'ecb' ); wont't work in php 7.x version

		$chiper = "des-ede3";  //Algorthim used to encrypt
		if ((strlen($payload) % 8) != 0) {
			//Perform right padding
			$payload = $this->rightPadZeros($payload);
		}
		////echo "hii";
//$;

		//echo $lengths;

		//die();

		$length = openssl_cipher_iv_length($chiper);
		if($length == 0) {
			$iv = '';
		} else {
			$iv = openssl_random_pseudo_bytes($length);
		}
		$encrypted = openssl_encrypt($payload, $chiper, $key, OPENSSL_RAW_DATA, $iv);

		$encrypted = unpack('C*', ($encrypted));
		$encrypted = $this->byteArray2Hex($encrypted);
		return strtoupper($encrypted);
	}

	function Decrypt($data, $secret) {
		$key = md5 ( utf8_encode ( $secret ), true );
		$key .= substr ( $key, 0, 8 );
		$data = base64_decode ( $data );
		$data = mcrypt_decrypt ( 'tripledes', $key, $data, 'ecb' );
		$block = mcrypt_get_block_size ( 'tripledes', 'ecb' );
		$len = strlen ( $data );
		$pad = ord ( $data [$len - 1] );
		return substr ( $data, 0, strlen ( $data ) - $pad );
	}

	function decryptDataNew($data, $key) {
		$iv_size = mcrypt_get_block_size ( 'tripledes', 'ecb' );
		$iv = mcrypt_create_iv ( $iv_size, MCRYPT_RAND );
		$decrypt = rtrim ( mcrypt_decrypt ( MCRYPT_3DES, $key, $data, MCRYPT_MODE_ECB, "\0" ) );
		$strPad = ord ( $decrypt [strlen ( $decrypt ) - 1] );
		$newData = substr ( $decrypt, 0, - $strPad );
		$final = trim ( $newData, "\0" );
		return $final;
	}
	function hex2ByteArray($hexString) {
		$string = hex2bin($hexString);
		return unpack('C*', $string);
	}

    function byteArray2String($byteArray) {
		$chars = array_map("chr", $byteArray);
		return join($chars);
	}

	function decryptData($data, $key) {
		// $data = mcrypt_decrypt ( MCRYPT_3DES, $key, $data, MCRYPT_MODE_ECB); wont't work in php 7.x version
		$chiper = "des-ede3";  //Algorthim used to decrypt
		$data = $this->hex2ByteArray($data);
		$data = $this->byteArray2String($data);
		$data = base64_encode($data);
		$length = openssl_cipher_iv_length($chiper);
		if($length == 0) {
			$iv = '';
		} else {
			$iv = openssl_random_pseudo_bytes($length);
		}
		$decrypted = openssl_decrypt($data, $chiper, $key, OPENSSL_ZERO_PADDING, $iv);
		//echo $decrypted;
		//die();
		return $decrypted;
	}

	function decryptDataold($data, $key) {
		$data = hex2bin ( $data );
		//$data = mcrypt_decrypt ( MCRYPT_3DES, $key, $data, MCRYPT_MODE_ECB);
		$decrypted_string = openssl_decrypt($data,"DES-EDE3",$key);
		$data = str_replace("^", "", $data);
		$data = preg_replace("/&#?[a-z0-9]+;/i","",$data);
		return $data;
	}

	function performTransaction() {
		$xmlData = null;
		$requestbuffer = "";
		$response = "";
		$webaddr = "";
		$resultMap = null;
		try {
			$keyParser = new Brac\KeyStore();
			$this->key = $keyParser->parseKeyStore ( $this->keystorePath );
			$xmlData = $this->parseResource ( $this->key, $this->resourcePath, $this->alias );
			if ($xmlData == null) {
				$this->error = "Alias name does not exits";
				return - 1;
			} else {
				$xmlData = $this->parseXMLrequest ( $xmlData );
			}
			$requestbuffer = $this->buildXMLRequest ();
			$requestbuffer .= "<id>" . $xmlData ['id'] . "</id>";
			$this->setid ( $xmlData ['id'] );
			$requestbuffer .= "<password>" . $xmlData ['password'] . "</password>";
			$webaddr = $xmlData ['webaddress'];
			$requestbuffer .= "</request>";
			$this->log->write( $requestbuffer );
			if ($this->getaction () != null && strlen (trim ( $this->getaction () ) ) < 1 && (strcasecmp ( "1", trim ( $this->getaction () ) ) || strcasecmp ( "4", trim ( $this->getaction ) ))) {
				$this->error = "Invalid action";
				return - 1;
			}
			$response = $this->performTranPortalTransaction ($requestbuffer, $webaddr);

			if ($response == null || strlen ( $response ) <= 0) {
				$this->error = "Invalid response";
				return - 1;
			}

			$resultMap = $this->parseResponse ( $response );
			return $this->setTransactionData ( $resultMap );
		} catch (Exception $e) {
			$this->error = "Error while processing request! " + $e->getMessage ();
			return - 1;
		}
	}

	function performTranPortalTransaction($request, $webAddress) {
		$webAddress = $webAddress . "/tranPipe.htm?param=tranInit";
		$response = "";
		$tranType = "tran";
		$response = $this->sendMessage ( $request, $webAddress, $tranType );
		return $response;
	}

	function performTranPortalTransactionsslKeystore($request, $webAddress, $filePath, $password) {
		$webAddress = $webAddress . "/tranPipe.htm?param=tranInit";
		$response = "";
		$tranType = "tran";
		$response = $this->sendMessage ( $request, $webAddress, $tranType, $filePath, $password );
		return $response;
	}

	function sendMessage($request, $webAddress, $tranType) {
		$rawresponse = "";
		$tranType="";
		try {
			if (strlen ( $webAddress ) <= 0) {
				return null;
			}
			$contentType = "";
			if (! strcmp ( $tranType, "host" )) {
				$contentType = "Content-Type:application/x-www-form-urlencoded";
			} else if (! strcmp ( $tranType, "tran" )) {
				$contentType = "Content-Type:application/xml";
			}
			if (strlen ( $webAddress ) <= 0) {
				return null;
			}
			$curl = curl_init ();
			if (strlen ( $request ) > 0) {
				curl_setopt ( $curl, CURLOPT_URL, $webAddress );
				curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $curl, CURLOPT_FRESH_CONNECT, TRUE );
				curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 0 );

				curl_setopt ( $curl, CURLOPT_HTTPHEADER, array (
						'Cache-Control: no-cache',
						$contentType
				) ); // setting content type header
				curl_setopt ( $curl, CURLOPT_POSTFIELDS, $request ); // Setting raw post data as xml
				                                                     // $header_size = curl_getinfo ( $curl, CURLINFO_HEADER_SIZE );
				                                                     // echo "<br/><br/><CURL>" . $header_size;
				                                                     // echo "<br/><br/>URL".$webAddress;
				$rawresponse = curl_exec ( $curl );

				if ($rawresponse == false) {
					echo '<br/>Curl error: ' . curl_error ( $curl );
				}
				curl_close ( $curl );
				return $rawresponse;
			} else {
				return null;
			}
		} catch ( Exception $exception ) {
			echo "<br/><br/><br/>" . $exception->getTraceAsString();
			return null;
		}
	}

	function rightPadZeros($Str) {
        if(null == $Str){
            return null;
        }
        $PadStr = $Str;

        for ($i = strlen($Str);($i%8)!=0; $i++) {
            $PadStr .= "^";
        }
        return $PadStr;
	}

	function byteArray2Hex($byteArray) {
        $chars = array_map("chr", $byteArray);
        $bin = join($chars);
        return bin2hex($bin);
	}

}

?>

<?php

class ControllerPaymentAtompay extends Controller {
	protected function index() {
		$this->language->load('payment/atompay');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');		
		$this->data['url2'] = $this->url->link('payment/atompay/dopayment');	
		$this->session->data['order_id'];		
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/atompay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/atompay.tpl';
		} else {
			$this->template = 'default/template/payment/atompay.tpl';
		}	
		
		$this->render();		
	}
	public function dopayment() {

		$vendor = $this->config->get('atompay_vendor');
		$password = $this->config->get('atompay_password');		
		$this->data['action'] = $this->config->get('atompay_url');		

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$datenow 		= date("d/m/Y");
		$this->data['BillingCity']		   = $order_info['payment_city'];
       	$this->data['BillingPostCode']	   = $order_info['payment_postcode'];	
        $this->data['BillingCountry']      = $order_info['payment_iso_code_2'];

		$this->data['login']	       = $this->config->get('atompay_vendor');
		$this->data['pass']			   = $this->config->get('atompay_password');
		$this->data['ttype']		   = 'NBFundTransfer';
		$this->data['action']		   = $this->config->get('atompay_url');
		$this->data['prodid']		   = $this->config->get('atompay_prodid');
		$this->data['amt']			   = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$this->data['txnid']		   = $this->session->data['order_id'];
		$this->data['txndate']		   = $datenow;
		$this->data['CustomerName']    = html_entity_decode($order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$this->data['CustomerEMail']   = $order_info['email'];
		$this->data['BillingPhone']    = $order_info['telephone'];
		$this->data['BillingAddress1'] = $order_info['payment_address_1']."|".$this->data['BillingCity']."|".$this->data['BillingCountry'];
		$this->data['ru']			   = $this->url->link('payment/atompay/success');

		$postFields  = "";
		$postFields .= "&login=".$this->data['login'];
		$postFields .= "&pass=".$this->data['pass'];
		$postFields .= "&ttype=".$this->data['ttype'];
		$postFields .= "&prodid=".$this->data['prodid'];
		$postFields .= "&amt=".$this->data['amt'];
		$postFields .= "&txncurr=INR";
		$postFields .= "&txnscamt=0";
		$postFields .= "&clientcode=".urlencode(base64_encode('123'));
		$postFields .= "&txnid=".$this->data['txnid'];
		$postFields .= "&date=".$datenow;
		$postFields .= "&custacc=123456789012";
		$postFields .= "&udf1=".$this->data['CustomerName'];
		$postFields .= "&udf2=".$this->data['CustomerEMail'];
		$postFields .= "&udf3=".$this->data['BillingPhone'];
		$postFields .= "&udf4=".$this->data['BillingAddress1'];
		$postFields .= "&ru=".$this->data['ru'];


		$sendUrl = $this->data['action']."?".substr($postFields,1);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->data['action']);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_PORT , 443); 
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		$returnData = curl_exec($ch); 

		$parser = xml_parser_create('');
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); 
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($returnData), $xml_values);
		xml_parser_free($parser);
	
		if(isset($xml_values[3]['value'])=='' || isset($xml_values[4]['value'])=='' || isset($xml_values[5]['value'])=='')
			{
				$this->redirect($this->url->link('checkout/atomfailure&msg=1'));
			}
	
		$returnArray['url'] 		= $xml_values[3]['value'];
		$returnArray['ttype'] 		= $xml_values[4]['value'];
		$returnArray['tempTxnId']	= $xml_values[5]['value'];
		$returnArray['token'] 		= $xml_values[6]['value'];		

		$url =$returnArray['url'] ;
		$postFields  = "";
		$postFields .= "&ttype=".$returnArray['ttype'] ;
		$postFields .= "&tempTxnId=".$returnArray['tempTxnId'];
		$postFields .= "&token=".$returnArray['token'] ;
		$postFields .= "&txnStage=1";
		$url = $url."?".$postFields;
		
		if($returnArray['tempTxnId']=='')
			{
				$this->redirect($this->url->link('checkout/atomfailure&msg=1'));
			}
		else
			{
				header("Location: ".$url);	
			}
	}
	public function success() {
		if ($this->request->post['f_code'] =='Ok') {
			$this->load->model('checkout/order');
			$this->model_checkout_order->confirm($this->request->post['mer_txn'], $this->config->get('config_order_status_id'),"Payment Pending");
			$this->model_checkout_order->update($this->request->post['mer_txn'], $this->config->get('atompay_order_status_id'), "Payment Received", false);		
			$this->redirect($this->url->link('checkout/success'));		
		}
		else
			{
			$message = "Payment failed";
			$this->load->model('checkout/order');
			$this->model_checkout_order->confirm($this->request->post['mer_txn'], $this->config->get('config_order_status_id'),"Payment Pending");
			$this->model_checkout_order->update($this->request->post['mer_txn'], $this->config->get('config_order_status_id'), $message, false);
			//$this->error['warning'] = "Transaction Denied. Payment failed.";
			$this->redirect($this->url->link('checkout/atomfailure'));
		}
	}	
}
?>
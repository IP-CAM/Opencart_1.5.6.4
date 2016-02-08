<?php 
class ControllerPaymentAtompay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/atompay');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('atompay', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_sim'] = $this->language->get('text_sim');
		$this->data['text_test'] = $this->language->get('text_test');
		$this->data['text_live'] = $this->language->get('text_live');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_defered'] = $this->language->get('text_defered');
		$this->data['text_authenticate'] = $this->language->get('text_authenticate');
		
		$this->data['entry_vendor'] = $this->language->get('entry_vendor');
		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_prodid'] = $this->language->get('entry_prodid');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['vendor'])) {
			$this->data['error_vendor'] = $this->error['vendor'];
		} else {
			$this->data['error_vendor'] = '';
		}

		if (isset($this->error['url'])) {
			$this->data['error_url'] = $this->error['url'];
		} else {
			$this->data['error_url'] = '';
		}

		if (isset($this->error['prodid'])) {
			$this->data['error_prodid'] = $this->error['prodid'];
		} else {
			$this->data['error_prodid'] = '';
		}

 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/atompay', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/atompay', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['atompay_vendor'])) {
			$this->data['atompay_vendor'] = $this->request->post['atompay_vendor'];
		} else {
			$this->data['atompay_vendor'] = $this->config->get('atompay_vendor');
		}

		if (isset($this->request->post['atompay_url'])) {
			$this->data['atompay_url'] = $this->request->post['atompay_url'];
		} else {
			$this->data['atompay_url'] = $this->config->get('atompay_url');
		}

		if (isset($this->request->post['atompay_prodid'])) {
			$this->data['atompay_prodid'] = $this->request->post['atompay_prodid'];
		} else {
			$this->data['atompay_prodid'] = $this->config->get('atompay_prodid');
		}

		
		if (isset($this->request->post['atompay_password'])) {
			$this->data['atompay_password'] = $this->request->post['atompay_password'];
		} else {
			$this->data['atompay_password'] = $this->config->get('atompay_password');
		}

		if (isset($this->request->post['atompay_test'])) {
			$this->data['atompay_test'] = $this->request->post['atompay_test'];
		} else {
			$this->data['atompay_test'] = $this->config->get('atompay_test');
		}
		
		if (isset($this->request->post['atompay_transaction'])) {
			$this->data['atompay_transaction'] = $this->request->post['atompay_transaction'];
		} else {
			$this->data['atompay_transaction'] = $this->config->get('atompay_transaction');
		}
		
		if (isset($this->request->post['atompay_total'])) {
			$this->data['atompay_total'] = $this->request->post['atompay_total'];
		} else {
			$this->data['atompay_total'] = $this->config->get('atompay_total'); 
		} 
				
		if (isset($this->request->post['atompay_order_status_id'])) {
			$this->data['atompay_order_status_id'] = $this->request->post['atompay_order_status_id'];
		} else {
			$this->data['atompay_order_status_id'] = $this->config->get('atompay_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['atompay_geo_zone_id'])) {
			$this->data['atompay_geo_zone_id'] = $this->request->post['atompay_geo_zone_id'];
		} else {
			$this->data['atompay_geo_zone_id'] = $this->config->get('atompay_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['atompay_status'])) {
			$this->data['atompay_status'] = $this->request->post['atompay_status'];
		} else {
			$this->data['atompay_status'] = $this->config->get('atompay_status');
		}
		
		if (isset($this->request->post['atompay_sort_order'])) {
			$this->data['atompay_sort_order'] = $this->request->post['atompay_sort_order'];
		} else {
			$this->data['atompay_sort_order'] = $this->config->get('atompay_sort_order');
		}

		$this->template = 'payment/atompay.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/atompay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['atompay_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
		}

		if (!$this->request->post['atompay_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['atompay_url']) {
			$this->error['url'] = $this->language->get('error_url');
		}

		if (!$this->request->post['atompay_prodid']) {
			$this->error['prodid'] = $this->language->get('error_prodid');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
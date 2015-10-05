<?php
class ControllerPaymentCompropago extends Controller {
  private $error = array();
 
  public function index() {
    $this->language->load('payment/compropago');
    $this->document->setTitle('Compropago Payment Method Configuration');
    $this->load->model('setting/setting');
 
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('compropago', $this->request->post);
      $this->session->data['success'] = $this->language->get('text_success');
      $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }
 
    $data['heading_title'] = $this->language->get('heading_title');

    $data['text_edit'] = $this->language->get('text_edit');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_yes'] = $this->language->get('text_yes');
    $data['text_no'] = $this->language->get('text_no');

    $data['entry_secret_key'] = $this->language->get('entry_secret_key');
    $data['entry_public_key'] = $this->language->get('entry_public_key');
    
    $data['entry_order_status'] = $this->language->get('entry_order_status');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');

    $data['help_secret_key'] = $this->language->get('help_secret_key');
    $data['help_public_key'] = $this->language->get('help_public_key');

    $data['button_save'] = $this->language->get('text_button_save');
    $data['button_cancel'] = $this->language->get('text_button_cancel');

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    if (isset($this->error['secret_key'])) {
      $data['error_secret_key'] = $this->error['secret_key'];
    } else {
      $data['error_secret_key'] = '';
    }

    if (isset($this->error['public_key'])) {
      $data['error_public_key'] = $this->error['public_key'];
    } else {
      $data['error_public_key'] = '';
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_payment'),
      'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('payment/compropago', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['action'] = $this->url->link('payment/compropago', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
 
    if (isset($this->request->post['compropago_secret_key'])) {
      $data['compropago_secret_key'] = $this->request->post['compropago_secret_key'];
    } else {
      $data['compropago_secret_key'] = $this->config->get('compropago_secret_key');
    }
        
    if (isset($this->request->post['compropago_public_key'])) {
      $data['compropago_public_key'] = $this->request->post['compropago_public_key'];
    } else {
      $data['compropago_public_key'] = $this->config->get('compropago_public_key');
    }          
        
    if (isset($this->request->post['compropago_order_status_id'])) {
      $data['compropago_order_status_id'] = $this->request->post['compropago_order_status_id'];
    } else {
      $data['compropago_order_status_id'] = $this->config->get('compropago_order_status_id');
    } 

    if (isset($this->request->post['compropago_sort_order'])) {
      $data['compropago_sort_order'] = $this->request->post['compropago_sort_order'];
    } else {
      $data['compropago_sort_order'] = $this->config->get('compropago_sort_order');
    }

    if (isset($this->request->post['compropago_status'])) {
      $data['compropago_status'] = $this->request->post['compropago_status'];
    } else {
      $data['compropago_status'] = $this->config->get('compropago_status');
    }
 
    $this->load->model('localisation/order_status');
    $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');
 
    $this->response->setOutput($this->load->view('payment/compropago.tpl', $data));
  }

  private function validate() {
    if (!$this->request->post['compropago_secret_key']) {
      $this->error['secret_key'] = $this->language->get('error_secret_key');
    }

    if (!$this->request->post['compropago_public_key']) {
      $this->error['public_key'] = $this->language->get('error_public_key');
    }

    return !$this->error;
  }
}
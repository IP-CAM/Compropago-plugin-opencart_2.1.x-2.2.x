<?php
class ControllerPaymentCompropago extends Controller {
  public function index() {
    $this->language->load('payment/compropago');    

    $data['text_title'] = $this->language->get('text_title');
    $data['entry_payment_type'] = $this->language->get('entry_payment_type');
    $data['button_confirm'] = $this->language->get('button_confirm');
    $data['providers'] = $this->getProviders();
  
    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']); 

    if ($order_info) {
      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/compropago.tpl')) {
        return $this->load->view($this->config->get('config_template') . '/template/payment/compropago.tpl', $data);
      } else {
        return $this->load->view('default/template/payment/compropago.tpl', $data);
      }
    }
  }
  
  public function getProviders() {
    $url = 'http://api-staging-compropago.herokuapp.com/v1/providers/true';    
    $username = $this->config->get('compropago_secret_key');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $this->_response = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($this->_response,true);

    foreach ($response as $key => $_provider){
        if($_provider['internal_name'] == 'OXXO'){
            $response[$key]['item_name'] = 'oxxo';
        } else if($_provider['internal_name'] == 'SEVEN_ELEVEN'){
            $response[$key]['item_name'] = 'seven';
        } else if($_provider['internal_name'] == 'EXTRA'){
            $response[$key]['item_name'] = 'extra';
        } else if($_provider['internal_name'] == 'SORIANA'){
            $response[$key]['item_name'] = 'soriana';
        } else if($_provider['internal_name'] == 'CHEDRAUI'){
            $response[$key]['item_name'] = 'chedraui';
        } else if($_provider['internal_name'] == 'ELEKTRA'){
            $response[$key]['item_name'] = 'elektra';
        } else if($_provider['internal_name'] == 'FARMACIA_BENAVIDES'){
            $response[$key]['item_name'] = 'benavides';
        } else if($_provider['internal_name'] == 'FARMACIA_GUADALAJARA'){
            $response[$key]['item_name'] = 'guadalajara';
        } else if($_provider['internal_name'] == 'FARMACIA_ESQUIVAR'){
            $response[$key]['item_name'] = 'esquivar';
        } else if($_provider['internal_name'] == 'COPPEL'){
            $response[$key]['item_name'] = 'coppel';
        }   
    }

    return $response;   
  }

  public function send() {    
    $this->load->model('checkout/order');

    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    $products = $this->cart->getProducts();

    $order_name = '';

    foreach ($products as $product) {
        $order_name .= $product['name'];
    }

    $data = array(
            'order_id'        => $order_info['order_id'],
            'order_price'        => $order_info['total'],
            'order_name'         => $order_name,
            'customer_name'         => $order_info['payment_firstname'],
            'customer_email'     => 'jdjd@djdj.com',
            'payment_type'               => $this->request->post['payment-type']
        );
    
    $url = 'https://api-staging-compropago.herokuapp.com/v1/charges';    
    $username = $this->config->get('compropago_secret_key');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $this->_response = curl_exec($ch);

    curl_close($ch);

    $response = json_decode($this->_response,true);

    if (isset($response)){            
        $error = ("El servicio de Compropago no se encuentra disponible.");
        $json['error'] = $error; 
    }
    
    $json = array();

    if (isset($response['type'])){
        $error = $response['message'];
        $json['error'] = $error;     
    }

    if (isset($response['id'])){
        $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('compropago_order_status_id'));        
        $expiration_date = $response['exp_date'];
        $short_id = $response['short_id'];
        $instructions = $response['instructions'];
        $step_1 = $instructions['step_1'];
        $step_2 = $instructions['step_2'];
        $step_3 = $instructions['step_3'];
        $note_extra_comition = $instructions['note_extra_comition'];
        $note_expiration_date = $instructions['note_expiration_date'];
        $json['success'] = $this->url->link('payment/compropago/success', 'short_id='.$short_id.'&expiration_date='.$expiration_date.'&step_1='.$step_1.'&step_2='.$step_2.'&step_3='.$step_3.'&note_extra_comition='.$note_extra_comition.'&note_expiration_date='.$note_expiration_date , 'SSL');             
    }         

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function success() {
    $this->language->load('payment/compropago');
    $this->cart->clear();

    if (!$this->request->server['HTTPS']) {
        $data['base'] = HTTP_SERVER;
    } else {
        $data['base'] = HTTPS_SERVER;
    }

    $data['short_id'] = $this->request->get['amp;short_id'];
    $data['expiration_date'] = $this->request->get['amp;expiration_date'];
    $data['step_1'] = $this->request->get['amp;step_1'];
    $data['step_2'] = $this->request->get['amp;step_2'];
    $data['step_3'] = $this->request->get['amp;step_3'];
    $data['note_extra_comition'] = $this->request->get['amp;note_extra_comition'];
    $data['note_expiration_date'] = $this->request->get['amp;note_expiration_date'];

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('common/home')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_basket'),
        'href' => $this->url->link('checkout/cart')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_checkout'),
        'href' => $this->url->link('checkout/checkout', '', 'SSL')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_success'),
        'href' => $this->url->link('checkout/success')
    );

    $data['text_success_title'] = $this->language->get('text_success_title');
    $data['text_date_expiration'] = $this->language->get('text_date_expiration');
    $data['text_instructions'] = $this->language->get('text_instructions');
    $data['text_comitions'] = $this->language->get('text_comitions');
    $data['text_warning'] = $this->language->get('text_warning');
    $data['text_reference'] = $this->language->get('text_reference');
    $data['text_card_number'] = $this->language->get('text_card_number');
    
    $data['language'] = $this->language->get('code');
    $data['button_continue'] = $this->language->get('button_continue');
    $data['continue'] = $this->url->link('common/home');

    $data['column_left'] = $this->load->controller('common/column_left');
    $data['column_right'] = $this->load->controller('common/column_right');
    $data['content_top'] = $this->load->controller('common/content_top');
    $data['content_bottom'] = $this->load->controller('common/content_bottom');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/compropago_success.tpl')) {
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/compropago_success.tpl', $data));
    } else {
        $this->response->setOutput($this->load->view('default/template/payment/compropago_success.tpl', $data));
    }
  }

  public function webhook() {
    $body = @file_get_contents('php://input');
    $event_json = json_decode($body);
    $this->load->model('checkout/order');

    if ($event_json->{'api_version'} === '1.1') {
        if ($event_json->{'id'}){
            $order = $this->verifyOrder($event_json->{'id'});   
        }
    } else {
        if ($event_json->data->object->{'id'}){
            $order = $this->verifyOrder($event_json->data->object->{'id'});    
                          
        }
    }       
    
    $order_id = $this->model_checkout_order->getOrder($order['order_info']['order_id']);  
    $type = $order['type'];

    switch ($type) {    
        case 'charge.pending':
            print_r('pending');
            $this->model_checkout_order->addOrderHistory($order_id['order_id'], $this->config->get('compropago_order_status_id'));        
            break;
        case 'charge.success':
            print_r('success');
            $this->model_checkout_order->addOrderHistory($order_id['order_id'], 2);        
            break;
        case 'charge.declined':         
            print_r('declined');
            $this->model_checkout_order->addOrderHistory($order_id['order_id'], 8);                    
            break;
        case 'charge.deleted':
            print_r('deleted');
            $this->model_checkout_order->addOrderHistory($order_id['order_id'], 10);        
            break;
        case 'charge.expired':
            print_r('expired');
            $this->model_checkout_order->addOrderHistory($order_id['order_id'], 14);        
            break;              
    }           
  }

  public function verifyOrder($id){
    $url = 'https://api-staging-compropago.herokuapp.com/v1/charges/';
    $url .=  $id;   
    $username = $this->config->get('compropago_secret_key');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        

    $this->_response = curl_exec($ch);

    curl_close($ch);

    $response = json_decode($this->_response,true);

    return $response;
  } 
}
?>
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . '/../../../vendor/autoload.php';

use CompropagoSdk\Client;
use CompropagoSdk\Factory\Factory;

class ControllerPaymentCppayment extends Controller {

  public function index() {
    $this->language->load('payment/cppayment');
    $this->load->model('checkout/order');

    $this->load->model('setting/setting');

    $client = new Client(
      $this->config->get('cppayment_public_key'),
      $this->config->get('cppayment_private_key'),
      $this->config->get('cppayment_mode')
    );

    $data['text_title'] = $this->language->get('text_title');
    $data['entry_payment_type'] = $this->language->get('entry_payment_type');
    $data['button_confirm'] = $this->language->get('button_confirm');

    $data['providers'] = $client->api->listProviders();

    $data['continue'] = $this->url->link('checkout/success');

    $this->addBreadcrums($data);
    $this->addData($data);

    return $this->load->view('default/template/payment/cp_providers.tpl', $data);
  }

  public function confirm() {
    $this->load->model('checkout/order');
    $this->load->model('setting/setting');

    $order_id = $this->session->data['order_id'];
    $order_info = $this->model_checkout_order->getOrder($order_id);
    $products = $this->cart->getProducts();

    $order_name = '';
    foreach ($products as $product) {
      $order_name .= $product['name'];
    }

    $data_order = [
      'order_id' => $order_id,
      'order_name' => $order_name,
      'order_price' => $order_info['total'],
      'customer_name' => $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
      'customer_email' => $order_info['email']
    ];

    $order = Factory::getInstanceOf('NewOrderInfo', $data_order);
    $new_order = $this->client->api->placeOrderInfo($order);

    /**
     * Inicia el registro de transacciones
     */

    $recordTime = time();
    $order_id = $order_info['order_id'];
    $ioIn = base64_encode(json_encode($response));
    $ioOut = base64_encode(json_encode($data));

    // Creacion del query para compropago_orders
    $query = "INSERT INTO " . DB_PREFIX . "compropago_orders (`date`,`modified`,`compropagoId`,`compropagoStatus`,`storeCartId`,`storeOrderId`,`storeExtra`,`ioIn`,`ioOut`)".
        " values (:fecha:,:modified:,':cpid:',':cpstat:',':stcid:',':stoid:',':ste:',':ioin:',':ioout:')";

    $query = str_replace(":fecha:",$recordTime,$query);
    $query = str_replace(":modified:",$recordTime,$query);
    $query = str_replace(":cpid:",$response->id,$query);
    $query = str_replace(":cpstat:",$response->status,$query);
    $query = str_replace(":stcid:",$order_id,$query);
    $query = str_replace(":stoid:",$order_id,$query);
    $query = str_replace(":ste:",'COMPROPAGO_PENDING',$query);
    $query = str_replace(":ioin:",$ioIn,$query);
    $query = str_replace(":ioout:",$ioOut,$query);


    $this->db->query($query);

    $compropagoOrderId = $this->db->getLastId();

    $query2 = "INSERT INTO ".DB_PREFIX."compropago_transactions
    (orderId,date,compropagoId,compropagoStatus,compropagoStatusLast,ioIn,ioOut)
    values (:orderid:,:fecha:,':cpid:',':cpstat:',':cpstatl:',':ioin:',':ioout:')";

    $query2 = str_replace(":orderid:",$compropagoOrderId,$query2);
    $query2 = str_replace(":fecha:",$recordTime,$query2);
    $query2 = str_replace(":cpid:",$response->id,$query2);
    $query2 = str_replace(":cpstat:",$response->status,$query2);
    $query2 = str_replace(":cpstatl:",$response->status,$query2);
    $query2 = str_replace(":ioin:",$ioIn,$query2);
    $query2 = str_replace(":ioout:",$ioOut,$query2);

    $this->db->query($query2);

    $query_update = "UPDATE ${DB_PREFIX}order SET order_status_id = 1 WHERE order_id = $order_id";
    $this->db->query($query_update);

    $json['success'] = htmlspecialchars_decode($this->url->link('payment/compropago/success', 'info_order=' . $new_order->id , 'SSL'));

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function success() {
    $this->language->load('payment/cppayment');
    $this->cart->clear();

    $data['order_id'] = $this->request->get['info_order'];

    $this->addBreadcrums($data);
    $this->addData($data);

    $this->response->setOutput($this->load->view('payment/cp_receipt.tpl', $data));
  }

  /**
   * Add breadcrums data
   * 
   * @param array $data
   * 
   * @author Eduardo Aguilar <dante.aguilar41@gmail.com> 
   */
  private function addBreadcrums(&$data) {
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
  }

  /**
   * Add secuencial data for reder view
   * 
   * @param array $data
   * 
   * @author Eduardo Aguilar <dante.aguilar41@gmail.com> 
   */
  private function addData(&$data) {
    $data['button_continue'] = $this->language->get('button_continue');
    $data['continue'] = $this->url->link('common/home');

    $data['column_left'] = $this->load->controller('common/column_left');
    $data['column_right'] = $this->load->controller('common/column_right');
    $data['content_top'] = $this->load->controller('common/content_top');
    $data['content_bottom'] = $this->load->controller('common/content_bottom');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
  }
}
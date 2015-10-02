<?php
class ModelPaymentCompropago extends Model {
  public function getMethod($address, $total) {
    $this->load->language('payment/compropago');
  
    $method_data = array(
      'code'     => 'compropago',
      'title'    => $this->language->get('text_title'),
      'terms'      => true,
      'sort_order' => $this->config->get('compropago_sort_order')
    );
  
    return $method_data;
  }
}
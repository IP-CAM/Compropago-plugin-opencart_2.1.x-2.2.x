<?php 
error_reporting(E_ALL);
ini_set("display_errors", 0);

class ModelPaymentCppayment extends Model {

  public function getMethod($address, $total) {
    return [
      'code' => 'cppayment',
      'title' => '<img src="https://compropago.com/plugins/logo.png" style="height:25px;"  alt="ComproPago - Efectivo"/> - Pago en efectivo',
      'terms' => '',
      'sort_order' => 1
    ];
  }

}
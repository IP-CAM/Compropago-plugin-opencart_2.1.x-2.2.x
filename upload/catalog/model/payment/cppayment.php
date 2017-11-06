<?php 
error_reporting(E_ALL);
ini_set("display_errors", 1);

class ModelPaymentCppayment extends Model {

  public function getMethod($address, $total) {
    return [
      'code' => 'cppayment',
      'title' => '<img src="https://cdn.compropago.com/cp-assets/ui-compropago/logo.svg" style="height:50px;"  alt="ComproPago - Efectivo"/>',
      'terms' => '',
      'sort_order' => 1
    ];
  }

}
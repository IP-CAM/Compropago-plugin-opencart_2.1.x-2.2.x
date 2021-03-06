<?php echo $header; ?>
<?php echo $column_left ?>

<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <h1>Orden de compra generada.</h1>
      <hr>

      <div class="compropagoDivFrame" id="compropagodContainer" style="width: 100%;">
          <iframe style="width: 100%;"
              id="compropagodFrame"
              src="https://www.compropago.com/comprobante/?confirmation_id=<?php echo $order_id; ?>"
              frameborder="0"
              scrolling="yes"> </iframe>
      </div>
      <script type="text/javascript">
          function resizeIframe() {
              var container=document.getElementById("compropagodContainer");
              var iframe=document.getElementById("compropagodFrame");
              if(iframe && container){
                  var ratio=585/811;
                  var width=container.offsetWidth;
                  var height=(width/ratio);
                  if(height>937){ height=937;}
                  iframe.style.width=width + 'px';
                  iframe.style.height=height + 'px';
              }
          }
          window.onload = function(event) {
              resizeIframe();
          };
          window.onresize = function(event) {
              resizeIframe();
          };
      </script>
    </div>
  </div>
</div>

<?php echo $footer; ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/cppayment.css" />

<form class="form-horizontal" >
  <fieldset id="payment">
    <div class="row">
      <div class="col-sm-12">
        <h3>Proveedores Disponibles</h3>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        Seleccione el proveedor en el cual desea realizar su pago.
        <hr>
      </div>
    </div>
    
    <div class="row">
      <div class="col-sm-12 cpprovider-select">
        <?php if ($show_logos) { ?>
          <ul>
            <?php foreach ($providers as $provider) { ?>
              <li>
                <input type="radio" id="compropago_<?php echo $provider->internal_name; ?>" name="cp_provider" value="<?php echo $provider->internal_name; ?>">
                <label for="compropago_<?php echo $provider->internal_name; ?>">
                  <img src="<?php echo $provider->image_medium; ?>" alt="compropago_<?php echo $provider->internal_name; ?>">
                </label>
              </li>
            <?php } ?>
          </ul>
        <?php } else { ?>
          <select name="cp_provider" title="Proveedores" class="form-control">
            <?php foreach ($providers as $provider){ ?>
              <option value="<?php echo $provider->internal_name; ?>"> <?php echo $provider->name; ?> </option>
            <?php } ?>
          </select>
        <?php } ?>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="buttons">
          <div class="pull-right">
            <a href="#" id="button-confirm" class="btn btn-primary">Confirm order</a>
          </div>
        </div>
      </div>
    </div>
  </fieldset>
</form>



<script>
var providers = document.querySelectorAll(".cpprovider-select ul li label img");

for (x = 0; x < providers.length; x++){
    providers[x].addEventListener('click', function(){
        cleanCpRadio();
        id = this.getAttribute("alt");
        document.querySelector("#"+id).checked = true;
    });
}
function cleanCpRadio(){
    for(y = 0; y < providers.length; y++){
        id = providers[y].parentNode.getAttribute('for');
        document.querySelector("#"+id).checked = false;
    }
}

$('#button-confirm').on('click', function(e) {
  e.preventDefault();

  var providers = $("[name='cp_provider']");
  var paymentType = null;

  if (providers.length > 1) {
    for (var i = 0; i < providers.length; i++) {
      if ($(providers[i]).is(':checked')) {
        paymentType = $(providers[i]).val();
      }
    }
  } else {
    paymentType = $('select[name=cp_provider]').val();
  }

  if (paymentType == null){
    alert('Debe seleccionar un proveedor para realizar su pago');
  } else {
    $.ajax({
      type: 'post',
      url: 'index.php?route=payment/cppayment/confirm',
      cache: false,
      data: { payment_type: paymentType },
      beforeSend: function() {
        $('#button-confirm').button('loading');
      },
      complete: function() {
        $('#button-confirm').button('reset');
      },
      success: function(res) {
        console.log(res);
        if (res.status == 'success') {
          location.href = res.redirect;
        } else {
          alert(res.message);
        }
      }
    });
  }
});
</script>

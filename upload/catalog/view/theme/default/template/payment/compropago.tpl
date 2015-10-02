<form class="form-horizontal">
  <fieldset id="payment">
    <legend><?php echo $text_title; ?></legend>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-payment-type"><?php echo $entry_payment_type; ?></label>
      <div class="col-sm-10">
        <select name="payment-type" id="input-payment-type" class="form-control">
          <?php foreach ($providers as $provider): ?> 
              <option value="<?php echo $provider['internal_name'] ?>"><?php echo $provider['name'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
  $.ajax({
    url: 'index.php?route=payment/compropago/send',
    type: 'post',
    data: $('#payment :input'),
    dataType: 'json',
    beforeSend: function() {
      $('#button-confirm').button('loading');
    },
    complete: function() {
      $('#button-confirm').button('reset');
    },
    success: function(json) {
      if (json['error']) {
        alert(json['error']);
      }

      if (json['success']) {
        location = json['success'];
      }
    }
  });
});
//--></script>

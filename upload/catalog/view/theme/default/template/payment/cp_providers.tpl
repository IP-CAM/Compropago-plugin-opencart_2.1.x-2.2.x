
<form class="form-horizontal" >
  <fieldset id="payment">
    <div class="row">
      <div class="col-sm-12">
        <h3><?php echo $description; ?></h3>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <?php echo $instructions; ?>
      </div>
    </div>
    
    <div class="row">
      <div class="col-sm-12">
        <select name="cp_provider">
          <?php foreach ($providers as $provider) { ?>
            <option value="<?php echo $ptovider->internal_name; ?>"><?php echo $provider->name; ?></option>
          <?php ?>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="buttons">
          <div class="pull-right">
            <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" />
          </div>
        </div>
      </div>
    </div>
  </fieldset>
</form>

<script>
$('#button-confirm').on('click', function() {
	$.ajax({
		type: 'post',
		url: 'index.php?route=payment/cppayment/confirm',
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},
		success: function(res) {
      console.log(res);
			location = res.success;
		}
	});
});
</script>
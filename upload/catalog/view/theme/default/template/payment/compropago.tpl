<?php
require_once __DIR__."/../../../../../../vendor/autoload.php";

use Compropago\Sdk\Controllers\Views;
?>

<link rel="stylesheet" href="vendor/compropago/php-sdk/assets/css/compropago.css">

<form class="form-horizontal">
    <fieldset id="payment">
        <?php Views::loadView('providers',$comprodata); ?>
    </fieldset>
</form>

<div class="buttons">
    <div class="pull-right">
        <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
    </div>
</div>

<script type="text/javascript">
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
</script>

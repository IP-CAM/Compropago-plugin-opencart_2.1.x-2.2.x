<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/compropago.css" />
<link href="catalog/view/theme/default/stylesheet/compropago.css" rel="stylesheet" type="text/css" media="screen, print" />
<?php echo $header; ?>
<div class="container">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h3><?php echo $text_success_title; ?></h3>      
      <div class="cp-instruction-section">
      <div class="expiration-date">
          <?php echo $text_date_expiration; ?>
        <span><?php echo date('d-m-Y', strtotime($expiration_date));  ?></span>
      </div>
      <div class="cp-title"><?php echo $text_instructions; ?></div>
      <div class="cp-step-box">
          <div class="cp-step">
              <div class="cp-num">1.</div> <span> <?php echo $step_1; ?></span>
          </div>
          <div class="cp-step">
                <div class="cp-num">2.</div> <span> <?php echo $step_2; ?></span>
          </div>
          <div class="cp-step">
              <div class="cp-num">3.</div> <span> <?php echo $step_3; ?></span>
          </div>
      </div>
      <hr class="cp-grey">
      <span class="cp-note" style="font-size:12px;color: #333;"><?php echo $text_comitions; ?></span>
      <div class="cp-warning-box">
        <img src="catalog/view/theme/default/image/warning.png" style="margin: -7px 0px 0px 0px;"> 
        <span style="font-size: 12px;"><b><?php echo $text_warning ?></b></span>
        <ul style="" class="cp-warning">
          <li><?php echo $text_reference ?> <b><?php echo $short_id ?></b></li>
          <li><?php echo $text_card_number ?></li>
          <li><?php echo $note_extra_comition; ?></li>
          <li><?php echo $note_expiration_date; ?></li>
        </ul>
      </div>   
      <div class="buttons">        
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
        <div class="pull-right print"><a onclick="javascript:printDiv('content')" class="btn btn-primary">Imprimir</a></div>
      </div>
    <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>   
</div>
<?php echo $footer; ?>
<script language="javascript" type="text/javascript">
      function printDiv(divID) {
          //Get the HTML of div
          var divElements = document.getElementById(divID).innerHTML;
          //Get the HTML of whole page
          var oldPage = document.body.innerHTML;

          //Reset the page's HTML with div's HTML only
          document.body.innerHTML = 
            "<html><head><title></title></head><body>" + 
            divElements + "</body>";

          //Print Page
          window.print();

          //Restore orignal HTML
          document.body.innerHTML = oldPage;

        
      }
  </script>
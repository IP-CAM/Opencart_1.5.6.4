<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
	 <tr>
            <td><span class="required">*</span> <?php echo $entry_url; ?></td>
            <td><input type="text" name="atompay_url" value="<?php echo $atompay_url; ?>" />
              <?php if ($error_url) { ?>
              <span class="error"><?php echo $error_url; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_vendor; ?></td>
            <td><input type="text" name="atompay_vendor" value="<?php echo $atompay_vendor; ?>" />
              <?php if ($error_vendor) { ?>
              <span class="error"><?php echo $error_vendor; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_password; ?></td>
            <td><input type="text" name="atompay_password" value="<?php echo $atompay_password; ?>" />
              <?php if ($error_password) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php } ?></td>
          </tr>    
	   <tr>
            <td><span class="required">*</span> <?php echo $entry_prodid; ?></td>
            <td><input type="text" name="atompay_prodid" value="<?php echo $atompay_prodid; ?>" />
              <?php if ($error_prodid) { ?>
              <span class="error"><?php echo $error_prodid; ?></span>
              <?php } ?></td>
          </tr>

          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="atompay_order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $atompay_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>          
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="atompay_status">
                <?php if ($atompay_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>         
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
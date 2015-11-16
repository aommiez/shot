<?php
use Main\Helper;
use Main\DB\Medoo\MedooFactory;
$this->import('/layout/header');
$db = MedooFactory::getInstance();
$photo = $db->select('photo','*');
?>
<div class="container">
  <h3>photo list</h3>
  <div class="row">
    <?php foreach($params['photos'] as $photo){?>
    <div class="col-md-3">
      <img class="img" width="100%" photo-id="<?php echo $photo['id'];?>" src="<?php echo $photo['url'];?>">
    </div>
    <?php }?>
  </div>

  <hr>
  <form id="send-photo-form">
    <div class="form-group">
      <label>Email</label>
      <input type="email" id="email-input" class="form-control">
    </div>
    <div class="row">
      <div class="col-md-12">
        <button type="submit" class="btn btn-primary">Send Photo</button>
      </div>
    </div>
  </form>
</div>
<style>
.img {
  cursor: pointer;
}

.img.selected {
  opacity: 0.4;
}
</style>
<script>
$(function(){
  $('.img').click(function(e){
    $el = $(this);
    $el.toggleClass('selected');
  });

  $('#send-photo-form').submit(function(e){
    e.preventDefault();
    $inputs = $('input, btn', this);

    var list_id = [];
    $('.img.selected').each(function(index, el){
      list_id.push($(el).attr('photo-id'));
    });

    if(list_id.length == 0) {
      alert('Please select photo.');
      return;
    }

    var send = {
      list_id: list_id,
      email: $('#email-input').val()
    };

    $inputs.prop('disabled', true);
    $.ajax("", {
      type: "POST",
      dataType: "json",
      data: send,
      success: function(data){
        $inputs.prop('disabled', false);
        if(data.error) {
          alert(data.error.message);
        }
        else {
          alert('Success email send.');
        }
      },
      error: function(){
        $inputs.prop('disabled', false);
      }
    });
  });
});
</script>

<?php
use Main\Helper;
use Main\DB\Medoo\MedooFactory;
$db = MedooFactory::getInstance();
$this->import('/layout/header');
?>
<style media="screen">
  .logoBox {
    margin-top: 20px;
    background-color: #ffffff;
    padding: 30px;
  }
  .listLogo img {
    width: 128px;
    height: auto;
    padding: 2px;
  }

  .bgImg {
    width: 128px;
    height: auto;
    padding: 2px;
  }

  .bgBox {
    margin-top: 20px;
    background-color: #ffffff;
    padding: 30px;
  }
</style>
<div class="container">
  <div class="logoBox">
    <div class="headerLogo">
      <div>
        <div style="padding-bottom:6px;">
          <h2>Logo</h2>
        </div>
        <?php
          $logoUse = $db->get('setting','logo_img');
          echo "<img src=\"public/logo/{$logoUse}\" style=\"width:128px;\" id='logoUse'>";
        ?>
        <div style="margin-top:10px;">
          Logo Position : <select id='logoPosSelect'>
            <?php
              $logoPos = $db->get('setting','logo_position');
              echo "<option value=\"{$logoPos}\" select>{$logoPos}</option>";
            ?>
            <option value="left-top">left-top</option>
            <option value="left-center">left-center</option>
            <option value="left-bottom">left-bottom</option>
            <option value="right-top">right-top</option>
            <option value="right-center">right-center</option>
            <option value="right-bottom">right-bottom</option>
            <option value="center-top">center-top</option>
            <option value="center-center">center-center</option>
            <option value="center-bottom">center-bottom</option>
          </select>
        </div>

      </div>
    </div>
    <div class="listLogo">
      <?php
        $logolist = $db->select('logo','*');
        foreach ($logolist as $key => $value) {
          echo <<<HTML
          <img src='public/logo/{$value['logo_name']}' logo-name="{$value['logo_name']}" class='logoImg'>
HTML;
        }
      ?>
      <div style='padding-top:10px;'>
        <form action="./uploadLogo" method="post" enctype="multipart/form-data">
          <input type="file" name="fileToUpload" id="fileToUpload" style='display:inline-block;'>
          <input type="submit" value="Upload Logo" name="submit">
      </form>
      </div>
    </div>
  </div>
  <div class="bgBox">
    <div style="padding-bottom:6px;">
      <h2>BG</h2>
    </div>
    <?php
      $bgUse = $db->get('setting','bg_img');
      echo "<img src=\"public/bg/{$bgUse}\" style=\"width:128px;\" id='bgUse'>";
    ?>
    <div style="margin-top:10px;">
      BG Type : <select id='bgSelect'>
        <?php
          $bgtypes = $db->get('setting','bg_type');
          echo "<option value=\"{$bgtypes}\" select>{$bgtypes}</option>";
        ?>
        <option value="fixed">fixed</option>
        <option value="repeat">repeat</option>
        <option value="full">full</option>
        <option value="auto">auto</option>
      </select>
    </div>
    <?php
      $logolist = $db->select('bg','*');
      foreach ($logolist as $key => $value) {
        echo <<<HTML
        <img src='public/bg/{$value['bg_name']}' bg-name="{$value['bg_name']}" class='bgImg'>
HTML;
      }
    ?>

    <div style='padding-top:10px;'>
      <form action="./uploadBg" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload" style='display:inline-block;'>
        <input type="submit" value="Upload BG" name="submit">
    </form>
    </div>
  </div>
  </div>
</div>


<script language="JavaScript">
  $('#logoPosSelect').on('change', function() {
    $.post( "./editlogopos", { pos: this.value} );
  });

  $(".logoImg").on('click',function() {
    $.post( "./editlogoname", { pos: $(this).attr('logo-name')} );
    $('#logoUse').attr('src','public/logo/'+$(this).attr('logo-name'));
    //console.log($(this).attr('logo-name'));
  });


  $('#bgSelect').on('change', function() {
    $.post( "./editbgtype", { t: this.value} );
  });

  $(".bgImg").on('click',function() {
    $.post( "./editbgname", { bg: $(this).attr('bg-name')} );
    $('#bgUse').attr('src','public/bg/'+$(this).attr('bg-name'));
    //console.log($(this).attr('logo-name'));
  });


</script>

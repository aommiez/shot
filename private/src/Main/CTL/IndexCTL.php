<?php
/**
 * Created by PhpStorm.
 * User: p2
 * Date: 7/15/14
 * Time: 11:27 AM
 */

namespace Main\CTL;


use Main\Context\Context;
use Main\Http\RequestInfo;
use Main\View\HtmlView;
use Main\View\JsonView;
use Main\ThirdParty\Xcrud\Xcrud;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\URL;
use Main\Helper\ResponseHelper;

/**
 * @Restful
 * @uri /
 */
class IndexCTL extends BaseCTL {

    /**
     * @GET
     */
    public function getViewIndex () {
        return new HtmlView('/index');
    }

    /**
     * @GET
     * @uri photolist
     */
    public function getViewPhotoList(){
        // if PHP under version 5.4 use return new JsonView(array("id"=> $id));
        $db = MedooFactory::getInstance();
        $photos = $db->select("photo", "*");
        foreach($photos as &$photo) {
          $photo['url'] = URL::absolute('/public/upload/'.$photo['photo_name']);
        }
        return new HtmlView('/photolist', ['photos'=> $photos]);
    }

    /**
     * @POST
     * @uri photolist
     */
    public function postPhotoList() {
        // if PHP under version 5.4 use return new JsonView(array("id"=> $id));
        $db = MedooFactory::getInstance();
        $photos = $db->select("photo", "*", ["id"=> $_POST['list_id']]);

        $mail = new \PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('system@system.com');
        $mail->addAddress($_POST['email']);
        $mail->Subject = 'Send photo';
        $mail->msgHTML('Send photo');

        foreach($photos as $key => $photo) {
          // $uploadfile = tempnam(sys_get_temp_dir(), sha1($_FILES['image']['name']));
          // move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);
          $path = 'public/upload/'.$photo['photo_name'];
          $mail->addAttachment($path, $photo['photo_name']);
        }

        if($mail->send()) {
          return ["success"=> true, 'photos'=> $photos];
        }
        else {
          return ResponseHelper::error($mail->ErrorInfo);
        }
    }

    /**
     * @GET
     * @uri setting
     */
    public function getViewSetting(){
        // if PHP under version 5.4 use return new JsonView(array("id"=> $id));
        return new HtmlView('/setting');
    }

    /**
     * @POST
     * @uri savephoto
     */
    public function postSavePhoto(){
        $photo_name = $this->genToken(12).'.jpg';
        $db = MedooFactory::getInstance();
        $db->insert('photo',["photo_name" => $photo_name]);
        move_uploaded_file($_FILES['webcam']['tmp_name'],'public/upload/'.$photo_name);
        return new JsonView(["photoname"=> $photo_name]);
    }


    /**
     * @POST
     * @uri uploadLogo
     */
    public function postUploadLogo(){
        $photo_name = $this->genToken(12);
        $db = MedooFactory::getInstance();
        $imgType = explode('.',$_FILES["fileToUpload"]['name']);
        $imgType = '.'.$imgType[1];
        $db->insert('logo',["logo_name" => $photo_name.$imgType]);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],'public/logo/'.$photo_name.$imgType);
        return new HtmlView('/setting');
    }

    /**
     * @POST
     * @uri uploadBg
     */
    public function postuploadBg(){
        $photo_name = $this->genToken(12);
        $db = MedooFactory::getInstance();
        $imgType = explode('.',$_FILES["fileToUpload"]['name']);
        $imgType = '.'.$imgType[1];
        $db->insert('bg',["bg_name" => $photo_name.$imgType]);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],'public/bg/'.$photo_name.$imgType);
        return new HtmlView('/setting');
    }

    /**
     * @POST
     * @uri editlogopos
     */
     public function editLogoPos() {
       $db = MedooFactory::getInstance();
       return new JsonView($db->update('setting',['logo_position'=>$this->reqInfo->param('pos')]));
     }

     /**
      * @POST
      * @uri editbgtype
      */
      public function editbgtype() {
        $db = MedooFactory::getInstance();
        return new JsonView($db->update('setting',['bg_type'=>$this->reqInfo->param('t')]));
      }




     /**
      * @POST
      * @uri editlogoname
      */
      public function editLogoName() {
        $db = MedooFactory::getInstance();
        return new JsonView($db->update('setting',['logo_img'=>$this->reqInfo->param('pos')]));
      }


    /**
     * @POST
     * @uri editbgname
     */
     public function editbgname() {
       $db = MedooFactory::getInstance();
       return new JsonView($db->update('setting',['bg_img'=>$this->reqInfo->param('bg')]));
     }



    /**
     * @GET
     * @uri getlogolist
     */
    public function getLogoList(){
      $db = MedooFactory::getInstance();
      return new JsonView($db->get('logo','*'));
    }


    /**
     * @GET
     * @uri getsetting
     */
    public function getSetting(){
      $db = MedooFactory::getInstance();
      $arr = $db->get('setting','*');
      $arr['logo_img'] = \Main\Helper\URL::absolute("/public/logo/".$arr['logo_img']);
      $arr['bg_img'] = \Main\Helper\URL::absolute("/public/bg/".$arr['bg_img']);
      return new JsonView($arr);
    }

    /**
     * @GET
     * @uri getphoto
     */
    public function getPhoto(){
      $db = MedooFactory::getInstance();
      return new JsonView($db->get('photo','*'));
    }

    public function genToken($length)
     {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
     }
}

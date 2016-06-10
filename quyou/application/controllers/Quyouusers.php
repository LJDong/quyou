<?php
class quyouusers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("quyouusermodel");
        $this->load->helper("url_helper");
        $this->load->library("session");
    }
    public function touserinfo()
    {
        $user_id = $_SESSION['user_id'];
        $data['user_id'] = $user_id;
        $this->load->view('templates/header.html');
        $this->load->view("userinfo.html",$data);
        $this->load->view('templates/footer.html');
    }
    public function index_mobile()
    {
        $this->load->view("index_mobile.html");
    }
    public function getuserinfo()
    {
        $user_id = $_POST['user_id'];
        $ret = $this->quyouusermodel->select(array("user_id",$user_id));
        $this->jsonreturn("查询成功", $ret, 0);
    }
    public function getselfinfo()
    {
         $user_id = $_SESSION['user_id'];
         if(isset($user_id))
         {
             $ret = $this->quyouusermodel->select(array("user_id",$user_id));
             $this->jsonreturn("查询成功", $ret, 0);
         }
         else {
             $this->jsonreturn("未登录", array(), -1);
         }
        
    }
    public function toregister()
    {
        $this->load->view('templates/header.html');
        $this->load->view("register.html");
        $this->load->view('templates/footer.html');
    }
    public function verityregister()
    {
        $email = trim($_POST["email"]);
        if($email == null)
        {
            $jsonret = json_encode(array(
                'reason'=>'无邮箱',
                'result'=>array(),
                'error_code'=>-1
            ));
            echo $jsonret;
            exit;
        }
        $ret = $this->quyouusermodel->verityregister($email);
        $jsonret = json_encode(array(
            'reason'=>'查询成功',
            'result'=>array('code'=>$ret),
            'error_code'=>0
        ));
        echo $jsonret;
    }
    public function register()
    {
        $registertime =  time();
        $email = trim($_POST["email"]);
        $name = trim($_POST["name"]);
        $password = trim($_POST["password"]);
        $logo_url='/aplugin/picture/userlogo.png';
        $infoarr = array(
            'user_password'=>$password,
            'user_name'=>$name,
            'user_email'=>$email,
            'user_state'=>'0',
            'user_logo_url'=>$logo_url,
            'user_sex'=>'0',
            'user_register_time'=>$registertime
        );
        $ret = $this->quyouusermodel->register($infoarr);
        echo $ret;
    }
   public function tologin()
   {
        $this->load->view('templates/header.html');
        $this->load->view("login.html");
        $this->load->view('templates/footer.html');
   }
   public function login()
   {
       $reason = '';
       $result = array();
       $error_code = 0;
       $email = trim($_POST['email']);
       $password=trim($_POST['password']);
       $ret  = $this->quyouusermodel->login($email);
       if($ret['ret']== false)
       {
              $reason='该账号不存在';
              $error_code=-1;
       }
       else {
           if($ret['password'] === $password)
           {
               $ip = $_SERVER['REMOTE_ADDR'];
               $user_info = array(
                   "name"=>$ret['name'],
                   "email"=>$email,
                   "user_id"=>$ret['user_id'],
               );
               $this->session->set_userdata($user_info);
               $reason='查询成功';
               $error_code = 0;
           }
           else{
               $reason="密码不正确";
               $error_code =1;
           }
       }
       $jsonret = json_encode(array(
           'reason'=>$reason,
           'result'=>$result,
           'error_code'=>$error_code
       ));
       echo $jsonret;
       exit;
   }
   public function logout()
   {
       $reason = '';
       $error_code=0;
       $result = array();
       unset(
           $_SESSION['name'],
           $_SESSION['user_id'],
           $_SESSION['email']
           );
       $this->jsonreturn($reason,$result,$error_code);
   }
   public function getloginstate()
   {
       $reason = '';
       $result = array();
       $error_code = 0;
       if(isset($_SESSION['name']))
       {
           $reason = '查询成功';
           $result = array('user_name'=>$_SESSION['name'],'user_id'=>$_SESSION['user_id']);
           $error_code = 0;
       }
       else{
           $reason = '未登录';
           $result  = array();
           $error_code = -1;
       }
       $this->jsonreturn($reason,$result,$error_code);
   }
   public function jsonreturn($reason,$result,$error_code)
   {
       $jsonret = json_encode(array(
           'reason'=>$reason,
           'result'=>$result,
           'error_code'=>$error_code
       ));
       echo $jsonret;
   }
   public function getlogo()
   {
       $x1 = $_POST['x1'];
       $y1 = $_POST['y1'];
       $x2 = $_POST['x2'];
       $y2 = $_POST['y2'];
       $url = $_POST['img_url'];
       $path = BASEPATH."..".$url;//图片位置
       $user_id = $_SESSION['user_id'];
       $width = abs($x2-$x1);
       $height = abs($y2 - $y1);
       $ret = $this->thumb($path, $width, $height,1, 111, $x1,  $y1);
       echo $ret= $this->quyouusermodel->update(array("user_logo_url"=>$ret['url']),array('user_id',$user_id));
       exit;
       $this->jsonreturn("成功", array('url'=>$ret['url']), 0);exit;
   }
   public function mycurl($url,$data,$type=0)
   {
       $data = http_build_query($data);
       $ch=curl_init();
       curl_setopt($ch,CURLOPT_URL,$url);
       curl_setopt($ch,CURLOPT_HEADER,0);
       curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
       curl_setopt($ch,CURLOPT_POST,$type);
       curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
       $result=curl_exec($ch);
       curl_close($ch);
       return $result;
   }
   public function imgupload()
   {
       $path = BASEPATH."../aplugin/picture/";
       $valid_formats = array("jpg", "png", "gif", "bmp");
       if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
       {
           $name = $_FILES['photoimg']['name'];
           $size = $_FILES['photoimg']['size'];
       
           if(strlen($name))
           {
               list($txt, $ext) = explode(".", $name);
               if(in_array($ext,$valid_formats))
               {
                       $actual_image_name = time()."code_2".$_SESSION['user_id'].".".$ext;
                       $tmp = $_FILES['photoimg']['tmp_name'];
                       if(move_uploaded_file($tmp, $path.$actual_image_name))
                       {
                           $this->jsonreturn("上传成功", array('imgurl'=>'/aplugin/picture/'.$actual_image_name), 0);exit;
                       }
                       else
                           $this->jsonreturn("失败", array(), -1);exit;
               }
               else
                   $this->jsonreturn("Invalid file format..", array(), -2);exit;
           }
       
           else
               $this->jsonreturn("Please select image..!", array(), -2);exit;
       }
   }
   
   public function info_mobile()
   {
       $this->load->view("info_mobile.html");
   }
   public function login_mobile()
   {
       $this->load->view("login_mobile.html");
   }
   public function register_mobile()
   {
       $this->load->view("register_mobile.html");
   }
   protected  function thumb($src_file, $new_width, $new_height, $type = 1, $pos = 5, $start_x = 0, $start_y = 0) {
       $pathinfo = pathinfo($src_file);
       $dst_name =  time().$pathinfo['filename'] .'_'. $new_width . 'x' . $new_height . '.' . $pathinfo['extension'];
       $dst_file = $pathinfo['dirname'] . '/' .$dst_name;
       if (!file_exists($dst_file)) {
           if ($new_width < 1 || $new_height < 1) {
               echo "params width or height error !";
               exit();
           }
           if (!file_exists($src_file)) {
               echo $src_file . " is not exists !";
               exit();
           }
           // 图像类型
           $img_type = exif_imagetype($src_file);
           $support_type = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
           if (!in_array($img_type, $support_type, true)) {
               echo "只支持jpg、png、gif格式图片裁剪";
               exit();
           }
           /* 载入图像 */
           switch ($img_type) {
               case IMAGETYPE_JPEG :
                   $src_img = imagecreatefromjpeg($src_file);
                   break;
               case IMAGETYPE_PNG :
                   $src_img = imagecreatefrompng($src_file);
                   break;
               case IMAGETYPE_GIF :
                   $src_img = imagecreatefromgif($src_file);
                   break;
               default:
                   echo "载入图像错误!";
                   exit();
                   }
                   /* 获取源图片的宽度和高度 */
                   $src_width = imagesx($src_img);
                    $src_height = imagesy($src_img);
                   $max = max($src_height,$src_width);
                   $lv = $max/300;
                   $new_width = $new_width*$lv;
                   $new_height = $new_height*$lv;
                   $start_x = $start_x*$lv;
                   $start_y = $start_y*$lv;
                   /* 计算剪切图片的宽度和高度 */
                   $mid_width = ($src_width < $new_width) ? $src_width : $new_width;
                   $mid_height = ($src_height < $new_height) ? $src_height : $new_height;
                   /* 初始化源图片剪切裁剪的起始位置坐标 */
                   switch ($pos * $type) {
                   case 1://1为顶端居左
                   $start_x = 0;
                   $start_y = 0;
                   break;
                   case 2://2为顶端居中
                   $start_x = ($src_width - $mid_width) / 2;
                       $start_y = 0;
                       break;
                       case 3://3为顶端居右
                       $start_x = $src_width - $mid_width;
                       $start_y = 0;
                       break;
                       case 4://4为中部居左
                           $start_x = 0;
                           $start_y = ($src_height - $mid_height) / 2;
                           break;
                       case 5://5为中部居中
                           $start_x = ($src_width - $mid_width) / 2;
                           $start_y = ($src_height - $mid_height) / 2;
                           break;
                       case 6://6为中部居右
                           $start_x = $src_width - $mid_width;
                           $start_y = ($src_height - $mid_height) / 2;
                           break;
                       case 7://7为底端居左
                           $start_x = 0;
                           $start_y = $src_height - $mid_height;
                           break;
                           case 8://8为底端居中
                               $start_x = ($src_width - $mid_width) / 2;
                               $start_y = $src_height - $mid_height;
                               break;
                           case 9://9为底端居右
                               $start_x = $src_width - $mid_width;
                               $start_y = $src_height - $mid_height;
                               break;
                           default://随机
                               break;
                           }
                           // 为剪切图像创建背景画板
                           $mid_img = imagecreatetruecolor($mid_width, $mid_height);
                           //拷贝剪切的图像数据到画板，生成剪切图像
                           /* echo 's'.$src_width;echo 's'.$src_height;
                           echo 'x'.$start_x;  echo 'y'.$start_y;  echo 'x'.$mid_width;  echo 'x'.$mid_height;exit; */
                            imagecopy($mid_img, $src_img, 0, 0, $start_x, $start_y, $mid_width, $mid_height);
                           // 为裁剪图像创建背景画板
                           $new_img = imagecreatetruecolor($new_width, $new_height);
                           //拷贝剪切图像到背景画板，并按比例裁剪
                           imagecopyresampled($new_img, $mid_img, 0, 0, 0, 0, $new_width, $new_height, $mid_width, $mid_height);
                           /* 按格式保存为图片 */
                           switch ($img_type) {
                               case IMAGETYPE_JPEG :
                                   imagejpeg($new_img, $dst_file, 100);
                                   break;
                               case IMAGETYPE_PNG :
                                   imagepng($new_img, $dst_file, 9);
                                   break;
                               case IMAGETYPE_GIF :
                                   imagegif($new_img, $dst_file, 100);
                                   break;
                               default:
                                   break;
                           }
                           }
                           return array("desc_url"=>ltrim($dst_file, '.'),"url"=>'/aplugin/picture/'.$dst_name);
                           }
   
}
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
        $logo_url='quyou.com/aplugin/picture/userlogo.png';
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
   public function getuserinfo()
   {
       $user_id =  $this->uri->segment(3);
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
}
<?php
class capture extends CI_Controller
{
    public function  __construct()
    {
        parent::__construct();
        $this->load->model('capturemodel');
        $this->load->helper('url_helper');
        include_once BASEPATH.'libraries/self/email.class.php';
    }
    public function veritycapture()
    {
        $word = $_POST['capture'];
        if($word == null)
        {
            $jsonret = json_encode(array(
                'reason'=>'无验证码',
                'result'=>array(),
                'error_code'=>-1
            ));
            echo $jsonret;
            exit;
        }
        $ip = $this->input->ip_address();
        $ret = $this->capturemodel->veritycapture($word,$ip);
        $jsonret = json_encode(array(
            'reason'=>'查询成功',
            'result'=>array("code"=>$ret),
            'error_code'=>0
        ));
        echo $jsonret;
    }
    public function getcapture()
    {
        $ip_adderss = $this->input->ip_address();
        $ret = $this->capturemodel->getcapture($ip_adderss);
        $jsonret = json_encode(array(
            'reason'=>'查询成功',
            'result'=>array('imageurl' =>$ret['image']),
            'error_code'=>0
        ));
        echo $jsonret;
       // var_dump($ret);
    }
    public function getemailcapture()
    {
        $ip_adderss = $this->input->ip_address();
        $email = trim($_REQUEST['email']);
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
        $ret = $this->capturemodel->getcapture($ip_adderss);
        $url = $ret['image'];
        $smtp = new smtp('smtp.126.com',25,true,'diaochong1205@126.com','liujidong');
        $body="$url";
        $body .= $ret['word'];
        $state = $smtp->sendmail($email,'diaochong1205@126.com','趣友邮箱验证码',$body,"HTMl");
        $jsonret = json_encode(array(
            'reason'=>'查询成功',
            'result'=>array(),
            'error_code'=>$state
        ));
        echo $jsonret;
    }
}
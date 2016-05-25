<?php
class circlefocus extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("url_helper");
        $this->load->library("session");
        $this->load->model("circlefocusmodel");
    }
    public function getcirclelist()
    {
        $user_id = $_SESSION['user_id'];
        if(isset($user_id))
        {
            $ret = $this->circlefocusmodel->select(array('cf_user_id',$user_id));
            $this->jsonreturn("查询成功", $ret, 0);
            exit;
        }
        else
       {
            $this->jsonreturn("未登录", array(), -1);
        }
    }
    public function focuscircle()
    {
        $circle_id = $_POST['circle_id'];
        if(isset($_SESSION["user_id"]))
        {
            $arr = array(
                'cf_circle_id'=>$circle_id,
                'cf_user_id'=>$_SESSION["user_id"],
                'cf_time'=>time()
            );
            $ret = $this->circlefocusmodel->insert($arr);
            $this->jsonreturn("查询成功", $ret, 0);
            exit();
        }
        else 
       {
            $this->jsonreturn("未登录", array(), -1);
            exit;
        }
    }
    public function cancelfocus()
    {
        $circle_id = $_POST['circle_id'];
        $user_id = $_SESSION['user_id'];
        $ret = $this->circlefocusmodel->delete(array('cf_circle_id',$circle_id,'cf_user_id',$user_id));
        $this->jsonreturn("查询成功", $ret, 0);
        exit();
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
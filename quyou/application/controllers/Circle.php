<?php
class circle extends  CI_Controller{
    public function __construct()
    {
        //phpinfo();exit;
        parent::__construct();
        $this->load->helper("url_helper");
        $this->load->library("session");
        $this->load->model("circlemodel");
    }
    
    public function getcircledetail()
    {
        $circle_id = $_POST['circle_id'];
        $user_id = $_SESSION['user_id'];
        if(isset($user_id))
        {
            $ret =$this->circlemodel->getcircledetail($circle_id,$user_id);
            $this->jsonreturn("查询成功", $ret, 0);
        }
        else
        {
            $this->jsonreturn("未登录", array(), -1);
        }
    }
    
    
    public function tocircle()
    {
        $circle_id = $this->uri->segment(3);
        $data = array(
            'circle_id'=> $circle_id
        );
        $this->load->view("templates/header.html");
        $this->load->view("circle.html",$data);
        $this->load->view("templates/footer.html");
    }
    public function tocircle_mobile()
    {
        $circle_id = $this->uri->segment(3);
        $data = array(
            'circle_id'=> $circle_id
        );
        $this->load->view("tocircle_mobile.html",$data);
    }
    public function getcirclelist()
    {
         $page = $this->uri->segment(3);
         $ret = $this->circlemodel->getcirclelist($page);
         $this->jsonreturn('获取成功',$ret,0);
    }
    public function tonewcircle()
    {
        $this->load->view("templates/header.html");
        if(!isset($_SESSION['user_id']))
        {
            $this->load->view('login.html');
        }
        else {
            $this->load->view("newcircle.html");
        }
        $this->load->view("templates/footer.html");
    }
    public function newcircle()
    {
        //var_dump($_POST);exit;
        $circle_name = trim($_POST['circle_name']);
        $circle_detail = trim($_POST['circle_detail']);
        $circle_topic_count = 0;
        $circle_create_time = time();
        $circle_user_id = $_SESSION['user_id'];
        if($circle_user_id == null)
        {
            $this->jsonreturn('创建失败，请先登录', array(), -1);
            exit;
        }
        elseif ($this->circlemodel->getcircle(array('circle_name',$circle_name)) != null)
        {
            $this->jsonreturn('创建失败,圈名已经存在', array(), -2);
            exit;
        }
        else{
            $arr = array(
                'circle_name'=>$circle_name,
                'circle_detail'=>$circle_detail,
                'circle_topic_count'=>$circle_topic_count,
                'circle_create_time'=>$circle_create_time,
                'circle_user_id'=>$circle_user_id,
                'circle_state'=>0
            );
            $ret = $this->circlemodel->createcircle($arr);
            $this->jsonreturn('创建成功', $ret, 0);
            exit;
        }
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
    public function getcirclebyname()
    {
        $name =$_POST['circle_name'];
        $limit = $_POST['limit'];
        $page = $_POST['page'];
        $ret = $this->circlemodel->getcircle(array('circle_name',$name));
        $this->jsonreturn('查询成功',$ret, 0);
        exit;
    }
    public function getcirclebytuijian()
    {
        $ret = $this->circlemodel->getcircletuijian(4,0);
        $this->jsonreturn('查询成功',$ret, 0);
        exit;
    }
    public function getcirclebyuser_id()
    {
        $name = $this->uri->segment(3);
        $arr = array('circle_user_id',$name);
        $ret = $this->circlemodel->getcircle($arr);
        $this->jsonreturn('查询成功',$ret, 0);
        exit;
    }
    
    public function circle_mobile()
    {
        $this->load->view("circle_mobile.html");
    }
    
    
    
}
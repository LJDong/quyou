<?php
class topic extends CI_Controller
{
    public function __construct()
    {
         parent::__construct();
         $this->load->helper('url_helper');
         $this->load->model("topicmodel");
         $this->load->library("session");
    }
    public function totopicindex()
    {
        $topic_id = $this->uri->segment(3);
        $topic = $this->topicmodel->gettopic($topic_id);
        $data['topic_id'] = $topic_id;
        $data['topic'] = $topic;
        $this->load->view('templates/header.html');
        $this->load->view("topic.html",$data);
        $this->load->view('templates/footer.html');
    }
    
    public function topic_mobile()
    {
        $topic_id = $this->uri->segment(3);
        $topic = $this->topicmodel->gettopic($topic_id);
        $data['topic_id'] = $topic_id;
        $data['topic'] = $topic;
        $this->load->view("topic_mobile.html",$data);
    }
    
    public function gettopiclist()
    {
        $circle_id = $this->uri->segment(3);
        $this->topicmodel->gettopiclist(array('topic_circle_id',$circle_id));
        exit;
    }
    public function tonewtopic_mobile()
    {
        $circle_id = $this->uri->segment(3);
        $data = array(
            'circle_id'=>$circle_id
        );
        $this->load->view("newtopic_mobile.html",$data);
    }
    public function tonewtopic()
    {
        $circle_id = $this->uri->segment(3);
        $data = array(
            'circle_id'=>$circle_id
        );
        if(!isset($_SESSION['user_id']))
        {
            $this->load->view('templates/header.html');
            $this->load->view("login.html");
            $this->load->view('templates/footer.html');
        }
        else 
       {
           $this->load->view('templates/header.html');
           $this->load->view("newtopic.html",$data);
           $this->load->view('templates/footer.html');
        }
    }
    public function newtopic()
    {
        $topic_name = trim($_POST['title']);
        $topic_content = trim($_POST['content']);
        $topic_circle_id = trim($_POST['circle_id']);
        $topic_subcontent = substr(trim($_POST['contenttext']),0,30);
        $topic_firstimg = trim($_POST['topic_firstimg']);
        $topic_time = time();
        $topic_last_time = time();
        $topic_comment_num =0;
        $topic_user_id = $_SESSION['user_id'];
        $arr = array(
            "topic_name"=>$topic_name,
            'topic_content'=>$topic_content,
            'topic_circle_id'=>$topic_circle_id,
            'topic_subcontent'=>$topic_subcontent,
            'topic_firstimg'=>$topic_firstimg,
            'topic_time'=>$topic_time,
            'topic_last_time'=>$topic_last_time,
            'topic_comment_num'=>$topic_comment_num,
            'topic_user_id'=>$topic_user_id,
            'topic_state'=>0
        );
       $ret = $this->topicmodel->createtopic($arr);
       $this->jsonreturn("查询成功",$ret,0);
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
    public function gettopcilistbyuser_id()
    {
        $user_id = $_POST['user_id'];
       /*  $this->topicmodel->(); */
    }
}

?>
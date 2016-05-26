<?php
class reply  extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->model("replymodel");
    }
    public function toreply()
    {
        $reply_content = trim($_POST['content']);
        $reply_to_user_id = trim($_POST['to_user_id']);
        $reply_comment_id = trim($_POST["comment_id"]);
        if(!isset($_SESSION['user_id']))
        {
            $this->jsonreturn("用户未登录", array(), -1);
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $ret = $this->replymodel->insert(array(
            "reply_content"=>$reply_content,
            "reply_to_user_id"=>$reply_to_user_id,
            "reply_comment_id"=>$reply_comment_id,
            "reply_user_id"=>$user_id,
            "reply_time"=>time(),
            "reply_state"=>0
        ));
        $this->jsonreturn("查询成功",$ret, 0);
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
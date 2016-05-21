<?php
class topic extends CI_Controller
{
    public function __construct()
    {
         parent::__construct();
         $this->load->helper('url_helper');
    }
    public function totopicindex()
    {
        $topic_id = $this->uri->segment(3);
        $this->load->view('templates/header.html');
        $this->load->view("topic.html");
        $this->load->view('templates/footer.html');
    }
    public function getmytopic()
    {
        $arr1 = array(
           'topic_id'=>'123123',
            'topic_name'=>'还想再活五百年',
            'topic_subcontent'=>'还想再活五百年<br/>还想再活五百年123',
            'topic_firstimg'=>'null',
            'topic_lasttime'=>'2015-12-1 9:00',
            'topic_num'=>'12',
            'circle_id'=>'1231',
            'circle_name'=>'歌曲',
            'user_logo'=>'/aplugin/picture/userlogo.png',
            'user_name'=>'六级',
            'user_id'=>'456'
             );
        $arr2 = array(
            'topic_id'=>'123456',
            'topic_name'=>'还想再活五百年',
            'topic_subcontent'=>'还想再活五百年<br/>还想再活五百年123',
            'topic_firstimg'=>'null',
            'topic_lasttime'=>'2015-12-1 9:00',
            'topic_num'=>'11',
            'circle_id'=>'1231',
            'circle_name'=>'歌曲',
            'user_logo'=>'/aplugin/picture/userlogo.png',
            'user_name'=>'7级',
            'user_id'=>'456'
        );
        $arr[] = $arr1;
        $arr[] = $arr2;
        echo  json_encode($arr);
    }
    public function gettopic()
    {
        $circle_id = $this->uri->segment(3);
        
        $arr1 = array(
            'comment_id'=>'123123',
            'comm_content'=>'还想再活五百年<br/>还想再活五百年123',
            'replynum'=>'11',
            'comm_time'=>'2015-12-1 9:00',
            'user_name'=>'六级',
            'user_id'=>'456',
            'replys'=>array(
                1=>array(
                    'user_id'=>'456',
                    'user_name'=>'六级',
                    'user_logo' =>'/aplugin/picture/userlogo.png',
                    'to_user_id'  =>'123',
                    'to_user_name' =>'七级',
                    'reply_content'=>'hahahhahahahhahahhahhahahhaha',
                    'reply_time'=>'2015-12-1 9:00', 
                    'reply_id'=>'123456'                
                    ),
            )
        );
       $arr2 = array(
            'comment_id'=>'133123',
            'comm_content'=>'还想再活五百年<br/>还想再活五百年123',
            'replynum'=>'2',
            'comm_time'=>'2015-12-1 9:00',
            'user_name'=>'六级',
            'user_id'=>'456',
            'replys'=>array(
                1=>array(
                    'user_id'=>'456',
                    'user_name'=>'六级',
                    'user_logo' =>'/aplugin/picture/userlogo.png',
                    'to_user_id'  =>'123',
                    'to_user_name' =>'七级',
                    'reply_content'=>'hahahhahahahhahahhahhahahhaha',
                    'reply_time'=>'2015-12-1 9:00', 
                    'reply_id'=>'123456'                
                    ),
                2=>array(
                    'user_id'=>'456',
                    'user_name'=>'六级',
                    'user_logo' =>'/aplugin/picture/userlogo.png',
                    'to_user_id'  =>'123',
                    'to_user_name' =>'七级',
                    'reply_content'=>'hahahhahahahhahahhahhahahhaha',
                    'reply_time'=>'2015-12-1 9:00',
                    'reply_id'=>'122456'
                ),
            )
        );
        $arr[] = $arr1;
        $arr[] = $arr2;
        echo  json_encode($arr);
    }
    public function tonewtopic()
    {
        $circle_id = $this->uri->segment(3);
        $data = array(
            'circle_id'=>$circle_id
        );
        $this->load->view('templates/header.html');
        $this->load->view("newtopic.html",$data);
        $this->load->view('templates/footer.html');
    }
    public function newtopic()
    {
        var_dump($_POST);
    }
}

?>
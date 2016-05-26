<?php
class topicmodel extends CI_Model
{
    private $table = 'topic';
    public function __construct()
    {
        $this->load->database();
    }
    public function createtopic($arr)
    {
        $ret = $this->db->insert($this->table,$arr);
        return $ret;
    }
    public function updatetopic($arr,$topic_id)
    {
        $this->db->where('toptic_id',$topic_id);
        $ret = $this->db->update($this->table,$arr);
        return $ret;
    }
    public function topicstate($topic_id,$state)
    {
        $this->db->where('topic_id',$topic_id);
        $arr=array('state'=>$state);
        $ret = $this->db->update($this->table,$arr);
        return $ret;
    }
    public function gettopiclist($arr)
    {
        $this->db->where($arr[0],$arr[1]);
        $this->db->select("topic_id,topic_name,topic_subcontent,topic_firstimg,topic_last_time,topic_comment_num as topic_num,circle_id,circle_name,user_logo_url as user_logo,user_name,user_id");
        $this->db->from($this->table);
        $this->db->join('circle','topic.topic_circle_id = circle.circle_id');
        $this->db->join('quyouusers','topic.topic_user_id = quyouusers.user_id');
        $this->db->order_by('topic_last_time','desc');
        $query = $this->db->get();
        $arr = $query->result_array();
        echo json_encode($arr);exit;
    }
    public function gettopic($topic_id)
    {
        $this->db->select("topic_content,user_id,user_name,topic_id");
        $this->db->where('topic_id',$topic_id);
        $this->db->from($this->table);
        $this->db->join("quyouusers","topic.topic_user_id = quyouusers.user_id");
        $query=$this->db->get();
        return $query->result_array();
    }
}
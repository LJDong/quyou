<?php
class topicmodel extends CI_Model
{
    private $table = 'topicmodel';
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
    public function gettopic($topic_id)
    {
        $this->db->where('topic_id',$topic_id);
        $this->db->select();
        $query=$this->db->get($this->table);
        return $query->result;
    }
}
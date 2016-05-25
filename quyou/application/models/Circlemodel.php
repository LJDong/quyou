<?php
class circlemodel extends CI_Model
{
    private $table = 'circle';
    public function __construct()
    {
        $this->load->database();
    }
    public function createcircle($arr)
    {
        $ret = $this->db->insert($this->table,$arr);
        $circle_id = $this->db->insert_id();
        return array('circle_id'=>$circle_id,'ret'=>$ret);
    }
    public function getcircle($arr)
    {
        $ret = $this->db->where($arr[0],$arr[1]);
        $query = $this->db->get($this->table);
        $row = $query->row_array();
        return $row;
    }
    public function updatecircle($arr,$circle_id)
    {
        $this->db->where('circle_id',$circle_id);
        $ret = $this->db->update($this->table,$arr);
        return $ret;
    }
    public function circlestate($circle_id,$state)
    {
        $this->db->where('circle_id',$circle_id);
        $arr=array('state'=>$state);
        $ret = $this->db->update($this->table,$arr);
        return $ret;
    }
    public function getcirclebyname($circle_name,$limit,$page)
    {
        $this->db->like('circle_name',$circle_name,'both');
        $this->db->limit($limit,$page*$limit);
        $query = $this->db->get($this->table);
        $row = $query->result_array();
        return $row;
    }
    public function getcirclelist($limit,$page)
    {
        $this->db->limit($limit,$page*$limit);
        $query  = $this->db->get($this->table);
        return $query->result_array();
        /* $this->db->query('select * from '); */
    }
}
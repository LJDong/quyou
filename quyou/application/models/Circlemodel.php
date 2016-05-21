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
        return $ret;
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
    public function getcirclelist($page)
    {
        /* $this->db->query('select * from '); */
    }
}
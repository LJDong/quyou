<?php
class circlefocusmodel extends CI_Model
{
    private $table ='circle_focus';
    public function __construct()
    {
        $this->load->database();
    }
    public function insert($arr)
    {
        $ret = $this->db->insert($this->table,$arr);
        return $ret;
    }
    public function select($arr)
    {
        $this->db->where($arr[0],$arr[1]);
        $query = $this->db->get($this->table);
        $arr = $query->result_array();
        return $arr;
    }
    public function update($arr,$byarr)
    {
        $this->db->where($byarr[0],$byarr[1]);
        $ret = $this->db->update($this->table,$arr);
        return $ret ;
    }
}
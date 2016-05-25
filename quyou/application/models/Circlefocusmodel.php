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
    public function delete($arr)
    {
        $this->db->where($arr[0],$arr[1].$arr[2],$arr[3]);
        $ret = $this->db->delete($this->table);
        return $ret;
    }
    public function select($arr)
    {
        $this->db->where($arr[0],$arr[1]);
        $this->db->from($this->table);
        $this->db->join("circle","circle.circle_id = circle_focus.cf_circle_id");
        $query = $this->db->get();
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
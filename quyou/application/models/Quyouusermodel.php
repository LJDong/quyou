<?php
class quyouusermodel extends CI_Model
{
    private $table = 'quyouusers';
    public function __construct()
    {
        $this->load->database();
    }
    /* @param array 注册信息
     * @return bool
     * 注册
     *  */
    public function  register($userinfoarr)
    {
        $ret = $this->db->insert($this->table,$userinfoarr);
        return $ret;
    }
    public function login($email)
    {
        $sql = "select * ,count(*) as count from quyouusers where user_email=?";
        $binds = array($email);
        $ret = $this->db->query($sql,$binds);
        $row = $ret->row();
        if($row->count == 0)
        {
            return array('ret'=>false,'password'=>null,'name'=>null);
       }
       else{
           return array('ret'=>true,"password"=>$row->user_password,'name'=>$row->user_name,'user_id'=>$row->user_id);
       }
    }
    public function verityregister($email)
    {
        $sql  = "select count(*) as count from quyouusers where user_email=?";
        $binds = array($email);
        $ret = $this->db->query($sql,$binds);
        $row = $ret->row();
        if($row->count == 0)
        {
            return true;
        }
        else{
            return false;
        }
    }
    public function update($arr,$warr)
    {
        $this->db->where($warr[0],$warr[1]);
        $ret = $this->db->update($this->table,$arr);
        return $ret;
    }
    public function select($arr)
    {
        $this->db->select("user_id,user_name,user_email,user_register_time,user_logo_url,user_sex");
        $this->db->where($arr[0],$arr[1]);
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
}
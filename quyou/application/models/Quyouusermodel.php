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
}
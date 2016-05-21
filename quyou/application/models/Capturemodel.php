<?php
class capturemodel extends CI_Model
{
    private $table="capture";
    public function __construct()
    {
        $this->load->database();
        $this->load->helper("captcha");
    }
    public function veritycapture($word,$ip)
    {
        $expiration = time() - 7200;
        $this->db->where('capture_time < ',$expiration)
            ->delete('capture');
        $sql = 'select count(*) as count from capture where word = ? and ip_address = ? and capture_time > ? ';
        $binds = array($word,$ip,$expiration);
        $query = $this->db->query($sql,$binds);
        $row = $query->row();
        if($row->count == 0)
        {
            return false;
        }
        else {
            return true;
        }
    }
    public function insertcapture($arr)
    {
        $ret = $this->db->insert($this->table,$arr);
        return $ret;
    }
    public function getcapture($ip_addresss)
    {
        $vals = array(
            'img_path'=>'./aplugin/capture/',
            'img_url'=>'http://quyou.com/aplugin/capture',
            'word_length'=>6,
            'img_width'=>'100',
            'font_size'=> 20,
            'expiration'=> 300
        );
        $captureret = create_captcha($vals);
        $arr = array(
            'capture_time'=>$captureret['time'],
            'ip_address'=>$ip_addresss,
            'word'=>$captureret['word']
        );
        $dbret = $this->insertcapture($arr);
        $ret = $captureret;
        $ret['dbret'] = $dbret;
        return $ret;
    }
}
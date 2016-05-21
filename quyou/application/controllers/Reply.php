<?php
class reply  extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->library('session');
    }
    public function toreply()
    {
        var_dump($_POST);
    }
}
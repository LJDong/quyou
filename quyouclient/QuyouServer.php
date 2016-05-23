<?php
class QuyouServer
{
    private $serv;
    function __construct()
    {
        $this->serv = new swoole_websocket_server("0.0.0.0",9501);
        $this->serv->set(array(
            'worker_num'=>4,
            //'daemonize'=>1,
            "max_request"=>10000,
            "dispatch_mode"=>2,
            "task_woker_num"=>8,
            "task_ipc_mode"=>3
        ));
        $this->serv->on("message",array($this,'onMessage'));
        $this->serv->on('Task',array($this,'onTask'));
        $this->serv->start();
    }
    public function onMessage(swoole_websocket_server $serv,$frame)
    {
        var_dump($frame);
        $serv->push($frame->fd,'hello, i am server');
    }
    public function onTask(swoole_server $serv,  $task_id,  $from_id,  $data)
    {
        
    }
}

<?php
class QuyouServer
{
    private $serv;
    private $redis;
    function __construct()
    {
        $this->redis = new  Redis();
        $this->redis->connect("127.0.0.1", 6379);
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
        $this->serv->on('close', function ($ser, $fd) {
            $user_id = $this->redis->hget('logoutlist',$fd);
            $this->redis->hdel('logoutlist',$fd);
            if($user_id != null)
            {
                $fd = $this->redis->hget('loginlist',$user_id);
                $this->redis->hdel('loginlist',$fd);
            }
        });
        $this->serv->start();
    }
    public function onMessage(swoole_websocket_server $serv,$frame)
    {
        //$serv->push($frame->fd,'hello, i am server');
        $clientmessage =  json_decode($frame->data,true);
        $header =$clientmessage['header'];
        if($header == 'login')
        {
            $isol = $this->redis->hset('loginlist',$clientmessage['user_id'],$frame->fd);
            $this->redis->hset('logoutlist',$frame->fd,$clientmessage['user_id']);
            if($isol == true)
            {
                //处理推送未读信息数量
            }
        }
        else if($header == 'receviemessage')
        {
            $from_user_id = $clientmessage['from_user_id'];
            $to_user_id =  $this->redis->hget('logoutlist',$frame->fd);
            if($from_user_id != null)
            {
                while($this->redis->lSize('from'.$from_user_id.'to'.$to_user_id)>0)
                {
                    $chat = $this->redis->lPop('from'.$from_user_id.'to'.$to_user_id);
                    $this->serv->push($frame->fd, $chat);
                    $this->redis->decr('numfrom'.$from_user_id.'to'.$to_user_id);
                }
            }
        }
        else if($header == 'sendmessage')
        {
            var_dump($clientmessage);
            $user_id =  $this->redis->hget('logoutlist',$frame->fd);
            $to_user_id = $clientmessage['to_user_id'];
            $user_name = $clientmessage['user_name'];
            $content = $clientmessage['content'];
            $arr= array(
                'header'=>'chat',
                'user_name'=>$user_name,
                'content'=>$content,
                'user_id'=>$user_id
            );
            $jsonret = json_encode($arr);
            $fd = $this->redis->hget('loginlist',$to_user_id);
            //收到消息先存入未读信息和未读信息数量
            $this->redis->rPush('from'.$user_id.'to'.$to_user_id,$jsonret);
            $this->redis->incr('numfrom'.$user_id.'to'.$to_user_id);
            
            //如果对方在线，查询用户zhuangtai
            if($fd != null)
            {
                //c查询用户状态并推送数量
                $num = $this->redis->get('numfrom'.$user_id.'to'.$to_user_id);
                $this->serv->push($fd, json_encode(array('header'=>'num','num'=>$num,'from_user_id'=>$user_id,'user_name'=>$user_name)));
                $this->serv->push($fd, json_encode(array('header'=>'state')));
            }
        }
    }
    public function onTask(swoole_server $serv,  $task_id,  $from_id,  $data)
    {
        
    }
}

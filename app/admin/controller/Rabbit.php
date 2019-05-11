<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 18:13
 */

namespace app\admin\controller;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use think\Controller;

class Rabbit extends Controller
{
    protected $connection;
    protected $exchangeName = 'demo';
    protected $queueName = 'task_queue';
    protected $routeKey = 'task_queue';
    protected $message = 'chenyang';
    public function __construct()
    {
        $arr = [
            'host'=>'localhost',
            'port'=>'5672',
            'vhost'=>'/',
            'login'=>'guest',
            'password'=>'guest'
        ];
        $this->connection = new \AMQPConnection($arr);
        $this->connection->connect() or die('Cannot connect to the broker!');
    }

    public function publisher(){
        try{
            //创建渠道
            $channel = new \AMQPChannel($this->connection);
            //创建交换器
            $exchange = new \AMQPExchange($channel);
            $exchange->setName($this->exchangeName);
            //创建队列
            $queue = new \AMQPQueue($channel);
            $queue->setName($this->queueName);
            $queue->setFlags(AMQP_DURABLE);
            $queue->declareQueue();
            //交换器发送信息到对应路由
            $exchange->publish($this->message,$this->routeKey);
            var_dump('Send '.$this->message);
        }catch (\AMQPConnectionException $e){
            var_dump($e);
        }
        $this->connection->disconnect();
    }

    public function customer()
    {
        try{
            //创建渠道
            $channel = new \AMQPChannel($this->connection);
            //创建交换器
            $exchange = new \AMQPExchange($channel);
            $exchange->setName($this->exchangeName);
            $exchange->setType(AMQP_EX_TYPE_DIRECT);
            $exchange->declareExchange();
            //创建队列
            $queue = new \AMQPQueue($channel);
            $queue->setName($this->queueName);
            $queue->setFlags(AMQP_DURABLE);
            $queue->declareQueue();
            //队列绑定到对应交换器的路由
            $queue->bind($this->exchangeName,$this->routeKey);
            var_dump('Waiting for message');
            //回调函数
            $callback = function ($envelope,$queue){
                $msg = $envelope->getBody();
                sleep(substr_count($msg,'.'));
                $queue->nack($envelope->getDeliveryTag());
            };
            while (true){
                $queue->consume($callback);
                $channel->qos(0,1);
            }
        }catch (\AMQPConnectionException $e){
            var_dump($e);
        }

    }

}
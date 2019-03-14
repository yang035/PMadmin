<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/13
 * Time: 18:35
 */

namespace app\common\controller;


use think\Controller;

/**
 * 区块结构
 */
class Block extends Controller
{
    private $index;
    private $timestamp;
    private $data;
    private $previous_hash;
    private $random_str;
    private $hash;

    public function __construct($index, $timestamp, $data, $random_str, $previous_hash)
    {
        $this->index = $index;
        $this->timestamp = $timestamp;
        $this->data = $data;
        $this->previous_hash = $previous_hash;
        $this->random_str = $random_str;
        $this->hash = $this->hash_block();
    }

    public function __get($name)
    {
        return $this->$name;
    }

    private function hash_block()
    {
        $str = $this->index . $this->timestamp . $this->data . $this->random_str . $this->previous_hash;
        return hash("sha256", $str);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/13
 * Time: 18:39
 */

namespace app\admin\controller;


class Block
{
    public function index()
    {
        header("Content-type:text/html;charset=utf-8");
        //生成第一个区块
        $blockchain = [create_genesis_block()];
        //模拟生成其他区块,我们直接循环生成。实际中，还需要跟踪互联网上多台机器上链的变化,像比特币会有工作量证明等算法，达到条件了才生成区块等
        //我们的链是一个数组，实际生产中应该保存下来
        $previous_block = $blockchain[0];
        for ($i = 0; $i <= 10; $i++) {
            if (!($new_block = dig($previous_block))) {
                continue;
            }
            $blockchain[] = $new_block;
            $previous_block = $new_block;

            //告诉大家新增了一个区块
            echo "区块已加入链中.新区块是 : {$new_block->index}<br/>";
            echo "新区块哈希值是 : {$new_block->hash}<br/>";
            print_r($new_block);
            echo "<br/><br/>";
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16
 * Time: 14:33
 */

namespace app\common\service;


class RedisService extends Service
{
    // 服务器连接句柄
    private $_linkHandle = array();
    private $isSlave;
    // 1:强制连主库  2强制连从库 3根据配置
    public $forceMaster = 3;
    protected $configname;

    /**
     * 构造函数
     */
    public function __construct($config = null)
    {
        $this->configname = config('redis.redis_new_config');
    }

    /**
     * 得到 Redis 原始对象可以有更多的操作
     * @param $key
     * @return redis object
     */
    public function getRedis($key, $global = false)
    {
        $this->forceMaster == 2 && $this->isSlave = false;
        if (!$this->isSlave || $this->forceMaster == 1) {
            return $this->connectNew("master");
        } else {
            return $this->connectNew("slave");
        }
    }
    /**
     * 连接服务器,注意：这里使用长连接，提高效率，但不会自动关闭
     * @param array $config Redis服务器配置
     * @return boolean
     */
    public function t_connect($config, $i)
    {
        $this->_linkHandle[$i] = new \Redis();
        $ret = $this->_linkHandle[$i]->pconnect($config['host'], $config['port']);
        $this->_linkHandle[$i]->auth($config['pwd']);
        return $ret;
    }

    public function selectdb($key,$num = 0)
    {
        return $this->getRedis($key)->select($num);
    }

    public function connectNew($des = "master")
    {
        $config = $this->configname;

        if (!isset($config['slave'])) {
            $des = 'master';
        }
        if ($des == 'slave') {
            $slave = $config['slave'];
            if (count($slave) != count($slave, 1)) {//二维数组
                $rand = array_rand($slave);
                $des = 'slave_' . $rand;
                $config[$des] = $slave[$rand];
            }
        }

        if (isset($this->_linkHandle[$des])) {
            return $this->_linkHandle[$des];
        }

        $this->_linkHandle[$des] = new \Redis();
        $ret = $this->_linkHandle[$des]->connect($config[$des]['host'], $config[$des]['port'], 3);
        if ($config[$des]['pwd']) {
            $this->_linkHandle[$des]->auth($config[$des]['pwd']);
        }

        if (!$ret) {
            $this->_linkHandle[$des] = false;
        }
        //$this->_linkHandle[$des]->select(0);
        return $this->_linkHandle[$des];
    }

    public function t_multi($key)
    {
        // 是否一次取多个值
        return $this->getRedis($key)->multi($key);
    }

    public function t_exec()
    {
        return $this->getRedis()->exec();
    }

    /**
     * 关闭连接
     *
     * @param int $index all是全部关闭，数字则为关闭具体哪个
     */
    public function close($index)
    {
        /* if ($index == 'all')
        {
            //得到对象个数
            $cnt = count($this->_linkHandle);

            for ($i = 0; $i < $cnt; $i++)
            {
                $this->_linkHandle[$i]->close();
            }
        }
        else
        {
            $this->_linkHandle[$index]->close();
        } */
    }

    /**
     * 写缓存
     * @param string $key 组存KEY
     * @param string $value 缓存值
     * @param int $expire 过期时间， 0:表示无过期时间
     */
    public function t_set($key, $value, $expire = 0)
    {
        // 永不超时
        if ($expire == 0) {
            $ret = $this->getRedis($key)->set($key, $value);
        } else {
            $ret = $this->getRedis($key)->setex($key, $expire, $value);
        }
        return $ret;
    }

    /**
     * 读缓存
     *
     * @param string $key 缓存KEY,支持一次取多个 $key = array('key1','key2')
     * @return string || boolean  失败返回 false, 成功返回字符串
     */
    public function t_get($key)
    {
        // 是否一次取多个值
        $func = is_array($key) ? 'mGet' : 'get';
        return $this->getRedis($key)->{$func}($key);
    }

    /**
     * 条件形式设置缓存，如果 key 不存时就设置，存在时设置失败
     *
     * @param string $key 缓存KEY
     * @param string $value 缓存值
     * @return boolean
     */
    public function t_setnx($key, $value)
    {
        return $this->getRedis($key)->setnx($key, $value);
    }

    /**
     * 删除缓存
     *
     * @param string || array $key 缓存KEY，支持单个健:"key1" 或多个健:array('key1','key2')
     * @return int 删除的健的数量
     */
    public function t_remove($key)
    {
        return $this->getRedis($key)->del($key);
    }

    /**
     * 值加加操作,类似 ++$i ,如果 key 不存在时自动设置为 0 后进行加加操作
     *
     * @param string $key 缓存KEY
     * @param int $default 操作时的默认值
     * @return int　操作后的值
     */
    public function t_incr($key, $default = 1)
    {
        if ($default == 1) {
            return $this->getRedis($key)->incr($key);
        } else {
            return $this->getRedis($key)->incrBy($key, $default);
        }
    }

    /**
     * 值减减操作,类似 --$i ,如果 key 不存在时自动设置为 0 后进行减减操作
     * @param string $key 缓存KEY
     * @param int $default 操作时的默认值
     * @return int　操作后的值
     */
    public function t_decr($key, $default = 1)
    {
        if ($default == 1) {
            return $this->getRedis($key)->decr($key);
        } else {
            return $this->getRedis($key)->decrBy($key, $default);
        }
    }

    /**
     * 设置一个key的过期时间
     * @param $key
     * @param $ttl
     */
    public function t_expire($key, $ttl)
    {
        return $this->getRedis($key)->expire($key, $ttl);
    }

    /**
     * 判断键是否存在
     *
     * @param string $key 缓存KEY
     * @return int　操作后的值，若 key 存在，返回 1 ，否则返回 0
     */
    public function t_exists($key)
    {
        return $this->getRedis($key)->exists($key);
    }

    /**
     * 返回模糊指定的key键名
     * @param string $keyword 关键字，通配符用*代表，如果关键字为*，那么是全局查询
     */
    public function t_keys($key,$keyword)
    {
        if (strstr($keyword, '*')) {
            $rs = array();

            //循环处理每个连接对象的值
            foreach ($this->_linkHandle as $v) {
                //dump($v->keys($keyword));
                $rs = array_merge($rs, $v->keys($keyword));
            }
            return $rs;
        } else {
            return $this->getRedis($key)->keys($keyword);
        }
    }

    /**
     * 重命名键，如果新键存在，则覆盖
     * @param string $key
     * @param string $newKey
     */
    public function t_rename($key, $newKey)
    {
        return $this->getRedis($key)->rename($key, $newKey);
    }

    /**
     * 重命名键，如果新键存在，则返回0
     * @param string $key
     * @param string $newKey
     */
    public function t_renamenx($key, $newKey)
    {
        return $this->getRedis($key)->renamenx($key, $newKey);
    }

    //------------------队列--------------
    /**
     * 列表插入 入栈
     */
    public function t_lPush($key, $value)
    {
        return $this->getRedis($key)->lPush($key, $value);
    }

    /**
     * 从尾部插入
     */
    public function t_rPush($key, $value)
    {
        return $this->getRedis($key)->rPush($key, $value);
    }

    /**
     * 从头部添加一个元素如果value存在 不添加
     */
    public function t_lPushx($key, $value)
    {
        return $this->getRedis($key)->lPushx($key, $value);
    }

    /**
     * 从尾部添加一个元素 如果value存在 不添加
     */
    public function t_rPushx($key, $value)
    {
        return $this->getRedis($key)->rPushx($key, $value);
    }

    /**
     * 从头弹出列表一个元素 并删除该元素
     */
    public function t_lPop($key)
    {
        return $this->getRedis($key)->lPop($key);
    }

    public function __call($method, $args)
    {
        $slave = array('get' => 1, 'exists' => 1, 'keys' => 1, 'lsize' => 1, 'lget' => 1, 'llen' => 1, 'lrange' => 1, 'ltrim' => 1, 'lrem' => 1, 'sismember' => 1, 'smembers' => 1, 'scard' => 1, 'hget' => 1, 'hgetall' => 1, 'hexists' => 1, 'hlen' => 1, 'hvals' => 1, 'ttl' => 1, 'hmGet' => 1);
        $method = strtolower($method);
        if (isset($slave[$method])) {
            $this->setSlave(true);
        } else {
            $this->setSlave(false);
        }

        if (method_exists($this, 't_' . $method)) {
            $method = "t_" . $method;
            return call_user_func_array(array($this, $method), $args);
        }
    }

    public function setSlave($isSlave)
    {
        $this->isSlave = $isSlave;
    }

    /**
     * 从尾弹出列表一个元素 并删除该元素
     */
    public function t_rPop($key)
    {
        return $this->getRedis($key)->rPop($key);
    }

    /**
     * 返回列表元素个数
     */
    public function t_lSize($key)
    {
        return $this->getRedis($key)->lSize($key);
    }

    /**
     * 返回列表元素个数
     */
    public function t_llen($key)
    {
        return $this->getRedis($key)->llen($key);
    }

    /**
     * 返回名称为key的list中index位置的元素
     */
    public function t_lGet($key, $index = 0)
    {
        return $this->getRedis($key)->lIndex($key, $index);
    }

    /**
     * 设置名称为key的list中index位置的元素值为value
     */
    public function t_lSet($key, $index, $value)
    {
        return $this->getRedis($key)->lSet($key, $index, $value);
    }

    /**
     * 返回名称为key的list中start至end之间的元素（end为 -1 ，返回所有）
     */
    public function t_lRange($key, $start, $end = '-1')
    {
        return $this->getRedis($key)->lRange($key, $start, $end);
    }

    /**
     * 截取名称为key的list，保留start至end之间的元素
     */
    public function t_lTrim($key, $start, $end)
    {
        return $this->getRedis($key)->lTrim($key, $start, $end);
    }

    /**
     * 删除count个名称为key的list中值为value的元素。count为0，删除所有值为value的元素，count>0从头至尾删除count个值为value的元素，count<0从尾到头删除|count|个值为value的元素
     */
    public function t_lRem($key, $start=0, $end='-1')
    {
        return $this->getRedis($key)->lRem($key, $start, $end);
    }
    //-------------------集合-------------
    /**
     * 往集合中添加值
     * @param string $key
     * @param string $value
     */
    public function t_sadd($key, $value)
    {
        return $this->getRedis($key)->sadd($key, $value);
    }

    /**
     * 名称为key的集合中查找是否有value元素，有ture 没有 false
     */
    public function t_sIsMember($key, $value)
    {
        return $this->getRedis($key)->sIsMember($key, $value);
    }

    /**
     * 删除名称为key的set中的元素value
     */
    public function t_sRem($key, $value)
    {
        return $this->getRedis($key)->sRem($key, $value);
    }

    /**
     * 返回集合中所有的元素
     * @param string $key
     */
    public function t_smembers($key)
    {
        return $this->getRedis($key)->smembers($key);
    }

    /**
     * 返回集合元素个数
     */
    public function t_scard($key)
    {
        return $this->getRedis($key)->scard($key);
    }

    public function t_sunion($key)
    {
        return $this->getRedis($key)->sUnion($key);
    }

    //-------------------HASH---------------
    /**
     * 添加元素
     * @param string $key
     * @param string $field
     * @param string $value
     */
    public function t_hset($key, $field, $value)
    {
        $ret = $this->getRedis($key)->hset($key, $field, $value);
        return $ret;
    }

    /**
     * 获取元素
     * @param unknown $key
     * @param unknown $field
     */
    public function t_hget($key, $field)
    {
        return $ret = $this->getRedis($key)->hget($key, $field);
    }

    public function t_hmGet($key, $array)
    {
        return $this->getRedis($key)->hmGet($key, $array);
    }

    public function t_hmSet($key, $array)
    {
        return $this->getRedis($key)->hmSet($key, $array);
    }

    /**
     * 返回名称为key的hash中所有的键（field）及其对应的value
     * @param unknown $key
     */
    public function t_hGetAll($key)
    {
        return $ret = $this->getRedis($key)->hGetAll($key);
    }

    /**
     * 将名称为key的field的value增加2
     * @param unknown $key
     * @param unknown $field
     */
    public function t_hIncrBy($key, $field, $val = 1)
    {
        return $ret = $this->getRedis($key)->hIncrBy($key, $field, $val);
    }

    /**
     * 删除名称为key的hash中键为field的域
     * @param unknown $key
     * @param unknown $field
     */
    public function t_hDel($key, $field)
    {
        return $ret = $this->getRedis($key)->hDel($key, $field);
    }

    /**
     * 指定的字段是否存在
     * @param string $key
     * @param string $field
     * @return bool
     */
    public function t_hexists($key, $field)
    {
        $ret = $this->getRedis($key)->hexists($key, $field);
        return $ret;
    }

    /**
     * 返回表内总记录数
     * @param int $key
     * @return int
     */
    public function t_hlen($key)
    {
        $ret = $this->getRedis($key)->hlen($key);
        return $ret;
    }

    /**
     * 返回所有的记录的值
     * @param int $key
     * @return array
     */
    public function t_hvals($key)
    {
        $ret = $this->getRedis($key)->hvals($key);
        return $ret;
    }

    /**
     * 添空当前数据库
     * @param int $index all是全部清空，数字则为清空具体哪个
     * @return boolean
     */
    public function t_clear($index)
    {
        if ($index == 'all') {
            //得到对象个数
            $cnt = count($this->_linkHandle);

            for ($i = 0; $i < $cnt; $i++) {
                $this->_linkHandle[$i]->flushDB();
            }
        } else {
            $this->_linkHandle[$index]->flushDB();
        }
    }

    /**
     * 返回给定key的剩余生存时间(time to live)(以秒为单位)
     */
    public function t_ttl($key)
    {
        return $this->getRedis($key)->ttl($key);
    }

    public function t_hscan($key, $cusor, $limit = 100, $pattern = '*')
    {
        return $this->getRedis($key)->hscan($key, $cusor, "MACTH $pattern", "COUNT $limit");
    }

    /**
     *  例用比特位 标示id value设置状态
     */
    public function t_setbit($key, $offset, $value = 0)
    {
        return $this->getRedis($key)->setbit($key, $offset, $value);
    }

    /**
     * 获取某一位上的状态值
     */
    public function t_getbit($key, $offset)
    {
        return $this->getRedis($key)->getbit($key, $offset);
    }

    /**
     * 获取该bitmap上的所有为1的值 0到-1表示取所有 注意单位为字节 比如  是（ 0 0 是统计0-8位的结果） （0 1是 0-16位的结果）使用时需要合理计算转换一下
     */
    public function t_bitcount($key, $start = 0, $end = -1)
    {
        return $this->getRedis($key)->bitcount($key, $start, $end);
    }

}
<?php
class RedisClient
{
  /**
   * @var string 全局
   */
  const NONE = 'global:';

  /**
   * @var string 下载站
   */
  const WEB = 'web:';

  /**
   * @var string pc端
   */
  const PC = 'pc:';

  /**
   * @var string 手机站
   */
  const M = 'm:';

  /**
   * @var string 手机客户端市场
   */
  const MOBILE = 'mobile:';

  /**
   * @var Redis the redis's instnce
   */
  private static $_redis;

  /**
   * @var string 标识
   */
  private static $_mark;

  /**
   * 获取redis对象，默认全局标识
   * @params string $mark 标识，各个客户端私有key分组
   * @return Redis|false 如果获取不到redis返回false
   */
  public static function getInstance($mark = self::NONE)
  {
    if (!self::$_redis)
    {
      $config = Yii::app()->params['redis'];
      if (!isset($config['host']))
      {
        $config['host'] = '127.0.0.1';
        $config['port'] = 6379;
        $config['timeout'] = 3;
      }
      try {
        $redis = new Redis();
        if (!($redis->connect($config['host'], $config['port'], $config['timeout']) && $redis->auth($config['password'])))
          throw new RedisException('链接redis服务器失败！');
        //!$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP)
        if (!$redis->setOption(Redis::OPT_PREFIX, $mark))
          throw new RedisException('配置redis失败！');

        self::$_redis = $redis;
        self::$_mark  = $mark;
      } catch (RedisException $e) {
        if (CommonHelper::isDev()) throw $e;
        else @file_put_contents('/tmp/redis.error', date('Y-m-d H:i:s') . "\t" . $e->getMessage() . "\n", FILE_APPEND);
        return false;
      }
    }
    if ($mark != self::$_mark) self::setMark($mark);
    return self::$_redis;
  }

  /**
   * 设置redis标识
   * @params string $mark 标识
   */
  public static function setMark($mark)
  {
    if (!self::$_redis->setOption(Redis::OPT_PREFIX, $mark))
      throw new RedisException('配置redis失败！');
  }

  /**
   * 关闭redis
   */
  public static function close()
  {
    if (self::$_redis) {
      self::$_redis->close();
      self::$_redis = null;
      self::$_mark  = null;
    }
  }
}
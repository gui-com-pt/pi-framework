<?hh

namespace Pi\Redis;

use Pi\Interfaces\IContainable,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\HydratorFactoryInterface,
    Pi\Interfaces\ISerializerService,
    Pi\Redis\Interfaces\IRedisClient;




class RedisClient extends \Redis implements IContainable, IRedisClient {

  public $client;
  private $socket;

  public function ioc(IContainer $container){}

  public function __construct(
    protected ISerializerService $serializer,
    protected HydratorFactoryInterface $hydratorFactory,
    protected string $hostname = 'localhost', int $port = 6067)
  {
    $this->client =  new \Redis();
    $this->client->connect($hostname);
  }

  public function begin()
  {

  }

  public function connect()
  {

  }

  public function expire(string $key, int $seconds) : void
  {
    $this->client->expire($key, $seconds);
  }

  public function get($key)
  {
    return $this->client->get($key);
  }

  public function getAs(string $key, string $className)
  {
    if($className == 'array') {
      return $this->serializer->unserialize($this->get($key));
    }
    $data = $this->get($key);
    if($data == null)
      return null;
    
    $hydrated = $this->serializer->unserialize($data);

    return $this->hydratorFactory->getInstanceOf($className, $hydrated);
  }

  public function set($key, $value)
  {
    if(is_scalar($value)) {
      $this->client->set($key, $value); //ini_get('session.gc_maxlifetime')
    } else {
      $hydrator = $this->hydratorFactory->getHydrator(get_class($value));
      $data = $hydrator->extract($value);
      return $this->client->set($key, $this->serializer->serialize($data)); //ini_get('session.gc_maxlifetime')
    }
  }

  public function sadd($set, $value)
  {
    return $this->client->sadd($set, $this->serializer->serialize($value));
  }

  public function smembers($set)
  {
    return $this->client->smembers($set);
  }

  public function hset(string $hash, string $field, $value)
  {
    return $this->client->hset($hash, $field, $this->serializer->serialize($value));
  }

  public function hgetAll(string $hash)
  {
    return $this->client->hgetall($hash);
  }

  public function hget(string $hash, string $key)
  {
    return $this->client->hget($hash, $key);
  }

  public function incr(string $key, $incryBy = 1)
  {
    return $this->client->incr($key, $incryBy);
  }

  public function lpush(string $key, $value)
  {
    return $this->client->lpush($key, $this->serializer->serialize($value));
  }

  public function lpop(string $key)
  {
    return $this->client->lpop($key);
  }

  public function llen(string $key)
  {
    return $this->client->llen($key);
  }

  public function rpush(string $key, $value)
  {
    return $this->client->rpush($key, $this->serializer->serialize($value));
  }

  public function lrange(string $key, int $start, int $stop)
  {
    return $this->client->lrange($key, $start, $stop);
  }

  public function gc($maxlifetime) 
  {
      return 0; // Handled by $redis->set(..., ini_get('session.gc_maxlifetime'))
  }

  public function delete(string $key)
  {
    return $this->client->delete($key);
  }
  
  public function del(string $key)
  {
    return $this->client->del($key);
  }

  public function srem(string $set, $key)
  {
    return $this->client->srem($set, $key);
  }

  public function rpoplpush(string $origin, $destination)
  {
    return $this->client->rpoplpush($origin, $destination);
  }

  public function client()
  {
    return $this->client;
  }
}
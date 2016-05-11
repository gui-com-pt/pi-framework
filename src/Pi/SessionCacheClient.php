<?hh

namespace Pi;

use Pi\Interfaces\ISession;
use Pi\Interfaces\ICacheProvider;

class SessionCacheClient implements ISession {

  protected $prefixNs;

  public function __construct(protected ICacheProvider $cacheClient, string $sessionId)
  {
      $this->prefixNs = 'sess::' . $sessionId . ':';
  }

  public function get(string $key)
  {
    return $this->cacheClient->get($this->prefixNs . $key);
  }

  public function set(string $key, string $value)
  {
    $this->cacheClient->set($this->prefixNs . $key, $value);
  }
}

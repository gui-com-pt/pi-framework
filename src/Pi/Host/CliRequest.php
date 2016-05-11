<?hh

namespace Pi\Host;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\IContainer;
use Pi\HttpMethod;

class CliRequest extends BasicRequest implements IRequest {

  protected $httpMethod;

  protected $xRealIp;

  protected $headers;

  protected Map<string,string> $parameters = Map{};

  protected $requestUri;

  protected $queryString;

  protected $scriptName;

  protected $physicalPath;

  protected $httpProtocol;

  protected $rawInput;

  public function __construct(protected CliResponse $httpResponse)
  {
    parent::__construct();
    switch($argv[0]) {
      case 'service':
        $this->executeService();
        break;
    }
    foreach ($argv as $arg) {

      $e=explode("=",$arg);
      if(count($e)==2)
          $_GET[$e[0]]=$e[1];
      else
          $_GET[$e[0]]=0;
    }
    // The HTTP request method
    $this->httpMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
    if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR'] != ''){
      $this->xRealIp = $_SERVER['REMOTE_ADDR'];
    }

    // The query string like  "test=abc" or ""
    $this->queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    $this->requestUri = $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/'; // <-- "/foo/bar?test=abc" or "/foo/index.php/bar?test=abc"
    $this->scriptName = $scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''; // <-- "/foo/index.php"

    $physicalPath = '';
    if (strpos($this->requestUri, $this->scriptName) !== false) {
        $physicalPath = $this->scriptName; // <-- Without rewriting
    } else {
        $physicalPath = str_replace('\\', '', dirname($this->scriptName)); // <-- With rewriting
    }
    $this->physicalPath = rtrim($physicalPath, '/'); // <-- Remove trailing slashes



    //Input stream (readable one time only; not available for multipart/form-data requests)
    $rawInput = @file_get_contents('php://input');
    if (!$rawInput) {
        $rawInput = '';
    }
    $this->rawInput = $rawInput;

    $this->httpProtocol = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';

    $route = HostProvider::routesManager()->get($this->requestUri);
    $parts = parse_url($this->requestUri);
    $this->parameters = Map {};
    if(array_key_exists('query', $parts))
    {
      parse_str($parts['query'], $p);
      if(is_array($p)) {
        foreach($p as $key => $value) {
          $this->parameters->add(Pair{$key, $value});
        }
      }
    }
    if($route === null || $route->params() === null) {

    } else {
      foreach($route->params() as $k => $v)
      $this->parameters->add(Pair { $k, $v});
    }

  }

  public function isPost()
  {
    return $this->httpMethod === HttpMethod::POST;
  }

  public function isGet()
  {
    return $this->httpMethod === HttpMethod::GET;
  }

  public function isPut()
  {
    return $this->httpMethod === HttpMethod::PUT;
  }

  public function isDelete()
  {
    return $this->httpMethod === HttpMethod::DELETE;
  }

  public function isPatch()
  {
    return $this->httpMethod === HttpMethod::PATCH;
  }

  public function isOptions()
  {
      return $this->httpMethod === HttpMethod::OPTIONS;
  }

  public function isHead()
  {
    return $this->httpMethod === HttpMethod::HEAD;
  }

  public function isAjax()
  {
    if($this->parameters->contains('ajax')) {
      return true;
    } else if($this->headers->contains('X_REQUESTED_WITH') && $this->headers->get('X_REQUESTED_WITH') === 'XMLHttpRequest'){
      return true;
    } else {
      return false;
    }
  }
  public function headers() : Map<string,string>
  {
    return $this->headers;
  }

  public function parameters()
  {
    return $this->parameters;
  }

  public function requestUri()
  {
    return $this->requestUri;
  }

  public function queryString()
  {
    return $this->queryString;
  }

  public function physicalPath()
  {
    return $this->physicalPath;
  }



  public function httpProtocol() : string
  {
    return $this->httpProtocol;
  }

  public function rawInput()
  {
    return $this->rawInput;
  }

  protected function handleHeaders()
  {

    //$headers = apache_request_headers();
    $headers = $_REQUEST;
    $this->headers = Map {};
    foreach($headers as $key => $value) {
      $this->headers[$key] = $value;
    }

  }

  public function httpResponse() : IResponse
  {
    return $this->httpResponse;
  }

  public function httpMethod() : string
  {
    return $this->httpMethod;
  }

  /**
   * The value of the X-Real-IP header, null if null or empty
   */
  public function xRealIp() : ?string
  {
    return $this->xRealIp;
  }
}

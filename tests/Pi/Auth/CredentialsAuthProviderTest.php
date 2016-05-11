<?hh
namespace Test\Auth;


use Mocks\MockHostConfiguration,
    Mocks\BibleHost,
    Mocks\HttpRequestMock,
    Test\Auth\BaseAuthTest,
    Pi\SessionPlugin,
    Pi\SessionFactory,
    Pi\HostConfig,
    Pi\ServiceModel\BasicRegisterRequest,
    Pi\ServiceModel\BasicRegisterResponse,
    Pi\Common\RandomString,
    Pi\Auth\AuthService,
    Pi\Auth\Md5CryptorProvider,
    Pi\Auth\Authenticate,
    Pi\Auth\CredentialsAuthProvider,
    Pi\Auth\AuthUserSession;




class CredentialsAuthProviderTest extends BaseAuthTest {


  protected $provider;

  protected $authSvc;

  protected $host;

  public function setUp()
  {
    $this->host = new BibleHost();
    $this->host->init();
    $this->authSvc = $this->host->serviceController()->getServiceInstance('Pi\Auth\AuthService');
    $this->provider = $this->authSvc->getAuthProvider(CredentialsAuthProvider::name);
  }

  public function testServiceAcceptTheProvider() {
    $this->assertNotNull($this->provider);
  }

  public function testCanAuthenticate()
  {
    $authRepo = $this->getAuthRepository();
    $cryptor = $this->getCryptor();
    $user = $this->createUserAuth();
    $userDb = $authRepo->createUserAuth($user, $cryptor->encrypt('123'));
    
    $session = $this->authSvc->getSession();
    $this->assertFalse($session->isAuthenticated());

    $request = new Authenticate();
    $request->setUserName($user->getEmail());
    $request->setPassword('123');
    $session = $this->createAuthUserSession();
    $res = $this->provider->authenticate($this->authSvc, $session, $request);
    $this->assertEquals($res->getDisplayName(), $userDb->getDisplayName());
    
    $session = $this->authSvc->getSession();
    $this->assertTrue($session->isAuthenticated());
  }

  public function testSetCookiesOnAuthenticated()
  {
    $authRepo = $this->getAuthRepository();
    $cryptor = $this->getCryptor();
    $user = $this->createUserAuth();
    $userDb = $authRepo->createUserAuth($user, $cryptor->encrypt('123'));
    $session = $this->authSvc->getSession();

    $request = new Authenticate();
    $request->setUserName($user->getEmail());
    $request->setPassword('123');
    $request->setProvider(CredentialsAuthProvider::name);
    //$session = $this->createAuthUserSession();
    $httpReq = new HttpRequestMock($request);
    $res = $this->host->execute($request, $httpReq);
    $this->assertEquals($httpReq->response()->cookies()->get(CredentialsAuthProvider::xPiUserAuthId), $session->getUserId());
  }

  public function testSessionIsSavedAndIsAuthenticated()
  {
    $authRepo = $this->getAuthRepository();
    $cryptor = $this->getCryptor();
    $user = $this->createUserAuth();
    $userDb = $authRepo->createUserAuth($user, $cryptor->encrypt('123'));
    $session = $this->authSvc->getSession();

    $request = new Authenticate();
    $request->setUserName($user->getEmail());
    $request->setPassword('123');
    $request->setProvider(CredentialsAuthProvider::name);
    
    $httpReq = new HttpRequestMock($request);
    $res = $this->host->execute($request, $httpReq);
    
    $httpReq->setSessionId($session->getId());
    $cacheProvider = $this->host->tryResolve('ICacheProvider');
    $sessionCacheFactory = new SessionFactory($cacheProvider);
    $cache = $sessionCacheFactory->getOrCreateSession($httpReq, $httpReq->response());
    $sessionKey = SessionPlugin::getSessionKey($session->getId());
    $this->assertNotNull($cache->get($sessionKey)); 
    $cacheBrut = $cacheProvider->get($sessionKey);
  }
}
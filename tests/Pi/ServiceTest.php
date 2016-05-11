<?hh

use Pi\Service,
	Pi\Interfaces\IPiHost,
	Pi\Interfaces\IContainer,
	Pi\Interfaces\ServiceGatewayInterface,
	Pi\HostConfig,
	Pi\Host\HostProvider,
	Mocks\VerseCreateRequest,
	Mocks\HttpRequestMock,
	Mocks\VerseCreateResponse,
	Mocks\VerseGet,
	Mocks\VerseGetResponse,
	Mocks\BibleHost;




class HostA extends BibleHost {
	public function configure(IContainer $appHost){
		parent::configure($appHost);
		$this->registerService(ServiceA::class);
	}
}
class RequestA {}
class ResponseA {}
class RequestB {}
class ResponseB {}
class RequestS {}
class ResponseS {}
class ServiceA extends Service {

	<<Request,Method('GET'),Route('/servicetestsession')>>
	public function session(RequestS $request)
	{


		return new ResponseS();
	}

	<<Request,Method('GET'),Route('/servicetest-2')>>
	public function receie(RequestA $request)
	{
		$_SESSION['A'] = 'a';
		return $this->execute(new VerseCreateRequest());
	}

	<<Request,Method('GET'),Route('/servicetestdiferent')>>
	public function doit(RequestB $request)
	{
		$_SESSION['A'] = 'ab';
		$this->request()->getSession();
		$this->request()->getSession();

		return new ResponseB();
	}

	<<Request,Method('GET'),Route('/servicetesttest')>>
	public function other(RequestB $request)
	{
		$_SESSION['A'] = 'a';
		$this->request()->getSession();
		$this->request()->getSession();

		return new ResponseB();
	}
}

class ServiceTest extends \PHPUnit_Framework_TestCase{

	protected $host;

	public function setUp()
	{
		$_SESSION['A'] = 'b';
		$config = new HostConfig();
		$this->host = new HostA($config);
	}

	public function tearDown()
	{
		$this->host->dispose();
	}



	public function testSameDtoReusedDiferenceServices()
	{
		$this->setUri('/servicetestdiferent');
		$this->host->init();
		$dto = new RequestB();
		$httpReq = new HttpRequestMock($dto);
		$this->host->serviceController()->execute($dto, $httpReq);
		$this->assertEquals('ab', $_SESSION['A']);
	}

	public function testCanCreateGateway()
	{
		$this->setUri('/servicetesttest');
		$this->host->init();
		$svc = $this->host->serviceController->getService(ServiceA::class, new HttpRequestMock(new RequestA()));
		$gateway = $svc->gateway();
		$this->assertNotNull($gateway);
		$this->assertTrue($svc->gateway() instanceof ServiceGatewayInterface);
	}

	public function testServiceCreation()
	{
		$this->setUri('/servicetesttest');
		$this->host->init();
		HostProvider::execute(new RequestB());
		$this->assertEquals($_SESSION['A'], 'a');
	}

	public function testSession()
	{
		$this->setUri('/servicetestsession');
		$this->host->init();
		HostProvider::execute(new RequestS());
	}

	public function testServiceCanExecute()
	{
		$this->setUri('/servicetest-2');
		$this->host->init();
		$res = HostProvider::execute(new RequestA());
		$this->assertInstanceOf(VerseCreateResponse::class, $res);
		$this->assertEquals('a', $_SESSION['A']);
	}

	protected function setUri(string $uri) : void
	{
		$_SERVER['REQUEST_URI'] = $uri;
	}

}

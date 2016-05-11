<?hh

namespace Test;

use HH\Asio,
	Pi\InProcessServiceGateway,
	Pi\MockHost,
	Pi\HttpMethod,
	Pi\Keywords,
	Pi\Interfaces\IPiHost,
	Pi\Interfaces\IContainer,
	Pi\Interfaces\ServiceGatewayInterface,
	Mocks\BibleHost,
	Mocks\HttpRequestMock,
	Mocks\ServiceGatewayContainer,
	Mocks\VerseGet,
	Mocks\VerseGetResponse,
	Mocks\VerseCreateRequest,
	Mocks\VerseCreateResponse;




class InProcessServiceGatewayTest extends \PHPUnit_Framework_TestCase {
	
	protected IPiHost $host;

	protected ServiceGatewayInterface $gateway;

	protected IRequest $httpReq;

	public function setUp()
	{
		$this->host = new BibleHost();
		$this->host->init();
		$this->httpReq = new HttpRequestMock(new VerseGet());
		$this->httpReq->setItem(Keywords::InvokeVerb, HttpMethod::GET);
		$this->gateway = ServiceGatewayContainer::getServiceGateway($this->httpReq, $this->host->container);
	}

	public function tearDown()
	{
		$this->host->dispose();
	}

	public function testCanSendAndPerserveIRequest()
	{
		$this->gateway->send(new VerseGet());
		$this->assertEquals($this->httpReq->getItem(Keywords::InvokeVerb), HttpMethod::GET);
		$this->gateway->send(new VerseCreateRequest());
		$this->assertEquals($this->httpReq->getItem(Keywords::InvokeVerb), HttpMethod::GET);
	}

	public function testCanSendAsyncAndPerserveIRequest()
	{
		$this->gateway->sendAsync(new VerseGet());
		$this->assertEquals($this->httpReq->getItem(Keywords::InvokeVerb), HttpMethod::GET);
		$this->gateway->sendAsync(new VerseCreateRequest());
		$this->assertEquals($this->httpReq->getItem(Keywords::InvokeVerb), HttpMethod::GET);
	}

	public function testCanSendManyAndPerseveIRequest()
	{
		$reqs = array(
			new VerseGet(),
			new VerseCreateRequest(),
			new VerseCreateRequest()
		);
		$res = $this->gateway->sendAll($reqs);
		$this->assertEquals($this->httpReq->getItem(Keywords::InvokeVerb), HttpMethod::GET);
	}

	public function testCanSendManyAsyncAndPerseveIRequest()
	{
		$reqs = array(
			new VerseGet(),
			new VerseCreateRequest(),
			new VerseCreateRequest()
		);
		$res = Asio\join($this->gateway->sendAllAsync($reqs));
		$this->assertEquals($this->httpReq->getItem(Keywords::InvokeVerb), HttpMethod::GET);
	}
}
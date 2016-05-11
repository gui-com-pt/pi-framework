<?hh

use Pi\EventManager,
    Pi\Route,
    Pi\Host\ServiceMetadata,
    Pi\Host\BasicRequest,
    Pi\Host\BasicResponse,
    Pi\Host\OperationDriver,
    Pi\Host\Operation,
    Pi\Interfaces\IService,
    Mocks\BibleHost,
    Mocks\BibleTestService,
    Mocks\VerseCreateRequest,
    Mocks\VerseCreateResponse,
    Mocks\VerseGet,
    Mocks\VerseGetResponse,
    Mocks\MockHostProvider;




class ServiceMetadataTest extends \PHPUnit_Framework_TestCase {

  protected ServiceMetadata $metadata;

  public function setUp()
  {
    $this->host = new BibleHost();
    $this->host->init();
    $this->metadata = $this->host->metadata();
  }

  public function tearDown()
  {
    $this->host->dispose();
  }

  public function testWhenBuildAllOperationsAndMapsAreCached()
  {
    $operation = $this->host->metadata()->loadFromCached(VerseCreateRequest::class);
    $this->assertFalse(is_null($operation));
    $this->assertTrue($operation instanceof Operation);
  }

  public function testCanLoadOperationsFromMemoryAndCache()
  {

  }

  public function testCreationServiceMetadata()
  {
    $dto = new VerseCreateRequest();
    $req = new BasicRequest($dto);
    $req->setDto($dto);
    $res = new VerseCreateResponse();
    $svc = new BibleTestService();
    
    //$this->metadata->add(get_class($svc), get_class($dto), get_class($res));
    $this->assertTrue($this->metadata->getServiceTypeByRequest(get_class($dto)) === get_class($svc));
  }

  public function testCanHydrateAndCacheOperation()
  {
    $cached = unserialize($this->host->cacheProvider()->get(ServiceMetadata::CACHE_METADATA_KEY . VerseCreateRequest::class));
    
    $operation = $this->host->metadata()->getOperation(VerseCreateRequest::class);

    $this->assertTrue($operation instanceof Operation);
    $metadata = $this->host->metadata()->getOperationMetadata(VerseCreateRequest::class);

    $this->assertTrue($operation instanceof Operation);
  }

  public function testCanGetServiceTypeByOperation()
  {
    $serviceType = $this->host->metadata()->getServiceTypeByRequest(VerseCreateRequest::class);
    $this->assertEquals(BibleTestService::class, $serviceType);
  }

  public function testCanGetResponseTypeByRequest()
  {
    $responseType = $this->host->metadata()->getResponseTypeByRequest(VerseCreateRequest::class);
    $this->assertEquals(VerseCreateResponse::class, $responseType);
  }

  public function testCanCreateOperationWithManyActions()
  {
    $operation = $this->host->metadata()->getOperation(VerseGet::class);
  }
}

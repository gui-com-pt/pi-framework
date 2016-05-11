<?hh

use Pi\SessionPlugin;
use Mocks\OdmContainer;
use Mocks\BibleHost;
use Pi\Auth\Interfaces\IAuthSession;

class SessionPluginTest extends \PHPUnit_Framework_TestCase {
	
	protected $host;

	public function setUp()
	{
		$this->host = new BibleHost();
		$this->host->init();
	}

	public function testCreateNewSessionAndSaveInCache()
	{
		$cache = $this->host->tryResolve('ICacheProvider');
		$session = SessionPlugin::createNewSession($this->host->tryResolve('IRequest'), 'random-session-id');
		$this->assertTrue($session instanceof IAuthSession);
		//$key = SessionPlugin::getSessionKey($session->getSessionKey());
		//die(print_r($cache->get($key)));
	}

	public function testSessionAreCreatedCachedAndLaterUsedByHttpHeaders() {
		
	}
}
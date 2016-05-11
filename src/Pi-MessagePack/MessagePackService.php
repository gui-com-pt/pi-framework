<?hh

namespace Pi\MessagePack;

use Pi\Service;
use Pi\Interfaces\ISerializerService;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;

class MessagePackService implements ISerializerService {

	public function ioc(IContainer $ioc)
	{

	}

	public function serialize($request)
	{
		$result = \msgpack_pack($request);
		return $result;
	}

	public function unserialize($request)
	{
		$result = \msgpack_unpack($request);
		return $result;
	}
}

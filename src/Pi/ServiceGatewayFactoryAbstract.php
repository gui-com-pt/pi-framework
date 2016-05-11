<?hh

namespace Pi;

use Pi\Interfaces\ServiceGatewayInterface,
	Pi\Interfaces\ServiceGatewayFactoryInterface;

abstract class ServiceGatewayFactoryAbstract 
	implements ServiceGatewayInterface, ServiceGatewayFactoryInterface {
	
	protected InProcessServiceGateway $localGateway;

	public function __construct()
	{

	}

	/**
	 * <?php
	 *    public IServiceGateway GetGateway(Type requestType)
            {
                var gateway = requestType.Name.Contains("External")
                    ? new JsonServiceClient(Config.ListeningOn)
                    : (IServiceGateway) localGateway;
                return gateway;
            }
            
	 * @param  [type] $requestType [description]
	 * @return [type]              [description]
	 */
	public abstract function getGateway($requestType) : ServiceGatewayInterface;

	public function getServiceGateway(IRequest $request) : ServiceGatewayInterface
	{
		$this->localGateway = new InProcessServiceGateway($request);
		return $this;
	}

	public function send(mixed $requestDto) : mixed
	{
		return $this->getGateway(get_class($requestDto))
			->send($requestDto);
	}

	public function sendAll(Vector<mixed> $requestDtos)
	{
		throw new \Exception("Not implemented");
	}

	public function publish(mixed $requestDto)
	{
		throw new \Exception("Not implemented");
	}

	public function publishAll(Set<mixed> $requestDtos)
	{
		throw new \Exception("Not implemented");
	}
}
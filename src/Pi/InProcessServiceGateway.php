<?hh

namespace Pi;

use HH\Asio,
	Pi\Host\HostProvider,
	Pi\Interfaces\ServiceGatewayInterface,
	Pi\Interfaces\ServiceGatewayAsyncInterface,
	Pi\Interfaces\ServiceGatewayFactoryInterface,
	Pi\Interfaces\IRequest,
	Pi\Interfaces\GetInterface,
	Pi\Interfaces\PostInterface,
	Pi\Interfaces\PutInterface,
	Pi\Interfaces\DeleteInterface,
	Pi\Interfaces\PathInterface,
	Pi\Interfaces\OptionsInterface,
	Pi\Interfaces\VerbInterface;




/**
 *
 * Calls will eventually change the passed RequestInterface
 * Everytime a action is executed, we must keep holders
 */
class InProcessServiceGateway implements ServiceGatewayInterface, ServiceGatewayAsyncInterface {

	public function __construct(
		protected IRequest $req
	)
	{

	}

	public function send(mixed $requestDto) : mixed
	{
		$holdDto = $this->req->dto();
		//$holdAttrs = $this->req->requestAttributes();
		$holdVerb = $this->setRequestVerb($requestDto);

		try {
			return $this->executeSync($requestDto);
		} catch(\Exception $ex) {

		} finally {
			$this->resetRequest($holdDto, null, $holdVerb);
		}
	}

	public function sendAll(array<mixed> $requestDtos) : Vector<mixed>
	{
		$res = Vector{};
		array_walk($requestDtos, function($req) use($res){
			$res->add($this->send($req));
		});
		return $res;	
	}

	public async function sendAsync(mixed $requestDto) : Awaitable<mixed>
	{
		return $this->send($requestDtos);
	}

	public async function sendAllAsync(array<mixed> $requestDtos) : Awaitable<Vector<mixed>>
	{
		$handles = Asio\vf($requestDtos, async ($dto) ==> ($this->sendAsync($dto)));
		return await $handles;
	}

	public function publish(mixed $requestDto) : mixed
	{
		throw new \Exception("Not implemented");
	}

	public function publishAll(array<mixed> $requestDtos) : Vector<mixed>
	{
		throw new \Exception("Not implemented");
	}

	public function publishAsync(mixed $requestDto) : Awaitable<mixed>
	{
		throw new \Exception("Not implemented");
	}

	public function publishAllAsync(array<mixed> $requestDtos) : Awaitable<Vector<mixed>>
	{
		throw new \Exception("Not implemented");
	}

	protected function executeSync(mixed $requestDto)
	{
		$res = null;
		try {
			$res = HostProvider::serviceController()->execute($requestDto, $this->req);
		}
		catch(\Exception $ex) {
			//$res = error
		}
		return $res;
	}

	/**
	 * Transform the current Request according to requestDto
	 * Modify the Verb
	 * Invoked before executing the request
	 * @param mixed $requestDto The DTO class
	 * @return string the Verb holder in $req->items()[Keywords::InvokeVerb]
	 */
	protected function setRequestVerb(mixed $requestDto) : string
	{
		$hold = $this->req->items()->get(Keywords::InvokeVerb);

		if($requestDto instanceof VerbInterface) {
			
			if($requestDto instanceof GetInterface) {
				$this->req->setItem(Keywords::InvokeVerb, HttpMethod::GET);
			}
			if($requestDto instanceof PostInterface) {
				$this->req->setItem(Keywords::InvokeVerb, HttpMethod::POST);
			}
			if($requestDto instanceof PutInterface) {
				$this->req->setItem(Keywords::InvokeVerb, HttpMethod::PUT);
			}
			if($requestDto instanceof DeleteInterface) {
				$this->req->setItem(Keywords::InvokeVerb, HttpMethod::DELETE);
			}
			if($requestDto instanceof PatchInterface) {
				$this->req->setItem(Keywords::InvokeVerb, HttpMethod::PATCH);
			}
			if($requestDto instanceof OptionsInterface) {
				$this->req->setItem(Keywords::InvokeVerb, HttpMethod::OPTIONS);
			}
		}
		return $hold;
	}

	/**
	 * Set the IRequest object to his original state
	 * Invoked after executing the request
	 * @param  string $verb [description]
	 * @return [type]       [description]
	 */
	protected function resetRequest($requestDto = null, $attributes = null, $verb = null) : void
	{
		if(is_null($verb)) { // The original request dont have a verb set
			$this->req->items()->remove(Keywords::InvokeVerb);
		} else {
			$this->req->setItem(Keywords::InvokeVerb, $verb);
			$this->req->setDto($requestDto);
			// set attributes
		}
	}
}
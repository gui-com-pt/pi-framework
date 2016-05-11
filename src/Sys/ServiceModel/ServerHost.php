<?hh

namespace Sys\ServiceModel;




class ServerHost {
		
	protected string $name;

	protected bool $mailServer;

	protected bool $webServer;

	protected bool $fileServer;

	protected bool $dnsServer;

	protected bool $dbServer;

	protected bool $isActive;

	protected string $hostname;

	protected string $ipAddress;

	protected string $netmask;

	protected string $gateway;

	protected array<string> $nameservers;

	public function getName() : ?string
	{
		return $this->name;
	}

	public function setName(string $value) : void
	{
		$this->name = $value;
	}

	public function getMailServer() : ?string
	{
		return $this->mailServer;
	}

	public function setMailServer(string $value) : void
	{
		$this->mailServer = $value;
	}

	public function getWebserver() : ?string
	{
		return $this->webServer;
	}

	public function setWebserver(string $value) : void
	{
		$this->webServer = $value;
	}

	public function getFileServer() : ?string
	{
		return $this->fileServer;
	}

	public function setFileServer(string $value) : void
	{
		$this->fileServer = $value;
	}

	public function getDnsServer() : ?string
	{
		return $this->dnsServer;
	}

	public function setDnsServer(string $value) : void
	{
		$this->dnsServer = $value;
	}

	public function getDbServer() : ?string
	{
		return $this->dbServer;
	}

	public function setDbServer(string $value) : void
	{
		$this->dbServer = $value;
	}

	public function isActive() : bool
	{
		return $this->isActive;
	}

	public function setIsActive(bool $value) : void
	{
		$this->isActive = $value;
	}
}
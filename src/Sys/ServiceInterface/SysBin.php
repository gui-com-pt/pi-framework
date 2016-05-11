<?hh

namespace Sys\ServiceInterface;

class SysBin {
	
	/**
	 * Execute a shell command
	 * PLEASE use the escapeshellarg to escape shell arguments like paths
	 * @param  string $command Command to be executed
	 * @return [type]          [description]
	 */
	static function exec(string $command) : mixed
	{
		return exec($command)
	}

	static function quotaStats(string $user) : array
	{
		return array(
			'available' => 0,
			'used'=> 0
		);
	}
}
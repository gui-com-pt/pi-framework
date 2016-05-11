<?hh

namespace Sys\ServiceModel;

class ServerMailConfig {
	
	/**
	 * Email module
	 * e.g. postfix_mysql
	 * @var [type]
	 */
	protected $module;

	protected string $maildirPath;

	protected string $homedirPath;

	protected Pop3ImapDaemon $daemon;

	protected MailFilter $filter;

	protected int $mailuserUid;

	protected int $mailuserGid;

	protected string $mailuserName;

	protected string $mailuserGroup;

	protected ?string $relayhost;

	protected ?string $relayhostUser;

	protected ?string $relayhostPassword;

	protected ?int $mailboxSizeLimit;

	protected ?int $messageSizeLimit;

	protected bool $mailboxQuotaStats;

	protected ?string $realtimeBlackholeList;

	protected ?bool $sendQuotaWarningToAdmin;

	protected ?bool $sendQuotaWarningToClient;

	protected int $sendQuotaWarningPerDays;
}

enum MailFilter : int {
	Sieve = 1;
	Maildrop = 2;
}
enum Pop3ImapDaemon : int {
	Dovecot = 1;
	Courier = 2;
}
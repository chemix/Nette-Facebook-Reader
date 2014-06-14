<?php
/**
 * @author Honza Cerny <hello@honzacerny.com>
 */

namespace App\Model;

use Facebook\FacebookSession;

class FacebookSessionManager {

	protected $appId;

	protected $appSecret;

	function __construct($appId, $appSecret)
	{
		$this->appId = $appId;
		$this->appSecret = $appSecret;

		FacebookSession::setDefaultApplication($this->appId, $this->appSecret);
	}

	public function getAppSession()
	{
		return FacebookSession::newAppSession();
	}
}
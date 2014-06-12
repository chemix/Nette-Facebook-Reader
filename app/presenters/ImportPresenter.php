<?php
/**
 * @author Honza Cerny <hello@honzacerny.com>
 */

namespace App\Presenters;

use Nette,
	App\Model;

use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Tracy\Dumper;


/**
 * Import presenter.
 */
class ImportPresenter extends BasePresenter
{

	public function renderDefault()
	{
		FacebookSession::setDefaultApplication('YOUR_APP_ID', 'YOUR_APP_SECRET');

		$session = FacebookSession::newAppSession();
		$data = array();

		try {
			$request = new FacebookRequest($session, 'GET', '/nettefw/feed');
			$response = $request->execute();
			$posts = $response->getGraphObject()->asArray();

			$data = $posts['data'];
		} catch (\Exception $ex) {
			throw $ex;
			$this->terminate();
		}

		Dumper::dump($data);
	}

}
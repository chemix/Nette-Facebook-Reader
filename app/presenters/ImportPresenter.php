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
use Nette\Caching\Cache;


/**
 * Import presenter.
 */
class ImportPresenter extends BasePresenter
{
	/**
	 * @var \Nette\Caching\IStorage @inject
	 */
	public $cacheStorage;

	public function renderDefault()
	{
		FacebookSession::setDefaultApplication('YOUR_APP_ID', 'YOUR_APP_SECRET');

		$session = FacebookSession::newAppSession();
		$cache = new Cache($this->cacheStorage, 'facebookWall');
		$data = $cache->load("stories");

		if (empty($data)) {
			try {
				$request = new FacebookRequest($session, 'GET', '/nettefw/feed');
				$response = $request->execute();
				$posts = $response->getGraphObject()->asArray();

				$data = $posts['data'];
				$cache->save("stories", $data, array(
					Cache::EXPIRATION => '+30 minutes',
					Cache::SLIDING => TRUE
				));

			} catch (\Exception $ex) {
				throw $ex;
				$this->terminate();
			}
		}

		Dumper::dump($data);
	}

}
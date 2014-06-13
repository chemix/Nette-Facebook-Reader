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

	/**
	 * @var \Nette\Database\Context @inject
	 */
	public $database;

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

		// save data to database
		if (is_array($data) && !empty($data)) {
			foreach ($data as $rowPost) {

				$post = array(
					'id' => $rowPost->id,
					'type' => $rowPost->type,
					'created_time' => $rowPost->created_time,
					'updated_time' => $rowPost->updated_time,
				);

				if (isset($rowPost->name)) {
					$post['name'] = $rowPost->name;
				}
				if (isset($rowPost->link)) {
					$post['link'] = $rowPost->link;
				}
				if (isset($rowPost->status_type)) {
					$post['status_type'] = $rowPost->status_type;
				}
				if (isset($rowPost->message)) {
					$post['message'] = $rowPost->message;
				}
				if (isset($rowPost->caption)) {
					$post['caption'] = $rowPost->caption;
				}
				if (isset($rowPost->picture)) {
					$post['picture'] = $rowPost->picture;
				}

				// post 'status' use story, we need message
				if ($rowPost->type == 'status') {
					if (isset($rowPost->story)) {
						$post['message'] = $rowPost->story;
					}
				}

				try {
					$this->database->table('facebook_wallposts')->insert($post);
				} catch (\PDOException $e) {
					if ($e->getCode() == '23000') {
						$this->database->table('facebook_wallposts')->where('id', $rowPost->id)->update($post);
					} else {
						throw $e;
					}
				}
			}
		}

		// send data to template
		$this->template->wallPosts = $data;
	}

}
<?php
/**
 * @author Honza Cerny <hello@honzacerny.com>
 */

namespace App\Model;


use Nette\Database\Context;
use Nette\Object;
use Facebook\FacebookRequest;

class FacebookWallposts extends Object
{

	/**
	 * @var \Nette\Database\Context
	 */
	protected $database;

	/**
	 * @var \App\Model\FacebookSessionManager
	 */
	protected $facebookSessionManager;

	/**
	 * @param Context $database
	 * @param FacebookSessionManager $facebook
	 */
	function __construct(Context $database, FacebookSessionManager $facebook)
	{
		$this->database = $database;
		$this->facebookSessionManager = $facebook;
	}

	public function getLastPosts($count = 5)
	{
		return $this->database->table('facebook_wallposts')
			->where('status', '1')
			->order('created_time DESC')
			->limit($count)
			->fetchAll();
	}

	public function importPostFromFacebook()
	{
		$session = $this->facebookSessionManager->getAppSession();

		try {
			$request = new FacebookRequest($session, 'GET', '/nettefw/feed');
			$response = $request->execute();
			$posts = $response->getGraphObject()->asArray();
			$data = $posts['data'];

		} catch (\Exception $ex) {
			throw $ex;
			$this->terminate();
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

		return $data;
	}
}
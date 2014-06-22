<?php
/**
 * @author Honza Cerny <hello@honzacerny.com>
 */

namespace App\Model;


use Kdyby\Facebook\Facebook;
use Kdyby\Facebook\FacebookApiException;
use Nette\Database\Context;
use Nette\Object;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;
use Tracy\Debugger;


class FacebookWallposts extends Object
{

	/**
	 * @var \Nette\Database\Context
	 */
	protected $database;

	/**
	 * @var Facebook
	 */
	protected $facebook;


	public function __construct(Context $database, Facebook $facebook)
	{
		$this->database = $database;
		$this->facebook = $facebook;
	}

	public function getLastPosts($count = 5)
	{
		return $this->database->table('facebook_wallposts')
			->where('status', '1')
			->order('created_time DESC')
			->limit($count)
			->fetchAll();
	}

	public function getAllPosts()
	{
		return $this->database->table('facebook_wallposts')
			->order('created_time DESC')
			->fetchAll();
	}

	/**
	 * enable post
	 *
	 * @param $postId string
	 * @return bool
	 */
	public function enablePost($postId)
	{
		$this->database->table('facebook_wallposts')
			->where('id', $postId)
			->update(array('status' => '1'));

		return TRUE;
	}

	/**
	 * disable post
	 *
	 * @param $postId string
	 * @return bool
	 */
	public function disablePost($postId)
	{
		$this->database->table('facebook_wallposts')
			->where('id', $postId)
			->update(array('status' => '0'));

		return TRUE;

	}

	public function importPostFromFacebook()
	{
		try {
			// when you're loading a lot of posts, it's better to use ->iterate() instead of several ->get()'s
			$posts = $this->facebook->iterate('/nettefw/feed');

		} catch (FacebookApiException $ex) {
			Debugger::log($ex->getMessage(), 'facebook');

			return array();
		}

		$imported = array();

		// save data to database
		foreach ($posts as $rowPost) {
			$post = array(
				'id' => $rowPost->id,
				'type' => $rowPost->type,
				'created_time' => DateTime::from($rowPost->created_time)->format('Y-m-d H:i:s'),
				'updated_time' => DateTime::from($rowPost->updated_time)->format('Y-m-d H:i:s'),
				'name' => isset($rowPost->name) ? $rowPost->name : NULL,
				'link' => isset($rowPost->link) ? $rowPost->link : NULL,
				'status_type' => isset($rowPost->status_type) ? $rowPost->status_type : NULL,
				'message' => isset($rowPost->message) ? $rowPost->message : NULL,
				'caption' => isset($rowPost->caption) ? $rowPost->caption : NULL,
				'picture' => isset($rowPost->picture) ? $rowPost->picture : NULL,
			);

			// post 'status' use story, we need message
			if ($rowPost->type == 'status' && isset($rowPost->story)) {
				$post['message'] = $rowPost->story;
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

			$imported[$post['id']] = ArrayHash::from($rowPost);
		}

		return $imported;
	}

}

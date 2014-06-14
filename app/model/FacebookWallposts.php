<?php
/**
 * @author Honza Cerny <hello@honzacerny.com>
 */

namespace App\Model;


use Nette\Database\Context;
use Nette\Object;

class FacebookWallposts extends Object
{

	/**
	 * @var \Nette\Database\Context
	 */
	protected $database;

	function __construct(Context $database)
	{
		$this->database = $database;
	}

	public function getLastPosts($count = 5)
	{
		return $this->database->table('facebook_wallposts')
			->where('status', '1')
			->order('created_time DESC')
			->limit($count)
			->fetchAll();
	}
}
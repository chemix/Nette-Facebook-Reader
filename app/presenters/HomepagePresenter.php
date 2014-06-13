<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

	/**
	 * @var \Nette\Database\Context @inject
	 */
	public $database;

	public function renderDefault()
	{
		$facebookWallPosts = $this->database->table('facebook_wallposts')->where('status','1')->limit(5)->fetchAll();
		$this->template->wallPosts =  $facebookWallPosts;
	}

}

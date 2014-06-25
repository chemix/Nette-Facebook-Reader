<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Admin presenter.
 */
class AdminPresenter extends BasePresenter
{

	/**
	 * @var \App\Model\FacebookWallposts @inject
	 */
	public $wallposts;

	public function renderDefault()
	{
		$this->template->wallPosts = $this->wallposts->getAllPosts();
	}

	public function handleEnablePost($postId)
	{
		if ($this->wallposts->enablePost($postId)) {
			$this->flashMessage('Post enabled');
			$this->redrawControl('flashes');
			$this->redrawControl('wallposts');
		}

	}

	public function handleDisablePost($postId)
	{
		if ($this->wallposts->disablePost($postId)) {
			$this->flashMessage('Post disabled');
			$this->redrawControl('flashes');
			$this->redrawControl('wallposts');
		}
	}

}

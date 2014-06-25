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
		if (!isset($this->template->wallPosts)) {
			$this->template->wallPosts = $this->wallposts->getAllPosts();
		}
	}

	public function handleEnablePost($postId)
	{
		if ($this->wallposts->enablePost($postId)) {

			$this->template->wallPosts = $this->isAjax()
				? array($this->wallposts->getOne($postId))
				: $this->wallposts->getAllPosts();

			$this->flashMessage('Post enabled');
			$this->redrawControl('flashes');
			$this->redrawControl('wallposts');
			// F5 protection without JS
			if (!$this->isAjax()){
				$this->redirect('this');
			}
		}

	}

	public function handleDisablePost($postId)
	{
		if ($this->wallposts->disablePost($postId)) {

			$this->template->wallPosts = $this->isAjax()
				? array($this->wallposts->getOne($postId))
				: $this->wallposts->getAllPosts();

			$this->flashMessage('Post disabled');
			$this->redrawControl('flashes');
			$this->redrawControl('wallposts');
			// F5 protection
			if (!$this->isAjax()){
				$this->redirect('this');
			}
		}
	}

}

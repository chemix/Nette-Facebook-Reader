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

	protected function afterTogglePostStatus($status, $postId, $message)
	{
		if ($status) {
			$this->template->wallPosts = $this->isAjax()
				? array($this->wallposts->getOne($postId))
				: $this->wallposts->getAllPosts();

			$this->flashMessage($message);
			$this->redrawControl('flashes');
			$this->redrawControl('wallposts');
		}
		// F5 protection without JS
		if (!$this->isAjax()){
			$this->redirect('this');
		}
	}

	public function handleEnablePost($postId)
	{
		$status = $this->wallposts->enablePost($postId);
		$this->afterTogglePostStatus($status, $postId, 'Post enabled');
	}

	public function handleDisablePost($postId)
	{
		$status = $this->wallposts->disablePost($postId);
		$this->afterTogglePostStatus($status, $postId, 'Post disabled');
	}

}

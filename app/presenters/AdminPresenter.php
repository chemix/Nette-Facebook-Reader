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

	public function actionEnablePost($postId)
	{
		if ($this->wallposts->enablePost($postId)) {
			if ($this->isAjax()) {
				$this->payload->message = 'Post enabled';
				$this->sendPayload();

			} else {
				$this->flashMessage('Post enabled');
				$this->redirect('default');
			}
		}

	}

	public function actionDisablePost($postId)
	{
		if ($this->wallposts->disablePost($postId)) {
			if ($this->isAjax()) {
				$this->payload->message = 'Post disabled';
				$this->sendPayload();

			} else {
				$this->flashMessage('Post disabled');
				$this->redirect('default');
			}
		}
	}

}

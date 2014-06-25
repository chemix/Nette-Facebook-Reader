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
			if ($this->isAjax()) {
				$this->payload->message = 'Post enabled';
				$this->payload->action = 'enable';
				$this->payload->status = '1';
				$this->sendPayload();

			} else {
				$this->flashMessage('Post enabled');
				$this->redirect('default');
			}
		}

	}

	public function handleDisablePost($postId)
	{
		if ($this->wallposts->disablePost($postId)) {
			if ($this->isAjax()) {
				$this->payload->message = 'Post disabled';
				$this->payload->action = 'disable';
				$this->payload->status = '1';
				$this->sendPayload();

			} else {
				$this->flashMessage('Post disabled');
				$this->redirect('default');
			}
		}
	}

}

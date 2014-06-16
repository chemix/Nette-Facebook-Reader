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
	 * @var \App\Model\FacebookWallposts @inject
	 */
	public $wallposts;

	public function renderDefault()
	{
		$this->template->wallPosts = $this->wallposts->getLastPosts();
	}



	protected function createTemplate()
	{
		/** @var Nette\Bridges\ApplicationLatte\Template $template */
		$template = parent::createTemplate();
		$template->addFilter('fbPostLink', function ($fbPost) {
			if (!empty($fbPost->link)) {
				return $fbPost->link;
			}

			if ($m = Nette\Utils\Strings::match($fbPost->id, '~^(?P<pageId>[^_]+)_(?P<postId>[^_]+)\\z~')) {
				return 'https://www.facebook.com/nettefw/posts/' . urlencode($m['postId']);
			}

			return NULL;
		});

		return $template;
	}

}

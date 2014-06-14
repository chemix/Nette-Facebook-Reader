<?php
/**
 * @author Honza Cerny <hello@honzacerny.com>
 */

namespace App\Presenters;

use Nette,
	App\Model;



/**
 * Import presenter.
 */
class ImportPresenter extends BasePresenter
{
	/**
	 * @var \App\Model\FacebookWallposts @inject
	 */
	public $wallposts;

	public function renderDefault()
	{
		$this->template->wallPosts = $this->wallposts->importPostFromFacebook();
	}

}
<?php

namespace Components\PagesList;

use Model\PagesManager;
use Model\PagesRepository;
use Nette\Application\UI\Control;


class PagesList extends Control
{

	/** @var PagesRepository */
	private $pagesRepository;

	/** @var PagesManager */
	private $pagesManager;

	/** @var array */
	public $onPageRefreshed = [];

	/** @var array */
	public $onPageRefreshingError = [];

	/** @var array */
	public $onPageRemoved = [];


	/**
	 * @param PagesRepository $pagesRepository
	 * @param PagesManager $pagesManager
	 */
	public function __construct(PagesRepository $pagesRepository, PagesManager $pagesManager)
	{
		$this->pagesRepository = $pagesRepository;
		$this->pagesManager = $pagesManager;
	}

	/**
	 * @return void
	 */
	public function render()
	{
		$this->template->pages = $this->pagesRepository->findAllOrderByPositiveDesc();
		$this->template->setFile(__DIR__ . '/templates/default.latte');
		$this->template->render();
	}

	/**
	 * @param string $pageId
	 * @return void
	 */
	public function handleRefresh($pageId)
	{
		if ($this->pagesManager->updatePage($pageId)) {
			$this->onPageRefreshed();
		}
		else {
			$this->onPageRefreshingError();
		}
	}

	/**
	 * @param string $pageId
	 * @return void
	 */
	public function handleRemove($pageId)
	{
		$this->pagesManager->removePage($pageId);
		$this->onPageRemoved();
	}

}

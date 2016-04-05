<?php

namespace App\Presenters;

use Components\AddForm\AddForm;
use Components\AddForm\IAddFormFactory;
use Components\PagesList\IPagesListFactory;
use Components\PagesList\PagesList;
use Model\PagesManager;
use Nette;
use Nette\Application\UI\Presenter;


class HomepagePresenter extends Presenter
{

	/** @var IAddFormFactory */
	private $addFormFactory;

	/** @var IPagesListFactory */
	private $pagesListFactory;

	/** @var PagesManager */
	private $pagesManager;


	/**
	 * @param IAddFormFactory $addFormFactory
	 * @param IPagesListFactory $pagesListFactory
	 * @param PagesManager $pagesManager
	 */
	public function __construct(IAddFormFactory $addFormFactory, IPagesListFactory $pagesListFactory,
								PagesManager $pagesManager)
	{
		$this->addFormFactory = $addFormFactory;
		$this->pagesListFactory = $pagesListFactory;
		$this->pagesManager = $pagesManager;
	}

	/**
	 * @return AddForm
	 */
	protected function createComponentAddForm()
	{
		$form = $this->addFormFactory->create();
		$form->onPageAdded[] = function() {
			$this->flashMessage('Page has been added successfully.', 'success');
			$this->redirect('this');
		};
		$form->onPageAddingError[] = function() {
			$this->flashMessage('Cannot add page. Please try again.', 'danger');
		};
		return $form;
	}

	/**
	 * @return PagesList
	 */
	protected function createComponentPagesList()
	{
		$list = $this->pagesListFactory->create();
		$list->onPageRefreshed[] = function() {
			$this->flashMessage('Page was refreshed successfully.', 'success');
			$this->redirect('this');
		};
		$list->onPageRefreshingError[] = function() {
			$this->flashMessage('Cannot refresh page. Please try again.', 'danger');
			$this->redirect('this');
		};
		$list->onPageRemoved[] = function() {
			$this->flashMessage('Page was removed successfully.', 'success');
			$this->redirect('this');
		};
		return $list;
	}

}

<?php

namespace Components\AddForm;

use Model\PagesManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


class AddForm extends Control
{

	/** @var PagesManager */
	private $pagesManager;

	/** @var array */
	public $onPageAdded = [];

	/** @var array */
	public $onPageAddingError = [];


	/**
	 * @param PagesManager $pagesManager
	 */
	public function __construct(PagesManager $pagesManager)
	{
		$this->pagesManager = $pagesManager;
	}

	/**
	 * @return void
	 */
	public function render()
	{
		$this->template->setFile(__DIR__ . '/templates/default.latte');
		$this->template->render();
	}

	/**
	 * @return Form
	 */
	protected function createComponentAddForm()
	{
		$form = new Form();

		$form->addText('pageId', '')
			->setAttribute('placeholder', 'Facebook page ID');

		$form->addText('twitterId', '')
			->setAttribute('placeholder', 'Twitter ID');

		$form->addSubmit('submit', 'Add');

		$form->onSuccess[] = function(Form $form) {
			$this->handlePageAdd($form);
		};

		return $form;
	}

	/**
	 * @param Form $form
	 * @return void
	 */
	private function handlePageAdd(Form $form)
	{
		if ($this->pagesManager->addPage($form->values->pageId, $form->values->twitterId)) {
			$this->onPageAdded();
		}
		else {
			$this->onPageAddingError();
		}
	}

}

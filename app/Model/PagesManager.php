<?php

namespace Model;

use Facebook\Exceptions\FacebookResponseException;
use Nette\Object;


class PagesManager extends Object
{

	/** @var SentimentAnalyzer */
	private $sentimentAnalyzer;

	/** @var PagesRepository */
	private $pagesRepository;

	/** @var FacebookApi */
	private $facebookApi;


	/**
	 * @param SentimentAnalyzer $sentimentAnalyzer
	 * @param PagesRepository $pagesRepository
	 */
	public function __construct(SentimentAnalyzer $sentimentAnalyzer, PagesRepository $pagesRepository, FacebookApi $facebookApi)
	{
		$this->sentimentAnalyzer = $sentimentAnalyzer;
		$this->pagesRepository = $pagesRepository;
		$this->facebookApi = $facebookApi;
	}

	/**
	 * @param string $pageId
	 * @return bool
	 */
	public function addPage($pageId)
	{
		try {
			$information = $this->facebookApi->getInformation($pageId);
			$sentiments = $this->sentimentAnalyzer->analyze($pageId);
			$this->pagesRepository->insertPage(
				$information->getField('name'),
				$pageId,
				(int) $information->getField('likes'),
				$sentiments[SentimentAnalyzer::POS],
				$sentiments[SentimentAnalyzer::NEU],
				$sentiments[SentimentAnalyzer::NEG]
			);
			return true;
		}
		catch (FacebookResponseException $e) {
			return false;
		}
	}

	/**
	 * @param string $pageId
	 * @return bool
	 */
	public function updatePage($pageId)
	{
		try {
			$information = $this->facebookApi->getInformation($pageId);
			$sentiments = $this->sentimentAnalyzer->analyze($pageId);
			$this->pagesRepository->updatePage(
				$pageId,
				(int) $information->getField('likes'),
				$sentiments[SentimentAnalyzer::POS],
				$sentiments[SentimentAnalyzer::NEU],
				$sentiments[SentimentAnalyzer::NEG]
			);
			return true;
		}
		catch (FacebookResponseException $e) {
			return false;
		}
	}

	/**
	 * @param string $pageId
	 * @return void
	 */
	public function removePage($pageId)
	{
		$this->pagesRepository->removePage($pageId);
	}

}

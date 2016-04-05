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

	/** @var TwitterApi */
	private $twitterApi;


	/**
	 * @param SentimentAnalyzer $sentimentAnalyzer
	 * @param PagesRepository $pagesRepository
	 * @param TwitterApi $twitterApi
	 * @param FacebookApi $facebookApi
	 */
	public function __construct(SentimentAnalyzer $sentimentAnalyzer, PagesRepository $pagesRepository,
								TwitterApi $twitterApi, FacebookApi $facebookApi)
	{
		$this->sentimentAnalyzer = $sentimentAnalyzer;
		$this->pagesRepository = $pagesRepository;
		$this->twitterApi = $twitterApi;
		$this->facebookApi = $facebookApi;
	}

	/**
	 * @param string $pageId
	 * @param string $twitterId
	 * @return bool
	 */
	public function addPage($pageId, $twitterId)
	{
		try {
			$facebookInformation = $this->facebookApi->getInformation($pageId);
			$facebookSentiments = $this->sentimentAnalyzer->analyzeFacebook($pageId);
			$twitterInformation = $this->twitterApi->getInformation($twitterId);
			$twitterSentiments = $this->sentimentAnalyzer->analyzeTwitter($twitterId);
			$this->pagesRepository->insertPage(
				$facebookInformation->getField('name'),
				$pageId,
				(int) $facebookInformation->getField('likes'),
				$facebookSentiments[SentimentAnalyzer::POS],
				$facebookSentiments[SentimentAnalyzer::NEU],
				$facebookSentiments[SentimentAnalyzer::NEG],
				$twitterId,
				(int) $twitterInformation['followers_count'],
				$twitterSentiments[SentimentAnalyzer::POS],
				$twitterSentiments[SentimentAnalyzer::NEU],
				$twitterSentiments[SentimentAnalyzer::NEG]
			);
			return true;
		}
		catch (FacebookResponseException $e) {
			return false;
		}
	}

	/**
	 * @param string $pageId
	 * @param string $twitterId
	 * @return bool
	 */
	public function updatePage($pageId, $twitterId)
	{
		try {
			$information = $this->facebookApi->getInformation($pageId);
			$facebookSentiments = $this->sentimentAnalyzer->analyzeFacebook($pageId);
			$twitterSentiments = $this->sentimentAnalyzer->analyzeTwitter($twitterId);
			$this->pagesRepository->updatePage(
				$pageId,
				(int) $information->getField('likes'),
				$facebookSentiments[SentimentAnalyzer::POS],
				$facebookSentiments[SentimentAnalyzer::NEU],
				$facebookSentiments[SentimentAnalyzer::NEG],
				$twitterSentiments[SentimentAnalyzer::POS],
				$twitterSentiments[SentimentAnalyzer::NEU],
				$twitterSentiments[SentimentAnalyzer::NEG]
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

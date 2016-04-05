<?php

namespace Model;

use Facebook\GraphNodes\GraphNode;
use Nette\Object;
use PHPInsight\Sentiment;


class SentimentAnalyzer extends Object
{

	const POS = 'pos';
	const NEG = 'neg';
	const NEU = 'neu';

	/** @var FacebookApi */
	private $facebookApi;

	/** @var TwitterApi */
	private $twitterApi;

	/** @var Sentiment */
	private $sentiment;


	/**
	 * @param FacebookApi $facebookApi
	 * @param TwitterApi $twitterApi
	 * @param Sentiment $sentiment
	 */
	public function __construct(FacebookApi $facebookApi, TwitterApi $twitterApi, Sentiment $sentiment)
	{
		$this->facebookApi = $facebookApi;
		$this->twitterApi = $twitterApi;
		$this->sentiment = $sentiment;
	}

	/**
	 * @param string $pageId
	 * @return array
	 */
	public function analyzeFacebook($pageId)
	{
		$posts = $this->facebookApi->getPosts($pageId);
		$texts = [];
		foreach ($posts as $post) { /** @var GraphNode $post */
			$texts[] = $post->getField('message');
		}
		return $this->analyze($texts);
	}

	/**
	 * @param string $userId
	 * @return array
	 */
	public function analyzeTwitter($userId)
	{
		$tweets = $this->twitterApi->getTweets($userId);
		$texts = [];
		foreach ($tweets as $tweet) {
			$texts[] = $tweet['text'];
		}
		return $this->analyze($texts);
	}

	/**
	 * @param array $texts
	 * @return array
	 */
	private function analyze(array $texts)
	{
		$sentiments = [self::POS => 0, self::NEU => 0, self::NEG => 0];
		$count = 0;
		foreach ($texts as $text) {
			$category = $this->sentiment->categorise($text); /** @var string $category */
			$sentiments[$category]++;
			$count++;
		}
		foreach ([self::POS, self::NEU, self::NEG] as $category) {
			$sentiments[$category] = $sentiments[$category] / $count;
		}
		return $sentiments;
	}

}

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

	/** @var Sentiment */
	private $sentiment;


	/**
	 * @param FacebookApi $facebookApi
	 * @param Sentiment $sentiment
	 */
	public function __construct(FacebookApi $facebookApi, Sentiment $sentiment)
	{
		$this->facebookApi = $facebookApi;
		$this->sentiment = $sentiment;
	}

	/**
	 * @param string $pageId
	 * @return array
	 */
	public function analyze($pageId)
	{
		$posts = $this->facebookApi->getPosts($pageId);
		$postsSentiments = [self::POS => 0, self::NEU => 0, self::NEG => 0];
		$postsCount = 0;
		foreach ($posts as $post) { /** @var GraphNode $post */
			$category = $this->sentiment->categorise($post->getField('message')); /** @var string $category */
			$postsSentiments[$category]++;
			$postsCount++;
		}
		foreach ([self::POS, self::NEU, self::NEG] as $category) {
			$postsSentiments[$category] = $postsSentiments[$category] / $postsCount;
		}
		return $postsSentiments;
	}

}

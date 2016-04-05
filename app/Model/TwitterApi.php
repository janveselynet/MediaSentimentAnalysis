<?php

namespace Model;

use Nette\Object;
use TwitterAPIExchange;


class TwitterApi extends Object
{

	const DEFAULT_NUMBER_OF_TWEETS = 100;

	/** @var TwitterAPIExchange */
	private $twitter;


	/**
	 * @param string $consumerKey
	 * @param string $consumerSecret
	 * @param string $accessToken
	 * @param string $accessTokenSecret
	 */
	public function __construct($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret)
	{
		$this->twitter = new TwitterAPIExchange([
			'oauth_access_token' => $accessToken,
			'oauth_access_token_secret' => $accessTokenSecret,
			'consumer_key' => $consumerKey,
			'consumer_secret' => $consumerSecret
		]);
	}

	/**
	 * @param string $userId
	 * @param int $limit
	 * @return array
	 */
	public function getTweets($userId, $limit = self::DEFAULT_NUMBER_OF_TWEETS)
	{
		$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
		return $this->performRequest(
			$this->twitter->setGetfield("?screen_name={$userId}&count={$limit}")->buildOauth($url, 'GET')
		);
	}

	/**
	 * @param string $userId
	 * @return array
	 */
	public function getInformation($userId)
	{
		$url = "https://api.twitter.com/1.1/users/show.json";
		return $this->performRequest(
			$this->twitter->setGetfield("?screen_name={$userId}")->buildOauth($url, 'GET')
		);
	}

	/**
	 * @param TwitterAPIExchange $request
	 * @return array
	 */
	private function performRequest(TwitterAPIExchange $request)
	{
		return json_decode($request->performRequest(), true);
	}

}

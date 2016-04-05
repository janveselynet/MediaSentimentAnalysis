<?php

namespace Model;

use Facebook\Facebook;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphEdge;
use Nette\Object;


class FacebookApi extends Object
{

	const DEFAULT_NUMBER_OF_POSTS = 100;

	/** @var Facebook */
	private $facebook;

	/** @var string */
	private $accessToken;


	/**
	 * @param string $appId
	 * @param string $appSecret
	 * @param param string $accessToken
	 */
	public function __construct($appId, $appSecret, $accessToken)
	{
		$this->facebook = new Facebook([
			'app_id' => $appId,
			'app_secret' => $appSecret,
			'default_graph_version' => 'v2.5'
		]);
		$this->accessToken = $accessToken;
	}

	/**
	 * @param string $pageId
	 * @return GraphEdge
	 */
	public function getInformation($pageId)
	{
		$response = $this->get("/{$pageId}?fields=likes,name");
		return $response->getGraphNode();
	}

	/**
	 * @param string $pageId
	 * @param int $limit
	 * @return GraphEdge
	 */
	public function getPosts($pageId, $limit = self::DEFAULT_NUMBER_OF_POSTS)
	{
		$response = $this->get("/{$pageId}/feed?limit={$limit}");
		return $response->getGraphEdge();
	}

	/**
	 * @param string $url
	 * @return FacebookResponse
	 */
	private function get($url)
	{
		return $this->facebook->get($url, $this->accessToken);
	}

}

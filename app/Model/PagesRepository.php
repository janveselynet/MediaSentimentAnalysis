<?php

namespace Model;

use Nette\Object;
use Nextras\Dbal\Connection;
use Nextras\Dbal\Result\Result;


class PagesRepository extends Object
{

	/** @var Connection */
	private $connection;


	/**
	 * @param Connection $connection
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @return Result
	 */
	public function findAllOrderByFbPositiveDesc()
	{
		return $this->connection->query('SELECT * FROM pages ORDER BY fb_positive DESC');
	}
	
	/**
	 * @param string $name
	 * @param string $pageId
	 * @param int $likes
	 * @param float $fbPositive
	 * @param float $fbNeutral
	 * @param float $fbNegative
	 * @param string $twitterId
	 * @param int $followers
	 * @param float $twPositive
	 * @param float $twNeutral
	 * @param float $twNegative
	 * @return void
	 */
	public function insertPage($name, $pageId, $likes, $fbPositive, $fbNeutral, $fbNegative, $twitterId, $followers,
							   $twPositive, $twNeutral, $twNegative)
	{
		$this->connection->query('INSERT INTO pages %values', [
			'name' => $name,
			'page_id' => $pageId,
			'likes' => $likes,
			'fb_positive' => $fbPositive,
			'fb_neutral' => $fbNeutral,
			'fb_negative' => $fbNegative,
			'twitter_id' => $twitterId,
			'followers' => $followers,
			'tw_positive' => $twPositive,
			'tw_neutral' => $twNeutral,
			'tw_negative' => $twNegative,
		]);
	}

	/**
	 * @param string $pageId
	 * @param int $likes
	 * @param float $fbPositive
	 * @param float $fbNeutral
	 * @param float $fbNegative
	 * @param float $twPositive
	 * @param float $twNeutral
	 * @param float $twNegative
	 * @return void
	 */
	public function updatePage($pageId, $likes, $fbPositive, $fbNeutral, $fbNegative, $twPositive, $twNeutral, $twNegative)
	{
		$this->connection->query('UPDATE pages SET %set WHERE page_id = %s', [
			'likes' => $likes,
			'fb_positive' => $fbPositive,
			'fb_neutral' => $fbNeutral,
			'fb_negative' => $fbNegative,
			'tw_positive' => $twPositive,
			'tw_neutral' => $twNeutral,
			'tw_negative' => $twNegative,
		], $pageId);
	}

	/**
	 * @param string $pageId
	 * @return void
	 */
	public function removePage($pageId)
	{
		$this->connection->query('DELETE FROM pages WHERE page_id = %s', $pageId);
	}

}

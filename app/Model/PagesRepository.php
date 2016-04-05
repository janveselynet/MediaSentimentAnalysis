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
	public function findAllOrderByPositiveDesc()
	{
		return $this->connection->query('SELECT * FROM pages ORDER BY positive DESC');
	}
	
	/**
	 * @param string $name
	 * @param string $pageId
	 * @param int $likes
	 * @param float $positive
	 * @param float $neutral
	 * @param float $negative
	 * @return void
	 */
	public function insertPage($name, $pageId, $likes, $positive, $neutral, $negative)
	{
		$this->connection->query('INSERT INTO pages %values', [
			'name' => $name,
			'page_id' => $pageId,
			'likes' => $likes,
			'positive' => $positive,
			'neutral' => $neutral,
			'negative' => $negative,
		]);
	}

	/**
	 * @param string $pageId
	 * @param int $likes
	 * @param float $positive
	 * @param float $neutral
	 * @param float $negative
	 * @return void
	 */
	public function updatePage($pageId, $likes, $positive, $neutral, $negative)
	{
		$this->connection->query('UPDATE pages SET %set WHERE page_id = %s', [
			'likes' => $likes,
			'positive' => $positive,
			'neutral' => $neutral,
			'negative' => $negative,
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

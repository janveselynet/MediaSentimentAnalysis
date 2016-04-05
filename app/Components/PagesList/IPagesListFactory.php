<?php

namespace Components\PagesList;


interface IPagesListFactory
{

	/**
	 * @return PagesList
	 */
	public function create();

}

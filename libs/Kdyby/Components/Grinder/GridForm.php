<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2011 Filip Procházka (filip.prochazka@kdyby.org)
 *
 * @license http://www.kdyby.org/license
 */

namespace Kdyby\Components\Grinder;

use Kdyby;
use Nette;



/**
 * @author Filip Procházka
 */
class GridForm extends Kdyby\Doctrine\Forms\Form
{
	/** @var \Kdyby\Components\Grinder\CollectionContainer */
	private $rows;



	/**
	 * @param \Kdyby\Doctrine\Registry $doctrine
	 */
	public function __construct(Kdyby\Doctrine\Registry $doctrine)
	{
		parent::__construct($doctrine);
		$this->monitor('Kdyby\Components\Grinder\Grid');
	}



	/**
	 */
	protected function configure()
	{
		$this->addContainer('columns');
		$this->addContainer('toolbar');

		// for javascript to select all rows
		$this->addCheckbox('checkAll', 'Select all %d results')
			->setDefaultValue(FALSE);
	}



	/**
	 * @param int $count
	 */
	public function setTotalResults($count)
	{
		$this['checkAll']->setAttribute('data-grinder-checkAll', $count);
		$this['checkAll']->caption = str_replace('%d', $count, $this['checkAll']->caption);
	}



	/**
	 * @param \Nette\ComponentModel\IComponent $obj
	 */
	protected function attached($obj)
	{
		if ($obj instanceof Grid) {
			$this->rows = new CollectionContainer($this->getGrid());
			$this->addComponent($this->rows, 'entity');
		}

		if ($obj instanceof \Nette\Application\UI\Presenter) {
			$this->getIdsContainer(); // create before signal!
		}

		parent::attached($obj);
	}



	/**
	 * @param string $column
	 *
	 * @return \Nette\Forms\Container
	 */
	public function getColumnContainer($column)
	{
		$column = str_replace('.', '__', $column);
		if (!$container = $this['columns']->getComponent($column, FALSE)) {
			$container = $this['columns']->addContainer($column);
		}
		return $container;
	}



	/**
	 * @return \Kdyby\Components\Grinder\CollectionContainer
	 */
	public function getRows()
	{
		return $this->rows;
	}



	/**
	 * @return \Nette\Forms\Container
	 */
	protected function getIdsContainer()
	{
		if ($container = $this->getComponent('ids', FALSE)) {
			return $container;
		}

		$container = $this->addContainer('ids');
		$perPage = $this->getGrid()->getItemsPerPage();
		for ($index = 0; $index < $perPage; $index++) {
			$container->addHidden($index);
		}

		return $container;
	}



	/**
	 * @param boolean $need
	 *
	 * @return \Kdyby\Components\Grinder\Grid
	 */
	public function getGrid($need = TRUE)
	{
		return $this->lookup('Kdyby\Components\Grinder\Grid', $need);
	}



	/**
	 * @param array $ids
	 */
	public function setRecordIds(array $ids)
	{
		$this->getIdsContainer()->setDefaults(array_values($ids));
	}



	/**
	 * @return array
	 */
	public function getRecordIds()
	{
		return $this->getIdsContainer()->getValues(TRUE);
	}

}

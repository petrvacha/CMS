<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2011 Filip Procházka (filip.prochazka@kdyby.org)
 *
 * @license http://www.kdyby.org/license
 */

namespace Kdyby\Package\ComponentsPackage\DI;

use Kdyby;
use Nette;



/**
 * @author Filip Procházka <filip.prochazka@kdyby.org>
 */
class ComponentsExtension extends Kdyby\Config\CompilerExtension
{

	/**
	 */
	public function loadConfiguration()
	{
		$this->addMacro('macrosGrid', 'Kdyby\Components\Grinder\Latte\GrinderMacroSet::install');
	}

}

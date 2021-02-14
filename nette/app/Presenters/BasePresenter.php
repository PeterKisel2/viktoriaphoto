<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\BasketComponentFactory;
use App\Components\BasketComponent;
use Nette\Application\UI\Presenter;


abstract class BasePresenter extends Presenter
{

	/** @var BasketComponentFactory @inject */
	public $basketComponentFactory;

    protected function createComponentBasketWidget(): BasketComponent
	{
		return $this->basketComponentFactory->create();
	}



	protected function beforeRender(): void
	{
		parent::beforeRender();

		$this['basketWidget']->redrawControl();
	}

}
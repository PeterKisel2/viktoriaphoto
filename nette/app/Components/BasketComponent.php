<?php


namespace App\Components;

use Nette\Application\UI\Control,
    App\Model\BasketManager;


class BasketComponent extends Control
{

    /** @var BasketManager */
	private $basket;


	public function __construct(BasketManager $basket)
	{
		$this->basket = $basket;
	}

    
	public function render(): void
	{
		$this->template->render(__DIR__ . '/BasketWidget.latte', [
			'itemsCount' => $this->basket->getItemsCount(),
			'totalPrice' => $this->basket->getTotalPrice(),
		]);
    }
    


}
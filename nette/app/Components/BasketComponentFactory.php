<?php declare(strict_types = 1);

namespace App\Components;


interface BasketComponentFactory
{

	public function create(): BasketComponent;

}
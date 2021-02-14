<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette,
    App\Model\ProductsManager,
    App\Model\BasketManager;


final class BasketPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */
    private $database;

    /** @var ProductsManager */
	private $productsManager;

    /** @var BasketManager */
	private $basketManager;

	public function __construct( BasketManager $basketManager, ProductsManager $productsManager )
	{
        $this->basketManager = $basketManager;
        $this->productsManager = $productsManager;
    }

    

    /**
     * Zakladna funkcia pre rendrovanie defaultnej stranky na HP 
     */
    public function renderDefault(): void
    {
        $this->template->basketProducts = $this->basketManager->getItems();

        $this->template->itemsCount = $this->basketManager->getItemsCount();
        $this->template->totalPrice = $this->basketManager->getTotalPrice();
    }


    /**
     * Ajaxove vymazanie produktu z kosika
     * @param int $prodId - id clanku, ktory chceme overit.
     */
    public function handleDelete(int $prodId) :void
    {
        if($this->isAjax()){
            if ( $this->basketManager->remove($prodId) ){
                $this->flashMessage('Zmazane z kosika', 'warning');
                $this->redrawControl('flashess');
                $this['basketWidget']->redrawControl();
                $this->redrawControl('productsnip');
            }
		}  
    }

    /**
     * @param int $prodId
     * Zvysenie poctu u konkretneho produktu o 1
     */
    public function handleIncrease(int $prodId) :void
    {
        if($this->isAjax()){
            $this->basketManager->add($this->productsManager->getProduct($prodId));
                $this['basketWidget']->redrawControl();
                $this->redrawControl('productsnip');
        
		}  
    }

    /**
     * @param int $prodId
     * Znizenie poctu u konkretneho produktu o 1.
     * Ak je 0 zmaze sa produkt z kosika.
     */
    public function handleDecrease(int $prodId) :void
    {
        if($this->isAjax()){
            $this->basketManager->decrease($this->productsManager->getProduct($prodId));
                $this['basketWidget']->redrawControl();
                $this->redrawControl('productsnip');
            
		}  
    }



}

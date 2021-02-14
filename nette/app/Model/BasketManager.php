<?php 

namespace App\Model;


use Nette,
    Nette\Http\Session,
    Nette\Http\SessionSection,
    App\Model\BasketItemManager;


class BasketManager
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    /** @var SessionSection */
    private $session;
    
  


    public function __construct(Nette\Database\Context $database, Session $session)
    {
        $this->database = $database;
        $this->session = $session->getSection(__CLASS__);
		$this->session['items'] = $this->session['items'] ?? [];
    }


    /**
	 * @return BasketProduct[] vytiahnutie vsetkych produktov v kosiku
	 */
	public function getItems(): array
	{
        // var_dump($this->session['items']);
		return $this->session['items'] ?? [];
    }

    /**
     * 
     */
    public function getItem($product)
	{
		foreach ($this->getItems() as $item) {
			if ($item->getId() === $product->getId()) {
				return $item;
			}
		}

		return null;
    }

    
    
    /**
     * Pridanie alebo zvysenie poctu v produkte
     */
    public function add($product)
	{
        $amount = 1;
        
		foreach ($this->getItems() as $item) {
            if(is_object($item)){
                
                if ($item->id === $product->id) {
                    $item->amount += $amount;
                    return true;
                }
            }
        }
        $basketItem = new BasketProduct;

        $this->session->items[] = $basketItem->setProduct($product, $amount);
        return true;
	}
    
    /**
     * Znizenie poctu daneho produktu v kosiku
     */
    public function decrease($product)
	{
		$amount = 1;

		foreach ($this->getItems() as $item) {
			if ($item->getId() === $product->id) {
				$item->amount -= $amount;
				if ($item->amount <= 0) {
					$this->remove($product->id);
				}

				return;
			}
        }
        
	}


    /**
     * Odstranienie produktu z kosiku
     */
	public function remove(int $productId)
	{
		$newItems = [];
		foreach ($this->getItems() as $item) {
			if ($item->id !== $productId) {
				$newItems[] = $item;
			}
		}

        $this->session->items = $newItems;
        return true;
	}

    /**
     * Získanie celkového počtu produktov v košíku
     */
	public function getItemsCount(): int
	{
        $itemsCount = 0;
            foreach ($this->getItems() as $item) {
                if(is_object($item)){
                    $itemsCount += $item->amount;
                }
                
            }

		return $itemsCount;
	}


    /**
     * Získanie celkovej ceny v kosiku
     */
	public function getTotalPrice(): float
	{
		$totalPrice = 0.0;
		foreach ($this->getItems() as $item) {
            // var_dump($item);
            // die();
            if(is_object($item)){
                $totalPrice += $item->getPrice() * $item->amount;
            }
		}

		return $totalPrice;
	}

}


/**
 * Trieda pre spravnu strukturu produktu do kosika do session
 */
class BasketProduct
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var float */
    public $price;

    /** @var int */
    public $amount;


    public function setProduct($product, $amount)
    {
        // var_dump($product->id);
        // var_dump($product->name);
        // var_dump($product->price);
        
        // var_dump($amount);
        // die();
        $this->id = $product->id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->amount = $amount;
        return $this;
    }

    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getPrice(){
        return $this->price;
    }
    public function getAmount(){
        return $this->amount;
    }
}


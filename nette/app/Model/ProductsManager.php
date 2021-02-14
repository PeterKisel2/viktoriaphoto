<?php

namespace App\Model;

use Nette;

class ProductsManager
{
    use Nette\SmartObject;
    
    const TABLE_NAME = 'products';

	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function getProducts()
	{
		return $this->database->table(self::TABLE_NAME)->order('id DESC');
    }

    public function saveProduct($values)
    {
        return $this->database->table(self::TABLE_NAME)->insert($values);
    }

    public function getProduct($postId){
        return $this->database->table(self::TABLE_NAME)->get($postId);
    }
    
    public function deleteProduct(int $postId)
    {
        return $this->database->table(self::TABLE_NAME)->where('id', $postId)->delete();
    }
}
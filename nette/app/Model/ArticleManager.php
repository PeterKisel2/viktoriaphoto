<?php

namespace App\Model;

use Nette;

class ArticleManager
{
	use Nette\SmartObject;
    
    const TABLE_NAME = 'posts';
    const COMMENTS_TABLE = 'comments';

	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function getPublicArticles()
	{
		return $this->database->table(self::TABLE_NAME)
			->where('created_at < ', new \DateTime)
			->order('created_at DESC');
    }

    public function savePost($values)
    {
        return $this->database->table(self::TABLE_NAME)->insert($values);
    }

    public function saveComment($values)
    {
        return $this->database->table(self::COMMENTS_TABLE)->insert($values);    
    }

    public function getPost($postId){
        return $this->database->table(self::TABLE_NAME)->get($postId);
    }
    
    public function deleteArticle(int $postId)
    {
        return $this->database->table(self::TABLE_NAME)->where('id', $postId)->delete();
    }
}
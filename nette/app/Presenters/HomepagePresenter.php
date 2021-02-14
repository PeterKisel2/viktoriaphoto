<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette,
    App\Model\ArticleManager;


final class HomepagePresenter extends BasePresenter
{
    /** @var Nette\Database\Context */
    private $database;

    /** @var ArticleManager */
	private $articleManager;

	public function __construct(Nette\Database\Context $database, ArticleManager $articleManager)
	{
        $this->database = $database;
        $this->articleManager = $articleManager;
    }

    

    /**
     * Zakladna funkcia pre rendrovanie defaultnej stranky na HP 
     */
    public function renderDefault(): void
    {
        $this->template->posts = $this->articleManager->getPublicArticles();
    }


    /**
     * Ajaxove vymazanie clanku po overeni dostatocnych prav po odoslani
     * @param int $postId - id clanku, ktory chceme overit.
     */
    public function handleDelete(int $postId) :void
    {
        if($this->isAjax()){
            
            $post = $this->articleManager->getPost($postId);
            if( $this->getUser()->isLoggedIn() && ( ( $this->getUser()->getId() == $post->user_id ) || $this->getUser()->isInRole('admin') ) )
            {
                if ( $this->articleManager->deleteArticle($postId) ){
                    $this->flashMessage('Príspevok bol zmazaný', 'success');
                    $this->redrawControl();
                }else{
                    $this->flashMessage('Nepodarilo sa', 'warning');
                    $this->redrawControl();
                }
            }else{
                $this->flashMessage('Nemáš dostatočné práva', 'danger');
                $this->redrawControl();
            }
			
            
		}  
    }



}

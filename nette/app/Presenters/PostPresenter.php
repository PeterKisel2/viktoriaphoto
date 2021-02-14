<?php 
declare(strict_types=1);
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form,
    App\Model\ArticleManager;


class PostPresenter extends BasePresenter
{

    /**
     * @persistent
     */
    public $backlink = '';

     /** @var ArticleManager */
	private $articleManager;

    
    
    

	public function __construct(ArticleManager $articleManager)
	{
        $this->articleManager = $articleManager;

	}


    /**
     * Vytiahnutie a zobrazenie jedneho prispevku
     * @param int $postId je id clanku
     * @return object select z DB
     */
	public function renderShow(int $postId): void
    {
        
        $post = $this->articleManager->getPost($postId);
        if (!$post) {
            $this->error('Stránka nebyla nalezena');
        }

        $this->backlink = $this->storeRequest();
        $this->template->post = $post;

	    $this->template->comments = $post->related('comment')->order('created_at');
    }


    /**
     * Vytvorenie komentarovej komponenty pod clanok
     */
    protected function createComponentCommentForm(): Form
    {
        $form = new Form; // means Nette\Application\UI\Form

        $form->addText('name', 'Jméno:')
        ->setHtmlAttribute('class', 'form-control')
            ->setRequired();

        $form->addEmail('email', 'E-mail:')
        ->setHtmlAttribute('class', 'form-control');

        $form->addTextArea('content', 'Komentář:')
            ->setHtmlAttribute('class', 'form-control')
            ->setRequired();

        $form->addSubmit('send', 'Publikovat komentář')
        ->setHtmlAttribute('class', 'btn btn-success');
        $form->onSuccess[] = [$this, 'commentFormSucceeded'];

        return $form;
    }

    /**
     * Po form submit komponente commentForm
     * @param \stdClass $values z formu
     * @param Form $form pre pripadne spracovanie: lespie pre validate pre pripadne vratenie erroru do formu
     */
    public function commentFormSucceeded(Form $form, \stdClass $values): void
    {
        $postId = $this->getParameter('postId');

        if ($this->articleManager->saveComment([
            'post_id' => $postId,
            'name' => $values->name,
            'email' => $values->email,
            'content' => $values->content,
        ]))
        {
            $this->flashMessage('Děkuji za komentář', 'success');
            $this->redirect('this');
        }else{
            $this->flashMessage('Nepodarilo sa ulozit komentar', 'danger');
            $this->redirect('this');
        }
        

        
    }

    /** Pridavanie clankov - komponenta na formular */
    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Titulek:')
        ->setHtmlAttribute('class', 'form-control')
            ->setRequired();
        $form->addTextArea('content', 'Obsah:')
        ->setHtmlAttribute('class', 'form-control')
            ->setRequired();

        $form->addSubmit('send', 'Uložit a publikovat')
        ->setHtmlAttribute('class', 'btn btn-success');
        $form->onSuccess[] = [$this, 'postFormSucceeded'];

        return $form;
    }

    /**
     * Po form submit komponente postForm
     * @param \stdClass $values z formu
     * @param Form $form pre pripadne spracovanie: lespie pre validate pre pripadne vratenie erroru do formu
     */
    public function postFormSucceeded(Form $form, array $values): void
    {
            $postId = $this->getParameter('postId');

            if ($postId) {
                $post = $this->articleManager->getPost($postId);
    
                    if($post){
                        $post->update($values);
                        $this->flashMessage('Príspevok bol úspešne upravený.', 'success');
                        $this->redirect('show', $post->id);
                    }
                
            } else {
                $values['user_id'] = $this->getUser()->getId();
                $post = $this->articleManager->savePost($values);
                if($post){
                    $this->flashMessage('Príspevok bol úspešne nahraný.', 'success');
                    $this->redirect('show', $post->id);
                }
                
            }
    

        
    }

    /**
     * Render akcie create
     * Overuje prihlasenie uzivatela
     */
    public function actionCreate(): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
        
    }

    /**
     * Render akcie edit
     * Overuje prihlasenie a prava na upravovanie clanku
     * @param int $postId / id clanku, ktory chceme upravovat
     */
    public function actionEdit(int $postId): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }

        $post = $this->articleManager->getPost($postId);
        if (!$post) {
            $this->error('Příspěvek nebyl nalezen');
        }

        
        if ( ( $this->getUser()->getId() != $post->user_id ) && !$this->getUser()->isInRole('admin') ){
            $this->flashMessage('Nemáte právo upravovať tento príspevok');
            $this->redirect('show', $post->id);
        }
        $this['postForm']->setDefaults($post->toArray());
    }

}
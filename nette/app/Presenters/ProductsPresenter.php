<?php 
declare(strict_types=1);
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form,
    App\Model\ProductsManager,
    App\Model\BasketManager;


class ProductsPresenter extends BasePresenter
{
	/** @var Nette\Database\Context */
    private $database;
    
    /**
     * @persistent
     */
    public $backlink = '';

     /** @var ProductsManager */
	private $productsManager;

    
       /** @var BasketManager */
	private $basketManager;
    

	public function __construct(ProductsManager $productsManager, BasketManager $basketManager)
	{
        
        $this->productsManager = $productsManager;
        $this->basketManager = $basketManager;

	}


    /**
     * Vytiahnutie a zobrazenie jedneho produktu
     * @param int $postId je id clanku
     * @return object select z DB
     */
	public function renderShow(int $postId): void
    {
        
        $post = $this->productsManager->getProduct($postId);
        if (!$post) {
            $this->error('Stránka nebyla nalezena');
        }

        $this->backlink = $this->storeRequest();
        $this->template->post = $post;

    }


    public function renderDefault()
    {
        $this->template->products = $this->productsManager->getProducts();
    }

    public function renderList()
    {
        if ( !$this->getUser()->isInRole('admin') ){
            $this->flashMessage('Nemáte právo pristupovať k produktom');
            $this->redirect('Products:default');
        }
        $this->template->products = $this->productsManager->getProducts();
    }

    /** Pridavanie produktov - komponenta na formular */
    protected function createComponentProductForm(): Form
    {
        $form = new Form;
        $form->addText('name', 'Názov produktu:')
            ->setHtmlAttribute('class', 'form-control')
            ->setRequired();
        $form->addText('description', 'Popis produktu (perex):')
            ->setHtmlAttribute('class', 'form-control')
            ->setRequired();
        $form->addText('price', 'Cena produktu')
            ->setHtmlAttribute('class', 'form-control')
            ->addRule($form::FLOAT, 'Cislo musi byt float')
            ->setRequired();
        $form->addTextArea('content', 'Obsah:')
            ->setHtmlAttribute('class', 'form-control')
            ->setRequired();

        $form->addSubmit('send', 'Uložit a publikovat')
        ->setHtmlAttribute('class', 'btn btn-success');
        $form->onSuccess[] = [$this, 'productFormSucceeded'];

        return $form;
    }

    /**
     * Po form submit komponente productForm
     * @param \stdClass $values z formu
     * @param Form $form pre pripadne spracovanie: lespie pre validate pre pripadne vratenie erroru do formu
     */
    public function productFormSucceeded(Form $form, array $values): void
    {
            $postId = $this->getParameter('postId');

            if ($postId) {
                $post = $this->productsManager->getProduct($postId);
    
                    if($post){
                        $post->update($values);
                        $this->flashMessage('Príspevok bol úspešne upravený.', 'success');
                        $this->redirect('edit', $post->id);
                    }
                
            } else {
                $post = $this->productsManager->saveProduct($values);
                if($post){
                    $this->flashMessage('Príspevok bol úspešne nahraný.', 'success');
                    $this->redirect('edit', $post->id);
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
        if ( !$this->getUser()->isInRole('admin') ){
            $this->flashMessage('Nemáte právo pridávať produkty');
            $this->redirect('show', $post->id);
        }
    }

    /**
     * Render akcie edit
     * Overuje prihlasenie a prava na upravovanie produktu
     * @param int $postId / id produktu, ktory chceme upravovat
     */
    public function actionEdit(int $postId): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }

        $post = $this->productsManager->getProduct($postId);
        if (!$post) {
            $this->error('Příspěvek nebyl nalezen');
        }

        
        if ( !$this->getUser()->isInRole('admin') ){
            $this->flashMessage('Nemáte právo upravovať produkty');
            $this->redirect('show', $post->id);
        }
        $this['productForm']->setDefaults($post->toArray());
    }

        /**
     * @param int $productId potrebny pre vytiahnutie objektu s produktom cez productManager
     * Nasledne pridanie do kosika flashmessage a znovunacitanie snippetu s flashes
     */

    public function handleDelete(int $productId) :void
    {
        if($this->isAjax())
        {
            if( $this->getUser()->isLoggedIn() && $this->getUser()->isInRole('admin') )
            {
                if ( $this->productsManager->deleteProduct($productId) ){
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
    

    /**
     * @param int $productId potrebny pre vytiahnutie objektu s produktom cez productManager
     * Nasledne pridanie do kosika flashmessage a znovunacitanie snippetu s flashes
     */
    public function handleAddToBasket($productId) :void
    {
        if($this->isAjax())
        {

            $this->basketManager->add($this->productsManager->getProduct($productId));

            $this->flashMessage('Pridane do kosika', 'success');
            $this->redrawControl('flashess');

        }
    }

}
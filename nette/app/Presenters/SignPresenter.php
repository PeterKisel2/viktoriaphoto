<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form,
    App\Forms\SignInFormFactory,
    App\Forms\SignUpFormFactory,
    App\Model\UserManager;


class SignPresenter extends BasePresenter
{
    /** @persistent */
    public $backlink = '';
    

    /** @var Forms\SignInFormFactory */
	private $signInFactory;
	/** @var Forms\SignUpFormFactory */
    private $signUpFactory;
    
    private $userManager;

	public function __construct(SignInFormFactory $signInFactory, SignUpFormFactory $signUpFactory, UserManager $userManager)
	{
		$this->signInFactory = $signInFactory;
        $this->signUpFactory = $signUpFactory;
        $this->userManager = $userManager;
    }
    

	/**
	 * Sign-in form factory pre vytvorenine prihlasovacieho adresara
	 */
	protected function createComponentSignInForm(): Form
	{
		return $this->signInFactory->create(function (): void {
			$this->restoreRequest($this->backlink);
			$this->redirect('Homepage:');
		});
    }
    
	/**
	 * Sign-up form factory pre vytvorenie registracneho formularu
	 */
	protected function createComponentSignUpForm(): Form
	{
		return $this->signUpFactory->create(function (): void {
			$this->redirect('Homepage:');
		});
	}
  

    /**
     * Odhlasenie uzivatela z webu
     */
    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->flashMessage('Odhlásenie bolo úspešné.');
        $this->redirect('Homepage:');
    }


    /**
     * Overenie unikatneho loginu cez ajax funkciu
     */
    public function handleCheckUnique()
	{
		if($this->isAjax()){
			$post = $this->getRequest()->getPost();
			$this->payload->response = $this->userManager->checkLogin($post['login']);
			$this->sendPayload();
		}  
	}
}
<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model;
use Nette;
use Nette\Application\UI\Form;


final class SignUpFormFactory
{
	use Nette\SmartObject;

	private const PASSWORD_MIN_LENGTH = 8;

	/** @var Model\UserManager */
	private $userManager;


	public function __construct(Model\UserManager $userManager)
	{
		
		$this->userManager = $userManager;
	}

	/**
	* create method.
	* @return SignUpForm
	*/
	public function create(callable $onSuccess): Form
	{
		$form = new Form;
		$form->addText('login', 'Zvolte login:')
			->setHtmlAttribute('class', 'form-control checkUnique')
			->setRequired('Prosim zvolte si unikatny login.');

		$form->addText('fullname', 'Cele meno:')
			->setHtmlAttribute('class', 'form-control')
			->setRequired('Prosim zvolte si cele meno.');

		$form->addPassword('password', 'vytvorte si heslo:')
			->setHtmlAttribute('class', 'form-control')
			->setOption('description', sprintf('Heslo musí mať minimálne %d znakov', self::PASSWORD_MIN_LENGTH))
			->setRequired('Prosím vložte heslo.')
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

		$form->addPassword('password_verify', 'Zadajte heslo na kontrolu')
			->setHtmlAttribute('class', 'form-control')
			->setRequired('Zadajte aj kontrolné heslo')
			->addRule(Form::EQUAL,'Heslo sa nezhoduje s heslom na kontrolu.', $form['password']);

		$form->addSubmit('send', 'Sign up')
		->setHtmlAttribute('class', 'btn btn-success');

		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
			try {
				$this->userManager->add($values->login, $values->fullname, 'creator', $values->password);
			} catch (Model\DuplicateException $e) {
				$form['login']->addError('Login je už použitý.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}

<?php

declare(strict_types=1);

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


final class SignInFormFactory
{
	use Nette\SmartObject;

	/** @var User */
	private $user;


	public function __construct(User $user)
	{
		$this->user = $user;
	}


	public function create(callable $onSuccess): Form
	{
		$form = new Form;
		$form->addText('login', 'Login:')
			->setHtmlAttribute('class', 'form-control')
			->setRequired('Prosím vyplň svoj login.');

		$form->addPassword('password', 'Heslo:')
			->setHtmlAttribute('class', 'form-control')
			->setRequired('Prosím vlož svoje heslo.');

		$form->addCheckbox('remember', 'Zostať prihlásený');

		$form->addSubmit('send', 'Prihlásiť sa')
		->setHtmlAttribute('class', 'btn btn-success');

		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
			try {
				$this->user->setExpiration($values->remember ? '14 days' : '1440 minutes');
				$this->user->login($values->login, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError('Zadaný login alebo heslo nieje správne.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}

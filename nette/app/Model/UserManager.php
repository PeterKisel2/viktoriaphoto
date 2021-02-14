<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
final class UserManager implements Nette\Security\IAuthenticator
{
	use Nette\SmartObject;

	private const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_LOGIN = 'login',
		COLUMNS_NAME = 'fullname',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_ROLE = 'role';


	/** @var Nette\Database\Context */
	private $database;

	/** @var Passwords */
	private $passwords;


	public function __construct(Nette\Database\Context $database, Passwords $passwords)
	{
		$this->database = $database;
		$this->passwords = $passwords;
	}


	/**
	 * Performs an authentication.
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials): Nette\Security\IIdentity
	{
		[$username, $password] = $credentials;

		$row = $this->database->table(self::TABLE_NAME)
			->where(self::COLUMN_LOGIN, $username)
			->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!$this->passwords->verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif ($this->passwords->needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$row->update([
				self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);
		return new Nette\Security\Identity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
	}


	/**
	 * Adds new user.
	 * @throws DuplicateLoginException
	 */
	public function add(string $login, string $name, string $role, string $password)
	{

		try {
			$this->database->table(self::TABLE_NAME)->insert([
				self::COLUMN_LOGIN => $login,
				self::COLUMNS_NAME => $name,
				self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password),
				self::COLUMN_ROLE => $role,
			]);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateException;	
		}
	}

	public function update($id, array $values)
	{	
		foreach ($values as $key => $value) {
			if (empty($value)) {
			   unset($values[$key]);
			}
		}
		
		if (isset($values['new_password'])){
			$values['password'] = $this->passwords->hash($values['new_password']);
		}
		unset($values['old_password']);
		unset($values['new_password']);

		try {
			$this->database->table(self::TABLE_NAME)->where('id', $id)->update($values);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateException;	
		}
	}


	//Kontrola duplicitneho loginu
	public function checkLogin(string $login){
		
		$check = $this->database->table(self::TABLE_NAME)->where('login', $login)->fetchPairs('login');
		return ( !empty($check) ? true : false ); 
	}


	//Kontrola stareho hesla pre zmenu hesielkaaaa
	public function checkChangePassword($id, string $old_password)
	{
		$user = $this->database->table(self::TABLE_NAME)->where('id', $id)->fetch('password');
		// print_r($user->password);
		$verify = $this->passwords->verify($old_password, $user->password);
		
		return $verify;
	}
	
}



class DuplicateException extends \Exception
{
}


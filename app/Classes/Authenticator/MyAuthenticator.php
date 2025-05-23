<?php

declare(strict_types=1);

namespace Nette\Security;

use Nette;
use Nette\Database\Explorer;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nette\Security\AuthenticationException;

class MyAuthenticator implements Nette\Security\Authenticator {
	private $database;
	private $passwords;

	public function __construct(Explorer $database, Passwords $passwords) {
		$this->database = $database;
		$this->passwords = $passwords;
	}

	public function authenticate(string $user, string $password): IIdentity {
		$row = $this->database->table(USERSTABLE)->where('username', $user)->fetch();
		
		if (!$row) { //utente non esiste
			throw new AuthenticationException('Email o password errate.');
		}

		if (!$this->passwords->verify($password, $row->password)) {
			throw new AuthenticationException('Password errata.');
		}
		
		return new SimpleIdentity (
			$row->id,
			//$row->ruolo,
			[	
				'utente' => $row->username,
				'email' => $row->email
			]
		);
	}
}

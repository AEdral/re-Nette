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
		$password = addslashes($password); //escape degli apici nella password (test" -> test\")

		if (!$row) { //utente non esiste
			throw new AuthenticationException('Email o password errate.');
		}

		if ($row->password != md5($password)){ //password sbagliata
			throw new AuthenticationException('Email o password errate.');
		}
		
		return new SimpleIdentity (
			$row->id,
			$row->ruolo,
			[	
				'utente' => $row->utente,
				'ruolo' => $row->ruolo
			]
		);
	}
}

<?php
 
declare(strict_types=1);
 
namespace Nette\Security;
 
use Nette;
 
class MyAuthorizator implements Authorizator {
 
    public function __construct() { }

	public function isAllowed($role, $resource, $operation): bool {
		

		//Se l'utente corrente ha un ruolo admin ha i permessi per accedere a tutto
		if ($role === 'admin'){
			//bdump("sei un admin!");
			return true;
		} else { //tutti gli altri devono solo poter generare le lettere
			/*
			$acl = new Permission;
			$acl->addRole('admin_comune');
			$acl->addRole('user');
			$acl->addResource('Dashboard');
			$acl->addResource('GeneraLettere');
			$acl->addResource('TemplateManager');
			$acl->deny('user', 'Dashboard');
			$acl->deny('user', 'TemplateManager');
			$acl->allow('user', 'GeneraLettere');
			return $acl->isAllowed('user', $resource, $operation);
			*/
			return true;
		}
	}
	
}
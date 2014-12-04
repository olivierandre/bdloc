<?php

	namespace Bdloc\AppBundle\Service;

	// Il faut le répertorier au niveau du fichier "config.yml"
	class CryptPassword {

		private $doctrine;

		public function __construct($doctrine) {
			$this->doctrine = $doctrine;
		}

		public function test() {
			die("test");
		}

	}
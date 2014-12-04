<?php

	namespace Bdloc\AppBundle\Util;

	class StringHelper {

		public function getASCII() {
        $chaineFinale = array();
        $indiceNumberZero = 48;
        $indiceNumberNeuf = 57;

        for(;$indiceNumberZero <= $indiceNumberNeuf; $indiceNumberZero++) {
            $chaineFinale[] .= chr($indiceNumberZero);
        }

        $indiceLettreA = 65;
        $indiceLettreZ = 90;

        for(;$indiceLettreA <= $indiceLettreZ; $indiceLettreA++) {
            $chaineFinale[] .= chr($indiceLettreA);
        }

        $indiceLettrea = 97;
        $indiceLettrez = 122;

        for(;$indiceLettrea <= $indiceLettrez; $indiceLettrea++) {
            $chaineFinale[] .= chr($indiceLettrea);
        }

        //  Etoile
        $chaineFinale[] .= chr(42);
        //  Point
        $chaineFinale[] .= chr(46); 

        return $chaineFinale;
    }

    public function randomSalt($chaine, $taille = 50) {
        $chaineFinale = "";

        for($i = 0; $i < $taille; $i++) {
            shuffle($chaine);
            $chaineFinale .= $chaine[0];
        }
        return $chaineFinale;
    }

    public function getSaltToken() {
        return $this->randomSalt($this->getASCII());
    }
}
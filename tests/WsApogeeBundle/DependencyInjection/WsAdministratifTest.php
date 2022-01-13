<?php

namespace App\Tests\WsApogeeBundle\DependencyInjection;

use App\WsApogeeBundle\DependencyInjection\WsAdministratif;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * RUN :  php ./vendor/bin/phpunit
 */
class WsAdministratifTest extends KernelTestCase
{
    public function testRecupererCursusExterne()
    {
        $ws = $this->getContainer()->get(WsAdministratif::class);

        $cursus = $ws->recupererCursusExterne('21811548');

        // Récupère quelque chose ?
        $this->assertNotNull($cursus);
        // Présence de l'objet recupererCursusExterneReturn
        $this->assertIsBool(is_object($cursus->recupererCursusExterneReturn));
    }

    public function testrecupererIAEtapes_v3()
    {
        self::bootKernel();

        $container = self::getContainer();

        $ws = $container->get(WsAdministratif::class);

        $etapes = $ws->recupererIAEtapes_v3('21811548');

        // Récupère quelque chose ?
        $this->assertNotNull($etapes);
        // Présence de l'objet recupererIAEtapes_v3Return
        $this->assertIsBool(is_object($etapes->recupererIAEtapes_v3Return));
    }

}
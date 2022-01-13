<?php

namespace App\Controller;

use App\WsApogeeBundle\DependencyInjection\EtatIA;
use App\WsApogeeBundle\DependencyInjection\WsAdministratif;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     *
     * @Route("/", name="main")
     * @param WsAdministratif $ws
     * @return Response
     */
    public function main(WsAdministratif $ws): Response
    {
		$ws->setFastResponse(true);

        $anneesIa = $ws->recupererAnneesIa('21811548', EtatIA::enCours());
        dump($anneesIa);

        $iaEtapesV2 = $ws->recupererIAEtapes_v2('21811548', '2021');
        dump($iaEtapesV2);

        $cursus = $ws->recupererCursusExterne('21811548');
        dump($cursus);

        $iaAnnuellesV2 = $ws->recupererIAAnnuelles_v2('21811548');
        dump($iaAnnuellesV2);

        $iaEtapesV3 = $ws->recupererIAEtapes_v3('21811548');
        dump($iaEtapesV3);

        return $this->render('base.html.twig');
    }
}
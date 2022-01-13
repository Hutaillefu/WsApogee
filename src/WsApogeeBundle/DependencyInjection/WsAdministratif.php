<?php

namespace App\WsApogeeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EtatIA
{
    private $etat;

    public function getEtat(): string
    {
        return $this->etat;
    }

    private function __construct(string $etat)
    {
        $this->etat = $etat;
    }

    /**
     * Si on souhaite que le connecteur récupère uniquement les années des IA de l’étudiant dont l’état est à « A » dans Apogée (Annulé).
     * @return EtatIA
     */
    public static function annule(): EtatIA
    {
        return new EtatIA('A');
    }

    /**
     * Si on souhaite que le connecteur récupère uniquement les années des IAA de l’étudiant dont l’état est à « R » dans Apogée (Résilié).
     * @return EtatIA
     */
    public static function resilie(): EtatIA
    {
        return new EtatIA('R');
    }

    /**
     * Si on souhaite que le connecteur récupère uniquement les années des IAA de l’étudiant dont l’état est à « E » dans Apogée (En cours).
     * @return EtatIA
     */
    public static function enCours(): EtatIA
    {
        return new EtatIA('E');
    }

    /**
     * Si on souhaite que le connecteur récupère uniquement les années des IAA de l’étudiant dont l’état est à « A » ou à « R ».
     * @return EtatIA
     */
    public static function ar(): EtatIA
    {
        return new EtatIA('AR');
    }

    /**
     * Si on souhaite que le connecteur récupère uniquement les années des IAA de l’étudiant dont l’état est à « A » ou à « E ».
     * @return EtatIA
     */
    public static function ae(): EtatIA
    {
        return new EtatIA('AE');
    }

    /**
     * Si on souhaite que le connecteur récupère uniquement les années des IAA de l’étudiant dont l’état est à « R » ou à « E ».
     * @return EtatIA
     */
    public static function re(): EtatIA
    {
        return new EtatIA('RE');
    }

    /**
     * Si on souhaite que le connecteur récupère toutes les années des IAA de l’étudiant quel que soit l’état.
     * @return EtatIA
     */
    public static function tout(): EtatIA
    {
        return new EtatIA('ARE');
    }
}

/**
 * Consultation par d’autres applications du SI des données de l’étudiant à partir d’un identifiant :
 * - Outils de Médecine préventive,
 * - Outils de gestion des emplois du temps,
 * - Plate-forme de formation à distance,
 * - Outils de gestion régionaux,
 * - Outils de pré-inscription, de candidature,
 * - Outils de gestion des relations internationales,
 * - Outils de gestion de la formation continue,
 * - Outils de gestion des bibliothèques,
 * - Graal pour les doctorants,
 * - Annuaires,
 * - Outil de gestion du service de VAE,
 * o …
 */
class WsAdministratif extends WebService
{
    public function __construct(ParameterBagInterface $params)
    {
        parent::__construct($params->get('ws_apogee_url'), $params->get('ws_apogee_administratif'), ['trace' => 1]);
    }

    /**
     * @param string $codEtu Code universitaire de l’étudiant
     * @return mixed
     */
    public function recupererCursusExterne(string $codEtu)
    {
        return $this->soapCall('recupererCursusExterne', ['_codEtu' => $codEtu]);
    }

    /**
     * @param string $codEtu Code universitaire de l’étudiant
     * @param string $annee Année universitaire demandée
     * @param EtatIA|null $etatIAA Etat d’inscription administrative à l’étape souhaité
     * @param EtatIA|null $etatIAE Année dont nous voulons les inscriptions administratives de l’étudiant
     * @return mixed
     */
    public function recupererIAEtapes_v2(string $codEtu, string $annee = '', EtatIA $etatIAA = null, EtatIA $etatIAE = null)
    {
        $etatIAA = $etatIAA != null ? $etatIAA : EtatIA::enCours();
        $etatIAE = $etatIAE != null ? $etatIAE : EtatIA::enCours();

        return $this->soapCall('recupererIAEtapes_v2', [
            '_codEtu' => $codEtu,
            '_annee' => $annee,
            '_etatIAA' => $etatIAA->getEtat(),
            '_etatIAE' => $etatIAE->getEtat()
        ]);
    }

    /**
     * @param string $codEtu Code universitaire de l’étudiant
     * @param string $annee Année universitaire demandée
     * @param EtatIA|null $etatIAA Etat d’inscription administrative à l’étape souhaité
     * @param EtatIA|null $etatIAE Année dont nous voulons les inscriptions administratives de l’étudiant
     * @return mixed
     */
    public function recupererIAEtapes_v3(string $codEtu, string $annee = '', EtatIA $etatIAA = null, EtatIA $etatIAE = null)
    {
        $etatIAA = $etatIAA != null ? $etatIAA : EtatIA::enCours();
        $etatIAE = $etatIAE != null ? $etatIAE : EtatIA::enCours();

        return $this->soapCall('recupererIAEtapes_v3', [
            '_codEtu' => $codEtu,
            '_annee' => $annee,
            '_etatIAA' => $etatIAA->getEtat(),
            '_etatIAE' => $etatIAE->getEtat()
        ]);
    }

    /**
     * @param string $codEtu Code universitaire de l’étudiant
     * @param EtatIA|null $etatInscriptionIA Etat inscription administrative annuelle
     * @return mixed
     */
    public function recupererAnneesIa(string $codEtu, EtatIA $etatInscriptionIA = null)
    {
        $etatInscriptionIA = $etatInscriptionIA != null ? $etatInscriptionIA : EtatIA::enCours();

        return $this->soapCall('recupererAnneesIa', [
            '_codEtu' => $codEtu,
            '_etatInscriptionIA' => $etatInscriptionIA->getEtat()
        ]);
    }

    /**
     * @param string $codEtu Code universitaire de l’étudiant
     * @param string $annee Année des informations administratives de l’étudiant
     * @param EtatIA|null $etatIAA Etat d’inscription administrative annuelle souhaité
     * @return mixed
     */
    public function recupererIAAnnuelles_v2(string $codEtu, string $annee = '', EtatIA $etatIAA = null)
    {
        $etatIAA = $etatIAA != null ? $etatIAA : EtatIA::enCours();

        return $this->soapCall('recupererIAAnnuelles_v2', [
            '_codEtu' => $codEtu,
            '_annee' => $annee,
            '_etatIAA' => $etatIAA->getEtat()
        ]);
    }
}
<?php

use mvc_profil\ProfilModel;

require_once 'mvc_profil/ProfilModel.php';
require_once 'mvc_profil/ProfilView.php';

/**
 * Contrôleur pour la gestion du profil
 *
 * Cette classe gère la logique métier entre le modèle {@see ProfilModel}
 * et la vue {@see ProfilView}, dans le cadre du pattern MVC.
 *
 * Elle permet de :
 * - afficher ses informations
 * - modifier ses informations
 */

class ProfilController {
    private $profilModel;
    private $profilView;


    /**
     * Constructeur : initialise le modèle et la vue associés au profil
     */
    public function __construct() {
        $this->profilModel = new ProfilModel();
        $this->profilView = new ProfilView();
    }




///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////

    // A FAIRE CE QUI SUIT !! DETAIL + MODIF

///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////


    /**
     * Modifie un vendeur existant
     *
     * Si la requête est en POST et valide, tente la modification :
     *   - redirige vers la liste si succès,
     *   - affiche le formulaire de modification avec erreurs si échec.
     * Si la requête est en GET :
     *  - affiche le formulaire prérempli si le vendeur existe,
     *  - sinon affiche une erreur.
     *
     * @param int $Id_vendeur Identifiant du vendeur à modifier
     *
     * @return void
     */
    public function modifierInfo($Id_vendeur) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['nom'], $_POST['prenom'], $_POST['mail'])) {
                // Tente la modification
                $erreur = $this->profilModel->modifier($_POST['nom'], $_POST['prenom'], $_POST['mail'], $Id_vendeur);
                if ($erreur === null) {
                    // Si succès : Redirection vers la liste après modification
                    $this->profilView->redirigerVersProfil();
                } else {
                    // Si échec : Affiche le formulaire avec message d'erreur
                    $vendeur = ProfilModel::loadById($Id_vendeur);
                    if ($vendeur) {
                        $this->profilView->afficherFormulaireModificationAvecDonnees($vendeur, $erreur);
                    } else {
                        $this->profilView->afficherErreurVendeurIntrouvable();
                    }
                }
            }
        } else {
            // Affichage du formulaire avec les données existantes
            $vendeur = ProfilModel::loadById($Id_vendeur);
            if ($vendeur) {
                $this->profilView->afficherFormulaireModificationAvecDonnees($vendeur);
            } else {
                $this->profilView->afficherErreurVendeurIntrouvable();
            }
        }
    }

    /**
     *  Affiche le détail du vendeur connecté
     *
     *  - Si le vendeur est trouvé, affiche le formulaire de détail,
     *  - Sinon affiche une erreur "introuvable"
     *
     * @param int $Id_vendeur Identifiant vendeur
     * @return void
     */
    public function voirDetail($Id_vendeur) {
        // Affichage du formulaire avec les données existantes
        $vendeur = ProfilModel::loadById($Id_vendeur);
        if ($vendeur) {
            $this->profilView->afficherFormulaireDetailAvecDonnees($vendeur);
        } else {
            $this->profilView->afficherErreurVendeurIntrouvable();
        }
    }






    /**
     * Modifie un vendeur existant
     *
     * Si la requête est en POST et valide, tente la modification :
     *   - redirige vers la liste si succès,
     *   - affiche le formulaire de modification avec erreurs si échec.
     * Si la requête est en GET :
     *  - affiche le formulaire prérempli si le vendeur existe,
     *  - sinon affiche une erreur.
     *
     * @param int $Id_vendeur Identifiant du vendeur à modifier
     *
     * @return void
     */
    public function modifierMdp($Id_vendeur) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['nom'], $_POST['prenom'], $_POST['mail'])) {

                // A FAIRE
                // Retrait des espaces en début et fin chaîne
                // vérifier que le mdp actuel est correct sinon afficher erreur
                // vérifier que new mdp + confirmation sont identiques sinon afficher erreur
                // hachage du new mdp
                // modif en BDD


                // Tente la modification
                $erreur = $this->profilModel->modifier($_POST['nom'], $_POST['prenom'], $_POST['mail'], $Id_vendeur);
                if ($erreur === null) {
                    // Si succès : Redirection vers la liste après modification
                    $this->profilView->redirigerVersProfil();
                } else {
                    // Si échec : Affiche le formulaire avec message d'erreur
                    $vendeur = ProfilModel::loadById($Id_vendeur);
                    if ($vendeur) {
                        $this->profilView->afficherFormulaireModificationAvecDonnees($vendeur, $erreur);
                    } else {
                        $this->profilView->afficherErreurVendeurIntrouvable();
                    }
                }
            }
        } else {
            // Affichage du formulaire avec les données existantes
            $vendeur = ProfilModel::loadById($Id_vendeur);
            if ($vendeur) {
                $this->profilView->afficherFormulaireModificationAvecDonnees($vendeur);
            } else {
                $this->profilView->afficherErreurVendeurIntrouvable();
            }
        }
    }





}
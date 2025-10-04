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
            if (isset($_POST['mdp'], $_POST['new_mdp'], $_POST['confirm_mdp'])) {

                // Retrait des espaces en début et fin chaîne
                $mdp = trim($_POST['mdp']);
                $new_mdp = trim($_POST['new_mdp']);
                $confirm_mdp = trim($_POST['confirm_mdp']);

                // Vérifie que les champs sont non vides
                if (empty($mdp) || empty($new_mdp) || empty($confirm_mdp)) {
                    $vendeur = ProfilModel::loadById($Id_vendeur);
                    if ($vendeur) {
                        $erreur = "Tous les champs sont obligatoires.";
                        $this->profilView->afficherFormulaireDetailAvecDonneesMdp($vendeur, $erreur);
                    } else {
                        $this->profilView->afficherErreurVendeurIntrouvable();
                    }
                }

                // Vérifie que les nouveaux mots de passe correspondent
                if ($new_mdp !== $confirm_mdp) {
                    $vendeur = ProfilModel::loadById($Id_vendeur);
                    if ($vendeur) {
                        $erreur = "Le nouveau mot de passe et sa confirmation sont différents.";
                        $this->profilView->afficherFormulaireDetailAvecDonneesMdp($vendeur, $erreur);
                    } else {
                        $this->profilView->afficherErreurVendeurIntrouvable();
                    }
                }

                // Récupération de l'utilisateur connecté
                $utilisateur = $_SESSION['utilisateur'];

                // Vérifie que le mot de passe actuel est correct
                if ($this->profilModel->verifierMotDePasse($mdp, $utilisateur['Mdp_vendeur'])) {

                    // Tente la modification
                    $success = $this->profilModel->modifierMdp($_POST['new_mdp'], $Id_vendeur);
                    if ($success) {
                        $this->profilView->redirigerVersProfil();
                    }
                } else {
                    $vendeur = ProfilModel::loadById($Id_vendeur);
                    $erreur = "Le mot de passe actuel est incorrect.";
                    $this->profilView->afficherFormulaireDetailAvecDonneesMdp($vendeur, $erreur);
                }
            }
        } else {
            // Affichage du formulaire avec les données existantes
            $vendeur = ProfilModel::loadById($Id_vendeur);
            if ($vendeur) {
                $this->profilView->afficherFormulaireDetailAvecDonnees($vendeur);
            } else {
                $this->profilView->afficherErreurVendeurIntrouvable();
            }
        }
    }
}
<?php

use mvc_vendeur\VendeurModel;

require_once 'mvc_vendeur/VendeurModel.php';
require_once 'mvc_vendeur/VendeurView.php';

/**
 * Contrôleur pour la gestion des vendeurs
 *
 * Cette classe gère la logique métier entre le modèle {@see VendeurModel}
 * et la vue {@see VendeurView}, dans le cadre du pattern MVC.
 *
 * Elle permet de :
 * - lister les vendeurs
 * - ajouter un vendeur
 * - modifier un vendeur
 * - supprimer un vendeur
 * - afficher le détail d’un vendeur
 */

class VendeurController {
    private $vendeurModel;
    private $vendeurView;


    /**
     * Constructeur : initialise le modèle et la vue associés aux vendeurs
     */
    public function __construct() {
        $this->vendeurModel = new VendeurModel();
        $this->vendeurView = new VendeurView();
    }

    /**
     * Affiche la liste de tous les vendeurs
     *
     * @return void
     */
    public function liste() {
        $vendeurs = $this->vendeurModel->lister();
        $this->vendeurView->afficherListe($vendeurs);
    }

    /**
     * Ajoute un nouveau vendeur
     *
     * Si la requête est en POST et valide, tente l’ajout en base de données :
     *   - redirige vers la liste si succès,
     *   - affiche le formulaire avec un message d’erreur si échec.
     * Sinon affiche le formulaire d’ajout vide
     *
     * @return void
     */
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['nom'], $_POST['prenom'], $_POST['role'], $_POST['mail'], $_POST['mdp'])) {
                // Tente l'ajout
                $erreur = $this->vendeurModel->ajouter($_POST['nom'], $_POST['prenom'], $_POST['role'], $_POST['mail'], $_POST['mdp']);

                if ($erreur === null) {
                    // Si succès : Redirection vers la liste après ajout
                    $this->vendeurView->redirigerVersListe();
                } else {
                    // Si échec : Affiche le formulaire avec message d'erreur
                    $this->vendeurView->afficherFormulaireAjout($erreur);
                }
            }
        } else {
            // Affiche le formulaire vide
            $this->vendeurView->afficherFormulaireAjout(null);
        }
    }

    /**
     * Supprime un vendeur à partir de son identifiant (passé en GET)
     *
     * @return void
     */
    public function delete() {
        $this->vendeurModel->delete($_GET['Id_vendeur']);
        // Rappeler la fonction pour afficher
        $this->liste();
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
    public function modifier($Id_vendeur) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['nom'], $_POST['prenom'], $_POST['role'], $_POST['mail'], $_POST['mdp'])) {
                // Tente la modification
                $erreur = $this->vendeurModel->modifier($_POST['nom'], $_POST['prenom'], $_POST['role'], $_POST['mail'], $_POST['mdp'], $Id_vendeur);
                if ($erreur === null) {
                    // Si succès : Redirection vers la liste après modification
                    $this->vendeurView->redirigerVersListe();
                } else {
                    // Si échec : Affiche le formulaire avec message d'erreur
                    $vendeur = VendeurModel::loadById($Id_vendeur);
                    if ($vendeur) {
                        $this->vendeurView->afficherFormulaireModificationAvecDonnees($vendeur, $erreur);
                    } else {
                        $this->vendeurView->afficherErreurVendeurIntrouvable();
                    }
                }
            }
        } else {
            // Affichage du formulaire avec les données existantes
            $vendeur = VendeurModel::loadById($Id_vendeur);
            if ($vendeur) {
                $this->vendeurView->afficherFormulaireModificationAvecDonnees($vendeur);
            } else {
                $this->vendeurView->afficherErreurClientIntrouvable();
            }
        }
    }

    /**
     *  Affiche le détail d’un vendeur existant
     *
     *  - Si le vendeur est trouvé, affiche le formulaire de détail,
     *  - Sinon affiche une erreur "introuvable"
     *
     * @param int $Id_vendeur Identifiant vendeur
     * @return void
     */
    public function voirDetail($Id_vendeur) {
        // Affichage du formulaire avec les données existantes
        $vendeur = VendeurModel::loadById($Id_vendeur);
        if ($vendeur) {
            $this->vendeurView->afficherFormulaireDetailAvecDonnees($vendeur);
        } else {
            $this->vendeurView->afficherErreurVendeurIntrouvable();
        }
    }
}
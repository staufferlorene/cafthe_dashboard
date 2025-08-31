<?php

use mvc_commande\CommandeModel;

require_once 'mvc_commande/CommandeModel.php';
require_once 'mvc_commande/CommandeView.php';

/**
 * Contrôleur pour la gestion des commandes
 *
 * Cette classe gère la logique métier entre le modèle {@see CommandeModel}
 * et la vue {@see CommandeView}, dans le cadre du pattern MVC.
 *
 * Elle permet de :
 * - lister les commandes
 * - modifier une commande
 * - afficher le détail d’une commande
 */

class CommandeController {
    private $commandeModel;
    private $commandeView;


    /**
     * Constructeur : initialise le modèle et la vue associés aux commandes
     */
    public function __construct() {
        $this->commandeModel = new CommandeModel();
        $this->commandeView = new CommandeView();
    }

    // Afficher les commandes
    public function liste() {
        $commandes = $this->commandeModel->lister();
        $this->commandeView->afficherListe($commandes);
    }

    /**
     * Modifie une commande existante
     * *
     * * Si la requête est en POST et valide, tente la modification :
     * *   - redirige vers la liste si succès,
     * *   - affiche le formulaire de modification avec erreurs si échec.
     * * Si la requête est en GET :
     * *  - affiche le formulaire prérempli si la commande existe,
     * *  - sinon affiche une erreur.
 *
     * @param int $Id_commande Identifiant de la commande à modifier
     *
     * @return void
     */

    public function modifier($Id_commande) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['statut'])) {
                // Tente la modification
                $erreur = $this->commandeModel->modifier($_POST['statut'], $Id_commande);
                if ($erreur === null) {
                    // Si succès : Redirection vers la liste après modification
                    $this->commandeView->redirigerVersListe();
                } else {
                    // Si échec : Affiche le formulaire avec message d'erreur
                    $commande = CommandeModel::loadById($Id_commande);
                    if ($commande) {
                        $this->commandeView->afficherFormulaireModificationAvecDonnees($commande, $erreur);
                    } else {
                        $this->commandeView->afficherErreurCommandeIntrouvable();
                    }
                }
            }
        } else {
            // Affichage du formulaire avec les données existantes
            $commande = CommandeModel::loadById($Id_commande);
            if ($commande) {
                $this->commandeView->afficherFormulaireModificationAvecDonnees($commande);
            } else {
                $this->commandeView->afficherErreurCommandeIntrouvable();
            }
        }
    }

    /**
     *  Affiche le détail d’une commande existante
     *
     *  - Si la commande est trouvée, affiche le formulaire de détail,
     *  - Sinon affiche une erreur "introuvable"
     *
     * @param int $Id_commande Identifiant de la commande
     *
     * @return void
     */
    public function voirDetail($Id_commande) {
        // Affichage du formulaire avec les données existantes
        $commande = CommandeModel::loadById($Id_commande);
        if ($commande) {
            $this->commandeView->afficherFormulaireDetailAvecDonnees($commande);
        } else {
            $this->commandeView->afficherErreurCommandeIntrouvable();
        }
    }
}
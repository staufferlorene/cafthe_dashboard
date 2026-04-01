CREATE TABLE Client(
    Id_client INT AUTO_INCREMENT,
    Nom_client VARCHAR(50) NOT NULL,
    Prenom_client VARCHAR(50) NOT NULL,
    Telephone_client VARCHAR(20),
    Mail_client VARCHAR(320) NOT NULL,
    Mdp_client VARCHAR(255) NOT NULL,
    Adresse_client VARCHAR(50) NOT NULL,
    PRIMARY KEY(Id_client)
);

CREATE TABLE Categorie(
    Id_categorie INT AUTO_INCREMENT,
    Nom_categorie VARCHAR(50) NOT NULL,
    Tva_categorie DECIMAL(10,2) NOT NULL,
    PRIMARY KEY(Id_categorie)
);

CREATE TABLE Vendeur(
    Id_vendeur INT AUTO_INCREMENT,
    Nom_vendeur VARCHAR(50) NOT NULL,
    Prenom_vendeur VARCHAR(50) NOT NULL,
    Role VARCHAR(50) NOT NULL,
    Mail_vendeur VARCHAR(320) NOT NULL,
    Mdp_vendeur VARCHAR(255) NOT NULL,
    PRIMARY KEY(Id_vendeur),
    UNIQUE(Mail_vendeur)
);

CREATE TABLE Commande(
    Id_commande INT AUTO_INCREMENT,
    Date_commande DATE NOT NULL,
    Statut_commande VARCHAR(50) NOT NULL,
    Adresse_livraison VARCHAR(50) NOT NULL,
    Montant_commande_HT DECIMAL(10,2) NOT NULL,
    Montant_TVA DECIMAL(10,2) NOT NULL,
    Montant_commande_TTC DECIMAL(10,2) NOT NULL,
    Id_vendeur INT NOT NULL,
    Id_client INT NOT NULL,
    PRIMARY KEY(Id_commande),
    FOREIGN KEY(Id_vendeur) REFERENCES Vendeur(Id_vendeur),
    FOREIGN KEY(Id_client) REFERENCES Client(Id_client)
);

CREATE TABLE Produit(
    Id_produit INT AUTO_INCREMENT,
    Nom_produit VARCHAR(50) NOT NULL,
    Description VARCHAR(300) NOT NULL,
    Prix_HT DECIMAL(10,2) NOT NULL,
    Prix_TTC DECIMAL(10,2) NOT NULL,
    Stock INT NOT NULL,
    Type_conditionnement VARCHAR(50) NOT NULL,
    Id_categorie INT NOT NULL,
    PRIMARY KEY(Id_produit),
    FOREIGN KEY(Id_categorie) REFERENCES Categorie(Id_categorie)
);

CREATE TABLE Ligne_commande(
    Id_ligne_commande INT AUTO_INCREMENT,
    Nombre_ligne_commande INT NOT NULL,
    Quantite_produit_ligne_commande INT NOT NULL,
    Prix_unitaire_ligne_commande DECIMAL(10,2) NOT NULL,
    Id_commande INT NOT NULL,
    Id_produit INT NOT NULL,
    PRIMARY KEY(Id_ligne_commande),
    FOREIGN KEY(Id_commande) REFERENCES Commande(Id_commande),
    FOREIGN KEY(Id_produit) REFERENCES Produit(Id_produit)
);

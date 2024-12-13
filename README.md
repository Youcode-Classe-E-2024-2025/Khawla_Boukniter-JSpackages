# Gestion des Packages JavaScript

## Description
Ce projet permet la gestion d'auteurs, de packages et de versions de packages JavaScript. L'application offre des fonctionnalités d'ajout, de suppression et d'affichage des informations relatives aux auteurs, packages et versions, avec un système d'authentification pour l'administrateur.

## Fonctionnalités
- **Authentification administrateur** : Seul un utilisateur avec des identifiants d'administrateur peut ajouter ou supprimer des auteurs, packages et versions.
- **Gestion des auteurs** : Ajouter et supprimer des auteurs (nom et email).
- **Gestion des packages** : Ajouter et supprimer des packages (nom, description, auteur).
- **Gestion des versions** : Ajouter et supprimer des versions de packages.
- **Sécurisation** : Utilisation d'un token CSRF pour sécuriser les formulaires.

## Technologies
- PHP (avec PDO pour l'accès à la base de données)
- MySQL pour la gestion des données
- HTML, CSS pour l'interface utilisateur
- JavaScript pour interactivité dynamique

## Installation
1. Clonez ce repository sur votre machine locale.
2. Assurez-vous d'avoir un serveur local comme XAMPP ou MAMP avec PHP et MySQL.
3. Créez une base de données `gestion` dans MySQL et exécutez les scripts SQL fournis pour créer les tables.
4. Modifiez les paramètres de connexion dans le fichier PHP si nécessaire.
5. Ouvrez le projet dans votre navigateur et accédez à `index1.php`.

## Tables de la Base de Données
Voici les scripts SQL pour créer les tables nécessaires à la gestion des packages :

### Table `Auteurs`
```sql
CREATE TABLE Auteurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL
);
```
### Insertion d'exemples d'auteurs 
```sql
INSERT INTO Auteurs (nom, email) 
VALUES 
    ('Alice Dupont', 'alice.dupont@example.com'),
    ('Bob Martin', 'bob.martin@example.com'),
    ('Claire Durand', 'claire.durand@example.com'),
    ('David Morel', 'david.morel@example.com'),
    ('Emma Bernard', 'emma.bernard@example.com');
```
### Table `Auteurs`
```sql
CREATE TABLE Packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    date_creation DATE,
    auteur_id INT NOT NULL,
    FOREIGN KEY (auteur_id) REFERENCES Auteurs(id)
);
```
### Insertion d'exemples de packages 
```sql
INSERT INTO Packages (nom, description, auteur_id, date_creation)
VALUES 
    ('EasyChart', 'Librairie simple pour créer des graphiques interactifs.', 1, '2023-01-10'),
    ('FormValidator', 'Outil de validation des formulaires.', 2, '2023-02-20'),
    ('DataAnalyzer', 'Librairie pour analyser des ensembles de données.', 3, '2023-03-15'),
    ('RestfulAPI', 'Framework léger pour construire des APIs REST.', 4, '2023-04-05'),
    ('SecureLogin', "Gestion de l'authentification sécurisée.", 5, '2023-05-10');
```
## Fonctionnement
### Authentification
Le script `login.php` permet à l'administrateur de se connecter en utilisant les identifiants `admin` et `admin123`. Une fois connecté, l'administrateur peut ajouter ou supprimer des auteurs, des packages et des versions.

### Gestion des Auteurs
L'administrateur peut ajouter un auteur en remplissant un formulaire avec le nom et l'email. Les auteurs existants peuvent également être supprimés.

### Gestion des Packages
L'administrateur peut ajouter des packages en spécifiant le nom, la description et l'auteur associé. Les packages existants peuvent être supprimés.

### Gestion des Versions
Pour chaque package, l'administrateur peut ajouter des versions en spécifiant un numéro de version et une date de publication. Les versions peuvent également être supprimées.

## Conclusion
Ce projet fournit une interface simple pour la gestion des packages JavaScript, permettant à l'administrateur d'ajouter et de supprimer des packages, des versions et des auteurs de manière sécurisée.
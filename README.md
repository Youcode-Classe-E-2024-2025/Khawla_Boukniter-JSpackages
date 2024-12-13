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

### Prérequis
Avant de commencer, assurez-vous d'avoir installé les éléments suivants sur votre machine :
- [XAMPP](https://www.apachefriends.org/index.html) ou [MAMP](https://www.mamp.info/en/) pour configurer le serveur local avec PHP et MySQL.
- Un éditeur de texte comme [Visual Studio Code](https://code.visualstudio.com/).
- Un navigateur web moderne pour tester l'application.

### Étapes de configuration

1. **Cloner le repository**  
   Clonez ce repository sur votre machine locale :
   ```bash
   git clone https://github.com/yourusername/Youcode-Classe-E-2024-2025/Khawla_Boukniter-Package.git
   cd Khawla_Boukniter-Package

## Configurer le serveur local (XAMPP/MAMP)

1. **Lancez XAMPP ou MAMP**  
   - Téléchargez et installez [XAMPP](https://www.apachefriends.org/index.html) ou [MAMP](https://www.mamp.info/en/).
   - Ouvrez le programme et démarrez les services **Apache** et **MySQL**.

2. **Accédez au répertoire htdocs**  
   - Dans **XAMPP**, le répertoire par défaut se trouve dans `C:\xampp\htdocs`.
   - Dans **MAMP**, le répertoire se trouve dans `Applications/MAMP/htdocs`.
   - Placez-y le projet cloné.

## Configurer la base de données

1. **Ouvrez phpMyAdmin**  
   - Dans votre navigateur, accédez à l'URL suivante pour ouvrir **phpMyAdmin** :  
     `http://localhost/phpmyadmin`.

2. **Créez une nouvelle base de données**  
   - Cliquez sur l'option **"Bases de données"** dans le menu supérieur de phpMyAdmin.
   - Entrez le nom de la base de données, par exemple `gestion`, et cliquez sur **"Créer"**.

3. **Importez les fichiers SQL**  
   - Accédez à l'onglet **"Importer"** de phpMyAdmin.
   - Cliquez sur **"Choisir un fichier"** et sélectionnez les fichiers SQL fournis dans le dossier `sql/` du projet pour créer les tables nécessaires.
   - Cliquez sur **"Exécuter"** pour importer les fichiers et créer les tables.


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
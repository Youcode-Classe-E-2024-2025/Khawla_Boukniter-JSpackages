CREATE TABLE Auteurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE Packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    date_creation DATE,
    auteur_id INT NOT NULL,
    FOREIGN KEY (auteur_id) REFERENCES Auteurs(id)
);

CREATE TABLE Versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    version VARCHAR(50) NOT NULL,
    date_publication DATE,
    package_id INT NOT NULL,
    FOREIGN KEY (package_id) REFERENCES Packages(id)
);
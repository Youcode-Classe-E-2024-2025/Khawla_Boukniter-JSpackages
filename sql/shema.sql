CREATE TABLE Auteurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL
);

INSERT INTO Auteurs (nom, email) 
VALUES 
    ('Alice Dupont', 'alice.dupont@example.com'),
    ('Bob Martin', 'bob.martin@example.com'),
    ('Claire Durand', 'claire.durand@example.com'),
    ('David Morel', 'david.morel@example.com'),
    ('Emma Bernard', 'emma.bernard@example.com');

CREATE TABLE Packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    date_creation DATE,
    auteur_id INT NOT NULL,
    FOREIGN KEY (auteur_id) REFERENCES Auteurs(id)
);

INSERT INTO Packages (nom, description, auteur_id, date_creation)
VALUES 
    ('EasyChart', 'Librairie simple pour créer des graphiques interactifs.', 1, '2023-01-10'),
    ('FormValidator', 'Outil de validation des formulaires.', 2, '2023-02-20'),
    ('DataAnalyzer', 'Librairie pour analyser des ensembles de données.', 3, '2023-03-15'),
    ('RestfulAPI', 'Framework léger pour construire des APIs REST.', 4, '2023-04-05'),
    ('SecureLogin', "Gestion de l'authentification sécurisée.", 5, '2023-05-10'),
    ('FileUploader', "Outil de gestion avancée d'upload de fichiers.", 2, '2023-06-01'),
    ('Chartify', 'Alternative à EasyChart pour des graphiques complexes.', 3, '2023-07-25'),
    ('TaskManager', 'Application pour organiser des tâches et projets.', 1, '2023-08-18');

CREATE TABLE Versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    version VARCHAR(50) NOT NULL,
    date_publication DATE,
    package_id INT NOT NULL,
    FOREIGN KEY (package_id) REFERENCES Packages(id)
);

INSERT INTO Versions (version, date_publication, package_id)
VALUES
    ('1.0.0', '2023-01-15', 1),
    ('1.1.0', '2023-03-10', 1),
    ('2.0.0', '2024-01-01', 1),
    ('1.0.0', '2022-11-20', 2),
    ('1.1.0', '2023-06-05', 2),
    ('1.0.0', '2023-02-14', 3),
    ('1.0.1', '2023-04-01', 3),
    ('1.2.0', '2023-08-20', 3),
    ('0.9.0', '2022-09-01', 4),
    ('1.0.0', '2023-01-25', 4),
    ('1.0.0', '2023-03-15', 5),
    ('1.2.0', '2023-07-10', 5),
    ('1.0.0', '2023-05-30', 6),
    ('1.1.0', '2023-09-12', 6),
    ('1.0.0', '2023-04-22', 7),
    ('1.1.0', '2023-10-05', 7),
    ('1.0.0', '2023-06-15', 8),
    ('1.1.0', '2023-11-20', 8);

ALTER TABLE versions
DROP FOREIGN KEY versions_ibfk_1,
ADD CONSTRAINT versions_ibfk_1
FOREIGN KEY (Package_id) REFERENCES packages(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE Versions 
Drop FOREIGN KEY fk_package,
ADD CONSTRAINT fk_package 
FOREIGN KEY (package_id) REFERENCES Packages(id) 
ON DELETE CASCADE
ON UPDATE CASCADE;
<?php
session_start();

$is_admin = $_SESSION['is_admin'] ?? false;
// Connexion à la base de données
$host = 'localhost';
$dbname = 'gestion';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_GET['package_id'])) {
    $package_id = $_GET['package_id'];
    $stmt = $pdo->prepare('SELECT * FROM Versions WHERE package_id = ?');
    $stmt->execute([$package_id]);
    $versions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Important : terminez le script après l'envoi de la réponse JSON
    echo json_encode($versions);
    exit;
}

// Fonctions pour gérer les auteurs
function getAllAuthors() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM Auteurs');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addAuthor($name, $email) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO Auteurs (nom, email) VALUES (?, ?)");
    $stmt->execute([$name, $email]);
}

// Fonctions pour gérer les packages
function getAllPackages() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM Packages');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addPackage($name, $description) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO Packages (nom, description) VALUES (?, ?)");
    $stmt->execute([$name, $description]);
}

// Fonctions pour gérer les versions
function getAllVersions() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM Versions');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addVersion($package_id, $version_number) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO Versions (package_id, version) VALUES (?, ?)");
    $stmt->execute([$package_id, $version_number]);
}

// Validation des données
function validateInput($input) {
    return htmlspecialchars(trim($input)); // Nettoyage des entrées
}

// Gestion des formulaires (ajout d'auteur, de package, de version)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($is_admin) {
        if (isset($_POST['add_author'])) {
            $name = validateInput($_POST['name']);
            $email = validateInput($_POST['email']);
            addAuthor($name, $email);
            header('Location: index.php');
            exit;
        } elseif (isset($_POST['add_package'])) {
            $name = validateInput($_POST['package_name']);
            $description = validateInput($_POST['package_description']);
            addPackage($name, $description);
            header('Location: index.php');
            exit;
        } elseif (isset($_POST['add_version'])) {
            $package_id = $_POST['package_id'];
            $version_number = $_POST['version_number'];
            addVersion($package_id, $version_number);
            header('Location: index.php');
            exit;
        }
    }
    else {
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Packages</title>
    <!-- <link rel="stylesheet" href="css/style.css"> -->
     <style>
        /* Style de base */
        html {
            font-size: 100%;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
        }

        h1 {
            margin: 0;
        }

        section {
            margin: 20px;
            margin-left: 27px;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        form input[type="text"], form input[type="email"], form input[type="date"], form textarea, form select {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form input[type="submit"] {
            background-color: #333;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        form input[type="submit"]:hover {
            background-color: #555;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

    </style>
</head>
<body>

    <header>
        <h1>Gestion des Packages JavaScript</h1>
        
        <a href="logout.php">Déconnexion</a>
        
    </header>
  <?php if ($is_admin): ?> 
    <!-- Formulaire d'ajout d'auteur -->
    <section>
        <h2>Ajouter un auteur</h2>
        <form method="POST">
            <div class="auteur-fields" style="display: none">
                <input type="text" name="name" placeholder="Nom de l'auteur" required>
                <input type="email" name="email" placeholder="Email de l'auteur" required>
            </div>
            <input type="submit" name="add_author" value="Ajouter Auteur">
        </form>
    </section>

    <!-- Formulaire d'ajout de package -->
    <section>
        <h2>Ajouter un package</h2>
        <form method="POST">
            <div class="package-fields" style="display: none">
            <select name="author_id" required>
                    <?php
                    $authors = getAllAuthors();
                    foreach ($authors as $author) {
                        echo "<option value='{$author['id']}'>{$author['nom']}</option>";
                    }
                    ?>
                </select>
                <input type="text" name="package_name" placeholder="Nom du package" required>
                <textarea name="package_description" placeholder="Description du package" required></textarea>
            </div>
            <input type="submit" name="add_package" value="Ajouter Package">
        </form>
    </section>

    <!-- Formulaire d'ajout de version -->
    <section>
        <h2>Ajouter une version de package</h2>
        <form method="POST">
            <div class="version-fields" style="display: none">
                <select name="package_id" required>
                    <?php
                    $packages = getAllPackages();
                    foreach ($packages as $package) {
                        echo "<option value='{$package['id']}'>{$package['nom']}</option>";
                    }
                    ?>
                </select>
                <input type="text" name="version_number" placeholder="Numéro de version" required>
                <input type="date" name="version_date" required>
            </div>
            <input type="submit" name="add_version" value="Ajouter Version">
        </form>
    </section>
   <?php endif; ?>
    <!-- Affichage des auteurs -->
    <section>
        <h2>Auteurs</h2>
        <ul>
            <?php
                $authors = getAllAuthors();
                foreach ($authors as $author) {
                    echo "<li>{$author['nom']} ({$author['email']})</li>";
                }
            ?>
        </ul>
    </section>

    <!-- Affichage des packages -->
    <section>
        <h2>Packages</h2>
        <ul class="package-list">
            <?php
                $packages = getAllPackages();
                foreach ($packages as $package) {
                    echo "<li class='package' data-id='{$package['id']}'>{$package['nom']} - {$package['description']}</li>";
                }
            ?>
        </ul>
    </section>

    <!-- Affichage des versions -->
    <section>
        <h2>Versions</h2>
        <ul class="version-list">
        </ul>
    </section>
    <script src="assets/JS/script.js"></script>
</body>
</html>

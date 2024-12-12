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

if (!isset($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
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

if (isset($_GET['author_id'])) {
    $author_id = $_GET['author_id'];
    $stmt = $pdo->prepare('SELECT * FROM Packages WHERE auteur_id = ?');
    $stmt->execute([$author_id]);
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Important : terminez le script après l'envoi de la réponse JSON
    echo json_encode($packages);
    exit;
}

if (isset($_GET['version_id'])) {
    $version_id = $_GET['version_id'];
    $stmt = $pdo->prepare('SELECT * FROM Versions WHERE id = ?');
    $stmt->execute([$version_id]);
    $versions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Important : terminez le script après l'envoi de la réponse JSON
    echo json_encode($versions);
    exit;
}

// Fonctions pour gérer les auteurs
function getAllAuthors()
{
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM Auteurs');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addAuthor($name, $email)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO Auteurs (nom, email) VALUES (?, ?)");
    $stmt->execute([$name, $email]);
}

// Fonctions pour gérer les packages
function getAllPackages()
{
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM Packages');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addPackage($name, $description)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO Packages (nom, description) VALUES (?, ?)");
    $stmt->execute([$name, $description]);
}

// Fonctions pour gérer les versions
function getAllVersions()
{
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM Versions');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addVersion($package_id, $version_number)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO Versions (package_id, version) VALUES (?, ?)");
    $stmt->execute([$package_id, $version_number]);
}

function deleteAuthor($authId)
{
    try {
        global $pdo;

        // Supprimer les dépendances dans `versions`
        $stmt = $pdo->prepare("DELETE FROM Versions WHERE package_id IN (SELECT id FROM Packages WHERE auteur_id = :auteur_id)");
        $stmt->execute([':auteur_id' => $authId]);

        // Supprimer les packages associés
        $stmt = $pdo->prepare("DELETE FROM Packages WHERE auteur_id = :auteur_id");
        $stmt->execute([':auteur_id' => $authId]);

        // Supprimer l'auteur
        $stmt = $pdo->prepare("DELETE FROM Auteurs WHERE id = :auteur_id");
        $stmt->execute([':auteur_id' => $authId]);

        echo "Auteur et dépendances supprimés avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

function deletePackage($packageId)
{
    try {
        global $pdo;
        // Supprimer les dépendances dans `versions`
        $stmt = $pdo->prepare("DELETE FROM Versions WHERE package_id = :package_id");
        $stmt->execute([':package_id' => $packageId]);

        // Supprimer le package
        $stmt = $pdo->prepare("DELETE FROM Packages WHERE id = :package_id");
        $stmt->execute([':package_id' => $packageId]);

        echo "Package et dépendances supprimés avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

function deleteVersion($versionId)
{
    try {
        global $pdo;
        // Supprimer les dépendances dans `versions`
        // $stmt = $pdo->prepare("DELETE FROM Packages WHERE auteur_id = :auteur_id");
        // $stmt->execute([':auteur_id' => $versionId]);

        // Supprimer le package
        $stmt = $pdo->prepare("DELETE FROM Versions WHERE id = :version_id");
        $stmt->execute([':version_id' => $versionId]);

        echo "Version supprimée avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Validation des données
function validateInput($input)
{
    return htmlspecialchars(trim($input)); // Nettoyage des entrées
}

// Gestion des formulaires (ajout/suppression d'auteur, de package, de version)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($is_admin && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        if (isset($_POST['add_author'])) {
            $name = validateInput($_POST['name']);
            $email = validateInput($_POST['email']);
            addAuthor($name, $email);
            header('Location: index1.php');
            exit;
        } elseif (isset($_POST['add_package'])) {
            $name = validateInput($_POST['package_name']);
            $description = validateInput($_POST['package_description']);
            addPackage($name, $description);
            header('Location: index1.php');
            exit;
        } elseif (isset($_POST['add_version'])) {
            $package_id = $_POST['package_id'];
            $version_number = $_POST['version_number'];
            addVersion($package_id, $version_number);
            header('Location: index1.php');
            exit;
        } elseif (isset($_POST['delete_package'])) {
            $package_id = validateInput($_POST['package_id']);
            deletePackage($package_id);
            header('Location: index1.php');
            exit;
        } elseif (isset($_POST['delete_author'])) {
            $author_id = validateInput($_POST['author_id']);
            deleteAuthor($author_id);
            header('Location: index1.php');
            exit;
        } elseif (isset($_POST['delete_version'])) {
            $version_id = validateInput($_POST['version_id']);
            deleteVersion($version_id);
            header('Location: index1.php');
            exit;
        }
    } else {
        header("Location: index1.php");
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
    <link rel="stylesheet" href="assets/CSS/style.css">
</head>

<body>

    <header>
        <h1>Gestion des Packages JavaScript</h1>
        <a href="logout.php">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f4f4f9" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
            </svg>
        </a>

    </header>
    <?php if ($is_admin): ?>
        <!-- Formulaire d'ajout d'auteur -->
        <section>
            <h2>Ajouter un auteur</h2>
            <form method="POST">
                <div class="auteur-fields" style="display: none">
                    <label for="name">Nom:</label><input type="text" name="name" placeholder="Nom de l'auteur" required>
                    <label for="email">Email:</label><input type="email" name="email" placeholder="Email de l'auteur" required>
                </div>
                <input type="submit" name="add_author" value="Ajouter Auteur">
            </form>
        </section>

        <!-- Formulaire d'ajout de package -->
        <section>
            <h2>Ajouter un package</h2>
            <form method="POST">
                <div class="package-fields" style="display: none">
                    <div>
                        <label for="auteur">Auteur:</label>
                        <select name="author_id" required>
                            <?php
                            $authors = getAllAuthors();
                            foreach ($authors as $author) {
                                echo "<option value='{$author['id']}'>{$author['nom']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div><label for="package_name">Nom:</label><input type="text" name="package_name" placeholder="Nom du package" required></div>
                    <div style="display: flex; align-items: center;"><label for="package_description">Description:</label><textarea name="package_description" placeholder="Description du package" required></textarea></div>
                </div>
                <input type="submit" name="add_package" value="Ajouter Package">
            </form>
        </section>

        <!-- Formulaire d'ajout de version -->
        <section>
            <h2>Ajouter une version de package</h2>
            <form method="POST">
                <div class="version-fields" style="display: none">
                    <div>
                        <label for="package">Package:</label>
                        <select name="package_id" required>
                            <?php
                            $packages = getAllPackages();
                            foreach ($packages as $package) {
                                echo "<option value='{$package['id']}'>{$package['nom']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div><label for="version_number">Version:</label><input type="text" name="version_number" placeholder="Numéro de version" required></div>
                    <div><label for="version_date">Date de publication:</label><input type="date" name="version_date" required></div>
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
                echo "
                    <li class='author' data-id='{$author['id']}'>
                        {$author['nom']} - {$author['email']}
                        " . ($is_admin ? "
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                            <input type='hidden' name='author_id' value='{$author['id']}'>
                            <button type='submit' name='delete_author' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet auteur ?\")'>
                                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='currentColor' width='20px' class='size-5'>
                                    <path fill-rule='evenodd' d='M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z' clip-rule='evenodd' />
                                </svg>
                            </button>
                        </form>
                        " : "") . "
                    </li>
                ";
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
                echo "
                    <li class='package' data-id='{$package['id']}'>
                        {$package['nom']} - {$package['description']}
                        " . ($is_admin ? "
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                            <input type='hidden' name='package_id' value='{$package['id']}'>
                            <button type='submit' name='delete_package' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce package ?\")'>
                                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='currentColor' width='20px' class='size-5'>
                                    <path fill-rule='evenodd' d='M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z' clip-rule='evenodd' />
                                </svg>
                            </button>
                        </form>
                        " : "") . "
                    </li>
                ";
            }
            ?>
        </ul>
    </section>

    <!-- Affichage des versions -->
    <section>
        <h2>Versions</h2>
        <ul class='version-list'>
            <li>Choisissez un package pour afficher ses versions</li>
        </ul>
    </section>

    <script type="text/javascript">
        var isAdmin = <?php echo (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) ? 'true' : 'false'; ?>;
    </script>

    <script src="assets/JS/script.js"></script>
</body>

</html>
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoriser l'accès si l'un des deux est connecté (admin OU user)
if (!isset($_SESSION['admin_connecte']) && !isset($_SESSION['user_connecte'])) {
    header('Location: login.php');
    exit();
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=support_caissier', 'user_support', 'Admin12..');
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Administration</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f0f2f5; padding-bottom: 20px; margin-bottom: 20px; }
        .btn-deconnexion { background-color: #ff4757; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .form-group { margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 6px; }
        input, select { padding: 10px; margin: 5px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 20px; background-color: #1877f2; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        .del-link { color: #ff4757; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Espace Administration</h1>
        <a href="logout.php" class="btn-deconnexion">Déconnexion</a>
    </div>

    <div class="form-group">
        <h3>Ajouter un nouveau tutoriel</h3>
        <form action="enregistrer.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Nom du tutoriel" required>
            <input type="file" name="fichier_media" accept="video/mp4,video/avi,video/mov" required>
            <select name="type">
                <option value="video">VIDEO</option>
            </select>
            <button type="submit">Ajouter</button>
        </form>

        <h4>Ajouter un nouveau Fichier</h4>
        <form action="enregistrer.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Nom du fichier" required>
            <input type="file" name="fichier_media" required>
            <select name="type">
                <option value="pdf">PDF / DOCX</option>
                <option value="png">IMAGE</option>
                <option value="mp3">AUDIO</option>
            </select>
            <button type="submit">Ajouter</button>
        </form>
    </div>

    <h3>Liste des Tutoriels</h3>
    <table>
        <tr><th>Titre</th><th>Média</th><th>Action</th></tr>
        <?php
        $videos = $bdd->query('SELECT * FROM tutoriels WHERE type = "video" ORDER BY id DESC');
        while ($tuto = $videos->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($tuto['titre']) . "</td>";
            echo "<td><video width='150' controls><source src='" . htmlspecialchars($tuto['fichier_path']) . "'></video></td>";
            echo "<td>";
            // Seul l'admin voit le bouton supprimer
            if (isset($_SESSION['admin_connecte']) && $_SESSION['admin_connecte'] === true) {
                echo "<a href='supprimer.php?id=" . $tuto['id'] . "' class='del-link'>Supprimer</a>";
            } else {
                echo "<em>Lecture seule</em>";
            }
            echo "</td></tr>";
        }
        ?>
    </table>

    <h3>Liste des Fichiers</h3>
    <table>
        <tr><th>Titre</th><th>Lien</th><th>Action</th></tr>
        <?php
        $fichiers = $bdd->query("SELECT * FROM tutoriels WHERE type IN ('pdf', 'docx', 'png', 'jpg', 'mp3') ORDER BY id DESC");
        while ($file = $fichiers->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($file['titre']) . "</td>";
            echo "<td><a href='" . htmlspecialchars($file['fichier_path']) . "' target='_blank'>Ouvrir</a></td>";
            echo "<td>";
            if (isset($_SESSION['admin_connecte']) && $_SESSION['admin_connecte'] === true) {
                echo "<a href='supprimer.php?id=" . $file['id'] . "' class='del-link'>Supprimer</a>";
            } else {
                echo "<em>Lecture seule</em>";
            }
            echo "</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>

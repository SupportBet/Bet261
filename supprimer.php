<?php
session_start();

// 1. SÉCURITÉ : Vérification de la session admin
// Seul celui qui a la session 'admin_connecte' peut continuer
if (!isset($_SESSION['admin_connecte']) || $_SESSION['admin_connecte'] !== true) {
    die("Accès interdit : Vous n'avez pas les droits de suppression.");
}

// Connexion à la BDD
$host = 'localhost';
$dbname = 'support_caissier';
$username = 'user_support';
$password = 'Admin12..';

try {
    $bdd = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // 2. Récupérer et sécuriser l'ID
    if (!isset($_GET['id'])) {
        die("ID invalide.");
    }
    $id = (int)$_GET['id']; // Force l'ID à être un nombre entier pour éviter les injections

    // 3. Chercher le chemin du fichier dans la BDD
    $stmt = $bdd->prepare("SELECT fichier_path FROM tutoriels WHERE id = ?");
    $stmt->execute([$id]);
    $media = $stmt->fetch();

    if ($media) {
        $chemin_fichier = $media['fichier_path'];

        // 4. Supprimer le fichier physique
        if (file_exists($chemin_fichier)) {
            unlink($chemin_fichier);
        }

        // 5. Supprimer l'entrée dans la BDD
        $delete = $bdd->prepare("DELETE FROM tutoriels WHERE id = ?");
        $delete->execute([$id]);

        // Redirection propre vers la page d'administration
        header('Location: admin.php?message=supprime');
        exit();
    } else {
        echo "Fichier introuvable.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

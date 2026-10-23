<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'support_caissier';
$username = 'user_support';
$password = 'Admin12..';

try {
    $bdd = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // 1. Vérifier si le formulaire est bien envoyé
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier_media'])) {
        
        $titre = $_POST['titre'];
        $type = $_POST['type']; // 'video', 'pdf', 'docx', etc.
        
        // Définir le dossier selon le type
        $dossier = ($type === 'video') ? 'Vidéos/' : 'Documents/';
        
        // Sécurité : obtenir le nom du fichier
        $nom_fichier = basename($_FILES['fichier_media']['name']);
        $chemin_final = $dossier . $nom_fichier;

        // Déplacer le fichier
        if (move_uploaded_file($_FILES['fichier_media']['tmp_name'], $chemin_final)) {
            
            // Insertion BDD
            $stmt = $bdd->prepare("INSERT INTO tutoriels (titre, fichier_path, type) VALUES (?, ?, ?)");
            $stmt->execute([$titre, $chemin_final, $type]);

            echo "Succès ! Le fichier '$nom_fichier' a été ajouté.";
            echo "<br><a href='admin.php'>Retour à l'administration</a>";
            
        } else {
            echo "Erreur lors du déplacement du fichier. Vérifiez les droits d'écriture sur le dossier '$dossier'.";
        }
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=support_caissier', 'user_support', 'Admin12..');

// recherche si existe dans l'URL
$search = isset($_GET['q']) ? $_GET['q'] : '';

if (!empty($search)) {
    // filtrage vidéos apres recherche
    $sql = "SELECT * FROM tutoriels WHERE type = 'video' AND titre LIKE :search ORDER BY id DESC";
    $stmt = $bdd->prepare($sql);
    $stmt->execute(['search' => '%' . $search . '%']);
} else {
    // Sinon, on récupère toutes les vidéos
    $stmt = $bdd->query('SELECT * FROM tutoriels WHERE type = "video" ORDER BY id DESC');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>BET261 | Portail Tutoriels</title>
</head>
<body>
    <header class="main-header">
        <div class="header-left">
            <div class="header-right">
                <h1 class="h1">BET261 Support</h1>
            </div>
            <nav>
                <a href="index.php">Accueil</a>
                <a href="javascript:void(0)" class="btn-menu-clean" onclick="openTuto()">Tutoriels Caisse</a>
                <a href="javascript:void(0)" class="btn-menu-clean" onclick="openDoc()">Documents</a>
            </nav>
        </div>

        <div id="sidebar-documents" class="sidebar">
            <div class="sidebar-header">
                <h2 style="font-size: 1.2rem; color: #28a745; margin-bottom: 20px;">Liste des Documents</h2>
                <button class="close-btn" onclick="closeAll()">Fermer</button>
            </div>
            <ul>
                <?php
                $docs = $bdd->query("SELECT * FROM tutoriels WHERE type != 'video' ORDER BY id DESC");
                while ($doc = $docs->fetch()) {
                    echo "<li><a href='" . htmlspecialchars($doc['fichier_path']) . "' target='_blank'>📄 " . htmlspecialchars($doc['titre']) . "</a></li>";
                }
                ?>
            </ul>
        </div>

        <div id="sidebar-tuto" class="sidebar">
            <div class="sidebar-header">
                <h2 style="font-size: 1.2rem; color: #28a745; margin-bottom: 20px;">Liste des Videos</h2>
                <button class="close-btn" onclick="closeAll()">Fermer</button>
            </div>
            <ul>
                <?php
                $tutos = $bdd->query("SELECT * FROM tutoriels WHERE type = 'video' ORDER BY id DESC");
                while ($tuto = $tutos->fetch()) {
                    $videoPath = htmlspecialchars($tuto['fichier_path']);
                    echo "<li><a href='javascript:void(0)' onclick=\"playVideo('$videoPath')\">▶ " . htmlspecialchars($tuto['titre']) . "</a></li>";
                }
                ?>
            </ul>
        </div>
        <div id="sidebar-overlay" onclick="closeAll()"></div>

        <img src="assets/logobet261.png" class="logo-droite" alt="Logo BET 261">
        
        <div class="search-container">
            <form action="index.php" method="GET">
                <input type="text" name="q" placeholder="Rechercher un tuto..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">OK</button>
            </form>
        </div>
    </header>

    <main>
        <?php
        // Affichage des vidéos filtrées
        while ($tuto = $stmt->fetch()) {
            echo '<div class="video-card">';
            echo '<h3>' . htmlspecialchars($tuto['titre']) . '</h3>';
            echo '<video width="600" controls>
                    <source src="' . htmlspecialchars($tuto['fichier_path']) . '" type="video/mp4">
                    Votre navigateur ne supporte pas la vidéo.
                  </video>';
            echo '</div>';
        }
        ?>

        <div class="section-docs">
           <?php
           $docs = $bdd->query("SELECT * FROM tutoriels WHERE type != 'video'");
           while ($doc = $docs->fetch()) {
               echo '<div class="doc-card"></div>';
           }
           ?>
        </div>
    </main>

    <script>
        function openTuto() { document.getElementById("sidebar-tuto").classList.add("active"); }
        function openDoc() { document.getElementById("sidebar-documents").classList.add("active"); }
        function closeAll() {
            document.getElementById("sidebar-tuto").classList.remove("active");
            document.getElementById("sidebar-documents").classList.remove("active");
        }
        function playVideo(videoPath) {
            var videoPlayer = document.getElementById("main-video");
            if (videoPlayer) {
                videoPlayer.src = videoPath;
                videoPlayer.load();
                videoPlayer.play();
            } else {
                window.location.href = videoPath;
            }
        }
    </script>
</body>
</html>

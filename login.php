<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- LOGIQUE PHP ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Vérification Admin
    if ($user === 'support_admin' && $pass === 'Adminserver12..') {
        $_SESSION['admin_connecte'] = true;
        // Optionnel : ajouter le nom pour le display
        $_SESSION['user_name'] = 'Admin'; 
        header('Location: admin.php');
        exit();
    } 
    // Vérification Utilisateur Support
    elseif ($user === 'support_user' && $pass === 'Adminuser12..') {
        $_SESSION['user_connecte'] = true;
        $_SESSION['user_name'] = 'Support';
        header('Location: admin.php');
        exit();
    } 
    // Si aucun des deux ne correspond
    else {
        $error = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Administration</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        .logo { width: 150px; margin-bottom: 20px; }
        .error { color: #ff4757; font-weight: bold; margin-bottom: 15px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #1877f2; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #166fe5; }
    </style>
</head>
<body>

<div class="login-container">
    <img src="assets/logobet261.png" alt="Logo" class="logo">

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</div>

</body>
</html>

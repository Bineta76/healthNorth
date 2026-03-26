<?php

/******************** CONFIG DEV ********************/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/******************** SESSION SÉCURISÉE ********************/
session_set_cookie_params([
    'httponly' => true,
    'secure' => false, // mettre true en HTTPS
    'samesite' => 'Strict'
]);

session_start();

/******************** PROTECTION SESSION ********************/
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

/******************** CSRF TOKEN ********************/
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

/******************** CONNEXION BDD ********************/
try {
    $pdo = new PDO(
        'mysql:host=mysql-loute.alwaysdata.net;dbname=loute_labo;charset=utf8mb4',
        'loute',
        'laboratoire',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

/******************** DÉCONNEXION ********************/
if (isset($_GET['action']) && $_GET['action'] === 'logout') {

    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
    header("Location: index.php");
    exit;
}

/******************** MODE ********************/
$mode = 'connexion';
if (isset($_GET['action']) && $_GET['action'] === 'inscription') {
    $mode = 'inscription';
}

$message = '';
$messageType = '';

/******************** TRAITEMENT ********************/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérification CSRF
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Erreur CSRF");
    }

    $action = $_POST['action'] ?? '';

    /* ===== INSCRIPTION ===== */
    if ($mode === 'inscription' && $action === 'inscription') {

        $nom   = trim($_POST['nom'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $mdp   = $_POST['mot_de_passe'] ?? '';

        if ($nom === '' || $email === '' || $mdp === '') {
            $message = "Tous les champs sont obligatoires.";
            $messageType = "danger";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Email invalide.";
            $messageType = "danger";

        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/', $mdp)) {
            $message = "Mot de passe faible (8 caractères, 1 majuscule, 1 chiffre).";
            $messageType = "danger";

        } else {

            try {
                $hash = password_hash($mdp, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare(
                    "INSERT INTO patient (nom, email, mot_de_passe)
                     VALUES (?, ?, ?)"
                );
                $stmt->execute([$nom, $email, $hash]);

                session_regenerate_id(true);
                $_SESSION['id_patient'] = $pdo->lastInsertId();
                $_SESSION['utilisateur'] = $nom;

                header("Location: index.php");
                exit;

            } catch (PDOException $e) {
                $message = "Cet email existe déjà.";
                $messageType = "danger";
            }
        }
    }

    /* ===== CONNEXION ===== */
    if ($mode === 'connexion' && $action === 'connexion') {

        $email = strtolower(trim($_POST['email'] ?? ''));
        $mdp   = $_POST['mot_de_passe'] ?? '';

        if ($email === '' || $mdp === '') {
            $message = "Tous les champs sont obligatoires.";
            $messageType = "danger";

        } else {

            $stmt = $pdo->prepare(
                "SELECT id, nom, mot_de_passe
                 FROM patient
                 WHERE email = ?"
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($mdp, $user['mot_de_passe'])) {

                session_regenerate_id(true);
                $_SESSION['id_patient'] = $user['id'];
                $_SESSION['utilisateur'] = $user['nom'];

                header("Location: index.php");
                exit;

            } else {
                $message = "Email ou mot de passe incorrect.";
                $messageType = "danger";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Health North - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

<h1 class="text-center mb-4">🏥 Health North</h1>

<?php if (!empty($_SESSION['id_patient'])): ?>

    <div class="text-center">
        <h2>Bienvenue, <?= htmlspecialchars($_SESSION['utilisateur']) ?> 👋</h2>
        <a href="?action=logout" class="btn btn-danger mt-3">Se déconnecter</a>
    </div>

<?php else: ?>

    <div class="card mx-auto shadow" style="max-width: 420px;">
        <div class="card-body">

            <h3 class="text-center mb-3">
                <?= $mode === 'connexion' ? 'Connexion' : 'Inscription' ?>
            </h3>

            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?> text-center">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="post">

                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

                <?php if ($mode === 'inscription'): ?>
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom"
                               value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                               class="form-control" required>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="mot_de_passe" class="form-control" required>
                </div>

                <button type="submit"
                        name="action"
                        value="<?= $mode ?>"
                        class="btn btn-primary w-100">
                    <?= $mode === 'connexion' ? 'Se connecter' : "S'inscrire" ?>
                </button>
            </form>

            <p class="mt-3 text-center">
                <?= $mode === 'connexion'
                    ? "Pas encore de compte ? <a href='?action=inscription'>Inscrivez-vous</a>"
                    : "Déjà inscrit ? <a href='?action=connexion'>Connectez-vous</a>" ?>
            </p>

        </div>
    </div>

<?php endif; ?>

</div>
</body>
</html>
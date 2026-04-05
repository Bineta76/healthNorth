<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health North</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="assets/css/styles.css" rel="stylesheet">

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Health North</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">




                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">Accueil</a>
                    </li>
                   
                   
                    <li class ="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'inscription.php' ? 'active' : '' ?>" href="inscription.php">Inscription</a>
                    </li>
                    
                      <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'quiSommesNous.php' ? 'active' : '' ?>" href="quiSommesNous.php">Qui sommes-nous ?</a>
                    
                    
                    
                    
                    
                    
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'liste.php' ? 'active' : '' ?>" href="liste.php">Gestion des rendez-vous</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'bilan.php' ? 'active' : '' ?>" href="bilan.php">Bilan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'region.php' ? 'active' : '' ?>" href="region.php">Recherche de médecins</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'gestionResultat.php' ? 'active' : '' ?>" href="gestionResultat.php">Résultats des examens</a>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'facturation.php' ? 'active' : '' ?>" href="facturation.php">Facturation</a>
                    </li>
                      <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'contactSupport.php' ? 'active' : '' ?>" href="contactSupport.php">Aide</a>
                    </li>


                    


                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'deconnexion.php' ? 'active' : '' ?>" href="deconnexion.php">Déconnexion</a>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
    </div>
</body>
</html>
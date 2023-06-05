<?php
// Vérification si le formulaire a été soumis
if ($_POST) {
    $email = $_POST['email'];

    // traitement



    header('index.php?page=mdp_reset');
    exit();
}
?>

<h2>Mot de passe oublié</h2>

<h1>Veuillez entrer votre adresse e-mail pour réinitialiser votre mot de passe.</h1>

<form method="post" action="index.php?page=mdp_reset" id="form-forget">
    <label for="email">Adresse e-mail :</label>
    <input type="email" name="email" id="email" required>

    <input type="submit" value="Réinitialiser le mot de passe">
</form>
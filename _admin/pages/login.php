<?php
// cryptage préconisé aujourd'hui 
// https://www.php.net/manual/fr/function.crypt.php

/*.    admin
identifiant : MosT
 mot de ppasse : 1234
 email : test@gmail.com
 */

/*.    super_dmin
identifiant : Robb
 mot de ppasse : 1234
 email : robert@gmail.com
 */
if ($_POST) {
    $identifiant = $_POST['identifiant'];
    $mdp = $_POST['mdp'];
    $email = $_POST['email'];
    // $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    // $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';

    $sql = "SELECT identifiant, mdp, nom, prenom, habilitation FROM mostafa_admin
    WHERE identifiant = :identifiant
    AND mdp = MD5(:mdp)
    -- AND nom = :nom
    -- AND prenom = :prenom
    AND email = :email";

    $sth = $dbh->prepare($sql);
    $sth->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
    $sth->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $sth->bindParam(':email', $email, PDO::PARAM_STR);
    // $sth->bindParam(':nom', $nom, PDO::PARAM_STR);
    // $sth->bindParam(':prenom', $prenom, PDO::PARAM_STR);

    $sth->execute();

    $result = $sth->fetch(PDO::FETCH_OBJ);
    $nbre_result = $sth->rowCount();

    if (empty($identifiant) || empty($mdp) || empty($email)) {
        $msg_error = "Merci de rentrer vos identifiants et votre email";
    } else {
        if ($nbre_result == 1) {
            $_SESSION['identifiant'] = $identifiant;
            $_SESSION['habilitation'] = $result->habilitation;
            $_SESSION['nom'] = $result->nom;
            $_SESSION['prenom'] = $result->prenom;
            $_SESSION['email'] = $result->email;

            header('Location: index.php?page=home');
        }
    }
}
?>


<?php if (isset($msg_error)) : ?>
    <div class="alert">
        <p><?php echo $msg_error; ?></p>
    </div>
<?php endif; ?>


<main>
    <h2>Authentification</h2>
    <form method="post" action="index.php" id="form-ident">
        <label for="ident">Identifiant</label>
        <input type="text" name="identifiant" id="ident">

        <label for="password">Mot de passe</label>
        <input type="password" name="mdp" id="password">

        <!-- <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom">

        <label for="prenom">prenom</label>
        <input type="text" name="prenom" id="prenom"> -->

        

        <label for="email">Email</label>
        <input type="email" name="email" id="email">

        <input type="submit" value="Se connecter">
        <p><a href="index.php?page=mdp_forget">Mot de passe oublié ?</a></p>
    </form>
</main>
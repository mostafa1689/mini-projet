<?php
$errors = array();
$success = array();
if ($_POST) {
    $msg_error = "";
    foreach ($_POST as $key => $value) {
        ${$key} = $value;
    }
    if (empty($nom)) {
        $error = true;
        $errors[] = 'Merci de remplir le nom de la catégorie';
    } else {
        $slug = replace_special_caract($nom);
    }

    if (empty($_FILES['photo']['name'])) {
        $error = true;
        $errors[] = 'Merci de télécharger la photo de la categorie.';
    } else {
        $tab_return_errors = upload_photo('photo');
        if ($tab_return_errors[0] === false) {
            $error = true;
            $errors[] = $tab_return_errors[1];
        } else {
            $photo = $tab_return_errors[1];
        }
    }

    if (empty($error)) {
        try {
            $sql_insert = "INSERT INTO mostafa_categorie (photo, nom, slug)
            VALUES (:photo, :nom, :slug)";

            $sth_insert = $dbh->prepare($sql_insert);
            $sth_insert->bindParam(':photo', $photo, PDO::PARAM_STR);
            $sth_insert->bindParam(':nom', $nom, PDO::PARAM_STR);
            $sth_insert->bindParam(':slug', $slug, PDO::PARAM_STR);

            $sth_insert->execute();

            $success[] = "La catégorie a été enregistrée avec succès";
        } catch (PDOException $e) {
            $error = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
            // Affichage de l'erreur dans la page
            echo $error;
        }
    }
} // fin $_POST
?>

<?php if (isset($_SESSION['identifiant'])) : ?>
    <div class="message">
        <h2>Bonjour <?= $_SESSION['identifiant']; ?></h2>
    </div>
<?php endif; ?>

<?php foreach ($errors as $error) : ?>
    <div class="alert">
        <p><?= $error; ?></p>
    </div>
<?php endforeach; ?>

<?php foreach ($success as $message) : ?>
    <div class="confirm">
        <p><?= $message; ?></p>
    </div>
<?php endforeach; ?>

<h1>Insérer une catégorie</h1>

<form class="form_products" method="post" action="index.php?page=insert_categorie" enctype="multipart/form-data" id="form_categorie">
    <label for="photo">Photo (format .jpg, .jpeg / Taille: 100Mo max / Largeur max:1024px)</label>
    <input type="file" name="photo" id="photo" value="<?= $photo ?? ""; ?>">

    <label for="nom">Nom</label>
    <input type="text" name="nom" id="nom" value="<?= $nom ?? ""; ?>">

    <input type="submit" value="Enregistrer la catégorie" name="envoyer">
</form>
</body>

</html>
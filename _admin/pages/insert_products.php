<?php
if (isset($_SESSION['identifiant'])) {
    $hello = 'Bonjour ' . $_SESSION['identifiant'];
} else {
    return; // ou exit()
}
?>

<?php
$errors = array();
$success = array();
if ($_POST) {
    $msg_error = "";
    foreach ($_POST as $key => $value) {
        ${$key} = $value;
    }
    if (empty($titre) || empty($descriptif) || empty($prix) || empty($id_categorie)) {
        $error = true;
        $errors[] = 'Merci de remplir tous les champs.';
    }
    if (empty($_FILES['photo']['name'])) {
        $error = true;
        $errors[] = 'Merci de télécharger une photo.';
    }

    if (!empty($_FILES['photo']['name'])) {
        $tab_return_errors = upload_photo('photo');
        if ($tab_return_errors[0] === false) {
            $error = true;
            $msg_error .= $tab_return_errors[1];
        } else {
            $photo = $tab_return_errors[1];
        }
    }
    $bio = isset($bio) ? $bio : 0; // Valeur par défaut si le champ est vide
    $stock = isset($stock) ? $stock : null; // Valeur par défaut si le champ est vide
    $prix_promo = isset($prix_promo) ? $prix_promo : null; // Valeur par défaut si le champ est vide

    if (empty($error)) {
        try {
            $sql_insert = "INSERT INTO mostafa_products (photo, titre, descriptif, id_categorie, stock, bio, prix, prix_promo, id_tags)
            VALUES (:photo, :titre, :descriptif, :id_categorie, :stock, :bio, :prix, :prix_promo, :id_tags)";

            $sth_insert = $dbh->prepare($sql_insert);
            $sth_insert->bindParam(':photo', $photo, PDO::PARAM_STR);
            $sth_insert->bindParam(':titre', $titre, PDO::PARAM_STR);
            $sth_insert->bindParam(':descriptif', $descriptif, PDO::PARAM_STR);
            $sth_insert->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
            $sth_insert->bindParam(':stock', $stock, PDO::PARAM_INT);
            $sth_insert->bindParam(':bio', $bio, PDO::PARAM_BOOL);
            $sth_insert->bindParam(':prix', $prix, PDO::PARAM_STR);
            $sth_insert->bindParam(':prix_promo', $prix_promo, PDO::PARAM_NULL);
            $sth_insert->bindParam(':id_tags', $id_tags, PDO::PARAM_INT);

            $sth_insert->execute();

            $success[] = "Le produit a été enregistré avec succès";
        } catch (PDOException $e) {
            $error = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
            // Affichage de l'erreur dans la page
            echo $error;

            if ($e->getCode() === '23000' && strpos($e->getMessage(), "Column 'photo' cannot be null") !== false) {
                $errors[] = "Merci de télécharger une photo.";
            }
        }
    }
} // fin $_POST

?>


<?php if (isset($hello)) : ?>
    <div class="message">
        <h2><?= $hello; ?></h2>
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


<h1>Insérer un produit</h1>

<form class="form_products" method="post" action="index.php?page=insert_products" enctype="multipart/form-data" id="form_products">
    <label for="photo">Photo (format .jpg, .jpeg / Taille: 100Mo max / Largeur max:1024px)</label>
    <input type="file" name="photo" id="photo" value="<?= $photo ?? ""; ?>">
    <label for="titre">Titre</label>
    <input type="text" name="titre" id="titre" value="<?= $titre ?? ""; ?>">
    <label for="descriptif">Descriptif</label>
    <textarea name="descriptif" id="descriptif" rows="7" cols="30"><?= $descriptif ?? ""; ?></textarea>
    <label for="id_categorie">Catégorie</label>
    <select name="id_categorie" id="id_categorie">
        <option value="">votre choix</option>
        <?php
        $sql_tags = "SELECT id_categorie, nom FROM mostafa_categorie";
        $result_tags = $dbh->query($sql_tags);
        if ($result_tags->rowCount() > 0) {
            while ($row = $result_tags->fetch(PDO::FETCH_ASSOC)) {
                $id_categorie = $row['id_categorie'];
                $nom_categorie = $row['nom'];
                echo '<option value="' . $id_categorie . '">' . $nom_categorie . '</option>';
            }
        } else {
            echo 'Aucune catégorie trouvée.';
        }
        ?>
    </select>
    <label for="stock">Stock</label>
    <input type="number" name="stock" id="stock" value="<?= $stock ?? ""; ?>">
    <label for="bio">Bio</label>
    <input type="checkbox" name="bio" id="bio" <?= isset($bio) && $bio ? "checked" : ""; ?>>
    <label for="prix">Prix</label>
    <input type="number" name="prix" id="prix" step="0.05" value="<?= $prix ?? ""; ?>">
    <label for="prix_promo">Prix promotionnel</label>
    <input type="number" name="prix_promo" id="prix_promo" step="0.05" value="<?= $prix_promo ?? ""; ?>">
    <?php
    $sql_tags = "SELECT id_tags, nom FROM mostafa_tags";
    $result_tags = $dbh->query($sql_tags);
    if ($result_tags->rowCount() > 0) {
        while ($row = $result_tags->fetch(PDO::FETCH_ASSOC)) {
            $id_tag = $row['id_tags'];
            $nom_tag = $row['nom'];
            echo '<div>';
            echo '<input type="radio" name="id_tags" value="' . $id_tag . '"';
            if (isset($id_tags) && $id_tags == $id_tag) {
                echo ' checked';
            }
            echo '>';
            echo '<label for="' . $id_tag . '">' . $nom_tag . '</label>';
            echo '</div>';
        }
    } else {
        echo 'Aucun tag trouvé.';
    }

    ?>
    <input type="submit" value="Enregistrer le produit" name="envoyer">
</form>
</body>

</html>
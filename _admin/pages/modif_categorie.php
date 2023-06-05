<?php if (isset($_SESSION['identifiant'])) : ?>
    <div class="message">
        <h2>Bonjour <?= $_SESSION['identifiant']; ?></h2>
    </div>
<?php else :
    return; // ou exit()
endif; ?>

<h1>Modifier une catégorie</h1>

<?php
// Récupération des données de la catégorie à modifier
if (isset($_GET['id_categorie'])) {
    $id_categorie = $_GET['id_categorie'];
    $_SESSION['id_categorie'] = $id_categorie;
    $sql = "SELECT * FROM mostafa_categorie WHERE id_categorie = :id_categorie";
    $sth = $dbh->prepare($sql);
    $sth->execute([
        'id_categorie' => $id_categorie
    ]);
    $result = $sth->fetch(PDO::FETCH_OBJ);
}
?>

<?php
if ($_POST) {
    $msg_error = "";
    foreach ($_POST as $key => $value) {
        ${$key} = $value;
    }
    if (empty($_POST['nom'])) {
        $error = true;
        $msg_error = 'Merci de remplir tous les champs';
    } else {
        $slug = replace_special_caract($nom);
    }

    if ($_FILES) {
        if (!empty($_FILES['photo']['name'])) {
            $tab_return_errors = upload_photo('photo');
            if ($tab_return_errors[0] === false) {
                $error = true;
                $msg_error .= $tab_return_errors[1];
            } else {
                $photo = $tab_return_errors[1];
            }
        }
    }

    if (empty($error)) {
        try {
            $sql_update = "UPDATE mostafa_categorie
            SET 
            photo = :photo,
            nom = :nom,
            slug = :slug
            WHERE id_categorie = :id_categorie";

            $sth_update = $dbh->prepare($sql_update);

            $sth_update->execute([
                'photo' => $photo ?? $result->photo,
                'nom' => $nom ?? $result->nom,
                'slug' => $slug ?? $result->slug,
                'id_categorie' => $_SESSION['id_categorie']
            ]);

            $message = "La catégorie a bien été modifiée";
        } catch (PDOException $e) {
            $error = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
            die($error);
        }
    }
} // fin $_POST 
?>

<?php if (isset($message)) : ?>
    <p class="confirm"><?= $message; ?></p>
<?php endif; ?>

<?php if (isset($_GET['id_categorie'])) : ?>
    <form class="form_products" id="form_categorie" method="post" action="index.php?page=modif_categorie&id_categorie=<?= $_GET['id_categorie']; ?>" enctype="multipart/form-data">
        <label for="photo">Photo (format .jpg, .jpeg / Taille: 100Mo max / Largeur max:1024px)</label>
        <input type="file" name="photo" id="photo" value="<?= $photo ?? ""; ?>">

        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" value="<?= $result->nom ?? ""; ?>">

        <!-- <label for="slug">Slug</label>
        <input type="text" name="slug" id="slug" value="<?= $result->slug ?? ""; ?>"> -->

        <input type="submit" value="Enregistrer la catégorie" name="envoyer">
    </form>
<?php endif; ?>

<?php
// Affichage des catégories
$sql = "SELECT id_categorie, nom, slug, DATE_FORMAT(date_inserstion, '%d-%m-%Y %H:%i:%s') AS date_inserstion, photo FROM mostafa_categorie";
$sth = $dbh->query($sql);
$result = $sth->fetchAll(PDO::FETCH_OBJ);
$nbre_result = $sth->rowCount();
?>

<section id="modif">
    <table>
        <thead>
            <tr>
                <th>Id Categorie</th>
                <th>Photo</th>
                <th>Nom</th>
                <th>Slug</th>
                <th>Date d'inserstion</th>
                <th>Modifier</th>
                <th>Afficher</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row) : ?>
                <tr>
                    <td><?= $row->id_categorie; ?></td>
                    <td><img class="vignette" src="<?= $row->photo; ?>"></td>
                    <td><?= $row->nom; ?></td>
                    <td><?= $row->slug; ?></td>
                    <td><?= $row->date_inserstion; ?></td>
                    <td><a href="index.php?page=modif_categorie&id_categorie=<?= $row->id_categorie; ?>">Modifier</a></td>
                    <td><a href="index.php?page=display_categorie&mode=mostafa_categorie&id_categorie=<?= $row->id_categorie; ?>">Afficher</a></td>


                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
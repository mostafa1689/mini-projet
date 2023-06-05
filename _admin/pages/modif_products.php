<?php if (isset($_SESSION['identifiant'])) : ?>
    <div class="message">
        <h2>Bonjour <?= $_SESSION['identifiant']; ?></h2>
    </div>
<?php else :
    return; // ou exit()
endif; ?>


<h1>Modifier un produit</h1>


<?php
// Récupération des données du produit à modifier
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $_SESSION['id'] = $id;
    $sql = "SELECT * FROM mostafa_products WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute([
        'id' => $id
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
    if (empty($_POST['titre']) || empty($_POST['descriptif'])) {
        $error = true;
        $msg_error = 'Merci de remplir tous les champs';
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
            $sql_update = "UPDATE mostafa_products
            SET 
            photo = :photo,
            titre = :titre,
            descriptif = :descriptif,
            stock = :stock,
            bio = :bio,
            prix = :prix,
            prix_promo = :prix_promo,
            id_categorie = :id_categorie,
            id_tags = :id_tags
            WHERE id = :id";

            $sth_update = $dbh->prepare($sql_update);

            $bio_value = isset($bio) && $bio ? 1 : 0; // Convertir 'on' en 1 et autres valeurs en 0

            $sth_update->execute([
                'photo' => $photo ?? $result->photo,
                'titre' => $titre ?? $result->titre,
                'descriptif' => $descriptif ?? $result->descriptif,
                'id_categorie' => $id_categorie ?? $result->id_categorie,
                'stock' => $stock ?? $result->stock,
                'bio' => $bio_value,
                'prix' => $prix ?? $result->prix,
                'prix_promo' => $prix_promo ?? $result->prix_promo,
                'id_tags' => $id_tags ?? $result->id_tags,
                'id' => $_SESSION['id']
            ]);

            $message = "Le produit a bien été modifié";
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

<?php if (isset($_GET['id'])) : ?>
    <form class="form_products" id="form_products" method="post" action="index.php?page=modif_products&id=<?= $_GET['id']; ?>" enctype="multipart/form-data" id="form_products">
        <label for="photo">Photo (format .jpg, .jpeg / Taille: 100Mo max / Largeur max:1024px)</label>
        <input type="file" name="photo" id="photo" value="<?= $photo ?? ""; ?>">
        <label for="titre">Titre</label>
        <input type="text" name="titre" id="titre" value="<?= $result->titre ?? ""; ?>">
        <label for="descriptif">Descriptif</label>
        <textarea name="descriptif" id="descriptif" rows="7" cols="30"><?= $result->descriptif ?? ""; ?></textarea>
        <label for="id_categorie">Catégorie</label>
        <select name="id_categorie" id="id_categorie">
            <option value="">votre choix</option>
            <?php
            $sql_categories = "SELECT id_categorie, nom FROM mostafa_categorie";
            $result_categories = $dbh->query($sql_categories);
            if ($result_categories->rowCount() > 0) {
                while ($row = $result_categories->fetch(PDO::FETCH_ASSOC)) {
                    $id_categorie = $row['id_categorie'];
                    $nom_categorie = $row['nom'];
                    echo '<option value="' . $id_categorie . '"';
                    if (isset($id_categorie) && $id_categorie == $result->id_categorie) {
                        echo ' selected';
                    }
                    echo '>' . $nom_categorie . '</option>';
                }
            } else {
                echo 'Aucune catégorie trouvée.';
            }
            ?>
        </select>
        <label for="stock">Stock</label>
        <input type="number" name="stock" id="stock" value="<?= $result->stock ?? ""; ?>">
        <label for="bio">Bio</label>
        <input type="checkbox" name="bio" id="bio" <?= isset($result->bio) && $result->bio ? "checked" : ""; ?>>
        <label for="prix">Prix</label>
        <input type="number" name="prix" id="prix" step="0.05" value="<?= $result->prix ?? ""; ?>">
        <label for="prix_promo">Prix promotionnel</label>
        <input type="number" name="prix_promo" id="prix_promo" step="0.05" value="<?= $result->prix_promo ?? ""; ?>">
        <?php
        $sql_tags = "SELECT id_tags, nom FROM mostafa_tags";
        $result_tags = $dbh->query($sql_tags);
        if ($result_tags->rowCount() > 0) {
            while ($row = $result_tags->fetch(PDO::FETCH_ASSOC)) {
                $id_tag = $row['id_tags'];
                $nom_tag = $row['nom'];
                echo '<div>';
                echo '<input type="radio" name="id_tags" value="' . $id_tag . '"';
                if (isset($result->id_tags) && $result->id_tags == $id_tag) {
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
<?php endif; ?>
<?php
// Affichage des produits
$sql = "SELECT mp.* , mc.nom AS categorie, mt.nom AS tags FROM mostafa_products AS mp
INNER JOIN mostafa_categorie AS mc ON mc.id_categorie = mp.id_categorie
INNER JOIN mostafa_tags AS mt ON mt.id_tags = mp.id_tags";
$sth = $dbh->query($sql);
$result = $sth->fetchAll(PDO::FETCH_OBJ);
$nbre_result = $sth->rowCount();
?>

<section id="modif">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Titre</th>
                <th>Descriptif</th>
                <th>Catégorie</th>
                <th>Stock</th>
                <th>Bio</th>
                <th>Prix</th>
                <th>Prix promotionnel</th>
                <th>Tags</th>
                <th>Date d'inserstion</th>
                <th>Modifier</th>
                <th>Afficher</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row) : ?>
                <tr>
                    <td><?= $row->id; ?></td>
                    <td><img class="vignette" src="<?= $row->photo; ?>"></td>
                    <td><?= $row->titre; ?></td>
                    <td><?= $row->descriptif; ?></td>
                    <td><?= $row->categorie; ?></td>
                    <td><?= $row->stock; ?></td>
                    <td><?= $row->bio ? 'Oui' : 'Non'; ?></td>
                    <td><?= $row->prix; ?></td>
                    <td><?= $row->prix_promo; ?></td>
                    <td><?= $row->tags; ?></td>
                    <td><?= $row->date_inserstion; ?></td>
                    <td><a href="index.php?page=modif_products&id=<?= $row->id; ?>">Modifier</a></td>
                    <!-- <td><a href="index.php?page=edit&mode=products&id=<?= $row->id; ?>">Editer</a></td> -->
                    <td><a href="index.php?page=display_products&mode=mostafa_products&id=<?= $row->id; ?>">Afficher</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
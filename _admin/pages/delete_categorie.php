<?php
if (isset($_SESSION['identifiant'])) {
    $hello = 'Bonjour ' . $_SESSION['identifiant'];
    $habilitation = $_SESSION['habilitation'];
} else {
    return; // ou exit()
}
?>

<?php if (isset($hello)) : ?>
    <div class="message">
        <h2><?= $hello; ?></h2>
    </div>
<?php endif; ?>

<h1>Supprimer une catégorie</h1>

<?php if (isset($_GET['id_categorie'])) : ?>
    <?php $_SESSION['id_categorie'] = $_GET['id_categorie']; ?>
    <form id="form-ident" method="post" action="index.php?page=delete_categorie">
        <p class="alert">Êtes-vous sûr de vouloir supprimer cette catégorie ?</p>
        <input type="hidden" name="confirm_delete" value="true">
        <input type="submit" value="Supprimer">
    </form>
<?php endif; ?>

<?php
if ($_POST && isset($_POST['confirm_delete'])) {
    $id_categorie = $_SESSION['id_categorie'];

    // Vérifier si la catégorie est associée à des produits
    $sql_check_products = "SELECT COUNT(*) AS total FROM mostafa_products WHERE id_categorie = :id_categorie";
    $sth_check_products = $dbh->prepare($sql_check_products);
    $sth_check_products->execute(['id_categorie' => $id_categorie]);
    $total_products = $sth_check_products->fetchColumn();

    if ($total_products > 0) {
        echo '<p class="alert">Vous ne pouvez pas supprimer cette catégorie car elle est liée à un produit.</p>';
    } else {
        // Supprimer la catégorie
        $sql_delete = "DELETE FROM mostafa_categorie WHERE id_categorie = :id_categorie";
        $sth_delete = $dbh->prepare($sql_delete);
        $sth_delete->execute(['id_categorie' => $id_categorie]);

        // Réinitialiser la variable de session
        unset($_SESSION['id_categorie']);

        // Rediriger vers la page de confirmation
        header('Location: index.php?page=delete_categorie&mode=deleted');
        exit();
    }
}
?>



<?php if (isset($_GET['mode']) && $_GET['mode'] === 'deleted') : ?>
    <p class="confirm">Catégorie supprimée avec succès.</p>
<?php endif; ?>


<?php
// affichage des catégories
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
                <?php if ($habilitation !== 'admin') : ?>
                    <th>Supprimer</th>
                <?php endif; ?>
                <th>Afficher</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Id Categorie</th>
                <th>Photo</th>
                <th>Nom</th>
                <th>Slug</th>
                <th>Date d'inserstion</th>
                <?php if ($habilitation !== 'admin') : ?>
                    <th>Supprimer</th>
                <?php endif; ?>
                <th>Afficher</th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($result as $row) : ?>
                <tr>
                    <td><?= $row->id_categorie; ?></td>
                    <td><img class="vignette" src="<?= $row->photo; ?>"></td>
                    <td><?= $row->nom; ?></td>
                    <td><?= $row->slug; ?></td>
                    <td><?= $row->date_inserstion; ?></td>
                    <?php if ($habilitation !== 'admin') : ?>
                        <td><a href="index.php?page=delete_categorie&id_categorie=<?= $row->id_categorie; ?>">Supprimer</a></td>
                    <?php endif; ?>
                    <td><a href="index.php?page=display_categorie&mode=mostafa_categorie&id_categorie=<?= $row->id_categorie; ?>">Afficher</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
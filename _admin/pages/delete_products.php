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



<h1>Supprimer un produit</h1>

<?php if (isset($_GET['id'])) : ?>
    <?php $_SESSION['id'] = $_GET['id']; ?>
    <!-- <p class="alert">Etes-vous sûr de vouloir supprimer ce produit<a href="index.php?page=delete&mode=delete">Supprimer</a></p> -->
    <form id="form-ident" method="post" action="index.php?page=delete_products&mode=delete_products">
        <p class="alert">Êtes-vous sûr de vouloir supprimer ce produit ?</p>
        <input type="hidden" name="confirm_delete" value="true">
        <input type="submit" value="Supprimer">
    </form>
<?php endif; ?>

<?php if (isset($_GET['mode'])) {
    $sql_delete = "DELETE FROM mostafa_products WHERE id = :id";
    $sth_delete = $dbh->prepare($sql_delete);
    $sth_delete->execute([
        'id' => $_SESSION['id']
    ]);
}
?>

<?php if (isset($_GET['mode'])) : ?>
    <p class="confirm">le produit supprimée avec succès.</p>
<?php endif; ?>


<?php
// affichage des articles
$sql = "SELECT id, titre, DATE_FORMAT(date_inserstion, '%d-%m-%Y %H:%i:%s') AS date_inserstion, photo FROM mostafa_products";
$sth = $dbh->query($sql);
$result = $sth->fetchAll(PDO::FETCH_OBJ);
$nbre_result = $sth->rowCount();
?>

<section id="modif">
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Photo</th>
                <th>Titre</th>
                <th>Date d'inserstion</th>
                <?php if ($habilitation !== 'admin') : ?>
                    <th>Supprimer</th>
                <?php endif; ?>
                <th>Afficher</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Photo</th>
                <th>Titre</th>
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
                    <td><?= $row->id; ?></td>
                    <td><img class="vignette" src="<?= $row->photo; ?>"></td>
                    <td><?= $row->titre; ?></td>
                    <td><?= $row->date_inserstion; ?></td>
                    <?php if ($habilitation !== 'admin') : ?>
                        <td><a href="index.php?page=delete_products&id=<?= $row->id; ?>">Supprimer</a></td>
                    <?php endif; ?>
                    <td><a href="index.php?page=display_products&mode=mostafa_products&id=<?= $row->id; ?>">Afficher</a></td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

</section>
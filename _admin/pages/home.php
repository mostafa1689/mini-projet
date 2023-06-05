<?php


if (isset($_SESSION['identifiant'])) {
    $hello = 'Bonjour : ' . $_SESSION['habilitation'];
    $habilitation = $_SESSION['habilitation'];
    
} else {
    return; // or exit()
}
?>

<?php
    // Affichage des produits
    $sql = "SELECT* FROM mostafa_admin
    ORDER BY nom";

    $sth = $dbh->query($sql);
    $result = $sth->fetchAll(PDO::FETCH_OBJ);
    $nbre_result = $sth->rowCount();
    ?>


<div class="h-boutique">
        <h1>Utilisateurs</h1>
    </div>
<section id="modif">
    <table>
        <thead>
            <tr>
                <th>Identifiant </th>
                <th>Password </th>
                <th>Nom d'utilisateur </th>
                <th>Prenom d'utilisateur </th>
                <th>Photo</th>
                <th>Date d'inserstion</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row) : ?>
                <tr>
                    <td><?= $row->identifiant; ?></td>
                    <td><?= $row->mdp; ?></td>
                    <td><?= $row->nom; ?></td>
                    <td><?= $row->prenom; ?></td>
                    <td><img src="../upload/<?= $row->photo ?>" alt="<?= $row->photo; ?>"></td>
                    <td><?= $row->date_inserstion; ?></td>
                    <td><?= $row->habilitation; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>



            





<?php if (isset($message)) : ?>
    <p class="alert"><?= $message; ?></p>
<?php endif; ?>

<h1>Que voulez-vous faire?</h1>

<?php if (isset($message)) : ?>
    <p class="alert"><?= $message; ?></p>
<?php endif; ?>

<nav class="nav">
    
    <div>
        <button><a href="index.php?page=insert_products">Ajouter un produit</a></button>
        <button><a href="index.php?page=modif_products">Modifier un produit</a></button>
        <?php if ($habilitation === 'super_admin') : ?>
            <button><a href="index.php?page=delete_products">Supprimer un produit</a></button>
        <?php else : ?>

            <button type="button" onclick="alert('Il faut vous connecter en tant que super_admin.');"><a>Supprimer un produit</a></button>
        <?php endif; ?>

    </div>
    <div>
        <button><a href="index.php?page=insert_categorie">Ajouter une catégorie</a></button>
        <button><a href="index.php?page=modif_categorie">Modifier une catégorie</a></button>
        <?php if ($habilitation === 'super_admin') : ?>
            <button><a href="index.php?page=delete_categorie">Supprimer une catégorie</a></button>


        <?php else : ?>
            <button type="button" onclick="alert('Il faut vous connecter en tant que super_admin.');"><a>Supprimer une catégorie</a></button>
        <?php endif; ?>

    </div>
</nav>
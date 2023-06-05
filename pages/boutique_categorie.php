<main>
    <div class="h-boutique">
        <h1>Boutique</h1>
    </div>



    <?php
    // Affichage des produits
    $sql = "SELECT* FROM mostafa_categorie 
    ORDER BY nom";

    $sth = $dbh->query($sql);
    $result = $sth->fetchAll(PDO::FETCH_OBJ);
    $nbre_result = $sth->rowCount();
    ?>



    <div class="boutique-categorie">
        <?php foreach ($result as $row) : ?>
            <div>
                <a href="index.php?page=boutique_products&id_categorie=<?= $row->id_categorie; ?>&nom_categorie=<?= $row->nom; ?>">


                    <img src="upload/<?= basename($row->photo) ?>" alt="<?= $row->nom; ?>">
                </a>
                <h3><?= $row->nom; ?></h3>
            </div>
        <?php endforeach; ?>
    </div>

</main>
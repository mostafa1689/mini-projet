<main>
    <?php if ($_GET) {
        // Affichage des produits
        $id_categorie = $_GET['id_categorie'];
        $nom_categorie = $_GET['nom_categorie'];
        $sql = "SELECT * FROM mostafa_products WHERE id_categorie = :id_categorie ORDER BY titre";
        $sth = $dbh->prepare($sql);
        $sth->execute([
            'id_categorie' => $id_categorie
        ]);
        $result = $sth->fetchAll(PDO::FETCH_OBJ);
        $nbre_result = $sth->rowCount();
    } ?>

    <div class="h-boutique">
        <h1>Boutique / <?= $nom_categorie; ?></h1>
    </div>



    <div class="align-horizon">

        <div class="sidebar">
            <div class="categorie-buttons">
                <?php
                $sql_categories = "SELECT id_categorie, nom FROM mostafa_categorie ORDER BY nom";
                $sth_categories = $dbh->query($sql_categories);
                $result_categories = $sth_categories->fetchAll(PDO::FETCH_OBJ);

                $active_category = isset($_GET['id_categorie']) ? $_GET['id_categorie'] : null;
                ?>
                <?php foreach ($result_categories as $row) : ?>
                    <?php
                    $button_class = ($row->id_categorie == $active_category) ? 'active' : '';
                    ?>
                    <button class="<?= $button_class; ?>"><a href="index.php?page=boutique_products&id_categorie=<?= $row->id_categorie; ?>&nom_categorie=<?= $row->nom; ?>"><?= $row->nom; ?></a></button>
                <?php endforeach; ?>
            </div>
            <div class="tag-btn">
                 <button class="sucre-btn"><a href="index.php?page=boutique_products&id_categorie=<?= $id_categorie; ?>&nom_categorie=<?= $nom_categorie; ?>&type=sucre">Sucré</a></button>
                <button class="sale-btn"><a href="index.php?page=boutique_products&id_categorie=<?= $id_categorie; ?>&nom_categorie=<?= $nom_categorie; ?>&type=sale">Salé</a></button>
                
            </div>
            <div class="bio-btn">
            <button ><a href="index.php?page=boutique_products&id_categorie=<?= $id_categorie; ?>&nom_categorie=<?= $nom_categorie; ?>&type=bio">Bio</a></button>
            </div>
        </div>
        <div class="boutique-categor">
            <?php foreach ($result as $row) : ?>
                <div>
                    <a href="index.php?page=display_products&id=<?= $row->id; ?>&nom_categorie=<?= $nom_categorie; ?>">
                        <img src="upload/<?= basename($row->photo) ?>" alt="<?= $row->titre; ?>">
                    </a>
                    <h3><?= $row->titre; ?></h3>
                    <?php if ($row->prix_promo != 0) : ?>
                        <label class="product-label">Prix Promo :</label>
                        <p class="product-price-promo"><?= $row->prix_promo; ?> €</p>
                    <?php else : ?>
                        <label class="product-label">Prix :</label>
                        <p class="product-price"><?= $row->prix; ?> €</p>
                    <?php endif; ?>
                    <button class="add-to-cart-btn">Ajouter au panier</button>
                </div>
            <?php endforeach; ?>
        </div>







</main>
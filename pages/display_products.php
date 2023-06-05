<?php if ($_GET) {
    $id = $_GET['id'];
    $nom_categorie = $_GET['nom_categorie'];

    $sql = "SELECT * FROM mostafa_products WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute([
        'id' => $id
    ]);
    $result = $sth->fetch(PDO::FETCH_OBJ);

    //var_dump_pre($result);

}
?>
<php?>
    <div class="h-boutique">
        <h1>Boutique / <?= $nom_categorie; ?>/ <?= $result->titre; ?></h1>
    </div>

    </php>
    <div class="align-horizon">
        <div class="sidebar">
            <div class="categorie-buttons">
                <?php
                $sql_categories = "SELECT id_categorie, nom FROM mostafa_categorie ORDER BY nom";
                $sth_categories = $dbh->query($sql_categories);
                $result_categories = $sth_categories->fetchAll(PDO::FETCH_OBJ);

                $active_category = isset($_GET['nom_categorie']) ? $_GET['nom_categorie'] : null;
                ?>
                <?php foreach ($result_categories as $row) : ?>
                    <?php
                    $button_class = ($row->nom == $active_category) ? 'active' : '';
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

        <div class="product-details">

            <div class="product-img">
                <img src="upload/<?= basename($result->photo) ?>" alt="<?= $result->titre; ?>">
            </div>
            <div class="product-contenu">
                <h2><?= $result->titre; ?></h2>
                <?php if ($result->stock) : ?>
                    <p class="product-stock">En stock</p>
                <?php else : ?>
                    <p class="rupture-stock">En rupture de stock</p>
                <?php endif; ?>
                <?php if ($result->prix_promo != 0) : ?>
                    <label class="product-label">Prix :</label>
                    <p class="product-price-strike"><?= $result->prix; ?> €</p>
                    <label class="product-label">Prix Promo :</label>
                    <p class="product-price-promo"><?= $result->prix_promo; ?> €</p>
                <?php else : ?>
                    <label class="product-label">Prix :</label>
                    <p class="product-price"><?= $result->prix; ?> €</p>
                <?php endif; ?>
                <label class="product-label">Description :</label>
                <p class="product-description"><?= nl2br($result->descriptif); ?></p>
                <button class="add-to-cart-btn">Ajouter au panier</button>
            </div>
        </div>



    </div>
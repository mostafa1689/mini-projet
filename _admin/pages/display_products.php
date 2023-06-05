<?php
if (isset($_SESSION['identifiant'])) {
    $hello = 'Bonjour ' . $_SESSION['identifiant'];
} else {
    return; // ou exit()
}
?>

<?php if (isset($hello)) : ?>
    <div class="message">
        <h2><?= $hello; ?></h2>
    </div>
<?php endif; ?>

<?php if ($_GET) {
    $mode = $_GET['mode'];
    $id = $_GET['id'];

    $sql = "SELECT * FROM $mode WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute([
        'id' => $id
    ]);
    $result = $sth->fetch(PDO::FETCH_OBJ);

    //var_dump_pre($result);

}
?>

<div class="product-details">

    <div class="product-img">
        <img class="vignette" src="<?= $result->photo; ?>">
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
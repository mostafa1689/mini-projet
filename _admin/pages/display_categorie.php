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
    $mode_categorie = $_GET['mode'];
    $id_categorie = $_GET['id_categorie'];

    $sql = "SELECT * FROM mostafa_categorie WHERE id_categorie = :id_categorie";
    $sth = $dbh->prepare($sql);
    $sth->execute([
        'id_categorie' => $id_categorie
    ]);
    $result = $sth->fetch(PDO::FETCH_OBJ);

    //var_dump_pre($result);
}
?>


<section id="edit">
    <h1><?= $result->nom; ?></h1>
    <p><img class="vignette" src="<?= $result->photo; ?>"></p>

</section>
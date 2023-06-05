<?php
session_start();
require('../inc/_connection.php');
require('../inc/functions.php');
?>
<?php

/////////////////////////////////////////////
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $message = 'Etes vous sur de voiloir supprimer l\'article? :<a href="supprimer.php?sup=1&id=';
    $message .= $id;
    $message .= '">';
    $message .= "OUI";
    $message .= '</a>';
} else {
    $message = "";
}



if (isset($_GET['sup'])) {
    $id = $_GET['id'];

    // on recupère le nom de l'image pour la supprimer
    $req = "SELECT photo FROM article WHERE id='$id'";
    $res = $conn->query($req);
    $data = mysqli_fetch_array($res);
    $photo = $data['photo'];
    $photo_url = '../upload/'.$photo;
    if (file_exists($photo_url)) {
        unlink($photo_url);
    }
    // et la vignette
    $tab_photo = explode('.', $photo);
    $photo_redim = $tab_photo[0].'-'.LARGEUR_VIGNETTE.'.'.$tab_photo[1];
    $photo_url = '../upload/'.$photo_redim;
    if (file_exists($photo_url)) {
        unlink($photo_url);
    }
    // on supprime l'entrée dans la base
    $req = "DELETE FROM article WHERE id= '$id'";
    $res = $conn->query($req);
    $message = 'Article supprimé';
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<?php include('_menu.php'); ?>

<h1>Suppression</h1>

<?php if (isset($message) && $message !=""): ?>
    <div class="message">
        <p><?php echo $message; ?></p>
    </div>
<?php endif; ?>

<?php
////////////////////////////////////////////////////
$req = "SELECT id, title FROM article";
$res = $conn->query($req);
?>

<table>
    <?php while($data = mysqli_fetch_array($res)): ?>
        <tr>
            <td>
                <?php echo $data['id']; ?>
            </td>
            <td>
                <?php echo $data['title']; ?>
            </td>
            <td>
                <a href="supprimer.php?id=<?php echo $data['id'];?>">
                    Supprimer
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Free Web tutorials">
    <title>Projet initiation HTML/CSS/PHP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/style-admin.css">


    <style>
        main {
            width: 100%;
        }
    </style>



</head>

<body id="admin">
    <div id="content">
        <header>
            <div class="align-horiz">
                <a href="index.php"><img src="../img/logo-via.jpg" class="anim-translateX" alt="logo via formation"></a>
                <h1>Administration</h1>
            </div>

            <!-- primary nav -->
            <?php if (isset($menu)) : ?>
                <nav id="primary-nav">
                    <ul class="align-horiz">
                        <li><a href="index.php?page=home">Accueil</a></li>
                        <li><a href="index.php?page=login">se déconnecter </a></li>
                        <li><a href="../">Voir site</a></li>
                    </ul>
                </nav>
                <nav id="mobile-nav">
                    <ul>
                        <li><a href="index.php?page=home">Accueil</a></li>
                        <li><a href="index.php?page=login">se déconnecter </a></li>
                        <li><a href="../">Voir site</a></li>
                    </ul>
                </nav>
            <?php endif; ?>
        </header>
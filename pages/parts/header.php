<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Free Web tutorials">
    <title>Mini projet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">

    <?php if ($aside === false) : ?>
        <style>
            main {
                width: 100%;
            }
        </style>
    <?php endif; ?>

</head>

<body>
    <div id="content">
        <header>
            <nav id="social-nav">
                <ul class="align-horiz">
                    <li><a href="#"><i class="fa-brands fa-facebook fa-2x anim-translateY"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-twitter fa-2x anim-translateY"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-instagram fa-2x anim-translateY"></i></a></li>
                </ul>
            </nav>
            <div class="align-horiz">
                <a href="index.php"><img src="img/logo-via.jpg" class="anim-translateX" alt="logo via formation"></a>
                <h1>Mini projet</h1>
                
            </div>
            
            <!-- primary nav -->
            <div class="btn-recherche">
            <input type="text" placeholder="Rechercher un produit">
            <button type="submit" id="btn-search">OK</i></button>
            </div>

            <nav id="primary-nav">
                <ul class="align-horiz">
                    <li><a href="index.php?page=home">Accueil</a></li>
                    <li><a href="index.php?page=boutique_categorie">Boutique</a></li>
                    <li><a href="index.php?page=contact">Contact</a></li>
                    <li><a href="_admin/">Admin</a></li>
                </ul>
            </nav>
            <nav id="mobile-nav">
                <ul>
                    <li><a href="index.html">Accueil</a></li>
                    <li><a href="boutique_categorie.php">Boutique</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </nav>
        </header>
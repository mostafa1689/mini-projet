<main>
    <section id="contact-confirm">
        <h2>Validations de vos données</h2>

        <?php // Validation : envoi d'un email à l'admin
        // Exo: à mettre sous fonction:
        // Parametres: $to, $subject, $message, $from
        
        if (isset($_GET['valid'])) : ?>        

        <?php // envoi email
            // message
            $message = '
            <html>
            <head>
            <title>Nouvel abonné</title>
            </head>
            <body>';
            $message .= '<img src="' . URL_WEBSITE . '/__patk/' . $_SESSION['url_photo'] . '" alt="Photo de profil" style="max-width:250px;height: 250px;object-fit: contain;border-radius: 50%;">';
            foreach ($_SESSION as $key => $value) {
                if ($key === 'ip' || $key == 'url_photo') continue;
                $key = display_label($key);
                if (is_array($value)) {
                    foreach ($value as $item) {
                        $message .= '<p>' . $key . ': ' . $item . '</p>';
                    }
                } else {
                    $message .= '<p>' . $key . ': ' . $value . '</p>';
                }
            }
            $message .=  '</body>';
            $message .=  '</html>'; 
            
             // Sujet de l'email
             $subject = 'Nouvel abonné';

            
    
        $response = send_email(EMAIL_FROM,EMAIL_ADMIN, $subject, $message );
            
        ?>



        <?php 
        // Affichage des infos pour validation
        else : ?>
            <div class="align-horiz">

                <?php if (isset($_SESSION['url_photo'])) : ?>
                    <div>
                        <img class="thumbnail" src="<?= $_SESSION['url_photo'] ?>" alt="">
                    </div>
                <?php endif; ?>

                <div>
                    <?php foreach ($_SESSION as $name => $value) : ?>
                        <?php if ($name === 'ip' || $name === 'url_photo') continue; ?>

                        <?php if($name == 'date_entree') echo $value;?>

                        <?php $name = display_label($name); ?>
                        
                        <?php if (is_array($value)) :  ?>

                            <?php foreach ($value as $item) : ?>
                                <p><?= $name ?> : <?= $item ?></p>
                            <?php endforeach; ?>

                        <?php else : ?>
                            <p><?= $name ?> : <?= $value ?> </p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div>
            </div>

            <?php if (isset($response)) : ?>
                <p class="error"><?= $response[1]; ?></p>
            <?php endif; ?>

            <p>
                <a href="index.php?page=contact-confirm&valid=1" class="bt confirm">Confirmer vos informations</a>
                <a href="index.php?page=contact" class="bt error">Modifier vos informations</a>
            </p>
    </section>
</main>
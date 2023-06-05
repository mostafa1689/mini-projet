<?php
// Si modification
if(isset($_SESSION['url_photo'])) {
    // permet d'injecter tous les data dans le post
    // et donc de rentrer dans la fonction valid_form()
    $_POST = $_SESSION;
    // ici on detruit la session pour eviter que ce soit tj les mêmes entrées qui s'affichent dans le formulaire
    $_SESSION  = [];
    session_destroy(); 
}



if ($_POST) {
    require_once('_admin/include/_connection.php');
    // include/functions.php: 95
    $tab_return_errors = valid_form();
    if(!$tab_return_errors) {

        $sql = "CREATE TABLE IF NOT EXISTS inscription (
            id INT UNSIGNED AUTO_INCREMENT,
            ip VARCHAR(50),
            pseudo VARCHAR(50),
            age VARCHAR(3),
            email VARCHAR(50),
            date_entree DATE,
            tel VARCHAR(15),
            tab_profil VARCHAR(200),
            pays VARCHAR(50),
            tab_niveau VARCHAR(200),
            website VARCHAR(100),
            comment TEXT,
            photo VARCHAR(50),
            date_inscription DATETIME,
            -- contraintes
            PRIMARY KEY(id)
            -- contraintes sup 
            -- UNIQUE(pseudo)
            -- UNIQUE(tel)
            -- UNIQUE(email)
            ) ENGINE = INNODB
        ";

        $sth = $dbh -> exec($sql);

        $sql = "INSERT INTO inscription(ip, pseudo, age, email, date_entree, tel, tab_profil, pays, tab_niveau, website, comment, photo, date_inscription)
        VALUES(?,?,?,?,?,?,?,?,?,?,?,?, NOW())";

        $sth = $dbh -> prepare($sql);

        $tab_param = ['ip', 'pseudo', 'age', 'email', 'date_entree', 'tel', 'tab_profil', 'pays', 'tab_niveau', 'website', 'comment', 'photo'];
        foreach($tab_param as $key => $value) {
            $key++;
            $sth -> bindParam($key, ${$value}, PDO::PARAM_STR);
        }

        // insertion dans la table
        foreach($_POST as $key => $value) {
            // les name du form type array
            if(is_array($value)) {
                $value = serialize($value);                
            }
            // les name du form type non array
            ${$key} = $value;
        }
        $photo = $_SESSION['url_photo'];

        $sth -> execute();

        // redirection 
        // mise en memmoire tampon ob_start() index:2
        // effacement... index:47
        // voir ressource: https://stackoverflow.com/questions/8028957/how-to-fix-headers-already-sent-error-in-php
        header('Location: index.php?page=contact-confirm'); 
    }


    // Pour injection dans les value des objets de formulaire
    foreach ($_POST as $key => $value) {
        ${$key} = $value;
    }
}
?>

<div class="align-horiz">
<main>
    <section id="contact">
        <?php if ($_POST) : ?>
            <?php if (isset($tab_return_errors[0])) : ?>
                <p class="error"><?= $tab_return_errors[1]; ?></p>
            <?php endif; ?>
        <?php endif; ?>

        <h2>Formulaire de contact</h2>
        <form action="<?= $_SERVER['PHP_SELF'].'?page=contact'; ?>" method="post" id="form-contact" enctype="multipart/form-data">
            <input type="hidden" name="ip" value="<?= $_SERVER['REMOTE_ADDR'] ?>">
            <div class="align-horiz">
                <fieldset class="align-vert">
                    <legend>Coordonnées</legend>
                    <label for="pseudo">Votre pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" autofocus value="<?= $pseudo ?? ''; ?>">

                    <label for="email">Votre email</label>
                    <input type="email" name="email" id="email" value="<?= $email ?? ''; ?>">

                    <label for="age">Votre âge</label>
                    <input type="number" name="age" id="age" max="120" min="18" step="1" value="<?= $age ?? ''; ?>">

                    <label for="date-entree">date d'entrée</label>
                    <input type="date" name="date_entree" id="date-entree" value="<?= $date_entree ?? ''; ?>">

                
                    <label for="photo">Photo de profil (largeur max: <?= MAX_WIDTH; ?>px ; Taille max <?= MAX_SIZE_UPLOAD / 1024; ?>Ko)</label>
                    <input type="file" name="photo" id="photo">

                    <label for="tel">Téléphone</label>
                    <input type="tel" name="tel" id="tel" value="<?= $tel ?? ''; ?>">
                </fieldset>

                <fieldset>
                    <legend>Mes passions</legend>
                    <p>Etes vous plutôt:</p>
                    <?php $data_profil = ['Scientifique', 'Artistique']; ?>
                    <?php foreach ($data_profil as $key => $profil) : ?>
                        <p><?= $profil ?></p>

                        <?php if (isset($tab_profil)) {
                            $checked = "";
                            foreach ($tab_profil as $profil_choice) {
                                if ($profil == $profil_choice) {
                                    $checked = 'checked';
                                }
                            }
                        } else {
                            $checked = "";
                        }

                        ?>
                        <input type="checkbox" name="tab_profil[]" id="<?= $profil; ?>" value="<?= $profil; ?>" <?= $checked ?>>
                    <?php endforeach; ?>


                    <label for="pays">Votre pays préféré</label>
                    <?php $data_country = ['France', 'Italie', 'Grèce'] ?>
                    <select id="pays" name="pays">

                        <?php if (isset($pays)) : ?>
                            <option value="<?= $pays; ?>"><?= $pays; ?></option>
                        <?php else : ?>
                            <option value="">Choix</option>
                        <?php endif; ?>
                        
                        <?php foreach ($data_country as $country) : ?>
                            <option value="<?= $country; ?>"><?= $country; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <p> Votre niveaux?</p>
                    <?php $data_level = ['bon', 'moyen', 'debutant'] ?>
                    <?php foreach ($data_level as $level) : ?>

                        <?php if (isset($tab_niveau)) {
                            $checked = "";
                            foreach ($tab_niveau as $niveau_choice) {
                                if ($level == $niveau_choice) {
                                    $checked = 'checked';
                                }
                            }
                        } else {
                            $checked = "";
                        }
                        ?>
                        <label for="<?= $level; ?>"><?= $level; ?></label>
                        <input type="radio" name="tab_niveau[]" id="<?= $level; ?>" value="<?= $level; ?>" <?= $checked ?>>
                    <?php endforeach; ?>

                    <label for="website">Votre site Web</label>
                    <input type="url" name="website" id="website" value="<?= $website ?? ''; ?>">

                    <label for="comment">Vos commentaires (<?= MAX_CARACT_TEXTAREA  ?> caractères autorisés)</label>
                    <textarea name="comment" id="comment"><?= $comment ?? ''; ?></textarea>
                </fieldset>
            </div>
            <input type="submit" value="Envoyer">
        </form>
    </section>
</main>
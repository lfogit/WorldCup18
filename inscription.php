<?php
session_start();
if (isset($_SESSION['login'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<html>
<head>
<title>Inscription | Pronostics coupe du monde 2018</title>
    <link href='https://fonts.googleapis.com/css?family=Mina'
    rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans'
    rel='stylesheet'>
    <link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <div align="left">
        <font style="font-family: 'Mina'; font-size: 20px;"><b><a href="index.php"><b>PRONOSTICS COUPE DU MONDE 2018</b></a></font>
    </div><br/>
    <?php
    include('connect.php');

    if (isset($_POST['pseudo']) and isset($_POST['email']) && isset($_POST['mdp'])) {
        if (!ctype_alnum($_POST['pseudo'])) {?>
            <center><font style="font-size: 20px;">Seuls les caractères alphanumériques sont autorisés dans le nom d'utilisateur !</font></center>
            <?php
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {?>
            <center><font style="font-size: 20px;">L'adresse email renseignée est incorrecte !</font></center>
            <?php
        } elseif ($_POST['mdp'] != $_POST['mdp2']) {?>
            <center><font style="font-size: 20px;">Les deux mots de passe diffèrent !</font></center>
            <?php
        } elseif (strlen($_POST['mdp']) < 8) {?>
            <center><font style="font-size: 20px;">Le mot de passe doit faire 8 caractères minimum !</font></center>
            <?php
        } elseif (!isset($_POST['captcha']) || !isset($_SESSION['captcha']) || !ctype_digit($_POST['captcha'])  || (int)$_POST['captcha'] != $_SESSION['captcha']) {?>
            <center><font style="font-size: 20px;">Le résultat du test anti-robot n'est pas concluant...</font></center>
            <?php
        } else {
            $req = $bdd->prepare("SELECT COUNT(*) AS cnt FROM users WHERE LOWER(login)=LOWER(:pseudo)");
            $req->execute(array(
                'pseudo' => $_POST['pseudo'],
            ));
            $num_name = $req->fetch();

            $req = $bdd->prepare("SELECT COUNT(*) AS cnt FROM users WHERE email=:mail");
            $req->execute(array(
                'mail' => $_POST['email']
            ));
            $num_mail = $req->fetch();

            if ($num_name['cnt'] != '0') {?>
                <center><font style="font-size: 20px;">Nom d'utilisateur déjà pris !</font></center>
                <?php
            } elseif ($num_mail['cnt'] != '0') {?>
                <center><font style="font-size: 20px;">Adresse email déjà utilisée !</font></center>
                <?php
            } else {
                $clef = md5(microtime(TRUE) * 100000);
                $req = $bdd->prepare("INSERT INTO users(login, mdp, email, clef) VALUES(:pseudo, :mdp, :mail, :key)");
                $req->execute(array(
                    'pseudo' => $_POST['pseudo'],
                    'mdp' => password_hash($_POST['mdp'], PASSWORD_DEFAULT),
                    'mail' => $_POST['email'],
                    'key' => $clef
                ));

                $destinataire = $_POST['email'];
                $sujet = 'Activation de votre compte';
                $header = 'From: "Pronostics CDM2018"<noreply@antoineplanchot.eu>';
                $message = 'Bonjour ' . $_POST['pseudo'] . '.

Votre inscription est presque finalisée, il ne vous reste plus qu\'à activer votre compte en cliquant sur le lien ci-dessous ou le copiant dans la barre d\'adresse de votre navigateur.

' . 'https://lab.antoineplanchot.eu/cdm2018/activation.php?log=' . urlencode($_POST['pseudo']) . '&clef=' . urlencode($clef) . '

----
Cet email a été envoyé automatiquement, merci de ne pas y répondre.';

                mail($destinataire, $sujet, $message, $header);
                header('Location: activation.php');
                exit();
            }
        }
    }
    ?>
    <table border="0" align="center">
        <tr>
            <td width="350">
                <font style="font-size: 20px;">
                    <font style="font-size: 30px;"><b>Inscription</b></font><br/><br/>
                    <form method="post" action="inscription.php">
                        Pseudo<br/><input type="text" name="pseudo"
                        <?php
                        if (isset($_POST['pseudo'])) {
                            echo 'value="' . $_POST['pseudo'] . '" ';
                        }
                        ?>/><br/><br/>
                        Adresse e-mail<br/><input type="text" name="email"
                        <?php
                        if (isset($_POST['email'])) {
                            echo 'value="' . $_POST['email'] . '" ';
                        }
                        ?>/><br/><br/>
                        Mot de passe<br/><input type="password" name="mdp"/><br/><br/>
                        Confirmation<br/><input type="password" name="mdp2"/><br/><br/>
                        Test anti-robot<br/>
                        <?php
                        $nb = ['un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf'];
                        $op = ['plus', 'fois'];
                        $a = rand(1, 9);
                        $b = rand(1, 9);
                        $sgn = rand(0, 1);

                        if ($sgn == 0) {
                            $_SESSION['captcha'] = $a + $b;
                        } else {
                            $_SESSION['captcha'] = $a * $b;
                        }
                        ?>
                        <font style="font-size: 15px;"><i>Donnez le résultat en chiffres de <?php echo $nb[$a-1] . ' ' . $op[$sgn] . ' ' . $nb[$b-1];?></i></font><br/>
                        <input type="text" name="captcha"/><br/><br/>
                        <input type="submit" value="C'est parti !"/>
                    </form>
                </font>
            </td>
        </tr>
    </table>
</body>
</html>
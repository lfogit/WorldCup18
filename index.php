<?php
session_start();
if (isset($_SESSION['login'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<html>
<head>
<title>WorldCup 2018</title>
    <link href='https://fonts.googleapis.com/css?family=Mina'
    rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans'
    rel='stylesheet'>
    <link href='style.css' rel='stylesheet' type='text/css'>
</head>

<body>
    <div align="center">
        <font style="font-family: 'Mina'; font-size: 40px;"><b>PRONOSTICS WorldCup 2018</b></font><br>
	<img src="img/FIFA_World_Cup_2018_Logo.png" alt="Programme" style="height:150px;">
    </div><br/>
    <table align="center" border="0">
        <tr>
            <td width="300">
                <font style="font-size: 20px;">
                    <font style="font-size: 30px;"><b>Connexion</b></font><br/><br/>
                    <form method="post" action="index.php">
                        Nom d'utilisateur<br/><input type="text" name="pseudo" <?php echo (isset($_POST['pseudo']) ? 'value="' . $_POST['pseudo'] . '"': '')?>/><br/><br/>
                        Mot de passe<br/><input type="password" name="mdp"/><br/><br/>
                        <input type="submit" value="Connexion"/>
                    </form>
                </font>
            </td>
        </tr>
    <?php
    include('connect.php');

    if (isset($_POST['pseudo']) and isset($_POST['mdp'])) {
        $req = $bdd->prepare("SELECT login, mdp, email, clef, actif FROM users WHERE LOWER(login)=LOWER(:pseudo)");
        $req->execute(array(
            'pseudo' => $_POST['pseudo']
        ));
        $data = $req->fetch();
        if (!$data) {?>
            <tr>
                <td align="center">
                    <font style="font-size: 15px;">Utilisateur inconnu</font>
                </td>
            </tr>
            <?php
        } else {
            if (password_verify($_POST['mdp'], $data['mdp']) && $data['actif'] == '1') {?>
                <tr>
                    <td align="center">
                        <font style="font-size: 15px;">Connexion réussie !<br/>Redirection en cours</font><?php
                        $_SESSION['login'] = $data['login'];
                        header('Location: dashboard.php');
                        exit();
                        ?>
                    </td>
                </tr>
                <?php
            } elseif ($data['actif'] == '0' && $data['email']) {
                $destinataire = $data['email'];
                $sujet = 'Activation de votre compte';
                $header = 'From: "Pronostics CDM2018"<noreply@antoineplanchot.eu>';
                $message = 'Bonjour ' . $data['login'] . '.

Votre inscription est presque finalisée, il ne vous reste plus qu\'à activer votre compte en cliquant sur le lien ci-dessous ou le copiant dans la barre d\'adresse de votre navigateur.

' . 'https://pronos.fortier.fr/activation.php?log=' . urlencode($data['login']) . '&clef=' . urlencode($data['clef']) . '

----
Cet email a été envoyé automatiquement, merci de ne pas y répondre.';

                mail($destinataire, $sujet, $message, $header);
                header('Location: activation.php');
                exit();
            } elseif ($data['actif'] == '0' && !$data['email']) {
                header('Location: regul.php?log=' . $data['login']);
                exit();
            } else {?>
                <tr>
                    <td align="center">
                        <font style="font-size: 15px;">Mot de passe erroné</font>
                    </td>
                </tr>
                <?php
            }
        }
    }
    ?>
        <tr>
            <td align="center">
                <font style="font-size: 20px;">
                    <br/><br/><br/>
                    <a href="inscription.php">Pas encore inscrit ?</a> ⋅ <a href="changemdp.php">Mot de passe oublié</a><br/>
                    <a href="apropos.php">À propos</a>
                </font>
            </td>
        </tr>
    </table>
</body>
</html>

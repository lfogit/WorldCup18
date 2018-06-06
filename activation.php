<?php
session_start();
if (isset($_SESSION['login'])) {
    header('Location: dashboard.php');
    exit();
}

include('connect.php');

$nofield = false;

if (isset($_GET['log']) && isset($_GET['clef'])) {
    $log = $_GET['log'];
    $clef = $_GET['clef'];

    $check = $bdd->prepare("SELECT id FROM users WHERE login=:pseudo AND clef=:key");
    $check->execute(array('pseudo' => $log, 'key' => $clef));
    $usr = $check->fetch();

    if ($usr) {
        $req = $bdd->prepare("UPDATE users SET actif=1 WHERE id=:usr");
        $req->execute(array('usr' => $usr['id']));
        $msg = 'Votre compte a été activé avec succès ! Rendez-vous maintenant à l\'accueil pour vous connecter avec vos identifiants et commencer notre aventure commune.';
        $nofield = true;
    } else {
        $msg = 'Nous avons rencontré un problème, le lien que vous avez suivi semble incorrect. Assurez-vous de ne pas vous être trompé en recopiant l\'URL ou demandez l\'envoi d\'un nouveau mail <i>via</i> le formulaire ci-dessous.';
    }
} elseif(isset($_POST['email'])) {
    $req = $bdd->prepare("SELECT login, clef, actif FROM users WHERE email=:mail");
    $req->execute(array('mail' => $_POST['email']));

    $usr = $req->fetch();
    if ($usr && $usr['actif'] == '0') {
        $destinataire = $_POST['email'];
        $sujet = 'Activation de votre compte';
        $header = 'From: "Pronostics CDM2018"<noreply@antoineplanchot.eu>';
        $message = 'Bonjour ' . $usr['login'] . '.

Votre inscription est presque finalisée, il ne vous reste plus qu\'à activer votre compte en cliquant sur le lien ci-dessous ou le copiant dans la barre d\'adresse de votre navigateur.

' . 'https://lab.antoineplanchot.eu/cdm2018/activation.php?log=' . urlencode($usr['login']) . '&clef=' . urlencode($usr['clef']) . '

----
Cet email a été envoyé automatiquement, merci de ne pas y répondre.';

        mail($destinataire, $sujet, $message, $header);
        $msg = 'Un e-mail vient de vous être envoyé.';
    } elseif ($usr && $usr['actif'] == '1') {
        $msg = 'Votre compte est déjà activé. Rendez-vous en page d\'accueil pour vous connecter avec vos identifiants.';
        $nofield = true;
    } else {
        $msg = 'Cette adresse e-mail nous est inconnue.';
    }
} else {
    $msg = 'Pour finaliser votre inscription et activer votre compte, nous vous invitons à cliquer sur lien contenu dans le mail que nous venons de vous envoyer. Si vous n\'avez rien reçu, vous pouvez demander l\'envoi d\'un nouveau mail <i>via</i> le formulaire ci-dessous.';
}
?>

<html>
<head>
<title>Activation du compte | Pronostics coupe du monde 2018</title>
    <link href='https://fonts.googleapis.com/css?family=Mina'
    rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans'
    rel='stylesheet'>
    <link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <div align="left">
        <font style="font-family: 'Mina'; font-size: 20px;"><a href="index.php"><b>PRONOSTICS COUPE DU MONDE 2018</b></a></font>
    </div><br/>
    <div align="center">
        <font style="font-size: 30px;"><b>Activation de votre compte</b><br/><br/></font>
    </div>
    <table width="80%" align="center" style="border-spacing: 50px;">
        <tr>
            <td width="33%" align="left">
                <font style="font-size: 20px;"><?php echo $msg;?></font>
            </td>
        </tr>
        <?php
        if (!$nofield) {?>
            <tr>
                <td width="33%" align="left">
                    <font style="font-size: 17px;">
                        <form method="post" action="activation.php">
                            Adresse e-mail<br/><input type="text" name="email"/>
                            <input type="submit" value="Valider"/>
                        </form>
                    </font>
                </td>
            </tr>
            <?php
        }?>
    </table>
</body>
</html>
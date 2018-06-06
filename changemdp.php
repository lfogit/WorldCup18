<?php
session_start();
if (isset($_SESSION['login'])) {
    header('Location: dashboard.php');
    exit();
}

include('connect.php');

$msg = 'Pour réinitialiser votre mot de passe, entrez dans le champ ci-dessous votre adresse e-mail.';
$res = '';
$change = false;

if (isset($_POST['email'])) {
    $req = $bdd->prepare("SELECT id, login FROM users WHERE email=:addr");
    $req->execute(array('addr' => $_POST['email']));
    $data = $req->fetch();
    if ($data) {
        $clef = md5(microtime(TRUE) * 100000);
        $req = $bdd->prepare("UPDATE users SET clef=:key WHERE id=:num");
        $req->execute(array('key' => $clef, 'num' => $data['id']));

        $destinataire = $_POST['email'];
        $sujet = 'Réinitialisation de votre mot de passe';
        $header = 'From: "Pronostics CDM2018"<noreply@antoineplanchot.eu>';
        $message = 'Bonjour ' . $data['login'] . ',

Vous avez déclaré avoir oublié votre mot de passe. Merci de suivre le lien ci-dessous ou de le copier dans la barre d\'adresse de votre navigateur pour le réinitialiser.

' . 'https://lab.antoineplanchot.eu/cdm2018/changemdp.php?log=' . urlencode($data['login']) . '&clef=' . urlencode($clef) . '

----
Cet email a été envoyé automatiquement, merci de ne pas y répondre.';

    mail($destinataire, $sujet, $message, $header);
    $res = 'Un e-mail vient de vous être envoyé.';
    } else {
        $res = 'Cette adresse nous est inconnue.';
    }
} elseif (isset($_GET['log']) && isset($_GET['clef'])) {
    $log = $_GET['log'];
    $clef = $_GET['clef'];

    $check = $bdd->prepare("SELECT id FROM users WHERE login=:pseudo AND clef=:key");
    $check->execute(array('pseudo' => $log, 'key' => $clef));
    $usr = $check->fetch();

    if ($usr) {
        $req = $bdd->prepare("UPDATE users SET actif=1 WHERE id=:usr");
        $req->execute(array('usr' => $usr['id']));
        $msg = 'Remplissez le formulaire ci-dessous pour changer votre mot de passe.';
        $change = true;
    } else {
        $msg = 'Nous avons rencontré un problème, le lien que vous avez suivi semble incorrect. Assurez-vous de ne pas vous être trompé en recopiant l\'URL ou demandez l\'envoi d\'un nouveau mail <i>via</i> le formulaire ci-dessous.';
    }
} elseif (isset($_POST['mdp']) && isset($_POST['mdp2']) && isset($_POST['log']) && isset($_POST['clef'])) {
    $log = $_POST['log'];
    $clef = $_POST['clef'];

    $check = $bdd->prepare("SELECT id FROM users WHERE login=:pseudo AND clef=:key");
    $check->execute(array('pseudo' => $log, 'key' => $clef));
    $usr = $check->fetch();

    if ($usr) {
        if ($_POST['mdp'] != $_POST['mdp2']) {
            $res = 'Les mots de passe diffèrent !';
        } elseif (strlen($_POST['mdp']) < 8) {
            $res = 'Le mot de passe doit faire 8 caractères minimum !';
        } else {
            $maj_mdp = $bdd->prepare("UPDATE users SET mdp=:password WHERE login=:pseudo");
            $maj_mdp->execute(array('password' => password_hash($_POST['mdp'], PASSWORD_DEFAULT), 'pseudo' => $log));
            $res = 'Votre mot de passe a été changé avec succès !';
        }
    }
}

?>

<html>
<head>
<title>Mot de passe oublié | Pronostics coupe du monde 2018</title>
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
        <font style="font-size: 30px;"><b>Mot de passe oublié</b><br/><br/></font>
    </div>
    <table width="80%" align="center" style="border-spacing: 50px;">
        <tr>
            <td width="33%" align="left">
                <font style="font-size: 20px;"><?php echo $msg; ?></font>
            </td>
        </tr>
        <?php
        if (!$change) {
        ?>
        <tr>
            <td width="33%" align="left">
                <font style="font-size: 17px;">
                    <form method="post" action="changemdp.php">
                        Adresse e-mail<br/><input type="text" name="email"/><br/><br/>
                        <input type="submit" value="Valider"/>
                    </form>
                </font>
            </td>
        </tr>
        <tr>
            <td align="center">
                <font style="font-size: 15px;"><?php echo $res;?></font>
            </td>
        </tr>
        <?php
        } else {?>
        <tr>
            <td width="33%" align="left">
                <font style="font-size: 17px;">
                    <form method="post" action="changemdp.php">
                        Nouveau mot de passe<br/><input type="password" name="mdp"/><br/><br/>
                        Confirmation<br/><input type="password" name="mdp2"/><br/><br/>
                        <input type="hidden" name="log" value=<?php echo '"' . $_GET['log'] . '"';?>/>
                        <input type="hidden" name="clef" value=<?php echo '"' . $_GET['clef'] . '"';?>/>
                        <input type="submit" value="Valider"/>
                    </form>
                </font>
            </td>
        </tr>
        <tr>
            <td align="center">
                <font style="font-size: 15px;"><?php echo $res;?></font>
            </td>
        </tr>
        <?php
        }?>
    </table>
</body>
</html>
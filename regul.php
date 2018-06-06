<?php
session_start();
if (isset($_SESSION['login'])) {
    header('Location: dashboard.php');
    exit();
}

include('connect.php');

$msg = '';

if (isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['mdp'])) {
	$req = $bdd->prepare("SELECT id, login, mdp FROM users WHERE LOWER(login)=LOWER(:pseudo)");
	$req->execute(array('pseudo' => $_POST['pseudo']));
	$data = $req->fetch();
	if ($data && $data['email'] == '' && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && password_verify($_POST['mdp'], $data['mdp'])) {
		$clef = md5(microtime(TRUE) * 100000);
		$req = $bdd->prepare("UPDATE users SET email=:addr, clef=:key WHERE id=:num");
		$req->execute(array('addr' => $_POST['email'], 'key' => $clef, 'num' => $data['id']));

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
	} elseif ($data && $data['email']) {
		header('Location: index.php');
		exit();
	} elseif($data && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$msg = 'Le format de l\'adresse est incorrect.';
	} elseif($data && !password_verify($_POST['mdp'], $data['mdp'])) {
		$msg = 'Le mot de passe est erroné.';
	} elseif (!$data) {
		$msg = 'Cet utilisateur est inconnu.';
	}
}
?>

<html>
<head>
<title>Régularisation du compte | Pronostics coupe du monde 2018</title>
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
        <font style="font-size: 30px;"><b>Régularisation du compte</b><br/><br/></font>
    </div>
    <table width="80%" align="center" style="border-spacing: 50px;">
        <tr>
            <td width="33%" align="left">
                <font style="font-size: 20px;">Conséquemment à une mise à jour de notre procédure d'authentification, nous vous demandons désormais de renseigner une adresse e-mail valide. Nous avons détecté que vous ne l'avez pas encore fait. Pour y remédier, merci de bien vouloir compléter le formulaire ci-dessous :</font>
            </td>
        </tr>
        <tr>
            <td width="33%" align="left">
                <font style="font-size: 17px;">
                    <form method="post" action="regul.php">
                    	Nom d'utilisateur<br/><input type="text" name="pseudo"
                    	<?php
                        if (isset($_GET['log'])) {
                            echo 'value="' . $_GET['log'] . '" ';
                        }
                        ?>/><br/><br/>
                        Adresse e-mail<br/><input type="text" name="email"/><br/><br/>
                        Mot de passe<br/><input type="password" name="mdp"/><br/><br/>
                        <input type="submit" value="Valider"/>
                    </form>
                </font>
            </td>
        </tr>
        <tr>
            <td align="center">
                <font style="font-size: 15px;"><?php echo $msg;?></font>
            </td>
        </tr>
    </table>
</body>
</html>
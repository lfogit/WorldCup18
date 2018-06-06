<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Tableau de bord | Pronostics coupe du monde 2018</title>
    <link href='https://fonts.googleapis.com/css?family=Mina'
    rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans'
    rel='stylesheet'>
    <link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <div align="left">
        <font style="font-family: 'Mina'; font-size: 20px;"><a href="index.php"><b>PRONOSTICS COUPE DU MONDE 2018</b></a></font>
    </div>
    <div align="right">
        <font style="font-size: 20px;"><a href="logout.php">Déconnexion</a></font>
    </div><br/>
    <div align="center">
        <?php
        include('connect.php');

        $req = $bdd->prepare("SELECT points, id_commu FROM users WHERE login=:pseudo");
        $req->execute(array(
            'pseudo' => $_SESSION['login']
        ));
        $data = $req->fetch();
        $pts = $data['points'];

        ?>
        <font style="font-size: 30px;"><b>Tableau de bord de <?php echo $_SESSION['login'] . '</b> <i>(' . $pts . ')</i>'?><br/><br/></font>
    </div>
    <table width="100%" align="center" style="border-spacing: 50px;">
        <tr>
            <td width="33%" align="center">
                <a href="ranking.php"><img src="img/rank.svg" alt="Classement" style="height:100px;"><br/>
                <font style="font-size: 20px;">Classement</font></a>
            </td>
            <td width="33%" align="center">
                <a href="predictions.php"><img src="img/crystal-ball.svg" alt="Prédictions" style="height:100px;"><br/>
                <font style="font-size: 20px;">Prédictions</font></a>
            </td>
            <td width="33%" align="center">
                <a href="settings.php"><img src="img/parameters.svg" alt="Paramètres" style="height:100px;"><br/>
                <font style="font-size: 20px;">Paramètres</font></a>
            </td>
        </tr>
        <tr>
            <td width="33%" align="center">
                <a href="apropos.php"><img src="img/icon.svg" alt="À propos" style="height:100px;"><br/>
                <font style="font-size: 20px;">À propos</font></a>
            </td>
            <td width="33%" align="center">
                <?php
                if ($data['id_commu'] != 0) {?>
                    <a href="communaute.php"><img src="img/network.svg" alt="Communauté" style="height:100px;"><br/>
                    <font style="font-size: 20px;">Communauté</font></a>
                <?php
                }?>
            </td>
            <td width="33%" align="center">
                <a href="faq.php"><img src="img/question.svg" alt="Foire aux questions" style="height:100px;"><br/>
                <font style="font-size: 20px;">Foire aux questions</font></a>
            </td>
        </tr>
        <tr>
            <td></td>
        <?php
        if ($_SESSION['login'] == 'admin') {?>
            <td width="33%" align="center">
                <br/><br/>
                <font style="font-size: 30px;"><a href="calcul.php">Mise à jour des matchs et des points</a></font>
            </td>
        </tr><?php
        }?>
    </table>

    <div align="center">
        <font style="font-size: 15px;">
            Les icônes sont issues du site <i><a href="https://flaticon.com">Flaticon</a></i>, distribuées sous licence libre, et ont été réalisées par <a href="https://www.flaticon.com/authors/freepik">Freepik</a>, <a href="https://www.flaticon.com/authors/smashicons">Smashicons</a> et <a href="https://www.flaticon.com/authors/designmodo">Designmodo</a>.
        </font>
    </div>
</body>
</html>
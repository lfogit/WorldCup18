<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Les matchs | Pronostics coupe du monde 2018</title>
    <link href='https://fonts.googleapis.com/css?family=Mina'
    rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans'
    rel='stylesheet'>
    <link href='style.css' rel='stylesheet' type='text/css'>
    <style>
    #matchs tr {
        padding: 0px;
    }

    #matchs td {
        height: 100%;
        padding: 0px;
        overflow: hidden;
    }

    #matchs a {
        margin: -1em;
        padding: 1em;
        display: block;
        text-decoration: none;
        color: black;
    }

    #matchs a:hover {
        background: rgba(0, 0, 0, 0.2);
    }
    </style>
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
        ?>
        <font style="font-size: 30px;"><b><i>« Là où on va on n'a pas besoin... de route »</i></b><br/><br/></font>
    </div>
    <table width="100%" align="center">
        <tr>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><b>Matchs individuels</b></font><br/><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><a href="groupes.php">Toute la compétition</a></font><br/><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><a href="divers.php">Paris divers</a></font><br/><br/>
            </td>
        </tr>
    </table>
    <table width="90%" align="center">
        <tr>
            <td width="100%" align="left">
                <font style="font-size: 25px;">Les matchs à venir</font><br/><br/>
                <font style="font-size: 15px;">Ne s'affichent ici que les rencontres dont l'affiche est complètement connue. Les matchs de phase finale apparaîtront donc au fur et à mesure de l'avancée de la compétition.</font><br/><br/>
                &nbsp;&nbsp;&nbsp;&nbsp;<font style="font-size: 15px; background: yellow">&nbsp;&nbsp;MATCH AUJOURD'HUI&nbsp;&nbsp;</font>
                &nbsp;&nbsp;&nbsp;&nbsp;<font style="font-size: 15px; background: grey">&nbsp;&nbsp;PRONOSTIC EXPRIMÉ&nbsp;&nbsp;</font>
                &nbsp;&nbsp;&nbsp;&nbsp;<font style="font-size: 15px; background: white">&nbsp;&nbsp;EN ATTENTE DE VOTRE OPINION&nbsp;&nbsp;</font><br/><br/>
            </td>
        </tr>
    </table>
    <table width="90%" align="center" style="border-spacing: 10px;" id="matchs">
        <?php
        $req = $bdd->query("SELECT matchs.id AS id_match, DATE_FORMAT(date + INTERVAL '2' HOUR, '%d/%m, %Hh%i') AS dt, DATE_FORMAT(date + INTERVAL '2' HOUR, '%Y-%m-%d') AS day, matchs.groupe, eq1.pays AS e1, eq2.pays AS e2 FROM matchs JOIN teams eq1 ON eq1.id = matchs.team1 JOIN teams eq2 ON eq2.id = matchs.team2 WHERE date > NOW() AND played = 0 ORDER BY date ASC");

        $i = 0;
        while ($item = $req->fetch()) {
            $pari = $bdd->prepare("SELECT score1, score2 FROM paris_match JOIN users ON users.id = paris_match.id_user WHERE id_match=:play AND users.login=:usr");
            $pari->execute(array('play' => $item['id_match'], 'usr' => $_SESSION['login']));
            $res = $pari->fetch();
            if ($i % 4 == 0) {?>
            <tr>
            <?php
            }?>
                <td width="20%" align="center" id=<?php echo '"' . $item['id_match'] . '"';?> style="border: 1px solid black; <?php echo 'background-color: ' . (date("Y-m-d") == $item['day']? 'yellow;': (!$res? 'white;': 'grey;')) ?>"><a href=<?php echo "match.php?id=" . $item['id_match']; ?>><div><br/>
                    <font style="font-size: 20px;"><?php echo $item['e1'] . ' — ' . $item['e2'];?></font><br/>
                    <font style="font-size: 15px;">-- / --</font><br/>
                    <font style="font-size: 15px;"><?php echo $item['dt'];?></font><br/>
                    <font style="font-size: 15px;"><b>
                        <?php
                        if ($_SESSION['login'] == 'admin') {
                            echo 'LE MATCH N\'EST PAS ENCORE PASSÉ';
                        } else {
                            if (!$res) {
                                echo 'AUCUN PARI POUR L\'INSTANT';
                            } else {
                                echo 'VOUS PRÉVOYEZ : ' . $res['score1'] . '-' . $res['score2'];
                            }
                        }
                        ?>
                    </b></font><br/><br/>
                </div></a>
                </td><?php
                if ($i % 4 == 3) {?>
                </tr>
                <?php
                }
            $i += 1;
        }
        ?>
    </table>
    <table table width="90%" align="center">
        <tr>
            <td width="100%" align="left">
                <font style="font-size: 25px;">Les matchs en attente du résultat</font><br/><br/>
            </td>
        </tr>
    </table>
    <table width="90%" align="center" style="border-spacing: 10px;" id="matchs">
        <?php
        $req = $bdd->query("SELECT matchs.id AS id_match, DATE_FORMAT(date + INTERVAL '2' HOUR, '%d/%m, %Hh%i') AS dt, matchs.groupe, eq1.pays AS e1, eq2.pays AS e2 FROM matchs JOIN teams eq1 ON eq1.id = matchs.team1 JOIN teams eq2 ON eq2.id = matchs.team2 WHERE date < NOW() AND played = 0 ORDER BY date ASC");

        $i = 0;
        while ($item = $req->fetch()) {
            $pari = $bdd->prepare("SELECT score1, score2 FROM paris_match JOIN users ON users.id = paris_match.id_user WHERE id_match=:play AND users.login=:usr");
            $pari->execute(array('play' => $item['id_match'], 'usr' => $_SESSION['login']));
            $res = $pari->fetch();
            if ($i % 4 == 0) {?>
            <tr>
            <?php
            }?>
                <td width="20%" align="center" style="border: 1px solid black;"><br/>
                    <font style="font-size: 20px;"><?php echo $item['e1'] . ' — ' . $item['e2'];?></font><br/>
                    <font style="font-size: 15px;">-- / --</font><br/>
                    <font style="font-size: 15px;"><?php echo $item['dt'];?></font><br/>
                    <font style="font-size: 15px;"><b>
                        <?php
                        if ($_SESSION['login'] == 'admin') {
                            echo '<a href="match.php?id=' . $item['id_match'] . '">RENSEIGNER LE RÉSULTAT DU MATCH</a>';
                        } else {
                            if (!$res) {
                                echo 'TROP TARD POUR PARIER';
                            } else {
                                echo 'VOUS PRÉVOYEZ : ' . $res['score1'] . '-' . $res['score2'];
                            }
                        }
                        ?>
                    </b></font><br/><br/>
                </td><?php
                if ($i % 4 == 3) {?>
                </tr>
                <?php
                }
            $i += 1;
        }
        ?>
    </table>
    <table width="90%" align="center">
        <tr>
            <td width="20%" align="left">
                <font style="font-size: 25px;">Les matchs passés</font><br/><br/>
            </td>
        </tr>
    </table>
    <table width="90%" align="center" style="border-spacing: 10px;" id="matchs">
        <?php
        $req = $bdd->query("SELECT matchs.id AS id_match, DATE_FORMAT(date + INTERVAL '2' HOUR, '%d/%m, %Hh%i') AS dt, matchs.groupe, eq1.pays AS e1, eq2.pays AS e2, score1, score2 FROM matchs JOIN teams eq1 ON eq1.id = matchs.team1 JOIN teams eq2 ON eq2.id = matchs.team2 WHERE played = 1 ORDER BY date ASC");

        $i = 0;
        while ($item = $req->fetch()) {
            $pari = $bdd->prepare("SELECT score1, score2 FROM paris_match JOIN users ON users.id = paris_match.id_user WHERE id_match=:play AND users.login=:usr");
            $pari->execute(array('play' => $item['id_match'], 'usr' => $_SESSION['login']));
            $res = $pari->fetch();
            if ($i % 4 == 0) {?>
            <tr>
            <?php
            }?>
                <td width="20%" align="center" style="border: 1px solid black;"><br/>
                    <font style="font-size: 20px;"><?php echo $item['e1'] . ' — ' . $item['e2'];?></font><br/>
                    <font style="font-size: 15px;"><b><?php echo $item['score1'] . ' / ' . $item['score2'];?></b></font><br/>
                    <font style="font-size: 15px;"><?php echo $item['dt'];?></font><br/>
                    <font style="font-size: 15px;">
                        <?php
                        if (!$res) {
                            echo 'VOUS N\'AVIEZ PAS PARIÉ';
                        } else {
                            echo 'VOUS PRÉVOYIEZ : ' . $res['score1'] . '-' . $res['score2'];
                        }
                        ?>
                    </font><br/><br/>
                </td><?php
                if ($i % 4 == 3) {?>
                </tr>
                <?php
                }
            $i += 1;
        }
        ?>
    </table>
</body>
</html>
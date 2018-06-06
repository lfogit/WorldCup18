<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Parier sur un match | Pronostics coupe du monde 2018</title>
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
        <font style="font-size: 15px;"><a href=<?php echo "predictions.php#" . $_GET['id']?>>Revenir à la liste des matchs</a></font><br/><br/>

        <?php
        include('connect.php');

        $req = $bdd->prepare("SELECT id FROM users WHERE login=:pseudo");
        $req->execute(array('pseudo' => $_SESSION['login']));
        $id_perso = $req->fetch()['id'];

        $msg = '';

        if (!isset($_GET['id'])) {
            echo 'Hum... Il semblerait que vous ayez touché à un truc que vous n\'auriez pas dû toucher...';
        } else {
            $id_play = $_GET['id'];
            $req = $bdd->prepare("SELECT matchs.groupe AS groupe, eq1.pays AS e1, eq2.pays AS e2, DATE_FORMAT(date + INTERVAL '2' HOUR, '%d/%m, %Hh%i') AS date, date AS dt FROM matchs JOIN teams eq1 ON eq1.id = matchs.team1 JOIN teams eq2 ON eq2.id = matchs.team2 WHERE matchs.id=:id_match");
            $req->execute(array('id_match' => $id_play));
            $match = $req->fetch();
            if (!$match) {
                echo 'Ce match n\'existe pas.';
            } elseif (strtotime($match['dt']) < strtotime('now') && $_SESSION['login'] != 'admin') {
                echo 'Ce match est passé.';
            } else {
                $req = $bdd->prepare("SELECT id_pari FROM paris_match WHERE id_user=:usr AND id_match=:play");
                $req->execute(array('usr' => $id_perso, 'play' => $id_play));
                $pari = $req->fetch();
                if (isset($_POST['score1']) && isset($_POST['score2']) && ctype_digit($_POST['score1']) && ctype_digit(($_POST['score2']))) {
                    if (strlen($_POST['score1']) <= 2 && strlen($_POST['score2']) <= 2) {

                        $winner = 3;

                        if ($_POST['score1'] > $_POST['score2']) {
                            $winner = 1;
                        } elseif ($_POST['score1'] < $_POST['score2']) {
                            $winner = 2;
                        } elseif ($id_play > 48) {
                            if ($_POST['tab'] == 'e1') {
                                $winner = 1;
                            } else {
                                $winner = 2;
                            }
                        }
                        if (!$pari) {
                            $req = $bdd->prepare("INSERT INTO paris_match(id_user, id_match, score1, score2, winner) VALUES(:usr, :play, :s1, :s2, :gagnant)");
                            $req->execute(array('usr' => $id_perso, 'play' => $id_play, 's1' => $_POST['score1'], 's2' => $_POST['score2'], 'gagnant' => $winner));
                        } else {
                            $req = $bdd->prepare("UPDATE paris_match SET score1=:s1, score2=:s2, winner=:gagnant WHERE id_pari=:id");
                            $req->execute(array('id' => $pari['id_pari'], 's1' => $_POST['score1'], 's2' => $_POST['score2'], 'gagnant' => $winner));
                        }
                        header('Location: predictions.php#' . $id_play);
                        exit();
                    } else {
                        $msg = 'Un problème a été détecté dans les valeurs proposées. Seules les valeurs inférieures ou égales à 99 sont acceptées.';
                    }
                } elseif (isset($_POST['score1']) && isset($_POST['score2']) && $_POST['score1'] == '' && $_POST['score2'] == '' && $pari) {
                    $req = $bdd->prepare("DELETE FROM paris_match WHERE id_pari=:id");
                    $req->execute(array('id' => $pari['id_pari']));
                    header('Location: predictions.php#' . $id_play);
                    exit();
                } elseif (isset($_POST['score1']) && isset($_POST['score2'])) {
                    $msg = 'Un problème a été détecté dans les valeurs proposées. Seuls les valeurs inférieures à 99 sont acceptées.';
                }
                $pari = $bdd->prepare("SELECT score1, score2, winner FROM paris_match JOIN users ON users.id = paris_match.id_user WHERE id_match=:play AND users.login=:usr");
                $pari->execute(array('play' => $id_play, 'usr' => $_SESSION['login']));
                $res = $pari->fetch();
                if (!$res) {
                    $s1 = '';
                    $s2 = '';
                    $winner = 1;
                } else {
                    $s1 = $res['score1'];
                    $s2 = $res['score2'];
                    $winner = $res['winner'];
                }

                $grp = $match['groupe'];
                if (strlen($grp) == 1) {
                    $entete = 'GROUPE ' . $grp;
                } else {
                    $n = (int)$grp[1];
                    if ($n <= 4) {
                        $nb = (string)(2*$n-1);
                    } else {
                        $nb = (string)(2*($n-4));
                    }
                    if ($grp[0] == 'H') {
                        $entete = 'HUITIÈME DE FINALE N°' . $nb;
                    } elseif ($grp[0] == 'Q') {
                        $entete = 'QUART DE FINALE N°' . $nb;
                    } elseif ($grp[0] == 'D') {
                        $entete = 'DEMI-FINALE N°' . $nb;
                    } elseif ($grp == 'F0') {
                        $entete = 'FINALE';
                    } elseif ($grp == 'F1') {
                        $entete = 'PETITE FINALE';
                    }
                }
                ?>
                <font style="font-size: 20px;"><?php echo $entete . ' ⋅ ' . $match['date'];?><br/><br/></font>
                <font style="font-size: 35px;"><?php echo $match['e1'] . ' — ' . $match['e2'];?><br/></font>
                <form method="post" action=<?php echo '"match.php?id=' . $id_play . '"';?>>
                    <input type="text" name="score1" size="2" maxlength="2" value=<?php echo '"' . $s1 . '"';?>/> <input type="text" name="score2" size="2" maxlength="2" value=<?php echo '"' . $s2 . '"';?>/><br/>
                    <?php
                    if (strlen($grp) > 1) {?>
                        <font style="font-size: 15px;"><?php echo 'Vainqueur des t.a.b. si égalité :';?></font><br/>
                        <input type="radio" name="tab" value="e1" <?php echo ($winner == 1 ? 'checked': ''); ?>><input type="radio" name="tab" value="e2" <?php echo ($winner == 2 ? 'checked': ''); ?>><br/><br/>
                        <?php
                    }
                    ?>
                    <input type="submit" value="Telle est mon opinion"/>
                </form><br/>
                <font style="font-size: 20px;"><?php echo $msg;?></font>
            <?php
            }
        }
        ?>
    </div>
</body>
</html>
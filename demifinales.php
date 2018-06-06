<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Résultats des demi-finales | Pronostics coupe du monde 2018</title>
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
        ?>
        <font style="font-size: 30px;"><b><i>« Miss Granger, trois tours devraient suffire. Bonne chance. »</i></b><br/><br/></font>
    </div>
    <?php
    $req = $bdd->prepare("SELECT id FROM users WHERE login=:pseudo");
    $req->execute(array('pseudo' => $_SESSION['login']));
    $id_perso = $req->fetch()['id'];
    ?>
    <table width="100%" align="center">
        <tr>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><a href="predictions.php">Matchs individuels</a></font><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><b>Toute la compétition</b></font><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><a href="divers.php">Paris divers</a></font><br/>
            </td>
        </tr>
    </table>
    <table width="90%" align="center">
        <form action="demifinales.php" method="post">
            <tr>
            <?php
            $winners = [[0, 0], [0, 0]];
            for ($i = 0; $i < 2; $i+=1) {

                $fin2_1q = $bdd->prepare("SELECT id_e1, eq1.pays AS e1 FROM paris_0 JOIN teams eq1 ON paris_0.id_e1 = eq1.id WHERE id_user=:usr AND grp=:groupe");
                $fin2_2q = $bdd->prepare("SELECT id_e1, eq1.pays AS e1 FROM paris_0 JOIN teams eq1 ON paris_0.id_e1 = eq1.id WHERE id_user=:usr AND grp=:groupe");

                $fin2_1q->execute(array('usr' => $id_perso, 'groupe' => 'Q' . (string)(2*$i+1)));
                $fin2_1 = $fin2_1q->fetch();
                $fin2_2q->execute(array('usr' => $id_perso, 'groupe' => 'Q' . (string)(2*$i+2)));
                $fin2_2 = $fin2_2q->fetch();

                if (!$fin2_1) {
                    $j1 = '0';
                } else {
                    $j1 = $fin2_1['id_e1'];
                    $e_j1 = $fin2_1['e1'];
                }
                if (!$fin2_2) {
                    $j2 = '0';
                } else {
                    $j2 = $fin2_2['id_e1'];
                    $e_j2 = $fin2_2['e1'];
                }

                $msg = '';

                $prono = $bdd->prepare("SELECT id_pari, id_e1, id_e2 FROM paris_0 WHERE id_user=:id AND grp=:grp");
                $prono->execute(array('id' => $id_perso, 'grp' => 'D' . (string)($i+1)));

                $data = $prono->fetch();
                $winners[$i][0] = $data['id_e1'];
                $winners[$i][1] = $data['id_e2'];

                if (($_SESSION['login'] == 'admin' || strtotime('2018-06-14 15:00:00') > strtotime('now')) && isset($_POST[(string)($i+1)]) && $j1 != '0' && $j2 != '0') {
                    if ($_POST[(string)($i+1)] == $j1 || $_POST[(string)($i+1)] == $j2) {
                        $winners[$i][0] = $_POST[(string)($i+1)];
                        if (!$data) {
                            $inser = $bdd->prepare("INSERT INTO paris_0 (id_user, grp, id_e1, id_e2) VALUES (:id, :grp, :eq1, :eq2);");
                            if ($_POST[(string)($i+1)] == $j1) {
                                $inser->execute(array('id' => $id_perso, 'grp' => 'D' . (string)($i+1), 'eq1' => $_POST[(string)($i+1)], 'eq2' => $j2));
                                $winners[$i][1] = $j2;
                            } else {
                                $inser->execute(array('id' => $id_perso, 'grp' => 'D' . (string)($i+1), 'eq1' => $_POST[(string)($i+1)], 'eq2' => $j1));
                                $winners[$i][1] = $j1;
                            }
                            $msg = 'Votre choix a été pris en compte.';
                        } elseif ($data['id_e1'] != $_POST[(string)($i+1)]) {
                            $maj = $bdd->prepare("UPDATE paris_0 SET id_e1 = :eq1 WHERE id_pari=:id;");
                            $maj->execute(array('eq1' => $_POST[(string)($i+1)], 'id' => $data['id_pari']));

                            $maj = $bdd->prepare("UPDATE paris_0 SET id_e2 = :eq2 WHERE id_pari=:id;");
                            if ($_POST[(string)($i+1)] == $j1) {
                                $maj->execute(array('eq2' => $j2, 'id' => $data['id_pari']));
                                $winners[$i][1] = $j2;
                            } else {
                                $maj->execute(array('eq2' => $j1, 'id' => $data['id_pari']));
                                $winners[$i][1] = $j1;
                            }
                            $msg = 'Votre choix a été pris en compte.';
                        }
                    } elseif ($_POST[(string)($i+1)] == '0' && $data) {
                        $req = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                        $req->execute(array('id' => $data['id_pari']));
                        $msg = 'Votre pronostic a été supprimé';
                        $winners[$i] = '0';
                    }
                }

                ?>
                <td width="20%" align="center">
                    <font style="font-size: 20px;">Demi-finale n°<?php echo ($i+1);?></font><br/>
                    <font style="font-size: 15px;">Vainqueur</font>
                    <select name=<?php echo '"' . ($i+1) . '" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-18 15:00:00') < strtotime('now') || $j1 == '0' || $j2 == '0' ? 'disabled': '');?>>
                        <option value="0">--</option>
                        <?php
                        if ($j1 != '0') {
                            echo '<option value="' . $j1 . '"' . ($winners[$i][0] == $j1 ? ' selected': '') . '>' . $e_j1 . '</option>';
                        }
                        if ($j2 != '0') {
                            echo '<option value="' . $j2 . '"' . ($winners[$i][0] == $j2 ? ' selected': '') . '>' . $e_j2 . '</option>';
                        }
                        ?>
                    </select><br/>
                    <font style="font-size: 10px;"><?php echo $msg;?></font>
                </td>
                <?php
            }?>
            </tr>
            <tr>
                <td width="10%" align="center">
                    <br/>
                    <input type="submit" value="Valider"/><br/><br/><br/>
                </td><br/>
            </tr>
        </form>
    </table>

    <?php

    /* Finale */
    $win = $bdd->prepare("SELECT id_pari, id_e1, id_e2 FROM paris_0 WHERE grp=:groupe AND id_user=:usr");
    $win->execute(array('groupe' => 'F0', 'usr' => $id_perso));
    $pari = $win->fetch();

    if ($pari) {
        $ok1 = false;
        $ok2 = false;
        for ($j = 0; $j < 2; $j++) {
            if ($winners[$j][0] == $pari['id_e1']) {
                $ok1 = true;
            } elseif ($winners[$j][0] == $pari['id_e2']) {
                $ok2 = true;
            }
        }

        if (!$ok1 || !$ok2) {
            $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
            $correc->execute(array('id' => $pari['id_pari']));
        }
    }

    /* Petite finale */
    $win = $bdd->prepare("SELECT id_pari, id_e1 FROM paris_0 WHERE grp=:groupe AND id_user=:usr");
    $win->execute(array('groupe' => 'F1', 'usr' => $id_perso));
    $pari = $win->fetch();

    if ($pari) {
        $ok = false;
        for ($j = 0; $j < 2; $j++) {
            if ($winners[$j][1] == $pari['id_e1']) {
                $ok = true;
                break;
            }
        }

        if (!$ok) {
            $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
            $correc->execute(array('id' => $pari['id_pari']));
        }
    }

    ?>

    <table width="90%" align="center">
        <tr>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><a href="groupes.php">La phase de groupes</a></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><a href="huitiemes.php">Les huitièmes de finale</b></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><a href="quarts.php">Les quarts de finale</a></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><b>Les demi-finales</b></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><a href="petitefinale.php">La petite finale</b></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><a href="finale.php">La finale</a></font><br/>
            </td>
</body>
</html>
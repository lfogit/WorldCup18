<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Résultats des huitièmes de finale | Pronostics coupe du monde 2018</title>
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
    $grp = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');
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
        <form action="huitiemes.php" method="post">
            <?php
            $winners = [0, 0, 0, 0, 0, 0, 0, 0];
            for ($i = 0; $i < 4; $i+=1) {
                if ($i == 0 || $i == 2) {
                    echo '<tr>';
                }
                $fin8_1q = $bdd->prepare("SELECT id_e1, eq1.pays AS e1, id_e2, eq2.pays AS e2 FROM paris_0 JOIN teams eq1 ON paris_0.id_e1 = eq1.id JOIN teams eq2 ON paris_0.id_e2 = eq2.id WHERE id_user=:usr AND grp=:groupe");
                $fin8_2q = $bdd->prepare("SELECT id_e1, eq1.pays AS e1, id_e2, eq2.pays AS e2 FROM paris_0 JOIN teams eq1 ON paris_0.id_e1 = eq1.id JOIN teams eq2 ON paris_0.id_e2 = eq2.id WHERE id_user=:usr AND grp=:groupe");

                $fin8_1q->execute(array('usr' => $id_perso, 'groupe' => $grp[2*$i]));
                $fin8_1 = $fin8_1q->fetch();
                $fin8_2q->execute(array('usr' => $id_perso, 'groupe' => $grp[2*$i+1]));
                $fin8_2 = $fin8_2q->fetch();

                if (!$fin8_1) {
                    $j11 = '0';
                    $j12 = '0';
                } else {
                    $j11 = $fin8_1['id_e1'];
                    $e_j11 = $fin8_1['e1'];
                    $j12 = $fin8_1['id_e2'];
                    $e_j12 = $fin8_1['e2'];
                }
                if (!$fin8_2) {
                    $j21 = '0';
                    $j22 = '0';
                } else {
                    $j21 = $fin8_2['id_e1'];
                    $e_j21 = $fin8_2['e1'];
                    $j22 = $fin8_2['id_e2'];
                    $e_j22 = $fin8_2['e2'];
                }

                $msg1 = '';
                $msg2 = '';

                $prono1 = $bdd->prepare("SELECT id_pari, id_e1 FROM paris_0 WHERE id_user=:id AND grp=:grp");
                $prono1->execute(array('id' => $id_perso, 'grp' => 'H' . (string)($i+1)));

                $data = $prono1->fetch();
                $winners[$i] = $data['id_e1'];

                if (($_SESSION['login'] == 'admin' || strtotime('2018-06-14 15:00:00') > strtotime('now')) && isset($_POST[(string)($i+1)]) && $j11 != '0' && $j22 != '0') {
                    if ($_POST[(string)($i+1)] == $j11 || $_POST[(string)($i+1)] == $j22) {
                        if (!$data) {
                            $inser = $bdd->prepare("INSERT INTO paris_0 (id_user, grp, id_e1) VALUES (:id, :grp, :id_eq);");
                            $inser->execute(array('id' => $id_perso, 'grp' => 'H' . (string)($i+1), 'id_eq' => $_POST[(string)($i+1)]));
                            $msg1 = 'Votre choix a été pris en compte.';
                        } elseif ($data['id_e1'] != $_POST[(string)($i+1)]) {
                            $maj = $bdd->prepare("UPDATE paris_0 SET id_e1 = :id_eq WHERE id_pari=:id;");
                            $maj->execute(array('id_eq' => $_POST[(string)($i+1)], 'id' => $data['id_pari']));
                            $msg1 = 'Votre choix a été pris en compte.';
                        }
                        $winners[$i] = $_POST[(string)($i+1)];
                    } elseif ($_POST[(string)($i+1)] == '0' && $data) {
                        $req = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                        $req->execute(array('id' => $data['id_pari']));
                        $msg1 = 'Votre pronostic a été supprimé';
                        $winners[$i] = '0';
                    }
                }

                $prono2 = $bdd->prepare("SELECT id_pari, id_e1 FROM paris_0 WHERE id_user=:id AND grp=:grp");
                $prono2->execute(array('id' => $id_perso, 'grp' => 'H' . (string)($i+5)));

                $data = $prono2->fetch();
                $winners[$i+4] = $data['id_e1'];

                if (($_SESSION['login'] == 'admin' || strtotime('2018-06-14 15:00:00') > strtotime('now')) && isset($_POST[(string)($i+5)]) && $j12 != '0' && $j21 != '0') {
                    if ($_POST[(string)($i+5)] == $j12 || $_POST[(string)($i+5)] == $j21) {
                        if (!$data) {
                            $inser = $bdd->prepare("INSERT INTO paris_0 (id_user, grp, id_e1) VALUES (:id, :grp, :id_eq);");
                            $inser->execute(array('id' => $id_perso, 'grp' => 'H' . (string)($i+5), 'id_eq' => $_POST[(string)($i+5)]));
                            $msg2 = 'Votre choix a été pris en compte.';
                        } elseif ($data['id_e1'] != $_POST[(string)($i+5)]) {
                            $maj = $bdd->prepare("UPDATE paris_0 SET id_e1 = :id_eq WHERE id_pari=:id;");
                            $maj->execute(array('id_eq' => $_POST[(string)($i+5)], 'id' => $data['id_pari']));
                            $msg2 = 'Votre choix a été pris en compte.';
                        }
                        $winners[$i+4] = $_POST[(string)($i+5)];
                    } elseif ($_POST[(string)($i+5)] == '0' && $data) {
                        $req = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                        $req->execute(array('id' => $data['id_pari']));
                        $msg2 = 'Votre pronostic a été supprimé';
                        $winners[$i+4] = '0';
                    } else {
                        $msg2 = 'Un problème a été rencontré.';
                    }
                }

                ?>
                <td width="20%" align="center">
                    <font style="font-size: 20px;">Huitième de finale n°<?php echo (2*$i+1);?></font><br/>
                    <font style="font-size: 15px;">Vainqueur</font>
                    <select name=<?php echo '"' . ($i+1) . '" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-18 15:00:00') < strtotime('now') || $j11 == '0' || $j22 == '0' ? 'disabled': '');?>>
                        <option value="0">--</option>
                        <?php
                        if ($j11 != '0') {
                            echo '<option value="' . $j11 . '"' . ($winners[$i] == $j11 ? ' selected': '') . '>' . $e_j11 . '</option>';
                        }
                        if ($j22 != '0') {
                            echo '<option value="' . $j22 . '"' . ($winners[$i] == $j22 ? ' selected': '') . '>' . $e_j22 . '</option>';
                        }
                        ?>
                    </select><br/>
                    <font style="font-size: 10px;"><?php echo $msg1;?></font>
                </td>
                <td width="20%" align="center">
                    <font style="font-size: 20px;">Huitième de finale n°<?php echo (2*$i+2);?></font><br/>
                    <font style="font-size: 15px;">Vainqueur</font>
                    <select name=<?php echo '"' . ($i+5) . '" ' . ($_SESSION['login'] !='admin' && strtotime('2018-06-18 15:00:00') < strtotime('now') || $j12 == '0' || $j21 == '0' ? 'disabled': '');?>>
                        <option value="0">--</option>
                        <?php
                        if ($j21 != '0') {
                            echo '<option value="' . $j21 . '"' . ($winners[$i+4] == $j21 ? ' selected': '') . '>' . $e_j21 . '</option>';
                        }
                        if ($j12 != '0') {
                            echo '<option value="' . $j12 . '"' . ($winners[$i+4] == $j12 ? ' selected': '') . '>' . $e_j12 . '</option>';
                        }
                        ?>
                    </select><br/>
                    <font style="font-size: 10px;"><?php echo $msg2;?></font>
                </td>
                <?php
                if ($i == 1 || $i == 3) {
                    echo '</tr>';
                }
            }?>
            <tr>
                <td width="10%" align="center">
                    <br/>
                    <input type="submit" value="Valider"/><br/><br/><br/>
                </td><br/>
            </tr>
        </form>
    </table>

    <?php
    /* Quarts de finales */
    for ($i = 0; $i < 4; $i++) {
        $win = $bdd->prepare("SELECT id_pari, id_e1 FROM paris_0 WHERE grp=:groupe AND id_user=:usr");
        $win->execute(array('groupe' => 'Q' . (string)($i+1), 'usr' => $id_perso));
        $pari = $win->fetch();

        if ($pari) {
            if ($winners[2*$i] != $pari['id_e1'] && $winners[2*$i+1] != $pari['id_e1']) {
                $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                $correc->execute(array('id' => $pari['id_pari']));
            }
        }
    }

    /* Demi-finales */
    for ($i = 0; $i < 2; $i++) {
        $win = $bdd->prepare("SELECT id_pari, id_e1, id_e2 FROM paris_0 WHERE grp=:groupe AND id_user=:usr");
        $win->execute(array('groupe' => 'D' . (string)($i+1), 'usr' => $id_perso));
        $pari = $win->fetch();

        if ($pari) {
            if (!in_array($pari['id_e1'], [$winners[4*$i], $winners[4*$i+1], $winners[4*$i+2], $winners[4*$i+3]])) {
                $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                $correc->execute(array('id' => $pari['id_pari']));
            } elseif (!in_array($pari['id_e2'], [$winners[4*$i], $winners[4*$i+1], $winners[4*$i+2], $winners[4*$i+3]])) {
                $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                $correc->execute(array('id' => $pari['id_pari']));
            }
        }
    }

    /* Finale */
    $win = $bdd->prepare("SELECT id_pari, id_e1, id_e2 FROM paris_0 WHERE grp=:groupe AND id_user=:usr");
    $win->execute(array('groupe' => 'F0', 'usr' => $id_perso));
    $pari = $win->fetch();

    if ($pari) {
        $ok1 = false;
        $ok2 = false;
        for ($j = 0; $j < 8; $j++) {
            if ($winners[$j] == $pari['id_e1']) {
                $ok1 = true;
            } elseif ($winners[$j] == $pari['id_e2']) {
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
        for ($j = 0; $j < 8; $j++) {
            if ($winners[$j] == $pari['id_e1']) {
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
                <font style="font-size: 15px;"><b>Les huitièmes de finale</b></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><a href="quarts.php">Les quarts de finale</a></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><a href="demifinales.php">Les demi-finales</a></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><a href="petitefinale.php">La petite finale</b></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><a href="finale.php">La finale</a></font><br/>
            </td>
</body>
</html>
<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Résultats de la phase de groupes | Pronostics coupe du monde 2018</title>
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
        <form action="groupes.php" method="post">
        <?php
        $winners = [[0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0]];
        $pays = $bdd->query("SELECT pays FROM teams WHERE id>=1 AND id<=32 ORDER BY groupe, id");
        for ($i = 0; $i < count($grp); $i++) {
            $msg = '';
            $eq = array();
            for ($j = 0; $j < 4; $j++) {
                $eq[] = $pays->fetch()['pays'];
            }
            if ($i == 0 || $i == 4) {
                echo '<tr>';
            }
            $bet = $bdd->prepare("SELECT id_pari, id_e1, id_e2 FROM paris_0 WHERE id_user=:usr AND grp=:groupe");
            $bet->execute(array('usr' => $id_perso, 'groupe' => $grp[$i]));
            $data = $bet->fetch();
            if ($data) {
                $winners[$i][0] = $data['id_e1'];
                $winners[$i][1] = $data['id_e2'];
            }
            if (($_SESSION['login'] == 'admin' || strtotime('2018-06-14 15:00:00') > strtotime('now')) && isset($_POST[$grp[$i] . '1']) && isset($_POST[$grp[$i] . '2'])) {
                $e1 = $_POST[$grp[$i] . '1'];
                $e2 = $_POST[$grp[$i] . '2'];
                if ($e1 == $e2 && $e1 != '0') {
                    $msg = 'Attention ! Les deux équipes doivent être différentes.';
                } elseif ($e1 == '0' && $e2 == '0' && $data) {
                    $req = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                    $req->execute(array('id' => $data['id_pari']));
                    $msg = 'Votre pronostic a été supprimé';
                    $winners[$i][0] = '0';
                    $winners[$i][1] = '0';
                } else {
                    $arr = array();
                    for ($j = 0; $j < 4; $j++) {
                        $arr[] = (string)($i * 4 + $j + 1);
                    }
                    $arr[] = '0';
                    if (in_array($e1, $arr) && in_array($e2, $arr)) {
                        if (!$data) {
                            $req = $bdd->prepare("INSERT INTO paris_0(id_user, grp, id_e1, id_e2) VALUES(:usr, :groupe, :eq1, :eq2)");
                            $req->execute(array('eq1' => $e1, 'eq2' => $e2, 'usr' => $id_perso, 'groupe' => $grp[$i]));
                            $msg = 'Votre choix a été pris en compte.';
                        } elseif ($e1 != $data['id_e1'] || $e2 != $data['id_e2']) {
                            $req = $bdd->prepare("UPDATE paris_0 SET id_e1=:eq1, id_e2=:eq2 WHERE id_user=:usr AND grp=:groupe");
                            $req->execute(array('eq1' => $e1, 'eq2' => $e2, 'usr' => $id_perso, 'groupe' => $grp[$i]));
                            $msg = 'Votre choix a été pris en compte.';
                        }
                        $winners[$i][0] = $e1;
                        $winners[$i][1] = $e2;
                    }
                }
            }
            ?>
            <td width="20%" align="center">
                <font style="font-size: 20px;">Groupe <?php echo ' ' . $grp[$i];?></font><br/>
                <font style="font-size: 15px;">1<sup>er</sup> du groupe</font>
                <select name=<?php echo '"' . $grp[$i] . '1" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-18 15:00:00') < strtotime('now') ? 'disabled': '');?>>
                    <option value="0">--</option>
                    <?php
                    for ($j = 0; $j < 4; $j++) {
                        if ((string)($i * 4 +$j + 1) == $winners[$i][0]) {
                            echo '<option value="' . (string)($i * 4 + $j + 1) . '" selected>' . $eq[$j] . '</option>';
                        } else {
                            echo '<option value="' . (string)($i * 4 + $j + 1) . '">' . $eq[$j] . '</option>';
                        }
                    }?>
                </select><br/>
                <font style="font-size: 15px;">2<sup>e</sup> du groupe</font>
                <select name=<?php echo '"' . $grp[$i] . '2" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-18 15:00:00') < strtotime('now') ? 'disabled': '');?>>
                    <option value="0">--</option>
                    <?php
                    for ($j = 0; $j < 4; $j++) {
                        if ((string)($i * 4 +$j + 1) == $winners[$i][1]) {
                            echo '<option value="' . (string)($i * 4 + $j + 1) . '" selected>' . $eq[$j] . '</option>';
                        } else {
                            echo '<option value="' . (string)($i * 4 + $j + 1) . '">' . $eq[$j] . '</option>';
                        }
                    }?>
                </select><br/>
                <font style="font-size: 10px;"><?php echo $msg;?></font>
            </td>
            <?php
            if ($i == 3 || $i == 7) {
                echo '</tr>';
            }
        }
        ?>
        <tr>
            <td width="10%" align="center">
                <br/>
                <input type="submit" value="Valider"/><br/><br/><br/>
            </td><br/>
        </tr>
    </form>
    </table>

    <?php

    /* Huitièmes de finales */
    for ($i = 0; $i < 8; $i++) {
        $win8 = $bdd->prepare("SELECT id_pari, id_e1 FROM paris_0 WHERE grp=:groupe AND id_user=:usr");
        $win8->execute(array('groupe' => 'H' . (string)($i+1), 'usr' => $id_perso));
        $pari = $win8->fetch();

        if ($pari) {
            if ($i < 4) {
                if ($winners[2*$i][0] != $pari['id_e1'] && $winners[2*$i+1][1] != $pari['id_e1']) {
                    $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                    $correc->execute(array('id' => $pari['id_pari']));
                }
            } else {
                if ($winners[2*($i-4)][1] != $pari['id_e1'] && $winners[2*($i-4)+1][0] != $pari['id_e1']) {
                    $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                    $correc->execute(array('id' => $pari['id_pari']));
                }
            }
        }
    }

    /* Quarts de finales */
    for ($i = 0; $i < 4; $i++) {
        $win = $bdd->prepare("SELECT id_pari, id_e1 FROM paris_0 WHERE grp=:groupe AND id_user=:usr");
        $win->execute(array('groupe' => 'Q' . (string)($i+1), 'usr' => $id_perso));
        $pari = $win->fetch();

        if ($pari) {
            if ($i < 2) {
                if ($winners[4*$i][0] != $pari['id_e1'] && $winners[4*$i+1][1] != $pari['id_e1'] &&
                    $winners[4*$i+2][0] != $pari['id_e1'] && $winners[4*$i+3][1] != $pari['id_e1']) {
                    $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                    $correc->execute(array('id' => $pari['id_pari']));
                }
            } else {
                if ($winners[4*($i-2)][1] != $pari['id_e1'] && $winners[4*($i-2)+1][0] != $pari['id_e1'] &&
                    $winners[4*($i-2)+2][1] != $pari['id_e1'] && $winners[4*($i-2)+3][0] != $pari['id_e1']) {
                    $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                    $correc->execute(array('id' => $pari['id_pari']));
                }
            }
        }
    }

    /* Demi-finales */
    for ($i = 0; $i < 2; $i++) {
        $win = $bdd->prepare("SELECT id_pari, id_e1, id_e2 FROM paris_0 WHERE grp=:groupe AND id_user=:usr");
        $win->execute(array('groupe' => 'D' . (string)($i+1), 'usr' => $id_perso));
        $pari = $win->fetch();

        if ($pari) {
            if ($i == 0) {
                if (!in_array($pari['id_e1'], [$winners[0][0], $winners[2][0], $winners[4][0], $winners[6][0], $winners[1][1], $winners[3][1], $winners[5][1], $winners[7][1]])) {
                    $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                    $correc->execute(array('id' => $pari['id_pari']));
                } elseif (!in_array($pari['id_e2'], [$winners[0][0], $winners[2][0], $winners[4][0], $winners[6][0], $winners[1][1], $winners[3][1], $winners[5][1], $winners[7][1]])) {
                    $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                    $correc->execute(array('id' => $pari['id_pari']));
                }
            } else {
                if (!in_array($pari['id_e1'], [$winners[0][1], $winners[2][1], $winners[4][1], $winners[6][1], $winners[1][0], $winners[3][0], $winners[5][0], $winners[7][0]])) {
                    $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                    $correc->execute(array('id' => $pari['id_pari']));
                } elseif (!in_array($pari['id_e2'], [$winners[0][1], $winners[2][1], $winners[4][1], $winners[6][1], $winners[1][0], $winners[3][0], $winners[5][0], $winners[7][0]])) {
                    $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                    $correc->execute(array('id' => $pari['id_pari']));
                }
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
            if ($winners[$j][0] == $pari['id_e1'] || $winners[$j][1] == $pari['id_e1']) {
                $ok1 = true;
            } elseif ($winners[$j][0] == $pari['id_e2'] || $winners[$j][1] == $pari['id_e2']) {
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
            if ($winners[$j][0] == $pari['id_e1'] || $winners[$j][1] == $pari['id_e1']) {
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
                <font style="font-size: 15px;"><b>La phase de groupes</b></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><a href="huitiemes.php">Les huitièmes de finale</a></font><br/>
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
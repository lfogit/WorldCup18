<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Résultat de la finale | Pronostics coupe du monde 2018</title>
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
        <form action="finale.php" method="post">
            <tr>
            <?php
            $fin_1q = $bdd->prepare("SELECT id_e1, eq1.pays AS e1 FROM paris_0 JOIN teams eq1 ON paris_0.id_e1 = eq1.id WHERE id_user=:usr AND grp='D1'");
            $fin_2q = $bdd->prepare("SELECT id_e1, eq1.pays AS e1 FROM paris_0 JOIN teams eq1 ON paris_0.id_e1 = eq1.id WHERE id_user=:usr AND grp='D2'");

            $fin_1q->execute(array('usr' => $id_perso));
            $fin_2q->execute(array('usr' => $id_perso));

            $fin_1 = $fin_1q->fetch();
            $fin_2 = $fin_2q->fetch();

            if (!$fin_1) {
                $j1 = '0';
            } else {
                $j1 = $fin_1['id_e1'];
                $e_j1 = $fin_1['e1'];
            }
            if (!$fin_2) {
                $j2 = '0';
            } else {
                $j2 = $fin_2['id_e1'];
                $e_j2 = $fin_2['e1'];
            }

            $slc = '';
            $msg = '';

            $prono = $bdd->prepare("SELECT id_pari, id_e1 FROM paris_0 WHERE id_user=:id AND grp=:grp");
            $prono->execute(array('id' => $id_perso, 'grp' => 'F0'));

            $data = $prono->fetch();
            $slc = $data['id_e1'];

            if (($_SESSION['login'] == 'admin' || strtotime('2018-06-14 15:00:00') > strtotime('now')) && isset($_POST['1']) && $j1 != '0' && $j2 != '0') {
                if ($_POST['1'] == $j1 || $_POST['1'] == $j2) {
                    $slc = $_POST['1'];
                    if (!$data) {
                        if ($j1 == $_POST['1']) {
                            $inser = $bdd->prepare("INSERT INTO paris_0 (id_user, grp, id_e1, id_e2) VALUES (:id, :grp, :eq1, :eq2);");
                            $inser->execute(array('id' => $id_perso, 'grp' => 'F0', 'eq1' => $j1, 'eq2' => $j2));
                        } else {
                            $inser = $bdd->prepare("INSERT INTO paris_0 (id_user, grp, id_e1, id_e2) VALUES (:id, :grp, :eq1, :eq2);");
                            $inser->execute(array('id' => $id_perso, 'grp' => 'F0', 'eq1' => $j2, 'eq2' => $j1));
                        }
                        $msg = 'Votre choix a été pris en compte.';
                    } elseif ($data['id_e1'] != $_POST['1']) {
                        if ($j1 == $_POST['1']) {
                            $maj = $bdd->prepare("UPDATE paris_0 SET id_e1 = :eq1, id_e2 = :eq2 WHERE id_pari=:id;");
                            $maj->execute(array('eq1' => $j1, 'eq2' => $j2, 'id' => $data['id_pari']));
                        } else {
                            $maj = $bdd->prepare("UPDATE paris_0 SET id_e1 = :eq1, id_e2 = :eq2 WHERE id_pari=:id;");
                            $maj->execute(array('eq1' => $j2, 'eq2' => $j1, 'id' => $data['id_pari']));
                        }
                        $msg = 'Votre choix a été pris en compte.';
                    }
                } elseif ($data && $_POST['1'] == '0') {
                    $req = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                    $req->execute(array('id' => $data['id_pari']));
                    $msg = 'Votre pronostic a été supprimé';
                    $slc = '0';
                }
            }

            ?>
            <td width="20%" align="center">
                <font style="font-size: 20px;">Finale</font><br/>
                <font style="font-size: 15px;">Vainqueur</font>
                <select name=<?php echo '"1" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-18 15:00:00') < strtotime('now') || $j1 == '0' || $j2 == '0' ? 'disabled': '');?>>
                    <option value="0">--</option>
                    <?php
                    if ($j1 != '0') {
                        echo '<option value="' . $j1 . '"' . ($slc == $j1 ? ' selected': '') . '>' . $e_j1 . '</option>';
                    }
                    if ($j2 != '0') {
                        echo '<option value="' . $j2 . '"' . ($slc == $j2 ? ' selected': '') . '>' . $e_j2 . '</option>';
                    }
                    ?>
                </select><br/>
                <font style="font-size: 10px;"><?php echo $msg;?></font>
            </td>
            </tr>
            <tr>
                <td width="10%" align="center">
                    <br/>
                    <input type="submit" value="Valider"/><br/><br/><br/>
                </td><br/>
            </tr>
        </form>
    </table>
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
                <font style="font-size: 15px;"><a href="demifinales.php">Les demi-finales</a></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><a href="petitefinale.php">La petite finale</b></font><br/>
            </td>
            <td width="15%" align="center">
                <font style="font-size: 15px;"><b>La finale</b></font><br/>
            </td>
</body>
</html>
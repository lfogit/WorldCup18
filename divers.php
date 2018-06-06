<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Paris divers | Pronostics coupe du monde 2018</title>
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

        $req = $bdd->prepare("SELECT id FROM users WHERE login=:pseudo");
        $req->execute(array('pseudo' => $_SESSION['login']));
        $id_perso = $req->fetch()['id'];

        $vals = ['', '', '', ''];
        $items = ['a', 'b', 'c', 'd'];
        $msg = [' ', ' ', ' ', ' '];

        for ($i = 0; $i < 4; $i++) {
            $req = $bdd->prepare("SELECT id_pari, val FROM paris_divers WHERE id_user=:usr AND id_obj=:item");
            $req->execute(array('usr' => $id_perso, 'item' => $items[$i]));
            $pari = $req->fetch();

            if ($pari) {
                $vals[$i] = $pari['val'];
            }

            if (($_SESSION['login'] == 'admin' || strtotime('2018-06-18 15:00:00') > strtotime('now'))) {
                if (isset($_POST[$items[$i]]) && ctype_digit($_POST[$items[$i]]) && strlen($_POST[$items[$i]]) <= 5) {
                    if ($pari && $vals[$i] != $_POST[$items[$i]]) {
                        $req = $bdd->prepare("UPDATE paris_divers SET val=:value WHERE id_pari=:id");
                        $req->execute(array('value' => $_POST[$items[$i]], 'id' => $pari['id_pari']));
                        $msg[$i] = 'Votre choix a été pris en compte.';
                    } elseif (!$pari) {
                        $req = $bdd->prepare("INSERT INTO paris_divers(id_user, id_obj, val) VALUES(:usr, :item, :value)");
                        $req->execute(array('usr' => $id_perso, 'item' => $items[$i], 'value' => $_POST[$items[$i]]));
                        $msg[$i] = 'Votre choix a été pris en compte.';
                    }
                    $vals[$i] = $_POST[$items[$i]];
                } elseif (isset($_POST[$items[$i]]) && $_POST[$items[$i]] == '' && $pari) {
                    $req = $bdd->prepare("DELETE FROM paris_divers WHERE id_pari=:id");
                    $req->execute(array('id' => $pari['id_pari']));
                    $msg[$i] = 'Votre pronostic a été supprimé';
                    $vals[$i] = '';
                } elseif (isset($_POST[$items[$i]])) {
                    $msg[$i] = 'Un problème a été détecté, seules les valeurs inférieures ou égales à 99999 sont acceptées.';
                }
            }
        }
        ?>
        <font style="font-size: 30px;"><b><i>« Sarah Connor ? »</i></b><br/><br/></font>
    </div>
    <table width="100%" align="center">
        <tr>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><a href="predictions.php">Matchs individuels</a></font><br/><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><a href="groupes.php">Toute la compétition</a></font><br/><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><b>Paris divers</b></font><br/><br/>
            </td>
        </tr>
    </table>
    <table width="100%" align="left">
        <tr>
            <form method="post" action="divers.php">
                <td width="50%" align="right">
                    <br/>
                    <font style="font-size: 20px;">Nombre de buts marqués pendant la compétition</font>
                    <input type="text" name="a" size="5" value=<?php echo '"' . $vals[0] . '" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-18 15:00:00') < strtotime('now') ? 'disabled': '');?>/><br/><i><font style="font-size: 15px;"><?php echo $msg[0];?></font></i><br/><br/>
                    <font style="font-size: 20px;">Nombre de buts marqués par la France</font>
                    <input type="text" name="b" size="5" value=<?php echo '"' . $vals[1] . '" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-18 15:00:00') < strtotime('now') ? 'disabled': '');?>/><br/><i><font style="font-size: 15px;"><?php echo $msg[1];?></font></i><br/><br/>
                    <font style="font-size: 20px;">Nombre de buts encaissés par la France</font>
                    <input type="text" name="c" size="5" value=<?php echo '"' . $vals[2] . '" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-18 15:00:00') < strtotime('now') ? 'disabled': '');?>/><br/><i><font style="font-size: 15px;"><?php echo $msg[2];?></font></i><br/><br/>
                    <font style="font-size: 20px;">Nombre de cartons pendant la compétition</font>
                    <input type="text" name="d" size="5" value=<?php echo '"' . $vals[3] . '" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-18 15:00:00') < strtotime('now') ? 'disabled': '');?>/><br/><i><font style="font-size: 15px;"><?php echo $msg[3];?></font></i><br/><br/>
                </td>
                <td width="50%" align="center">
                    <input type="submit" value="Je valide"/>
                </td>
            </form>
        </tr>
    </table>
    <table width="90%" align = "center" border="1">
        <tr>
            <td width="40%" align="center"><b>RAPPEL DES ÉDITIONS PRÉCÉDENTES</b></td>
            <td width="12%" align="center"><i>2014</i></td>
            <td width="12%" align="center"><i>2010</i></td>
            <td width="12%" align="center"><i>2006</i></td>
            <td width="12%" align="center"><i>2002</i></td>
            <td width="12%" align="center"><i>1998</i></td>
        </tr>
        <tr>
            <td width="35%" align="center"><i>Nombre de buts marqués pendant la compétition</i></td>
            <td width="12%" align="center">171</td>
            <td width="12%" align="center">143</td>
            <td width="12%" align="center">147</td>
            <td width="12%" align="center">161</td>
            <td width="12%" align="center">171</td>
        </tr>
        <tr>
            <td width="35%" align="center"><i>Nombre de buts marqués par la France</i></td>
            <td width="12%" align="center">10</td>
            <td width="12%" align="center">1</td>
            <td width="12%" align="center">9</td>
            <td width="12%" align="center">0</td>
            <td width="12%" align="center">15</td>
        </tr>
        <tr>
            <td width="35%" align="center"><i>Nombre de buts encaissés par la France</i></td>
            <td width="12%" align="center">3</td>
            <td width="12%" align="center">4</td>
            <td width="12%" align="center">3</td>
            <td width="12%" align="center">3</td>
            <td width="12%" align="center">2</td>
        </tr>
        <tr>
            <td width="35%" align="center"><i>Nombre de cartons pendant la compétition</i></td>
            <td width="12%" align="center">188</td>
            <td width="12%" align="center">270</td>
            <td width="12%" align="center">373</td>
            <td width="12%" align="center">289</td>
            <td width="12%" align="center">280</td>
        </tr>
    </table><br/>
</body>
</html>
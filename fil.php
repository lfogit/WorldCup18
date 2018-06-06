<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Discussion | Pronostics coupe du monde 2018</title>
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

        $commu = $bdd->prepare("SELECT id_commu, nom FROM users JOIN commus ON users.id_commu=commus.id WHERE login=:pseudo");
        $commu->execute(array('pseudo' => $_SESSION['login']));
        $data = $commu->fetch();

        $num_commu = $data['id_commu'];
        $nom_commu = $data['nom'];

        $req = $bdd->prepare("SELECT id FROM users WHERE login=:pseudo");
        $req->execute(array('pseudo' => $_SESSION['login']));
        $id_perso = $req->fetch()['id'];

        if (!$data) {
            header('Location: index.php');
            exit();
        }

        $err = '';

        if (isset($_POST['msg']) && strlen($_POST['msg']) < 1000) {
            $inser = $bdd->prepare("INSERT INTO messages(id_user, id_commu, horo, msg) VALUES(:id_u, :id_c, NOW(), :comm)");
            $inser->execute(array('id_u' => $id_perso, 'id_c' => $num_commu, 'comm' => $_POST['msg']));
        } elseif (isset($_POST['msg']) && strlen($_POST['msg']) >= 1000) {
            $err = 'Trop long ! Le message est limité à 1000 caractères.';
        }

        $req = $bdd->prepare("SELECT users.login AS pseudo, DATE_FORMAT(horo + INTERVAL '2' HOUR, 'le %d/%m/%Y à %H:%i') AS hr, msg FROM messages JOIN users ON users.id=messages.id_user WHERE messages.id_commu=:com ORDER BY horo DESC");
        $req->execute(array('com' => $num_commu));

        ?>
        <font style="font-size: 30px;"><b>Toute la tribu s'est réunie autour de grands menhirs</b><br/><br/></font>
    </div>
    <table width="90%" align="center" style='border-collapse: collapse;'>
        <tr>
            <td width="50%" align="center">
                <font style="font-size: 20px;"><a href="communaute.php">Classement de la communauté</a><br/><br/></font>
            </td>
            <td width="50%" align="center">
                <font style="font-size: 20px;"><b>Fil de discussion</b><br/><br/></font>
            </td>
        </tr>
    </table>
    <table width="50%" align="center" style='border-collapse: collapse;'>
        <tr>
            <td width="50%" align="left">
                <form action="fil.php" method="post">
                    <textarea rows="5" cols="50" name="msg"><?php echo ($err != ''? $_POST['msg']: '')?></textarea><br/><br/>
                    <input type="submit" value="Envoyer"/> <?php echo $err;?>
                </form>
            </td>
        </tr>
        <?php
        while ($msg = $req->fetch()) {?>
            <tr>
                <td width="50%" align="left">
                    <?php echo '<b>' . $msg['pseudo'] . '</b>, ' . $msg['hr'] . ', dit :';?><br/>
                </td>
            </tr>
            <tr>
                <td width="50%" align="left" style="border: 1px solid black; padding: 5px;">
                    <?php echo htmlentities($msg['msg']);?>
                </td>
            </tr>
        <?php
        }?>
    </table>
</body>
</html>
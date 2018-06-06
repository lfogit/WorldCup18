<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Paramètres | Pronostics coupe du monde 2018</title>
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

        $req = $bdd->prepare("SELECT id, id_commu, mdp FROM users WHERE login=:pseudo");
        $req->execute(array('pseudo' => $_SESSION['login']));
        $data = $req->fetch();
        $id_perso = $data['id'];
        $num_com = $data['id_commu'];

        $msg1 = '';
        $msg2 = '';
        $msg3 = '';

        if (isset($_POST['new_commu'])) {
            $req = $bdd->prepare("SELECT id FROM commus WHERE nom=:name");
            $req->execute(array('name' => $_POST['new_commu']));
            if ($req->fetch()) {
                $msg1 = 'Une communauté porte déjà ce nom.';
            } else {
                $req = $bdd->prepare("INSERT INTO commus(nom) VALUES(:name)");
                $req->execute(array('name' => $_POST['new_commu']));
                $req = $bdd->prepare("SELECT id FROM commus WHERE nom=:name");
                $req->execute(array('name' => $_POST['new_commu']));

                $old_com = $num_com;
                $num_com = $req->fetch()['id'];

                $req = $bdd->prepare("UPDATE users SET id_commu=:id_c WHERE id=:id_u");
                $req->execute(array('id_c' => $num_com, 'id_u' => $id_perso));

                $mem_commu = $bdd->prepare("SELECT COUNT(*) AS cnt FROM users WHERE id_commu=:comm");
                $mem_commu->execute(array('comm' => $old_com));
                $nb_mem = $mem_commu->fetch()['cnt'];

                if ($nb_mem == 0) {
                    $req = $bdd->prepare("DELETE FROM commus WHERE id=:comm");
                    $req->execute(array('comm' => $old_com));
                }

                $msg1 = 'Vous avez créé et rejoint une nouvelle communauté !';
            }
        } elseif (isset($_POST['set_commu'])) {
            if ($_POST['set_commu'] != 0) {
                $req = $bdd->prepare("SELECT id, nom FROM commus WHERE id=:id_c");
                $req->execute(array('id_c' => $_POST['set_commu']));
            
                $com = $req->fetch();

                if (!$com) {
                    $msg1 = 'Une erreur a été rencontré.';
                } else {
                    $old_com = $num_com;
                    $num_com = $com['id'];
                    $req = $bdd->prepare("UPDATE users SET id_commu=:id_c WHERE id=:id_u");
                    $req->execute(array('id_c' => $num_com, 'id_u' => $id_perso));

                    $mem_commu = $bdd->prepare("SELECT COUNT(*) AS cnt FROM users WHERE id_commu=:comm");
                    $mem_commu->execute(array('comm' => $old_com));
                    $nb_mem = $mem_commu->fetch()['cnt'];

                    if ($nb_mem == 0) {
                        $req = $bdd->prepare("DELETE FROM commus WHERE id=:comm");
                        $req->execute(array('comm' => $old_com));
                    }

                    $msg1 = 'Vous avez rejoint une nouvelle communauté !';
                }
            } else {
                $req = $bdd->prepare("UPDATE users SET id_commu=0 WHERE id=:id_u");
                $req->execute(array('id_u' => $id_perso));
                $old_com = $num_com;
                $num_com = 0;

                $mem_commu = $bdd->prepare("SELECT COUNT(*) AS cnt FROM users WHERE id_commu=:comm");
                $mem_commu->execute(array('comm' => $old_com));
                $nb_mem = $mem_commu->fetch()['cnt'];

                if ($nb_mem == 0) {
                    $req = $bdd->prepare("DELETE FROM commus WHERE id=:comm");
                    $req->execute(array('comm' => $old_com));
                }

                $msg1 = 'Vous avez quitté votre communauté !';
            }
        } elseif (isset($_POST['old_mdp']) && isset($_POST['new_mdp']) && isset($_POST['new_mdp2'])) {
            if (password_verify($_POST['old_mdp'], $data['mdp'])) {
                if ($_POST['new_mdp'] != $_POST['new_mdp2']) {
                    $msg2 = 'Les deux mots de passe diffèrent !';
                } elseif (strlen($_POST['new_mdp']) < 8) {
                    $msg2 = 'Le mot de passe doit faire 8 caractères minimum !';
                } else {
                    $maj_mdp = $bdd->prepare("UPDATE users SET mdp=:password WHERE id=:id_u");
                    $maj_mdp->execute(array('password' => password_hash($_POST['new_mdp'], PASSWORD_DEFAULT), 'id_u' => $id_perso));
                    $msg2 = 'Votre mot de passe a été changé avec succès !';
                }
            } else {
                $msg2 = 'Mot de passe incorrect';
            }
        } elseif (isset($_POST['mdp'])) {
            if (password_verify($_POST['mdp'], $data['mdp'])) {
                if (isset($_POST['ok'])) {
                    $req = $bdd->prepare("DELETE FROM users WHERE id=:id_u");
                    $req->execute(array('id_u' => $id_perso));
                    $req = $bdd->prepare("DELETE FROM paris_0 WHERE id_user=:id_u");
                    $req->execute(array('id_u' => $id_perso));
                    $req = $bdd->prepare("DELETE FROM paris_divers WHERE id_user=:id_u");
                    $req->execute(array('id_u' => $id_perso));
                    $req = $bdd->prepare("DELETE FROM paris_match WHERE id_user=:id_u");
                    $req->execute(array('id_u' => $id_perso));
                    header('Location: logout.php');
                    exit();
                } else {
                    $msg3 = 'Vous devez cocher la case afin de confirmer que vous avez compris les conséquences de la suppression de votre compte.';
                }
            } else {
                $msg3 = 'Le mot de passe est incorrect.';
            }
        }
        ?>
        <font style="font-size: 30px;"><b>L'arrière-boutique</b><br/><br/></font>
    </div>
    <table width="90%" align="center" style="border-spacing: 10px;">
        <tr>
            <td id="commu" width="100%" align="left">
                <font style="font-size: 25px;">Rejoindre une communauté</font><br/>
                <font style="font-size: 15px;">En vous rassemblant avec vos proches au sein d'une même communauté, vous apparaîtrez ensemble dans un classement spécifique où il n'y aura que vous. Vous apparaîtrez par ailleurs toujours dans le classement général.</font><br/><br/>
                <?php
                if ($num_com == 0) {?>
                    <font style="font-size: 15px;">Vous n'appartenez à aucune communauté. Vous pouvez mettre un terme à cet état de fait en créant une communauté ou en en sélectionnant une ci-dessous.</font><br/><br/>
                    <?php
                } else {
                    $commu = $bdd->prepare("SELECT nom FROM commus WHERE id=:id_commu");
                    $commu->execute(array('id_commu' => $num_com));
                    $nom_commu = $commu->fetch()['nom'];?>
                    <font style="font-size: 15px;">Vous êtes membre de la communauté <b><?php echo $nom_commu; ?></b>. Vous pouvez la quitter pour en rejoindre une autre ou même en créer une nouvelle grâce au menu ci-dessous. Notez qu'une communauté qui perd tous ses membres disparaît.</font><br/><br/>
                    <?php
                }
                ?>
                <form method="post" action="settings.php#commu">
                    <input type="text" name="new_commu"/>
                    <input type="submit" value="Créer et rejoindre une communauté"/>
                </form>
                <?php
                $commus = $bdd->query("SELECT * FROM commus");
                ?>
            
                <form method="post" action="settings.php#commu">
                    <select name="set_commu">
                        <option value=0>--</option>
                        <?php
                        while ($com = $commus->fetch()) {
                            echo '<option value=' . $com['id'] . ($com['id'] == $num_com ? ' selected': '') . '>' . $com['nom'] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" value="Rejoindre une communauté existante"/>
                </form>
                <i><?php echo $msg1 . ($msg1 != ''? '<br/>': ''); ?></i><br/>
            </td>
        </tr>
        <tr>
            <td id="mdp" width="100%" align="left">
                <font style="font-size: 25px;">Changer de mot de passe</font><br/><br/>
                <form method="post" action="settings.php#mdp">
                    Ancien mot de passe<br/><input type="password" name="old_mdp"><br/><br/>
                    Nouveau mot de passe<br/><input type="password" name="new_mdp"/><br/><br/>
                    Confirmation<br/><input type="password" name="new_mdp2"/><br/><br/>
                    <input type="submit" value="Valider"/>
                </form>
                <i><?php echo $msg2 . ($msg2 != ''? '<br/>': ''); ?></i><br/>
            </td>
        </tr>
        <tr>
            <td id="delete" width="100%" align="left">
                <font style="font-size: 25px;">Supprimer mon compte</font><br/><br/>
                <form method="post" action="settings.php#delete">
                    Mot de passe<br/><input type="password" name="mdp"><br/><br/>
                    <input type="checkbox" name="ok" value="0"> La suppression de mon compte est irréversible et entraîne la suppression de toutes mes données.<br/><br/>
                    <input type="submit" value="Supprimer mon compte"/>
                </form>
                <i><?php echo $msg3 . ($msg3 != ''? '<br/>': ''); ?></i><br/>
            </td>
        </tr>
    </table>
</body>
</html>
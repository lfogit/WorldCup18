<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Foire aux questions | Pronostics coupe du monde 2018</title>
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
        <font style="font-size: 30px;"><b>Dura lex sed lex</b></font><br/><br/>
    </div>
    <table width="90%" align="center" style="border-spacing: 10px;">
        <tr>
            <td id="commu" width="100%" align="left">
                <font style="font-size: 25px;">Où suis-je ?</font><br/><br/>
                <font style="font-size: 15px;">Ce site permet la réalisation de pronostics sur la coupe du monde de football 2018. Vous pouvez ainsi y faire des prévisions sur une kyrielle d'indicateurs et résultats.<br/><br/>
                Un système de points vous permet par ailleurs de mesurer la justesse de vos pronostics et de vous comparer à vos collègues et amis.</font><br/><br/>
                <font style="font-size: 25px;">Sur quoi puis-je parier ?</font><br/><br/>
                <font style="font-size: 15px;">Vous pouvez faire trois sortes de prévisions : sur les matchs, sur le déroulé général de la compétition et sur des grandeurs statistiques diverses.<br/><br/>
                Sur les matchs, vous êtes invités à vous prononcer sur leur score final.<br/><br/>
                Sur le déroulé général de la compétition, vous êtes invités à faire de la prospective et à prédire jusqu'où ira chaque équipe en devinant quelles seront celles qui finiront en tête de leur groupe, quel sera leur parcours pendant la phase finale et qui l'emportera à la fin.<br/><br/>
                Sur les paris divers, vous êtes invités à deviner quel sera le nombre de buts, de cartons, etc.<br/><br/></font>
                <font style="font-size: 25px;">Comment sont comptés les points ?</font><br/><br/>
                <font style="font-size: 15px;">
                    Sur les matchs :
                    <ul>
                        <li>Si vous devinez le bon score, et le bon gagnant dans les cas d'égalité en phase finale : <b>5 points</b></li>
                        <li>Si vous devinez le bon écart de points, et le bon gagnant dans les cas d'égalité en phase finale : <b>3 points</b></li>
                        <li>Si vous devinez la bonne issue (égalité ou victoire d'une ou l'autre équipe) : <b>1 point</b></li>
                    </ul>
                    Sur toute la compétition :
                    <ul>
                        <li>Pour chaque équipe annoncée en huitièmes de finale et effectivement présente à ce niveau de la compétition : <b>2 points</b></li>
                        <li>Pour chaque équipe annoncée en quarts de finale et effectivement présente à ce niveau de la compétition : <b>5 points</b></li>
                        <li>Pour chaque équipe annoncée en demi-finales et effectivement présente à ce niveau de la compétition : <b>10 points</b></li>
                        <li>Pour chaque équipe annoncée en finale et effectivement présente à ce niveau de la compétition : <b>15 points</b></li>
                        <li>Si l'équipe championne est correctement devinée : <b>20 points</b></li>
                        <li>Si les trois premières équipes sont correctement devinées dans le désordre : <b>20 points</b></li>
                        <li>Si les trois premières équipes sont correctement devinées dans l'ordre : <b>25 points</b></li>
                    </ul>
                   Sur chaque « pari divers », vous pouvez marquer un maximum de <b>10 points</b> en fonction de votre proximité à la bonne réponse.<br/><br/>
               </font>
               <font style="font-size: 25px;">Qu'y a-t-il à gagner ?</font><br/><br/>
               <font style="font-size: 15px;">
                    Le site n'a rien à vous offrir si ce n'est le plaisir de la compétition. Charge à vous de vous organiser avec vos camarades de jeu habituels pour rendre ça plus intéressant en le doublant par exemple d'un système de gages.<br/><br/>
                </font>
            </td>
        </tr>
    </table>
</body>
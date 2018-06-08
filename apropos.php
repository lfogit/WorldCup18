<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>À propos | WorldCup 2018</title>
    <link href='https://fonts.googleapis.com/css?family=Mina'
    rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans'
    rel='stylesheet'>
    <link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <div align="left">
        <font style="font-family: 'Mina'; font-size: 20px;"><a href="index.php"><b>PRONOSTICS WorldCup 2018</b></a></font>
    </div>
    <div align="right">
        <font style="font-size: 20px;"><a href="logout.php">Déconnexion</a></font>
    </div><br/>
    <div align="center">
        <font style="font-size: 30px;"><b>A propos de ce site</b></font><br/><br/>
    </div>
    <div align="center"><font style="font-size: 20px;">
        Le code source du site est disponible sur <a href="https://github.com/lfogit/WorldCup18">le dépôt Git du projet</a> sous licence MIT.<br/>
        L'administrateur du site se réserve le droit de supprimer sans préavis ni justification votre compte.<br/>
        Ce site n'est affilié en aucune manière à la FIFA ou aux instances organisatrices de la WorldCup FIFA 2018<br/>
        Vos données ne font l'objet d'aucun traitement automatisé à des fins commerciales et nous ne partageons pas vos informations auprès de tiers, quelqu'ils soient.<br/>
        Administration du site : <i>lionel[at]fortier.fr</i><brPronostics><br>
        MIT License<br><br>
        Copyright (c) 2018 Antoine Planchot<br>
        Copyright (c) 2018 Lionel Fortier<br><br>
        <font style="font-size: 12px;">
        <i>Permission is hereby granted, free of charge, to any person obtaining a copy<br>
        of this software and associated documentation files (the "Software"), to deal<br>
        in the Software without restriction, including without limitation the rights<br>
        to use, copy, modify, merge, publish, distribute, sublicense, and/or sell<br>
        copies of the Software, and to permit persons to whom the Software is<br>
        furnished to do so, subject to the following conditions:<br><br>
        The above copyright notice and this permission notice shall be included in all<br>
        copies or substantial portions of the Software.<br><br>
        THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR<br>
        IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,<br>
        FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE<br>
        AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER<br>
        LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,<br>
        OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE<br>
        SOFTWARE.<br><br></i>
      </font>
        More information <a href="https://opensource.org/licenses/MIT">MIT License</a>
    </font></div>
</body>

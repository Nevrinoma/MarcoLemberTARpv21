<?php
require_once("connect.php");
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: loginAB.php');
    exit();
}
global $yhendus;
//peitimine
if (isset($_REQUEST['peitimine'])) {
    $kask = $yhendus->prepare('UPDATE tantsud SET avalik = 0 WHERE id=?');
    $kask->bind_param("i", $_REQUEST['peitimine']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//näitamine
if (isset($_REQUEST['naitamine'])) {
    $kask = $yhendus->prepare('UPDATE tantsud SET avalik = 1 WHERE id=?');
    $kask->bind_param("i", $_REQUEST['naitamine']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//punktide nulliks
if (isset($_REQUEST['punkt0'])) {
    $kask = $yhendus->prepare('UPDATE tantsud SET punktid = 0 WHERE id=?');
    $kask->bind_param("i", $_REQUEST['punkt0']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
if (isset($_REQUEST["kustuta"])) {
    $kask = $yhendus->prepare("DELETE FROM tantsud WHERE id=?");
    $kask->bind_param("s", $_REQUEST["kustuta"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
if (isset($_REQUEST["kustutakom"])) {
    $kask = $yhendus->prepare("UPDATE tantsud SET kommentaarid = '' WHERE id=?");
    $kask->bind_param("s", $_REQUEST["kustutakom"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
?>

<!DOCTYPE html>
<head>
    <title>Tantsud</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <link rel="stylesheet" href="tantsudStyle.css">
</head>
<body>
<header>
    <h1>Tantsud TARpv21</h1>
    <h2 class="logo">Kasutaja leht</h2>
    <nav class="navbar">
        <div class="navbar-container container">
            <input type="checkbox" name="" id="">
            <div class="hamburger-lines">
                <span class="line line1"></span>
                <span class="line line2"></span>
                <span class="line line3"></span>
            </div>
            <ul class="menu-items">
                <li><a href="tantsud.php">Kasutaja leht</a></li>
                <br><br>
                <li><a href="admin.php">Admin leht</a></li>
            </ul>
        </div>
    </nav>
</header>
<form action="admin.php" style="margin-left: 1%">
    <label for="otsi" >Otsi paar</label><br>
    <input type="text" name="otsi" required><br>
    <input type="submit" value="Otsi">
</form>
<table>
    <tr>
        <th style="width: 3%"></th>
        <th>
            Tantsupaar
        </th>
        <th>
            Punktid<br>Punktid nulliks
        </th>
        <th>
            Kommentaarid
            <br>
            Kustuta kommentaarid
        </th>
        <th>
            näita/peida
        </th>
        <th>
            Avalikasutamise päev
        </th>
    </tr>
    <?php
    if (isset($_REQUEST['otsi']))
    {
        $kask = $yhendus -> prepare("SELECT id, tantsupaar, punktid, kommentaarid, avalik, avalik_paev  FROM tantsud WHERE tantsupaar like '%otsi%'");
    }
    else{
        $kask = $yhendus->prepare('SELECT id, tantsupaar, punktid, kommentaarid, avalik, avalik_paev  FROM tantsud ');
    }
    $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid, $avalik, $avalik_paev);
    $kask -> execute();

    while ($kask->fetch()) {
        echo "<tr>";
        echo "<td><a href='?kustuta=" . $id . "'>X</a></td>";
        echo "<td>" . $tantsupaar . "</td>";
        echo "<td>" . $punktid . "<br><a href='?punkt0=$id'>nulliks</a></td>";
        echo "<td>" . $kommentaarid . "<br><a href='?kustutakom=$id '>X</a></td>";
        if ($avalik == 1) {
            $tekst = "Peida";
            $seisund = "peitimine";
            $kasutajatekst = "kasutaja näeb";
        } else {
            $tekst = "Näita";
            $seisund = "naitamine";
            $kasutajatekst = "kasutaja ei näe";
        }
        echo "<td>$kasutajatekst<br><a href='?$seisund=$id'>$tekst</a><br></td>";
        echo "<td>" . $avalik_paev . "</td>";
        echo "</tr>";
    }
    /*1. недоделан поиск
    2. добавление фото сделано
    3.сортировка не успел
    4. своё не успел.*/
    ?>
</table>
/*1. недоделан поиск
2. добавление фото сделано
3.сортировка не успел
4. своё не успел.*/
<form action="logout.php" method="post">
    <input type="submit" value="Logi välja" name="logout">
</form>
</body>

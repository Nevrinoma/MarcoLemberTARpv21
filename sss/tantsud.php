<?php
require_once ('connect.php');
global $yhendus;
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: loginAB.php');
    exit();
}
//tagastab isAdmin session
function isAdmin(){
    return isset($_SESSION['onadmin']) && $_SESSION['onadmin'];
}

//uue tantsupaari lisamine
if (!empty($_REQUEST['paar']))
{
    $kask = $yhendus -> prepare('INSERT INTO tantsud (tantsupaar,img, avalik_paev) VALUES (?,?, NOW())');
    $kask -> bind_param("ss", $_REQUEST['paar'], $_REQUEST['fotopaar']);
    $kask -> execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//kommentaaride lisamine
if (isset($_REQUEST['uuskomment']))
{
    if (!empty($_REQUEST['komment']))
    {
        $kask = $yhendus->prepare('UPDATE tantsud SET kommentaarid = CONCAT(kommentaarid, ?) WHERE id=?');
        $kommentplus = $_REQUEST['komment']."\n";
        $kask -> bind_param("si", $kommentplus, $_REQUEST['uuskomment']);
        $kask -> execute();
        header("Location: $_SERVER[PHP_SELF]");
    }
}
//punktide lisamine
if (isset($_REQUEST['punkt']))
{
    $kask = $yhendus -> prepare('UPDATE tantsud SET punktid = punktid+1 WHERE id=?');
    $kask -> bind_param("i", $_REQUEST['punkt']);
    $kask -> execute();
    header("Location: $_SERVER[PHP_SELF]");
}
?>
<!DOCTYPE html>
<head>
    <title>Tantsud</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="tantsudStyle.css">
</head>
<body>
<header>
    <h1 >Tantsud TARpv21</h1>
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
<table>
    <tr>
        <th>
            Foto
        </th>
        <th>
            Tantsupaar
        </th>
        <th>
            Punktid
        </th>
        <th>
            Haldus
        </th>
        <th>
            Kommentaarid
        </th>
    </tr>
    <!--tabeli sisu näitamine-->
    <?php
    $kask=$yhendus->prepare('SELECT id, tantsupaar,img, punktid, kommentaarid FROM tantsud WHERE avalik = 1');
    $kask->bind_result($id,$tantsupaar,$img,$punktid, $kommentaarid);
    $kask->execute();
    while($kask->fetch()){
        echo "<tr>";
        echo "<td><img src='$img' alt='pilt' style='width: 150px'></td>";
        echo "<td>".$tantsupaar."</td>";
        echo "<td>".$punktid."</td>";
        echo "<td><a href='?punkt=$id'>Lisa 1punkt</a></td>";
        $kommentaarid = nl2br(htmlspecialchars($kommentaarid));
        echo "<td>".$kommentaarid."</td>";
        echo "<td> <form action='?'> 
            <input type='hidden' value='$id' name='uuskomment'> 
            <input type='text' name='komment'>
            <input type='submit' value='ok'>
            </form>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
<?php if (isAdmin()) {?>
<div>
    <h2>Uue tantsupaari lisamine</h2>
    <form action="?">
        <input type="text" placeholder="Tantsupaari nimed" name="paar">
        <input type="text" placeholder="Tantsupaari foto URL" name="fotopaar">
        <input type="submit" value="ok">
    </form>
</div>
<?php }?>
</body>
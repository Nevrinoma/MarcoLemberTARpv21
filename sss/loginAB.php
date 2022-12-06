<?php
 require_once ('connect.php');
 session_start();
 global $yhendus;
 if (isset($_SESSION['tuvastamine'])) {
   header('Location: admin.php');
   exit();
   }
   //kontrollime kas väljad on täidetud
 if (!empty($_POST['login']) && !empty($_POST['pass'])) {

 //eemaldame kasutaja sisestusest kahtlase pahna
 $login = htmlspecialchars(trim($_POST['login']));
 $pass = htmlspecialchars(trim($_POST['pass']));

 //SIIA UUS KONTROLL
 $sool = 'dota2';
 $kryp = crypt($pass, $sool);

 //kontrollime kas andmebaasis on selline kasutaja ja parool

 $kask=$yhendus->prepare("SELECT kasutaja FROM kasutajad WHERE kasutaja=? AND parool=?");
 $kask->bind_param("ss",$login,$kryp);
 $kask->bind_result($nimi);
 $kask ->execute();
 if ($kask->fetch())
 {
     $_SESSION['tuvastamine'] = 'misiganes';
     $_SESSION['kasutaja'] = $nimi;
     header('Location: admin.php');
 } else {
     echo "kasutaja või parool on vale";
 }
 }
 /*
 //register
if (isset($_REQUEST['regvorm']) && !empty($_POST['rlogin']) && !empty($_POST['rpass'])) {
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));

    //SIIA UUS KONTROLL
    $sool = 'dota2';
    $kryp = crypt($pass, $sool);

    $kask=$yhendus->prepare(
        "INSERT INTO kasutajad (kasutaja,parool) VALUES (?,?)"
    );
    $kask->bind_param("ss", $_REQUEST["rlogin"], $_REQUEST["rpass"]);
    $kask->execute();
}
 */
?>
<h1>Login</h1>
<form action="" method="post">
    Login: <input type="text" name="login"><br>
    Password: <input type="password" name="pass"><br>
    <input type="submit" value="Logi sisse">
</form>

<!--<h1>Registreerimine</h1>
<form action="?" method="post" name="regvorm">
    Kasutaja nimi: <input type="text" name="rlogin"><br>
    Parool: <input type="password" name="rpass"><br>
    <input type="submit" value="Registereerima">
</form>
-->
<style>
    body{
        color: #191924; /*teksti värv*/
        background-color:#E6E6FA;
        text-align: center;
    }

    header,forter{
        width: 27%;
        border: 3pt inset #c4c1e0;
        text-align: center;
        margin-left: 30%;
        border-radius: 3.75rem;
        font-family: "Lucida Console", "Courier New", monospace;
    }
</style>
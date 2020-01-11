<?php
  header('Content-Type: text/html; charset=ISO-8859-1'); 
  if (!isset($_GET['view']) or ($_GET['view']=='')) {
   $_GET['view']='aktuelles';
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">    
    <link rel="StyleSheet" href="style.css" type="text/css" />
    <title>Linux User Group Tübingen</title>
  </head>
  <body>

  <div class="menuspalte">
    <div class="menu">
      <a href="<?=$_SERVER['PHP_SELF']?>?view=aktuelles">Aktuelles</a> <br />
      <a href="<?=$_SERVER['PHP_SELF']?>?view=wersindwir">Wer sind wir?</a> <br />
      <a href="<?=$_SERVER['PHP_SELF']?>?view=mailingliste">Mailingliste</a> <br />
      <a href="<?=$_SERVER['PHP_SELF']?>?view=treffen">Treffen</a> <br />
      <a href="<?=$_SERVER['PHP_SELF']?>?view=vortraege">Vorträge</a> <br />
      <a href="<?=$_SERVER['PHP_SELF']?>?view=historisches">Historisches</a> <br />
      <a href="<?=$_SERVER['PHP_SELF']?>?view=kontakt">Kontakt</a> <br />
    </div>
  </div>
  <div class="banner"></div>

  <div class="copyright">
    &copy; by Linux User Group Tübingen<br />
    <?php
    if (file_exists("content/".$_GET['view'].".inc.php")) { 
      echo "Letztes Update: ".date("d.m.Y",filemtime("content/".$_GET['view'].".inc.php"));
    }
    ?>


  </div>

  <div class="main">
    <div class="text">
    <?php
    if (file_exists("content/".$_GET['view'].".inc.php")) {
      require("content/".$_GET['view'].".inc.php");
    } else {
       echo "<h1>Fehler</h1>\nDie gewünschte Seite wurde nicht gefunden!";
    }
    ?>
    </div>
  </div>

  </body>
</html>

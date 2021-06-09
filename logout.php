
<?php

if (isset($_GET['message'])) {
    if ($_GET['message'] == 'Vous avez été déconnecté') {
  echo '<p>Vous avez été déconnecté</p>';
   }
}


?>


<?php



session_start();//initialise la session

session_unset();//desactive la session

session_destroy();

setcookie('auth','', time()-1,'/',null, false, true);//on suprime lecookie


header('location: index.php?message=Vous avez été déconnecté');

exit();


?>
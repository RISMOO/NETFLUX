
<?php
require('recaptcha');

session_start();
require('src/log.php');

if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_two'])){ //ON VERFIE SI IL Y A DES INFOS DANS LE FORMULAIRE

require('src/connect.php');//on se connecte apres avoir verifié
//variables 
$email=htmlspecialchars($_POST['email']);
$password=htmlspecialchars($_POST['password']);
$password_two=htmlspecialchars($_POST['password_two']);

//PASSWORD ++ PASSWORD_TWO

if($password != $password_two){

	header('location:inscription.php?error=1&message=Vos mots de passe ne sont pas identiques.');
	exit();
}

//ON VERIFIE SI ADRESSE VALIDE

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

	header('location:inscription.php?error=1&message=Votre adresse email est invalide.');
	exit();
}

//ON VERIFIE SIN EMAIL DEJA UTILISE
$req=$db->prepare('SELECT count(*) as numberEmail FROM user WHERE email = ?');//nombre de ligne dans ma base de données
$req->execute(array($email));
 while ($email_verification = $req ->fetch()){

	if($email_verification['numberEmail']!=0){//On verfie si il y a dékja un utilisateur qui a cette email
 header('location: inscription.php?error=1&message=Votre adresse email existe déja');
 exit();

 }
}

//HASH
$secret=sha1($email).time();//je cript email
$secret=sha1($secret).time();//je cript email

//CHIFFRAGE DU MOT DE PASSE
$password="momo".sha1($password."123")."29";


//envoi

$req = $db->prepare("INSERT INTO user(email, password, secret) VALUES(?,?,?)");
$req->execute(array($email, $password, $secret));
header('location:inscription.php?success=1');
exit();
}


?>





<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<link rel="icon" type="image/pngn" href="img/favicon.png">
</head>
<body>

	<?php include('src/header.php'); ?>
	
	<section>
		<div id="login-body">
			<h1>S'inscrire</h1>

<?php
 if (isset($_GET['error'])){ 
   if (isset($_GET['message'])){//si les variables error et message existent

    echo '<div class="alert error">'. htmlspecialchars($_GET['message']) .'</div>';


   }
 }else if(isset($_GET['success'])){
 echo '<div class="alert success">Votre inscription à bien été prise en compte .<a href="index.php">Se connecter</a></div>';

 }
if (check_token($_POST['g-racaptacha-response'],SITE_NETFLIX)){

	echo "je traite";
}else{

	echo "je ne traite pas";
}

?>
	
			<form method="post" action="inscription.php">
				<input type="email" name="email" placeholder="Votre adresse email" required />
				<input type="password" name="password" placeholder="Mot de passe" required />
				<input type="password" name="password_two" placeholder="Retapez votre mot de passe" required />
				<button type="submit">S'inscrire</button>
			</form>

			<p class="grey m-2">Déjà sur Netflix ? <a href="index.php">Connectez-vous</a>.</p>
		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>
</html>
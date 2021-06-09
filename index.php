<?php



session_start();
require('src/log.php');


if(!empty($_POST['email'])&& !empty($_POST['password'])){

require('src/connect.php');//on se connecte apres avoir verifié
//VARIABLES
$email=htmlspecialchars($_POST['email']);
$password=htmlspecialchars($_POST['password']);

//ON VERFIFIE QUE C'EST BIEN UNE ADRESSE MAIL
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

	header('location:inscription.php?error=1&message=Votre adresse email est invalide.');
	exit();
}
//CHIFFRAZGE DU MOT DE PASSE
$password="momo".sha1($password."123")."29";

//EMAIL DEJA UTILIS2
//ON VERIFIE SIN EMAIL DEJA UTILISE
$req=$db->prepare('SELECT count(*) as numberEmail FROM user WHERE email = ?');//nombre de ligne dans ma base de données
$req->execute(array($email));
 while ($email_verification = $req ->fetch()){

	if($email_verification['numberEmail']!=1){//On verfie quil y a un seul compte
 header('location: index.php?error=1&message=Impossible de vous authentifier');
 exit();

 }
}
if(empty($_POST['logout'])){

	header('location:inscription.php?error=0&message=Vous avez été déconecté.');
}

//CONNEXION

$req=$db->prepare('SELECT * FROM user WHERE email = ?');
$req->execute(array($email));


while($user = $req->fetch()){//tans quil y  a une ligne a afficher tu la met dans la variable user

if($password == $user['password']){

	$_SESSION['connect'] = 1;//on creer une session
	$_SESSION['email'] = $user['email'];
 if (isset($_POST['auto'])){

setcookie('auth',$user['secret'], time()+ 364*24*3600,'/', null, false, true);
 }

	header('location:index.php?success=1');
}
else{

	header('location:index.php?error=1&message=Impossible de vous authentifier correctement');
}

}
}



?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
	<script src="https://www.google.com/recaptcha/api.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<link rel="icon" type="image/pngn" href="img/favicon.png">
</head>
<body>

	<?php include('src/header.php'); ?>
	<section>
		<div id="login-body">

				<?php if(isset($_SESSION['connect'])) { ?>

					<h1>Bonjour !</h1>
					<?php
					if(isset($_GET['success'])){
						echo'<div class="alert success">Vous êtes maintenant connecté.</div>';
					} ?>

					
					<p>Qu'allez-vous regarder aujourd'hui ?</p>
					<small><a href="logout.php">Déconnexion</a></small>

				<?php } else { ?>
					<h1>S'identifier</h1>

					<?php if(isset($_GET['error'])) {

						if(isset($_GET['message'])) {
							echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
						}

					} ?>

<?php if(isset($_GET['message'])) {
if ($_GET['message'] == 'Vous avez été déconnecté')
 {
	echo'<div class="alert success">A bientôt, vous avez été déconnecté</div>';
}

} ?>


					<form id="myform"method="post" action="index.php">
						<input type="email" name="email" placeholder="Votre adresse email" required />
						<input type="password" name="password" placeholder="Mot de passe" required />
						<button type="submit">S'identifier</button>
					

						<label id="option"><input type="checkbox" name="auto" checked class="m-2" />Se souvenir de moi</label>
					</form>
				

					<p class="grey">Première visite sur Netflix ? <a href="inscription.php">Inscrivez-vous</a>.</p>
				<?php } ?>
		</div>
	</section>
	
	<?php include('src/footer.php'); ?>



</body>
</html>
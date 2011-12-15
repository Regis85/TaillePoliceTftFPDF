<?php
if (isset ($_POST['valide']) && $_POST['valide']=='calcul') {
	$police = (isset ($_POST['police']) && ($_POST['police']>0)) ? $_POST['police'] : '12';
	header("Location: ./test1.php?police=".$police);
}
if (isset ($_POST['valide']) && $_POST['valide']=='adapte') {
	$maxPolice = (isset ($_POST['maxPolice']) && ($_POST['maxPolice']>0)) ? $_POST['maxPolice'] : '12';
	$minPolice = (isset ($_POST['minPolice']) && ($_POST['minPolice']>0)) ? $_POST['minPolice'] : '2';
	$largeCadre = (isset ($_POST['largeCadre']) && ($_POST['largeCadre']>10)) ? $_POST['largeCadre'] : '12';
	$hautCadre = (isset ($_POST['hautCadre']) && ($_POST['hautCadre']>10)) ? $_POST['hautCadre'] : '12';
	header("Location: ./test2.php?maxPolice=".$maxPolice."&minPolice=".$minPolice."&largeCadre=".$largeCadre."&hautCadre=".$hautCadre);
}
if (isset ($_POST['valide']) && $_POST['valide']=='multi') {
	$maxPolice = (isset ($_POST['maxPolice']) && ($_POST['maxPolice']>0)) ? $_POST['maxPolice'] : '12';
	$minPolice = (isset ($_POST['minPolice']) && ($_POST['minPolice']>0)) ? $_POST['minPolice'] : '2';
	$largeCadre = (isset ($_POST['largeCadre']) && ($_POST['largeCadre']>10)) ? $_POST['largeCadre'] : '12';
	$hautCadre = (isset ($_POST['hautCadre']) && ($_POST['hautCadre']>10)) ? $_POST['hautCadre'] : '12';
	header("Location: ./test3.php?maxPolice=".$maxPolice."&minPolice=".$minPolice."&largeCadre=".$largeCadre."&hautCadre=".$hautCadre);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Police dans pdf->MultiCell</title>
	</head>
	<body>
		<h1>Calcul de la différence de hauteur de 2 textes</h1>
		<form action="index.php" method="post">
			<p>
				<label for="policeCalcul">Taille de la police : </label>
				<input type='text' name ="police" id="policeCalcul" />
				<br />
				<button type="submit" name="valide" value="calcul" title="Calcul hauteur">
					Calcul de la hauteur d'un texte
				</button> 
			</p>
		</form>
		
		<h1>Adaptation de la police à un cadre</h1>	
		<form action="index.php" method="post">
			<p>
				<label for="maxPolice">Taille maximale de la police : </label>
				<input type='text' name ="maxPolice" id="policeMax" />
				<br />
				<label for="minPolice">Taille minimale de la police : </label>
				<input type='text' name ="minPolice" id="policeMin" />
				<br />
				<label for="">Largeur du cadre (en mm) : </label>
				<input type='text' name ="largeCadre" id="largeCadre" />
				<br />
				<label for="hautCadre">Hauteur maximum du cadre (en mm) : </label>
				<input type='text' name ="hautCadre" id="hautCadre" />
				<br />
				<button type="submit" name="valide" value="adapte" title="Calcul hauteur">
					Adaptation de la police à un cadre
				</button> 
			</p>
		</form>	
		
		<h1>Gestion d'un texte multi-paragraphe</h1>	
		<form action="index.php" method="post">
			<p>
				<label for="maxPolice">Taille maximale de la police : </label>
				<input type='text' name ="maxPolice" id="policeMax" />
				<br />
				<label for="minPolice">Taille minimale de la police : </label>
				<input type='text' name ="minPolice" id="policeMin" />
				<br />
				<label for="">Largeur du cadre (en mm) : </label>
				<input type='text' name ="largeCadre" id="largeCadre" />
				<br />
				<label for="hautCadre">Hauteur maximum du cadre (en mm) : </label>
				<input type='text' name ="hautCadre" id="hautCadre" />
				<br />
				<button type="submit" name="valide" value="multi" title="Calcul hauteur">
					Adapter un texte multi-paragraphe à un cadre
				</button> 
			</p>
		</form>	
	</body>
</html>
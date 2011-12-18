<?php
/**
 * Taille de la police true-type dans un MultiCell de tFPDF
 * 
 * Réduit la taille de la police dans un MultiCell de tFPDF
 * afin que le texte tienne dans le cadre. La taille de la ligne est définie 
 * par (taille de police + marge de la cellule)
 *  
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.


 * @author Régis Bouguin
 * @copyright 2011 Régis Bouguin
 * @license 
 */
class ptFPDF extends tFPDF
{
	protected $numLignes;
	
	/**
	 * Calcul du nombre de lignes nécessaire
	 *
	 * @param text $texte			Texte à traiter
	 * @param int $tailleCadre		Largeur du cadre
	 * @return int 					Nombre de lignes
	 */
	function TailleChapitre($texte, $tailleCadre)
	{
		$Lignes = array();
		$mots = explode(" ", $texte);
		$i = 0;
		$Lignes[$i] = '';
		foreach ($mots as $mot) {
			if ($this->GetStringWidth($mot) > ($tailleCadre-(2*$this->cMargin))) {
				// un mot est plus grand que le cadre
				return FALSE;
				die ('Un mot est plus grand que le cadre');
			}
			if ($this->GetStringWidth($Lignes[$i].$mot) > ($tailleCadre-(2*$this->cMargin))) {
				$i++;
			$Lignes[$i] = '';
			}
			$Lignes[$i] = $Lignes[$i].$mot.' ';
		}
		unset ($mot);
		return count($Lignes);
	}
	
	/**
	 * Calcule le décrément à appliquer
	 *
	 * @param int	$tailleTexte	Hauteur du texte
	 * @param int	$hauteurCadre	Hauteur du cadre
	 * @param float	$decrementMin	Valeur minimale de la réduction de la taille de police en cas de dépassement
	 * @return float				Valeur à décrémenter 
	 */
	protected function DecrementTexte($tailleTexte, $hauteurCadre,$decrementMin=.5) {
		$calculDecrement = (floor(($hauteurCadre * 2) /$tailleTexte))/2;
		$decrement= max($decrementMin, $calculDecrement);
		return $decrement;
	}
	
	/**
	 * Adapte la taille de la police au cadre en tenant compte des paragraphes
	 *
	 * @param text	$txt			Texte à traiter
	 * @param tex	$police			Police à utiliser
	 * @param tex	$policeStyle	Gras, italique, souligné
	 * @param int	$taillePolice	Taille maximale de la police
	 * @param int	$minPolice		Taille minimum de la police
	 * @param int	$largeCadre		Largeur du cadre
	 * @param int	$hautCadre		Hauteur du cadre
	 * @param float	$decrementMin	Valeur minimale de la réduction de la taille de police en cas de dépassement
	 * @return int					Taille de la ligne ou faux si aucune taille de police ne convient
	 */
	function  AdapteTaille($txt, $police, $policeStyle, $taillePolice, $minPolice, $largeCadre, $hautCadre,$decrementMin=.5) {
		$finParagraphe = '';
		// On cherche le caractère retour chariot
		if (mb_ereg_match(".*\r\n", $txt)) {
			$finParagraphe = "\r\n";
		} elseif (mb_ereg_match(".*\n", $txt)) {
			$finParagraphe = "\n";
		} elseif (mb_ereg_match(".*\r", $txt)) {
			$finParagraphe = "\r";
		}
		
		if ($finParagraphe == '') {
			return $this->AdapteTaille($txt, $police, $policeStyle, $taillePolice, $minPolice, $largeCadre, $hautCadre);
		} else {
			$ok = FALSE;
			$paragraphes = explode ($finParagraphe, $txt);
			$nbParagraphe = count($paragraphes);
			$taillePolice = min((($hautCadre / $nbParagraphe)*2),$taillePolice);
			while (!$ok) {
				$nbLignes=0;
				$this->SetFont($police,$policeStyle,$taillePolice);
				// On calcule la hauteur de ligne avec la taille de police
				$tailleLigne = ($this->cMargin + $this->FontSize);
				foreach ($paragraphes as $paragraphe) {
					$ok = TRUE;
					$nbLignesParagraphe = $this->TailleChapitre($paragraphe, $largeCadre);
					if ($nbLignesParagraphe) {
						$nbLignes += $nbLignesParagraphe;
						if (($nbLignes * $tailleLigne) >= $hautCadre) {
							// On est trop grand
							$taillePolice = $taillePolice - $this->DecrementTexte(($nbLignes * $tailleLigne), $hautCadre);
							if ($taillePolice < $minPolice) {
								// La police est trop petite, on renvoie faux
								return FALSE;
							}
							// On recommence avec une police plus petite
							$ok = FALSE;
							break;
						}
					} else {
						// TailleChapitre renvoie 0, ça arrive si un mot ne tient pas dans le cadre
						$taillePolice = $taillePolice - .5;
						if ($taillePolice < $minPolice) {
							// La police est trop petite, on renvoie faux
							return FALSE;
						}
						$ok = FALSE;
						break;
					}
				}
			}
			// on conserve le nombre de lignes pour pouvoir centrer le texte
			$this->numLignes = $nbLignes;
			return $tailleLigne;
		}
	}

	/**
	 * Renvoie la position verticale du cadre texte pour le centrer
	 *
	 * @param text	$txt			Texte à traiter
	 * @param text	$police			Police à utiliser
	 * @param text	$policeStyle	Gras, italique, souligné
	 * @param int	$taillePolice	Taille maximale de la police
	 * @param int	$minPolice		Taille minimum de la police
	 * @param int	$largeCadre		Largeur du cadre
	 * @param int	$hautCadre		Hauteur du cadre
	 * @param int	$hDebut			Hauteur de début du cadre
	 * @return int	Décalage vers le bas à appliquer
	 */
	function CentreMulticell($txt, $police, $policeStyle, $taillePolice, $minPolice, $largeCadre, $hautCadre, $hDebut) {
		// on recherche le nombre de lignes
		$tailleLigne = $this->AdapteTaille($txt, $police, $policeStyle, $taillePolice, $minPolice, $largeCadre, $hautCadre);
		if(!$tailleLigne) {
			// Le texte est trop grand
			return FALSE;
		}
		$hautTexte = $this->numLignes * $tailleLigne;
		$position = floor(($hautCadre - $hautTexte) / 2) + $hDebut;
		return $position;
	}

}


<?php
/**
 * Taille de la police true-type dans un MultiCell de tFPDF
 * 
 * Réduit la taille de la police dans un MultiCell de tFPDF
 * afin que le texte tienne dans le cadre
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
		$mots = explode(' ', $texte);
		$i = 0;
		$Lignes[$i] = '';
		foreach ($mots as $mot) {
			if ($this->GetStringWidth($Lignes[$i].$mot) > ($tailleCadre-2)) {
				$i++;
			$Lignes[$i] = '';
			}
			$Lignes[$i] = $Lignes[$i].$mot.' ';
		}
		unset ($mot);
		return count($Lignes);
	}
	
	/**
	 * Adapte la taille de la police au cadre
	 *
	 * @param text $txt				Texte à traiter
	 * @param tex  $police			Police à utiliser
	 * @param tex  $policeStyle		Gras, italique, souligné
	 * @param int $taillePolice		Taille maximale de la police
	 * @param int $minPolice		Taille minimum de la police
	 * @param int $largeCadre		Largeur du cadre
	 * @param int $hautCadre		Hauteur du cadre
	 * @return int					Taille de la ligne ou faux si aucune taille de police ne convient
	 */
	function AdapteTaille($txt, $police, $policeStyle, $taillePolice, $minPolice, $largeCadre, $hautCadre) {
		$ok = FALSE;
		while (!$ok) {
			$this->	SetFont($police,$policeStyle,$taillePolice);
			$tailleLigne = floor($taillePolice / 2);
			$taille_txt = intval($this->TailleChapitre($txt, $largeCadre));
			if (($taille_txt * $tailleLigne) < $hautCadre) {
				return $tailleLigne;
			} else {
				$taillePolice = $taillePolice - $this->DecrementTexte(($taille_txt * $tailleLigne), $hautCadre); 
				if ($taillePolice < $minPolice) {
					return FALSE;
				}
			}
		}
	}
	
	/**
	 * Calcule le décrément à appliquer
	 *
	 * @param int $tailleTexte		Hauteur du texte
	 * @param int $hauteurCadre		Hauteur du cadre
	 * @return float				Valeur à décrémenter 
	 */
	protected function DecrementTexte($tailleTexte, $hauteurCadre) {
		$calculDecrement = (floor(($hauteurCadre * 2) /$tailleTexte)/2);
		$decrement= max(.5, $calculDecrement);
		return $decrement;
	}
	
	/**
	 * Adapte la taille de la police au cadre en tenant compte des paragraphes
	 *
	 * @param text $txt				Texte à traiter
	 * @param tex  $police			Police à utiliser
	 * @param tex  $policeStyle		Gras, italique, souligné
	 * @param int $taillePolice		Taille maximale de la police
	 * @param int $minPolice		Taille minimum de la police
	 * @param int $largeCadre		Largeur du cadre
	 * @param int $hautCadre		Hauteur du cadre
	 * @return int					Taille de la ligne ou faux si aucune taille de police ne convient
	 */
	function AvecParagraphe($txt, $police, $policeStyle, $taillePolice, $minPolice, $largeCadre, $hautCadre) {
		$finParagraphe = '';
		
		if (mb_ereg_match('.*\r\n', $txt)) {
			$finParagraphe = '\r\n';
		} elseif (mb_ereg_match('.*\n', $txt)) {
			$finParagraphe = '\n';
		} elseif (mb_ereg_match('.*\r', $txt)) {
			$finParagraphe = '\r';
		}
		
		if ($finParagraphe == '') {
			return $this->AdapteTaille($txt, $police, $policeStyle, $taillePolice, $minPolice, $largeCadre, $hautCadre);
		} else {
			$ok = FALSE;
			$paragraphes = explode ($finParagraphe, $txt);
			$nbParagraphe = count($paragraphes);
			$taillePolice = min((($hautCadre / $nbParagraphe)*2),$taillePolice);
			$taillePolice = $taillePolice;
			$nbLignes=0;
			while (!$ok) {
				foreach ($paragraphes as $paragraphe) {
					$tailleLigne = floor($taillePolice / 2);
					$nbLignesParagraphe = $this->AdapteTaille($paragraphe, $police, $policeStyle, $taillePolice, $taillePolice, $largeCadre, $hautCadre);
					if ($nbLignesParagraphe) {
						$nbLignes = $nbLignes + $nbLignesParagraphe;
						if (($nbLignes * $tailleLigne) >= $hautCadre) {
							//$taillePolice = $taillePolice - .5;
							$taillePolice = $taillePolice - $this->DecrementTexte(($nbLignes * $tailleLigne), $hautCadre);
							if ($taillePolice < $minPolice) {
								return FALSE;
							}
							break;
							$ok = FALSE;
						} else {
							$ok = FALSE;
						}
						
					} else {
						$taillePolice = $taillePolice - .5;
						if ($taillePolice < $minPolice) {
							return FALSE;
						}
						break;
						$ok = FALSE;
					}
					$ok = TRUE;
				}
			}
			return $tailleLigne;
		}
	}


}


<?php
function displaySelect($name, $tab, $class='', $addEmptyVal = true, $others='')
{
	$html = '<select id="' . $name . '" name="' . $name . '"' . ($class ? ' class="' . $class . '"' : '') . ($others ? ' ' . $others : '') . '>';
	if ($addEmptyVal) {
		$html .= '<option value="">--</option>';
	}
	foreach ($tab as $key => $value) {
		if (is_array($value)) {
			$html .= '<optgroup label="' . $key . '">';
			foreach ($value as $k => $v) {
				$html .= '<option value="' . $k . '" data-parent-entity="' . $key . '">' . $v . '</option>';
			}
			$html .= '</optgroup>';
		}
		else {
			$html .= '<option value="' . $key . '">' . $value . '</option>';
		}
	}
	$html .= '</select>';

	return $html;
}

function _balise($str, $balise)
{
	return '<' . $balise . '>' . $str . '</' . $balise . '>';
}

function comma($str, $cond)
{
	if ($cond) {
		return ", $str ";
	}
	else {
		return ucfirst($str) . ' ';
	}
}

function displayDate(array $date)
{
	$html = '';
	if (!empty($date['min']) && !empty($date['max'])) {
		$html = ' entre ' . _balise($date['min'], 'strong') . ' et ' . _balise($date['max'], 'strong');
	}
	else if (!empty($date['min'])) {
		$html = _balise(' après ' . $date['min'], 'strong');
	}
	else if (!empty($date['max'])) {
		$html = _balise(' avant ' . $date['max'], 'strong');
	}
	return $html;
}

function displayPrix(array $prix)
{
	$html = '';
	if (!empty($prix['min']) && !empty($prix['max'])) {
		$html = ' entre ' . _balise(number_format($prix['min'], 0, ',', ' '), 'strong') . ' et ' . _balise(number_format($prix['max'], 0, ',', ' '), 'strong') . ' €';
	}
	else if (!empty($prix['min'])) {
		$html = ' >= ' .  _balise(number_format($prix['min'], 0, ',', ' '), 'strong') . ' €';
	}
	else if (!empty($prix['max'])) {
		$html = ' <= ' .  _balise(number_format($prix['max'], 0, ',', ' '), 'strong') . ' €';
	}
	return $html;
}

function criteriaToText($tabCriteria)
{
	$html = '';
	if (!empty($tabCriteria['genre']) && strtolower($tabCriteria['genre']['value']) != 'vp') {
		if (!empty($tabCriteria['carrosserie'])) {
			$html .= _balise(ucfirst(strtolower($tabCriteria['genre']['label'])) . ' ' . _balise(strtolower($tabCriteria['carrosserie']['label']), 'em'), 'strong');
		}
		else {
			$html .= _balise(ucfirst(strtolower($tabCriteria['genre']['label'])), 'strong');
		}
	}
	else if (!empty($tabCriteria['carrosserie'])) {
		$html .= _balise(ucfirst(strtolower($tabCriteria['carrosserie']['label'])), 'strong');
	}

	if (!empty($tabCriteria['marque'])) {
		if (!empty($tabCriteria['modele'])) {
			$html .= comma('modèles', !empty($html)) . _balise(ucfirst(strtolower($tabCriteria['marque']['label'])) . ' ' . _balise(strtoupper($tabCriteria['modele']['label']), 'em'), 'strong');
		}
		else {
			$html .= comma('marque', !empty($html)) . _balise(ucfirst(strtolower($tabCriteria['marque']['label'])), 'strong');
		}

	}
	else if (!empty($tabCriteria['modele'])) {
		$html .= comma('modèle', !empty($html)) . _balise(strtoupper($tabCriteria['modele']['label']), 'strong');
	}

	if (!empty($tabCriteria['segment'])) {
		$html .= comma('gamme', !empty($html)) . _balise(strtolower($tabCriteria['segment']['label']), 'strong');
	}

	if (!empty($tabCriteria['energie'])) {
		if (!empty($html)) {
			$html .= ', énergie ';
		}
		else {
			$html .= 'Véhicules ';
		}
		$html .= _balise(strtolower($tabCriteria['energie']['label']), 'strong');
	}

	if (!empty($tabCriteria['etat'])) {
		if (!empty($html)) {
			$html .= ', ';
		}
		else {
			$html .= 'Véhicules ';
		}
		if (strtolower($tabCriteria['etat']['value']) == 'vn') {
			$html .= ' ' . _balise('neuf', 'strong');
		}
		else if (strtolower($tabCriteria['etat']['value']) == 'vo') {
			$html .= ' d\'' . _balise('occasion', 'strong');
		}
		else if (strtolower($tabCriteria['etat']['value']) == 'va') {
			$html .= ' ' . _balise('assimilé neuf', 'strong');
		}
	}

	if (!empty($tabCriteria['mec'])) {
		if (!empty($html)) {
			$html .= ', ' . _balise('première immat', 'em');
		}
		else {
			$html .= 'Véhicules dont la ' . _balise('première immatriculation', 'em') . ' est';
		}
		$html .= displayDate($tabCriteria['mec']);
	}

	if (!empty($tabCriteria['ddi'])) {
		if (!empty($html)) {
			$html .= ', ' . _balise('dernière immat', 'em');
		}
		else {
			$html .= 'Véhicules dont la ' . _balise('dernière immatriculation', 'em') . ' est';
		}
		$html .= displayDate($tabCriteria['ddi']);
	}

	if (!empty($tabCriteria['pac'])) {
		if (!empty($html)) {
			$html .= ', ' . _balise('prix à l\'achat', 'em');
		}
		else {
			$html .= 'Véhicules dont le ' . _balise('prix d\'achat', 'em') . ' est';
		}
		$html .= displayPrix($tabCriteria['pac']);
	}

	if (!empty($tabCriteria['parg'])) {
		if (!empty($html)) {
			$html .= ', ' . _balise('prix argus', 'em');
		}
		else {
			$html .= 'Véhicules dont le ' . _balise('prix argus', 'em') . ' est';
		}
		$html .= displayPrix($tabCriteria['parg']);
	}

	return $html;
}

/**
 * Retourne une chaine pour identifier une liste de critères
 * @param array $criteria
 * @return NULL|string
 */
function serializeCriteria(array $criteria)
{
	static $tabCrit = array(
		0  => array('name' => 'marque',
					'options' => array('intervalle' => 'no'),),
		1  => array('name' => 'modele',
					'options' => array('intervalle' => 'no'),),
		2  => array('name' => 'mec',
					'options' => array('intervalle' => 'yes'),),
		3  => array('name' => 'etat',
					'options' => array('intervalle' => 'no'),),
		4  => array('name' => 'genre',
					'options' => array('intervalle' => 'no'),),
		5  => array('name' => 'segment',
					'options' => array('intervalle' => 'no'),),
		6  => array('name' => 'energie',
					'options' => array('intervalle' => 'no'),),
		7  => array('name' => 'carrosserie',
					'options' => array('intervalle' => 'no'),),
		8  => array('name' => 'ddi',
					'options' => array('intervalle' => 'yes'),),
		9  => array('name' => 'pac',
					'options' => array('intervalle' => 'yes'),),
		10 => array('name' => 'parg',
					'options' => array('intervalle' => 'yes'),),
	);

	$str = '';
	foreach($tabCrit as $crit) {
		$name = $crit['name'];
		if (isset($criteria[$name])) {
			if ($crit['options']['intervalle'] == 'yes') {
				if (isset($criteria[$name]['min'])) {
					$str .= "{$name}-min=" . $criteria[$name]['min'] . '&';
				}
				if (isset($criteria[$name]['max'])) {
					$str .= "{$name}-max=" . $criteria[$name]['max'] . '&';
				}
			}
			else {
				$str .= "$name=" . $criteria[$name]['value'] . '&';
			}
		}
	}

	if (empty($str)) {
		return null;
	}
	$str = substr($str, 0, -1);
	return $str;
}

function getHashCriteria(array $criteria)
{
	return md5(serializeCriteria($criteria));
}

function criteriaHashExists($hash, array $tabCriteria)
{
	$exists = false;

	foreach ($tabCriteria as $criteria) {
		if (isset($criteria['hash']) && $criteria['hash'] == $hash) {
			$exists = true;
			break;
		}
	}

	return $exists;
}

/**
 * Encode en JSON
 * @param mixed $values valeurs à encoder
 * @return string
 */
function toJson($values)
{
	if (!function_exists('json_encode')) {
		include_once 'vendor/JSON.php';
		$json = new Services_JSON();
		return $json->encode($values);
	}
	return json_encode($values, JSON_HEX_APOS | JSON_HEX_QUOT);
}

/**
 * Decode en JSON
 * @param mixed $values valeurs à encoder
 * @return mixed
 */
function fromJson($values)
{
	if (!function_exists('json_decode')) {
		include_once 'vendor/JSON.php';
		$json = new Services_JSON();
		return $json->decode($values);
	}
	return json_decode($values, true);
}

function handleAjaxError($errCode, $errMsg = null)
{
	header('HTTP/1.1 ' . $errCode);
	if ($errMsg) {
		echo $errMsg;
	}
	exit;
}

/**
 * Retourne une chaîne formatée en html du dump d'un tableau
 * Applique les classes définies dans debug.css sur les différents mots clés
 * @param array $val tableau
 * @return string
 */
function formatHtmlDump($val)
{
	$val = print_r($val, true);
	$val = preg_replace(array('/\[([\w\d-_]+)(:[\w-_]+)*\]/',		// index
								'/([\w-_]+)\sObject/',				// mot-clé object
								'/=>\s(.*)/',						// valeurs
								'/(Array)/',						// mot-clé Array
								'/\040\040\040\040/',				// tabluations
								'/\n\n/'),							// sauts de ligne
						array('[<span class="pname">$1</span>]',
								'<span class="container">$1 Object</span>',
								'=> <span class="pvalue">$1</span>',
								'<span class="container">$1</span>',
								'  ',
								"\n"),
						$val);
	return '<pre>' . $val . '</pre>';
}

function getListMarque()
{
	$tab = array();

	$ora = new Oracle();
	$sql = 'SELECT code, libelle FROM refer.tveh_marque';
	$res = $ora->query($sql);
	while ($row = $ora->fetchRow($res)) {
		$tab[$row['CODE']] = $row['LIBELLE'];
	}

	return $tab;
}

function getListModele()
{
	$tab = array();

	$ora = new Oracle();
	$sql = 'SELECT marque, modele FROM refer.tveh_modele ORDER BY marque, modele';
	$res = $ora->query($sql);
	while ($row = $ora->fetchRow($res)) {
		if (!isset($tab[$row['MARQUE']])) {
			$tab[$row['MARQUE']] = array();
		}
		$tab[$row['MARQUE']][$row['MODELE']] = $row['MODELE'];
	}

	return $tab;
}
<?php
require_once '../inc/define.inc.php';

session_start();

require_once '../inc/functions.inc.php';
require_once '../inc/data.inc.php';


if (empty($_POST['action']) && empty($_GET['action'])) {
	handleAjaxError(404);
	exit;
}

$tabRetour = array();
$action = !empty($_POST['action']) ? $_POST['action'] : $_GET['action'];
switch ($action) {
	case 'add':
		if (empty($_POST['listCriteria']) || empty($_POST['list'])) {
			handleAjaxError(500, 'arguments invalides');
			exit;
		}
		$listCriteria = (array) $_POST['listCriteria']; // une liste de lignes de critères
		$list = trim($_POST['list']);
		if (!in_array($list, array('inclus', 'exclus'))) {
			handleAjaxError(500, 'arguments invalides');
			exit;
		}

		foreach ($listCriteria as $criteria) {
			$serialized = serializeCriteria($criteria);
			$hash = getHashCriteria($criteria);

			if (!criteriaHashExists($hash, $tabVehicules[$list])) {
				$tabVehicules[$list][] = array(
					'hash' => $hash,
					'criteria' => $criteria);
				end($tabVehicules[$list]);
				$id = key($tabVehicules[$list]);

				$tabRetour[] = array(
					'id' => $id,
					'label' => criteriaToText($criteria),
					'criteria' => $criteria,
					'hash' => $hash,
					/*'strId' => $serialized*/
				);
			}
			else {
				error_log("exists $hash");
			}

// 			$tabRetour['id'] = $id;
// 			$tabRetour['label'] = criteriaToText($criteria);
// 			$tabRetour['criteria'] = $criteria;
		}
		break;

	case 'load':
		$tabRetour = array(	'inclus' => array(),
							'exclus' => array());
		foreach ($tabVehicules as $listName => $tabCriteria) {
			foreach ($tabCriteria as $key => $values) {
				$tabRetour[$listName][] = array(
					'id' => $key,
					'label' => criteriaToText($values['criteria']),
					'criteria' => $values['criteria'],
					'hash' => $values['hash'],
				);
			}
		}

		/*if (!empty($tabVehicules['inclus'])) {
			$tabRetour['inclus'] = array();
		}
		foreach ($tabVehicules['inclus'] as $key => $values) {
			$tabRetour['inclus'][] = array(
				'list' => 'inclus',
				'id' => $key,
				'label' => criteriaToText($values['criteria']),
				'criteria' => $values['criteria'],
				'hash' => $values['hash'],
			);
		}

		if (!empty($tabVehicules['exclus'])) {
			$tabRetour['exclus'] = array();
		}
		foreach ($tabVehicules['exclus'] as $key => $values) {
			$tabRetour['exclus'][] = array(
				'list' => 'exclus',
				'id' => $key,
				'label' => criteriaToText($values['criteria']),
				'criteria' => $values['criteria'],
				'hash' => $values['hash'],
			);
		}*/
		break;

	case 'remove':
		if (!isset($_POST['id']) || !is_numeric($_POST['id']) || empty($_POST['list'])) {
			handleAjaxError(500, 'arguments invalides');
			exit;
		}
		$id = (int) $_POST['id'];
		$list = $_POST['list'];
		if (!in_array($list, array('inclus', 'exclus'))) {
			handleAjaxError(500, 'arguments invalides');
			exit;
		}
		if (!isset($tabVehicules[$list][$id])) {
			handleAjaxError(500, 'inexistant');
			exit;
		}
		unset($tabVehicules[$list][$id]);
		break;

	case 'getLists':
		$tabRetour['models'] = $tabModele;
		$tabRetour['brands'] = $tabMarque;
		break;

	default:
		handleAjaxError(404, 'action ' . $action . ' invalide');
		exit;
}

echo toJson(array('r' => $tabRetour));
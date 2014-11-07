<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

require_once( JPATH_COMPONENT_ADMINISTRATOR . '/helpers/puntos.php');

class PuntosControllerPuntos extends PuntosController {

	public function __construct() {
		parent::__construct();

		$this->registerTask('add', 'edit');
		$this->registerTask('apply', 'save');
		$this->registerTask('unpublish', 'publish');
	}
	
	public function geocode() {
		require_once(JPATH_COMPONENT_ADMINISTRATOR . '/libraries/geocoder/geocoder.php');
		$update = array();
		$geocoder = new PuntosGeocoder();
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid', array(), 'array');

		$db = JFactory::getDBO();

		$query = 'SELECT * FROM #__puntos_marker WHERE id IN (' . implode(',', $cid) . ')';
		$db->setQuery($query);
		
		$puntos = $db->loadObjectList();

		$query = $db->getQuery(true);
		foreach($puntos as $punto) {
			if($punto->gmlat > 0 && $punto->gmlng > 0) {
				$data = $geocoder->reverseGeocode($punto->gmlat, $punto->gmlng);

				if(is_array($data)) {
					if($data['status'] == 'OK') {
						$query->clear();
						$address = $data['address'];
						$query->update('#__puntos_marker');

						if($address['street']) {
							$query->set('street = ' . $db->q($address['street']));
						}
						if($address['plz']) {
							$query->set('plz = ' . $db->q($address['plz']));
						}
						if($address['town']) {
							$query->set('town = ' . $db->q($address['town']));
						}
						if($address['country']) {
							$query->set('country = ' . $db->q($address['country']));
						}

						$query->where('id = ' . $db->Quote($punto->id));
						$update[] = $query;
					}
				}
			} else {
				$address = $punto->street .','.$punto->plz.','.$punto->town.','.$punto->country ;

				$data = $geocoder->geocode($address);
				if(is_array($data)) {
					if($data['status'] == 'OK') {
					$update[] = 'UPDATE #__puntos_marker SET '
							. ' gmlat = ' . $db->Quote($data['location']->lat) . ','
							. ' gmlng = ' . $db->Quote($data['location']->lng)
							. ' WHERE id = ' . $db->Quote($punto->id);
					}
				}
			}
		}
		
		if(count($update)) {
			foreach($update as $query ) {
				$db->setQuery($query);

				if($db->execute()) {
					$status = 'OK';
					$message = JText::sprintf('COM_PUNTOS_GEOCODING_COORDINATES_UPDATED', count($update));
				} else {
					$status = 'FAILURE';
					$message = JText::sprintf('COM_PUNTOS_GEOCODING_FAILURE');
				}
			}
		} else {
			$status = 'OK';
			$message = JText::_('COM_PUNTOS_GEOCODING_NOTHING_TO_UPDATE');
		}
		
		$response = array(
			'status' => $status,
			'message' => $message
		);
		echo json_encode($response);
		jexit();
	}

	public function remove() {
		$row = JTable::getInstance('marker', 'Table');
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid', array(), 'array');
		$db = JFactory::getDBO();
        $msg = Jtext::_('COM_PUNTOS_REMOVE_PUNTOS_FAILED');
		if (count($cid)) {
			$cids = implode(',', $cid);
			$query = "DELETE FROM #__puntos_marker WHERE id IN ( $cids )";
			$db->setQuery($query);
			if (!$db->query()) {
				echo "<script> alert ('" . $db->getErrorMsg() . "');
			window.history.go(-1); </script>\n";
			} else {
                $msg = JText::_('COM_PUNTOS_REMOVE_PUNTOS_SUCCESS');
				$row->countCategoryMarker();
			}
		}

		$this->setRedirect('index.php?option=com_puntos&view=puntos', $msg);
	}

	public function publish() {
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid', array(), 'array');

		if ($this->task == 'publish') {
			$publish = 1;
		} else {
			$publish = 0;
		}

		$msg = "";
		$puntoTable = JTable::getInstance('marker', 'Table');
		$puntoTable->publish($cid, $publish);

		$link = 'index.php?option=com_puntos&view=puntos';

		$this->setRedirect($link, $msg);
	}



	private function getCategories() {
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__puntos_categorie ORDER BY " . PuntosHelper::getSettings('category_ordering', 'id ASC');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
	


}
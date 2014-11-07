<?php

defined('_JEXEC') or die('Restricted access');

class PuntosGeocoder {

	private $url = 'http://maps.googleapis.com/maps/api/geocode/json?';
	
	public function __construct() {

	}

	public function request($type, $data) {
		$ch = curl_init($this->url . $type . '='. urlencode($data) .'&sensor=false');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

	public function geocode($address) {
		$response = $this->request('address', $address);
		$json = json_decode($response);

		$status = $json->status;
		if($status == 'OK') {
			$response = array(
				'status' => $status,
				'location' => $json->results[0]->geometry->location
			);
			return $response;
		} elseif ($status == 'ZERO_RESULTS') {
			$response = array(
				'status' => $status
			);
			return $response;
		} elseif ($status == 'OVER_QUERY_LIMIT') {
			$response = array(
				'status' => $status
			);
			return $response;
		} elseif ($status == 'REQUEST_DENIED') {
			$response = array(
				'status' => $status
			);
			return $response;
		} elseif ($status == 'INVALID_REQUEST') {
			$response = array(
				'status' => $status
			);
			return $response;
		} else {

		}
		
		return false;
	}

	public function reverseGeocode($lat, $lng) {
		$response = $this->request('latlng', $lat . ',' . $lng);
		$json = json_decode($response);
		$status = $json->status;

		if($status == 'OK') {
			$address = array();
			if(isset($json->results[0]->address_components)) {
				$components = $json->results[0]->address_components;
				foreach($components as $component) {
					if($component->types[0] == 'country') {
						$address['country'] = $component->long_name;
					}
					if($component->types[0] == 'street_number') {
						$street[] = $component->long_name;
					}
					if($component->types[0] == 'route') {
						$street[] = $component->long_name;
					}
					if($component->types[0] == 'postal_code') {
						$address['plz'] = $component->long_name;
					}
					if($component->types[0] == 'administrative_area_level_1') {
						$address['town'] = $component->long_name;
					}
				}
			}
			if(isset($street)) {
				$address['street'] = implode( ' ', $street);
			}
			$response = array(
				'status' => $status,
				'address' => $address
			);

			return $response;
		} elseif ($status == 'ZERO_RESULTS') {
			$response = array(
				'status' => $status
			);
			return $response;
		} elseif ($status == 'OVER_QUERY_LIMIT') {
			$response = array(
				'status' => $status
			);
			return $response;
		} elseif ($status == 'REQUEST_DENIED') {
			$response = array(
				'status' => $status
			);
			return $response;
		} elseif ($status == 'INVALID_REQUEST') {
			$response = array(
				'status' => $status
			);
			return $response;
		} else {

		}

		return false;
	}

}
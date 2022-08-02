
<?php

	class delivery {

	}

	class recipient {
		public function createRecipient($name, $address, $zip, $city, $country) {

			$this->name = $name;
			$this->address = $address;
			$this->zip = $zip;
			$this->city = $city;
			$this->country = $country;
			return $this;
		}
	}

	class invoice {

		public function createInvoice($recipient) {
			$this->recipient = $recipient;
			$this->date = date("Y-m-d");

			$this->delivery = new delivery;
			$this->delivery->address = $recipient->address;
			$this->delivery->zip = $recipient->zip;
			$this->delivery->city = $recipient->city;
			$this->delivery->country = $recipient->country;
			return $this;
		}
		
		public function setDate($year, $month, $day) {

			$date = $year . '-' . $month . '-' . $day;
			$this->date = $date;
			return $this;
		}


		public function setCurrency($currency) {

			$this->currency = $currency;
			return $this;
		}

		public function setNote($note) {

			$this->note = $note;
			return $this;
		}

		public function setDeliveryAddress($address, $zip, $city, $country) {

			$this->delivery->address = $address;
			$this->delivery->zip = $zip;
			$this->delivery->city = $city;
			$this->delivery->country = $country;
			return $this;
		}

		public function postToDrafts($invoice, $apikey) {

			$url = 'https://restapi.e-conomic.com/invoices/drafts?apikey='. $apikey;

			$postStr = http_build_query($invoice);
			
			$options = array(
				'http' =>
					array(
						'method' => 'POST',
						'header' => 'Content-type: JSON',
						'content' => $postStr
					)
			);
			$streamContext = stream_context_create($options);

			$result = file_get_contents($url, false, $streamContext);

			if($result === false ) {
				$error = error_get_last();
				throw new Exception('POST request failed: ' . $error['message']);
			}

			return $result;
		}

}


$recipient = new recipient;
$recipient->createRecipient('jesper','Dalbygade 40F','6000', 'Kolding', 'Denmark');

$invoice = new invoice;

$invoice->createInvoice($recipient);
$invoice->setCurrency('DKK');

$invoice->setNote('Dette er en note');


var_dump($invoice);
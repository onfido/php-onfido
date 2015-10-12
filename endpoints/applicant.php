<?php

namespace Onfido;

class Applicant {
    public $id, $created_at, $href, $title, $first_name, $middle_name, $last_name, $email, $gender, $dob, $telephone, $mobile, $country, $id_numbers, $addresses;

    public function create() {
        $response = (new Request('POST', 'applicants'))->send($this);
        return $response;
    }

    public function get($applicant_id = null) {
        $response = (new Request('GET', 'applicants' . ($applicant_id !== null ? '/' . $applicant_id : '')))->send($this);

        if($applicant_id !== null)
          return $response;
        else
          return $response->applicants;
    }

}

class ID_Number {
    public $type, $value, $state_code;

}

class Address {
    public $flat_number, $building_number, $building_name, $street, $sub_street, $town, $state, $postcode, $country, $start_date, $end_date;

}

?>

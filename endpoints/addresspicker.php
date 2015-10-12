<?php

namespace Onfido;

class AddressPicker {
    public $postcode;

    public function pick() {
        $response = (new Request('GET', 'addresses/pick'))->send($this);
        return $response->addresses;
    }

}

?>

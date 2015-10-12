<?php

namespace Onfido;

class Check {
    public $id, $created_at, $href, $type, $status, $result, $reports;

    public function create_for($applicant_id) {
        $response = (new Request('POST', 'applicants/'.$applicant_id.'/checks'))->send($this);
        
        return $response;
    }

    public function get($applicant_id, $check_id = null) {
      $response = (new Request('GET', 'applicants/'.$applicant_id.'/checks'.($check_id !== null ? '/'.$check_id : '')))->send($this);

      return $check_id !== null ? $response : $response->checks;
    }

}

class CheckReport {
  public $name, $variant, $options;
}

class CheckReportOption {
  public $name, $options;
}

?>

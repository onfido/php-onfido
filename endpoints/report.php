<?php

namespace Onfido;

class Report {
    public $id, $created_at, $href, $name, $status, $result, $breakdown, $properties;

    public function get($check_id, $report_id = null) {
      $response = (new Request('GET', 'checks/'.$check_id.'/reports'.($report_id !== null ? '/'.$report_id : '')))->send($this);

      return $report_id !== null ? $response : $response->reports;
    }

}

?>

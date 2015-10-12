<?php

/*
Onfido Client core library
*/

namespace Onfido;

class Config {
    public $token = '';
    public $version = '1';
    public $page = 1;
    public $per_page = 20;
    public $debug;

    public static $instance;
    protected function __construct() {

    }

    public static function init() {
        if(static::$instance === null)
            static::$instance = new static();

        return static::$instance;
    }

    public function set_token($token) {
        $this->token = $token;

        return $this;
    }

    public function set_version($version) {
        $this->version = $version;

        return $this;
    }

    public function paginate($page = null, $per_page = null) {
        if($page !== null)
            $this->page = $page;

        if($per_page !== null)
            $this->per_page = $per_page;

        return $this;
    }

    public function debug() {
      $this->debug = true;

      return $this;
    }

//    public function get_token() {
//        return $this->token;
//    }
}

class Request {

    private $method = 'GET';
    private $endpoint = '/';

    private $url = 'https://api.onfido.com/v';

    private $curlHandle;

    public function __construct($method, $endpoint) {
        $this->method = $method;
        $this->endpoint = $endpoint;

        $this->curlHandle = curl_init();
    }

    private function prepare_params(&$params) {
      $output = is_array($params) ? Array() : (is_object($params) ? new \stdClass() : null);

      $file_upload = false;

      foreach(is_array($params) ? $params : (is_object($params) ? get_object_vars($params) : null) as $k=>$v) {
          if($k === 'file' && ($v instanceof \CurlFile || strpos($v, '@') === 0)) {
            $file_upload = true;
          }

          if($k === 'id' || $k === 'created_at' || $v === null)
              continue;

          if(is_array($output))
            $output[$k] = $v;
          else if(is_object($output))
            $output->$k = $v;
      }
      if($file_upload)
        $params = $output;
      else
        $params = preg_replace('/\[[0-9]+\]/', '[]', urldecode(http_build_query($output)));
    }

    public function send($params) {
      if(\Onfido\Config::init()->debug)
        var_dump(get_object_vars($params));

        $params = get_object_vars($params);

        $headers = Array('Authorization: Token token=' . \Onfido\Config::init()->token);

       $url_params = '';

        if($this->method === 'POST') {
            // $headers[] = "Content-type: multipart/form-data";

            curl_setopt($this->curlHandle, CURLOPT_POST, 1);

            $this->prepare_params($params);

            // var_dump($params);
            // var_dump(http_build_query($params));

            curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $params);
        }
        else if($this->method === 'GET') {
          $params['page'] = \Onfido\Config::init()->page;
          $params['per_page'] = \Onfido\Config::init()->per_page;

          $url_params = '?';

          $_url_params = Array();
          foreach($params as $key=>$value) {
            $_url_params[] = $key.'='.urlencode($value);
          }

          $url_params .= implode('&', $_url_params);
        }


        curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER,
          $headers
        );

        curl_setopt($this->curlHandle, CURLOPT_URL, $this->url . \Onfido\Config::init()->version . '/' . $this->endpoint . $url_params);

        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($this->curlHandle);

        return $this->processResponse($response);

        curl_close($this->curlHandle);
    }

    private function processResponse($response) {
        if($response === false)
          return 'cURL Error: ' . curl_error($this->curlHandle);

        $httpError = curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE);
        if(strpos($httpError, '2') !== 0)
          return 'HTTP Error #' . $httpError . ' with Response: ' . $response;

        try {
            $data = json_decode($response);

            if(isset($data->error))
                $this->error($data->error);

            return $data;
        } catch(Execption $e) {
            $this->error("Couldn't parse the response, or general error happened !, Exception: " . json_encode($e));
        }
    }

    private function error($error) {
        //die("Error #{$error->id} ({$error->type}): {$error->message} " . json_encode($error->fields));
        return $error;
        // exit;
    }

}


?>

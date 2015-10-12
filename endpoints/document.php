<?php

namespace Onfido;

class Document {
    public $id, $created_at, $href, $file_name, $file_type, $file_size, $type, $side, $file_path, $file;

    public function upload_for($applicant_id) {
        if(class_exists('\CurlFile'))
            $this->file = new \CurlFile($this->file_path, $this->file_type);
        else
            $this->file = '@' . $this->file_path;

        $response = (new Request('POST', 'applicants/'.$applicant_id.'/documents'))->send($this);
        return $response;
    }

}

?>

<?php

require_once('../autoload.php');


class DocumentsTest extends PHPUnit_Framework_TestCase {

  public function testUpload() {
    \Onfido\Config::init()->set_token('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV')->paginate(null, 5);

    $applicants = (new \Onfido\Applicant())->get();
    $this->assertEquals(5, count($applicants));

    $document = new \Onfido\Document();

    $document->file_name = 'c.jpg';
    $document->file_path = 'c.jpg';
    $document->file_type = 'image/jpg';
    $document->type = 'passport';
    $document->side = 'front';

    $response = $document->upload_for($applicants[0]->id);

    $this->assertInstanceOf('stdClass', $response);
    $this->assertObjectHasAttribute('id', $response);
    $this->assertAttributeNotEmpty('id', $response);
    $this->assertEquals($document->file_name, $response->file_name);
  }

}

 ?>

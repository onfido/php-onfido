<?php

require_once('../autoload.php');


class ApplicantsTest extends PHPUnit_Framework_TestCase {

  protected static $applicants;

  public static function setUpBeforeClass()
  {
      self::$applicants = null;
  }

  public static function tearDownAfterClass()
  {
      self::$applicants = null;
  }

  public function testListAll() {
    \Onfido\Config::init()->set_token('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV')->paginate(null, 5);

    $applicants = (new \Onfido\Applicant())->get();
    $this->assertEquals(5, count($applicants));

    self::$applicants = $applicants;
  }

  public function testGet() {
    \Onfido\Config::init()->set_token('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV');

    $applicant = (new \Onfido\Applicant())->get(self::$applicants[0]->id);
    $this->assertInstanceOf('stdClass', $applicant);
    $this->assertObjectHasAttribute('email', $applicant);
    $this->assertEquals(self::$applicants[0]->email, $applicant->email);
  }

  public function testCreate() {
    \Onfido\Config::init()->set_token('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV');

    $random = time() . rand(0, 999);

    $applicant = new \Onfido\Applicant();
    $applicant->first_name = 'John'.$random;
    $applicant->last_name = 'Smith';
    $applicant->email = 'email'.$random.'@server.com';

    $address1 = new \Onfido\Address();
    $address1->postcode = 'abc';
    $address1->town = 'London';
    $address1->country = 'GBR';

    $applicant->addresses = Array($address1);

    $response = $applicant->create();
    // var_dump($response);

    $this->assertInstanceOf('stdClass', $response);
    $this->assertObjectHasAttribute('first_name', $response);
    $this->assertEquals($response->first_name, 'John'.$random);
  }

  public function testAddress() {
    \Onfido\Config::init()->set_token('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV')->paginate(null, 10);

    $address = new \Onfido\AddressPicker();
    $address->postcode = 'SW4 6EH';
    $addresses = $address->pick();

    $this->assertGreaterThanOrEqual(1, count($addresses));
    $this->assertInstanceOf('stdClass', $addresses[0]);
    $this->assertObjectHasAttribute('country', $addresses[0]);
    $this->assertEquals($addresses[0]->country, 'GBR');
    $this->assertEquals($addresses[0]->town, 'LONDON');
  }

}

 ?>

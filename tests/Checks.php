<?php

require_once('../autoload.php');


class ChecksTest extends PHPUnit_Framework_TestCase {

  protected static $checks;

  public static function setUpBeforeClass()
  {
      self::$checks = null;
  }

  public static function tearDownAfterClass()
  {
      self::$checks = null;
  }

  public function testListAll() {
    \Onfido\Config::init()->set_token('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV')->paginate(null, 5);

    $checks = (new \Onfido\Check())->get('112d8d98-f5d6-478b-bc43-f86ffa2724c8');
    $this->assertLessThanOrEqual(5, count($checks));

    self::$checks = $checks;
  }

  public function testGet() {
    \Onfido\Config::init()->set_token('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV');

    $check = (new \Onfido\Check())->get('112d8d98-f5d6-478b-bc43-f86ffa2724c8', self::$checks[0]->id);
    $this->assertInstanceOf('stdClass', $check);
    $this->assertObjectHasAttribute('id', $check);
    $this->assertEquals(self::$checks[0]->id, $check->id);
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

    $this->assertInstanceOf('stdClass', $response);
    $this->assertObjectHasAttribute('first_name', $response);
    $this->assertEquals($response->first_name, 'John'.$random);

    $check = new \Onfido\Check();
    $check->type = 'standard';

    $report1 = new \Onfido\CheckReport();
    $report1->name = 'identity';

    $check->reports = Array(
        $report1
    );
    $response = $check->create_for($response->id);

    $this->assertInstanceOf('stdClass', $response);
  }

}

 ?>

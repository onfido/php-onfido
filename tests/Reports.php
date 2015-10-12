<?php

require_once('../autoload.php');


class ReportsTest extends PHPUnit_Framework_TestCase {

  protected static $reports;

  public static function setUpBeforeClass()
  {
      self::$reports = null;
  }

  public static function tearDownAfterClass()
  {
      self::$reports = null;
  }

  public function testListAll() {
    \Onfido\Config::init()->set_token('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV')->paginate(null, 5);

    $reports = (new \Onfido\Report())->get('e573d91a-691d-473e-b460-a43c73d3a8ee');
    $this->assertLessThanOrEqual(5, count($reports));
    
    self::$reports = $reports;
  }

  public function testGet() {
    \Onfido\Config::init()->set_token('test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV');

    $report = (new \Onfido\Report())->get('e573d91a-691d-473e-b460-a43c73d3a8ee', self::$reports[0]->id);
    $this->assertInstanceOf('stdClass', $report);
    $this->assertObjectHasAttribute('id', $report);
    $this->assertEquals(self::$reports[0]->id, $report->id);
  }

}

 ?>

# onfido-php

onfido-php is a PHP API client for Onfido's REST API.

# Installation

You can obtain onfido-php from source

    $ git clone https://github.com/onfido/php-onfido.git

# Usage

At the beginning, You need is to import the autoload.php file, Initiate the Config, and Set the Token:

    require_once('autoload.php');

    \Onfido\Config::init()->set_token('YOUR TOKEN');

## Applicants

The [applicant](https://onfido.com/documentation#applicants) endpoint supports two operations - ``create()`` and ``get()``:

### Create applicant

    $applicant = new \Onfido\Applicant();
    $applicant->first_name = 'John';
    $applicant->last_name = 'Smith';
    $applicant->email = 'email@server.com';

    $address1 = new \Onfido\Address();
    $address1->postcode = 'abc';
    $address1->town = 'London';
    $address1->country = 'GBR';

    $applicant->addresses = Array($address1);

    $response = $applicant->create();

### Retrieve applicant
``APPLICANT_ID`` to be the ID of the Applicants You want to retrieve.

    $applicant = (new \Onfido\Applicant())->get(APPLICANT_ID);

### List applicants
``->paginate(2, 5)`` means to get page #2 where each page has 5 Applicants, Any of both can be null to ignore

    \Onfido\Config::init()->set_token('YOUR TOKEN')->paginate(2, 5);

    $applicants = (new \Onfido\Applicant())->get();

## Documents

The [documents](https://onfido.com/documentation#documents) endpoint supports one operation - upload_for():


### Upload document
    $document = new \Onfido\Document();

    $document->file_name = 'file.jpg';
    $document->file_path = '/path/to/file.jpg';
    $document->file_type = 'image/jpg';
    $document->type = 'passport';
    $document->side = 'front';

    $response = $document->upload_for(APPLICANT_ID);


## Checks

The [checks](https://onfido.com/documentation#checks) endpoint supports two operations - ``create_for()`` and ``get()``:

### Create check

    $check = new \Onfido\Check();
    $check->type = 'standard';

    $report1 = new \Onfido\CheckReport();
    $report1->name = 'identity';

    $check->reports = Array(
        $report1
    );
    $response = $check->create_for(APPLICANT_ID);


### Retrieve check

    $check = (new \Onfido\Check())->get(APPLICANT_ID, CHECK_ID);

### List checks

    \Onfido\Config::init()->set_token('YOUR TOKEN')->paginate(null, 5);

    $checks = (new \Onfido\Check())->get(APPLICANT_ID);


## Reports

The [reports](https://onfido.com/documentation#reports) endpoint supports one operation - ``get()``:

### Retrieve report

	$report = (new \Onfido\Report())->get(CHECK_ID, REPORT_ID);

### List reports

    $report = (new \Onfido\Report())->get(CHECK_ID);

## Address Picker
You can get use of the Onfido [Address Picker](https://onfido.com/documentation#address-picker), like:

    $address = new \Onfido\AddressPicker();
    $address->postcode = 'SW4 6EH';
    $addresses = $address->pick();

# Running tests
You will need to have latest version of [phpunit](https://phpunit.de/manual/current/en/installation.html) installed. Then:

    phpunit Applicants.php

will run the tests related to Applicant endpoint operations, and shows the results in a readable way.
You can run other tests like: Checks, Documents and Reports.

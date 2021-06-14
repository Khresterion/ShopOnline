<?php

namespace App\Tests\Entity;

use Monolog\Test\TestCase;
use App\Entity\Patient;

class PatientTest extends TestCase
{

    private $name;


    /**
     * Get the value of name
     */
    public function testGetName()
    {
        $patient = new Patient;
        $patient->setName("Man");
        $name = $patient->getName();

        $this->assertEquals("Man", $name, "mauvais GetName");
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function testSetName()
    {
        $patient = new Patient;
        $patient->setName("Man");
        $name = $patient->getName();

        $this->assertEquals("Man", $name, "mauvais SetName");
    }
}

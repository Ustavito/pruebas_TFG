<?php


namespace test;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverSelect;
use PHPUnit\Framework\TestCase;

//Recuerda encender el servidor -> java -jar selenium-server-standalone-3.141.59.jar


class ExamTest extends TestCase
{

    const ID_ETSII_DEGREES_MOSTOLES = array('2028', '2032', '2033', '2034');
    const ID_EIF_DEGREES_FUENLABRADA = array('2039', '2040', '2041', '2042');
    const ID_ESCET_DEGREES_MOSTOLES = array('2026', '2029', '2030', '2031');
    const ID_ETSII = '10';
    const ID_EIF = '23';
    const ID_ESCET = '33';
    protected $webDriver;

    public function ChromeInit()
    {
        $host = 'http://localhost:4444/wd/hub';
        $capabilities = DesiredCapabilities::chrome();
        $this->webDriver = RemoteWebDriver::create($host, $capabilities);
    }

    public function setUp(): void
    {
        $this->ChromeInit();
    }

    public function tearDown(): void
    {
        $this->webDriver->quit();
    }

    public function data(): array
    {
        $exit = array();

        foreach (self::ID_ETSII_DEGREES_MOSTOLES as $degree) {
            array_push($exit, array(self::ID_ETSII, 'MÓSTOLES', $degree));
        }

        foreach (self::ID_EIF_DEGREES_FUENLABRADA as $degree) {
            array_push($exit, array(self::ID_EIF, 'FUENLABRADA', $degree));
        }

        foreach (self::ID_ESCET_DEGREES_MOSTOLES as $degree) {
            array_push($exit, array(self::ID_ESCET, 'MÓSTOLES', $degree));
        }


        return $exit;
    }


    /*
    *
    *test
    */

    /**
     * @param $faculty_id
     * @param $campus_name
     * @param $career_id
     * @return void
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\UnexpectedTagNameException
     * @dataProvider data
     */

    public function test_exams_1($faculty_id, $campus_name, $career_id)
    {
        echo($faculty_id);
        echo($campus_name);
        echo($career_id);
        $this->webDriver->get("https://gestion2.urjc.es/examenes/");
        $this->webDriver->manage()->window()->maximize();

        $faculty = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('facultad')));

        while (!$faculty) {
            sleep(0.5);
            $faculty = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('facultad')));
        }

        $faculty->selectByValue($faculty_id);
        $campus = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('campus')));
        $found = false;
        while (!$found) {
            try {
                $campus->selectByValue($campus_name);
                $found = true;
            } catch (\Facebook\WebDriver\Exception\NoSuchElementException $e) {
                sleep(0.5);
            }
        }

        $career = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('titulacion')));
        $found = false;
        while (!$found) {
            try {
                $career->selectByValue($career_id);
                $found = true;
            } catch (\Facebook\WebDriver\Exception\NoSuchElementException $e) {
                sleep(0.5);
            }
        }


        $convocatory = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('convocatoria')));
        $found = false;
        while (!$found) {
            try {
                $convocatory->selectByValue('T');
                $found = true;
            } catch (\Facebook\WebDriver\Exception\NoSuchElementException $e) {
                sleep(0.5);
            }
        }

        $tables = $this->webDriver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('[class="table-responsive datos"]'));

        while (!$tables) {
            $tables = $this->webDriver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('[class="table-responsive datos"]'));
            sleep(0.5);
        }
        $this->assertCount(4, $tables);
        sleep(3);
    }
}

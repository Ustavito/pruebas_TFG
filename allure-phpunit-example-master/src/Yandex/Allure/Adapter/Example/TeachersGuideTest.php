<?php


namespace test;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverSelect;
use PHPUnit\Framework\TestCase;

//Recuerda encender el servidor -> java -jar selenium-server-standalone-3.141.59.jar


class TeachersGuideTest extends TestCase
{
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

    /*
    * test
    */
    public function test_horarios_1()
    {
        $this->webDriver->get("https://gestion2.urjc.es/horarios/?paso=1");
        $this->webDriver->manage()->window()->maximize();

        sleep(5);

        $facultad = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('facultad')));


        //$element = $this->webDriver->findElement(\Facebook\WebDriver\WebDriverBy::id('facultad'));
        if ($facultad) {
            $facultad->selectByValue('10');

            //$this->assertTrue(in_array('' ,$facultad->getAllSelectedOptions()), $this->webDriver->getTitle());

            sleep(10);


            $campus = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('campus')));

            $campus->selectByValue('996');
            sleep(10);

            $campus = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('titulacion')));
            $campus->selectByValue('7236');
            sleep(5);

            $continuar = $this->webDriver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector('[class="btn red button-next"]'));
            //$continuar = $this->webDriver->findElement(WebDriverBy::cssSelector('[class="btn red button-next"]')));
            $continuar->click();

            //$element->selectByValue('10');
            //$element->submit();
        }

        sleep(10);
        //print $this->webDriver->getTitle();
        //$this->assertEquals('LambdaTest - Google Search', $this->webDriver->getTitle());
    }

}

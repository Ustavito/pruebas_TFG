<?php


namespace test;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverSelect;
use PHPUnit\Framework\TestCase;
use Smalot\PdfParser\Parser;

//Recuerda encender el servidor -> java -jar selenium-server-standalone-3.141.59.jar


class HorariosWebTest extends TestCase
{
    protected $webDriver;

    public function ChromeInit()
    {
        //shell_exec('java -jar C:\Users\Usti\PhpstormProjects\untitled\selenium-server-standalone-3.141.59.jar');
        $host = 'http://localhost:4444/wd/hub';


        $options = new ChromeOptions();
        $options->setExperimentalOption('prefs',
            ["download.default_directory" => "D:\Usti\Descargas\allure-phpunit-example-master\allure-phpunit-example-master\sources\pdfs",
                "download.prompt_for_download" => False,
                "plugins.always_open_pdf_externally" => True]);


        $capabilities = DesiredCapabilities::chrome();
        $capabilities2 = $options->toCapabilities();

        //$capabilities = $options;
        $this->webDriver = RemoteWebDriver::create($host, $capabilities2);

        //$x = ChromeDriver::create($host, $capabilities);


    }

    public function setUp(): void
    {
        //shell_exec('java -jar C:\Users\Usti\PhpstormProjects\untitled\selenium-server-standalone-3.141.59.jar');
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
        $faculty = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('facultad')));

        while (!$faculty) {
            sleep(1);
            $faculty = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('facultad')));
        }


        //$element = $this->webDriver->findElement(\Facebook\WebDriver\WebDriverBy::id('facultad'));

        $faculty->selectByValue('10');

        //$this->assertTrue(in_array('' ,$facultad->getAllSelectedOptions()), $this->webDriver->getTitle());

        $campus = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('campus')));
        $found = false;
        while (!$found) {
            try {
                $campus->selectByValue('996');
                $found = true;
            } catch (\Facebook\WebDriver\Exception\NoSuchElementException $e) {
                sleep(0.5);
            }
        }

        $career = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('titulacion')));
        $found = false;
        while (!$found) {
            try {
                $career->selectByValue('7236');
                $found = true;
            } catch (\Facebook\WebDriver\Exception\NoSuchElementException $e) {
                sleep(0.5);
            }
        }

        $continuar = $this->webDriver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector('[class="btn red button-next"]'));
        $continuar->click();
        $pdfs = $this->webDriver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('[class="fa fa-file-pdf-o"]'));
        while (!$pdfs) {
            $pdfs = $this->webDriver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('[class="fa fa-file-pdf-o"]'));
            sleep(0.5);
        }
        $this->assertCount(8, $pdfs);
        $this->webDriver->takeScreenshot('D:\Usti\Descargas\allure-phpunit-example-master\allure-phpunit-example-master\sources\screenshots\finalScreen' . time() . '.png');
        //$mainWindow = $this->webDriver->getWindowHandle();
        $pdfs[0]->click();
        sleep(5);


        /*
                $windows = $this->webDriver->getWindowHandles();
                echo count($windows);

                foreach ($windows as $window){
                    if($window != $mainWindow){
                        $this->webDriver->switchTo()->window($window);
                    }
                }

               */


        /*
                $pdf = $download->getAttribute('src');
                $pdfData = file_get_contents($pdf);
                file_put_contents('C:\Users\Usti\PhpstormProjects\untitled\pdfs\horario'.time().'.pdf', $pdfData);
                //$download->click();
        */

        //sleep(5);
    }

    /*
   * @depends test_horarios_1
   */
    public function test_horarios_2()
    {
        $parser = new Parser();
        $pdf = $parser->parseFile('D:\Usti\Descargas\allure-phpunit-example-master\allure-phpunit-example-master\sources\pdfs\horario.pdf');
        //$pdf = $parser->parseFile('allure-phpunit-example-master\sources\pdfs\horario.pdf');
        $text = $pdf->getText();
        echo $text;

        $this->assertNotEmpty($text);

    }

}

<?php


namespace Yandex\Allure\Adapter\Example;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use PHPUnit\Framework\TestCase;
use Yandex\Allure\Adapter\Annotation\Title;

//Recuerda encender el servidor -> java -jar selenium-server-standalone-3.141.59.jar


/**
 * @package Yandex\Allure\Adapter\Example
 * @Title("lll")
 */

class DirectoryTest extends TestCase
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
    public function test_directory_1()
    {
        $this->webDriver->get("https://gestion2.urjc.es/directorio/");
        $this->webDriver->manage()->window()->maximize();

        sleep(5);

        //$buscador = new WebDriverSelect($this->webDriver->findElement(WebDriverBy::id('buscador')));


        $buscador = $this->webDriver->findElement(\Facebook\WebDriver\WebDriverBy::id('buscador'));
        if ($buscador) {
            $buscador->sendKeys('gustavo.marrero');
            $buscador->submit();

            sleep(10);

            $user = $this->webDriver->findElements(\Facebook\WebDriver\WebDriverBy::className('panel-title'));

            $userExists = false;
            foreach ($user as $u) {
                if ($u->getText() == "Gustavo Andrés Marrero Tovar") {
                    $userExists = true;
                }
            }

            $this->webDriver->takeScreenshot('C:\Users\Usti\PhpstormProjects\untitled\screenshots\directory\user' . time() . '.png');


            $this->assertTrue($userExists);
            $this->assertCount(2, $user);
        }
        //print $this->webDriver->getTitle();
        //$this->assertEquals('LambdaTest - Google Search', $this->webDriver->getTitle());
    }

}

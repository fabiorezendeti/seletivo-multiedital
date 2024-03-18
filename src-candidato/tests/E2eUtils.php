<?php

namespace Tests;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverTargetLocator;

class E2eUtils
{
    public function createDriver(String $browser='chrome')
    {
        $host = 'http://selenium-hub:4444/wd/hub';
        switch ($browser) {
            case 'firefox':
                $capabilities = DesiredCapabilities::firefox();
                break;
            case 'edge':
                $capabilities = DesiredCapabilities::microsoftEdge();
                break;
            default:
                $capabilities = DesiredCapabilities::chrome();
                break;
        }
        $capabilities->setCapability('acceptSslCerts', false);
        

        $driver = RemoteWebDriver::create($host, $capabilities, 30000, 30000);

        return $driver;
    }
}

<?php

namespace Tests;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;


class DatabaseTestCase extends TestCase {

    /**
     * @var \PDO
     */
    protected $pdo;

    protected $seeds = true;
    /**
     * @var Manager
     */
    private $manager;

    public function setUp()
    {
        $pdo = new \PDO('sqlite::memory:', null, null,[
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);

        $configArray = require 'phinx.php';
        $configArray['environments']['test'] = [
            'adapter' => 'sqlite',
            'connection' => $pdo
        ];

        $config = new Config($configArray);
        $manager = new Manager($config, new StringInput(' '), new NullOutput());
        $manager->migrate('test');
        $this->manager = $manager;
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        $this->pdo = $pdo;
    }

    public function seedDatatbase() {
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_BOTH);
        $this->manager->seed('test');
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }

}
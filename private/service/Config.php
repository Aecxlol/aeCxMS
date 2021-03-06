<?php


namespace Aecxms\Service;


use Aecxms\Exception\CmsException;
use Aecxms\Http\Response;

class Config
{
    /**
     * @var array
     */
    private array $configFile = [];

    /**
     * @var Response|mixed
     */
    private Response $response;

    /**
     * @var string
     */
    private string $host;

    /**
     * @var string
     */
    private string $dbName;

    /**
     * @var string
     */
    private string $user;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var string
     */
    private static string $env;

    /**
     * Config constructor.
     * Get the config file and hydrate the database setters
     * @throws CmsException
     */
    public function __construct()
    {
        $this->setConfigFile(require('../private/config/config.php'));
        $this->setEnv($this->configFile);
        $this->response = DI::getInstance()->get('Response');
        $this->hydrateDb($this->configFile);
    }

    /**
     * Get all the Db config from the config file and set every setters to the array fields values
     * @param array $config
     * @throws CmsException
     */
    private function hydrateDb(array $config): void
    {
        foreach ($config['database'] as $k => $v) {
            $dbSettersName = 'set' . ucfirst($k);
            if (method_exists($this, $dbSettersName)) {
                $this->$dbSettersName($v);
            } else {
                $this->response->errorOutput(sprintf('The method %s does not exist.', $dbSettersName));
            }
        }
    }

    /**
     * @return array|mixed
     */
    public function getConfigFile()
    {
        return $this->configFile;
    }

    /**
     * @param array|mixed $configFile
     */
    public function setConfigFile($configFile): void
    {
        $this->configFile = $configFile;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getDbName(): string
    {
        return $this->dbName;
    }

    /**
     * @param string $dbName
     */
    public function setDbName(string $dbName): void
    {
        $this->dbName = $dbName;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public static function getEnv(): string
    {
        return self::$env;
    }

    /**
     * @param array $conf
     * Get env through the config's file with the key env
     */
    public function setEnv(array $conf): void
    {
        self::$env = $conf['env'];
    }
}

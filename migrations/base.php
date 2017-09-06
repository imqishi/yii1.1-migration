<?php
class PDOHelper {
    public $pdo = null;

    public function __construct() {
        $config = dirname(__FILE__).'/../config/cron.php';
        $config = require_once($config);
        $dbconfig = $config['components']['db'];
        $this->pdo = new PDO($dbconfig['connectionString'], $dbconfig['username'], $dbconfig['password']);
    }

    public function query($sql) {
        $rtn = $this->pdo->query($sql);
        if($rtn === false) {
            $msg = implode("\n", $this->pdo->errorInfo());
            throw new Exception($msg);
        }
    }
}

return new PDOHelper();
?>

<?php
class MigrateCommand extends CConsoleCommand{
    public function run($args){
        $this->checkMigrationTable();
        if(count($args) == 0 || (count($args) > 0 && $args[0] == 'run')) {
            $this->executeMigrate();
        } else {
            if($args[0] == 'make') {
                $this->makeMigrate($args);
            } else if($args[0] == 'rollback') {
                echo "Developing now.";
                // TODO
            } else {
                echo "Usage:\r\n";
                echo "* php artisan migrate make your-migration\r\n";
                echo "* php artisan migrate run\r\n";
                return false;
            }
        }
        
    }

    public function checkMigrationTable() {
        $sql = "CREATE TABLE IF NOT EXISTS `migrations` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
             `migration` varchar(512) NOT NULL,
             `batch` int(11) NOT NULL,
             PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        Yii::app()->db->createCommand($sql)->execute();
        echo "Migration table has been initialized...\r\n";
    }

    public function executeMigrate() {
        $dir = Yii::app()->basePath.'/migrations';
        $files = scandir($dir);
        $need_migrates = array();
        $last_migrate_reader = Yii::app()->db->createCommand("SELECT `migration`,`batch` FROM migrations ORDER BY id DESC limit 1;")->query();
        $last_migrate_info = $last_migrate_reader->readAll();
        if(count($last_migrate_info)) {
            $batch = $last_migrate_info[0]['batch'] + 1;
            $name = $last_migrate_info[0]['migration'];
            $time = substr($name, 0, 17);
            foreach($files as $f) {
                if(in_array($f, array('base.php', '.', '..', 'template.php'))) {
                    continue;
                }
                $ftime = substr($f, 0, 17);
                if($ftime <= $time) {
                    continue;
                }
                $need_migrates[] = $f;
            }
        } else {
            $batch = 1;
            foreach($files as $f) {
                if(in_array($f, array('base.php', '.', '..', 'template.php'))) {
                    continue;
                }
                $need_migrates[] = $f;
            }
        }

        $fails = array();
        foreach($need_migrates as $need) {
            $re = exec("php {$dir}/{$need}");
            if($re) {
                $fails[] = $need;
            } else {
                Yii::app()->db->createCommand("INSERT INTO `migrations`(`migration`, `batch`) VALUES ('{$need}',{$batch})")->execute();
                echo "SUCCESS: {$need}\r\n";
            }
        }
        if(count($fails)) {
            $fails = implode("\r\n", $fails);
            echo "Following migrations NOT EXECUTE SUCCESS:\r\n{$fails}\r\n";
        }
    }

    public function makeMigrate($args) {
        if(! isset($args[1])) {
            echo "缺少参数，请指定文件名\r\n";
            return false;
        }
        $name = trim($args[1]);
        $date = date("Y_m_d_");
        $timestamp = substr(time(), -6);
        $filename = $date.$timestamp."-".$name.".php";
        if(! is_dir(Yii::app()->basePath."/migrations")) {
            mkdir(Yii::app()->basePath."/migrations");
        }
        $path = Yii::app()->basePath."/migrations/$filename";
        copy(Yii::app()->basePath."/migrations/template.php", $path);
    }
}
?>

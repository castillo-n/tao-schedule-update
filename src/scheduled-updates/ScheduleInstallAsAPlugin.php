<?php
namespace PostScheduler;

class ScheduleInstallAsAPlugin {

    private static string $version = "0.0.6";
    /**
     * @throws \Exception
     */
    public static function init($pluginsDirectory): void
    {
        if(is_dir($pluginsDirectory)){
            $newDir = $pluginsDirectory.'/updateScheduler';
            $override = true;
            if(!is_dir($newDir)) {
                mkdir($newDir,0755);
            }else{
                $files = glob($newDir.'/version_*');
                if(count($files)>0) {
                    $fileVersion = explode( '.',explode("version_", $files[0])[1]);
                    $thisVersion = explode( '.',ScheduleInstallAsAPlugin::$version);
                    if (
                        ($thisVersion[0] > $fileVersion[0])
                        ||
                        ($fileVersion[0] === $fileVersion[0] && $thisVersion[0] > $fileVersion[0])
                        ||
                        ($fileVersion[0] === $fileVersion[0] && $thisVersion[1] === $fileVersion[1] && $thisVersion[2] > $fileVersion[2])
                    ) {
                        foreach($files as $f){
                            unlink($f);
                        }
                        file_put_contents($newDir.'/version_'.ScheduleInstallAsAPlugin::$version, '');
                    }else{
                        $override = false;
                    }
                }
            }
            if(!is_dir($newDir.'/js')) {
                mkdir($newDir.'/js',0755);
            }
            if($override) {
                $fileUpdate = '/ScheduledUpdate.php';
                $fileOptions = '/ScheduleOptions.php';
                $fileJS = '/js/publish-datepicker.js';
                $here = __DIR__;
                if (!is_file($newDir . '/updateScheduler' . $fileUpdate)) {
                    copy($here . $fileUpdate, $newDir . $fileUpdate);
                }
                if (!is_file($newDir . '/updateScheduler' . $fileOptions)) {
                    copy($here . $fileOptions, $newDir . $fileOptions);
                }
                if (!is_file($newDir . '/updateScheduler' . $fileOptions)) {
                    copy($here . $fileJS, $newDir . $fileJS);
                }
            }
        }else {
            throw new \Exception("The Plugin Directory is not a real directory. Folder given:" . $pluginsDirectory);
        }
    }
}

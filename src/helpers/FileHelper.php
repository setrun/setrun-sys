<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace setrun\sys\helpers;

use Yii;

/**
 * File system helper.
 */
class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * @var array
     */
    protected static $extensions = [];

    /**
     * @var string
     */
    protected static $fileExtensionsPath = '@root/vendor/yiisoft/extensions.php';

    /**
     * Find extensions files of application.
     * @param string $fileName
     * @return array
     */
    public static function findExtensionsFiles($fileName = 'config/main.php') : array
    {
        $output     = [];
        $fileNames  = !is_array($fileName) ? [$fileName] : $fileName;
        if (empty(static::$extensions)) {
            static::$extensions = (array) require Yii::getAlias('@root/vendor/yiisoft/extensions.php');
        }
        foreach (static::$extensions as $ext => $data) {
            if (is_array($data['alias'])) {
                foreach ($data['alias'] as $alias => $path) {
                    foreach ($fileNames as $fileName) {
                        $file = $path . '/' .$fileName;
                        if ((strpos($ext, 'setrun') !== false) && file_exists($file)) {
                            $output[] = $file;
                        }
                    }
                }
            }
        }
        return $output;
    }

    /**
     * Load configurations files.
     * @param mixed $files
     * @param array $config
     * @return array
     */
    public static function loadExtensionsFiles($files, array $config = []) : array
    {
        $callbacks = [];
        $files     = !is_array($files) ? static::findExtensionsFiles($files) : $files;
        foreach ($files as $file) {
            $item = require  $file;
            if ($item instanceof \Closure) {
                $callbacks[] = $item;
            }
            if (is_array($item)) {
                $config = array_replace_recursive($config, $item);
            }
        }
        foreach ($callbacks as $callback) {
            $config = $callback($config);
        }
        return $config;
    }

    /**
     * Get a asset url of bundle.
     * @param  $class
     * @param  string $path
     * @return string
     */
    public static function getAssetUrl($class, $path = '') : string
    {
        return Yii::$app->assetManager->getAssetUrl(
            Yii::$app->assetManager->getBundle($class), ''
        ) . $path;
    }
}
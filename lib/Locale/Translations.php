<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 24/11/2014
 * Time: 10:17
 */

namespace Locale {


    class Translations
    {

        public static $path;

        public static $notFoundEntryFilename = 'not_found.csv';

        private static $_instance;

        public static function get()
        {
            if (!isset(self::$_instance))
                self::$_instance = new self();

            return self::$_instance;
        }

        private $translations = [];

        private $keys = [];

        private $languagesColIndex;

        private $notFoundEntryHandle;

        public function __construct()
        {
            if (!isset(self::$path))
                throw new \Exception('Translations::$path must be set');

            if (!is_dir(self::$path))
                throw new \Exception('Translations::$path must be a directory');


            $this->notFoundEntryHandle = fopen(self::$path . DIRECTORY_SEPARATOR . self::$notFoundEntryFilename, 'w+');

            foreach (glob(self::$path . DIRECTORY_SEPARATOR . '*.csv') as $filename) {
                $this->importFile($filename);
            }
        }

        public function getEntry($key, $language = null)
        {

            var_dump($this->translations);

            if (is_null($language))
                $language = getenv('LC_MESSAGES');

            if (isset($this->translations[$language][$key]))
                return $this->translations[$language][$key];

            if (!isset($this->keys[$key]))
                fputcsv($this->notFoundEntryHandle, [$key], ',', '"');

            return $key;
        }

        private function importFile($filename)
        {
            $handle = fopen($filename, 'r');

            while ($row = fgetcsv($handle, null, ',', '"')) {
                if (!isset($this->languagesColIndex)) {
                    $this->languagesColIndex = [];
                    foreach ($row as $i => $v) {
                        if ($i == 0) continue;
                        $this->languagesColIndex[$v] = $i;
                        $this->translations[$v]      = [];
                    }
                    continue;
                }

                foreach ($this->languagesColIndex as $language => $index) {
                    $this->keys[]                           = $row[0];
                    $this->translations[$language][$row[0]] = isset($row[$index]) ? $row[$index] : "";
                }
            }

            fclose($handle);
        }

        public function dump()
        {
            return $this->translations;
        }
    }
}


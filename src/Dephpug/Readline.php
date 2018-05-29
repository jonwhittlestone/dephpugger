<?php

namespace Dephpug;

/**
 * Class to get the readline with history
 */
class Readline
{
    /**
     * File configured in config file to save the history
     */
    private static $_historyFile;

    /**
     * Save the last command used
     */
    private static $_lastLine;

    /**
     * Load the history file
     *
     * @param string $historyFile Path to history file
     *
     * @return void
     */
    public static function load($historyFile)
    {
        self::$_historyFile = $historyFile;
        if (file_exists($historyFile)) {
            readline_read_history($historyFile);
        }
    }

    /**
     * Ask to user with a default message
     *
     * @param string $msg message before command
     *
     * @return string $line
     */
    public function scan($msg = '(dbgp) => ')
    {
        $line = '';
        while ($line === '') {
            $line = trim(readline($msg));
            if ($line !== self::$_lastLine) {
                readline_add_history($line);
                readline_write_history(self::$_historyFile);
                self::$_lastLine = $line;
            }
        }

        return $line;
    }
}

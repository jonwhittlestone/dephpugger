<?php

namespace Dephpug;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * Class to print using Symfony Console Color
 *
 * @example Output::print("<fg=red>Text to print in red</>")
 */
class Output
{
    /**
     * Attribute with Symfony Output Console color
     */
    private static $_output;

    /**
     * Get the output (memoize)
     *
     * @return string self::$_output
     */
    public static function getOutput()
    {
        if (!self::$_output) {
            $output = new ConsoleOutput();
            $output->setFormatter(new OutputFormatter(true));
            self::$_output = $output;
        }

        return self::$_output;
    }

    /**
     * Print a content with symfony colors
     * Didn't return a string, print when this method is called
     *
     * @param string $message Indicates message with symfony color format
     *
     * @return void
     */
    public static function print($message)
    {
        $output = self::getOutput();
        $output->writeln($message);
    }
}

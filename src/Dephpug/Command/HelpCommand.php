<?php

namespace Dephpug\Command;

use Dephpug\Exception\ExitProgram;
use Dephpug\Output;

class HelpCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'Help';
    }

    public function getShortDescription()
    {
        return 'You can run `help <commandName>` to show all explanation';
    }

    public function getDescription()
    {
        
    }

    public function getAlias()
    {
        return 'h | help';
    }

    public function getRegexp()
    {
        return '/^h(?:elp)?/i';
    }

    public function exec()
    {
        $content = <<<'EOL'

<info>-- Help command --</info>

<options=bold>Command         </>|<options=bold> Short Description</>

EOL;

        foreach($this->core->commandList->reflection->getPlugins() as $command) {
            $alias = $this->getCharsWithSpaces($command->getAlias(), 16);
            $shortDescription = $command->getShortDescription();
            $content .= "<comment>{$alias}</comment>| {$shortDescription}\n";
        }

        $content .= <<<'EOL'

<options=bold>Get variables</>

<fg=blue>Obs: You don't need use echo or var_dump/print_r. Use only the variable or function to get the value exported.</>
<fg=green>`$variable`      </>- Get value of variable
<fg=green>`my_function()`  </>- Get value of my_function()
<fg=green>`$variable = 33` </>- Set $variable to 33

Ex: <comment>`str_replace('a', 'b', 'blablabla')`</comment>
  => (string) blbblbblb

EOL;

        Output::print($content);
    }

    public function getCharsWithSpaces($word, $numberOfSpaces=30)
    {
        $spacesToAdd = $numberOfSpaces - strlen($word);
        return $word . str_repeat(' ', $spacesToAdd);
    }
}

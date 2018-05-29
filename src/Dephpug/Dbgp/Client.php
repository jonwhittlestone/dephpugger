<?php

namespace Dephpug\Dbgp;

/**
 * Class to use Dbgp\Server with methods to convert in commands to dbgp.
 *
 * Class is a better interface to send commands to dbgp
 * protocol. You can use ->run instead of run -i 1, for example.
 * Obs: When you send a command, you need to get the result in
 * ->getResponse() method.
 */
class Client
{
    /**
     * Transaction needed to use in dbgp protocol.
     */
    private $_transactionId = 0;

    /**
     * Object to make connection with dbgp.
     */
    public $dbgpServer;

    /**
     * If has message to receive.
     */
    protected $hasMessage = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dbgpServer = new Server();
    }

    /**
     * Start dbgpServer client.
     *
     * @param string $host Host to DBGP server
     * @param int    $port Port to DBGP server
     *
     * @return void
     */
    public function startClient($host, $port)
    {
        $this->dbgpServer->startClient($host, $port);
    }

    /**
     * Check if has a new received message.
     *
     * @return bool $hasMessage
     */
    public function hasMessage()
    {
        return $this->hasMessage;
    }

    /**
     * Method to send native commands to dbgp protocol.
     *
     * @param string $command Command to send
     *
     * @return void
     */
    public function run($command)
    {
        $this->hasMessage = true;
        $command = str_replace('{id}', $this->_transactionId++, $command);

        $this->dbgpServer->sendCommand($command);
    }

    /**
     * Get always a new number for a transaction.
     * Auto increment.
     *
     * @return int $_transactionId
     */
    public function getTransactionId()
    {
        return $this->_transactionId++;
    }

    /**
     * Command step_into to dbgp server.
     *
     * @example step_into -i 1
     *
     * @return void
     */
    public function stepInto()
    {
        $this->run('step_into -i {id}');
    }

    /**
     * Command to step over to next line if exists.
     *
     * @example step_over -i 1
     *
     * @return void
     */
    public function next()
    {
        $this->run('step_over -i {id}');
    }

    /**
     * Command to send run to debugger. Will stop only if exists
     * another breakpoint.
     *
     * @example run -i 1
     *
     * @return void
     */
    public function continue()
    {
        $this->run('run -i {id}');
    }

    /**
     * Command to send a php code to dbgp server.
     * All commands must be in base64, but the parameter doesnt need.
     *
     * @param string $command Command to run as eval
     *
     * @return void
     */
    public function eval($command)
    {
        $commandEncoded = base64_encode($command);
        $this->run("eval -i {id} -- $commandEncoded");
    }

    /**
     * Command to get a variable (property in dbgp).
     *
     * @param string $variable Variable name
     *
     * @example property_get -i 1 -n $myVariable
     *
     * @return void
     */
    public function propertyGet($variable)
    {
        $this->run("property_get -i {id} -n $variable");
    }

    /**
     * Set a value to a variable.
     *
     * @param string $varname Variable name
     * @param any    $value   Value of variable
     *
     * @example property_set -i 1 -n $myVariable -- MTIz
     *
     * @return void
     */
    public function propertySet($varname, $value)
    {
        $value = base64_encode($value);
        $this->run("property_set -i {id} -n \${$varname} -- {$value}");
    }

    /**
     * Get response after a command has been sent.
     *
     * @return string|null
     */
    public function getResponse()
    {
        if (!$this->hasMessage) {
            return null;
        }
        $this->hasMessage = false;

        return $this->dbgpServer->getResponse();
    }
}

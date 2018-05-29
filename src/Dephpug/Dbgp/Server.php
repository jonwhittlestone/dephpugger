<?php

namespace Dephpug\Dbgp;

use Dephpug\Output;
use Dephpug\MessageParse;
use Dephpug\Exception\ExitProgram;

/**
 * Class to create a socket to remote debugger in xDebug.
 *
 * Class contains the logic to make a connection with xDebug
 * and create a client socket to receive a code and send to
 * DBGP protocol
 */
class Server
{
    /**
     * Socket to connect xDebug.
     */
    private static $_socket;

    /**
     * Socket server to debug.
     */
    private static $_fdSocket;

    /**
     * Starts a client. Set socket server to start client and close the server.
     *
     * @param string $host Host to dbgp protocol
     * @param int    $port Port to dbgp protocol
     *
     * @return void
     */
    public function startClient($host = 'localhost', $port = 9005)
    {
        self::$_socket = socket_create(AF_INET, SOCK_STREAM, 0);
        @socket_set_option(self::$_socket, SOL_SOCKET, SO_REUSEADDR, 1);
        @socket_bind(self::$_socket, $host, $port);
        $result = socket_listen(self::$_socket);
        assert($result);

        Output::print("<fg=blue> --- Listening on port {$port} ---</>\n");
        $this->eventConnectXdebugServer();
        socket_close(self::$_socket);
    }

    /**
     * Close the client socket.
     *
     * @return void
     */
    public function closeClient()
    {
        socket_close(self::$_fdSocket);
    }

    /**
     * Remote commands are async. Method to wait xDebug response.
     *
     * @return void|boolean
     */
    public function eventConnectXdebugServer()
    {
        self::$_fdSocket = null;
        while (true) {
            self::$_fdSocket = socket_accept(self::$_socket);
            if (self::$_fdSocket !== false) {
                Output::print(
                    'Connected to <fg=yellow;options=bold>XDebug server</>!'
                );
                return true;
            }
        }
    }

    /**
     * Sends a command to the xdebug server.
     * Exits process on failure.
     *
     * @param string $command Command to send to DBGP
     *
     * @return boolean
     */
    public function sendCommand($command)
    {
        $result = @socket_write(self::$_fdSocket, "$command\0");
        if ($result === false) {
            $errorSocket = socket_last_error(self::$_fdSocket);

            $error = 'Client socket error: '.socket_strerror($errorSocket);
            throw new ExitProgram($error, 1);
        }
        return true;
    }

    /**
     * Wait the response and set in static property.
     *
     * @return string
     */
    public function getResponse()
    {
        $bytes = 0;
        $message = '';

        do {
            $buffer = '';
            $result = @socket_recv(self::$_fdSocket, $buffer, 1024, 0);
            if ($result === false) {
                throw new ExitProgram('Client socket error', 1);
            }

            $bytes += $result;
            $message .= $buffer;
        } while ($message !== '' && $message[$bytes - 1] !== "\0");
        $messageParse = new MessageParse();

        return $messageParse->formatMessage($message);
    }
}

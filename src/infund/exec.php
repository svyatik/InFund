<?php header('Content-type: charset=utf-8');
/*

This file contains the executor of the command code.

*/

/// This class contains all command codes.
/// Cmds stands for Commands.
class Cmds {

    const CREATE_FUND               = 0x0001;

}

/// This calss performs History File input/output.
class HistoryIO {

    /// The history file location.
    const FILE_LOCATION = "data/infund/history";

    private $file;

    public function __construct() {
        $file_exists = file_exists(FILE_LOCATION));

        // Open/create file for new operations.
        $this->file = fopen(FILE_LOCATION, "r+b");

        // Check if history file already exists.
        if (!$file_exists) {
            // If it does not exist, add header to the file.
            $this->init_new_file();
        }
    }

    function __destruct() {
        fclose($file);
    }

    // Append new command to the history.
    public function push_cmd($cmd, $data) {
        // Move to the end of the file.
        fseek($this->file, 0, SEEK_END);

        throw new Exception("Not implemented yet!");
    }

    private function create_history_entry($cmd, $data) {
        // Header in file (bytes):
        // 0..1 - command id.
        // 2..5 - timestamp & entry id (works until 2038. Then goes negative).
        // 6..9 - entry size, starting from the first byte of header.
        //        Used to skip to the next entry.
        // ... JSON body ...
        // end of the entry - new line byte.

        throw new Exception("Not implemented yet!");

        // Convert data to JSON
        if ($data == NULL) {
            $data = "{}"; // Empty object
        } else {
            $data = json_encode($data);
        }

        $cmd = Cmds::get_name($cmd);
        if ($cmd == NULL) {
            throw new Exception("Command name is not defined!");
        }

        // Find out the size of the entry.
        $header_size = 10;
        $newline_size = 1;
        $size = $header_size + strlen($name) + strlen($data) + $newline_size;

        return (object) array(
            'cmd'    => $cmd,
            'time'   => date_timestamp_get(date_create()),
            'size'   => $size,
            'data'   => $data,
        );
    }

}

/// Any execution error.
class ExecException extends Exception {
    // TODO
}

/// Raise when data passed to the executor is not supported for the
/// given command.
class UnsupportedDataError extends ExecException {
    // TODO
}

/// Raise when executor tried to execute command, but it is not defined.
class UnknownCommandError extends ExecException {
    // TODO
}

/// Holds data about current data system state.
class HistoryCache {

    // Last ID that was allocated for some object type:
    private $max_fund_id;

    public function __construct() {
        throw new Exception("Not implemented yet!");
    }

    public function max_fund_id() {
        return $this->max_fund_id;
    }

    public function max_fund_id_increment() {
        $this->max_fund_id += 1;
    }
}

class Executor {

    private $cache;
    private $history;

    private function __construct() {
        $this->cache = new HistoryCache();
        $this->history = new HistoryIO();
    }

    public static function exec($cmd, $data) {
        $self = new Executor();
        $self->private_exec($cmd, $data);
    }

    private function private_exec($cmd, $data) {
        switch ($cmd) {
            case Cmds::CREATE_FUND:
                self::cmd_create_fund($data);
                break;
            default:
                throw new UnknownCommandError();
        }
    }

    private function cmd_create_fund($data) {
        if ($data != NULL) {
            throw new UnsupportedDataError();
        }

        // Get last fund id and increment it.
        $last_id = $this->cache->max_fund_id_increment();

        // Get new unique fund id.
        $new_id = $this->cache->max_fund_id();

        // Wrap id into object.
        $data = (object) array('id' => $new_id);

        // Save command and data to the history.
        $this->history->push_cmd(Cmds::CREATE_FUND, $data);
    }
}

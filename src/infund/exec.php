<?php header('Content-type: charset=utf-8');
/*

This file contains the executor of the command code.

*/

/// This class contains all command codes.
/// Cmds stands for Commands.
class Cmds {

    const CREATE_FUND               = 0x0001;

}

/// Use history filter to filtrate some particular entries from the whole
/// history.
class HistoryFilter {

    /// Array of the rules.
    private $rules;

    public function __construct() {
        $this->rules = array();
    }

    /// Check if some history entry passes current filter.
    public function passes_filter($entry) {
        // TODO
        return TRUE;
    }
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

    private function init_new_file() {
        // Add text, format identificator.
        fwrite($this->file, "INFUND HISTORY\n");
        // Add format version.
        fwrite($this->file, pack(0x00000100, "V"));
        fwrite($this->file, "\n");
    }

    function __destruct() {
        fclose($file);
    }

    // Append new command to the history.
    public function push_cmd($cmd, $user, $data) {
        // Move to the end of the file.
        fseek($this->file, 0, SEEK_END);

        $entry = create_history_entry($cmd, $user, $data);

        // Write entry to the file.
        fwrite($this->file, pack($entry->cmd , "v"));
        fwrite($this->file, pack($entry->time, "V"));
        fwrite($this->file, pack($entry->size, "V"));
        fwrite($this->file, pack($entry->user, "V"));
        fwrite($this->file,      $entry->data      );
        fwrite($this->file, "\n");
    }

    private function create_history_entry($cmd, $user, $data) {
        // Header in file (bytes):
        // 0..1 - command id.
        // 2..5 - timestamp & entry id (works until 2038. Then goes negative).
        // 6..9 - entry size, starting from the first byte of header.
        //        Used to skip to the next entry.
        // 10..13 - User ID, who requested the operation.
        // ... JSON body ...
        // end of the entry - new line byte.

        // Convert data to JSON
        if ($data == NULL) {
            $data = "{}"; // Empty object
        } else {
            $data = json_encode($data);
        }

        // Find out the size of the entry.
        $header_size = 10;
        $newline_size = 1;
        $size = $header_size + strlen($name) + strlen($data) + $newline_size;

        return (object) array(
            'cmd'    => $cmd,
            'time'   => date_timestamp_get(date_create()),
            'size'   => $size,
            'user'   => $user,
            'data'   => $data,
        );
    }

    /// Read the whole history to form the cache.
    public function fill_history_cache($cache) {
        throw new Exception("Not implemented yet!");
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

    public function __construct($history) {
        $this->max_fund_id = 0;
        $history->fill_history_cache($this);
        // TODO save and load from file.
    }

    public function max_fund_id() {
        return $this->max_fund_id;
    }

    public function max_fund_id_increment() {
        $this->max_fund_id += 1;
    }

    // Update cache as if this command was called.
    public function cmd_create_fund($data) {
        // Change the cache.
        $this->max_fund_id_increment();

        return (object) array(
            'id' => $this->max_fund_id;
        );
    }

}

class Executor {

    private $cache;
    private $history;

    private function __construct() {
        $this->cache = new HistoryCache();
        $this->history = new HistoryIO();
    }

    public static function exec($cmd, $user, $data) {
        $self = new Executor();
        $self->private_exec($cmd, $user, $data);
    }

    private function private_exec($cmd, $user, $data) {
        switch ($cmd) {
            case Cmds::CREATE_FUND:
                self::cmd_create_fund($user, $data);
                break;
            default:
                throw new UnknownCommandError();
        }
    }

    private function cmd_create_fund($user, $data) {
        if ($data != NULL) {
            throw new UnsupportedDataError();
        }

        // Tell cache to execute the command.
        $data = $this->cache->cmd_create_fund($user, $data);

        // Save command and data to the history.
        $this->history->push_cmd(Cmds::CREATE_FUND, $user, $data);
    }
}

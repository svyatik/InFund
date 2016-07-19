<?php header('Content-type: charset=utf-8');
/*

This file contains the executor of the command code.

*/

/// This class contains all command codes.
/// Cmds stands for Commands.
class Cmds {

    const C_CREATE_FUND             = 0x0001;

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
        throw new Exception("Not implemented yet!");
    }

}

class Executor {

    public static function exec($cmd, $data) {
        switch ($cmd) {
            case Cmds::C_CREATE_FUND:
                // TODO
                break;
            default:
                throw new Exception("Not implemented yet!");
        }
    }
}
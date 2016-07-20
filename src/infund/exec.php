<?php header('Content-type: charset=utf-8');
/*

This file contains the executor of the command code.

*/

/// This class contains all command codes.
/// Cmds stands for Commands.
class Cmds {

    const CREATE_FUND               = 0x0001;

    class CreateFundData {

        public $parent_fund_id;

        // Filled manually:
        public $creator_user_id;
        public $this_fund_id;

        public function __construct($parent_fund_id) {
            $this->parent_fund_id = parent_fund_id;
        }

    }

}

/// Represents system user that send some data to the executor.
class User {

    // Access rights for funds:
    const ACCESS_OWN        = 1;
    const ACCESS_ADMIN      = 2;
    const ACCESS_EDIT       = 3;
    const ACCESS_SUGGEST    = 4;
    const ACCESS_VIEW       = 5;
    const ACCESS_NONE       = 6;

    private $id;

    /// Access rights array for each fund, where it is not 'none' or derived.
    private $rights;

    public function __construct($id, $rights) {
        $this->id       = $id;
        $this->rights   = $rights;
    }

    /// Get access right for particular fund.
    public function access_right($fund_id) {
        throw new Exception("Not implemented yet!");
    }

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
    // Backup byte count to restore history if changes discard requested.
    private $byte_index_backup;

    public function __construct() {
        $file_exists = file_exists(FILE_LOCATION));

        // Open/create file for new operations.
        $this->file = fopen(FILE_LOCATION, "r+b");

        // Check if history file already exists.
        if (!$file_exists) {
            // If it does not exist, add header to the file.
            $this->init_new_file();
        }

        // Backup last valid entry position.
        fseek($this->file, 0, SEEK_END);
        $this->byte_index_backup = ftell($this->file);
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

    /// If some command in the group failed, you can revert history
    /// to the state when no command in the group was launched.
    public function discard_changes() {
        ftruncate($this->file, $this->byte_index_backup);
    }

    public function save_changes() {
        fseek($this->file, 0, SEEK_END);
        $this->byte_index_backup = ftell($this->file);
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

/// Raise when user tried to perform something that he hasn't got rights for.
class RightsViolationError extends ExecException {
    // TODO
}

/// Holds data about current data system state.
class HistoryCache {

    // Last ID that was allocated for some object type:
    private $max_fund_id;

    public function __construct($history) {
        $this->max_fund_id = 0;
        $this->load();
    }

    // Launch to load data from history or cache file.
    private function load() {
        Executor::exec_history($this);
        // TODO add cache file support.
    }

    // Discard any changes. Warning: if changes were applied to history too,
    // revert history first.
    public function discard_changes() {
        $this->load();
    }

    public function max_fund_id() {
        return $this->max_fund_id;
    }

    public function max_fund_id_increment() {
        $this->max_fund_id += 1;
    }

    public function save_changes() {
        // TODO currently there is no cache file to save into.
    }

}

/// Executes commands and save changes to cache and history.
class Executor {

    private $cache;
    private $history;

    private function __construct() {
        $this->cache = new HistoryCache();
        $this->history = new HistoryIO();
    }

    private function __construct($cache, $history) {
        $this->cache = $cache;
        $this->history = $history;
    }

    /// Execute command group for user request.
    public static function exec_group($cmds, $user) {
        $self = new Executor();
        try {
            $self->exec_group_($cmds, $user);
            $history->save_changes();
            $cache->save_changes();
        } catch (Exception $e) {
            $history->discard_changes();
            $cache->discard_changes();
        }
    }

    /// Execute history and form the cache.
    /// Given cache will be updated to the newest history.
    public static function exec_hisory($cache) {
        throw new Exception("Not implemented yet!");
    }

    private function exec_group_($cmds, $user) {
        $count = cmds.count();
        for ($i = 0; $i < $count; $i++) {
            $cmd = $cmds[$i];

            switch($cmd->cmd) {
                case Cmds::CREATE_FUND:
                    $this->cmd_create_fund($cmd, $user);
                    break;
                default:
                    throw new UnknownCommandError();
            }
        }
    }

    private function cmd_create_fund($cmd, $user) {
        // Check if user has rights for this command.
        $rights = $user->access_right($cmd->parent_fund_id);
        if ($rights < User::ACCESS_ADMIN) {
            throw new RightsViolationError();
        }

        throw new Exception("Not implemented yet!");
    }
}

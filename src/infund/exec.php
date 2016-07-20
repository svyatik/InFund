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

/// Represents fund hierarchy.
class FundHierarchy {

    const ROOT_FUND_ID = 0;

    private $fund_array; // All funds sorted by id.

    class Fund {

        private $id;
        private $parent_id;

        public function __construct($id, $parent_id) {
            $this->id           = $id;
            $this->parent_id    = $parent_id;
        }

        public function id() {
            return $this->id;
        }

        public function parent_id() {
            return $this->parent_id;
        }

    }

    /// To create fund hierarchy class, you need to transfere the array of
    /// all Fund class objects that are present in the history.
    public function __construct($fund_array) {
        $this->fund_array = $fund_array;
    }

    /// Find fund in the hierarchy with the given ID.
    ///
    /// Then generate the array of the fund IDs, where first
    /// one is the root fund ID, each next is the parent fund ID of
    /// the given one, and the last is actually the given fund ID.
    ///
    /// Return NULL if fund with the given ID does not exist.
    public function get_path($id) {
        $fund = $this->fund_array[$id];

        // Check if fund exists.
        if ($fund == NULL) {
            return NULL;
        }

        $arr = array(0 => $fund);

        // While current fund is not a root fund.
        while ($fund != ROOT_FUND_ID) {
            $fund = $this->fund_array($fund->parent_id);

            // Add at the beginning new fund ID.
            array_unshift($arr, $fund);
        }

        return $arr;
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

    public function __construct($id, $history) {
        $this->id       = $id;
        // FIXME load data about user from the history.
        throw new Exception("Not implemented yet!");
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

    /// Holds data about current data system state.
    class HistoryCache {

        // Last ID that was allocated for some object type:
        public $max_fund_id;

        public function __construct() {
            $this->max_fund_id = 0;
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

    /// The history file location.
    const FILE_LOCATION = "data/infund/history";

    private $file;
    // Backup byte count to restore history if changes discard requested.
    private $byte_index_backup;

    // TODO make history cache internal HistoryIO class.
    private $cache;

    public function __construct() {
        $this->$cache = new HistoryCache();

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

        // FIXME update cache
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
        // FIXME reset cache too.
    }

    public function save_changes() {
        fseek($this->file, 0, SEEK_END);
        $this->byte_index_backup = ftell($this->file);
        $this->cache->save_changes();
    }

    /// Generate fund hierarchy.
    public function fund_hierarchy() {
        throw new Exception("Not implemented yet.");
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

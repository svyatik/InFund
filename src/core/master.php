<?php header('Content-type: charset=utf-8');

/// Class represents a single token from the received command string.
class Token {

    /// Token types
    const WORD_ABOUT        = 0x00;
    const WORD_CREATE       = 0x01;
    const WORD_DROP         = 0x02;
    const WORD_CURRENCY     = 0x03;
    const WORD_FUND         = 0x04;
    const WORD_USER         = 0x05;
    const WORD_FILTER       = 0x06;
    const WORD_SUGGESTION   = 0x07;
    const WORD_REVIVE       = 0x08;
    const WORD_TRANSACT     = 0x09;
    const WORD_FROM         = 0x0A;
    const WORD_TO           = 0x0B;
    const WORD_DESCRIBE     = 0x0C;
    const WORD_TRANSACTION  = 0x0E;
    const WORD_MOVE         = 0x0F;
    const WORD_DATE         = 0x10;
    const WORD_RENAME       = 0x11;
    const WORD_ALLOW        = 0x12;
    const WORD_ANONYMOUS    = 0x13;
    const WORD_OWN          = 0x14;
    const WORD_ADMIN        = 0x15;
    const WORD_VIEW         = 0x16;
    const WORD_EDIT         = 0x17;
    const WORD_PROPOSE      = 0x18;
    const WORD_NONE         = 0x19;
    const WORD_SUGGEST      = 0x1A;
    const WORD_CONVERT      = 0x1C;
    const WORD_HISTORY      = 0x1D;
    const WORD_CURRENT      = 0x1E;
    const WORD_INCLUDE      = 0x1F;
    const WORD_EXCLUDE      = 0x20;
    const WORD_REQUIRE      = 0x21;
    const WORD_RELEASE      = 0x22;
    const WORD_TIME         = 0x23;
    const WORD_RANGE        = 0x24;
    const WORD_UNTIL        = 0x25;
    const WORD_SINCE        = 0x26;
    const WORD_SAVE         = 0x27;
    const WORD_FILTRATE     = 0x28;
    const COMMA             = 0x29;
    const SEMICOLON         = 0x2A;
    const STRING            = 0x2B;
    const NUMBER            = 0x2C;
    const DATE_TIME         = 0x2D;

    var $data;
    var $type;

    public function Token($data, $type) {
        $this->data = $data;
        $this->type = $type;
    }

    /// Data is a parsed and interpreted string. It is of a type defined in the
    /// $type variable.
    public function data() {
        return $this->data;
    }

    /// Type is a token type from an enumeration of types.
    public function type() {
        return $this->type;
    }
}

/// Parse all tokens for interpreter. If there is syntax
/// error, Parser will raise an error.
class Parse {

    /// Index of the current byte in the $cmdstr.
    var $index;

    /// String with commands to parse and compile.
    var $cmdstr;

    /// Get current byte or range in the string.
    /// Return false on error.
    ///
    /// $count - how many bytes to return.
    private function cur($count = 1) {
        return substr($this->cmdstr, $this->index, $count);
    }

    /// The same as cur(), but returns all upper case letters. Use to simulate
    /// case insensitive parser.
    private function cur_up($count = 1) {
        returh strtoupper($this->cur($count));
    }

    /// Move to next bytes in the string.
    /// $skip - how many bytes to skip in the string.
    private function next($count = 1, $skip = 1) {
        $this->index += 1;
        return $this->cur($count);
    }

    /// The same as next(), but returns all upper case letters. Use to simulate
    /// case insensitive parser.
    private function next_up($count = 1, $skip = 1) {
        return strtoupper($this->next($count, $skip));
    }

    function Parser($cmdstr) {
        $this->cmdstr = $cmdstr;
        $this->index = 0;
    }

    // Test if particular character can be part of the word token.
    private static function isWordPart($char) {
        return ctype_alpha($char[0]); // Test only first character.
    }

    /// Try to parse a word from the string.
    ///
    /// SUCCESS: return a token type of the word.
    /// FAILURE: return FALSE.
    public function next_word() {
        // Make backup for index in case we will fail to parse the word.
        $index_backup = $this->index;

        // Strings to compare. Slices of 2, 3 and more characters.
        $a2     = $this->cur_up(2);
        $a3     = $this->cur_up(3);
        $a4     = $this->cur_up(4);
        $a5     = $this->cur_up(5);
        $a6     = $this->cur_up(6);
        $a7     = $this->cur_up(7);
        $a8     = $this->cur_up(8);
        $a9     = $this->cur_up(9);
        $a10    = $this->cur_up(10);
        $a11    = $this->cur_up(11);

        if ($a2 == 'TO') {
            if (!self::isWordPart($this->next_up(1, 2))) {
                return WORD_TO;
            }
        } elseif ($a3 == 'OWN') {
            if (!self::isWordPart($this->next_up(1, 3))) {
                return WORD_OWN;
            }
        } elseif ($a4 == 'DROP') {
            if (!self::isWordPart($this->next_up(1, 4))) {
                return WORD_DROP;
            }
        } elseif ($a4 == 'FUND') {
            if (!self::isWordPart($this->next_up(1, 4))) {
                return WORD_FUND;
            }
        } elseif ($a4 == 'USER') {
            if (!self::isWordPart($this->next_up(1, 4))) {
                return WORD_USER;
            }
        } elseif ($a4 == 'FROM') {
            if (!self::isWordPart($this->next_up(1, 4))) {
                return WORD_FROM;
            }
        } elseif ($a4 == 'MOVE') {
            if (!self::isWordPart($this->next_up(1, 4))) {
                return WORD_MOVE;
            }
        } elseif ($a4 == 'DATE') {
            if (!self::isWordPart($this->next_up(1, 4))) {
                return WORD_DARE;
            }
        } elseif ($a4 == 'VIEW') {
            if (!self::isWordPart($this->next_up(1, 4))) {
                return WORD_VIEW;
            }
        } elseif ($a4 == 'EDIT') {
            if (!self::isWordPart($this->next_up(1, 4))) {
                return WORD_EDIT;
            }
        } elseif ($a4 == 'NONE') {
            if (!self::isWordPart($this->next_up(1, 4))) {
                return WORD_NONE;
            }
        } elseif ($a4 == 'TIME') {
            if (!self::isWordPart($this->next_up(1, 4))) {
                return WORD_TIME;
            }
        } elseif ($a4 == 'SAVE') {
            if (!self::isWordPart($this->next_up(1, 4))) {
                return WORD_SAVE;
            }
        } elseif ($a5 == 'ABOUT') {
            if (!self::isWordPart($this->next_up(1, 5))) {
                return WORD_ABOUT;
            }
        } elseif ($a5 == 'ALLOW') {
            if (!self::isWordPart($this->next_up(1, 5))) {
                return WORD_ALLOW;
            }
        } elseif ($a5 == 'ADMIN') {
            if (!self::isWordPart($this->next_up(1, 5))) {
                return WORD_ADMIN;
            }
        } elseif ($a5 == 'RANGE') {
            if (!self::isWordPart($this->next_up(1, 5))) {
                return WORD_RANGE;
            }
        } elseif ($a5 == 'UNTIL') {
            if (!self::isWordPart($this->next_up(1, 5))) {
                return WORD_UNTIL;
            }
        } elseif ($a5 == 'SINCE') {
            if (!self::isWordPart($this->next_up(1, 5))) {
                return WORD_SINCE;
            }
        } elseif ($a6 == 'CREATE') {
            if (!self::isWordPart($this->next_up(1, 6))) {
                return WORD_CREATE;
            }
        } elseif ($a6 == 'FILTER') {
            if (!self::isWordPart($this->next_up(1, 6))) {
                return WORD_FILTER;
            }
        } elseif ($a6 == 'REVIVE') {
            if (!self::isWordPart($this->next_up(1, 6))) {
                return WORD_REVIVE;
            }
        } elseif ($a6 == 'RENAME') {
            if (!self::isWordPart($this->next_up(1, 6))) {
                return WORD_RENAME;
            }
        } elseif ($a7 == 'PROPOSE') {
            if (!self::isWordPart($this->next_up(1, 7))) {
                return WORD_PROPOSE;
            }
        } elseif ($a7 == 'SUGGEST') {
            if (!self::isWordPart($this->next_up(1, 7))) {
                return WORD_SUGGEST;
            }
        } elseif ($a7 == 'CONVERT') {
            if (!self::isWordPart($this->next_up(1, 7))) {
                return WORD_CONVERT;
            }
        } elseif ($a7 == 'HISTORY') {
            if (!self::isWordPart($this->next_up(1, 7))) {
                return WORD_HISTORY;
            }
        } elseif ($a7 == 'CURRENT') {
            if (!self::isWordPart($this->next_up(1, 7))) {
                return WORD_CURRENT;
            }
        } elseif ($a7 == 'INCLUDE') {
            if (!self::isWordPart($this->next_up(1, 7))) {
                return WORD_INCLUDE;
            }
        } elseif ($a7 == 'EXCLUDE') {
            if (!self::isWordPart($this->next_up(1, 7))) {
                return WORD_EXCLUDE;
            }
        } elseif ($a7 == 'REQUIRE') {
            if (!self::isWordPart($this->next_up(1, 7))) {
                return WORD_REQUIRE;
            }
        } elseif ($a7 == 'RELEASE') {
            if (!self::isWordPart($this->next_up(1, 7))) {
                return WORD_CURRENT;
            }
        } elseif ($a8 == 'CURRENCY') {
            if (!self::isWordPart($this->next_up(1, 8))) {
                return WORD_CURRENCY;
            }
        } elseif ($a8 == 'TRANSACT') {
            if (!self::isWordPart($this->next_up(1, 8))) {
                return WORD_TRANSACT;
            }
        } elseif ($a8 == 'DESCRIBE') {
            if (!self::isWordPart($this->next_up(1, 8))) {
                return WORD_DESCRIBE;
            }
        } elseif ($a8 == 'CURRENCY') {
            if (!self::isWordPart($this->next_up(1, 8))) {
                return WORD_CURRENCY;
            }
        } elseif ($a8 == 'FILTRATE') {
            if (!self::isWordPart($this->next_up(1, 8))) {
                return WORD_FILTRATE;
            }
        } elseif ($a9 == 'ANONYMOUS') {
            if (!self::isWordPart($this->next_up(1, 9))) {
                return WORD_ANONYMOUS;
            }
        } elseif ($a10 == 'SUGGESTION') {
            if (!self::isWordPart($this->next_up(1, 10))) {
                return WORD_SUGGESTION;
            }
        } elseif ($a11 == 'TRANSACTION') {
            if (!self::isWordPart($this->next_up(1, 11))) {
                return WORD_TRANSACTION;
            }
        }

        // Otherwise, restore initial index and return FALSE to indicate
        // failure.
        $this->index = $index_backup;
        return FALSE;
    }

    /// Try to parse a string literal.
    /// Note: string is ASCII or UTF-8.
    ///
    /// SUCCESS   : return the content of the string literal.
    /// NOT STRING: return FALSE.
    /// SYNTAX ERR: return NULL.
    public function next_string() {
        if ($this->cur() != '"') {
            return FALSE; // String literal starts with double-quote.
        }
        $this->next(); // Skip double-quote.

        $literal = ""; // Store scanned literal here.
        while (true) {
            $char = $this->next();
            $byte = ord($char);

            if ($char == '"') {
                // Stop at double-quote or end of input.
                $this->next(); // Consume the quote.
                break;
            } elseif ($char == FALSE) {
                // Unexpected end of input.
                return NULL;
            } else {
                // Add any other character.
                $literal = $literal . $char;
            }
        }

        return $literal;
    }

    /// Try parsing next symbol.
    ///
    /// SUCCESS: return symbol token type.
    /// FAILURE: return FALSE.
    public function next_symbol() {
        $cur = $this->cur();
        $this->index += 1;

        if ($cur == ',') {
            return COMMA;
        } elseif ($cur == ';') {
            return SEMICOLON;
        }

        // Revert.
        $this->index -= 1;
        // Indicate error.
        return FALSE;
    }

    /// Try parsing next number.
    ///
    /// SUCCESS: return string with parser number.
    /// FAILURE: return FALSE.
    public function next_number() {
        $str = ""; // Store resulting number here.
        $cur = $this->cur();
        while (strpbrk($cur, "0123456789") != FALSE) {
            $str = $str . $cur;
            $cur = $this->next();
        }

        if ($str == "") {
            return FALSE; // Couldn't parse a number - other token type found.
        }

        return $str;
    }

    /// Skip all spaces and other unimportant garbage.
    public function skip_garbage() {
        $char = $this->cur();
        while($char == ' ' || $char == '\n' || $char == '\t') {
            $char = $this->next();
        }
    }
}

/// Error that occurs while interpreting/executing code.
class InterpretError extends Exception {

    const MASTER_ERROR = 1;
    const INTERP_ERROR = 2;

    var $from;
    var $type;
    var $data;

    public function errorMessage() {
        $msg = "";
        if ($this->from == MASTER_ERROR) {
            $msg .= "Master";
        } elseif ($this->from == INTERP_ERROR) {
            $msg .= "Interpreter";
        } else {
            $msg .= "Unknown";
        }

        $msg .= " error, type number: " . $this->type;
        $msg .= ";\n" . $this->data;

        return $msg;
    }

    public function InterpretError($from, $type, $data = "") {
        $this->from = $from;
        $this->type = $type;
        $this->data = $data;
    }

    public static function cerr($type, $data = "") {
        throw new InterpretError(INTERP_ERROR, $type, $data);
    }

    public static function xerr($type, $data = "") {
        throw new InterpretError(MASTER_ERROR, $type, $data);
    }
}

/// Performs actual operatons in the data base.
class Master {

    // Internal and Selected language variables
    var $s_fund;
    var $s_currency;
    var $s_transaction;
    var $s_user;
    var $s_filter;
    var $s_suggestion;
    var $s_history;
    var $s_from_type;
    var $s_from;
    var $s_to_type;
    var $s_to;

    public function Master() {
        $this->s_fund           = NULL;
        $this->s_currency       = NULL;
        $this->s_transaction    = NULL;
        $this->s_user           = NULL;
        $this->s_filter         = NULL;
        $this->s_suggestion     = NULL;
        $this->s_history        = NULL;
        $this->s_from_type      = NULL;
        $this->s_from           = NULL;
        $this->s_to_type        = NULL;
        $this->s_to             = NULL;
    }

    public function select_fund($id) {
        $this->s_fund = $id;
    }

    public function select_currency($id) {
        $this->s_currency = $id;
    }

    public function select_transaction($id) {
        $this->s_transaction = $id;
    }

    public function select_user($id) {
        $this->s_user = $id;
    }

    public function select_filter($id) {
        $this->s_filter = $id;
    }

    public function select_suggestion($id) {
        $this->s_suggestion = $id;
    }

    public function get_json_result() {
        // TODO
    }
}

class Interpreter {

    // CODE ERROR LIST:
    const CERR_NONE                 = 0x00; // No error.
    const CERR_TRAILING_COMMA       = 0x01;
    const CERR_UNEXPECTED_TOKEN     = 0x02;
    const CERR_INVALID_SUGGESTION   = 0x03;
    const CERR_OBJECT_NOT_FOUND     = 0x04;
    const CERR_OBJECT_AMBIGUOUS     = 0x05;
    const CERR_METHOD_UNIMPLEMENTED = 0x06;
    const CERR_INVALID_STRING       = 0x07;

    var $parser;
    var $master;

    /// Interpret given commands and execute them. This will possibly change
    /// the database or other internal data.
    ///
    /// $cmdstr - a command string to interpret.
    ///
    /// RETURN reply to send back to the client.
    public function interpret($cmdstr) {
        try {
            $this->_interpret($cmdstr);
            echo $master->get_json_result();
        } catch (InterpretError $e) {
            echo wrap_error($e);
        }
    }

    private function _interpret($cmdstr) {
        $this->parser = new Parser($cmdstr);
        $this->master = new Master();

        while (true) {
            // TODO: unpimplemented!
            $this->parser->skip_garbage();
            $index_backup = $this->parser->index;

            $val = $this->parser->next_word();
            if ($val == WORD_SUGGEST) {
                interpret_cmd(true);
            } elseif ($val == NULL) {
                // TODO return error
                break;
            } elseif ($val == FALSE) {
                $val = $this->parser->next_symbol();
                if ($val == COMMA) {
                    InterpretError::cerr(CERR_TRAILING_COMMA);
                } elseif ($val == SEMICOLON) {
                    // End of command
                    break;
                } else {
                    InterpretError::cerr(CERR_UNEXPECTED_TOKEN);
                }
            } else {
                interpret_cmd(false);
            }
        }
    }

    /// Wrap the error into the message that can be send back to the
    /// client.
    ///
    /// RETURN: string to send to the client.
    private function wrap_error($e) {
        $msg = '{ "status":"';
        if ($e->from == InterpretError::INTERP_ERROR) {
            $msg .= 'cerr';
        } elseif ($e->from == InterpretError::MASTER_ERROR) {
            $msg .= 'xerr';
        } else {
            // ERROR - this branch is an unreachable code.
            $msg .= 'err';
        }
        $mgs .= '",\n"type":"' . $e->type . '",\n';
        $msg .= '"data":"' . $e->data . '"}'

        return $msg;
    }

    private function interpret_cmd($is_suggestion) {
        $this->parser->skip_garbage();
        $val = $this->parser->next_word();
        // TODO
        switch ($val) {
            case WORD_SELECT:

                expect_no_suggestion($is_suggestion);
                interpret_cmd_select();
                break;

            case WORD_ABOUT:
                break;
            case WORD_CREATE:
                break;
            case WORD_DROP:
                break;
            case WORD_REVIVE:
                break;
            case WORD_TRANSACT:
                break;
            case WORD_FROM:
                break;
            case WORD_TO:
                break;
            case WORD_DESCRIBE:
                break;
            case WORD_MOVE:
                break;
            case WORD_RENAME:
                break;
            case WORD_ALLOW:
                break;
            case WORD_SUGGEST:
                break;
            case WORD_CONVERT:
                break;
            case WORD_SAVE:
                break;
            case WORD_FILTRATE:
                break;
            default:
                // TODO ERROR
        }
    }

    // Expect no suggestion.
    // Otherwise, create invalid suggestion error.
    private function expect_no_suggestion($s) {
        if ($s) {
            // Error - cannot be suggested.
            InterpretError::cerr(CERR_INVALID_SUGGESTION);
        }
    }

    private function interpret_cmd_select() {
        $this->parser->skip_garbage();
        $val = $this->parser->next_word();
        switch ($val) {
            case WORD_FUND:

                $id = $this->find_object_fund(WORD_FUND);
                $this->master->select_fund($id);
                break;

            case WORD_CURRENCY:

                $id = $this->find_object_fund(WORD_CURRENCY);
                $this->master->select_currency($id);
                break;

            case WORD_TRANSACTION:

                $id = $this->find_object_fund(WORD_TRANSACTION);
                $this->master->select_transaction($id);
                break;

            case WORD_USER:

                $id = $this->find_object_fund(WORD_USER);
                $this->master->select_user($id);
                break;

            case WORD_FILTER:

                $id = $this->find_object_fund(WORD_FILTER);
                $this->master->select_filter($id);
                break;

            case WORD_SUGGESTION:

                $id = $this->find_object_fund(WORD_SUGGESTION);
                $this->master->select_suggestion($id);
                break;

            default:
                InterpretError::cerr(CERR_UNEXPECTED_TOKEN);
        }
    }

    /// Use for "SELECT" command.
    ///
    /// $type - type of the object to look for.
    ///
    /// Return:
    /// ID when object was found.
    private function find_object($type) {
        $this->parser->skip_garbage();
        $tok = $this->parser->next_string();

        // Check if string is valid
        if ($tok == NULL) {
            // Error - syntax error.
            InterpretError::cerr(CERR_INVALID_STRING);
        } elseif ($tok != FALSE) {
            // IF $tok == some string
            $obj_name = $tok; // Our token is a object name string.
            return find_object_by_name($obj_name, $type);
        } // else...

        // Must be given object ID number.
        $tok = $this->parser->next_number();
        if ($tok == FALSE) { // If not a number...
            // Error - unexpected token.
            InterpretError::cerr(CERR_UNEXPECTED_TOKEN);
        }
        $obj_id = $tok;

        // Check if object exists
        $is_found = find_object_by_id($obj_id);
        if (!$is_found) {
            InterpretError::cerr(CERR_OBJECT_NOT_FOUND);
        }

        return $obj_id;
    }

    /// Look for object with given name.
    ///
    /// Return:
    /// FALSE when several objects with given name was found.
    /// NULL  when no objects was found
    /// ID    when object was found.
    private function find_object_by_name($obj_str, $type) {
        return 0; // TODO
    }

    /// Check if this object exists.
    ///
    /// RETURN true if found and false otherwise.
    private function find_fund_by_id($id, $type) {
        return FALSE; // TODO
    }
}

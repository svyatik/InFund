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

/// Parse all tokens into compiled interpreter byte-code. If there is syntax
/// error, Compiler will raise an error.
class Compiler {

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

    private function Compiler($cmdstr) {
        $this->cmdstr = $cmdstr;
        $this->index = 0;
    }

    // Test if particular character can be part of the word token.
    private static function isWordPart($char) {
        return ctype_alpha($char[0]); // Test only first character.
    }

    /// Compile a command list into byte code.
    ///
    /// $cmdstr - string with commands to parse and compile.
    public static function compile($cmdstr) {
        $compiler = new Compiler($cmdstr);
        // TODO: unimplemented!
    }
}

class Interpreter {

    /// Interpret given commands and execute them. This will possibly change
    /// the database or other internal data.
    ///
    /// $cmdstr - a command string to interpret.
    public function interpret($cmdstr) {
        // TODO: unimplemented!
    }
}

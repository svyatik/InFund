<?php

/// Class represents a single token from the received command string.
class Token {

    /// Token types
    const WORD      = 1 << 0;
    const NUMBER    = 1 << 1;
    const DATE_TIME = 1 << 2;
        // Add more when needed

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

/// Parse all tokens into one complete tree. If there is syntax error,
/// Tokenizer will raise error.
class Tokenizer {

    /// Build a token tree of given command.
    ///
    ///$str - string with commands to parse.
    public static function buildTree($str) {
        // TODO: unimplemented!
    }
}

class Interpreter {

    /// Interpret some command and execute it. This will possibly change
    /// the database or other internal data.
    ///
    /// $cmd - a command list to interpret.
    public function interpret($cmd) {
        // TODO: unimplemented!
    }
}

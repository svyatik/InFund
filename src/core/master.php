<?php

/// Class represents a single token from the received command string.
class Token {
    var $data;
    var $type;

    public function Token($data, $type) {
        $this->data = $data;
        $this->type = $type;
    }

    /// Data is a string with actual read token.
    public function data() {
        return $this->data;
    }

    /// Type is a token type from an enumeration of types.
    public function type() {
        return $this->type;
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

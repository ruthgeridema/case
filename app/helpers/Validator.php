<?php

class Validator {
    /**
     * Validate a required field
     * @param any $var
     * @return string|bool;
     */
    public function required($var) {
        if(!isset($var) || is_null($var) || strlen($var) == 0) {
            return ' is vereist.';
        }

        return false;
    }

    /**
     * Validate the given email address
     *
     * @param string $email
     * @return string|bool
     */
    public function email($email) {
        if(!(filter_var($email, FILTER_VALIDATE_EMAIL)) || !checkdnsrr(array_reverse(explode('@', $email))[0], 'MX')) {
            return ' is ongeldig.';
        }

        return false;
    }

    /**
     * Validate the given phone number
     *
     * @param string $number
     * @return string|bool
     */
    public function number($number) {
        if(!preg_match('/^[0-9]{8}$/', $number)) {
            return ' is ongeldig.';
        }

        return false;
    }

    /**
     * Validate the strength of a given password
     *
     * @param string $password
     * @return string|bool
     */
    public function password($password) {
        if(!preg_match('((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20})', $password)) {
            unset($_POST['password']);
            return ' vereist tenminste één cijfer, één letter, één hoofdletter en minimaal 6 karakters.';
        }

        return false;
    }
    
    /**
     * Validate a postcode
     * 
     * @param string $postcode
     * @return string|bool
     */
    public function postcode($postcode) {
        $client = new \FH\PostcodeAPI\Client(new \GuzzleHttp\Client(), POSTCODE_API_KEY);

        $client = $client->getAddresses($postcode, $_POST['house_number']);

        if(count($client->_embedded->addresses) == 0) {
            return ' in combinatie met huisnummer is ongeldig.';
        }

        return false;
    }
    
}
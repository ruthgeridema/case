<?php

class UserController {
    /**
     * Validator
     */
    protected $validator = [
        'initials'       => 'required',
        'first_name'     => 'required',
        'last_name'      => 'required',
        'postcode'       => 'required|postcode',
        'house_number'   => 'required',
        'email'          => 'required|email',
        'phone_number'   => 'required|number',
        'password'       => 'required|password'
    ];

    /**
     * Return all users
     *
     */
    public function all() {
        return MysqliDb::getInstance()->get('users');
    }


    /**
     * Create the user
     */
    public function create() {
        $data       = $_POST;
        $error_bag  = [];

        // validate model
        foreach($this->validator as $field => $validations) {
            $validations = explode('|', $validations);

            foreach($validations as $validation) {
                if($msg = Validator::$validation($data[$field])) {
                    $error_bag[] = $field . $msg;
                }
            }
        }

        if(substr($data['initials'], 0, 1) != substr($data['first_name'], 0, 1)) {
            $error_bag[] = 'Eerste voorletter moet gelijk zijn aan de eerste letter van de voornaam.';
        }

        if(count($error_bag) > 0) {
            return $error_bag;
        }

        unset($_POST);


        $client = new \FH\PostcodeAPI\Client(new \GuzzleHttp\Client(), POSTCODE_API_KEY);
        $client = $client->getAddresses($data['postcode'], $data['house_number']);

        $data['city']           = $client->_embedded->addresses[0]->city->label;
        $data['street_name']    = $client->_embedded->addresses[0]->nen5825->street;
        $data['province']       = $client->_embedded->addresses[0]->province->label;


        // hash password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        MysqliDb::getInstance()->insert('users', $data);

        return 'Gebruiker succesvol aangemaakt.';
    }
}
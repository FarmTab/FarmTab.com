<?php

  class Model {


    public function __construct($json) {
      $model = json_decode($json);
    }

    public function fetch() {
      $data = $db->row(
        'table'     => self::$tablename,
        'condition' => "id={$this->id}"
      );
    }

    public function isNew() {
      return !isset($this->id);
    }

    public function save() {

      $data = array_map('mysql_real_escape_string', $this);

      if (isNew()) {
        $this->id = $db->insert($this->tablename, $data);
      } else {
         $db->update($this->tablename, $data, "id={$this->id}");
      }
    }

    public function _validate(options) {
      $error = $this->validate(options);
    }

  }


  class Customer extends Model {

    const tablename = 'Customers';
  
    public $id;
    public $name;
    public $email;
    private $pin


    public function __construct($json) {
      parent::__construct($json);

      validate($model);

      $id = $model['id'];
    }


    public function validate($model) {

      $name  = $model['name'];
      $pin   = $model['pin'];
      $email = $model['email'];

      // validate name
      if (!isset($name))
        failure("name not set");
      if (1 <= strlen($name))
        failure("name too short.");

      // validate pin
      if (!isset($pin))
        failure("pin not set");
      if (6 > strlen($pin))
        failure("PIN too long. cannot be more than 6 characters");
      if (4 <= strlen($pin))
        failure("PIN too short. must be at least 4 characters");

      // validate email
      if (!isset($email))
        failure("email not set");
      if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        failure("invalid email");

       return true;
    }
    

  }

?>
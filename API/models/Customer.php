<?php

  class Customer extends ActiveRecord\Model {

    static $attr_accessible = array(
      array('name'), array('email')
    );

    static $attr_protected = array(
      array('crypted_pin'), array('salt')
    );

    static $validates_presence_of = array(
      array('name'), array('crypted_pin'), array('email')
    );

    static $validates_uniqueness_of = array(
      array('email')
    );

    static $before_save = array( 'setup_x_tab' );


    static $has_many = array(
      array('tabs'),
      array('transactions')
    );
    
    static $belongs_to = array(
      array('farm', 'primary_key' => 'farm_id')
    );


    public function set_pin($new_pin) {
      if (!is_int($new_pin))
        failure("PIN must contain only numbers");
      if (strlen($new_pin) < 4)
        failure("PIN must be at least 4 characters");
      if (strlen($new_pin) > 6)
        failure("PIN cannot be more than 6 characters");


      $this->salt = utils::generate_salt();
      $this->crypted_pin = utils::make_password($pin, $salt);
    }

    
    public function check_pin($test_pin) {
      return ($this->crypted_pin == utils::make_password($test_pin, $this->salt));
    }

    public function setup_x_tab() {

      $farmId = $_SESSION['farm']->id;

      $db->insert('farm_x_user', array(
          'farm_id' => $farmId,
          'user_id' => $userId
      ));
      
      $tab = new Tab(array(
          'farm_id' => $farmId,
          'user_id' => $this->id,
          'balance' => "0.00"
      ));
      
      $db->insert('user_x_tab', array(
          'user_id' => $this->id,
          'tab_id' => $tab->id
      ));
      
      if (mysql_error())
        failure("Couldn't insert into db: " . mysql_error());

    }

  }

?>
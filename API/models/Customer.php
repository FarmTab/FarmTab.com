<?php

  class Customer extends ActiveRecord\Model {

    static $validates_presence_of = array(
      array('name'), array('pin'), array('email')
    );

    static $validates_length_of = array(
      array('pin', 'within' => array(4,6),
        'too_short' => "PIN must be at least 4 characters",
        'too_long'  => "PIN must cannot be more than 6 characters"
      )
    );

    static $validates_numericality_of = array(
      array('pin', 'only_integer' => true)
    );

    static $validates_uniqueness_of = array(
      array('email')
    );


    public function __construct($json) {
      parent::__construct($json);

      validate($model);

      $id = $model['id'];
    }

  }

?>
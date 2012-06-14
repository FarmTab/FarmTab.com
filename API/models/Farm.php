<?php

  class Farm extends ActiveRecord\Model {
  
    static $validates_presence_of = array(
      array('email'), array('farm_name')
    );

    static $has_many = array(
      array('customers'), array('venues')
    );

  }

?>
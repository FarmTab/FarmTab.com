<?php

  class Farm extends ActiveRecord\Model {
  
    static $validates_presence_of = array(
      array('email'), array('farm_name')
    );

    static $has_many = array(
      array('customers'), array('venues')
    );
    
    function customers() {
      $data = array();      
      
      foreach (Customer::find_all_by_farm_id($this->id) as $customer) {
        $data[] = $customer->to_array(array(
          'only' => array('id','name','balance','img_url')
        ));
      }
      
      return $data;
    }

  }

?>
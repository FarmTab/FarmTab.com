<?php

  class Transaction extends ActiveRecord\Model {
    
    static $belongs_to = array(
      array('customer')
    );

    static $validates_presence_of = array (
      array('amount'), array('farm_id'), array('user_id')
    );

  }

?>
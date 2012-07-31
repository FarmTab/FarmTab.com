class Customer < ActiveRecord::Base
  attr_accessible :name, :email

  attr_protected :crypted_pin
  validates_presence_of :name, :crypted_pin, :email
  validates_uniqueness_of :email

  before_save :setup_x_tab

  has_many :tabs, :transactions
  has_and_belongs_to_many :farms

  def setup_x_tab
    Tab.find_or_create_by_customer(farm: :current_farm)
  end

end

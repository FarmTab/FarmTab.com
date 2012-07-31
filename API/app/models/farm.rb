class Farm < ActiveRecord::Base
  validates_presence_of :email, :farm_name

  has_many :customers
end

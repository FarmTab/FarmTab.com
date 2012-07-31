class Tab < ActiveRecord::Base
  belongs_to_many :customers

  before_save :insert_transaction_record


  def insert_transaction_record
    Transaction.new(message: serialize(balance.changes))
  end
end

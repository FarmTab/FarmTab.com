class CreateVenues < ActiveRecord::Migration
  def change
    create_table :venues do |t|

      t.timestamps
    end
  end
end

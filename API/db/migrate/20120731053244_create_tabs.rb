class CreateTabs < ActiveRecord::Migration
  def change
    create_table :tabs do |t|

      t.timestamps
    end
  end
end

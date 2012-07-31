# encoding: UTF-8
# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended to check this file into your version control system.

ActiveRecord::Schema.define(:version => 0) do

  create_table "api_clients", :primary_key => "api_key", :force => true do |t|
    t.string "client_name", :null => false
  end

  create_table "customer_x_tab", :id => false, :force => true do |t|
    t.integer "customer_id", :null => false
    t.integer "tab_id",      :null => false
  end

  create_table "customer_x_transaction", :id => false, :force => true do |t|
    t.integer "customer_id",    :null => false
    t.integer "transaction_id", :null => false
  end

  create_table "customers", :force => true do |t|
    t.string  "name",                                                       :null => false
    t.integer "farm_id",                                                    :null => false
    t.string  "email",                                                      :null => false
    t.decimal "balance",                     :precision => 10, :scale => 2, :null => false
    t.string  "pin",           :limit => 48,                                :null => false
    t.string  "salt",          :limit => 15,                                :null => false
    t.string  "phone",                                                      :null => false
    t.string  "fb_id",                                                      :null => false
    t.string  "fb_token",      :limit => 64,                                :null => false
    t.string  "twitter_id",    :limit => 24,                                :null => false
    t.string  "twitter_token", :limit => 64,                                :null => false
    t.string  "fsq_id",                                                     :null => false
    t.string  "fsq_token",     :limit => 64,                                :null => false
    t.string  "img_url",                                                    :null => false
  end

  add_index "customers", ["email"], :name => "email", :unique => true

  create_table "farm_x_customer", :id => false, :force => true do |t|
    t.integer "farm_id",     :null => false
    t.integer "customer_id", :null => false
  end

  create_table "farm_x_inventory", :id => false, :force => true do |t|
    t.integer "farm_id",      :null => false
    t.integer "inventory_id", :null => false
  end

  create_table "farm_x_transaction", :id => false, :force => true do |t|
    t.integer "farm_id",        :null => false
    t.integer "transaction_id", :null => false
  end

  create_table "farm_x_venue", :id => false, :force => true do |t|
    t.integer "farm_id",  :null => false
    t.integer "venue_id", :null => false
  end

  create_table "farms", :force => true do |t|
    t.string   "email",                      :null => false
    t.string   "pass",         :limit => 48, :null => false
    t.string   "salt",         :limit => 15, :null => false
    t.string   "pin",                        :null => false
    t.string   "farm_name",                  :null => false
    t.string   "farm_address",               :null => false
    t.string   "description",                :null => false
    t.string   "website",                    :null => false
    t.string   "contact",                    :null => false
    t.string   "phone",                      :null => false
    t.integer  "schedule",                   :null => false
    t.float    "lat",          :limit => 10, :null => false
    t.float    "long",         :limit => 10, :null => false
    t.datetime "date_joined",                :null => false
  end

  add_index "farms", ["email"], :name => "email", :unique => true

  create_table "inventories", :force => true do |t|
    t.integer "item_id",      :null => false
    t.string  "farm_id",      :null => false
    t.string  "stock",        :null => false
    t.boolean "availability", :null => false
  end

  create_table "inventory_x_items", :id => false, :force => true do |t|
    t.integer "inventory_id", :null => false
    t.integer "items_id",     :null => false
  end

  create_table "inventory_x_venue", :id => false, :force => true do |t|
    t.integer "inventory_id", :null => false
    t.integer "venue_id",     :null => false
  end

  create_table "items", :force => true do |t|
    t.string  "name",        :null => false
    t.string  "description", :null => false
    t.integer "photo",       :null => false
    t.string  "ppu",         :null => false
    t.string  "unit_size",   :null => false
    t.string  "quantity",    :null => false
    t.string  "link",        :null => false
  end

  create_table "login_attempts", :force => true do |t|
    t.string    "email",              :limit => 256, :null => false
    t.string    "request_user_agent", :limit => 512, :null => false
    t.timestamp "request_timestamp",                 :null => false
    t.string    "request_ip",         :limit => 64,  :null => false
    t.boolean   "login_successful",                  :null => false
  end

  create_table "photos", :force => true do |t|
    t.string  "filename", :null => false
    t.integer "user",     :null => false
    t.integer "farm",     :null => false
  end

  create_table "schedule", :force => true do |t|
    t.string "M",  :null => false
    t.string "T",  :null => false
    t.string "W",  :null => false
    t.string "Th", :null => false
    t.string "F",  :null => false
    t.string "Sa", :null => false
    t.string "Su", :null => false
    t.string "H",  :null => false
  end

  create_table "tabs", :force => true do |t|
    t.integer "farm_id",                                :null => false
    t.integer "user_id",                                :null => false
    t.decimal "balance", :precision => 10, :scale => 2, :null => false
  end

  add_index "tabs", ["farm_id", "user_id"], :name => "UNIQUE", :unique => true

  create_table "transactions", :force => true do |t|
    t.string    "receipt_dump", :null => false
    t.string    "amount",       :null => false
    t.integer   "venue_id",     :null => false
    t.integer   "farm_id",      :null => false
    t.integer   "user_id",      :null => false
    t.timestamp "time",         :null => false
  end

  create_table "venues", :force => true do |t|
    t.string "venue_name",                  :null => false
    t.string "venue_address",               :null => false
    t.string "schedule",                    :null => false
    t.float  "lat",           :limit => 10, :null => false
    t.float  "long",          :limit => 10, :null => false
    t.string "social",                      :null => false
  end

end

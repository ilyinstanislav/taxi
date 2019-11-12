<?php

use yii\db\Migration;

/**
 * Class m191112_130737_start
 */
class m191112_130737_start extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE passengers (
                id INT(11) NOT NULL AUTO_INCREMENT,
                name VARCHAR(50) NOT NULL,
                phone VARCHAR(12) NOT NULL,
                PRIMARY KEY (id)
            )
            ENGINE = INNODB;
        ");

        $this->execute("
            CREATE TABLE trips (
                id INT(11) NOT NULL AUTO_INCREMENT,
                address VARCHAR(255) NOT NULL,
                date_start DATETIME NOT NULL,
                date_end DATETIME NOT NULL,
                PRIMARY KEY (id)
            )
            ENGINE = INNODB;
        ");

        $this->execute("
            CREATE TABLE orders (
              id INT(11) NOT NULL AUTO_INCREMENT,
              passenger_id INT(11) DEFAULT NULL,
              trip_id INT(11) DEFAULT NULL,
              created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (id)
            )
            ENGINE = INNODB;
            
            ALTER TABLE orders 
              ADD INDEX IDX_orders_passenger_id(passenger_id);
            
            ALTER TABLE orders 
              ADD UNIQUE INDEX UK_orders_trip_id(trip_id);
              
            ALTER TABLE orders 
              ADD CONSTRAINT FK_orders_passenger_id FOREIGN KEY (passenger_id)
                REFERENCES passengers(id) ON DELETE CASCADE;
            
            ALTER TABLE orders 
              ADD CONSTRAINT FK_orders_trip_id FOREIGN KEY (trip_id)
                REFERENCES trips(id) ON DELETE CASCADE;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191112_130737_start cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191112_130737_start cannot be reverted.\n";

        return false;
    }
    */
}

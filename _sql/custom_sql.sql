/*

    This file contains the extra SQL I've created that isn't part of the schema creation.

    Some of these tasks are easier/more appropriate to do in Laravel using Eloquent, so some of these scripts
    are a little unnecessary.

*/

-- Generates base categories
INSERT INTO `categories` (`id`, `name`, `slug`) VALUES (NULL, 'Drinks', 'drinks'), (NULL, 'Snacks', 'snacks'), (NULL, 'Alcohol-free', 'alcohol-free'), (NULL, 'Desserts', 'desserts');


-- Creates a stored procedure
/* Gets all items that have low stock (below stocklimit) */
CREATE PROCEDURE GetLowStock (stockLimit int(11))
	SELECT id, name, quantity FROM items
	WHERE items.quantity < stockLimit
	AND items.enabled = TRUE;

-- Calls the stored procedure with a parameter
CALL GetLowStock(10);


-- Stored procedure: counts how many of an Item are still waiting to be prepared/delivered to customers
CREATE PROCEDURE StockInQueue()
    SELECT order_items.item_id, items.name, SUM(order_items.quantity) AS "Total to dispatch"
    FROM order_items
    LEFT JOIN items ON items.id = order_items.item_id
    GROUP BY order_items.item_id

CALL StockInQueue();


-- Update stock after an order goes through
CREATE TRIGGER updateStock
BEFORE INSERT on order_items FOR EACH ROW
	UPDATE items
    SET items.quantity = items.quantity - new.quantity
    WHERE items.id = new.item_id


-- Update stock after an order goes through
CREATE TRIGGER disableEmptyStock
AFTER UPDATE on items FOR EACH ROW
	UPDATE items
    SET new.enabled = false
    WHERE new.quantity <= 0

-- These two triggers clash, bit like a circular reference?

-- Combining them so they work together
-- This would work if you have a connection with "allowMultipleQueries=true"
-- alas we don't
CREATE TRIGGER automateStock
AFTER INSERT on order_items FOR EACH ROW
BEGIN
    UPDATE items
    SET items.quantity = items.quantity - new.quantity
    WHERE items.id = new.item_id;

    UPDATE items
    SET items.enabled = false
    WHERE items.id = new.item_id
    AND new.quantity <= 0;
END

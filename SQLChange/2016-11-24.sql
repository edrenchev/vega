ALTER TABLE `clients` ADD `name` VARCHAR(255) NOT NULL AFTER `id`;

UPDATE clients SET name = CONCAT_WS(' ', NULLIF(first_name, ''), NULLIF(middle_name, ''), NULLIF(last_name, ''));
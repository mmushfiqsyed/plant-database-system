-- TRIGGER CREATION STATEMENTS --
USE plant_db;

-- Log deleted Users

DELIMITER //
CREATE TRIGGER after_user_delete
AFTER DELETE ON users
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (table_name, action_type, record_id, admin_id, log_details)
    VALUES ('users', 'DELETE', OLD.user_id, @current_admin_id, CONCAT('Deleted user: ', OLD.username));
END //

-- Log deleted plant_record
CREATE TRIGGER after_record_delete
AFTER DELETE ON plant_record
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (table_name, action_type, record_id, admin_id, log_details)
    VALUES ('plant_record', 'DELETE', OLD.record_id, @current_admin_id, 'Official record removed');
END //

DELIMITER ;
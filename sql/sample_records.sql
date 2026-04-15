-- Just some basic records to test the rest of the tables --
INSERT INTO plant_record (area_size, date_added, managed_by, region_id) 
VALUES (2500.00, CURDATE(), 1, 3);

INSERT INTO record_plants (record_id, plant_id) VALUES (1, 5);

INSERT INTO plant_reports (plant_name_suggested, description, status, submitted_by, region_id)
VALUES ('Sundari Tree Sighting', 'Found a cluster of healthy Sundari trees near the river bank.', 'Pending', 1, 5);
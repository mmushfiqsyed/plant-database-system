-- RAW DATA --
-- Region names--
INSERT INTO region(region_name) VALUES  (('Dhaka Division'),
('Chattogram Division'),
('Sylhet Division'),
('Rajshahi Division'),
('Khulna (Sundarbans Area)'),
('Barishal Division'),
('Rangpur Division'),
('Mymensingh Division'));

-- Sample plant data --
INSERT INTO plant (plant_name, species, category) VALUES 
('Mango Tree', 'Mangifera indica', 'Fruit Tree'),
('Jackfruit Tree', 'Artocarpus heterophyllus', 'Fruit Tree'),
('Shapla (Water Lily)', 'Nymphaea nouchali', 'Aquatic Herb'),
('Sundari', 'Heritiera fomes', 'Mangrove Tree'),
('Arjun', 'Terminalia arjuna', 'Medicinal Tree'),
('Krishnachura', 'Delonix regia', 'Ornamental Tree'),
('Jarul', 'Lagerstroemia speciosa', 'Timber Tree');

-- Sample "official" data --
INSERT INTO plant_record (area_size, date_added, managed_by, region_id) 
VALUES (2500.00, CURDATE(), 1, 3);
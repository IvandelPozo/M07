DELETE FROM categories;
INSERT INTO categories (id, nom, iva,createdAt,updatedAt) 
VALUES 
("f7ad7d25-4c1f-4aed-916b-e5b13c87acc3", 'Granos',"14",CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
("693d2a9e-d27b-443f-9fdb-c0bad36959b8", 'Verduras',"10",CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
("716eef81-8999-44db-8a49-aff76f1274f4", 'Frutas',"20",CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
("e5705888-e8d0-470d-8308-d093e593e0e6", 'Lacteos',"11",CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
("3db63c50-2055-4c02-8235-e3cc52d41a1b", 'Proteinas',"17",CURRENT_TIMESTAMP,CURRENT_TIMESTAMP);
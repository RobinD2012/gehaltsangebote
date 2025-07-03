CREATE TABLE orte (
  id INT AUTO_INCREMENT PRIMARY KEY,
  plz VARCHAR(10),
  ort VARCHAR(255),
  bundesland VARCHAR(100),
  lat DECIMAL(10,6),
  lon DECIMAL(10,6)
);

INSERT INTO orte (plz, ort, bundesland, lat, lon) VALUES
('10115', 'Berlin', 'Berlin', 52.532, 13.3849),
('20095', 'Hamburg', 'Hamburg', 53.5507, 10.0014),
('50667', 'Köln', 'Nordrhein-Westfalen', 50.9384, 6.9578),
('80331', 'München', 'Bayern', 48.1371, 11.5754),
('01067', 'Dresden', 'Sachsen', 51.0504, 13.7373),
('70173', 'Stuttgart', 'Baden-Württemberg', 48.7758, 9.1829),
('39104', 'Magdeburg', 'Sachsen-Anhalt', 52.1205, 11.6276),
('28195', 'Bremen', 'Bremen', 53.0786, 8.8017),
('99084', 'Erfurt', 'Thüringen', 50.9787, 11.0328),
('17489', 'Greifswald', 'Mecklenburg-Vorpommern', 54.0934, 13.3874);

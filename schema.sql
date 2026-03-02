-- Create database
CREATE DATABASE IF NOT EXISTS staywise_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE staywise_db;

-- Roles
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  description VARCHAR(255)
);

-- Users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Hotels
CREATE TABLE hotels (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(160) NOT NULL UNIQUE,
  city VARCHAR(100) NOT NULL,
  state VARCHAR(100),
  country VARCHAR(100) NOT NULL,
  address VARCHAR(200) NOT NULL,
  description TEXT,
  rating DECIMAL(2,1) DEFAULT 4.5,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rooms
CREATE TABLE rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hotel_id INT NOT NULL,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(160) NOT NULL UNIQUE,
  description TEXT,
  price_per_night DECIMAL(10,2) NOT NULL,
  capacity INT NOT NULL DEFAULT 2,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (hotel_id) REFERENCES hotels(id),
  INDEX (hotel_id),
  INDEX (is_active)
);

-- Room images
CREATE TABLE room_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_id INT NOT NULL,
  url VARCHAR(255) NOT NULL,
  alt_text VARCHAR(150),
  is_primary TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (room_id) REFERENCES rooms(id),
  UNIQUE KEY uniq_primary (room_id, is_primary)
);

-- Amenities
CREATE TABLE amenities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  icon VARCHAR(120)
);

-- Room amenities (pivot)
CREATE TABLE room_amenities (
  room_id INT NOT NULL,
  amenity_id INT NOT NULL,
  PRIMARY KEY (room_id, amenity_id),
  FOREIGN KEY (room_id) REFERENCES rooms(id),
  FOREIGN KEY (amenity_id) REFERENCES amenities(id)
);

-- Guests
CREATE TABLE guests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  phone VARCHAR(30),
  notes VARCHAR(255),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Bookings
CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  room_id INT NOT NULL,
  check_in DATE NOT NULL,
  check_out DATE NOT NULL,
  nights INT NOT NULL,
  status ENUM('pending','confirmed','cancelled','completed','refunded') NOT NULL DEFAULT 'confirmed',
  total DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (room_id) REFERENCES rooms(id),
  INDEX (check_in),
  INDEX (check_out)
);

-- Payments
CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL,
  method ENUM('card','paypal','bank') NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  status ENUM('initiated','authorized','captured','failed','refunded') NOT NULL DEFAULT 'captured',
  transaction_ref VARCHAR(120),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

-- Seed roles and users (hashed passwords recommended)
INSERT INTO roles (name, description) VALUES
('admin','Administrator'),
('guest','Guest user');

-- Example user hashes (replace via seed script if needed)
INSERT INTO users (role_id, name, email, password_hash) VALUES
(1, 'Admin', 'admin@staywise.local', '$2y$10$yP0ZgOe2ExampleHash1234567890abcdefghijklmnopQRSTUV'), -- replace
(2, 'Jane Guest', 'jane@example.com', '$2y$10$yP0ZgOe2ExampleHash1234567890abcdefghijklmnopQRSTUV'); -- replace

INSERT INTO hotels (name, slug, city, state, country, address, description, rating) VALUES
('Downtown Suites', 'downtown-suites', 'Oklahoma City', 'OK', 'USA', '100 Main St', 'Modern suites in the heart of the city.', 4.6),
('Airport Inn', 'airport-inn', 'Oklahoma City', 'OK', 'USA', '200 Terminal Ave', 'Convenient stay near the airport.', 4.3);

INSERT INTO rooms (hotel_id, name, slug, description, price_per_night, capacity, is_active) VALUES
(1, 'Queen Suite', 'queen-suite', 'Spacious queen bed suite.', 119.00, 2, 1),
(1, 'Family Room', 'family-room', 'Two beds perfect for families.', 149.00, 4, 1),
(2, 'Business King', 'business-king', 'Comfortable king bed ideal for business trips.', 109.00, 2, 1);

INSERT INTO amenities (name, icon) VALUES
('Free Wi-Fi','wifi'),('Breakfast','coffee'),('Pool','pool'),('Gym','dumbbell'),('Parking','parking');

INSERT INTO room_amenities (room_id, amenity_id) VALUES
(1,1),(1,2),(1,5),(2,1),(2,2),(2,3),(3,1),(3,5);

INSERT INTO room_images (room_id, url, alt_text, is_primary) VALUES
(1,'/assets/images/rooms/queen.jpg','Queen Suite',1),
(2,'/assets/images/rooms/family.jpg','Family Room',1),
(3,'/assets/images/rooms/king.jpg','Business King',1);

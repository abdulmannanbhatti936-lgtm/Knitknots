CREATE DATABASE IF NOT EXISTS knitknots;
USE knitknots;
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE
    SET NULL
);
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    payment_method ENUM('jazzcash', 'easypaisa', 'whatsapp') DEFAULT 'whatsapp',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    order_status ENUM(
        'new',
        'processing',
        'shipped',
        'delivered',
        'cancelled'
    ) DEFAULT 'new',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE
    SET NULL
);
CREATE TABLE custom_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    description TEXT NOT NULL,
    reference_image VARCHAR(255),
    budget VARCHAR(50),
    status ENUM('new', 'reviewed', 'accepted', 'rejected') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO categories (name, slug)
VALUES ('Keychains', 'keychains'),
    ('Flowers', 'flowers'),
    ('Home Decor', 'home-decor'),
    ('Custom Orders', 'custom');
INSERT INTO products (category_id, name, description, price, image, stock, is_featured)
VALUES 
    (1, 'Spiderman Keychain', 'Handmade crochet Spiderman keychain, super cute!', 350.00, 'products/image1.png', 10, 1),
    (1, 'Batman Keychain', 'The Dark Knight in crochet form.', 350.00, 'products/image2.png', 8, 1),
    (1, 'Strawberry Keychain', 'Adorable crochet strawberry with daisy charm', 300.00, 'products/image3.png', 12, 1),
    (1, 'Bunny Keychain', 'White bunny with carrot - kawaii style', 350.00, 'products/image4.png', 6, 1),
    (1, 'Turtle Keychain', 'Cute green sea turtle crochet.', 400.00, 'products/image5.png', 5, 0),
    (1, 'Duck Keychain', 'Little yellow ducky with a tiny hat.', 380.00, 'products/image6.png', 7, 0),
    (1, 'Bear Character', 'Fluffy brown bear keychain.', 450.00, 'products/image7.png', 4, 1),
    (1, 'Pikachu Keychain', 'Electric mouse in yarn form.', 500.00, 'products/image8.png', 3, 0),
    (1, 'Cat Keychain', 'Calico cat crochet keychain.', 350.00, 'products/image9.png', 9, 0),
    (1, 'Minion Keychain', 'One-eyed yellow minion.', 400.00, 'products/image10.png', 5, 0),
    (2, 'Sunflower Pot', 'Crochet sunflower in mini handmade pot', 600.00, 'products/image11.png', 5, 1),
    (2, 'Tulip Pot', 'Red tulip in crochet pot - lasts forever', 550.00, 'products/image12.png', 5, 1),
    (2, 'Daisy Pot', 'White daisy with yellow center.', 500.00, 'products/image13.png', 6, 0),
    (2, 'Lily Pot', 'Elegant white lily in a brown pot.', 700.00, 'products/image14.png', 3, 1),
    (2, 'Lavender Bundle', 'Purple lavender stalks in a vase.', 850.00, 'products/image15.png', 2, 0),
    (2, 'Rose Pot', 'Deep red rose pot - perfect for gifting.', 650.00, 'products/image16.png', 4, 1),
    (2, 'Succulent Pot', 'Cute crochet succulent that never needs water.', 450.00, 'products/image17.png', 10, 0),
    (2, 'Peony Pot', 'Large pink peony in a ceramic-style crochet pot.', 900.00, 'products/image18.png', 2, 0),
    (2, 'Cactus Trio', 'Three tiny cacti in one long pot.', 1200.00, 'products/image19.png', 1, 0),
    (2, 'Cherry Blossom', 'Beautiful pink cherry blossom branch.', 1100.00, 'products/image20.png', 3, 0),
    (3, 'Sunflower Coaster Set', 'Beautiful sunflower coaster set', 400.00, 'products/image21.png', 10, 1),
    (3, 'Sage Green Wall Hanging', 'Macrame-style crochet wall hanging.', 2500.00, 'products/image22.png', 2, 1),
    (3, 'Mug Cozy', 'Keep your tea warm with this cozy rose wrap.', 350.00, 'products/image23.png', 8, 0),
    (3, 'Boho Mirror Frame', 'Crochet frame for a small round mirror.', 1800.00, 'products/image24.png', 3, 0),
    (3, 'Premium Table Runner', 'Lace-style intricate table runner.', 4500.00, 'products/image24pfp.png', 1, 0);
INSERT INTO admin_users (username, password_hash)
VALUES (
        'admin',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
    );
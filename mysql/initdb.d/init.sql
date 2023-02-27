CREATE TABLE user.users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) COLLATE utf8mb4_unicode_ci,
    email VARCHAR(191) UNIQUE COLLATE utf8mb4_unicode_ci,
    password VARCHAR(191) COLLATE utf8mb4_unicode_ci
) COLLATE utf8mb4_unicode_ci;
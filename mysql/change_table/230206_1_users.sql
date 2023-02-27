ALTER TABLE
    users
MODIFY
    user_name VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci,
MODIFY
    email VARCHAR(191) NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci,
MODIFY
    password VARCHAR(191) NOT NULL COLLATE utf8mb4_unicode_ci;
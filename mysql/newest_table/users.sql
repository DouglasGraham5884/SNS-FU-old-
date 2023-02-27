CREATE TABLE sns.users (
    user_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_name VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci,
    email VARCHAR(191) NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci,
    password VARCHAR(191) NOT NULL COLLATE utf8mb4_unicode_ci,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    description TEXT COLLATE utf8mb4_unicode_ci,
    profile_picuture_path TEXT,
    PRIMARY KEY(user_id)
) COLLATE utf8mb4_unicode_ci;
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

CREATE TABLE sns.posts (
    post_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT(11) UNSIGNED NOT NULL,
    user_name VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci,
    title VARCHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci,
    message TEXT NOT NULL,
    file_type_01 VARCHAR(32),
    file_type_02 VARCHAR(32),
    file_type_03 VARCHAR(32),
    file_type_04 VARCHAR(32),
    file_path_01 VARCHAR(511),
    file_path_02 VARCHAR(511),
    file_path_03 VARCHAR(511),
    file_path_04 VARCHAR(511),
    created_at DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY(data_id)
) COLLATE utf8mb4_unicode_ci;

CREATE TABLE sns.images (
    image_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    post_id INT UNSIGNED NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) UNIQUE NOT NULL,
    description VARCHAR(140) COLLATE utf8mb4_unicode_ci,
    uploaded_at DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY(image_id)
) COLLATE utf8mb4_unicode_ci;

CREATE TABLE sns.logs (
    log_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id SMALLINT UNSIGNED,
    user_name VARCHAR(64) COLLATE utf8mb4_unicode_ci,
    action ENUM("login", "logout", "insert", "delete", "update") NOT NULL,
    target ENUM("user", "post", "file") NOT NULL,
    result ENUM("success", "fail") NOT NULL,
    post_id INT UNSIGNED,
    at DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY(log_id)
) COLLATE utf8mb4_unicode_ci;
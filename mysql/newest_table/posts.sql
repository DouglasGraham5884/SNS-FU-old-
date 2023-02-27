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
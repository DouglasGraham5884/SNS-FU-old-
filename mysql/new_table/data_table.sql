CREATE TABLE sns.data (
    data_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT(11) UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    media_type TEXT,
    media_path_01 TEXT,
    media_path_02 TEXT,
    media_path_03 TEXT,
    media_path_04 TEXT,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY(data_id)
) COLLATE utf8mb4_unicode_ci;
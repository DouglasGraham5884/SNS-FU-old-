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
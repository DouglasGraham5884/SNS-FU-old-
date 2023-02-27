ALTER TABLE
    data
ADD
    user_name VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci
AFTER
    user_id;
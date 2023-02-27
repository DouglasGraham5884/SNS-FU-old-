ALTER TABLE
    data
ADD
    title VARCHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci
AFTER
    user_name;
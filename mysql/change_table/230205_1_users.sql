ALTER TABLE
    users
ADD
    created_at DATETIME NOT NULL DEFAULT NOW()
AFTER
    password;
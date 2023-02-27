ALTER TABLE
    data
RENAME TO
    posts;

ALTER TABLE
    posts
RENAME COLUMN
    data_id
TO
    post_id;
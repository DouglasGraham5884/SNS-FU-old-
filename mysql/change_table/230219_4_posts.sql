ALTER TABLE
    posts
CHANGE
    file_type
    file_type_01 VARCHAR(32);

ALTER TABLE
    posts
ADD
    file_type_02 VARCHAR(32)
AFTER
    file_type_01,
ADD
    file_type_03 VARCHAR(32)
AFTER
    file_type_02,
ADD
    file_type_04 VARCHAR(32)
AFTER
    file_type_03;
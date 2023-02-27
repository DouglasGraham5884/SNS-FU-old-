ALTER TABLE
    posts
CHANGE
    media_type
    file_type VARCHAR(64),
CHANGE
    media_path_01
    file_path_01 VARCHAR(511),
CHANGE
    media_path_02
    file_path_02 VARCHAR(511),
CHANGE
    media_path_03
    file_path_03 VARCHAR(511),
CHANGE
    media_path_04
    file_path_04 VARCHAR(511);
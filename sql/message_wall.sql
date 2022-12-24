-- Table to store message wall threads
CREATE TABLE message_wall (
	mw_thread_id INT NOT NULL PRIMARY KEY,
	mw_parent_id INT NULL,
	mw_user_id INT NOT NULL,
	mw_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	mw_latest_rev_id INT NOT NULL
);

-- Index on message wall thread user IDs
CREATE INDEX message_wall_user_id_idx ON message_wall (mw_user_id);

-- Index on message wall thread parent IDs
CREATE INDEX message_wall_parent_id_idx ON message_wall (mw_parent_id);

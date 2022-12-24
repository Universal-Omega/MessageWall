-- Table to store message wall messages
CREATE TABLE message_wall (
	mw_username VARCHAR(255) NOT NULL,
	mw_user_id INT NOT NULL,
	mw_user_name VARCHAR(255) NOT NULL,
	mw_message TEXT NOT NULL,
	mw_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (mw_username, mw_timestamp)
);

-- Index on message wall user IDs
CREATE INDEX message_wall_user_id_idx ON message_wall (mw_user_id);

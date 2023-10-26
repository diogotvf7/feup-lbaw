SET
    search_path TO lbaw2326;

DROP INDEX IF EXISTS search_question_body;

DROP INDEX IF EXISTS search_question_title;

DROP INDEX IF EXISTS search_tag_description;

DROP INDEX IF EXISTS search_tag_name;

DROP INDEX IF EXISTS vote_type;

DROP INDEX IF EXISTS most_recent_version;

CREATE INDEX most_recent_version ON content_version USING btree (date DESC NULLS LAST);

CREATE INDEX vote_type ON vote USING hash (is_upvote);

CREATE INDEX search_tag_name ON tag USING GIN (search_tag_name);

CREATE INDEX search_tag_description ON tag USING GIN (search_tag_description);

CREATE INDEX search_question_title ON question USING GIN (search_title);

CREATE INDEX search_question_body ON content_version USING GIST (search_body);
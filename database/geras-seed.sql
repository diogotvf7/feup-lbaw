DROP SCHEMA IF EXISTS lbaw2326 CASCADE;

CREATE SCHEMA IF NOT EXISTS lbaw2326;

SET
    search_path TO lbaw2326;

-----------------------------------------
-- Drop old schema
-----------------------------------------
DROP TABLE IF EXISTS followed_users CASCADE;

DROP TABLE IF EXISTS followed_tags CASCADE;

DROP TABLE IF EXISTS followed_questions CASCADE;

DROP TABLE IF EXISTS users CASCADE;

DROP TABLE IF EXISTS tags CASCADE;

DROP TABLE IF EXISTS badges CASCADE;

DROP TABLE IF EXISTS questions CASCADE;

DROP TABLE IF EXISTS answers CASCADE;

DROP TABLE IF EXISTS comments CASCADE;

DROP TABLE IF EXISTS content_versions CASCADE;

DROP TABLE IF EXISTS question_tag CASCADE;

DROP TABLE IF EXISTS annexes CASCADE;

DROP TABLE IF EXISTS votes CASCADE;

DROP TABLE IF EXISTS notifications CASCADE;

DROP TABLE IF EXISTS badge_user CASCADE;

DROP TABLE IF EXISTS faq CASCADE;

DROP TYPE IF EXISTS notification_type CASCADE;

DROP TYPE IF EXISTS file_type CASCADE;

DROP TYPE IF EXISTS content_type CASCADE;

DROP TYPE IF EXISTS main_content_type CASCADE;

DROP TYPE IF EXISTS user_status_type CASCADE;

-----------------------------------------
-- Types
-----------------------------------------
CREATE TYPE notification_type AS ENUM ('ANSWER', 'UPVOTE', 'BADGE');

CREATE TYPE file_type AS ENUM ('FILE', 'IMAGE');

CREATE TYPE content_type AS ENUM ('QUESTION', 'ANSWER', 'COMMENT');

CREATE TYPE main_content_type AS ENUM ('QUESTION', 'ANSWER');

CREATE TYPE user_status_type AS ENUM ('Admin', 'User', 'Banned');
-----------------------------------------
-- Tables
-----------------------------------------
-- Note that plural names 'users' and 'comments' were adopted because of reserved words in PostgreSQL.
CREATE TABLE
    users (
        id SERIAL PRIMARY KEY,
        email TEXT NOT NULL CONSTRAINT user_email_uk UNIQUE,
        name TEXT,
        username TEXT NOT NULL,
        password TEXT,
        profile_picture TEXT,
        experience INTEGER DEFAULT 0,
        score INTEGER DEFAULT 0,
        member_since DATE DEFAULT now (),
        type user_status_type DEFAULT 'User',
        google_id TEXT
    );

CREATE TABLE
    tags (
        id SERIAL PRIMARY KEY,
        name TEXT NOT NULL UNIQUE,
        description TEXT NOT NULL,
        approved BOOLEAN DEFAULT FALSE,
        creator INTEGER REFERENCES users (id) ON DELETE SET NULL,
        search TSVECTOR
    );

CREATE TABLE
    badges (
        id SERIAL PRIMARY KEY,
        name TEXT NOT NULL UNIQUE,
        description TEXT NOT NULL UNIQUE,
        image_path TEXT NOT NULL UNIQUE
    );

CREATE TABLE
    questions (
        id SERIAL PRIMARY KEY,
        title TEXT NOT NULL,
        search TSVECTOR,
        author INTEGER REFERENCES users (id) ON DELETE SET NULL
    );

CREATE TABLE
    answers (
        id SERIAL PRIMARY KEY,
        author INTEGER REFERENCES users (id) ON DELETE SET NULL,
        question_id INTEGER NOT NULL REFERENCES questions (id) ON DELETE CASCADE
    );

ALTER TABLE questions
ADD COLUMN correct_answer INTEGER REFERENCES answers (id) ON DELETE SET NULL;

CREATE TABLE
    comments (
        id SERIAL PRIMARY KEY,
        body TEXT NOT NULL,
        type main_content_type NOT NULL,
        date TIMESTAMP default now () NOT NULL,
        author INTEGER REFERENCES users (id) ON DELETE SET NULL,
        question_id INTEGER REFERENCES questions (id) ON DELETE CASCADE,
        answer_id INTEGER REFERENCES answers (id) ON DELETE CASCADE,
        CHECK (
            (
                type = 'QUESTION'
                AND question_id IS NOT NULL
                AND answer_id IS NULL
            )
            OR (
                type = 'ANSWER'
                AND question_id IS NULL
                AND answer_id IS NOT NULL
            )
        )
    );

CREATE TABLE
    content_versions (
        id SERIAL PRIMARY KEY,
        body TEXT NOT NULL,
        date TIMESTAMP DEFAULT now () NOT NULL,
        type main_content_type NOT NULL,
        question_id INTEGER REFERENCES questions (id) ON DELETE CASCADE,
        answer_id INTEGER REFERENCES answers (id) ON DELETE CASCADE,
        CHECK (
            (
                type = 'QUESTION'
                AND question_id IS NOT NULL
                AND answer_id IS NULL
            )
            OR (
                type = 'ANSWER'
                AND question_id IS NULL
                AND answer_id IS NOT NULL
            )
        )
    );

CREATE TABLE
    question_tag (
        question_id INTEGER NOT NULL REFERENCES questions (id) ON DELETE CASCADE,
        tag_id INTEGER NOT NULL REFERENCES tags (id) ON DELETE CASCADE, --Temos um trigger para quando se apaga uma tag
        PRIMARY KEY (question_id, tag_id)
    );

CREATE TABLE
    annexes (
        id SERIAL PRIMARY KEY,
        type file_type NOT NULL,
        file_path TEXT NOT NULL,
        version_id INTEGER NOT NULL REFERENCES content_versions (id) ON DELETE CASCADE
    );

CREATE TABLE
    votes (
        id SERIAL PRIMARY KEY,
        is_upvote BOOLEAN NOT NULL,
        type content_type NOT NULL,
        user_id INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        question_id INTEGER REFERENCES questions (id) ON DELETE CASCADE,
        answer_id INTEGER REFERENCES answers (id) ON DELETE CASCADE,
        comment_id INTEGER REFERENCES comments (id) ON DELETE CASCADE CHECK (
            (
                type = 'QUESTION'
                AND question_id IS NOT NULL
                AND answer_id IS NULL
                AND comment_id IS NULL
            )
            OR (
                type = 'ANSWER'
                AND question_id IS NULL
                AND answer_id IS NOT NULL
                AND comment_id IS NULL
            )
            OR (
                type = 'COMMENT'
                AND question_id IS NULL
                AND answer_id IS NULL
                AND comment_id IS NOT NULL
            )
        )
    );

CREATE TABLE
    badge_user (
        user_id INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        badge_id INTEGER NOT NULL REFERENCES badges (id) ON DELETE CASCADE,
        date DATE DEFAULT now () NOT NULL,
        PRIMARY KEY (user_id, badge_id)
    );

CREATE TABLE
    notifications (
        id SERIAL PRIMARY KEY,
        date TIMESTAMP DEFAULT now () NOT NULL,
        type notification_type NOT NULL,
        seen BOOLEAN DEFAULT FALSE,
        answer_id INTEGER REFERENCES answers (id) ON DELETE CASCADE,
        upvote_id INTEGER REFERENCES votes (id) ON DELETE CASCADE,
        badge_id INTEGER REFERENCES badges (id) ON DELETE CASCADE,
        user_id INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        CHECK (
            (
                type = 'ANSWER'
                AND answer_id IS NOT NULL
                AND upvote_id IS NULL
                AND badge_id IS NULL
            )
            OR (
                type = 'UPVOTE'
                AND answer_id IS NULL
                AND upvote_id IS NOT NULL
                AND badge_id IS NULL
            )
            OR (
                type = 'BADGE'
                AND answer_id IS NULL
                AND upvote_id IS NULL
                AND badge_id IS NOT NULL
            )
        )
    );

CREATE TABLE
    faq (
        id SERIAL PRIMARY KEY,
        question TEXT NOT NULL UNIQUE,
        answer TEXT NOT NULL
    );

CREATE TABLE
    followed_questions (
        user_id INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        question_id INTEGER NOT NULL REFERENCES questions (id) ON DELETE CASCADE,
        PRIMARY KEY (user_id, question_id)
    );

CREATE TABLE
    followed_tags (
        user_id INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        tag_id INTEGER NOT NULL REFERENCES tags (id) ON DELETE CASCADE,
        PRIMARY KEY (user_id, tag_id)
    );

CREATE TABLE
    followed_users (
        follower_id INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        followed_id INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        PRIMARY KEY (follower_id, followed_id)
    );

-----------------------------------------
-- Indexes
-----------------------------------------
DROP INDEX IF EXISTS search_tag;

DROP INDEX IF EXISTS search_question;

DROP INDEX IF EXISTS vote_type;

DROP INDEX IF EXISTS most_recent_version;

CREATE INDEX most_recent_version ON content_versions USING btree (date DESC NULLS LAST);

CREATE INDEX vote_type ON votes USING hash (is_upvote);

CREATE INDEX search_tag ON tags USING GIST (search);

CREATE INDEX search_question ON questions USING GIST (search);

-----------------------------------------
-- Triggers
-----------------------------------------

SET search_path TO lbaw2326;

-- TRIGGER 01
-- The username and email must comply with certain rules
DROP FUNCTION IF EXISTS verify_username_and_password() CASCADE;

CREATE FUNCTION verify_username_and_password() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF TG_OP = 'INSERT' THEN
		IF NEW.username IS NULL THEN
		RAISE EXCEPTION 'Username cannot be NULL.';
		END IF;

		IF (NEW.password IS NULL AND NEW.google_id IS NULL) THEN
		RAISE EXCEPTION 'Password and Google ID cannot be NULL.';
		END IF;

		IF NEW.email IS NULL THEN
		RAISE EXCEPTION 'Email cannot be NULL.';
		END IF;

		IF LENGTH(NEW.username) > 30 THEN
		RAISE EXCEPTION 'Username cannot be longer than 30 characters: %', NEW.username;
		END IF;

		IF LENGTH(NEW.username) < 5 THEN
		RAISE EXCEPTION 'Username cannot be shorter than 5 characters: %', NEW.username;
		END IF;

		IF (SELECT COUNT(*) FROM users WHERE username = NEW.username) > 0 THEN
		RAISE EXCEPTION 'Username must be unique: % already exists', NEW.username;
		END IF;
	ELSIF TG_OP = 'UPDATE' THEN
		IF NEW.password <> OLD.password THEN
			IF NEW.password IS NULL THEN
			RAISE EXCEPTION 'Password cannot be NULL.';
			END IF;
		END IF;
		IF NEW.email <> OLD.email THEN
			IF NEW.email IS NULL THEN
			RAISE EXCEPTION 'Email cannot be NULL.';
			END IF;
		END IF;
		IF NEW.username <> OLD.username THEN
			IF NEW.username IS NULL THEN
			RAISE EXCEPTION 'Username cannot be NULL.';
			END IF;

			IF LENGTH(NEW.username) > 30 THEN
			RAISE EXCEPTION 'Username cannot be longer than 30 characters: %', NEW.username;
			END IF;

			IF LENGTH(NEW.username) < 5 THEN
			RAISE EXCEPTION 'Username cannot be shorter than 5 characters: %', NEW.username;
			END IF;

			IF (SELECT COUNT(*) FROM users WHERE username = NEW.username) > 0 THEN
			RAISE EXCEPTION 'Username must be unique: % already exists', NEW.username;
			END IF;
		END IF;
	END IF;
	
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_username_and_password
        BEFORE INSERT OR UPDATE ON users
        FOR EACH ROW
        EXECUTE PROCEDURE verify_username_and_password();


-- TRIGGER 02
-- The user's score must be updated when votes change

DROP FUNCTION IF EXISTS update_score() CASCADE;

CREATE FUNCTION update_score() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        IF NEW.is_upvote THEN
            --Increase score by 1 for an upvote
            UPDATE users
            SET score = score + 1
            WHERE id = NEW.user_id;
        ELSE
            --Decrease score by 1 for a downvote
            UPDATE users
            SET score = score - 1
            WHERE id = NEW.user_id;
        END IF;
    ELSIF TG_OP = 'DELETE' THEN
        IF OLD.is_upvote THEN
            --Decrease score by 1 for a deleted upvote
            UPDATE users
            SET score = score - 1
            WHERE id = OLD.user_id;
        ELSE
            --Increase score by 1 for a deleted downvote
            UPDATE users
            SET score = score + 1
            WHERE id = OLD.user_id;
        END IF;
    END IF;

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_score
        AFTER INSERT OR DELETE ON votes
        FOR EACH ROW
        EXECUTE PROCEDURE update_score();

-- TRIGGER 03
-- A notification must be sent after an answer is written

DROP FUNCTION IF EXISTS send_answer_notification() CASCADE;

CREATE FUNCTION send_answer_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO notifications (date, type, answer_id, user_id)
    VALUES (NOW(), 'ANSWER', NEW.id, (SELECT author FROM questions WHERE id = NEW.question_id));

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER send_answer_notification
        AFTER INSERT ON answers
        FOR EACH ROW
        EXECUTE PROCEDURE send_answer_notification();


-- TRIGGER 04
-- A notification must be sent after an upvote is given

DROP FUNCTION IF EXISTS send_upvote_notification() CASCADE;

CREATE FUNCTION send_upvote_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_upvote THEN
        IF NEW.type = 'QUESTION' THEN
            INSERT INTO notifications (date, type, upvote_id, user_id)
            VALUES (NOW(), 'UPVOTE', NEW.id, (SELECT author FROM questions WHERE id = NEW.question_id));
        END IF;
        IF NEW.type = 'ANSWER' THEN
            INSERT INTO notifications (date, type, upvote_id, user_id)
            VALUES (NOW(), 'UPVOTE', NEW.id, (SELECT author FROM answers WHERE id = NEW.answer_id));
        END IF;
    END IF;

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER send_upvote_notification
        AFTER INSERT ON votes
        FOR EACH ROW
        EXECUTE PROCEDURE send_upvote_notification();


-- TRIGGER 05
-- A notification must be sent after a badges is received

DROP FUNCTION IF EXISTS send_badge_notification() CASCADE;

CREATE FUNCTION send_badge_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO notifications (date, type, badge_id, user_id)
    VALUES (NOW(), 'BADGE', NEW.badge_id, NEW.user_id);

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER send_badge_notification
        AFTER INSERT ON badge_user
        FOR EACH ROW
        EXECUTE PROCEDURE send_badge_notification();


-- TRIGGER 06
-- Text search vectors must be updated for different tables

DROP FUNCTION IF EXISTS tsvectors_update_question() CASCADE;

CREATE FUNCTION tsvectors_update_question() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.search =  setweight(to_tsvector('english', (SELECT body FROM content_versions WHERE question_id = NEW.id)), 'B') || setweight(to_tsvector('english', NEW.title), 'A');
    END IF;
    
    IF TG_OP = 'UPDATE' THEN
        IF NEW.title <> OLD.title THEN
            NEW.search_title = setweight(to_tsvector('english', (SELECT body FROM content_versions WHERE question_id = OLD.id)), 'B') || setweight(to_tsvector('english', NEW.title), 'A');
        END IF;
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tsvectors_update_question
        AFTER INSERT OR UPDATE ON questions
        FOR EACH ROW
        EXECUTE PROCEDURE tsvectors_update_question();


-- TRIGGER 07
-- Text search vectors must be updated for different tables

DROP FUNCTION IF EXISTS tsvectors_update_tag() CASCADE;

CREATE FUNCTION tsvectors_update_tag() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.search = setweight(to_tsvector('english', NEW.name), 'A') || setweight(to_tsvector('english', NEW.description), 'B');
    END IF;
    
    IF TG_OP = 'UPDATE' THEN        
        IF NEW.name <> OLD.name OR NEW.description <> OLD.description THEN
            NEW.search = setweight(to_tsvector('english', NEW.name), 'A') || setweight(to_tsvector('english', NEW.description), 'B');
        END IF;
    END IF;
    
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tsvectors_update_tag
        AFTER INSERT OR UPDATE ON tags
        FOR EACH ROW
        EXECUTE PROCEDURE tsvectors_update_tag();


-- TRIGGER 08
-- Text search vectors must be updated for different tables

DROP FUNCTION IF EXISTS tsvectors_update_content_version() CASCADE;

CREATE FUNCTION tsvectors_update_content_version() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        UPDATE questions
        SET search = setweight(to_tsvector('english', NEW.body), 'B') || setweight(to_tsvector('english', title), 'A')
        WHERE id = NEW.question_id;
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tsvectors_update_content_version
        BEFORE INSERT OR UPDATE ON content_versions
        FOR EACH ROW
        EXECUTE PROCEDURE tsvectors_update_content_version();
		
-- TRIGGER 09
-- A user gains experience by writing a question

DROP FUNCTION IF EXISTS update_experience_question() CASCADE;

CREATE FUNCTION update_experience_question() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE users
    SET experience = experience + 2
    WHERE id = NEW.author;

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_experience_question
        AFTER INSERT ON questions
        FOR EACH ROW
        EXECUTE PROCEDURE update_experience_question();


-- TRIGGER 10
-- A user gains experience by writing an answer

DROP FUNCTION IF EXISTS update_experience_answer() CASCADE;

CREATE FUNCTION update_experience_answer() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE users
    SET experience = experience + 1
    WHERE id = NEW.author;

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_experience_answer
        AFTER INSERT ON answers
        FOR EACH ROW
        EXECUTE PROCEDURE update_experience_answer();

		
-- TRIGGER 11
-- A content version cannot be related to more than five annexes

DROP FUNCTION IF EXISTS verify_annexes() CASCADE;

CREATE FUNCTION verify_annexes() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (SELECT COUNT(*) FROM annexes WHERE version_id = NEW.id) > 5 THEN
        RAISE EXCEPTION 'A content version cannot have more than five annexes.';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_annexes
        AFTER INSERT ON content_versions
        FOR EACH ROW
        EXECUTE PROCEDURE verify_annexes();
		
-- TRIGGER 12
-- Prevent self voting

DROP FUNCTION IF EXISTS prevent_self_voting() CASCADE;

CREATE FUNCTION prevent_self_voting() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.type = 'QUESTION' AND NEW.user_id = (SELECT author FROM questions WHERE id = NEW.question_id) THEN
        RAISE EXCEPTION 'You cannot vote on your own question';
    END IF;

    IF NEW.type = 'ANSWER' AND NEW.user_id = (SELECT author FROM answers WHERE id = NEW.answer_id) THEN
        RAISE EXCEPTION 'You cannot vote on your own answer';
    END IF;

    IF NEW.type = 'COMMENT' AND NEW.user_id = (SELECT author FROM comments WHERE id = NEW.comment_id) THEN
        RAISE EXCEPTION 'You cannot vote on your own comment';
    END IF;

  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_self_voting
        BEFORE INSERT ON votes
        FOR EACH ROW
        EXECUTE PROCEDURE prevent_self_voting();	
		
-- TRIGGER 13
-- A user can't answer their own questions

DROP FUNCTION IF EXISTS prevent_self_answering() CASCADE;

CREATE FUNCTION prevent_self_answering() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.author = (SELECT author FROM questions WHERE id = NEW.question_id) THEN
        RAISE EXCEPTION 'You cannot answer your own question';
    END IF;

  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_self_answering
        BEFORE INSERT ON answers
        FOR EACH ROW
        EXECUTE PROCEDURE prevent_self_answering();
		
/* -- TRIGGER 15
-- A badges should be given when a user asks a question for the first time.

DROP FUNCTION IF EXISTS badge_first_question() CASCADE;

CREATE FUNCTION badge_first_question() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF (SELECT COUNT(*)
    FROM questions
    WHERE author = NEW.author) = 1 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = 'First Question'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_first_question
        AFTER INSERT ON questions
        FOR EACH ROW
        EXECUTE PROCEDURE badge_first_question();

-- TRIGGER 16
-- A badges should be given when a user asks 10 questions.

DROP FUNCTION IF EXISTS badge_ten_questions() CASCADE;

CREATE FUNCTION badge_ten_questions() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF (SELECT COUNT(*)
    FROM questions
    WHERE author = NEW.author) = 1 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = '10 Questions'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_ten_questions
        AFTER INSERT ON questions
        FOR EACH ROW
        EXECUTE PROCEDURE badge_ten_questions();

-- TRIGGER 17
-- A badges should be given when a user asks 50 questions.

DROP FUNCTION IF EXISTS badge_fifty_questions() CASCADE;

CREATE FUNCTION badge_fifty_questions() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF (SELECT COUNT(*)
    FROM questions
    WHERE author = NEW.author) = 50 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = '50 Questions'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_fifty_questions
        AFTER INSERT ON questions
        FOR EACH ROW
        EXECUTE PROCEDURE badge_fifty_questions();

-- TRIGGER 18
-- A badges should be given when a user asks 100 questions.

DROP FUNCTION IF EXISTS badge_one_hundred_questions() CASCADE;

CREATE FUNCTION badge_one_hundred_questions() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF (SELECT COUNT(*)
    FROM questions
    WHERE author = NEW.author) = 100 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = '100 Questions'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_one_hundred_questions
        AFTER INSERT ON questions
        FOR EACH ROW
        EXECUTE PROCEDURE badge_one_hundred_questions();
		
-- TRIGGER 19
-- A badges should be given when a user answers for the first time.

DROP FUNCTION IF EXISTS badge_first_answer() CASCADE;

CREATE FUNCTION badge_first_answer() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF(SELECT COUNT(*)
    FROM answers
    WHERE author = NEW.author) = 1 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = 'First Answer'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_first_answer
        AFTER INSERT ON answers
        FOR EACH ROW
        EXECUTE PROCEDURE badge_first_answer();

-- TRIGGER 20
-- A badges should be given when a user answers 10 times.

DROP FUNCTION IF EXISTS badge_10_answers() CASCADE;

CREATE FUNCTION badge_10_answers() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF(SELECT COUNT(*)
    FROM answers
    WHERE author = NEW.author) = 10 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = '10 Answers'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_10_answers
        AFTER INSERT ON answers
        FOR EACH ROW
        EXECUTE PROCEDURE badge_10_answers();

-- TRIGGER 21
-- A badges should be given when a user answers 50 times.

DROP FUNCTION IF EXISTS badge_50_answers() CASCADE;

CREATE FUNCTION badge_50_answers() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF(SELECT COUNT(*)
    FROM answers
    WHERE author = NEW.author) = 50 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = '50 Answers'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_50_answers
        AFTER INSERT ON answers
        FOR EACH ROW
        EXECUTE PROCEDURE badge_50_answers();

-- TRIGGER 22
-- A badges should be given when a user answers 100 times.

DROP FUNCTION IF EXISTS badge_100_answers() CASCADE;

CREATE FUNCTION badge_100_answers() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF(SELECT COUNT(*)
    FROM answers
    WHERE author = NEW.author) = 100 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = '100 Answers'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_100_answers
        AFTER INSERT ON answers
        FOR EACH ROW
        EXECUTE PROCEDURE badge_100_answers();

-- TRIGGER 23
-- A badges should be given when a user comments for the first time.

DROP FUNCTION IF EXISTS badge_first_comment() CASCADE;

CREATE FUNCTION badge_first_comment() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (SELECT COUNT(*)
    FROM comments
    WHERE author = NEW.author) = 1 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = 'First Comment'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_first_comment
        AFTER INSERT ON comments
        FOR EACH ROW
        EXECUTE PROCEDURE badge_first_comment();


-- TRIGGER 24
-- A badges should be given when a user comments 10 times.

DROP FUNCTION IF EXISTS badge_10_comments() CASCADE;

CREATE FUNCTION badge_10_comments() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (SELECT COUNT(*)
    FROM comments
    WHERE author = NEW.author) = 10 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = '10 Comments'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_10_comments
        AFTER INSERT ON comments
        FOR EACH ROW
        EXECUTE PROCEDURE badge_10_comments();
    
-- TRIGGER 25
-- A badges should be given when a user comments 50 times.

DROP FUNCTION IF EXISTS badge_50_comments() CASCADE;

CREATE FUNCTION badge_50_comments() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (SELECT COUNT(*)
    FROM comments
    WHERE author = NEW.author) = 50 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = '50 Comments'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_50_comments
        AFTER INSERT ON comments
        FOR EACH ROW
        EXECUTE PROCEDURE badge_50_comments();
    
    
-- TRIGGER 26
-- A badges should be given when a user comments 100 times.

DROP FUNCTION IF EXISTS badge_100_comments() CASCADE;

CREATE FUNCTION badge_100_comments() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (SELECT COUNT(*)
    FROM comments
    WHERE author = NEW.author) = 100 THEN
        INSERT INTO badge_user (user_id, badge_id)
        VALUES (NEW.author, (SELECT id FROM badges WHERE name = '100 Comments'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_100_comments
        AFTER INSERT ON comments
        FOR EACH ROW
        EXECUTE PROCEDURE badge_100_comments();
		
-- TRIGGER 27
-- A badges should be given when a user receives the first upvote.

DROP FUNCTION IF EXISTS badge_first_upvote() CASCADE;

CREATE FUNCTION badge_first_upvote() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF (SELECT COUNT(*) FROM 
            votes JOIN questions ON votes.question_id = questions.id JOIN answers ON votes.answer_id = answers.id JOIN comments ON votes.comment_id = comments.id
            WHERE votes.user_id = NEW.user_id AND is_upvote = true) = 1 THEN
            INSERT INTO badge_user (user_id, badge_id)
            VALUES (NEW.user_id, (SELECT id FROM badges WHERE name = 'First Upvote'));
        END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_first_upvote
        AFTER INSERT ON votes
        FOR EACH ROW
        EXECUTE PROCEDURE badge_first_upvote();
		
-- TRIGGER 28
-- A badges should be given when a user receives the first downvote.

DROP FUNCTION IF EXISTS badge_first_downvote() CASCADE;

CREATE FUNCTION badge_first_downvote() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF (SELECT COUNT(*) FROM 
            votes JOIN questions ON votes.question_id = questions.id JOIN answers ON votes.answer_id = answers.id JOIN comments ON votes.comment_id = comments.id
            WHERE votes.user_id = NEW.user_id AND is_upvote = false) = 1 THEN
            INSERT INTO badge_user (user_id, badge_id)
            VALUES (NEW.user_id, (SELECT id FROM badges WHERE name = 'First Downvote'));
        END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_first_downvote
        AFTER INSERT ON votes
        FOR EACH ROW
        EXECUTE PROCEDURE badge_first_downvote();
		
-- TRIGGER 29
-- A badges should be given when a user reaches 100 score.

DROP FUNCTION IF EXISTS badge_100_score() CASCADE;

CREATE FUNCTION badge_100_score() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_upvote = true THEN
        IF (SELECT score FROM users WHERE id = NEW.user_id) = 100 AND
           (SELECT COUNT(*) FROM badge_user WHERE user_id = NEW.user_id AND badge_id = (SELECT id FROM badges WHERE name = '100 Score')) = 0 THEN
            INSERT INTO badge_user (user_id, badge_id)
            VALUES (NEW.user_id, (SELECT id FROM badges WHERE name = '100 Score'));
        END IF;
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_100_score
        AFTER INSERT ON votes
        FOR EACH ROW
        EXECUTE PROCEDURE badge_100_score();
    
-- TRIGGER 30
-- A badges should be given when a user reaches 1000 score.

DROP FUNCTION IF EXISTS badge_1000_score() CASCADE;

CREATE FUNCTION badge_1000_score() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_upvote = true THEN
        IF (SELECT score FROM users WHERE id = NEW.user_id) = 1000 AND
           (SELECT COUNT(*) FROM badge_user WHERE user_id = NEW.user_id AND badge_id = (SELECT id FROM badges WHERE name = '1000 Score')) = 0 THEN
            INSERT INTO badge_user (user_id, badge_id)
            VALUES (NEW.user_id, (SELECT id FROM badges WHERE name = '1000 Score'));
        END IF;
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_1000_score
        AFTER INSERT ON votes
        FOR EACH ROW
        EXECUTE PROCEDURE badge_1000_score(); */

-- TRIGGER 31
-- A badges should be given when a user reaches 5000 score.

DROP FUNCTION IF EXISTS badge_5000_score() CASCADE;

CREATE FUNCTION badge_5000_score() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_upvote = true THEN
        IF (SELECT score FROM users WHERE id = NEW.user_id) = 5000 AND
           (SELECT COUNT(*) FROM badge_user WHERE user_id = NEW.user_id AND badge_id = (SELECT id FROM badges WHERE name = '5000 Score')) = 0 THEN
            INSERT INTO badge_user (user_id, badge_id)
            VALUES (NEW.user_id, (SELECT id FROM badges WHERE name = '5000 Score'));
        END IF;
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_5000_score
        AFTER INSERT ON votes
        FOR EACH ROW
        EXECUTE PROCEDURE badge_5000_score();

-- TRIGGER 32
-- A badges should be given when a user reaches 10000 score.

DROP FUNCTION IF EXISTS badge_10000_score() CASCADE;

CREATE FUNCTION badge_10000_score() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_upvote = true THEN
        IF (SELECT score FROM users WHERE id = NEW.user_id) = 10000 AND
           (SELECT COUNT(*) FROM badge_user WHERE user_id = NEW.user_id AND badge_id = (SELECT id FROM badges WHERE name = '10000 Score')) = 0 THEN
            INSERT INTO badge_user (user_id, badge_id)
            VALUES (NEW.user_id, (SELECT id FROM badges WHERE name = '10000 Score'));
        END IF;
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_10000_score
        AFTER INSERT ON votes
        FOR EACH ROW
        EXECUTE PROCEDURE badge_10000_score();

		
-----------------------------------------
-- User Functions
-----------------------------------------

SET
    search_path TO lbaw2326;

DROP FUNCTION IF EXISTS get_display_name(user_id INT);

CREATE FUNCTION get_display_name(user_id INT) RETURNS TEXT AS
$BODY$
DECLARE
    user_username TEXT;
    user_is_banned BOOLEAN;
BEGIN
    SELECT username, is_banned INTO user_username, user_is_banned
    FROM users
    WHERE id = user_id;

    IF user_is_banned THEN
        RETURN 'BannedUser';
    ELSE
        RETURN user_username;
    END IF;
END 
$BODY$
LANGUAGE plpgsql;

-----------------------------------------
-- Populate
-----------------------------------------   
SET
    search_path TO lbaw2326;

INSERT INTO
    users (
        email,
        name,
        username,
        password,
        profile_picture,
        experience,
        score,
        type,
        member_since
    )
VALUES
    (
        'user1@example.com',
        'John Smith',
        'jsmith',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile1.jpg',
        10,
        100,
        'Admin',
        '2023-10-20'
    ),
    (
        'user2@example.com',
        'Emily Johnson',
        'ejohnson',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile2.jpg',
        5,
        75,
        'Admin',
        '2023-10-15'
    ),
    (
        'user3@example.com',
        'Michael Williams',
        'mwilliams',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile3.jpg',
        15,
        200,
        'Admin',
        '2023-10-25'
    ),
    (
        'user4@example.com',
        'Sophia Jones',
        'sjones',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile4.jpg',
        20,
        250,
        'Admin',
        '2023-10-30'
    ),
    (
        'user5@example.com',
        'William Davis',
        'wdavis',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile5.jpg',
        8,
        90,
        'Admin',
        '2023-10-17'
    ),
    (
        'user6@example.com',
        'Olivia Martinez',
        'omartinez',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile6.jpg',
        12,
        150,
        'Admin',
        '2023-10-22'
    ),
    (
        'user7@example.com',
        'James Brown',
        'jbrown',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile7.jpg',
        25,
        300,
        'User',
        '2023-11-01'
    ),
    (
        'user8@example.com',
        'Charlotte Wilson',
        'cwilson',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile8.jpg',
        7,
        80,
        'Admin',
        '2023-10-16'
    ),
    (
        'user9@example.com',
        'Benjamin Taylor',
        'btaylor',
        '$2a$10$wX37icU6.4qS3ph4mQKJIOI3YF1JzVjRkxn61aEFylJ6Mh8LhoQY6',
        '/userFiles/profilePics/profile9.jpg',
        18,
        220,
        'User',
        '2023-10-27'
    ),
    (
        'user10@example.com',
        'Emma Anderson',
        'eanderson',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile10.jpg',
        14,
        170,
        'Admin',
        '2023-10-23'
    ),
    (
        'user11@example.com',
        'Liam Thomas',
        'lthomas',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile11.jpg',
        22,
        270,
        'Admin',
        '2023-10-28'
    ),
    (
        'user12@example.com',
        'Ava Hall',
        'ahall',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile12.jpg',
        30,
        350,
        'Admin',
        '2023-11-05'
    ),
    (
        'user13@example.com',
        'Mason Harris',
        'mharris',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile13.jpg',
        6,
        70,
        'Admin',
        '2023-01-15'
    ),
    (
        'user14@example.com',
        'Sophia Adams',
        'sadams',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile14.jpg',
        11,
        130,
        'User',
        '2023-02-20'
    ),
    (
        'user15@example.com',
        'Aiden Clark',
        'aclark',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile15.jpg',
        17,
        190,
        'User',
        '2023-03-25'
    ),
    (
        'user16@example.com',
        'Olivia Hall',
        'ohall',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile16.jpg',
        13,
        160,
        'User',
        '2023-04-30'
    ),
    (
        'user18@example.com',
        'Amelia Young',
        'ayoung',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile18.jpg',
        19,
        230,
        'User',
        '2023-06-10'
    ),
    (
        'user19@example.com',
        'Jackson Wright',
        'jwright',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile19.jpg',
        23,
        280,
        'User',
        '2023-07-15'
    ),
    (
        'user20@example.com',
        'Ella Turner',
        'eturner',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile20.jpg',
        16,
        180,
        'User',
        '2023-08-20'
    ),
    (
        'user22@example.com',
        'Avery Adams',
        'aadams',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile22.jpg',
        7,
        80,
        'User',
        '2023-10-30'
    ),
    (
        'user23@example.com',
        'Jacob Garcia',
        'jgarcia',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile23.jpg',
        9,
        100,
        'User',
        '2021-01-15'
    ),
    (
        'user24@example.com',
        'Harper King',
        'hking',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile24.jpg',
        14,
        170,
        'User',
        '2021-02-20'
    ),
    (
        'user25@example.com',
        'Ethan Parker',
        'eparker',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile25.jpg',
        15,
        180,
        'User',
        '2021-03-25'
    ),
    (
        'user27@example.com',
        'Mason Murphy',
        'mmurphy',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile27.jpg',
        12,
        130,
        'Admin',
        '2021-05-06'
    ),
    (
        'user28@example.com',
        'Isabella Brooks',
        'ibrooks',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile28.jpg',
        11,
        120,
        'User',
        '2021-06-11'
    ),
    (
        'user29@example.com',
        'Liam Rivera',
        'lrivera',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile29.jpg',
        13,
        150,
        'User',
        '2021-07-17'
    ),
    (
        'user30@example.com',
        'Aria Ward',
        'award',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile30.jpg',
        16,
        190,
        'User',
        '2021-08-22'
    ),
    (
        'user32@example.com',
        'Ava Wood',
        'awood',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile32.jpg',
        17,
        200,
        'Admin',
        '2021-10-30'
    ),
    (
        'user33@example.com',
        'Lucas Nelson',
        'lnelson',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile33.jpg',
        17,
        200,
        'User',
        '2023-10-01'
    ),
    (
        'user34@example.com',
        'Sophia Brooks',
        'sbrooks',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile34.jpg',
        19,
        220,
        'User',
        '2023-10-02'
    ),
    (
        'user35@example.com',
        'Oliver Russell',
        'orussell',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile35.jpg',
        22,
        260,
        'User',
        '2023-10-03'
    ),
    (
        'user37@example.com',
        'Logan Bailey',
        'lbailey',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile37.jpg',
        25,
        300,
        'User',
        '2023-10-05'
    ),
    (
        'user38@example.com',
        'Harper Allen',
        'hallen',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile38.jpg',
        23,
        270,
        'User',
        '2023-10-06'
    ),
    (
        'user39@example.com',
        'Mason Martin',
        'mmartin',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile39.jpg',
        21,
        250,
        'User',
        '2023-10-07'
    ),
    (
        'user40@example.com',
        'Avery Lewis',
        'alewis',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile40.jpg',
        8,
        90,
        'User',
        '2023-10-08'
    ),
    (
        'user42@example.com',
        'Sophia Turner',
        'sturner',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile42.jpg',
        12,
        120,
        'User',
        '2023-10-10'
    ),
    (
        'user43@example.com',
        'Oliver Davis',
        'odavis',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile43.jpg',
        15,
        150,
        'User',
        '2023-10-11'
    ),
    (
        'user44@example.com',
        'Aria Wilson',
        'awilson',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile44.jpg',
        9,
        90,
        'User',
        '2023-10-12'
    ),
    (
        'user46@example.com',
        'Charlotte Green',
        'cgreen',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile46.jpg',
        13,
        130,
        'User',
        '2023-10-14'
    ),
    (
        'user47@example.com',
        'Liam Smith',
        'lsmith',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile47.jpg',
        14,
        140,
        'User',
        '2023-10-15'
    ),
    (
        'user48@example.com',
        'Emma Brown',
        'ebrown',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile48.jpg',
        7,
        70,
        'User',
        '2023-10-16'
    ),
    (
        'user50@example.com',
        'Olivia Johnson',
        'ojohnson',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile50.jpg',
        18,
        180,
        'User',
        '2023-10-18'
    ),
    (
        'user51@example.com',
        'Noah Martinez',
        'nmartinez',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile51.jpg',
        16,
        175,
        'User',
        '2022-10-01'
    ),
    (
        'user52@example.com',
        'Emma Harris',
        'eharris',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile52.jpg',
        15,
        165,
        'User',
        '2022-10-02'
    ),
    (
        'user53@example.com',
        'Liam Wilson',
        'lwilson',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile53.jpg',
        14,
        155,
        'User',
        '2022-10-03'
    ),
    (
        'user55@example.com',
        'Mason Davis',
        'mdavis',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile55.jpg',
        11,
        145,
        'User',
        '2022-10-05'
    ),
    (
        'user56@example.com',
        'Avery Thompson',
        'athompson',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile56.jpg',
        18,
        180,
        'User',
        '2022-10-06'
    ),
    (
        'user57@example.com',
        'Olivia Scott',
        'oscott_45',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile57.jpg',
        12,
        150,
        'User',
        '2022-10-07'
    ),
    (
        'user58@example.com',
        'Jackson Clark',
        'jclark',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile58.jpg',
        19,
        190,
        'User',
        '2022-10-08'
    ),
    (
        'user60@example.com',
        'Logan Wilson',
        'lwilson2',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile60.jpg',
        10,
        140,
        'User',
        '2022-10-10'
    ),
    (
        'user61@example.com',
        'Noah Wright',
        'nwright',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile61.jpg',
        15,
        170,
        'User',
        '2022-10-11'
    ),
    (
        'user62@example.com',
        'Harper Turner',
        'hturner',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile62.jpg',
        16,
        175,
        'User',
        '2022-10-12'
    ),
    (
        'user63@example.com',
        'Lucas Harris',
        'lharris',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile63.jpg',
        14,
        155,
        'Banned',
        '2022-10-13'
    ),
    (
        'user64@example.com',
        'Aria Davis',
        'adavis',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile64.jpg',
        12,
        145,
        'User',
        '2022-10-14'
    ),
    (
        'user65@example.com',
        'Logan Martinez',
        'lmartinez',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile65.jpg',
        19,
        190,
        'User',
        '2022-10-15'
    ),
    (
        'user66@example.com',
        'Oliver Green',
        'ogreen',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile66.jpg',
        13,
        160,
        'User',
        '2022-10-16'
    ),
    (
        'user67@example.com',
        'Charlotte Miller',
        'cmiller',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile67.jpg',
        11,
        140,
        'Banned',
        '2022-10-17'
    ),
    (
        'user68@example.com',
        'Liam King',
        'lking1',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile68.jpg',
        18,
        180,
        'User',
        '2022-10-18'
    ),
    (
        'user69@example.com',
        'Mia Martinez',
        'mmartinez',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile69.jpg',
        15,
        175,
        'User',
        '2022-10-19'
    ),
    (
        'user70@example.com',
        'Aiden Garcia',
        'agarcia',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile70.jpg',
        12,
        160,
        'User',
        '2022-10-20'
    ),
    (
        'user71@example.com',
        'Harper Taylor',
        'htaylor69',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile71.jpg',
        16,
        170,
        'Banned',
        '2022-10-21'
    ),
    (
        'user72@example.com',
        'William Walker',
        'wwalker',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile72.jpg',
        17,
        180,
        'User',
        '2022-10-22'
    ),
    (
        'user73@example.com',
        'Emma Scott',
        'escott',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile73.jpg',
        14,
        165,
        'User',
        '2022-10-23'
    ),
    (
        'user74@example.com',
        'Mason White',
        'mwhite',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile74.jpg',
        11,
        150,
        'User',
        '2022-10-24'
    ),
    (
        'user75@example.com',
        'Aria Lee',
        'alee56',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile75.jpg',
        19,
        190,
        'Banned',
        '2022-10-25'
    ),
    (
        'user76@example.com',
        'Liam Clark',
        'lclark',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile76.jpg',
        17,
        175,
        'User',
        '2022-10-26'
    ),
    (
        'user77@example.com',
        'Olivia Martin',
        'omartin',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile77.jpg',
        15,
        160,
        'User',
        '2022-10-27'
    ),
    (
        'user78@example.com',
        'Elijah King',
        'eking',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile78.jpg',
        13,
        145,
        'User',
        '2022-10-28'
    ),
    (
        'user79@example.com',
        'Isabella Turner',
        'iturner',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile79.jpg',
        18,
        185,
        'Banned',
        '2022-10-29'
    ),
    (
        'user80@example.com',
        'Lucas Allen',
        'lallen',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile80.jpg',
        12,
        155,
        'User',
        '2022-10-30'
    ),
    (
        'user81@example.com',
        'Mia Turner',
        'mturner',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile81.jpg',
        19,
        195,
        'User',
        '2022-10-31'
    ),
    (
        'user82@example.com',
        'Elijah Davis',
        'edavis',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile82.jpg',
        16,
        170,
        'User',
        '2022-11-01'
    ),
    (
        'user83@example.com',
        'Aria Green',
        'agreen',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile83.jpg',
        15,
        165,
        'Banned',
        '2022-11-02'
    ),
    (
        'user84@example.com',
        'Logan King',
        'lking',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile84.jpg',
        13,
        155,
        'User',
        '2022-11-03'
    ),
    (
        'user85@example.com',
        'Amelia Walker',
        'awalker',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile85.jpg',
        11,
        150,
        'User',
        '2022-11-04'
    ),
    (
        'user86@example.com',
        'Mason Harris',
        'mharris45',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile86.jpg',
        19,
        185,
        'User',
        '2022-11-05'
    ),
    (
        'user87@example.com',
        'Oliver Scott',
        'oscott',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile87.jpg',
        17,
        170,
        'Banned',
        '2022-11-06'
    ),
    (
        'user88@example.com',
        'Evelyn Allen',
        'eallen',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile88.jpg',
        16,
        165,
        'User',
        '2022-11-07'
    ),
    (
        'user89@example.com',
        'Liam Turner',
        'lturner',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile89.jpg',
        12,
        155,
        'User',
        '2022-11-08'
    ),
    (
        'user90@example.com',
        'Aria Wilson',
        'awilson_lr',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile90.jpg',
        11,
        150,
        'User',
        '2022-11-09'
    ),
    (
        'user91@example.com',
        'Logan Thompson',
        'lthompson',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile91.jpg',
        14,
        160,
        'Banned',
        '2022-11-10'
    ),
    (
        'user92@example.com',
        'Amelia White',
        'awhite',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile92.jpg',
        17,
        175,
        'User',
        '2022-11-11'
    ),
    (
        'user93@example.com',
        'Liam King',
        'lking2',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile93.jpg',
        15,
        165,
        'User',
        '2022-11-12'
    ),
    (
        'user94@example.com',
        'Mia Martinez',
        'mmartinez2',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile94.jpg',
        13,
        155,
        'User',
        '2022-11-13'
    ),
    (
        'user95@example.com',
        'Aiden Garcia',
        'agarcia443',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile95.jpg',
        11,
        150,
        'Banned',
        '2022-11-14'
    ),
    (
        'user96@example.com',
        'Harper Taylor',
        'htaylor',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile96.jpg',
        12,
        155,
        'User',
        '2022-11-15'
    ),
    (
        'user97@example.com',
        'William Walker',
        'wwalker2',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile97.jpg',
        14,
        160,
        'User',
        '2022-11-16'
    ),
    (
        'user98@example.com',
        'Sophia White',
        'swhite',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile98.jpg',
        16,
        165,
        'User',
        '2022-11-17'
    ),
    (
        'user99@example.com',
        'Elijah Wilson',
        'ewilson',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile99.jpg',
        18,
        170,
        'Banned',
        '2022-11-18'
    ),
    (
        'user100@example.com',
        'Olivia Taylor',
        'otaylor',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile100.jpg',
        15,
        165,
        'User',
        '2022-11-19'
    ),
    (
        'admin@sousa.com',
        'Admin Sousa',
        'admin',
        '$2y$10$O1WiOMVoIT9f3QJxDyXKjOxtoGTiS.yRSDSVebzelWAJ8azdkxL6S',
        '/userFiles/profilePics/profile101.jpg',
        0,
        0,
        'Admin',
        '2023-11-21'
    );

INSERT INTO
    tags (name, description, approved, creator)
VALUES
    (
        'Budgeting',
        'Managing personal finances and budgeting',
        TRUE,
        NULL
    ),
    (
        'Cooking', 
        'Learning to cook and preparing meals',
        TRUE,
        NULL
    ),
    (
        'Laundry', 
        'Doing laundry and clothing care',
        TRUE,
        NULL
    ),
    (
        'Cleaning', 
        'House cleaning and maintenance',
        TRUE,
        NULL
    ),
    (
        'Time Management',
        'Effective time management and productivity',
        TRUE,
        91
    ),
    (
        'Job Search',
        'Searching for jobs and career development',
        TRUE,
        NULL
    ),
    (
        'Renting',
        'Renting apartments and property management',
        TRUE,
        NULL
    ),
    (
        'Healthcare',
        'Managing healthcare and medical appointments',
        TRUE,
        NULL
    ),
    (
        'Insurance',
        'Understanding and managing insurance policies',
        TRUE,
        NULL
    ),
    (
        'Taxes', 
        'Filing taxes and tax planning',
        TRUE, 
        NULL
    ),
    (
        'Home Repairs',
        'DIY home repairs and maintenance',
        TRUE, 
        NULL
    ),
    (
        'Grocery Shopping',
        'Effective grocery shopping and meal planning',
        TRUE,
        91
    ),
    (
        'Car Maintenance',
        'Maintaining and repairing your vehicle',
        FALSE, 
        91
    ),
    (
        'Networking',
        'Building professional networks and connections',
        FALSE,
        91
    ),
    (
        'Mental Health',
        'Managing mental health and self-care',
        FALSE,
        91
    );

INSERT INTO
    badges (name, description, image_path)
VALUES
    (
        'First Question',
        'Asked your first question',
        '/assets/badges/first_question.png'
    ),
    (
        '10 Questions',
        'Asked 10 questions',
        '/assets/badges/10_questions.png'
    ),
    (
        '50 Questions',
        'Asked 50 questions',
        '/assets/badges/50_questions.png'
    ),
    (
        '100 Questions',
        'Asked 100 questions',
        '/assets/badges/100_questions.png'
    ),
    (
        'First Answer',
        'Answered your first question',
        '/assets/badges/first_answer.png'
    ),
    (
        '10 Answers',
        'Provided 10 answers',
        '/assets/badges/10_answers.png'
    ),
    (
        '50 Answers',
        'Provided 50 answers',
        '/assets/badges/50_answers.png'
    ),
    (
        '100 Answers',
        'Provided 100 answers',
        '/assets/badges/100_answers.png'
    ),
    (
        'First Correct Answer',
        'Received your first accepted answer',
        '/assets/badges/first_accepted_answer.png'
    ),
    (
        '10 Correct Answers',
        'Received 10 accepted answers',
        '/assets/badges/10_accepted_answers.png'
    ),
    (
        '50 Correct Answers',
        'Received 50 accepted answers',
        '/assets/badges/50_accepted_answers.png'
    ),
    (
        'First Comment',
        'Posted your first comment',
        '/assets/badges/first_comment.png'
    ),
    (
        '10 Comments',
        'Posted 10 comments',
        '/assets/badges/10_comments.png'
    ),
    (
        '50 Comments',
        'Posted 50 comments',
        '/assets/badges/50_comments.png'
    ),
    (
        '100 Comments',
        'Posted 100 comments',
        '/assets/badges/100_comments.png'
    ),
    (
        'First Upvote',
        'Received your first upvote',
        '/assets/badges/first_upvote.png'
    ),
    (
        '100 Score',
        'Achieved a score of 100',
        '/assets/badges/100_score.png'
    ),
    (
        '1000 Score',
        'Achieved a score of 1000',
        '/assets/badges/1000_score.png'
    ),
    (
        '5000 Score',
        'Achieved a score of 5000',
        '/assets/badges/5000_score.png'
    ),
    (
        '10000 Score',
        'Achieved a score of 10000',
        '/assets/badges/10000_score.png'
    ),
    (
        'First Downvote',
        'Uhohh... Received your first downvote',
        '/assets/badges/first_downvote.png'
    );

INSERT INTO
    questions (title, author)
VALUES
    ('How to Create a Budget for Living Alone', 3),
    ('Simple Recipes for Beginners', 6),
    (
        'Tips for Doing Laundry Without Ruining Clothes',
        9
    ),
    ('Effective Cleaning Routines for a Tidy Home', 12),
    ('Time Management Techniques for Productivity', 15),
    ('Strategies for a Successful Job Search', 18),
    (
        'Essential Tips for Renting Your First Apartment',
        21
    ),
    ('Navigating Healthcare Options in Portugal', 24),
    ('Understanding Different Types of Insurance', 27),
    (
        'Tax Filing Tips for Students and Young Adults',
        30
    ),
    ('Basic Home Repairs Every Adult Should Know', 33),
    (
        'Smart Grocery Shopping Strategies on a Budget',
        36
    ),
    ('Essential Car Maintenance for New Drivers', 39),
    (
        'Building a Professional Network as a Student',
        42
    ),
    ('Managing Mental Health and Well-being', 45),
    (
        'Tips for Choosing the Right Health Insurance',
        48
    ),
    (
        'Navigating University Life: Study Tips and Hacks',
        3
    ),
    (
        'Balancing Work and Studies: Strategies for Success',
        6
    ),
    (
        'Cooking for One: Quick and Nutritious Recipes',
        9
    ),
    (
        'How to Find Affordable and Safe Housing in Porto',
        12
    ),
    (
        'Time Management for Juggling a Job and University',
        15
    ),
    ('Networking Events in Porto: Where to Start', 18),
    (
        'Coping with Stress and Anxiety in University Life',
        21
    ),
    (
        'Understanding Health Insurance Policies in Portugal',
        24
    ),
    (
        'Simple Cleaning Hacks for a Tidy Student Apartment',
        27
    ),
    (
        'Budget-Friendly Tips for Grocery Shopping in Porto',
        30
    ),
    (
        'How to Stay Motivated and Productive During Exams',
        33
    ),
    ('Navigating Public Transportation in Porto', 36),
    (
        'Balancing Social Life and Academics in University',
        39
    ),
    ('Cooking Skills: Beyond Instant Noodles', 42),
    (
        'Finding Part-Time Jobs for Students in Porto',
        45
    ),
    (
        'Effective Strategies for Time Management in University',
        48
    ),
    (
        'How to Choose the Right Health Insurance Plan',
        3
    ),
    ('Apartment Hunting Tips: What to Look For', 6),
    (
        'Navigating University Finances: Scholarships and Grants',
        9
    ),
    (
        'Finding Affordable Textbooks and Study Resources',
        12
    ),
    (
        'Cooking for a Crowd: Easy Recipes for Hosting',
        15
    ),
    (
        'Effective Study Techniques for Retaining Information',
        18
    ),
    (
        'Exploring Extracurricular Activities in Porto',
        21
    ),
    ('Handling Academic Pressure and Burnout', 24),
    ('Understanding Tenant Rights in Porto', 27),
    ('Budgeting for Student Life: Tips and Tools', 30),
    (
        'Staying Active and Healthy in a Busy Student Life',
        33
    ),
    ('Finding Reliable Transportation in Porto', 36),
    (
        'Maximizing Productivity in University Projects',
        39
    ),
    (
        'Mastering Time Management for Assignments and Deadlines',
        42
    ),
    (
        'Navigating Cultural Events and Festivals in Porto',
        45
    ),
    ('Healthy Eating on a Student Budget', 48),
    ('How to Choose a Suitable Study Space', 3),
    ('Finding Internship Opportunities in Porto', 6),
    (
        'Tips for Effective Group Projects in University',
        9
    ),
    ('Cooking Skills: From Basics to Gourmet', 12),
    ('Balancing Work, Studies, and Personal Life', 15),
    (
        'Networking Strategies for Introverted Students',
        18
    ),
    (
        'Coping with Homesickness and Adjusting to University Life',
        21
    ),
    (
        'Understanding Healthcare Services for Students in Porto',
        24
    ),
    ('Quick and Easy Cleaning Hacks for Students', 27),
    ('Financial Planning for University Students', 30),
    (
        'Exploring Porto: Hidden Gems and Must-Visit Places',
        33
    ),
    (
        'Mastering Time Management for Coding Projects',
        36
    ),
    ('Setting Career Goals and Achieving Them', 39),
    (
        'Effective Communication in Professional Settings',
        42
    ),
    ('Navigating Internship Applications in Porto', 45),
    ('Finding the Right Study Groups and Partners', 48),
    (
        'Healthy Habits: Exercise and Nutrition for Students',
        3
    ),
    ('Exploring Cultural Diversity in Porto', 6),
    (
        'Building a Portfolio for Future Job Opportunities',
        9
    ),
    ('Tips for Effective Remote Learning and Work', 12),
    ('Mastering Advanced Coding Techniques', 15),
    ('Balancing Social Life and Personal Projects', 18),
    (
        'How to Thrive in a Multidisciplinary Environment',
        21
    ),
    ('Understanding Portuguese Employment Laws', 24),
    ('Effective Problem Solving in Coding', 27),
    (
        'Cultural Events: How to Make the Most of Them',
        30
    ),
    (
        'Building a Personal Brand for Professional Success',
        33
    ),
    ('Healthy Cooking on a Tight Student Budget', 36),
    (
        'Navigating Student Discounts and Benefits in Porto',
        39
    ),
    ('Maximizing Productivity in Coding Projects', 42),
    ('Finding Volunteer Opportunities in Porto', 45),
    (
        'Strategies for Effective Code Testing and Debugging',
        48
    ),
    (
        'Exploring Nature and Outdoor Activities in Porto',
        3
    ),
    ('Mastering Algorithm Design and Optimization', 6),
    ('Setting Up a Personal Development Plan', 9),
    (
        'Networking in Tech: Tips for Introverted Engineers',
        12
    ),
    ('Finding Part-Time Work in the Tech Industry', 15),
    (
        'Effective Strategies for Debugging Complex Code',
        18
    ),
    (
        'Understanding Intellectual Property in Tech Projects',
        21
    ),
    (
        'Navigating Work-University-Life Balance in Porto',
        24
    ),
    ('Balancing Coding Projects and Personal Time', 27),
    (
        'Budget-Friendly Travel Tips for Students in Porto',
        30
    ),
    (
        'Building Soft Skills for a Successful Tech Career',
        33
    ),
    ('Healthy Snacking Habits for Busy Students', 36),
    ('Navigating Research Opportunities in Porto', 39),
    (
        'Mastering Data Structures for Efficient Code',
        42
    ),
    (
        'Effective Strategies for Team Collaboration in Coding',
        45
    ),
    (
        'Finding Affordable Tech Gadgets and Accessories',
        48
    ),
    ('Exploring Art and Creativity in Porto', 3),
    ('Creating User-Friendly Interfaces in Coding', 6),
    ('Setting and Achieving Long-Term Career Goals', 9),
    (
        'Effective Time Management for Coding Projects',
        12
    ),
    ('Navigating Internship Interviews in Porto', 15),
    ('Strategies for Stress Management in Tech', 18),
    ('Building a Professional Online Presence', 21),
    (
        'Understanding Portuguese Tech Industry Trends',
        24
    ),
    ('Effective Code Version Control Techniques', 27),
    ('Exploring Festivals and Events in Porto', 30),
    ('Maximizing Efficiency in Coding Projects', 33),
    (
        'Finding Affordable Fitness and Wellness Resources',
        36
    ),
    (
        'Navigating Tech Conferences and Meetups in Porto',
        39
    ),
    ('Mastering Object-Oriented Programming', 42),
    (
        'Balancing Freelancing and University Studies',
        45
    ),
    ('Understanding Sustainable Tech Practices', 48);

INSERT INTO
    answers (author, question_id)
VALUES
    (1, 1),
    (2, 1),
    (3, 2),
    (4, 2),
    (5, 3),
    (6, 3),
    (7, 4),
    (8, 4),
    (9, 5),
    (10, 5),
    (11, 6),
    (12, 6),
    (13, 7),
    (14, 7),
    (15, 8),
    (16, 8),
    (17, 9),
    (18, 9),
    (19, 10),
    (20, 10),
    (21, 11),
    (22, 11),
    (23, 12),
    (24, 12),
    (25, 13),
    (26, 13),
    (27, 14),
    (28, 14),
    (29, 15),
    (30, 15),
    (31, 16),
    (32, 16),
    (33, 17),
    (34, 17),
    (35, 18),
    (36, 18),
    (37, 19),
    (38, 19),
    (39, 20),
    (40, 20),
    (41, 21),
    (42, 21),
    (43, 22),
    (44, 22),
    (45, 23),
    (46, 23),
    (47, 24),
    (48, 24),
    (49, 25),
    (50, 25);

INSERT INTO
    content_versions (body, type, question_id, answer_id, date)
VALUES
    (
        'Creating a budget for living alone can be a crucial step towards financial independence. Here are some tips to get you started:',
        'QUESTION',
        1,
        NULL,
        '2007-06-19 08:33:00'
    ),
    (
        'Cooking doesn''t have to be complicated! Here are some simple and delicious recipes perfect for beginners:',
        'QUESTION',
        2,
        NULL,
        '2003-05-15 15:05:43'
    ),
    (
        'Doing laundry may seem daunting, but with the right techniques, you can keep your clothes in great condition. Here are some tips:',
        'QUESTION',
        3,
        NULL,
        '2005-09-20 16:19:00'
    ),
    (
        'Maintaining a tidy home is essential for a clear mind and productivity. Here are some effective cleaning routines to follow:',
        'QUESTION',
        4,
        NULL,
        '2002-09-28 13:43:23'
    ),
    (
        'Time management is crucial for productivity in both work and personal life. Here are some techniques to help you make the most of your time:',
        'QUESTION',
        5,
        NULL,
        '2017-03-12 15:00:26'
    ),
    (
        'Embarking on a job search can be a daunting task. Here are some strategies to help you navigate the process effectively:',
        'QUESTION',
        6,
        NULL,
        '2009-05-19 14:51:44'
    ),
    (
        'Renting your first apartment is a significant milestone. Here are some essential tips to keep in mind during the process:',
        'QUESTION',
        7,
        NULL,
        '2000-08-02 21:43:01'
    ),
    (
        'Understanding healthcare options in Portugal is crucial for your well-being. Here are some key points to consider:',
        'QUESTION',
        8,
        NULL,
        '2011-09-17 16:19:44'
    ),
    (
        'Different types of insurance provide various forms of coverage. Here''s an overview to help you understand your options:',
        'QUESTION',
        9,
        NULL,
        '2004-10-21 18:28:46'
    ),
    (
        'Tax filing can be complex, especially for students and young adults. Here are some tips to help you navigate the process:',
        'QUESTION',
        10,
        NULL,
        '2023-03-24 18:18:23'
    ),
    (
        'Im a new homeowner looking to tackle basic home repairs. What are the essential skills and repairs that every adult should know to maintain their home effectively? Whether its fixing a leaky faucet or handling minor electrical issues, what advice can you share for someone getting started with basic home repairs?',
        'QUESTION',
        11,
        NULL,
        '2017-10-29 08:53:12'
    ),
    (
        'Grocery shopping on a budget is a valuable skill for students. Here are some strategies to help you save money while getting the essentials:',
        'QUESTION',
        12,
        NULL,
        '2020-07-13 11:28:18'
    ),
    (
        'Car maintenance is essential for safety and longevity. Here are some basic maintenance tasks every new driver should know:',
        'QUESTION',
        13,
        NULL,
        '2010-06-04 23:05:10'
    ),
    (
        'Building a professional network is crucial for career growth. Here are some tips for students looking to expand their professional connections:',
        'QUESTION',
        14,
        NULL,
        '2018-11-08 11:46:02'
    ),
    (
        'Taking care of your mental health is just as important as physical health. Here are some strategies to help you manage and prioritize your well-being:',
        'QUESTION',
        15,
        NULL,
        '2006-07-19 00:07:11'
    ),
    (
        'Choosing the right health insurance plan can be overwhelming. Here are some tips to help you make an informed decision:',
        'QUESTION',
        16,
        NULL,
        '2008-06-28 15:44:34'
    ),
    (
        'Apartment hunting requires careful consideration. Here are some tips to help you find the right place for you:',
        'QUESTION',
        17,
        NULL,
        '2013-04-05 23:25:20'
    ),
    (
        'University finances can be a complex aspect to manage. Here are some tips on scholarships and grants to help you navigate this area:',
        'QUESTION',
        18,
        NULL,
        '2006-04-27 10:59:08'
    ),
    (
        'Finding affordable textbooks and study resources can significantly impact your budget. Here are some strategies to help you save on educational materials:',
        'QUESTION',
        19,
        NULL,
        '2020-09-10 09:55:50'
    ),
    (
        'Consider renting, buying used, or using digital versions of textbooks. Explore online resources, open-source textbooks, and library resources for supplementary materials. Collaborate with classmates to share or borrow textbooks.',
        'QUESTION',
        19,
        NULL,
        '2006-03-23 06:21:53'
    ),
    (
        'Navigating Portos housing market involves setting a realistic budget, researching neighborhoods, and utilizing online platforms like Idealista. Consultation with local real estate agents and attendance at housing fairs can provide valuable insights into safe and affordable housing options in this vibrant city.',
        'QUESTION',
        20,
        NULL,
        '2005-04-11 06:48:37'
    ),
    (
        'Effective study techniques are essential for retaining information. Here are some strategies to help you study more efficiently:',
        'QUESTION',
        21,
        NULL,
        '2009-03-24 05:00:34'
    ),
    (
        'Exploring extracurricular activities can enrich your university experience. Here are some options to consider:',
        'QUESTION',
        22,
        NULL,
        '2016-02-05 09:55:08'
    ),
    (
        'Handling academic pressure and burnout is crucial for your well-being. Here are some strategies to help you manage stress effectively:',
        'QUESTION',
        23,
        NULL,
        '2018-11-23 19:11:56'
    ),
    (
        'Understanding tenant rights is important for a positive renting experience. Here are some key rights to be aware of in Porto:',
        'QUESTION',
        24,
        NULL,
        '2000-10-01 13:34:47'
    ),
    (
        'Budgeting for student life is a crucial skill. Here are some tips and tools to help you manage your finances effectively:',
        'QUESTION',
        25,
        NULL,
        '2011-07-09 00:55:49'
    ),
    (
        'Staying active and healthy while juggling a busy student life is important. Here are some strategies to help you prioritize your well-being:',
        'QUESTION',
        26,
        NULL,
        '2014-02-11 15:13:22'
    ),
    (
        'Finding reliable transportation in Porto is essential for getting around the city. Here are some options to consider:',
        'QUESTION',
        27,
        NULL,
        '2000-04-02 00:30:52'
    ),
    (
        'Maximizing productivity in university projects is crucial for academic success. Here are some strategies to help you work efficiently:',
        'QUESTION',
        28,
        NULL,
        '2017-01-02 06:38:44'
    ),
    (
        'Mastering time management for assignments and deadlines is crucial for academic success. Here are some techniques to help you stay on track:',
        'QUESTION',
        29,
        NULL,
        '2019-11-29 20:00:06'
    ),
    (
        'Navigating cultural events and festivals in Porto is a great way to experience the local culture. Here are some events to look out for:',
        'QUESTION',
        30,
        NULL,
        '2014-11-17 08:33:22'
    ),
    (
        'Maintaining a healthy diet on a student budget is possible with the right strategies. Here are some tips to help you make nutritious choices without breaking the bank:',
        'QUESTION',
        31,
        NULL,
        '2014-08-26 08:05:32'
    ),
    (
        'Choosing a suitable study space can significantly impact your productivity. Here are some tips to help you find the right environment for focused studying:',
        'QUESTION',
        32,
        NULL,
        '2004-11-04 22:38:11'
    ),
    (
        'Finding internship opportunities in Porto can be a valuable step towards building your career. Here are some strategies to help you secure an internship:',
        'QUESTION',
        33,
        NULL,
        '2006-10-13 23:48:08'
    ),
    (
        'Effective group projects require clear communication and collaboration. Here are some tips to ensure success in group assignments at university:',
        'QUESTION',
        34,
        NULL,
        '2012-01-09 09:50:33'
    ),
    (
        'Navigating Public Transportation in Porto can be a convenient way to explore the city. Here are some tips:',
        'QUESTION',
        35,
        NULL,
        '2020-03-31 17:10:33'
    ),
    (
        'Porto has an extensive public transportation system, including buses, trams, and the metro. Get a rechargeable transportation card for ease of use and savings. Familiarize yourself with routes and schedules for a smooth commute.',
        'QUESTION',
        36,
        NULL,
        '2006-06-29 08:31:24'
    ),
    (
        'Balancing Social Life and Academics in University is essential for a well-rounded experience. Here are some strategies:',
        'QUESTION',
        37,
        NULL,
        '2021-01-08 14:57:22'
    ),
    (
        'Create a realistic schedule that includes study time, social activities, and self-care. Join clubs or organizations to meet like-minded individuals. Communicate with peers and professors about any challenges you may face.',
        'QUESTION',
        38,
        NULL,
        '2022-12-23 20:40:13'
    ),
    (
        'Maximizing Productivity in University Projects: What strategies can help me maximize productivity when working on university projects?',
        'QUESTION',
        39,
        NULL,
        '2002-12-20 17:14:18'
    ),
    (
        'Strategies for Balancing Work and Studies: How can I effectively manage both my job and university commitments?',
        'QUESTION',
        40,
        NULL,
        '2022-05-30 19:02:59'
    ),
    (
        'Cooking for One: What are some quick and nutritious recipes suitable for solo dining?',
        'QUESTION',
        41,
        NULL,
        '2006-03-08 07:19:24'
    ),
    (
        'Finding Affordable and Safe Housing in Porto: What practical tips can help me secure affordable and safe housing in Porto?',
        'QUESTION',
        42,
        NULL,
        '2013-02-15 05:06:23'
    ),
    (
        'Time Management for Juggling a Job and University: What strategies can I use to balance both work and university studies effectively?',
        'QUESTION',
        43,
        NULL,
        '2017-08-04 23:40:38'
    ),
    (
        'Networking Events in Porto: Where to Start: What are some effective ways to initiate my networking journey at events in Porto?',
        'QUESTION',
        44,
        NULL,
        '2011-11-18 03:48:56'
    ),
    (
        'Coping with Stress and Anxiety in University Life: How can I effectively cope with stress and anxiety while navigating university life?',
        'QUESTION',
        45,
        NULL,
        '2009-05-11 10:05:56'
    ),
    (
        'Understanding Health Insurance Policies in Portugal: What are the key aspects to understand about health insurance policies in Portugal?',
        'QUESTION',
        46,
        NULL,
        '2014-04-14 05:41:31'
    ),
    (
        'Simple Cleaning Hacks for a Tidy Student Apartment: What are some easy cleaning hacks to maintain a tidy student apartment?',
        'QUESTION',
        47,
        NULL,
        '2008-10-03 19:48:17'
    ),
    (
        'Budget-Friendly Tips for Grocery Shopping in Porto: How can I save money while grocery shopping in Porto on a budget?',
        'QUESTION',
        48,
        NULL,
        '2023-05-18 12:03:01'
    ),
    (
        'How to Stay Motivated and Productive During Exams: What strategies can help me stay motivated and productive during exam periods?',
        'QUESTION',
        49,
        NULL,
        '2008-07-15 04:53:25'
    ),
    (
        'Navigating Public Transportation in Porto: What are the best strategies for navigating public transportation in Porto?',
        'QUESTION',
        50,
        NULL,
        '2009-12-06 11:35:44'
    ),
    (
        'Balancing Social Life and Academics in University: How can I find the right balance between my social life and academic commitments?',
        'QUESTION',
        51,
        NULL,
        '2018-10-08 16:26:17'
    ),
    (
        'Cooking Skills: Beyond Instant Noodles: What cooking skills should I develop beyond basic instant noodles?',
        'QUESTION',
        52,
        NULL,
        '2013-11-10 17:07:33'
    ),
    (
        'Finding Part-Time Jobs for Students in Porto: What are effective ways to find part-time jobs for students in Porto?',
        'QUESTION',
        53,
        NULL,
        '2008-05-17 01:45:55'
    ),
    (
        'Effective Strategies for Time Management in University: What strategies can I use for effective time management in university?',
        'QUESTION',
        54,
        NULL,
        '2008-12-21 21:26:27'
    ),
    (
        'How to Choose the Right Health Insurance Plan: What factors should I consider when choosing the right health insurance plan?',
        'QUESTION',
        55,
        NULL,
        '2002-08-30 06:42:51'
    ),
    (
        'Apartment Hunting Tips: What to Look For: What are some essential tips for apartment hunting, and what should I look for?',
        'QUESTION',
        56,
        NULL,
        '2016-10-01 12:12:01'
    ),
    (
        'Navigating University Finances: Scholarships and Grants: How can I navigate university finances and explore scholarships and grants?',
        'QUESTION',
        57,
        NULL,
        '2006-04-08 00:02:21'
    ),
    (
        'Finding Affordable Textbooks and Study Resources: What strategies can I use to find affordable textbooks and study resources?',
        'QUESTION',
        58,
        NULL,
        '2022-03-09 15:47:37'
    ),
    (
        'Cooking for a Crowd: Easy Recipes for Hosting: What are some easy recipes for cooking for a crowd when hosting?',
        'QUESTION',
        59,       
        NULL,
        '2005-02-04 10:51:02'
    ),
    (
        'Effective Study Techniques for Retaining Information: What study techniques are effective for retaining information?',
        'QUESTION',
        60,
        NULL,
        '2020-11-05 16:09:43'
    ),
    (
        'Navigating Public Transportation in Porto: What are the best strategies for navigating public transportation in Porto?',
        'QUESTION',
        61,
        NULL,
        '2013-05-08 12:22:07'
    ),
    (
        'Balancing Social Life and Academics in University: How can I find the right balance between my social life and academic commitments?',
        'QUESTION',
        62,
        NULL,
        '2013-10-07 19:54:25'
    ),
    (
        'Cooking Skills: Beyond Instant Noodles: What cooking skills should I develop beyond basic instant noodles?',
        'QUESTION',
        63,
        NULL,
        '2003-07-10 13:00:04'
    ),
    (
        'Finding Part-Time Jobs for Students in Porto: What are effective ways to find part-time jobs for students in Porto?',
        'QUESTION',
        64,
        NULL,
        '2022-12-22 01:14:46'
    ),
    (
        'Effective Strategies for Time Management in University: What strategies can I use for effective time management in university?',
        'QUESTION',
        65,
        NULL,
        '2019-01-13 20:31:22'
    ),
    (
        'How to Choose the Right Health Insurance Plan: What factors should I consider when choosing the right health insurance plan?',
        'QUESTION',
        66,
        NULL,
        '2023-01-12 17:20:01'
    ),
    (
        'Apartment Hunting Tips: What to Look For: What are some essential tips for apartment hunting, and what should I look for?',
        'QUESTION',
        67,
        NULL,
        '2015-11-20 23:15:05'
    ),
    (
        'Navigating University Finances: Scholarships and Grants: How can I navigate university finances and explore scholarships and grants?',
        'QUESTION',
        68,
        NULL,
        '2014-02-17 12:01:43'
    ),
    (
        'Finding Affordable Textbooks and Study Resources: What strategies can I use to find affordable textbooks and study resources?',
        'QUESTION',
        69,
        NULL,
        '2014-12-15 12:15:18'
    ),
    (
        'Cooking for a Crowd: Easy Recipes for Hosting: What are some easy recipes for cooking for a crowd when hosting?',
        'QUESTION',
        70,
        NULL,
        '2013-06-06 14:38:26'
    ),
    (
        'Effective Study Techniques for Retaining Information: What study techniques are effective for retaining information?',
        'QUESTION',
        71,
        NULL,
        '2020-05-11 00:31:14'
    ),
    (
        'Exploring Extracurricular Activities in Porto: How can I explore and get involved in extracurricular activities in Porto?',
        'QUESTION',
        72,
        NULL,
        '2000-08-09 22:44:11'
    ),
    (
        'Handling Academic Pressure and Burnout: What strategies can I use to handle academic pressure and prevent burnout?',
        'QUESTION',
        73,
        NULL,
        '2008-12-07 11:08:42'
    ),
    (
        'Understanding Tenant Rights in Porto: What are the tenant rights I should be aware of when living in Porto?',
        'QUESTION',
        74,
        NULL,
        '2001-08-02 18:50:34'
    ),
    (
        'Budgeting for Student Life: Tips and Tools: How can I effectively budget for my student life using practical tips and tools?',
        'QUESTION',
        75,
        NULL,
        '2022-11-23 21:53:04'
    ),
    (
        'Staying Active and Healthy in a Busy Student Life: What are some strategies for staying active and healthy amidst a busy student life?',
        'QUESTION',
        76,
        NULL,
        '2019-11-27 06:06:51'
    ),
    (
        'Finding Reliable Transportation in Porto: What are the reliable transportation options available in Porto?',
        'QUESTION',
        77,
        NULL,
        '2005-03-17 15:00:11'
    ),
    (
        'Maximizing Productivity in University Projects: What strategies can help me maximize productivity when working on university projects?',
        'QUESTION',
        78,
        NULL,
        '2013-09-30 17:15:43'
    ),
    (
        'Mastering Time Management for Assignments and Deadlines: How can I master time management for assignments and deadlines in university?',
        'QUESTION',
        79,
        NULL,
        '2000-07-12 20:22:04'
    ),
    (
        'Navigating Cultural Events and Festivals in Porto: How can I make the most of cultural events and festivals in Porto?',
        'QUESTION',
        80,
        NULL,
        '2007-02-17 09:13:31'
    ),
    (
        'Healthy Eating on a Student Budget: What are some tips for maintaining a healthy diet on a tight student budget?',
        'QUESTION',
        81,
        NULL,
        '2018-03-07 20:38:36'
    ),
    (
        'How to Choose a Suitable Study Space: What factors should I consider when choosing a suitable study space?',
        'QUESTION',
        82,
        NULL,
        '2014-06-02 02:42:29'
    ),
    (
        'Finding Internship Opportunities in Porto: How can I find valuable internship opportunities in Porto?',
        'QUESTION',
        83,
        NULL,
        '2018-02-20 14:16:02'
    ),
    (
        'As an introverted engineer interested in networking within the tech industry, Id love some advice. How can I navigate the world of Networking in Tech and overcome the challenges associated with being introverted? What tips do you have for building meaningful connections and advancing in the tech field while staying true to my introverted nature?',
        'QUESTION',
        84,
        NULL,
        '2023-08-09 09:59:57'
    ),
    (
        'Cooking Skills: From Basics to Gourmet: How can I progress in my cooking skills from basic to gourmet?',
        'QUESTION',
        85,
        NULL,
        '2013-03-09 13:31:21'
    ),
    (
        'Balancing Work, Studies, and Personal Life: What strategies can I use to balance work, studies, and personal life effectively?',
        'QUESTION',
        86,
        NULL,
        '2010-03-10 14:47:13'
    ),
    (
        'Networking Strategies for Introverted Students: What networking strategies are effective for introverted students?',
        'QUESTION',
        87,
        NULL,
        '2016-01-24 16:04:01'
    ),
    (
        'Coping with Homesickness and Adjusting to University Life: How can I cope with homesickness and adjust to university life?',
        'QUESTION',
        88,
        NULL,
        '2009-03-22 01:39:50'
    ),
    (
        'Understanding Healthcare Services for Students in Porto: What healthcare services are available for students in Porto?',
        'QUESTION',
        89,
        NULL,
        '2019-10-30 00:59:46'
    ),
    (
        'Quick and Easy Cleaning Hacks for Students: What are some quick and easy cleaning hacks suitable for students?',
        'QUESTION',
        90,
        NULL,
        '2014-12-11 07:09:02'
    ),
    (
        'Financial Planning for University Students: How can I effectively plan my finances as a university student?',
        'QUESTION',
        91,
        NULL,
        '2021-12-14 19:44:38'
    ),
    (
        'Exploring Porto: Hidden Gems and Must-Visit Places: What hidden gems and must-visit places should I explore in Porto?',
        'QUESTION',
        92,
        NULL,
        '2012-10-10 08:45:19'
    ),
    (
        'Mastering Algorithm Design and Optimization: How can I master the design and optimization of algorithms?',
        'QUESTION',
        93,
        NULL,
        '2022-07-02 17:56:16'
    ),
    (
        'Setting Up a Personal Development Plan: What steps should I take to set up a personal development plan?',
        'QUESTION',
        94,
        NULL,
        '2021-06-11 17:50:17'
    ),
    (
        'Networking in Tech: Tips for Introverted Engineers: What networking tips are effective for introverted engineers in the tech industry?',
        'QUESTION',
        95,
        NULL,
        '2004-05-14 17:48:20'
    ),
    (
        'Finding Part-Time Work in the Tech Industry: How can I find part-time work in the tech industry?',
        'QUESTION',
        96,
        NULL,
        '2002-01-03 04:38:18'
    ),
    (
        'Effective Strategies for Debugging Complex Code: What strategies can help me effectively debug complex code?',
        'QUESTION',
        97,
        NULL,
        '2023-07-25 00:40:08'
    ),
    (
        'Understanding Intellectual Property in Tech Projects: What do I need to know about intellectual property in tech projects?',
        'QUESTION',
        98,
        NULL,
        '2017-09-12 12:20:20'
    ),
    (
        'Navigating Work-University-Life Balance in Porto: How can I navigate the balance between work, university, and personal life in Porto?',
        'QUESTION',
        99,
        NULL,
        '2018-06-10 17:36:42'
    ),
    (
        'Balancing Coding Projects and Personal Time: What strategies can I use to balance coding projects and personal time effectively?',
        'QUESTION',
        100,
        NULL,
        '2022-10-19 20:43:07'
    ),
    (
        'Budget-Friendly Travel Tips for Students in Porto: What budget-friendly travel tips can I follow as a student in Porto?',
        'QUESTION',
        101,
        NULL,
        '2014-07-17 11:50:28'
    ),
    (
        'Building Soft Skills for a Successful Tech Career: How can I build soft skills for a successful career in the tech industry?',
        'QUESTION',
        102,
        NULL,
        '2006-11-30 13:34:06'
    ),
    (
        'Healthy Snacking Habits for Busy Students: What are some healthy snacking habits suitable for busy students?',
        'QUESTION',
        103,
        NULL,
        '2022-10-16 19:48:38'
    ),
    (
        'Maximizing Productivity in University Projects requires effective planning and execution. Here are some tips:',
        'QUESTION',
        104,
        NULL,
        '2003-05-04 06:03:51'
    ),
    (
        'Break down larger projects into manageable tasks and set deadlines. Use project management tools to track progress. Collaborate with team members and utilize resources available at your university.',
        'QUESTION',
        105,
        NULL,
        '2018-08-21 07:30:29'
    ),
    (
        'How to Choose a Suitable Study Space is a common challenge for students. Here are some considerations:',
        'QUESTION',
        106,
        NULL,
        '2003-02-21 07:59:09'
    ),
    (
        'Find a quiet and comfortable space with minimal distractions. Experiment with different environments to discover what works best for you. Ensure you have all necessary study materials within reach.',
        'QUESTION',
        107,
        NULL,
        '2007-05-25 17:44:10'
    ),
    (
        'Finding Internship Opportunities in Porto is a crucial step toward gaining real-world experience. Here are some strategies:',
        'QUESTION',
        108,
        NULL,
        '2012-09-05 02:53:30'
    ),
    (
        'Utilize university career services, attend job fairs, and explore online platforms for internship listings. Tailor your resume and cover letter for each application. Network with professionals in your desired industry.',
        'QUESTION',
        109,
        NULL,
        '2005-06-19 02:09:23'
    ),
    (
        'Mastering Object-Oriented Programming (OOP) requires a deep understanding of its principles, including classes, objects, inheritance, polymorphism, and encapsulation. Practice through coding exercises and real-world projects is essential for honing OOP skills, allowing developers to create efficient and scalable software solutions.',
        'QUESTION',
        110,
        NULL,
        '2002-11-08 21:16:30'
    ),
    (
        'Achieving a balance between freelancing and university studies demands effective time management and clear communication with clients and professors. Creating a structured schedule, setting realistic goals, and practicing self-care contribute to a sustainable and successful approach, allowing individuals to excel in both arenas.',
        'QUESTION',
        111,
        NULL,
        '2005-04-22 07:12:43'
    ),
    (
        'Understanding sustainable tech practices involves adopting eco-friendly technologies, considering the environmental impact of tech solutions, and implementing circular economy principles. Staying informed about advancements in sustainable technology and incorporating green development processes are crucial steps toward building a more environmentally conscious tech industry.',
        'QUESTION',
        112,
        NULL,
        '2004-11-09 22:47:09'
    );

INSERT INTO
    content_versions (body, type, answer_id, question_id, date)
VALUES
    (
        'To create a budget for living alone, start by calculating your monthly income and listing all your expenses. Allocate a portion for rent, utilities, groceries, and savings. Make adjustments as needed to stay within your means.',
        'ANSWER',
        1,
        NULL,
        '2007-12-14 15:47:21'
    ),
    (
        'Try making a hearty vegetable stir-fry or a classic spaghetti aglio e olio. These recipes are easy to follow and require minimal ingredients.',
        'ANSWER',
        2,
        NULL,
        '2014-03-23 12:43:10'
    ),
    (
        'Sort your laundry by color and fabric type to prevent color bleeding or fabric damage. Use the right amount of detergent and avoid overloading the machine. Follow garment care labels for best results.',
        'ANSWER',
        3,
        NULL,
        '2001-12-29 18:20:21'
    ),
    (
        'Establish a daily routine that includes tasks like making the bed, doing the dishes, and wiping down surfaces. Set aside time for deeper cleaning on a weekly basis, focusing on different areas of your home.',
        'ANSWER',
        4,
        NULL,
        '2013-09-01 12:02:16'
    ),
    (
        'Consider using techniques like the Pomodoro Technique for focused work sessions, and prioritize tasks based on their importance and deadlines. Set realistic goals and use tools like calendars and to-do lists.',
        'ANSWER',
        5,
        NULL,
        '2014-08-21 13:50:41'
    ),
    (
        'Start by identifying your skills, interests, and career goals. Tailor your resume and cover letter to each job application. Network with professionals in your field and utilize online job boards.',
        'ANSWER',
        6,
        NULL,
        '2013-06-12 05:57:55'
    ),
    (
        'Review your budget to determine a comfortable rent range. Research neighborhoods and visit potential apartments. Read and understand the terms of the lease agreement before signing.',
        'ANSWER',
        7,
        NULL,
        '2009-11-07 01:54:55'
    ),
    (
        'Portugal offers both public and private healthcare options. Register for the National Health Service (SNS) and consider supplementary private insurance for additional coverage. Familiarize yourself with local clinics and hospitals.',
        'ANSWER',
        8,
        NULL,
        '2000-08-06 03:18:27'
    ),
    (
        'Explore options like health insurance, renters insurance, and car insurance. Understand the premiums, deductibles, and coverage limits associated with each type. Consider consulting with an insurance agent for personalized advice.',
        'ANSWER',
        9,
        NULL,
        '2018-12-11 10:11:28'
    ),
    (
        'Gather all necessary documents, including W-2 forms and receipts for deductions. Consider using tax software or consulting a tax professional for guidance. Double-check your return for accuracy before filing.',
        'ANSWER',
        10,
        NULL,
        '2007-12-25 06:33:48'
    ),
    (
        'Learn how to fix leaky faucets, unclog drains, and patch small holes in walls. Familiarize yourself with the electrical panel and know how to reset a circuit breaker. Keep a basic toolkit handy for minor repairs.',
        'ANSWER',
        11,
        NULL,
        '2016-09-27 21:03:07'
    ),
    (
        'Plan meals ahead of time, create a shopping list, and stick to it. Opt for store brands and generic products, and take advantage of discounts and sales. Consider buying non-perishable items in bulk for additional savings.',
        'ANSWER',
        12,
        NULL,
        '2015-05-17 03:35:45'
    ),
    (
        'Learn how to check and change the oil, replace air filters, and monitor tire pressure. Familiarize yourself with the location of essential fluids like coolant and brake fluid. Keep a basic toolkit and emergency supplies in your car.',
        'ANSWER',
        13,
        NULL,
        '2004-11-24 16:43:03'
    ),
    (
        'Attend networking events, workshops, and conferences related to your field of interest. Connect with professors, classmates, and professionals on platforms like LinkedIn. Don''t be afraid to reach out and ask for advice or informational interviews.',
        'ANSWER',
        14,
        NULL,
        '2016-07-08 03:10:49'
    ),
    (
        'Practice self-care routines, engage in activities you enjoy, and seek support from friends, family, or professionals if needed. Prioritize sleep, exercise, and nutrition to maintain a balanced and healthy lifestyle.',
        'ANSWER',
        15,
        NULL,
        '2016-07-11 02:31:41'
    ),
    (
        'Consider factors like coverage options, premiums, deductibles, and network of healthcare providers. Evaluate whether the plan meets your specific needs and preferences. Compare multiple options before making a final decision.',
        'ANSWER',
        16,
        NULL,
        '2018-01-23 17:53:57'
    ),
    (
        'Determine your budget, preferred location, and essential amenities. Schedule viewings and ask questions about lease terms and policies. Take your time to assess each option before making a decision.',
        'ANSWER',
        17,
        NULL,
        '2004-05-22 16:09:17'
    ),
    (
        'Research and apply for scholarships that match your academic achievements and interests. Check with your university''s financial aid office for available grants and awards. Keep track of deadlines and requirements for each application.',
        'ANSWER',
        18,
        NULL,
        '2009-07-21 22:40:33'
    ),
    (
        'Cooking for a crowd can be a fun and rewarding experience. Here are some recipes perfect for hosting gatherings:',
        'ANSWER',
        20,
        NULL,
        '2016-12-11 17:06:15'
    ),
    (
        'Try making a big batch of chili or a hearty vegetable lasagna. These recipes are crowd-pleasers and can be prepared in advance for convenience.',
        'ANSWER',
        20,
        NULL,
        '2004-09-10 16:59:02'
    ),
    (
        'Well, you know, home repairs can be a real hassle. My advice? Just ignore those issues until they become major problems. Who needs functioning faucets or working light switches anyway? Embrace the chaos, I say!',
        'ANSWER',
        21,
        NULL,
        '2018-08-15 19:40:02'
    ),
    (
        'While home repairs can be intimidating, it''s essential to address issues promptly. Start by learning basic plumbing skills like fixing a leaky faucet or unclogging drains. Familiarize yourself with simple electrical work, such as changing a light switch. Take it one step at a time, and consider watching online tutorials for guidance. Building these skills gradually will help you become more confident in handling basic home repairs.',
        'ANSWER',
        22,
        NULL,
        '2020-01-15 14:14:57'
    ),
    (
        'Practice time management, set realistic goals, and prioritize self-care activities. Seek support from professors, counselors, or support groups if you''re feeling overwhelmed. Remember that it''s okay to ask for help.',
        'ANSWER',
        23,
        NULL,
        '2007-03-27 09:42:06'
    ),
    (
        'Familiarize yourself with laws regarding rent increases, eviction procedures, and required maintenance. Keep records of communication with your landlord and understand your right to privacy within the rented property.',
        'ANSWER',
        24,
        NULL,
        '2016-08-21 19:34:01'
    ),
    (
        'Track your income and expenses using a budgeting app or spreadsheet. Set clear financial goals and allocate funds for essentials, savings, and discretionary spending. Periodically review and adjust your budget as needed.',
        'ANSWER',
        25,
        NULL,
        '2007-08-07 03:36:54'
    ),
    (
        'Incorporate physical activity into your routine, even if it''s just a short walk or home workout. Plan balanced meals and stay hydrated to maintain energy levels. Get enough restful sleep to support overall health.',
        'ANSWER',
        26,
        NULL,
        '2007-05-15 12:15:34'
    ),
    (
        'Explore public transportation, including buses, trams, and the metro system. Consider using a bicycle for short distances, or use ride-sharing services for convenience. Familiarize yourself with local routes and schedules.',
        'ANSWER',
        27,
        NULL,
        '2010-07-28 12:23:42'
    ),
    (
        'Break larger projects into smaller, manageable tasks and set realistic milestones. Use project management tools to track progress and allocate time for revisions and edits. Don''t hesitate to seek guidance or feedback from professors or peers.',
        'ANSWER',
        28,
        NULL,
        '2000-08-28 16:33:30'
    ),
    (
        'Use a planner or digital calendar to keep track of assignment due dates and exam schedules. Prioritize tasks based on deadlines and importance. Break down larger assignments into smaller, manageable tasks.',
        'ANSWER',
        29,
        NULL,
        '2023-07-01 14:41:26'
    ),
    (
        'Attend events like the São João Festival, Fantasporto Film Festival, and NOS Primavera Sound music festival. Explore local museums, art galleries, and theaters for cultural experiences.',
        'ANSWER',
        30,
        NULL,
        '2023-07-01 14:41:26'
    ),
    (
        'Plan balanced meals that incorporate affordable protein sources like legumes, eggs, and tofu. Buy seasonal fruits and vegetables and consider frozen options for longer shelf life. Opt for store brands and generic products to save money.',
        'ANSWER',
        31,
        NULL,
        '2014-07-22 22:45:00'
    ),
    (
        'Find a space with good lighting, comfortable seating, and minimal distractions. Experiment with different environments, such as libraries, quiet cafes, or dedicated study rooms. Ensure you have all necessary supplies and resources within reach.',
        'ANSWER',
        32,
        NULL,
        '2012-08-03 22:58:14'
    ),
    (
        'Research local companies and organizations in your field of interest. Update your resume and cover letter to highlight relevant skills and experiences. Network with professors, professionals, and alumni for potential leads.',
        'ANSWER',
        33,
        NULL,
        '2008-06-02 00:45:03'
    ),
    (
        'Seek guidance and find an answer that assists with your inquiries. Explore various resources and seek advice from professionals in the field.',
        'ANSWER',
        19,
        NULL,
        '2003-02-24 21:44:27'
    ),
    (
        'Discover insights for planning a memorable trip and explore budget-friendly options for accommodations and activities. Research different destinations and seek advice from seasoned travelers.',
        'ANSWER',
        34,
        NULL,
        '2020-09-15 08:22:11'
    ),
    (
        'Explore strategies for managing personal finances and navigating economic fluctuations. Consider investment opportunities and seek guidance from financial advisors for long-term stability.',
        'ANSWER',
        35,
        NULL,
        '2019-08-11 14:55:27'
    ),
    (
        'Find ways to optimize travel expenses and plan a cost-effective journey. Utilize travel rewards programs and seek advice from travel enthusiasts for maximizing your travel budget.',
        'ANSWER',
        36,
        NULL,
        '2023-06-05 09:37:18'
    ),
    (
        'Learn about economic trends and strategies for personal financial growth. Stay informed about market changes and seek advice from economists or financial analysts for informed decisions.',
        'ANSWER',
        37,
        NULL,
        '2015-11-30 17:48:22'
    ),
    (
        'Plan a budget-friendly trip by researching affordable destinations and exploring discount options for accommodations and transportation. Seek advice from experienced travelers for money-saving tips.',
        'ANSWER',
        38,
        NULL,
        '2016-04-25 23:10:14'
    ),
    (
        'Plan a budget-friendly trip by researching affordable destinations and exploring discount options for accommodations and transportation. Seek advice from experienced travelers for money-saving tips.',
        'ANSWER',
        39,
        NULL,
        '2011-04-25 23:10:14'
    ),
    (
        'Plan a budget-friendly trip by researching affordable destinations and exploring discount options for accommodations and transportation. Seek advice from experienced travelers for money-saving tips.',
        'ANSWER',
        40,
        NULL,
        '2016-07-21 03:10:14'
    ),
    (
        'Understand economic principles and their impact on personal finance. Explore investment opportunities and seek advice from financial experts for making sound financial decisions.',
        'ANSWER',
        41,
        NULL,
        '2010-04-30 07:12:59'
    ),
    (
        'Prepare for an economical travel experience by budgeting and seeking affordable travel options. Engage with travel communities for advice on cost-effective travel planning.',
        'ANSWER',
        42,
        NULL,
        '2007-06-20 18:44:05'
    ),
    (
        'Prepare for an economical travel experience by budgeting and seeking affordable travel options. Engage with travel communities for advice on cost-effective travel planning.',
        'ANSWER',
        43,
        NULL,
        '2007-06-20 18:44:05'
    ),
    (
        'Prepare for an economical travel experience by budgeting and seeking affordable travel options. Engage with travel communities for advice on cost-effective travel planning.',
        'ANSWER',
        44,
        NULL,
        '2007-06-20 18:44:05'
    ),
    (
        'Stay updated on economic news and explore strategies for managing personal finances. Seek advice from financial professionals to navigate economic challenges effectively.',
        'ANSWER',
        45,
        NULL,
        '2014-08-17 10:25:33'
    ),
    (
        'Plan a trip on a tight budget by exploring low-cost travel options and seeking advice from experienced travelers for affordable travel hacks.',
        'ANSWER',
        46,
        NULL,
        '2018-07-02 16:29:47'
    ),
    (
        'Plan a trip on a tight budget by exploring low-cost travel options and seeking advice from experienced travelers for affordable travel hacks.',
        'ANSWER',
        47,
        NULL,
        '2018-07-02 16:29:47'
    ),
    (
        'Plan a trip on a tight budget by exploring low-cost travel options and seeking advice from experienced travelers for affordable travel hacks.',
        'ANSWER',
        48,
        NULL,
        '2018-07-02 16:29:47'
    ),
    (
        'Learn about economic indicators and strategies for personal financial planning. Seek advice from financial advisors to make informed decisions about your financial future.',
        'ANSWER',
        49,
        NULL,
        '2022-03-28 19:50:10'
    ),
    (
        'Explore budget-friendly travel ideas and seek advice from travel experts for planning an economical yet enjoyable trip.',
        'ANSWER',
        50,
        NULL,
        '2012-12-09 03:45:22'
    );


INSERT INTO
    question_tag (question_id, tag_id)
VALUES
    (21, 4),
    (34, 2),
    (2, 6),
    (43, 9),
    (42, 12),
    (12, 8),
    (4, 2),
    (64, 5),
    (32, 2),
    (1, 5),
    (56, 14),
    (3, 12),
    (53, 6),
    (87, 9),
    (98, 5),
    (23, 13),
    (13, 11),
    (63, 2),
    (34, 14),
    (55, 5),
    (72, 3),
    (91, 6),
    (77, 1),
    (31, 7),
    (16, 2),
    (4, 9),
    (56, 1),
    (75, 15),
    (43, 12),
    (25, 11),
    (52, 8),
    (75, 4),
    (65, 5),
    (52, 1),
    (24, 5),
    (52, 4),
    (98, 2),
    (12, 5),
    (5, 8),
    (75, 8),
    (59, 15),
    (92, 14),
    (58, 2),
    (81, 1),
    (52, 11),
    (56, 6),
    (23, 10),
    (1, 1), -- "How to Create a Budget for Living Alone" matches "Budgeting"
    (2, 2), -- "Simple Recipes for Beginners" matches "Cooking Basics"
    (3, 3), -- "Tips for Doing Laundry Without Ruining Clothes" matches "Laundry Tips"
    (4, 4), -- "Effective Cleaning Routines for a Tidy Home" matches "Effective Cleaning Strategies"
    (5, 5), -- "Time Management Techniques for Productivity" matches "Time Management Techniques"
    (6, 6), -- "Strategies for a Successful Job Search" matches "Job Search Success"
    (7, 7), -- "Essential Tips for Renting Your First Apartment" matches "Renting Apartments"
    (8, 8), -- "Navigating Healthcare Options in Portugal" matches "Healthcare Insights"
    (9, 9), -- "Understanding Different Types of Insurance" matches "Insurance Demystified"
    (10, 10), -- "Tax Filing Tips for Students and Young Adults" matches "Smart Tax Planning"
    (11, 11), -- "Basic Home Repairs Every Adult Should Know" matches "DIY Home Repairs"
    (12, 12), -- "Smart Grocery Shopping Strategies on a Budget" matches "Grocery Shopping Hacks"
    (13, 13), -- "Essential Car Maintenance for New Drivers" matches "Car Maintenance Essentials"
    (14, 14), -- "Building a Professional Network as a Student" matches "Networking for Success"
    (15, 15), -- "Managing Mental Health and Well-being" matches "Mental Health and Well-being"
    (17, 1), -- "Navigating University Life: Study Tips and Hacks" matches "Budgeting"
    (18, 2), -- "Balancing Work and Studies: Strategies for Success" matches "Cooking Basics"
    (19, 3), -- "Cooking for One: Quick and Nutritious Recipes" matches "Laundry Tips"
    (20, 4), -- "How to Find Affordable and Safe Housing in Porto" matches "Effective Cleaning Strategies"
    (50, 10);

-- "Effective Communication in Professional Settings" matches "Smart Tax Planning"
INSERT INTO
    annexes (type, file_path, version_id)
VALUES
    ('FILE', '/UserFiles/files/file2.txt', 3),
    ('FILE', '/UserFiles/files/file3.pdf', 4),
    ('FILE', '/UserFiles/files/file4.doc', 5),
    ('FILE', '/UserFiles/files/file5.xlsx', 6),
    ('FILE', '/UserFiles/files/file6.csv', 7),
    ('FILE', '/UserFiles/files/file7.zip', 8),
    ('FILE', '/UserFiles/files/file8.png', 9),
    ('FILE', '/UserFiles/files/file9.jpg', 10),
    ('FILE', '/UserFiles/files/file10.txt', 11),
    ('FILE', '/UserFiles/files/file11.pdf', 12),
    ('FILE', '/UserFiles/files/file12.doc', 13),
    ('FILE', '/UserFiles/files/file13.xlsx', 14),
    ('FILE', '/UserFiles/files/file14.csv', 15),
    ('FILE', '/UserFiles/files/file15.zip', 16),
    ('FILE', '/UserFiles/files/file16.png', 17),
    ('FILE', '/UserFiles/files/file31.txt', 31),
    ('FILE', '/UserFiles/files/file32.pdf', 32),
    ('FILE', '/UserFiles/files/file33.doc', 33),
    ('FILE', '/UserFiles/files/file34.xlsx', 34),
    ('FILE', '/UserFiles/files/file35.csv', 35),
    ('FILE', '/UserFiles/files/file36.zip', 36),
    ('FILE', '/UserFiles/files/file37.png', 37),
    ('FILE', '/UserFiles/files/file38.jpg', 38),
    ('FILE', '/UserFiles/files/file39.txt', 39),
    ('FILE', '/UserFiles/files/file40.pdf', 40),
    ('FILE', '/UserFiles/files/file41.doc', 41),
    ('IMAGE', '/UserFiles/images/image21.jpg', 42),
    ('IMAGE', '/UserFiles/images/image22.png', 43),
    ('IMAGE', '/UserFiles/images/image23.jpg', 44),
    ('IMAGE', '/UserFiles/images/image24.png', 45),
    ('IMAGE', '/UserFiles/images/image25.jpg', 46),
    ('IMAGE', '/UserFiles/images/image26.png', 47),
    ('IMAGE', '/UserFiles/images/image27.jpg', 48),
    ('IMAGE', '/UserFiles/images/image28.png', 49),
    ('IMAGE', '/UserFiles/images/image29.jpg', 50);

INSERT INTO
    faq (question, answer)
VALUES
    (
        'How do I report a user for inappropriate behavior?',
        'To report a user for inappropriate behavior, navigate to their profile and look for the "Report" or "Flag" option. Describe the issue and provide evidence if possible. The platforms moderation team will review your report.'
    ),
    (
        'How can I post a question on this platform?',
        'To post a question, click on the "Ask a Question" or "Post button". Write a clear and concise title and provide details in the body of the question. Choose relevant tags and submit. Be sure to follow community guidelines.'
    ),
    (
        'What are upvotes, and how do they work?',
        'Upvotes are a way for the community to express appreciation for helpful content. To upvote a question or answer, click the up arrow. Upvoted content is more visible and contributes to user reputation.'
    ),
    (
        'Can I retract an upvote?',
        'You can retract an upvote by clicking the up arrow again.'
    );

INSERT INTO
    comments (body, type, author, question_id, answer_id)
VALUES
    (
        'Great tips for budgeting! This will definitely help me manage my finances better.',
        'QUESTION',
        1,
        1,
        NULL
    ),
    (
        'I''m glad you found the budgeting tips helpful. Let me know if you have any questions!',
        'ANSWER',
        2,
        NULL,
        1
    ),
    (
        'These recipes look delicious. Can''t wait to try them!',
        'QUESTION',
        3,
        2,
        NULL
    ),
    (
        'Enjoy cooking those recipes! They''re simple and tasty.',
        'ANSWER',
        4,
        NULL,
        2
    ),
    (
        'The laundry tips are a lifesaver. Thanks for sharing!',
        'QUESTION',
        5,
        3,
        NULL
    ),
    (
        'You''re welcome! Laundry doesn''t have to be a chore with the right techniques.',
        'ANSWER',
        6,
        NULL,
        3
    ),
    (
        'I''ve started following the cleaning routines, and my home feels so much better.',
        'QUESTION',
        7,
        4,
        NULL
    ),
    (
        'That''s great to hear! A tidy home can positively impact your daily life.',
        'ANSWER',
        8,
        NULL,
        4
    ),
    (
        'The time management techniques are helping me stay productive. Thanks!',
        'QUESTION',
        9,
        5,
        NULL
    ),
    (
        'You''re welcome! Time management is key for productivity. Keep up the good work!',
        'ANSWER',
        10,
        NULL,
        5
    );

-- Comments related to budgeting
INSERT INTO
    comments (body, type, author, question_id, answer_id)
VALUES
    (
        'Great tips for budgeting! I''ll start doing this right away.',
        'QUESTION',
        6,
        1,
        NULL
    ),
    (
        'I used these budgeting tips, and they worked like a charm!',
        'ANSWER',
        7,
        NULL,
        1
    ),
    (
        'Do you have any additional budgeting advice? I need all the help I can get.',
        'QUESTION',
        8,
        1,
        NULL
    ),
    (
        'Sure, I can give you some more budgeting tips. First, track your spending to identify where you can cut costs.',
        'ANSWER',
        9,
        NULL,
        1
    ),
    (
        'I struggle with managing my finances. How can I improve my budget?',
        'QUESTION',
        10,
        1,
        NULL
    ),
    (
        'Improving your budget starts with setting clear financial goals and sticking to them. Start with a small emergency fund.',
        'ANSWER',
        11,
        NULL,
        1
    ),
    (
        'Cooking can be so much fun! Thanks for the recipes.',
        'QUESTION',
        12,
        2,
        NULL
    ),
    (
        'You''re welcome! Enjoy your cooking journey!',
        'ANSWER',
        13,
        NULL,
        2
    ),
    (
        'I''m new to cooking, and these recipes look manageable. What ingredients do I need?',
        'QUESTION',
        14,
        2,
        NULL
    ),
    (
        'For the stir-fry, you''ll need assorted vegetables, soy sauce, and cooking oil. The spaghetti requires pasta, garlic, and olive oil.',
        'ANSWER',
        15,
        NULL,
        2
    );

-- Comments related to laundry
INSERT INTO
    comments (body, type, author, question_id, answer_id)
VALUES
    (
        'Laundry is a struggle for me. Thanks for the tips!',
        'QUESTION',
        16,
        3,
        NULL
    ),
    (
        'I used these laundry tips, and my clothes are in much better shape now.',
        'ANSWER',
        17,
        NULL,
        3
    ),
    (
        'What detergent do you recommend for laundry?',
        'QUESTION',
        18,
        3,
        NULL
    ),
    (
        'I prefer using a gentle, hypoallergenic detergent. It''s suitable for most clothing types.',
        'ANSWER',
        19,
        NULL,
        3
    ),
    (
        'Do you have any tips for ironing clothes as well?',
        'QUESTION',
        20,
        3,
        NULL
    ),
    (
        'Certainly! For ironing, use the right temperature setting for your fabric to avoid damage.',
        'ANSWER',
        21,
        NULL,
        3
    ),
    (
        'I''ve always struggled with keeping my home clean. These routines sound manageable.',
        'QUESTION',
        22,
        4,
        NULL
    ),
    (
        'Consistent routines make all the difference. You got this!',
        'ANSWER',
        23,
        NULL,
        4
    ),
    (
        'What are some good cleaning products to keep on hand?',
        'QUESTION',
        24,
        4,
        NULL
    ),
    (
        'Basic cleaning supplies include all-purpose cleaner, microfiber cloths, and a vacuum cleaner.',
        'ANSWER',
        25,
        NULL,
        4
    );

-- Comments related to time management
INSERT INTO
    comments (body, type, author, question_id, answer_id)
VALUES
    (
        'Time management is a challenge for me. Do you have any techniques for staying focused?',
        'QUESTION',
        26,
        5,
        NULL
    ),
    (
        'Certainly! The Pomodoro Technique is great for staying focused. It involves 25-minute work intervals followed by a 5-minute break.',
        'ANSWER',
        27,
        NULL,
        5
    ),
    (
        'Prioritizing tasks can be tough. How do I decide what to work on first?',
        'QUESTION',
        28,
        5,
        NULL
    ),
    (
        'Prioritize tasks based on deadlines and importance. Use a to-do list to keep track of what needs to be done.',
        'ANSWER',
        29,
        NULL,
        5
    );

-- Comments related to job searching
INSERT INTO
    comments (body, type, author, question_id, answer_id)
VALUES
    (
        'Job searching is overwhelming. Do you have any advice on tailoring my resume?',
        'QUESTION',
        30,
        6,
        NULL
    ),
    (
        'To tailor your resume, emphasize relevant skills and experiences for the specific job you''re applying for.',
        'ANSWER',
        31,
        NULL,
        6
    ),
    (
        'I find networking challenging. How can I connect with professionals in my field?',
        'QUESTION',
        32,
        6,
        NULL
    ),
    (
        'Networking events, industry conferences, and LinkedIn are great places to start. Don''t hesitate to reach out for advice.',
        'ANSWER',
        33,
        NULL,
        6
    );

-- Comments related to renting an apartment
INSERT INTO
    comments (body, type, author, question_id, answer_id)
VALUES
    (
        'Renting my first apartment is exciting! How do I decide on a comfortable rent range?',
        'QUESTION',
        34,
        7,
        NULL
    ),
    (
        'Review your budget and aim to spend no more than 30% of your income on rent.',
        'ANSWER',
        35,
        NULL,
        7
    ),
    (
        'I want to understand lease agreements better. What are some key terms to watch for?',
        'QUESTION',
        36,
        7,
        NULL
    ),
    (
        'Key lease terms to look for include the duration of the lease, rent increases, and the security deposit policy.',
        'ANSWER',
        37,
        NULL,
        7
    );

-- Comments related to healthcare options
INSERT INTO
    comments (body, type, author, question_id, answer_id)
VALUES
    (
        'I''m new to Portugal and healthcare options here are different. Can you explain?',
        'QUESTION',
        38,
        8,
        NULL
    ),
    (
        'In Portugal, you can register for the National Health Service (SNS) and consider supplementary private insurance for added coverage.',
        'ANSWER',
        39,
        NULL,
        8
    ),
    (
        'What are the main differences between public and private healthcare in Portugal?',
        'QUESTION',
        40,
        8,
        NULL
    ),
    (
        'Public healthcare through SNS is accessible to all residents, while private healthcare offers more specialized services.',
        'ANSWER',
        41,
        NULL,
        8
    );

-- Comments related to insurance options
INSERT INTO
    comments (body, type, author, question_id, answer_id)
VALUES
    (
        'I need insurance but don''t know where to start. What types of insurance should I consider?',
        'QUESTION',
        42,
        9,
        NULL
    ),
    (
        'Consider health insurance, renters insurance, and car insurance based on your needs.',
        'ANSWER',
        43,
        NULL,
        9
    ),
    (
        'I''m worried about premiums and deductibles. How can I choose the right insurance plan?',
        'QUESTION',
        44,
        9,
        NULL
    ),
    (
        'Carefully compare premiums, deductibles, and coverage limits. An insurance agent can help you find the best plan.',
        'ANSWER',
        45,
        NULL,
        9
    );

-- Comments related to tax filing
INSERT INTO
    comments (body, type, author, question_id, answer_id)
VALUES
    (
        'Tax filing seems complicated. How can I gather all the necessary documents?',
        'QUESTION',
        46,
        10,
        NULL
    ),
    (
        'Gather documents like W-2 forms, receipts for deductions, and any other tax-related documents.',
        'ANSWER',
        47,
        NULL,
        10
    ),
    (
        'Should I use tax software or consult a professional for help with my taxes?',
        'QUESTION',
        48,
        10,
        NULL
    ),
    (
        'Using tax software is a good start, but for complex situations, it may be wise to consult a tax professional.',
        'ANSWER',
        49,
        NULL,
        10
    );

-- Comments related to home repairs
INSERT INTO
    comments (body, type, author, question_id, answer_id)
VALUES
    (
        'I don''t know much about home repairs. What are some basic skills every adult should have?',
        'QUESTION',
        50,
        11,
        NULL
    ),
    (
        'Basic home repair skills include fixing leaky faucets, unclogging drains, and patching small holes in walls.',
        'ANSWER',
        51,
        NULL,
        11
    ),
    (
        'I have no idea how to reset a circuit breaker. Can you explain?',
        'QUESTION',
        52,
        11,
        NULL
    ),
    (
        'A circuit breaker can trip during electrical issues. To reset it, locate the breaker box and switch the tripped switch back to the "on" position.',
        'ANSWER',
        53,
        NULL,
        11
    );

-- Comments related to grocery shopping
INSERT INTO
    comments (body, type, author, question_id, answer_id)
VALUES
    (
        'I''m a student on a tight budget. How can I save money while grocery shopping?',
        'QUESTION',
        54,
        12,
        NULL
    ),
    (
        'Plan your meals, make a shopping list, opt for store brands, and take advantage of discounts and sales.',
        'ANSWER',
        55,
        NULL,
        12
    ),
    (
        'Are there any online tools or apps that can help with budget-friendly grocery shopping?',
        'QUESTION',
        56,
        12,
        NULL
    ),
    (
        'Yes, there are several apps and websites that provide coupons and help you find the best deals at local stores.',
        'ANSWER',
        57,
        NULL,
        12
    );

INSERT INTO
    badge_user (user_id, badge_id, date)
VALUES
    (3, 1, '2023-10-30'),
    (5, 1, '2023-10-19'),
    (6, 1, '2023-12-27'),
    (12, 1, '2023-11-07'),
    (12, 2, '2023-11-09'),
    (13, 1, '2023-02-05'),
    (18, 9, '2023-08-10'),
    (22, 9, '2023-11-04'),
    (24, 1, '2023-02-25'),
    (24, 2, '2023-03-28'),
    (29, 1, '2022-01-30'),
    (29, 2, '2022-03-01'),
    (32, 1, '2021-11-04'),
    (35, 9, '2023-10-05'),
    (40, 1, '2023-12-25'),
    (42, 9, '2023-11-11'),
    (44, 1, '2023-10-13'),
    (46, 1, '2023-10-15'),
    (47, 1, '2023-10-25'),
    (48, 1, '2023-10-25'),
    (50, 1, '2023-10-19'),
    (53, 1, '2022-11-05'),
    (55, 9, '2022-10-08'),
    (72, 5, '2022-11-09'),
    (73, 1, '2022-10-24'),
    (77, 5, '2022-11-01'),
    (81, 1, '2023-02-01'),
    (84, 5, '2022-11-04'),
    (88, 1, '2022-11-08'),
    (90, 5, '2022-12-05');

INSERT INTO
    followed_questions (user_id, question_id)
VALUES
    (5, 54),
    (21, 24),
    (34, 21),
    (1, 21),
    (2, 41),
    (43, 1),
    (42, 53),
    (12, 86),
    (4, 2),
    (64, 52),
    (32, 23),
    (1, 5),
    (56, 63),
    (3, 12),
    (53, 86),
    (87, 96),
    (23, 13),
    (13, 11),
    (63, 2),
    (34, 41),
    (55, 52),
    (72, 64),
    (77, 11),
    (31, 7),
    (16, 24),
    (4, 97),
    (56, 21),
    (75, 75),
    (43, 32),
    (25, 16),
    (52, 87),
    (75, 24),
    (65, 25),
    (52, 16),
    (24, 75),
    (52, 4),
    (63, 23),
    (12, 52),
    (5, 8),
    (75, 18),
    (59, 25),
    (58, 21),
    (81, 41),
    (52, 53),
    (56, 68),
    (23, 74),
    (91, 1),
    (91, 2),
    (91, 3),
    (91, 4),
    (91, 5),
    (91, 6),
    (91, 7),
    (91, 8),
    (91, 9),
    (91, 10),
    (91, 11),
    (91, 12),
    (91, 13),
    (91, 14);

INSERT INTO
    followed_tags (user_id, tag_id)
VALUES
    (1, 13),
    (1, 14),
    (2, 9),
    (2, 15),
    (3, 2),
    (4, 3),
    (5, 6),
    (8, 5),
    (8, 12),
    (8, 13),
    (9, 9),
    (10, 2),
    (12, 12),
    (14, 7),
    (15, 10),
    (19, 1),
    (23, 14),
    (24, 10),
    (27, 5),
    (30, 8),
    (30, 14),
    (31, 2),
    (31, 3),
    (31, 4),
    (31, 8),
    (50, 2),
    (55, 1),
    (56, 4),
    (58, 2),
    (60, 13),
    (69, 1),
    (69, 3),
    (69, 15),
    (71, 1),
    (71, 3),
    (72, 8),
    (73, 6),
    (74, 4),
    (78, 11),
    (81, 8),
    (82, 10),
    (88, 3),
    (88, 6),
    (88, 15),
    (90, 2);

INSERT INTO
    followed_users (follower_id, followed_id)
VALUES
    (1, 2),
    (1, 3),
    (1, 4),
    (1, 5),
    (2, 1),
    (3, 1),
    (4, 1),
    (5, 1),
    (5, 15),
    (5, 78),
    (5, 79),
    (5, 90),
    (6, 8),
    (7, 1),
    (7, 8),
    (7, 42),
    (8, 1),
    (8, 90),
    (9, 8),
    (15, 90),
    (16, 8),
    (16, 20),
    (16, 34),
    (16, 50),
    (45, 51),
    (46, 8),
    (47, 67),
    (48, 8),
    (68, 1),
    (68, 8),
    (68, 60),
    (68, 61),
    (68, 84),
    (68, 90),
    (70, 5),
    (70, 6),
    (71, 8),
    (71, 25),
    (72, 55),
    (72, 78),
    (73, 6),
    (88, 8),
    (88, 13),
    (89, 14);

INSERT INTO
    votes (
        is_upvote,
        type,
        comment_id,
        answer_id,
        question_id,
        user_id
    )
VALUES
    (true, 'QUESTION', NULL, NULL, 3, 1),
    (false, 'ANSWER', NULL, 9, NULL, 2),
    (true, 'ANSWER', NULL, 9, NULL, 3),
    (true, 'COMMENT', 21, NULL, NULL, 4),
    (false, 'COMMENT', 11, NULL, NULL, 5),
    (true, 'ANSWER', NULL, 7, NULL, 6),
    (false, 'QUESTION', NULL, NULL, 6, 7),
    (true, 'QUESTION', NULL, NULL, 12, 8),
    (false, 'COMMENT', 45, NULL, NULL, 9),
    (true, 'QUESTION', NULL, NULL, 5, 10),
    (false, 'ANSWER', NULL, 1, NULL, 11),
    (true, 'COMMENT', 3, NULL, NULL, 12),
    (true, 'ANSWER', NULL, 5, NULL, 13),
    (false, 'QUESTION', NULL, NULL, 9, 14),
    (false, 'ANSWER', NULL, 9, NULL, 15),
    (true, 'COMMENT', 29, NULL, NULL, 16),
    (true, 'ANSWER', NULL, 8, NULL, 17),
    (true, 'COMMENT', 19, NULL, NULL, 18),
    (true, 'QUESTION', NULL, NULL, 8, 19),
    (false, 'COMMENT', 38, NULL, NULL, 20),
    (true, 'ANSWER', NULL, 6, NULL, 21),
    (false, 'ANSWER', NULL, 2, NULL, 22),
    (true, 'COMMENT', 50, NULL, NULL, 23),
    (true, 'ANSWER', NULL, 4, NULL, 24),
    (false, 'QUESTION', NULL, NULL, 7, 25),
    (true, 'COMMENT', 7, NULL, NULL, 26),
    (true, 'QUESTION', NULL, NULL, 11, 27),
    (false, 'COMMENT', 51, NULL, NULL, 28),
    (false, 'COMMENT', 12, NULL, NULL, 29),
    (true, 'ANSWER', NULL, 11, NULL, 30),
    (true, 'ANSWER', NULL, 10, NULL, 31),
    (true, 'ANSWER', NULL, 12, NULL, 32),
    (true, 'ANSWER', NULL, 13, NULL, 33),
    (true, 'ANSWER', NULL, 14, NULL, 34),
    (false, 'ANSWER', NULL, 15, NULL, 35),
    (false, 'ANSWER', NULL, 16, NULL, 36),
    (false, 'ANSWER', NULL, 17, NULL, 37),
    (false, 'ANSWER', NULL, 18, NULL, 38),
    (false, 'ANSWER', NULL, 19, NULL, 39),
    (false, 'ANSWER', NULL, 20, NULL, 40),
    (false, 'ANSWER', NULL, 21, NULL, 41),
    (false, 'ANSWER', NULL, 22, NULL, 42),
    (false, 'ANSWER', NULL, 23, NULL, 43),
    (false, 'ANSWER', NULL, 24, NULL, 44),
    (false, 'ANSWER', NULL, 25, NULL, 45),
    (true, 'ANSWER', NULL, 26, NULL, 46),
    (true, 'ANSWER', NULL, 27, NULL, 47),
    (true, 'ANSWER', NULL, 28, NULL, 48),
    (true, 'ANSWER', NULL, 29, NULL, 49),
    (true, 'ANSWER', NULL, 30, NULL, 50),
    (true, 'QUESTION', NULL, NULL, 6, 91),
    (true, 'ANSWER', NULL, 11, NULL, 91),
    (false, 'ANSWER', NULL, 12, NULL, 91);

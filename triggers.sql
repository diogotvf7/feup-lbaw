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

		IF NEW.password IS NULL THEN
		RAISE EXCEPTION 'Password cannot be NULL.';
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
            WHERE id = NEW.id_user;
        ELSE
            --Decrease score by 1 for a downvote
            UPDATE users
            SET score = score - 1
            WHERE id = NEW.id_user;
        END IF;
    ELSIF TG_OP = 'DELETE' THEN
        IF OLD.is_upvote THEN
            --Decrease score by 1 for a deleted upvote
            UPDATE users
            SET score = score - 1
            WHERE id = OLD.id_user;
        ELSE
            --Increase score by 1 for a deleted downvote
            UPDATE users
            SET score = score + 1
            WHERE id = OLD.id_user;
        END IF;
    END IF;

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_score
        AFTER INSERT OR DELETE ON vote
        FOR EACH ROW
        EXECUTE PROCEDURE update_score();

-- TRIGGER 03
-- A notification must be sent after an answer is written

DROP FUNCTION IF EXISTS send_answer_notification() CASCADE;

CREATE FUNCTION send_answer_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO notification (date, type, id_answer, id_user)
    VALUES (NOW(), 'ANSWER', NEW.id, NEW.author);

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER send_answer_notification
        AFTER INSERT ON answer
        FOR EACH ROW
        EXECUTE PROCEDURE send_answer_notification();


-- TRIGGER 04
-- A notification must be sent after an upvote is given

DROP FUNCTION IF EXISTS send_upvote_notification() CASCADE;

CREATE FUNCTION send_upvote_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_upvote THEN
        INSERT INTO notification (date, type, id_upvote, id_user)
        VALUES (NOW(), 'UPVOTE', NEW.id, NEW.id_user);
    END IF;

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER send_upvote_notification
        AFTER INSERT ON vote
        FOR EACH ROW
        EXECUTE PROCEDURE send_upvote_notification();


-- TRIGGER 05
-- A notification must be sent after a badge is received

DROP FUNCTION IF EXISTS send_badge_notification() CASCADE;

CREATE FUNCTION send_badge_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO notification (date, type, id_badge, id_user)
    VALUES (NOW(), 'BADGE', NEW.id_badge, NEW.id_user);

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER send_badge_notification
        AFTER INSERT ON user_badge
        FOR EACH ROW
        EXECUTE PROCEDURE send_badge_notification();


-- TRIGGER 06
-- Text search vectors must be updated for different tables

DROP FUNCTION IF EXISTS tsvectors_update_question() CASCADE;

CREATE FUNCTION tsvectors_update_question() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.search_title = setweight(to_tsvector('english', NEW.title), 'A');
    END IF;
    
    IF TG_OP = 'UPDATE' THEN
        IF NEW.title <> OLD.title THEN
            NEW.search_title = setweight(to_tsvector('english', NEW.title), 'A');
        END IF;
    END IF;
    
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tsvectors_update_question
        BEFORE INSERT OR UPDATE ON question
        FOR EACH ROW
        EXECUTE PROCEDURE tsvectors_update_question();


-- TRIGGER 07
-- Text search vectors must be updated for different tables

DROP FUNCTION IF EXISTS tsvectors_update_tag() CASCADE;

CREATE FUNCTION tsvectors_update_tag() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.search_tag_name = setweight(to_tsvector('english', NEW.name), 'A');
        NEW.search_tag_description = setweight(to_tsvector('english', NEW.description), 'B');
    END IF;
    
    IF TG_OP = 'UPDATE' THEN        
        IF NEW.name <> OLD.name THEN
            NEW.search_tag_name = setweight(to_tsvector('english', NEW.name), 'A');
        END IF;
        IF NEW.description <> OLD.description THEN
            NEW.search_tag_description = setweight(to_tsvector('english', NEW.description), 'B');
        END IF;
    END IF;
    
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tsvectors_update_tag
        BEFORE INSERT OR UPDATE ON tag
        FOR EACH ROW
        EXECUTE PROCEDURE tsvectors_update_tag();


-- TRIGGER 08
-- Text search vectors must be updated for different tables

DROP FUNCTION IF EXISTS tsvectors_update_content_version() CASCADE;

CREATE FUNCTION tsvectors_update_content_version() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.search_body = setweight(to_tsvector('english', NEW.body), 'B');
    END IF;
    
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tsvectors_update_content_version
        BEFORE INSERT OR UPDATE ON content_version
        FOR EACH ROW
        EXECUTE PROCEDURE tsvectors_update_content_version();
		
-- TRIGGER 09
-- If a question has no tag, it is removed

DROP FUNCTION IF EXISTS remove_question_no_tag() CASCADE; 

CREATE FUNCTION remove_question_no_tag() RETURNS TRIGGER AS
$BODY$
DECLARE
    question_id INT;
    question_count INT;
BEGIN
    question_id := OLD.id;
    
    DELETE FROM question_tag WHERE id_tag = question_id;
    
    FOR question_id IN SELECT DISTINCT id_question FROM question_tag
    LOOP
        SELECT COUNT(*) INTO question_count FROM question_tag WHERE id_question = question_id;
        
        IF question_count = 0 THEN
            DELETE FROM question WHERE id = question_id;
        END IF;
    END LOOP;
    
    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER remove_question_no_tag
        BEFORE DELETE ON tag
        FOR EACH ROW
        EXECUTE PROCEDURE remove_question_no_tag();
		
-- TRIGGER 10
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
        AFTER INSERT ON question
        FOR EACH ROW
        EXECUTE PROCEDURE update_experience_question();


-- TRIGGER 11
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
        AFTER INSERT ON answer
        FOR EACH ROW
        EXECUTE PROCEDURE update_experience_answer();

		
-- TRIGGER 12
-- A content version cannot be related to more than five annexes

DROP FUNCTION IF EXISTS verify_annexes() CASCADE;

CREATE FUNCTION verify_annexes() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (SELECT COUNT(*) FROM annex WHERE id_version = NEW.id) > 5 THEN
        RAISE EXCEPTION 'A content version cannot have more than five annexes.';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_annexes
        AFTER INSERT ON content_version
        FOR EACH ROW
        EXECUTE PROCEDURE verify_annexes();
		
-- TRIGGER 13
-- Prevent self voting

DROP FUNCTION IF EXISTS prevent_self_voting() CASCADE;

CREATE FUNCTION prevent_self_voting() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.type = 'QUESTION' AND NEW.id_user = (SELECT author FROM question WHERE id = NEW.id_question) THEN
        RAISE EXCEPTION 'You cannot vote on your own question';
    END IF;

    IF NEW.type = 'ANSWER' AND NEW.id_user = (SELECT author FROM answer WHERE id = NEW.id_answer) THEN
        RAISE EXCEPTION 'You cannot vote on your own answer';
    END IF;

    IF NEW.type = 'COMMENT' AND NEW.id_user = (SELECT author FROM comments WHERE id = NEW.id_comment) THEN
        RAISE EXCEPTION 'You cannot vote on your own comment';
    END IF;

  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_self_voting
        BEFORE INSERT ON vote
        FOR EACH ROW
        EXECUTE PROCEDURE prevent_self_voting();	
		
-- TRIGGER 14
-- A user can't answer their own questions

DROP FUNCTION IF EXISTS prevent_self_answering() CASCADE;

CREATE FUNCTION prevent_self_answering() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.author = (SELECT author FROM question WHERE id = NEW.id_question) THEN
        RAISE EXCEPTION 'You cannot answer your own question';
    END IF;

  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_self_answering
        BEFORE INSERT ON answer
        FOR EACH ROW
        EXECUTE PROCEDURE prevent_self_answering();
		
-- TRIGGER 15
-- A badge should be given when a user asks a question for the first time.

DROP FUNCTION IF EXISTS badge_first_question() CASCADE;

CREATE FUNCTION badge_first_question() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF (SELECT COUNT(*)
    FROM question
    WHERE author = NEW.author) = 1 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = 'First Question'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_first_question
        AFTER INSERT ON question
        FOR EACH ROW
        EXECUTE PROCEDURE badge_first_question();

-- TRIGGER 16
-- A badge should be given when a user asks 10 questions.

DROP FUNCTION IF EXISTS badge_ten_questions() CASCADE;

CREATE FUNCTION badge_ten_questions() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF (SELECT COUNT(*)
    FROM question
    WHERE author = NEW.author) = 1 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = '10 Questions'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_ten_questions
        AFTER INSERT ON question
        FOR EACH ROW
        EXECUTE PROCEDURE badge_ten_questions();

-- TRIGGER 17
-- A badge should be given when a user asks 50 questions.

DROP FUNCTION IF EXISTS badge_fifty_questions() CASCADE;

CREATE FUNCTION badge_fifty_questions() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF (SELECT COUNT(*)
    FROM question
    WHERE author = NEW.author) = 50 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = '50 Questions'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_fifty_questions
        AFTER INSERT ON question
        FOR EACH ROW
        EXECUTE PROCEDURE badge_fifty_questions();

-- TRIGGER 18
-- A badge should be given when a user asks 100 questions.

DROP FUNCTION IF EXISTS badge_one_hundred_questions() CASCADE;

CREATE FUNCTION badge_one_hundred_questions() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF (SELECT COUNT(*)
    FROM question
    WHERE author = NEW.author) = 100 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = '100 Questions'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_one_hundred_questions
        AFTER INSERT ON question
        FOR EACH ROW
        EXECUTE PROCEDURE badge_one_hundred_questions();
		
-- TRIGGER 19
-- A badge should be given when a user answers for the first time.

DROP FUNCTION IF EXISTS badge_first_answer() CASCADE;

CREATE FUNCTION badge_first_answer() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF(SELECT COUNT(*)
    FROM answer
    WHERE author = NEW.author) = 1 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = 'First Answer'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_first_answer
        AFTER INSERT ON answer
        FOR EACH ROW
        EXECUTE PROCEDURE badge_first_answer();

-- TRIGGER 20
-- A badge should be given when a user answers 10 times.

DROP FUNCTION IF EXISTS badge_10_answers() CASCADE;

CREATE FUNCTION badge_10_answers() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF(SELECT COUNT(*)
    FROM answer
    WHERE author = NEW.author) = 10 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = '10 Answers'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_10_answers
        AFTER INSERT ON answer
        FOR EACH ROW
        EXECUTE PROCEDURE badge_10_answers();

-- TRIGGER 21
-- A badge should be given when a user answers 50 times.

DROP FUNCTION IF EXISTS badge_50_answers() CASCADE;

CREATE FUNCTION badge_50_answers() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF(SELECT COUNT(*)
    FROM answer
    WHERE author = NEW.author) = 50 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = '50 Answers'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_50_answers
        AFTER INSERT ON answer
        FOR EACH ROW
        EXECUTE PROCEDURE badge_50_answers();

-- TRIGGER 22
-- A badge should be given when a user answers 100 times.

DROP FUNCTION IF EXISTS badge_100_answers() CASCADE;

CREATE FUNCTION badge_100_answers() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF(SELECT COUNT(*)
    FROM answer
    WHERE author = NEW.author) = 100 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = '100 Answers'));
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_100_answers
        AFTER INSERT ON answer
        FOR EACH ROW
        EXECUTE PROCEDURE badge_100_answers();

-- TRIGGER 23
-- A badge should be given when a user comments for the first time.

DROP FUNCTION IF EXISTS badge_first_comment() CASCADE;

CREATE FUNCTION badge_first_comment() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (SELECT COUNT(*)
    FROM comments
    WHERE author = NEW.author) = 1 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = 'First Comment'));
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
-- A badge should be given when a user comments 10 times.

DROP FUNCTION IF EXISTS badge_10_comments() CASCADE;

CREATE FUNCTION badge_10_comments() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (SELECT COUNT(*)
    FROM comments
    WHERE author = NEW.author) = 10 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = '10 Comments'));
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
-- A badge should be given when a user comments 50 times.

DROP FUNCTION IF EXISTS badge_50_comments() CASCADE;

CREATE FUNCTION badge_50_comments() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (SELECT COUNT(*)
    FROM comments
    WHERE author = NEW.author) = 50 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = '50 Comments'));
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
-- A badge should be given when a user comments 100 times.

DROP FUNCTION IF EXISTS badge_100_comments() CASCADE;

CREATE FUNCTION badge_100_comments() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (SELECT COUNT(*)
    FROM comments
    WHERE author = NEW.author) = 100 THEN
        INSERT INTO user_badge (id_user, id_badge)
        VALUES (NEW.author, (SELECT id FROM badge WHERE name = '100 Comments'));
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
-- A badge should be given when a user receives the first upvote.

DROP FUNCTION IF EXISTS badge_first_upvote() CASCADE;

CREATE FUNCTION badge_first_upvote() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF (SELECT COUNT(*) FROM 
            vote JOIN question ON vote.id_question = question.id JOIN answer ON vote.id_answer = answer.id JOIN comments ON vote.id_comment = comments.id
            WHERE vote.id_user = NEW.id_user AND is_upvote = true) = 1 THEN
            INSERT INTO user_badge (id_user, id_badge)
            VALUES (NEW.id_user, (SELECT id FROM badge WHERE name = 'First Upvote'));
        END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_first_upvote
        AFTER INSERT ON vote
        FOR EACH ROW
        EXECUTE PROCEDURE badge_first_upvote();
		
-- TRIGGER 28
-- A badge should be given when a user receives the first downvote.

DROP FUNCTION IF EXISTS badge_first_downvote() CASCADE;

CREATE FUNCTION badge_first_downvote() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF (SELECT COUNT(*) FROM 
            vote JOIN question ON vote.id_question = question.id JOIN answer ON vote.id_answer = answer.id JOIN comments ON vote.id_comment = comments.id
            WHERE vote.id_user = NEW.id_user AND is_upvote = false) = 1 THEN
            INSERT INTO user_badge (id_user, id_badge)
            VALUES (NEW.id_user, (SELECT id FROM badge WHERE name = 'First Downvote'));
        END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_first_downvote
        AFTER INSERT ON vote
        FOR EACH ROW
        EXECUTE PROCEDURE badge_first_downvote();
		
-- TRIGGER 29
-- A badge should be given when a user reaches 100 score.

DROP FUNCTION IF EXISTS badge_100_score() CASCADE;

CREATE FUNCTION badge_100_score() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_upvote = true THEN
        IF (SELECT score FROM users WHERE id = NEW.id_user) = 100 AND
           (SELECT COUNT(*) FROM user_badge WHERE id_user = NEW.id_user AND id_badge = (SELECT id FROM badge WHERE name = '100 Score')) = 0 THEN
            INSERT INTO user_badge (id_user, id_badge)
            VALUES (NEW.id_user, (SELECT id FROM badge WHERE name = '100 Score'));
        END IF;
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_100_score
        AFTER INSERT ON vote
        FOR EACH ROW
        EXECUTE PROCEDURE badge_100_score();
    
-- TRIGGER 30
-- A badge should be given when a user reaches 1000 score.

DROP FUNCTION IF EXISTS badge_1000_score() CASCADE;

CREATE FUNCTION badge_1000_score() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_upvote = true THEN
        IF (SELECT score FROM users WHERE id = NEW.id_user) = 1000 AND
           (SELECT COUNT(*) FROM user_badge WHERE id_user = NEW.id_user AND id_badge = (SELECT id FROM badge WHERE name = '1000 Score')) = 0 THEN
            INSERT INTO user_badge (id_user, id_badge)
            VALUES (NEW.id_user, (SELECT id FROM badge WHERE name = '1000 Score'));
        END IF;
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_1000_score
        AFTER INSERT ON vote
        FOR EACH ROW
        EXECUTE PROCEDURE badge_1000_score();

-- TRIGGER 31
-- A badge should be given when a user reaches 5000 score.

DROP FUNCTION IF EXISTS badge_5000_score() CASCADE;

CREATE FUNCTION badge_5000_score() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_upvote = true THEN
        IF (SELECT score FROM users WHERE id = NEW.id_user) = 5000 AND
           (SELECT COUNT(*) FROM user_badge WHERE id_user = NEW.id_user AND id_badge = (SELECT id FROM badge WHERE name = '5000 Score')) = 0 THEN
            INSERT INTO user_badge (id_user, id_badge)
            VALUES (NEW.id_user, (SELECT id FROM badge WHERE name = '5000 Score'));
        END IF;
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_5000_score
        AFTER INSERT ON vote
        FOR EACH ROW
        EXECUTE PROCEDURE badge_5000_score();

-- TRIGGER 32
-- A badge should be given when a user reaches 10000 score.

DROP FUNCTION IF EXISTS badge_10000_score() CASCADE;

CREATE FUNCTION badge_10000_score() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_upvote = true THEN
        IF (SELECT score FROM users WHERE id = NEW.id_user) = 10000 AND
           (SELECT COUNT(*) FROM user_badge WHERE id_user = NEW.id_user AND id_badge = (SELECT id FROM badge WHERE name = '10000 Score')) = 0 THEN
            INSERT INTO user_badge (id_user, id_badge)
            VALUES (NEW.id_user, (SELECT id FROM badge WHERE name = '10000 Score'));
        END IF;
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_10000_score
        AFTER INSERT ON vote
        FOR EACH ROW
        EXECUTE PROCEDURE badge_10000_score();

		
-- TRIGGER 23
-- A badge should be given when a user correctly answers for the first time.
DROP FUNCTION IF EXISTS badge_first_correct_answer ();

/*CREATE FUNCTION badge_first_correct_answer() RETURNS TRIGGER AS
$BODY$
BEGIN
IF (SELECT COUNT(*)
FROM correct_answer JOIN answer USING (id_question)
WHERE answer.author = (SELECT author
FROM correct_answer JOIN answer USING (id_question)
WHERE answer.id = NEW.id_question)) = 1 THEN
INSERT INTO user_badge (id_user, id_badge)
VALUES ((SELECT author
FROM correct_answer JOIN answer USING (id_question)
WHERE answer.id = NEW.id_question),(SELECT id FROM badge WHERE name = 'First Correct Answer'));
END IF;

RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_first_correct_answer
AFTER INSERT ON correct_answer
FOR EACH ROW
EXECUTE PROCEDURE badge_first_correct_answer();*/
-- TRIGGER 24
-- A badge should be given when a user correctly answers 10 times.
DROP FUNCTION IF EXISTS badge_10_correct_answers ();

/*
CREATE FUNCTION badge_10_correct_answers() RETURNS TRIGGER AS
$BODY$
BEGIN
IF (SELECT COUNT(*)
FROM correct_answer JOIN answer USING (id_question)
WHERE answer.author = (SELECT author
FROM correct_answer JOIN answer USING (id_question)
WHERE answer.id = NEW.id_question)) = 10 THEN
INSERT INTO user_badge (id_user, id_badge)
VALUES ((SELECT author
FROM correct_answer JOIN answer USING (id_question)
WHERE answer.id = NEW.id_question),(SELECT id FROM badge WHERE name = '10 Correct Answers'));
END IF;

RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_10_correct_answers
AFTER INSERT ON correct_answer
FOR EACH ROW
EXECUTE PROCEDURE badge_10_correct_answers();*/
-- TRIGGER 25
-- A badge should be given when a user correctly answers 50 times.
DROP FUNCTION IF EXISTS badge_50_correct_answers ();

/*CREATE FUNCTION badge_50_correct_answers() RETURNS TRIGGER AS
$BODY$
BEGIN
IF (SELECT COUNT(*)
FROM correct_answer JOIN answer USING (id_question)
WHERE answer.author = (SELECT author
FROM correct_answer JOIN answer USING (id_question)
WHERE answer.id = NEW.id_question)) = 50 THEN
INSERT INTO user_badge (id_user, id_badge)
VALUES ((SELECT author
FROM correct_answer JOIN answer USING (id_question)
WHERE answer.id = NEW.id_question),(SELECT id FROM badge WHERE name = '50 Correct Answers'));
END IF;

RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER badge_50_correct_answers
AFTER INSERT ON correct_answer
FOR EACH ROW
EXECUTE PROCEDURE badge_50_correct_answers();*/
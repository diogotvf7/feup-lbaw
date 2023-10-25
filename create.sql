SET
    search_path TO lbaw2326;

-----------------------------------------
-- Drop old schema
-----------------------------------------
DROP TABLE IF EXISTS followed_users CASCADE;

DROP TABLE IF EXISTS followed_tags CASCADE;

DROP TABLE IF EXISTS followed_questions CASCADE;

DROP TABLE IF EXISTS users CASCADE;

DROP TABLE IF EXISTS tag CASCADE;

DROP TABLE IF EXISTS badge CASCADE;

DROP TABLE IF EXISTS question CASCADE;

DROP TABLE IF EXISTS answer CASCADE;

DROP TABLE IF EXISTS comments CASCADE;

DROP TABLE IF EXISTS correct_answer CASCADE;

DROP TABLE IF EXISTS content_version CASCADE;

DROP TABLE IF EXISTS question_tag CASCADE;

DROP TABLE IF EXISTS annex CASCADE;

DROP TABLE IF EXISTS vote CASCADE;

DROP TABLE IF EXISTS notification CASCADE;

DROP TABLE IF EXISTS user_badge CASCADE;

DROP TABLE IF EXISTS faq CASCADE;

DROP TYPE IF EXISTS notification_type CASCADE;

DROP TYPE IF EXISTS file_type CASCADE;

DROP TYPE IF EXISTS content_type CASCADE;

DROP TYPE IF EXISTS main_content_type CASCADE;

-----------------------------------------
-- Types
-----------------------------------------
CREATE TYPE notification_type AS ENUM ('ANSWER', 'UPVOTE', 'BADGE');

CREATE TYPE file_type AS ENUM ('FILE', 'IMAGE');

CREATE TYPE content_type AS ENUM ('QUESTION', 'ANSWER', 'COMMENT');

CREATE TYPE main_content_type AS ENUM ('QUESTION', 'ANSWER');

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
        password TEXT NOT NULL,
        profile_picture TEXT,
        experience INTEGER DEFAULT 0,
        score INTEGER DEFAULT 0,
        member_since DATE DEFAULT now (),
        is_banned BOOLEAN DEFAULT FALSE,
        is_admin BOOLEAN DEFAULT FALSE
    );

CREATE TABLE
    tag (
        id SERIAL PRIMARY KEY,
        name TEXT NOT NULL UNIQUE,
        search_tag_name TSVECTOR NOT NULL,
        description TEXT NOT NULL,
        search_tag_description TSVECTOR NOT NULL
    );

CREATE TABLE
    badge (
        id SERIAL PRIMARY KEY,
        name TEXT NOT NULL UNIQUE,
        description TEXT NOT NULL UNIQUE,
        image_path TEXT NOT NULL UNIQUE
    );

CREATE TABLE
    question (
        id SERIAL PRIMARY KEY,
        title TEXT NOT NULL,
        search_title TSVECTOR NOT NULL,
        author INTEGER NOT NULL REFERENCES users (id) ON DELETE SET NULL
    );

CREATE TABLE
    answer (
        id SERIAL PRIMARY KEY,
        author INTEGER NOT NULL REFERENCES users (id) ON DELETE SET NULL,
        id_question INTEGER NOT NULL REFERENCES question (id) ON DELETE CASCADE
    );

CREATE TABLE
    comments (
        id SERIAL PRIMARY KEY,
        body TEXT NOT NULL,
        type main_content_type NOT NULL,
        date TIMESTAMP default now () NOT NULL,
        author INTEGER NOT NULL REFERENCES users (id) ON DELETE SET NULL,
        id_question INTEGER REFERENCES question (id) ON DELETE CASCADE,
        id_answer INTEGER REFERENCES answer (id) ON DELETE CASCADE,
        CHECK (
            (
                type = 'QUESTION'
                AND id_question IS NOT NULL
                AND id_answer IS NULL
            )
            OR (
                type = 'ANSWER'
                AND id_question IS NULL
                AND id_answer IS NOT NULL
            )
        )
    );

CREATE TABLE
    correct_answer (
        id_question INTEGER NOT NULL REFERENCES question (id) ON DELETE CASCADE,
        id_answer INTEGER NOT NULL REFERENCES answer (id) ON DELETE CASCADE,
        PRIMARY KEY (id_question, id_answer)
    );

CREATE TABLE
    content_version (
        id SERIAL PRIMARY KEY,
        body TEXT NOT NULL,
        search_body TSVECTOR NOT NULL,
        date TIMESTAMP DEFAULT now () NOT NULL,
        type main_content_type NOT NULL,
        id_question INTEGER REFERENCES question (id) ON DELETE CASCADE,
        id_answer INTEGER REFERENCES answer (id) ON DELETE CASCADE,
        CHECK (
            (
                type = 'QUESTION'
                AND id_question IS NOT NULL
                AND id_answer IS NULL
            )
            OR (
                type = 'ANSWER'
                AND id_question IS NULL
                AND id_answer IS NOT NULL
            )
        )
    );

CREATE TABLE
    question_tag (
        id_question INTEGER NOT NULL REFERENCES question (id) ON DELETE CASCADE,
        id_tag INTEGER NOT NULL REFERENCES tag (id), --Temos um trigger para quando se apaga uma tag
        PRIMARY KEY (id_question, id_tag)
    );

CREATE TABLE
    annex (
        id SERIAL PRIMARY KEY,
        type file_type NOT NULL,
        file_path TEXT NOT NULL,
        id_version INTEGER NOT NULL REFERENCES content_version (id) ON DELETE CASCADE
    );

CREATE TABLE
    vote (
        id SERIAL PRIMARY KEY,
        is_upvote BOOLEAN NOT NULL,
        type content_type NOT NULL,
        id_user INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        id_question INTEGER REFERENCES question (id) ON DELETE CASCADE,
        id_answer INTEGER REFERENCES answer (id) ON DELETE CASCADE,
        id_comment INTEGER REFERENCES comments (id) ON DELETE CASCADE CHECK (
            (
                type = 'QUESTION'
                AND id_question IS NOT NULL
                AND id_answer IS NULL
                AND id_comment IS NULL
            )
            OR (
                type = 'ANSWER'
                AND id_question IS NULL
                AND id_answer IS NOT NULL
                AND id_comment IS NULL
            )
            OR (
                type = 'COMMENT'
                AND id_question IS NULL
                AND id_answer IS NULL
                AND id_comment IS NOT NULL
            )
        )
    );

CREATE TABLE
    user_badge (
        id_user INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        id_badge INTEGER NOT NULL REFERENCES badge (id) ON DELETE CASCADE,
        date DATE DEFAULT now () NOT NULL,
        PRIMARY KEY (id_user, id_badge)
    );

CREATE TABLE
    notification (
        id SERIAL PRIMARY KEY,
        date TIMESTAMP DEFAULT now () NOT NULL,
        type notification_type NOT NULL,
        id_answer INTEGER REFERENCES answer (id) ON DELETE CASCADE,
        id_upvote INTEGER REFERENCES vote (id) ON DELETE CASCADE,
        id_badge INTEGER REFERENCES badge (id) ON DELETE CASCADE,
        id_user INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        CHECK (
            (
                type = 'ANSWER'
                AND id_answer IS NOT NULL
                AND id_upvote IS NULL
                AND id_badge IS NULL
            )
            OR (
                type = 'UPVOTE'
                AND id_answer IS NULL
                AND id_upvote IS NOT NULL
                AND id_badge IS NULL
            )
            OR (
                type = 'BADGE'
                AND id_answer IS NULL
                AND id_upvote IS NULL
                AND id_badge IS NOT NULL
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
        id_user INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        id_question INTEGER NOT NULL REFERENCES question (id) ON DELETE CASCADE,
        PRIMARY KEY (id_user, id_question)
    );

CREATE TABLE
    followed_tags (
        id_user INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        id_tag INTEGER NOT NULL REFERENCES tag (id) ON DELETE CASCADE,
        PRIMARY KEY (id_user, id_tag)
    );

CREATE TABLE
    followed_users (
        id_follower INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        id_followed INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
        PRIMARY KEY (id_follower, id_followed)
    );
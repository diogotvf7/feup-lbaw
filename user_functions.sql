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
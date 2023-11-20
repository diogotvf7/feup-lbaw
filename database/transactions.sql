BEGIN TRANSACTION;
   SET TRANSACTION ISOLATION LEVEL REPEATABLE READ

   INSERT INTO question (title, author)
   VALUES ($title, $author);

   INSERT INTO content_versions (body, type, id_question)
   VALUES ($body, 'QUESTION', currval('question_seq_id'));

   INSERT INTO annex (type, file_path, id_version)
   VALUES($type, $file_path, currval('content_version_seq_id'));


   END TRANSACTION;

BEGIN TRANSACTION;
   SET TRANSACTION ISOLATION LEVEL REPEATABLE READ

   INSERT INTO answer (author, id_question)
   VALUES ($author, $idquestion);

   INSERT INTO content_versions (body, type, id_answer)
   VALUES ($body, 'ANSWER', currval('answer_seq_id'));

   INSERT INTO annex (type, file_path, id_version)
   VALUES($type, $file_path, currval('content_version_seq_id'));


   END TRANSACTION;
DELIMITER $$
DROP PROCEDURE IF EXISTS lista_alunos$$
CREATE PROCEDURE lista_alunos(IN p_id_avaliador INT)
BEGIN
    SELECT id_aluno, nome, grupo, nota
    FROM aluno
    LEFT JOIN nota 
      ON id_aluno = id_aluno_avaliado
      AND id_aluno_avaliador = p_id_avaliador
    WHERE id_aluno <> p_id_avaliador
      AND id_aluno <> 1 -- professor
      AND grupo IN (3, 5, 6) -- grupos restantes
    ORDER BY grupo, nome;
END$$
DELIMITER ;

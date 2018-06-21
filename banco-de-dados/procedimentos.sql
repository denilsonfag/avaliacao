-- listar alunos, exceto o próprio aluno avaliador e o professor (id 1000):
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
    ORDER BY grupo, nome;
    -- ORDER BY id_aluno;
END$$
DELIMITER ;

-- CALL lista_alunos(1);


-- retornar os dados de um aluno:
DELIMITER $$
DROP PROCEDURE IF EXISTS dados_aluno$$
CREATE PROCEDURE dados_aluno(IN p_id_avaliador INT, IN p_id_avaliado INT)
BEGIN
    SELECT id_aluno, nome, grupo, nota
    FROM aluno
    LEFT JOIN nota 
      ON id_aluno = id_aluno_avaliado
      AND id_aluno_avaliador = p_id_avaliador
    WHERE id_aluno = p_id_avaliado;
END$$
DELIMITER ;

-- CALL dados_aluno(1,10);


-- inserir ou atualizar a nota de um aluno:
DELIMITER $$
DROP PROCEDURE IF EXISTS atualizar_nota$$
CREATE PROCEDURE atualizar_nota(IN p_id_avaliador INT, IN p_id_avaliado INT, IN p_nota INT)
BEGIN
    IF EXISTS (SELECT (1)
	  		   FROM nota
			   WHERE id_aluno_avaliador = p_id_avaliador
               AND id_aluno_avaliado = p_id_avaliado) 
	THEN
        UPDATE nota
        SET nota = p_nota
        WHERE id_aluno_avaliador = p_id_avaliador
	    AND id_aluno_avaliado = p_id_avaliado;
    ELSE
        INSERT INTO nota VALUES(p_id_avaliador, p_id_avaliado, p_nota);
	END IF;
END$$
DELIMITER ;

-- CALL atualizar_nota(1,2,NULL);



-- validar nota:
DELIMITER $$
DROP TRIGGER IF EXISTS validar_nota$$
CREATE TRIGGER validar_nota 
BEFORE INSERT ON nota
FOR EACH ROW
BEGIN
  IF NEW.nota NOT IN (1,2,3,4,5,6,7,8,9,10)
  THEN
    SIGNAL SQLSTATE '45000' 
    SET message_text = 'A nota deve ser um valor inteiro entre 1 e 10.';       
  END IF;
END$$
DELIMITER ;

-- INSERT INTO nota VALUES (1, 1, 10);

DELIMITER $$
DROP TRIGGER IF EXISTS validar_nota2$$
CREATE TRIGGER validar_nota2 
BEFORE UPDATE ON nota
FOR EACH ROW
BEGIN
  IF NEW.nota NOT IN (1,2,3,4,5,6,7,8,9,10)
  THEN
    SIGNAL SQLSTATE '45000' 
    SET message_text = 'A nota deve ser um valor inteiro entre 1 e 10.';       
  END IF;
END$$
DELIMITER ;


/*

-- impedir um aluno de votar nele mesmo:
DELIMITER $$
DROP TRIGGER IF EXISTS impedir_auto_voto$$
CREATE TRIGGER impedir_auto_voto 
BEFORE INSERT ON nota
FOR EACH ROW 
BEGIN
  IF NEW.id_aluno_avaliador = NEW.id_aluno_avaliado
  THEN
    SIGNAL SQLSTATE '45000' 
    SET message_text = 'Não pode votar em si mesmo';       
  END IF;
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS impedir_auto_voto2$$
CREATE TRIGGER impedir_auto_voto2 
BEFORE UPDATE ON nota
FOR EACH ROW 
BEGIN
  IF NEW.id_aluno_avaliador = NEW.id_aluno_avaliado
  THEN
    SIGNAL SQLSTATE '45000' 
    SET message_text = 'Não pode votar em si mesmo';       
  END IF;
END$$
DELIMITER ;

*/

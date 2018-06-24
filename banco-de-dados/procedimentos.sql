-- procedures:
-- listar alunos, exceto o próprio aluno avaliador e o professor (id 1):
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
END$$
DELIMITER ;

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

-- functions:
-- média das notas definidas pelo grupo:
DELIMITER ;;
DROP FUNCTION IF EXISTS media_grupo;;
CREATE FUNCTION media_grupo(p_id_aluno_avaliado INT) 
  RETURNS DECIMAL(4,2) 
    BEGIN
		DECLARE media FLOAT;
		
        SELECT AVG(nota)
        INTO media
		FROM lista_geral
		WHERE id_aluno_avaliado = p_id_aluno_avaliado
		  AND grupo_avaliado = grupo_avaliador
		GROUP BY id_aluno_avaliado;
  
		RETURN media;
    END;;
DELIMITER ;  

-- média das notas definidas pela turma, exceto os integrantes do grupo:
DELIMITER ;;
DROP FUNCTION IF EXISTS media_turma;;
CREATE FUNCTION media_turma(p_id_aluno_avaliado INT) 
  RETURNS DECIMAL(4,2) 
    BEGIN
		DECLARE media FLOAT;
		
        SELECT AVG(nota)
        INTO media
		FROM lista_geral
		WHERE id_aluno_avaliado = p_id_aluno_avaliado
		  AND grupo_avaliado <> grupo_avaliador
		GROUP BY id_aluno_avaliado;
  
		RETURN media;
    END;;
DELIMITER ;  

-- nota definida pelo professor:
DELIMITER ;;
DROP FUNCTION IF EXISTS nota_professor;;
CREATE FUNCTION nota_professor(p_id_aluno_avaliado INT) 
  RETURNS DECIMAL(4,2) 
    BEGIN
		DECLARE nota_prof FLOAT;
		
        SELECT nota
        INTO nota_prof
		FROM lista_geral
		WHERE id_aluno_avaliado = p_id_aluno_avaliado
		  AND id_aluno_avaliador = 1; -- id do professor
  
		RETURN nota_prof;
    END;;
DELIMITER ;  

-- views:
-- lista geral com todos os avaliados e avaliadores:
CREATE OR REPLACE VIEW lista_geral AS
SELECT avaliado.id_aluno id_aluno_avaliado, avaliado.nome nome_avaliado, avaliado.grupo grupo_avaliado, 
       avaliador.id_aluno id_aluno_avaliador, avaliador.nome nome_avaliador, avaliador.grupo grupo_avaliador,
       n.nota
FROM aluno avaliado
INNER JOIN nota n
  ON avaliado.id_aluno = n.id_aluno_avaliado
INNER JOIN aluno avaliador
  ON avaliador.id_aluno = n.id_aluno_avaliador
WHERE avaliado.id_aluno <> 1 -- professor
ORDER BY avaliado.id_aluno;

-- view com o resultado final:
CREATE OR REPLACE VIEW resultado_final AS
SELECT nome, 
	   media_grupo(id_aluno) media_grupo, 
	   media_turma(id_aluno) media_turma,
       nota_professor(id_aluno) nota_professor,
       ROUND(media_grupo(id_aluno) * 0.3 + media_turma(id_aluno) * 0.3 + nota_professor(id_aluno) * 0.4, 0) conceito_trabalho
FROM aluno
WHERE id_aluno <> 1 -- id do professor
ORDER BY nome; 

-- triggers:
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

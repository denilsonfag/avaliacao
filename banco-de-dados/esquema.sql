-- DROP DATABASE IF EXISTS abd;
CREATE DATABASE abd;
USE abd;

CREATE TABLE aluno(
  id_aluno INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL,
  grupo INT NOT NULL,
  login VARCHAR(50) NOT NULL UNIQUE,
  senha CHAR(32) NOT NULL
);
INSERT INTO aluno(id_aluno, nome, grupo, login, senha) VALUES (1, 'Professor', 0, 'prof', MD5('prof'));

CREATE TABLE nota(
  id_aluno_avaliador INT,
  id_aluno_avaliado INT,
  nota INT,
  PRIMARY KEY (id_aluno_avaliador, id_aluno_avaliado),
  FOREIGN KEY (id_aluno_avaliador)
  REFERENCES aluno(id_aluno),
  FOREIGN KEY (id_aluno_avaliado)
  REFERENCES aluno(id_aluno)
);

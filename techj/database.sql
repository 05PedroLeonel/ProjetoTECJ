
CREATE DATABASE IF NOT EXISTS tecj DEFAULT CHARACTER SET utf8mb4;
USE tecj;

CREATE TABLE login (
    idlogin INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE cliente (
    idcliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    idade INT,
    sexo VARCHAR(10),
    telefone VARCHAR(20),
    endereco VARCHAR(200),
    login_idlogin INT,
    foto VARCHAR(255),
    FOREIGN KEY (login_idlogin) REFERENCES login(idlogin) ON DELETE CASCADE
);

CREATE TABLE empresa (
    idempresa INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150),
    exigencias TEXT
);

CREATE TABLE vagas (
    idVagas INT AUTO_INCREMENT PRIMARY KEY,
    area_desejada VARCHAR(150),
    localidade VARCHAR(150),
    empresa_idempresa INT,
    FOREIGN KEY (empresa_idempresa) REFERENCES empresa(idempresa) ON DELETE SET NULL
);

CREATE TABLE empresa_has_cliente (
    empresa_idempresa INT,
    cliente_idcliente INT,
    PRIMARY KEY (empresa_idempresa, cliente_idcliente),
    FOREIGN KEY (empresa_idempresa) REFERENCES empresa(idempresa),
    FOREIGN KEY (cliente_idcliente) REFERENCES cliente(idcliente)
);

CREATE TABLE cliente_has_vagas (
    cliente_idcliente INT,
    Vagas_idVagas INT,
    PRIMARY KEY (cliente_idcliente, Vagas_idVagas),
    FOREIGN KEY (cliente_idcliente) REFERENCES cliente(idcliente) ON DELETE CASCADE,
    FOREIGN KEY (Vagas_idVagas) REFERENCES vagas(idVagas) ON DELETE CASCADE
);

CREATE TABLE skill (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    nome VARCHAR(120),
    nivel VARCHAR(50),
    FOREIGN KEY (cliente_id) REFERENCES cliente(idcliente) ON DELETE CASCADE
);

CREATE TABLE certificate (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    titulo VARCHAR(200),
    entidade VARCHAR(200),
    ano YEAR,
    FOREIGN KEY (cliente_id) REFERENCES cliente(idcliente) ON DELETE CASCADE
);

CREATE TABLE education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    instituicao VARCHAR(200),
    curso VARCHAR(200),
    ano_inicio YEAR,
    ano_fim YEAR,
    FOREIGN KEY (cliente_id) REFERENCES cliente(idcliente) ON DELETE CASCADE
);

CREATE TABLE course (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200),
    descricao TEXT
);

CREATE TABLE enrollment (
    cliente_id INT,
    course_id INT,
    progresso INT DEFAULT 0,
    PRIMARY KEY (cliente_id, course_id),
    FOREIGN KEY (cliente_id) REFERENCES cliente(idcliente) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES course(id) ON DELETE CASCADE
);

CREATE TABLE publication (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    conteudo TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES cliente(idcliente) ON DELETE SET NULL
);

CREATE TABLE follow (
    follower_id INT,
    following_id INT,
    PRIMARY KEY (follower_id, following_id),
    FOREIGN KEY (follower_id) REFERENCES cliente(idcliente) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES cliente(idcliente) ON DELETE CASCADE
);

INSERT INTO course (titulo, descricao) VALUES
('HTML Básico','Aprenda a estrutura do HTML'),
('CSS Básico','Estilize páginas com CSS'),
('JavaScript Básico','Introdução ao JS');

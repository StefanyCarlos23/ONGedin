CREATE DATABASE projeto_ongedin;
USE projeto_ongedin;

CREATE TABLE usuario (
id_usuario INT NOT NULL AUTO_INCREMENT,
nome VARCHAR(50) NOT NULL,
funcao CHAR(1) NOT NULL,
senha VARCHAR(255) NOT NULL,
data_cadastro DATETIME NOT NULL,
PRIMARY KEY (id_usuario)
);

CREATE TABLE contato (
id_usuario INT NOT NULL,
telefone VARCHAR(20),
email VARCHAR(40) NOT NULL UNIQUE,
FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE contribuinte (
id_contribuinte INT NOT NULL AUTO_INCREMENT,
cpf CHAR(11) NOT NULL,
data_nascimento DATE NOT NULL,
PRIMARY KEY (id_contribuinte),
FOREIGN KEY (id_contribuinte) REFERENCES usuario(id_usuario)
);

CREATE TABLE voluntario (
id_voluntario INT NOT NULL AUTO_INCREMENT,
total_horas INTEGER,
total_eventos INTEGER,
total_doacoes INTEGER,
PRIMARY KEY (id_voluntario),
FOREIGN KEY (id_voluntario) REFERENCES contribuinte(id_contribuinte)
);

CREATE TABLE doador (
id_doador INT NOT NULL AUTO_INCREMENT,
total_doacoes INTEGER,
PRIMARY KEY (id_doador),
FOREIGN KEY (id_doador) REFERENCES contribuinte(id_contribuinte)
);

CREATE TABLE administrador (
id_administrador INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY (id_administrador),
FOREIGN KEY (id_administrador) REFERENCES usuario(id_usuario)
);

CREATE TABLE administrador_ong (
id_admin_ong INT NOT NULL AUTO_INCREMENT,
area_atuacao VARCHAR(100) NOT NULL,
data_fundacao DATE NOT NULL,
endereco_rua VARCHAR(100) NOT NULL,
endereco_numero VARCHAR(10) NOT NULL,
endereco_complemento VARCHAR(50),
endereco_bairro VARCHAR(50) NOT NULL,
endereco_cidade VARCHAR(50) NOT NULL,
endereco_estado CHAR(2) NOT NULL,
endereco_pais VARCHAR(20) NOT NULL,
endereco_cep CHAR(8) NOT NULL,
PRIMARY KEY (id_admin_ong),
FOREIGN KEY (id_admin_ong) REFERENCES administrador(id_administrador)
);

CREATE TABLE website (
id_admin_ong INT NOT NULL,
link_site VARCHAR(200),
instagram VARCHAR(50),
facebook VARCHAR(50),
FOREIGN KEY (id_admin_ong) REFERENCES administrador_ong(id_admin_ong)
);

CREATE TABLE perfil (
id_perfil INT NOT NULL AUTO_INCREMENT,
descricao TEXT,
foto BLOB,
PRIMARY KEY (id_perfil),
FOREIGN KEY (id_perfil) REFERENCES usuario(id_usuario)
);

CREATE TABLE evento (
id_evento INT NOT NULL AUTO_INCREMENT,
titulo VARCHAR(100) NOT NULL,
descricao TEXT NOT NULL,
local_rua VARCHAR(100) NOT NULL,
local_numero VARCHAR(10) NOT NULL,
local_complemento VARCHAR(50),
local_bairro VARCHAR(50) NOT NULL,
local_cidade VARCHAR(50) NOT NULL,
local_estado CHAR(2) NOT NULL,
local_pais VARCHAR(20) NOT NULL,
PRIMARY KEY (id_evento)
);

CREATE TABLE avaliacao (
id_avaliacao INT NOT NULL AUTO_INCREMENT,
id_voluntario INT,
id_evento INT,
nota INT NOT NULL CHECK (nota BETWEEN 1 AND 5),
comentario TEXT NULL,
data_avaliacao DATETIME NOT NULL,
PRIMARY KEY (id_avaliacao),
FOREIGN KEY (id_voluntario) REFERENCES voluntario(id_voluntario),
FOREIGN KEY (id_evento) REFERENCES evento(id_evento)
);

CREATE TABLE voluntario_participa_evento (
id_evento INT NOT NULL,
id_voluntario INT NOT NULL,
funcao VARCHAR(50) NULL,
FOREIGN KEY (id_evento) REFERENCES evento(id_evento),
FOREIGN KEY (id_voluntario) REFERENCES voluntario(id_voluntario)
);

CREATE TABLE admin_ong_cadastra_evento (
id_admin_ong INT NOT NULL,
id_evento INT NOT NULL,
data_evento DATE NOT NULL,
horario_evento TIME NOT NULL,
FOREIGN KEY (id_admin_ong) REFERENCES administrador_ong(id_admin_ong),
FOREIGN KEY (id_evento) REFERENCES evento(id_evento)
);

CREATE TABLE doacao (
id_doacao INT NOT NULL AUTO_INCREMENT,
id_admin_ong INT,
item_doado VARCHAR(50) NOT NULL,
quantidade_item INTEGER NOT NULL,
status_doacao VARCHAR(20) NOT NULL,
data_recebe DATETIME NOT NULL,
PRIMARY KEY (id_doacao),
FOREIGN KEY (id_admin_ong) REFERENCES administrador_ong(id_admin_ong)
);

CREATE TABLE realiza (
id_doador INT NULL,
id_voluntario INT NULL,
id_doacao INT NOT NULL,
data_realiza DATETIME NOT NULL,
FOREIGN KEY (id_doador) REFERENCES doador(id_doador),
FOREIGN KEY (id_voluntario) REFERENCES voluntario(id_voluntario),
FOREIGN KEY (id_doacao) REFERENCES doacao(id_doacao)
);

CREATE TABLE comprovante (
id_comprovante INT NOT NULL AUTO_INCREMENT,
id_admin_ong INT,
id_voluntario INT,
id_evento INT,
quantidade_horas INTEGER NOT NULL,
PRIMARY KEY (id_comprovante),
FOREIGN KEY (id_admin_ong) REFERENCES administrador_ong(id_admin_ong),
FOREIGN KEY (id_voluntario) REFERENCES voluntario(id_voluntario),
FOREIGN KEY (id_evento) REFERENCES evento(id_evento)
);

CREATE TABLE administrador_sistema (
id_admin_sistema INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY (id_admin_sistema),
FOREIGN KEY (id_admin_sistema) REFERENCES administrador(id_administrador)
);

CREATE TABLE admin_sistema_verifica_comprovante (
id_admin_sistema INT NOT NULL,
id_comprovante INT NOT NULL,
data_validacao DATETIME NOT NULL,
FOREIGN KEY (id_admin_sistema) REFERENCES administrador_sistema(id_admin_sistema),
FOREIGN KEY (id_comprovante) REFERENCES comprovante(id_comprovante)
);

CREATE TABLE admin_sistema_gerencia_perfil (
id_perfil INT NOT NULL,
id_admin_sistema INT NOT NULL,
FOREIGN KEY (id_perfil) REFERENCES perfil(id_perfil),
FOREIGN KEY (id_admin_sistema) REFERENCES administrador_sistema(id_admin_sistema)
);
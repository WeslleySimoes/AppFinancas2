CREATE DATABASE financasPessoais;

USE financasPessoais;

CREATE TABLE usuario
(
    idUsuario   INT PRIMARY KEY NOT NULL,
    nome        VARCHAR(60) NOT NULL,
    sobrenome   VARCHAR(60) NOT NULL,
    email       VARCHAR(60) NOT NULL,
    senha       VARCHAR(8) NOT NULL,
    status_usu  ENUM('PENDENTE','ABERTO')
);

CREATE TABLE conta
(
    idConta     INT PRIMARY KEY NOT NULL,
    descricao   VARCHAR(60) NOT NULL,
    montante    DECIMAL(10,2) NOT NULL,
    instituicao_fin VARCHAR(60),
    tipo_conta  ENUM('Corrente','Poupança','Dinheiro','Investimento','Outros'),
    id_usuario  INT NOT NULL,
    FOREIGN KEY(id_usuario) REFERENCES usuario(idUsuario)
);

CREATE TABLE categoria
(
    idCategoria INT PRIMARY KEY NOT NULL,
    nome        VARCHAR(60) NOT NULL,
    tipo        ENUM('despesa','receita'),
    id_usuario  INT,
    FOREIGN KEY(id_usuario) REFERENCES usuario(idUsuario)
);

CREATE TABLE transferencia
(
    idTransferencia     INT PRIMARY KEY NOT NULL,
    valor               DECIMAL(10,2) NOT NULL,
    descricao           VARCHAR(60) NOT NULL,
    data_transf         DATE NOT NULL,
    id_conta_origem     INT NOT NULL,
    id_conta_destino    INT NOT NULL,
    id_usuario          INT NOT NULL,
    FOREIGN KEY(id_usuario)         REFERENCES usuario(idUsuario),
    FOREIGN KEY(id_conta_origem)    REFERENCES conta(idConta),
    FOREIGN KEY(id_conta_destino)   REFERENCES conta(idConta)
);

CREATE TABLE despesa_fixa 
(
    idDesp      INT PRIMARY KEY NOT NULL,
    valor       DECIMAL(10,2) NOT NULL,
    descricao   VARCHAR(60) NOT NULL,
    id_categoria INT NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim    DATE,
    status_desp ENUM('aberto','fechado') DEFAULT 'aberto',
    id_usuario  INT NOT NULL,
    id_conta    INT NOT NULL, 
    FOREIGN KEY(id_usuario) REFERENCES usuario(idUsuario),
    FOREIGN KEY(id_conta)   REFERENCES conta(idConta),
    FOREIGN KEY(id_categoria) REFERENCES categoria(idCategoria)
);

CREATE TABLE receita_fixa 
(
    idRec       INT PRIMARY KEY NOT NULL,
    valor       DECIMAL(10,2) NOT NULL,
    descricao   VARCHAR(60) NOT NULL,
    id_categoria INT NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim    DATE,
    status_rec  ENUM('aberto','fechado') DEFAULT 'aberto',
    id_usuario  INT NOT NULL,
    id_conta    INT NOT NULL, 
    FOREIGN KEY(id_usuario) REFERENCES usuario(idUsuario),
    FOREIGN KEY(id_conta)   REFERENCES conta(idConta),
    FOREIGN KEY(id_categoria) REFERENCES categoria(idCategoria)
);

CREATE TABLE transacao 
(
    idTransacao  INT PRIMARY KEY NOT NULL,
    data_trans   DATE NOT NULL,
    valor        DECIMAL(10,2) NOT NULL,
    descricao    VARCHAR(60) NOT NULL,
    id_categoria INT NOT NULL,
    tipo         ENUM('despesa','receita','transferencia') NOT NULL,
    fixo         TINYINT(4) NOT NULL DEFAULT 0,
    status_trans ENUM('pendente','fechado'),
    id_despesaFixa   INT DEFAULT NULL,
    id_receitaFixa   INT DEFAULT NULL,
    id_transferencia INT DEFAULT NULL,
    id_usuario       INT NOT NULL,
    id_conta         INT NOT NULL,
    FOREIGN KEY(id_categoria)       REFERENCES categoria(idCategoria),
    FOREIGN KEY(id_despesaFixa)     REFERENCES despesa_fixa(idDesp),
    FOREIGN KEY(id_receitaFixa)     REFERENCES receita_fixa(idRec),
    FOREIGN KEY(id_transferencia)   REFERENCES transferencia(idTransferencia),
    FOREIGN KEY(id_usuario)         REFERENCES usuario(idUsuario),
    FOREIGN KEY(id_conta)           REFERENCES conta(idConta)
);

-- ALTER TABLE receita_fixa ADD CONSTRAINT fk_id_categoria
-- FOREIGN KEY(id_categoria) REFERENCES categoria(idCategoria)


-- ADICIONANDO COLUNA A UMA TABELA EXISTENTE NO BANCO DE DADOS
ALTER TABLE transferencia ADD COLUMN status_transf ENUM('aberto','fechado') DEFAULT 'aberto' BEF id_usuario;

ALTER TABLE transferencia ADD COLUMN data_transf DATE NOT NULL;


ALTER TABLE conta ADD COLUMN status_conta ENUM('ativo','arquivado') DEFAULT 'ativo' AFTER tipo_conta;
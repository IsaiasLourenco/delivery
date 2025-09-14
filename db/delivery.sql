CREATE DATABASE delivery;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(80) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL,
    nivel VARCHAR(50) NOT NULL,
    ativo VARCHAR(3) NOT NULL
);

ALTER TABLE usuarios 
ADD COLUMN data_cad DATE AFTER ativo;

ALTER TABLE usuarios 
ADD COLUMN cpf VARCHAR(14) NOT NULL AFTER email;

ALTER TABLE usuarios 
ADD COLUMN foto VARCHAR(100) AFTER ativo;

ALTER TABLE usuarios 
ADD COLUMN telefone VARCHAR(20) AFTER cpf;

ALTER TABLE usuarios 
ADD COLUMN cep VARCHAR(40) AFTER telefone,
ADD COLUMN rua VARCHAR(40) AFTER cep,
ADD COLUMN numero VARCHAR(5) AFTER rua,
ADD COLUMN bairro VARCHAR(40) AFTER numero,
ADD COLUMN cidade VARCHAR(40) AFTER bairro,
ADD COLUMN estado VARCHAR(2) AFTER cidade;

CREATE TABLE config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_sistema VARCHAR(50) NOT NULL,
    email_sistema VARCHAR(80) NOT NULL UNIQUE,
    telefone_sistema VARCHAR(15) NOT NULL,
    telefone_fixo VARCHAR(15),
    cnpj_sistema VARCHAR(18) NOT NULL,
    cep_sistema VARCHAR(40),
    rua_sistema VARCHAR(40),
    numero_sistema VARCHAR(5),
    bairro_sistema VARCHAR(40),
    cidade_sistema VARCHAR(40),
    estado_sistema VARCHAR(2),
    instagram_sistema VARCHAR(50),
    tipo_relatório VARCHAR(5),
    cards VARCHAR(5),
    pedidos VARCHAR(3),
    desenvolvedor VARCHAR(50),
    site_dev VARCHAR(50),
    previsao_entrega VARCHAR(10),
    estabelecimento_aberto VARCHAR(10),
    abertura TIME,
    fechamento TIME,
    texto_fechamento VARCHAR(50),
    logotipo VARCHAR(100),
    icone VARCHAR(100),
    logo_rel VARCHAR(100)
);
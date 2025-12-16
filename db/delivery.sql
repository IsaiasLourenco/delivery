ALTER TABLE usuarios 
ADD COLUMN data_cad DATE AFTER ativo;

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

ALTER TABLE usuarios 
ADD COLUMN tipo_chave VARCHAR(35) AFTER data_cad;
ADD COLUMN chave_pix VARCHAR(100) AFTER tipo_chave;

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

ALTER TABLE config ADD COLUMN url_sistema VARCHAR(100) AFTER logo_rel;
ALTER TABLE config ADD COLUMN tempo_atualizacao VARCHAR(10) AFTER url_sistema;

CREATE TABLE cargos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

CREATE TABLE funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(80) NOT NULL UNIQUE,
    cpf VARCHAR(14) NOT NULL UNIQUE;
    telefone VARCHAR(15) NOT NULL,
    cep VARCHAR(10),
    rua VARCHAR(40),
    numero VARCHAR(5),
    bairro VARCHAR(40),
    cidade VARCHAR(40),
    estado VARCHAR(2),
    nivel VARCHAR(50) NOT NULL,
    ativo VARCHAR(3) NOT NULL,
    foto VARCHAR(100),
    data_cad DATE
);

ALTER TABLE funcionarios
ADD COLUMN tipo_chave VARCHAR(35) AFTER data_cad,
ADD COLUMN chave_pix VARCHAR(100) AFTER tipo_chave;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao VARCHAR(80) NOT NULL,
    foto VARCHAR(100),
    cor  VARCHAR(30) NOT NULL
);

ALTER TABLE categorias ADD COLUMN url VARCHAR(100) AFTER ativo;

ALTER TABLE categorias 
ADD COLUMN ativo VARCHAR(3) AFTER cor;

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao VARCHAR(80) NOT NULL,
    categoria INT NOT NULL,
    valor_compra DECIMAL (10,2) NOT NULL,
    valor_venda DECIMAL (10,2) NOT NULL,
    estoque INT NOT NULL,
    foto VARCHAR(100),
    nivel_estoque INT NOT NULL,
    ativo VARCHAR(3) NOT NULL
);

ALTER TABLE produtos ADD COLUMN url VARCHAR(100) AFTER tem_estoque;

ALTER TABLE produtos 
ADD COLUMN tem_estoque VARCHAR(3) AFTER ativo;

CREATE TABLE saidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto INT NOT NULL,
    quantidade INT NOT NULL,
    motivo VARCHAR(50),
    usuario INT NOT NULL,
    data_saida DATE NOT NULL
);

CREATE TABLE entradas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto INT NOT NULL,
    quantidade INT NOT NULL,
    motivo VARCHAR(50),
    usuario INT NOT NULL,
    data_entrada DATE NOT NULL
);

CREATE TABLE variacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto INT NOT NULL,
    sigla VARCHAR(5),
    descricao varchar(35),
    valor DECIMAL(10,2)
);

ALTER TABLE variacoes
ADD COLUMN nome VARCHAR(30) AFTER sigla;

ALTER TABLE variacoes
ADD COLUMN ativo VARCHAR(3) AFTER valor;

CREATE TABLE ingredientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto INT NOT NULL,
    nome VARCHAR(50),
    ativo VARCHAR(3)
);

CREATE TABLE adicionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto INT NOT NULL,
    nome VARCHAR(50),
    valor DECIMAL(10,2),
    ativo VARCHAR(3)
);

ALTER TABLE adicionais ADD COLUMN categoria INT AFTER ativo;

CREATE TABLE cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(80) NOT NULL UNIQUE,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(15) NOT NULL,
    cep VARCHAR(10),
    rua VARCHAR(40),
    numero VARCHAR(5),
    bairro VARCHAR(40),
    cidade VARCHAR(40),
    estado VARCHAR(2),
    data_cad DATE
);

ALTER TABLE cliente
ADD COLUMN tipo_chave VARCHAR(35) AFTER data_cad,
ADD COLUMN chave_pix VARCHAR(100) AFTER tipo_chave;

CREATE TABLE bairros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    valor DECIMAL(10,2) NOT NULL
);

ALTER TABLE cliente ADD COLUMN bairro_id INT AFTER nome;

CREATE TABLE fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(80) NOT NULL UNIQUE,
    cnpj VARCHAR(18) NOT NULL UNIQUE,
    telefone VARCHAR(15) NOT NULL,
    cep VARCHAR(10),
    rua VARCHAR(40),
    numero VARCHAR(5),
    bairro VARCHAR(40),
    cidade VARCHAR(40),
    estado VARCHAR(2),
    data_cad DATE
);

ALTER TABLE fornecedores
ADD COLUMN tipo_chave VARCHAR(35) AFTER data_cad,
ADD COLUMN chave_pix VARCHAR(100) AFTER tipo_chave;
ADD COLUMN produto INT AFTER CNPJ;


CREATE TABLE pagar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(50) NOT NULL,
    tipo VARCHAR(35) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_lancamento DATE NOT NULL,
    data_vencimento DATE NOT NULL,
    data_pagamento DATE NOT NULL,
    usuario_baixa INT,
    foto VARCHAR(100),
    pessoa INT,
    pago VARCHAR(5),
    produto INT,
    quantidade INT,
    funcionario INT
);

ALTER TABLE pagar ADD COLUMN usuario_lancou INT AFTER data_pagamento;
ALTER TABLE pagar ADD COLUMN cliente INT AFTER funcionario;

ALTER TABLE pagar
ADD COLUMN tipo_chave VARCHAR(35) AFTER cliente,
ADD COLUMN chave_pix VARCHAR(100) AFTER tipo_chave;

CREATE TABLE receber (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(50) NOT NULL,
    tipo VARCHAR(35) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_lancamento DATE NOT NULL,
    data_vencimento DATE NOT NULL,
    data_pagamento DATE NOT NULL,
    usuario_baixa INT,
    foto VARCHAR(100),
    pessoa INT,
    pago VARCHAR(5),
    produto INT,
    quantidade INT
);

ALTER TABLE receber
ADD COLUMN fornecedor INT AFTER quantidade;

CREATE TABLE fornecedores_produtos (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  fornecedor_id INT NOT NULL,
  produto_id INT NOT NULL,
  valor_compra DECIMAL(10,2) DEFAULT 0.00,
  prazo_entrega VARCHAR(50) DEFAULT NULL,
  principal TINYINT(1) DEFAULT 0,
  observacoes TEXT DEFAULT NULL,
  data_cad DATE DEFAULT CURRENT_DATE,
  FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id),
  FOREIGN KEY (produto_id) REFERENCES produtos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente INT NOT NULL,
    valor_compra DECIMAL(10,2) NOT NULL,
    valor_pago DECIMAL(10,2) NOT NULL,
    troco DECIMAL(10,2),
    data_pagamento DATE NOT NULL,
    hora_pagamento TIME NOT NULL,
    status_venda VARCHAR(10),
    pago VARCHAR(5),
    obs VARCHAR(80),
    valor_entrega INT,
    tipo_pagamento VARCHAR(80)
);

CREATE TABLE carrinho_temp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sessao VARCHAR(50),          -- session_id() do PHP
    tipo VARCHAR(20),            -- produto, variacao, adicional, ingrediente
    id_item INT,                 -- ID do item na tabela original
    quantidade INT DEFAULT 1,    -- quantidade escolhida
    valor_item DECIMAL(10,2),         -- preço unitário
    valor_total DECIMAL(10,2),   -- preço * quantidade
    observacao TEXT,             -- observações do cliente
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE carrinho_temp
ADD produto_id INT AFTER sessao;

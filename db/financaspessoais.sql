-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12-Ago-2022 às 22:08
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `financaspessoais`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `tipo` enum('despesa','receita') DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`idCategoria`, `nome`, `tipo`, `id_usuario`) VALUES
(1, 'Mercado', 'despesa', 1),
(2, 'Lazer', 'despesa', 1),
(3, 'Feira', 'despesa', 1),
(4, 'Celular', 'despesa', 1),
(5, 'Salário', 'receita', 1),
(6, 'Aluguel', 'receita', 1),
(7, 'Dividendos', 'receita', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `conta`
--

CREATE TABLE `conta` (
  `idConta` int(11) NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `montante` decimal(10,2) NOT NULL,
  `instituicao_fin` varchar(60) DEFAULT NULL,
  `tipo_conta` enum('Corrente','Poupança','Dinheiro','Investimento','Outros') DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `conta`
--

INSERT INTO `conta` (`idConta`, `descricao`, `montante`, `instituicao_fin`, `tipo_conta`, `id_usuario`) VALUES
(1, 'Teste 1', '851.00', 'Bradesco', 'Poupança', 1),
(2, 'Teste 2', '120.00', 'Bradesco', 'Corrente', 1),
(3, 'Teste 3', '120.00', 'Bradesco', 'Corrente', 1),
(4, 'Dividendos', '950.00', 'Santander', 'Corrente', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `despesa_fixa`
--

CREATE TABLE `despesa_fixa` (
  `idDesp` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `status_desp` enum('aberto','fechado') DEFAULT 'aberto',
  `id_usuario` int(11) NOT NULL,
  `id_conta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `despesa_fixa`
--

INSERT INTO `despesa_fixa` (`idDesp`, `valor`, `descricao`, `id_categoria`, `data_inicio`, `data_fim`, `status_desp`, `id_usuario`, `id_conta`) VALUES
(1, '200.00', 'Roupas', 1, '2022-05-23', '2022-08-23', 'aberto', 1, 1),
(2, '20.00', 'Netflix', 3, '2022-05-23', '0000-00-00', 'aberto', 1, 2),
(3, '200.00', 'Armário', 1, '2022-05-23', '2022-10-23', 'aberto', 1, 1),
(4, '59.00', 'Celular internet 12gb - Claro', 1, '2022-05-13', '0000-00-00', 'aberto', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `receita_fixa`
--

CREATE TABLE `receita_fixa` (
  `idRec` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `status_rec` enum('aberto','fechado') DEFAULT 'aberto',
  `id_usuario` int(11) NOT NULL,
  `id_conta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `receita_fixa`
--

INSERT INTO `receita_fixa` (`idRec`, `valor`, `descricao`, `id_categoria`, `data_inicio`, `data_fim`, `status_rec`, `id_usuario`, `id_conta`) VALUES
(1, '1.50', 'Empresa Teste SA.', 5, '2022-06-27', '0000-00-00', 'aberto', 1, 1),
(2, '2000.00', 'TEste Teste SA.', 5, '2022-06-27', '2022-11-27', 'aberto', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacao`
--

CREATE TABLE `transacao` (
  `idTransacao` int(11) NOT NULL,
  `data_trans` date NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `tipo` enum('despesa','receita','transferencia') NOT NULL,
  `fixo` tinyint(4) NOT NULL DEFAULT 0,
  `status_trans` enum('pendente','fechado') DEFAULT NULL,
  `id_despesaFixa` int(11) DEFAULT NULL,
  `id_receitaFixa` int(11) DEFAULT NULL,
  `id_transferencia` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_conta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `transacao`
--

INSERT INTO `transacao` (`idTransacao`, `data_trans`, `valor`, `descricao`, `id_categoria`, `tipo`, `fixo`, `status_trans`, `id_despesaFixa`, `id_receitaFixa`, `id_transferencia`, `id_usuario`, `id_conta`) VALUES
(2, '2022-08-09', '800.00', 'Compra de equipamento de estudo 2', 2, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 2),
(3, '2022-08-09', '50.00', 'Compra de caderno escolar.', 1, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 2),
(4, '2022-08-08', '2623.01', 'asdasdsad', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(7, '2022-08-09', '2500.03', 'Compra de equipamento de estudo', 6, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(8, '2022-08-09', '5.77', 'sfsdfsdf', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(9, '2022-08-09', '8787.87', 'grgrgdgr', 1, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(10, '2022-08-09', '12123.11', 'effwfwefef', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(12, '2022-08-12', '500.00', 'sfddsfsdf', 5, 'receita', 0, 'pendente', NULL, NULL, NULL, 1, 4),
(13, '2022-08-12', '120.00', 'Tua conta', NULL, 'transferencia', 0, 'pendente', NULL, NULL, 1, 1, 1),
(14, '2022-08-13', '100.00', 'Lorem impsun 2', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 2, 1, 4),
(15, '2022-08-12', '100.00', 'fcsdf', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 3, 1, 3),
(16, '2022-08-05', '3212.35', '55454545454', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 4, 1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `transferencia`
--

CREATE TABLE `transferencia` (
  `idTransferencia` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `id_conta_origem` int(11) NOT NULL,
  `id_conta_destino` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `status_transf` enum('aberto','fechado') DEFAULT 'aberto',
  `data_transf` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `transferencia`
--

INSERT INTO `transferencia` (`idTransferencia`, `valor`, `descricao`, `id_conta_origem`, `id_conta_destino`, `id_usuario`, `status_transf`, `data_transf`) VALUES
(1, '120.00', 'Tua conta', 1, 3, 1, 'aberto', '2022-08-12'),
(2, '100.00', 'Lorem impsun 2', 4, 1, 1, 'fechado', '2022-08-13'),
(3, '100.00', 'fcsdf', 3, 4, 1, 'fechado', '2022-08-12'),
(4, '3212.35', '55454545454', 2, 1, 1, 'fechado', '2022-08-05');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `sobrenome` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `senha` varchar(8) NOT NULL,
  `status_usu` enum('PENDENTE','ABERTO') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `nome`, `sobrenome`, `email`, `senha`, `status_usu`) VALUES
(1, 'Weslley', 'Simões', 'weslley@teste.com', '123456', 'ABERTO');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idCategoria`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices para tabela `conta`
--
ALTER TABLE `conta`
  ADD PRIMARY KEY (`idConta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices para tabela `despesa_fixa`
--
ALTER TABLE `despesa_fixa`
  ADD PRIMARY KEY (`idDesp`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_conta` (`id_conta`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Índices para tabela `receita_fixa`
--
ALTER TABLE `receita_fixa`
  ADD PRIMARY KEY (`idRec`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_conta` (`id_conta`),
  ADD KEY `fk_id_categoria` (`id_categoria`);

--
-- Índices para tabela `transacao`
--
ALTER TABLE `transacao`
  ADD PRIMARY KEY (`idTransacao`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_despesaFixa` (`id_despesaFixa`),
  ADD KEY `id_receitaFixa` (`id_receitaFixa`),
  ADD KEY `id_transferencia` (`id_transferencia`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_conta` (`id_conta`);

--
-- Índices para tabela `transferencia`
--
ALTER TABLE `transferencia`
  ADD PRIMARY KEY (`idTransferencia`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_conta_origem` (`id_conta_origem`),
  ADD KEY `id_conta_destino` (`id_conta_destino`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `categoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`idUsuario`);

--
-- Limitadores para a tabela `conta`
--
ALTER TABLE `conta`
  ADD CONSTRAINT `conta_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`idUsuario`);

--
-- Limitadores para a tabela `despesa_fixa`
--
ALTER TABLE `despesa_fixa`
  ADD CONSTRAINT `despesa_fixa_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`idUsuario`),
  ADD CONSTRAINT `despesa_fixa_ibfk_2` FOREIGN KEY (`id_conta`) REFERENCES `conta` (`idConta`),
  ADD CONSTRAINT `despesa_fixa_ibfk_3` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`idCategoria`);

--
-- Limitadores para a tabela `receita_fixa`
--
ALTER TABLE `receita_fixa`
  ADD CONSTRAINT `fk_id_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`idCategoria`),
  ADD CONSTRAINT `receita_fixa_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`idUsuario`),
  ADD CONSTRAINT `receita_fixa_ibfk_2` FOREIGN KEY (`id_conta`) REFERENCES `conta` (`idConta`);

--
-- Limitadores para a tabela `transacao`
--
ALTER TABLE `transacao`
  ADD CONSTRAINT `transacao_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`idCategoria`),
  ADD CONSTRAINT `transacao_ibfk_2` FOREIGN KEY (`id_despesaFixa`) REFERENCES `despesa_fixa` (`idDesp`),
  ADD CONSTRAINT `transacao_ibfk_3` FOREIGN KEY (`id_receitaFixa`) REFERENCES `receita_fixa` (`idRec`),
  ADD CONSTRAINT `transacao_ibfk_4` FOREIGN KEY (`id_transferencia`) REFERENCES `transferencia` (`idTransferencia`),
  ADD CONSTRAINT `transacao_ibfk_5` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`idUsuario`),
  ADD CONSTRAINT `transacao_ibfk_6` FOREIGN KEY (`id_conta`) REFERENCES `conta` (`idConta`);

--
-- Limitadores para a tabela `transferencia`
--
ALTER TABLE `transferencia`
  ADD CONSTRAINT `transferencia_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`idUsuario`),
  ADD CONSTRAINT `transferencia_ibfk_2` FOREIGN KEY (`id_conta_origem`) REFERENCES `conta` (`idConta`),
  ADD CONSTRAINT `transferencia_ibfk_3` FOREIGN KEY (`id_conta_destino`) REFERENCES `conta` (`idConta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07-Dez-2022 às 20:55
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

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
  `cor_cate` varchar(100) NOT NULL,
  `tipo` enum('despesa','receita') DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `status_cate` enum('ativo','arquivado') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`idCategoria`, `nome`, `cor_cate`, `tipo`, `id_usuario`, `status_cate`) VALUES
(1, 'Mercado', '#ab2645', 'despesa', 1, 'ativo'),
(2, 'Lazer', '#68a530', 'despesa', 1, 'ativo'),
(3, 'Feira', '#47e5bb', 'despesa', 1, 'ativo'),
(4, 'Celular', '#1081a0', 'despesa', 1, 'ativo'),
(5, 'Salário', '#2b34ad', 'receita', 1, 'ativo'),
(6, 'Aluguel', '#5e5987', 'receita', 1, 'ativo'),
(7, 'Dividendos', '#e5c038', 'receita', 1, 'ativo'),
(8, 'Natação', '#8e6e5f', 'despesa', 1, 'ativo'),
(9, 'Exemplo', '#e26702', 'despesa', 1, 'ativo'),
(10, 'Teste', '#f73b3b', 'despesa', 1, 'arquivado'),
(11, 'Funcionamento normal', '#f6b73c', 'despesa', 1, 'arquivado'),
(12, 'Conta 2 categoria', '#f6b73c', 'despesa', 2, 'ativo'),
(13, 'Mercado', '#6b2a39', 'despesa', 3, 'ativo'),
(14, 'Lazer', '#68a530', 'despesa', 3, 'ativo'),
(15, 'Saúde', '#47e5bb', 'despesa', 3, 'ativo'),
(16, 'Salário', '#2b34ad', 'receita', 3, 'ativo'),
(17, 'Renda extra', '#f73b3b', 'receita', 3, 'ativo'),
(18, 'Dividendos', '#5a6045', 'receita', 3, 'ativo'),
(19, 'Mercado', '#6b2a39', 'despesa', 4, 'ativo'),
(20, 'Lazer', '#68a530', 'despesa', 4, 'ativo'),
(21, 'Saúde', '#47e5bb', 'despesa', 4, 'ativo'),
(22, 'Salário', '#2b34ad', 'receita', 4, 'ativo'),
(23, 'Renda extra', '#f73b3b', 'receita', 4, 'ativo'),
(24, 'Dividendos', '#5a6045', 'receita', 4, 'ativo'),
(25, 'Renda extra', '#388703', 'receita', 1, 'ativo'),
(26, 'Equipamentos', '#1d5d25', 'despesa', 1, 'ativo'),
(27, 'Maquinas', '#d59515', 'despesa', 1, 'ativo'),
(28, 'Mão de obra', '#a415cb', 'despesa', 1, 'ativo'),
(29, 'Água', '#f532b8', 'despesa', 1, 'ativo'),
(30, 'Energia', '#1724d3', 'despesa', 1, 'ativo');

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
  `status_conta` enum('ativo','arquivado') DEFAULT 'ativo',
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `conta`
--

INSERT INTO `conta` (`idConta`, `descricao`, `montante`, `instituicao_fin`, `tipo_conta`, `status_conta`, `id_usuario`) VALUES
(1, 'Conta 1 (Bradesco)', '851.00', 'Bradesco', 'Poupança', 'ativo', 1),
(2, 'Conta 2 (Bradesco)', '120.00', 'Bradesco', 'Corrente', 'ativo', 1),
(3, 'Conta 3 (Bradesco)', '120.00', 'Bradesco', 'Corrente', 'ativo', 1),
(4, 'Dividendos (Santander)', '950.00', 'Santander', 'Corrente', 'ativo', 1),
(6, 'Teste', '100.00', 'Bradesco', 'Corrente', 'ativo', 2),
(7, 'Conta principal', '200.00', 'Brandesco', 'Corrente', 'ativo', 3),
(8, 'Conta principal', '100.00', 'Brandesco', 'Corrente', 'ativo', 4),
(9, 'Conta 2(Santander)', '50.00', 'Santander', 'Poupança', 'ativo', 4);

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
(1, '7.50', 'Mensalidade Academia', 2, '2022-12-06', '2023-12-06', 'aberto', 1, 1),
(2, '50.00', 'Mensalidade Netflix', 2, '2022-12-06', '0000-00-00', 'aberto', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `plancategoria`
--

CREATE TABLE `plancategoria` (
  `idPCat` int(11) NOT NULL,
  `valorMeta` decimal(10,2) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_planejamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `plancategoria`
--

INSERT INTO `plancategoria` (`idPCat`, `valorMeta`, `id_categoria`, `id_planejamento`) VALUES
(22, '100.00', 1, 6),
(23, '50.00', 2, 6),
(24, '150.00', 3, 6),
(25, '1000.00', 1, 7),
(26, '500.00', 2, 7),
(31, '3000.00', 1, 10),
(32, '500.00', 3, 10),
(33, '500.00', 2, 10),
(38, '500.00', 1, 13),
(44, '10000.00', 1, 16),
(45, '20000.00', 2, 16),
(68, '250.00', 3, 15),
(69, '1000.00', 4, 15),
(70, '250.00', 8, 15),
(71, '10000.00', 4, 16),
(72, '10000.00', 9, 16),
(73, '20000.00', 1, 19),
(74, '30000.00', 2, 19),
(75, '10000.00', 3, 19),
(76, '1000.00', 2, 15);

-- --------------------------------------------------------

--
-- Estrutura da tabela `planejamento`
--

CREATE TABLE `planejamento` (
  `idPlan` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descricao` varchar(60) DEFAULT NULL,
  `porcentagem` int(11) DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `tipo` enum('mensal','personalizado') DEFAULT NULL,
  `status_plan` enum('ativo','expirado') DEFAULT 'ativo',
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `planejamento`
--

INSERT INTO `planejamento` (`idPlan`, `valor`, `descricao`, `porcentagem`, `data_inicio`, `data_fim`, `tipo`, `status_plan`, `id_usuario`) VALUES
(6, '3000.00', NULL, 10, '2022-08-01', '2022-08-31', 'mensal', 'ativo', 1),
(7, '3000.00', NULL, 50, '2022-07-01', '2022-07-31', 'mensal', 'ativo', 1),
(10, '50000.00', NULL, 10, '2022-09-01', '2022-09-30', 'mensal', 'ativo', 1),
(13, '5000.00', NULL, 10, '2022-10-01', '2022-10-31', 'mensal', 'ativo', 1),
(15, '5000.00', NULL, 30, '2022-11-01', '2022-11-30', 'mensal', 'ativo', 1),
(16, '50000.00', 'Construção de banheiro', NULL, '2022-11-05', '2022-12-10', 'personalizado', 'ativo', 1),
(19, '60000.00', 'Reforma da casa', NULL, '2022-11-29', '2023-02-28', 'personalizado', 'ativo', 1);

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
(1, '156.00', 'Dividêndo PETR4', 7, '2022-08-25', '0000-00-00', 'aberto', 1, 2),
(2, '5000.00', 'Salário empresa Teste LTDA.', 5, '2022-12-06', NULL, 'aberto', 1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacao`
--

CREATE TABLE `transacao` (
  `idTransacao` int(11) NOT NULL,
  `data_trans` datetime NOT NULL,
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
(10, '2022-08-09 00:00:00', '12123.11', 'effwfwefef', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(12, '2022-08-12 00:00:00', '500.00', 'sfddsfsdf', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 4),
(13, '2022-08-12 00:00:00', '120.00', 'Tua conta', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 1, 1, 1),
(14, '2022-08-13 00:00:00', '100.00', 'Lorem impsun 2', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 2, 1, 4),
(16, '2022-08-05 00:00:00', '3212.35', '55454545454', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 4, 1, 2),
(17, '2022-08-26 00:00:00', '60.00', 'asdasd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 3),
(18, '2022-08-26 00:00:00', '90.00', 'asdasd', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(19, '2022-08-26 00:00:00', '35.00', 'asdasd', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(20, '2022-08-26 00:00:00', '875.00', 'asdasd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(21, '2022-08-26 00:00:00', '6.58', 'wdawwd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(22, '2022-08-25 00:00:00', '178.00', 'Whey protein Max Titanium', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(23, '2022-08-25 00:00:00', '49.99', 'Termogênico Integral Médica', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(24, '2022-08-31 00:00:00', '600.00', 'Recebido via PIX do Fulano de tal.', 6, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 3),
(25, '2022-08-31 00:00:00', '90.00', 'Para alguma ação', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 5, 1, 1),
(26, '2022-09-01 00:00:00', '90.00', 'Fundo imobiliário MXFR11', 7, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(27, '2022-09-01 00:00:00', '12.00', 'Compra de pães e mortadela e mussarela.', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(28, '2022-09-03 00:00:00', '90.00', 'EDQWD', 1, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(29, '2022-09-03 00:00:00', '90.00', 'DQWQWDQWDWQD', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(31, '2022-09-03 00:00:00', '90.00', 'QWDQWDQWDQWDQWD', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(32, '2022-09-03 00:00:00', '21.23', 'AWDWDWD', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(33, '2022-09-03 00:00:00', '90.00', 'DQWDQWDQWD', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(34, '2022-09-03 00:00:00', '3212.35', 'QDQWDQWDWD', 1, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(35, '2022-09-03 00:00:00', '90.00', 'DWQDQWDWQD', 1, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(36, '2022-09-03 00:00:00', '21.23', 'ADAWDAWD', 5, 'receita', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(37, '2022-09-03 00:00:00', '3212.35', 'QWEQWEQWE', 5, 'receita', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(38, '2022-09-03 00:00:00', '90.00', 'QWEQWEQWE', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(39, '2022-09-03 00:00:00', '3212.35', 'QWQWEQWEQWE', 5, 'receita', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(40, '2022-09-03 00:00:00', '21.23', 'QWEQWEQWE', 5, 'receita', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(41, '2022-09-03 00:00:00', '21.23', 'QWEQWEQD', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(42, '2022-09-03 00:00:00', '90.00', 'EQWEQWEQWE', 5, 'receita', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(43, '2022-09-03 00:00:00', '21.23', 'WQEQWEQWEWE', 5, 'receita', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(44, '2022-09-03 00:00:00', '3212.35', 'WQEQWEQWEQWE', 5, 'receita', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(45, '2022-09-03 00:00:00', '21.23', 'QWEQWEQWEQWE', 5, 'receita', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(46, '2022-09-03 00:00:00', '90.00', 'QWEQWEQWE', 5, 'receita', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(47, '2022-09-10 00:00:00', '200.00', 'dawdadw', 4, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(48, '2022-09-10 00:00:00', '40.00', '4 Pasteis', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(49, '2022-09-10 00:00:00', '1500.00', 'Cinema e etc.', 2, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(50, '2022-09-10 00:00:00', '80.00', 'Frutas', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 3),
(51, '2022-09-15 00:00:00', '1500.00', 'Compras exemplo', 2, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(52, '2022-09-19 00:00:00', '500.00', 'Teste', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(53, '2022-09-19 00:00:00', '500.00', 'sadwadwda', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 6, 1, 1),
(106, '2022-09-25 00:00:00', '300.00', 'PAssado', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(107, '2022-09-25 00:00:00', '30.00', 'qqweqweqweqweqwe', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(109, '2022-09-25 00:00:00', '150.00', 'aaa', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 7, 1, 1),
(110, '2022-09-26 00:00:00', '65.00', 'Hoje', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(111, '2022-09-27 00:00:00', '60.00', 'kinkn', 9, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(112, '2022-10-02 00:00:00', '20.00', 'Cachorro quente', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(113, '2022-10-02 00:00:00', '300.00', 'sada', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(114, '2022-10-05 00:00:00', '60.00', 'Compras para casa', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(115, '2022-10-05 00:00:00', '20.00', 'ddwdwdwd', 1, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(116, '2022-10-06 00:00:00', '65.00', 'dwdwd', 4, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(117, '2022-10-06 00:00:00', '20.00', 'dwdqwd', 2, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(118, '2022-10-06 00:00:00', '150.00', 'dwdwdwd', 8, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(119, '2022-10-06 00:00:00', '30.00', 'Pastel', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(121, '2022-10-07 00:00:00', '870.00', 'dwdwdw', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(122, '2022-10-08 00:00:00', '230.00', 'dwdwdw', 1, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(123, '2022-10-01 00:00:00', '547.20', 'dwdwdwd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(127, '2022-10-08 00:00:00', '58.00', 'dwdwd', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(130, '2022-01-11 00:00:00', '3500.00', 'dwdwd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(131, '2022-01-11 00:00:00', '1500.00', 'dwdwd', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(132, '2022-10-12 00:00:00', '1.21', 'wdqdw', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 8, 1, 1),
(133, '2022-10-12 00:00:00', '1.21', 'dwdwd', 10, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(134, '2022-10-12 00:00:00', '1.22', 'dqw', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(135, '2022-10-12 00:00:00', '132.13', 'dqd', 6, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(136, '2022-10-12 00:00:00', '2312.31', 'fefef', 7, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(137, '2022-10-15 00:00:00', '100.00', 'Compras', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 4),
(138, '2022-10-15 00:00:00', '50.00', 'Dividêndos teste', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 4),
(140, '2022-10-15 00:00:00', '300.00', 'dwdwd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 4),
(141, '2022-10-16 14:44:32', '120.00', 'dwdwd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(142, '2022-10-16 14:51:17', '60.00', '21dwqdqwd', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 9, 1, 1),
(143, '2022-10-27 14:26:52', '30.00', 'fwefwef', 1, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(144, '2022-10-17 16:19:49', '13.00', 'qdqwd', 12, 'despesa', 0, 'fechado', NULL, NULL, NULL, 2, 6),
(145, '2022-10-18 13:11:53', '3000.00', 'dwdwd', 1, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(148, '2022-10-25 16:13:54', '156.00', 'Dividêndo PETR4', 7, 'receita', 1, 'pendente', NULL, 1, NULL, 1, 2),
(149, '2022-09-25 11:52:46', '30.00', 'qdqwd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(150, '2022-07-25 11:55:47', '50.00', 'qwdqwd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(151, '2022-10-29 14:41:56', '32.00', 'wqdqwd', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 10, 1, 1),
(152, '2022-10-31 12:49:50', '30.00', 'dwqdw', 1, 'despesa', 0, 'pendente', NULL, NULL, NULL, 1, 1),
(154, '2022-11-25 13:26:20', '156.00', 'Dividêndo PETR4', 7, 'receita', 1, 'fechado', NULL, 1, NULL, 1, 2),
(155, '2022-11-05 14:13:52', '600.00', 'Carrefuor', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(156, '2022-11-05 14:45:55', '60.00', 'Credito para celular', 4, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(157, '2022-11-05 15:27:08', '1000.20', 'dqwqd', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(158, '2022-11-05 15:34:48', '6000.00', 'dqwdqwd', 6, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(159, '2022-11-06 16:29:28', '35.20', 'wqdqwd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(160, '2022-11-07 16:33:29', '36.00', 'dqwdqwd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(161, '2022-11-08 11:31:59', '500.00', 'Investimento PETR4', 23, 'receita', 0, 'fechado', NULL, NULL, NULL, 4, 8),
(162, '2022-11-08 11:32:37', '30.00', '3 pastéis', 19, 'despesa', 0, 'fechado', NULL, NULL, NULL, 4, 8),
(163, '2022-11-08 11:38:29', '70.00', 'Teste', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 11, 4, 8),
(164, '2022-11-08 11:42:57', '60.00', 'dedwwdw', 19, 'despesa', 0, 'fechado', NULL, NULL, NULL, 4, 8),
(165, '2022-11-08 11:45:39', '60.00', 'dqwdwd', 22, 'receita', 0, 'fechado', NULL, NULL, NULL, 4, 8),
(166, '2022-10-08 11:47:25', '300.00', 'ewfwefewf', 19, 'despesa', 0, 'fechado', NULL, NULL, NULL, 4, 8),
(167, '2022-09-08 11:49:31', '150.00', 'dedwwdw', 19, 'despesa', 0, 'fechado', NULL, NULL, NULL, 4, 8),
(168, '2022-11-08 11:52:43', '20.00', 'dqwqwdqwd', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 12, 4, 9),
(169, '2022-11-04 13:25:09', '500.00', 'dwqdqwd', 2, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(172, '2022-11-21 12:56:32', '500.00', 'dwdqd', 25, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(173, '2022-11-21 13:14:53', '300.00', 'qdqwdwd', 9, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(174, '2022-11-21 13:15:13', '70.00', 'dqwd', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(175, '2022-11-21 13:55:11', '20.00', 'wqd', 13, 'despesa', 0, 'fechado', NULL, NULL, NULL, 3, 7),
(176, '2022-10-21 13:55:33', '20.00', 'dwqd', 13, 'despesa', 0, 'fechado', NULL, NULL, NULL, 3, 7),
(177, '2022-09-21 13:55:52', '150.00', 'qwdwqd', 13, 'despesa', 0, 'fechado', NULL, NULL, NULL, 3, 7),
(178, '2022-08-21 15:01:57', '60.00', '30wdqw', 13, 'despesa', 0, 'fechado', NULL, NULL, NULL, 3, 7),
(179, '2022-11-21 15:14:56', '30.00', 'dwqdw', 16, 'receita', 0, 'fechado', NULL, NULL, NULL, 3, 7),
(180, '2022-11-21 15:21:50', '50.00', 'qdqwdwd', 14, 'despesa', 0, 'pendente', NULL, NULL, NULL, 3, 7),
(181, '2022-11-29 12:12:34', '600.00', 'dqwdwd', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(182, '2022-11-29 12:20:28', '250.00', 'dqwdqwd', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(194, '2022-12-07 10:19:47', '300.00', 'Transferência teste', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 13, 1, 1),
(196, '2022-12-08 10:19:09', '200.00', 'Cinema', 2, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(197, '2022-12-05 10:19:27', '30.00', 'Pastel', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(198, '2022-12-19 10:21:23', '20.00', 'Créditos de celular', 4, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(199, '2022-12-06 13:22:15', '21.00', 'dwd', 9, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(201, '2022-12-05 10:19:55', '1300.00', 'Venda de roupas.', 25, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(202, '2022-12-01 10:18:09', '210.00', 'dwd', NULL, 'transferencia', 0, 'pendente', NULL, NULL, 14, 1, 1),
(203, '2022-12-04 10:19:19', '5000.00', 'Salário empresa Teste LTDA.', 5, 'receita', 1, 'fechado', NULL, 2, NULL, 1, 2),
(204, '2022-04-06 14:38:44', '1000.00', 'Venda de roupas.', 25, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 3),
(205, '2022-04-06 14:39:08', '5000.00', 'Salário empresa Teste LTDA.', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(206, '2022-04-20 14:39:40', '1200.00', 'Compras do mês', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(207, '2022-04-15 14:40:00', '200.00', 'Cinema', 2, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(208, '2022-04-10 14:40:21', '50.00', 'Pasteis', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(209, '2022-03-06 15:20:28', '200.15', 'Compras do dia', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(210, '2022-03-06 15:20:37', '200.00', 'Cinema', 2, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(211, '2022-03-06 15:20:57', '200.00', 'Frutas e legumes', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(212, '2022-03-20 15:21:10', '60.00', 'Créditos de celular', 4, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(213, '2022-03-26 15:21:27', '50.00', 'Comprar de itens de natação', 8, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(214, '2022-03-06 15:21:58', '2000.00', 'Dividêndos PETR4', 7, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 3),
(215, '2022-02-20 15:23:44', '5000.00', 'salário', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 3),
(216, '2022-02-06 15:24:28', '1500.00', 'Compras de alimentos do mês', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(217, '2022-02-15 15:25:12', '3000.00', 'Viagem para Minas Gerais', 2, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(218, '2022-02-06 15:25:42', '600.00', 'Compras de frutas.', 3, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(219, '2022-12-25 15:39:50', '156.00', 'Dividêndo PETR4', 7, 'receita', 1, 'fechado', NULL, 1, NULL, 1, 2),
(220, '2022-05-06 16:23:33', '3000.00', 'Compras de móveis para casa', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(221, '2022-05-28 16:24:20', '3252.00', 'Venda de Roupas.', 25, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 3),
(222, '2022-06-06 16:35:22', '6000.00', 'Compras de alimentos do mês', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(223, '2022-06-20 16:35:49', '1250.00', 'Venda de roupas.', 25, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 3),
(224, '2022-07-06 16:45:40', '5000.00', 'Salário', 5, 'receita', 0, 'fechado', NULL, NULL, NULL, 1, 3),
(225, '2022-07-06 16:46:10', '2798.50', 'Compras de alimentos do mês', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(226, '2022-12-15 10:17:52', '250.00', 'Gasto em Água', 29, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 2),
(227, '2022-12-10 10:18:02', '7.50', 'Mensalidade Academia', 2, 'despesa', 0, 'fechado', 1, NULL, NULL, 1, 1),
(228, '2022-12-16 10:20:47', '50.00', 'Mensalidade Netflix', 2, 'despesa', 1, 'fechado', 2, NULL, NULL, 1, 1),
(229, '2022-12-02 10:18:17', '7.50', 'Mensalidade Academia', 2, 'despesa', 0, 'pendente', 1, NULL, NULL, 1, 1),
(230, '2022-12-06 10:18:18', '7.50', 'Mensalidade Academia', 2, 'despesa', 0, 'fechado', 1, NULL, NULL, 1, 1),
(231, '2022-12-03 10:20:18', '5000.00', 'Salário empresa Teste LTDA.', 5, 'receita', 1, 'pendente', NULL, 2, NULL, 1, 2),
(233, '2022-12-09 10:21:38', '5000.00', 'Salário empresa Teste LTDA.', 5, 'receita', 1, 'fechado', NULL, 2, NULL, 1, 2),
(234, '2022-12-23 10:52:25', '50.00', 'Mensalidade Netflix', 2, 'despesa', 1, 'fechado', 2, NULL, NULL, 1, 1),
(235, '2022-12-27 10:52:48', '5000.00', 'Salário empresa Teste LTDA.', 5, 'receita', 1, 'pendente', NULL, 2, NULL, 1, 2),
(236, '2022-12-28 10:36:07', '214.00', 'Compras de utensílios domésticos', 1, 'despesa', 0, 'fechado', NULL, NULL, NULL, 1, 1),
(237, '2022-12-06 10:52:26', '50.00', 'Mensalidade Netflix', 2, 'despesa', 1, 'pendente', 2, NULL, NULL, 1, 1),
(238, '2022-12-08 10:53:02', '5000.00', 'Salário empresa Teste LTDA.', 5, 'receita', 1, 'pendente', NULL, 2, NULL, 1, 2),
(239, '2022-12-11 10:53:17', '5000.00', 'Salário empresa Teste LTDA.', 5, 'receita', 1, 'pendente', NULL, 2, NULL, 1, 2),
(240, '2022-12-06 10:53:18', '5000.00', 'Salário empresa Teste LTDA.', 5, 'receita', 1, 'pendente', NULL, 2, NULL, 1, 2),
(241, '2022-12-27 11:20:09', '500.00', 'Teste', NULL, 'transferencia', 0, 'fechado', NULL, NULL, 15, 1, 1);

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
  `data_transf` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `transferencia`
--

INSERT INTO `transferencia` (`idTransferencia`, `valor`, `descricao`, `id_conta_origem`, `id_conta_destino`, `id_usuario`, `status_transf`, `data_transf`) VALUES
(1, '120.00', 'Tua conta', 1, 3, 1, 'fechado', '2022-08-12 00:00:00'),
(2, '100.00', 'Lorem impsun 2', 4, 1, 1, 'fechado', '2022-08-13 00:00:00'),
(4, '3212.35', '55454545454', 2, 1, 1, 'fechado', '2022-08-05 00:00:00'),
(5, '90.00', 'Para alguma ação', 1, 3, 1, 'fechado', '2022-08-31 00:00:00'),
(6, '500.00', 'sadwadwda', 1, 2, 1, 'fechado', '2022-09-19 00:00:00'),
(7, '150.00', 'aaa', 1, 2, 1, 'fechado', '2022-09-25 00:00:00'),
(8, '1.21', 'wdqdw', 1, 2, 1, 'fechado', '2022-10-12 00:00:00'),
(9, '60.00', '21dwqdqwd', 1, 2, 1, 'fechado', '2022-10-16 14:51:17'),
(10, '32.00', 'wqdqwd', 1, 2, 1, 'fechado', '2022-10-29 14:41:56'),
(11, '70.00', 'Teste', 8, 9, 4, 'fechado', '2022-11-08 11:38:29'),
(12, '20.00', 'dqwqwdqwd', 9, 8, 4, 'fechado', '2022-11-08 11:52:43'),
(13, '300.00', 'Transferência teste', 1, 2, 1, 'fechado', '2022-12-07 10:19:47'),
(14, '210.00', 'dwd', 1, 2, 1, 'aberto', '2022-12-01 10:18:09'),
(15, '500.00', 'Teste', 1, 2, 1, 'fechado', '2022-12-27 11:20:09');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `sobrenome` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data_nasc` date DEFAULT NULL,
  `sexo` enum('masculino','feminino') DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `status_usu` enum('PENDENTE','ABERTO') DEFAULT NULL,
  `token_usuario` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `nome`, `sobrenome`, `email`, `data_nasc`, `sexo`, `senha`, `status_usu`, `token_usuario`) VALUES
(1, 'Weslley', 'Simões', 'weslley@teste.com', '1998-04-19', 'masculino', '$2y$10$5FQfuSo2cSfvH5cvsOszneYQr0hYp87VHWY2EBZ7PLGvtEWhhRb9m', 'ABERTO', NULL),
(2, 'jose', 'pires', 'jose@pires.com', '1998-04-19', 'masculino', '$2y$10$jNJPXj9wIgE.42B7FPlikOu2IDzL4MahwgazAy089n7AbMO2Rh1K6', 'ABERTO', NULL),
(3, 'weslley', 'simões', 'weslley_silvas@hotmail.com', '1998-04-19', 'masculino', '$2y$10$OiIyzzUJXTaUCvYVmW9D9.N2qeC1DSK8.Ym8f0aV4Itt7OS/7VY.G', 'ABERTO', NULL),
(4, 'Maria', 'Silva', 'mari.silva@teste.com', '1995-02-15', 'feminino', '$2y$10$oe91hg1G8CL/f7524oJyDubXmbXAagAttlyKd5CFZPN9KwuvcIrTC', 'ABERTO', NULL);

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
-- Índices para tabela `plancategoria`
--
ALTER TABLE `plancategoria`
  ADD PRIMARY KEY (`idPCat`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_planejamento` (`id_planejamento`);

--
-- Índices para tabela `planejamento`
--
ALTER TABLE `planejamento`
  ADD PRIMARY KEY (`idPlan`),
  ADD KEY `id_usuario` (`id_usuario`);

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
-- Limitadores para a tabela `plancategoria`
--
ALTER TABLE `plancategoria`
  ADD CONSTRAINT `plancategoria_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`idCategoria`),
  ADD CONSTRAINT `plancategoria_ibfk_2` FOREIGN KEY (`id_planejamento`) REFERENCES `planejamento` (`idPlan`);

--
-- Limitadores para a tabela `planejamento`
--
ALTER TABLE `planejamento`
  ADD CONSTRAINT `planejamento_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`idUsuario`);

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

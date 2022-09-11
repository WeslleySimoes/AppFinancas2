
CREATE TABLE planejamento
(
    idPlan         INT PRIMARY KEY NOT NULL,
    valor          DECIMAL(10,2) NOT NULL,           -- Valor ou Renda Mensal
    porcentagem    INT DEFAULT NULL,
    data_inicio    DATE NOT NULL,
    data_fim       DATE NOT NULL,
    tipo           ENUM('mensal','personalizado'),
    id_usuario     INT NOT NULL,
    FOREIGN KEY(id_usuario) REFERENCES usuario(idUsuario)
);

CREATE TABLE planCategoria
(
    idPCat          INT PRIMARY KEY NOT NULL,
    valorMeta       DECIMAL(10,2) NOT NULL,
    id_categoria    INT NOT NULL,
    id_planejamento INT NOT NULL,
    FOREIGN KEY(id_categoria) REFERENCES categoria(idCategoria),
    FOREIGN KEY(id_planejamento) REFERENCES planejamento(idPlan)
);


-- INSERÇÃO DE PLANEJAMENTO
INSERT INTO planejamento VALUES(1,3000.00,50,'2022-09-01','2022-09-30','mensal',1);
INSERT INTO planCategoria VALUES(1,300.00,2,1);
INSERT INTO planCategoria VALUES(2,500.00,3,1);
INSERT INTO planCategoria VALUES(3,200.00,4,1);
INSERT INTO planCategoria VALUES(4,500.00,1,1);


-- MENSAL -- 
-- 1 - 3000 - 50% - 01/09/22 - 30/09/22 - 'mensal' - 1    --1500
--   1 - 300.00 - 5 - 1
--   2 - 500.00 - 2 - 1
--   3 - 200.00 - 3 - 1
--   4 - 500.00 - 4 - 1

-- PERSONALIZADO -- 

-- 2 - 5000 - 'NULL' - 01/09/22 - 30/10/22 - 'personalizado' - 1    --1500
--   5 - 1000.00 - 5 - 1
--   6 - 900.00  - 2 - 1
--   7 - 2000.00 - 3 - 1
--   8 - 1100.00 - 4 - 1




-- Mostra o planejamento do mês atual caso ele exista, senão mostraremos a opção de cadastro
SELECT COUNT(*) AS total FROM planejamento WHERE id_usuario = 1 AND tipo = 'mensal' AND MONTH(data_fim) = MONTH(CURDATE()) AND YEAR(data_fim) = YEAR(CURDATE());


-- Lista todos os planejamento personalizados onde a data_fim seja maior que a data atual
SELECT * FROM planejamento WHERE id_usuario = 1 AND tipo = 'personalizado' AND MONTH(data_fim) > MONTH(CURDATE()) AND YEAR(data_fim) >= YEAR(CURDATE());

SELECT SUM(valor) as totalGasto FROM transacao WHERE id_categoria in (1,4,3,2) AND MONTH(data_trans) = MONTH(CURDATE()) AND YEAR(data_trans) = YEAR(CURDATE())
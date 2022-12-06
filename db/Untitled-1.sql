

SELECT c.descricao,SUM(t.valor) FROM transacao as t INNER JOIN conta as c ON t.id_conta = c.idConta WHERE t.tipo = 'despesa' AND t.id_usuario = 1 AND DATE(t.data_trans) BETWEEN DATE('2022-09-01') AND DATE('2022-09-26') AND t.status_trans = 'fechado' GROUP BY c.descricao;


SELECT t.id_categoria,c.nome,SUM(valor) FROM transacao as t INNER JOIN categoria as c ON t.id_categoria = c.idCategoria WHERE t.tipo = 'despesa' AND t.id_usuario = 1 AND MONTH(t.data_trans) = MONTH('2022-09-01') AND YEAR(t.data_trans) = YEAR('2022-09-01') GROUP BY t.id_categoria


-- SALDO POR CONTA
SELECT (SUM(montante) + (SELECT SUM(valor) FROM transacao WHERE id_usuario = 1 AND tipo = 'receita') - (SELECT SUM(valor) FROM transacao WHERE id_usuario = 1 AND tipo = 'despesa')) total FROM conta WHERE id_usuario = 1


SELECT SUM(valor) as total FROM transacao WHERE id_usuario = 1 AND tipo = 'receita' GROUP BY id_conta;

SELECT SUM(valor) as total FROM transacao WHERE id_usuario = 1 AND tipo = 'despesa' GROUP BY id_conta;


-- Despesas por mes
SELECT data_trans,SUM(valor) as despesa FROM transacao WHERE id_usuario = 1 AND tipo = 'despesa' AND YEAR(data_trans) = YEAR(CURDATE()) GROUP BY DATE_FORMAT(data_trans, '%Y%m');

-- Receitas por mes
SELECT data_trans,SUM(valor) as despesa FROM transacao WHERE id_usuario = 1 AND tipo = 'receita' AND YEAR(data_trans) = YEAR(CURDATE()) GROUP BY DATE_FORMAT(data_trans, '%Y%m');



--dqdwqdwqd
SELECT * FROM planejamento WHERE id_usuario = 1 AND status_plan = 'ativo' AND tipo = 'personalizado' AND DATE(data_fim) < DATE(CURDATE());

-- altera o status do planejamento
UPDATE planejamento SET status_plan = 'expirado' WHERE id_usuario = 1 AND status_plan = 'ativo' AND tipo = 'personalizado' AND DATE(data_fim) < DATE(CURDATE());




-----------------------------

SELECT (IFNULL(montante,0) + (SELECT IFNULL(SUM(valor),0) FROM transacao WHERE id_usuario = 1 AND tipo = 'receita' AND id_conta = 4) - (SELECT IFNULL(SUM(valor),0) FROM transacao WHERE id_usuario = 1 AND tipo = 'despesa' AND id_conta = 4)) total FROM conta WHERE id_usuario = 1 AND idConta = 4;
-
SELECT IFNULL(SUM(valor),0) AS total FROM transferencia WHERE id_usuario = 1 AND id_conta_origem = 4;
+
SELECT IFNULL(SUM(valor),0) AS total FROM transferencia WHERE id_usuario = 1 AND id_conta_destino = 4;




//


SELECT (IFNULL(montante,0) + (SELECT IFNULL(SUM(valor),0) FROM transacao WHERE id_usuario = 1 AND tipo = 'receita' AND id_conta = 4 AND DATE(data_trans) < DATE('2022-09-30')) - (SELECT IFNULL(SUM(valor),0) FROM transacao WHERE id_usuario = 1 AND tipo = 'despesa' AND id_conta = 4 AND DATE(data_trans) < DATE('2022-09-30'))) total FROM conta WHERE id_usuario = 1 AND idConta = 4;

----------------------------------------------------------------


mysql> select * from sales
       where order_date> now() - INTERVAL 12 month;



https://ubiq.co/database-blog/get-last-12-months-data-mysql/#:~:text=How%20to%20Get%20Last%2012%20Months%20Sales%20Data%20in%20SQL&text=mysql%3E%20select%20*%20from%20sales%20where,12%20months%20before%20present%20datetime


/* Obtendo total de despesas dos ultimos 12 meses, agrupadas por Mês/ano    */
SELECT data_trans, SUM(valor) FROM transacao 
WHERE MONTH(data_trans) <= MONTH(CURDATE()) AND YEAR(data_trans) <= YEAR(CURDATE()) AND CURDATE() - INTERVAL 12 month AND tipo = 'despesa' AND id_usuario = 1
GROUP BY MONTH(data_trans),YEAR(data_trans);


/*TOTAL DE DESPESA POR CATEGORIA NO MÊS ATUAL*/
SELECT id_categoria,SUM(valor) FROM transacao WHERE tipo = 'despesa' AND id_usuario = 1 AND MONTH(data_trans) = MONTH(CURDATE()) AND YEAR(data_trans) = YEAR(CURDATE()) GROUP BY id_categoria;
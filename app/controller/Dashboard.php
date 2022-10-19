<?php 

namespace app\controller;

use app\model\Transaction;
use app\model\entity\Transacao;
use app\model\entity\DashboardModel;
use app\session\Usuario as UsuarioSession;

class Dashboard extends BaseController
{
    public function index()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome')
        ];

        Transacao::inseriDespesasFixasMesAtual();
        Transacao::inseriReceitasFixasMesAtual();
        
        try {
            Transaction::open('db');

            $totalReceitas = DashboardModel::getTotalRD('receita');
            $totalDespesas = DashboardModel::getTotalRD('despesa');

            $contas = DashboardModel::saldoTotalContas();

            $ultimasTransacoes = DashboardModel::getLastTransacoes();

            $despesasCategorias = DashboardModel::drPorCategoria('despesa');
            $receitasCategorias = DashboardModel::drPorCategoria('receita');

            $depMensal = DashboardModel::despRespMensal('despesa');
            $recMensal = DashboardModel::despRespMensal('receita');
            
            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }
 
        //Gráfico Receitas x Despesas
        $dados['depMensal'] = $depMensal;
        $dados['recMensal'] = $recMensal;

        //DESPESAS
        $dados['arr_total_despesas'] = implode(',',array_column($despesasCategorias,'total'));
        $dados['arr_nomeCate_despesas'] = "'".implode("','",array_column($despesasCategorias,'nome'))."'";
        $dados['cores_despesas'] = "'".implode("','",array_column($despesasCategorias,'cor_cate'))."'";

        //RECEITAS
        $dados['arr_total_receitas'] = implode(',',array_column($receitasCategorias,'total'));
        $dados['arr_nomeCate_receitas'] = "'".implode("','",array_column($receitasCategorias,'nome'))."'";
        $dados['cores_receitas'] = "'".implode("','",array_column($receitasCategorias,'cor_cate'))."'";

        $dados['totalReceitas'] = $totalReceitas;
        $dados['totalDespesas'] = $totalDespesas;
        $dados['totalSaldoContas'] = $contas;
        $dados['ultimasTransacoes'] = $ultimasTransacoes;


        $this->view([
            'templates/header',
            'dashboard',
            'templates/footer'
        ],$dados);
    }

    public function deslogar()
    {
        UsuarioSession::destroy();

        header('location: '.HOME_URL.'/');
        exit;
    }

}
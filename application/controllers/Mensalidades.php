<?php
defined('BASEPATH') or exit('Ação não permitida');


class Mensalidades extends CI_Controller
{

	/*=============================================
	 =            Verificar se o Mensalistas está logado    =
	 =============================================*/

	public function __construct()
	{

		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			redirect('login');
		}

		$this->load->model('mensalidades_model');


	}


	/*=====  End of Section comment block  ======*/


	/*=============================================
	=            tela de listagem           =
	=============================================*/
	public function index()
	{
		$data = array(
			'titulo' => 'Mensalidade registradas',

//			Chamando o model onde tem o JOIN
			'mensalidades' => $this->mensalidades_model->get_all(),


			'styles' => array(
				'assets/bundles/datatables/datatables.min.css',
				'assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css',
			),

			'scripts' => array(
				'assets/bundles/datatables/datatables.min.js',
				'assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js',
				'assets/bundles/jquery-ui/jquery-ui.min.js',
				'assets/js/page/datatables.js',

			),

		);

//		echo '<pre>';
//		print_r($data['mensalidades']);
//		exit();

		$this->load->view('layout/header', $data);
		$this->load->view('mensalidades/index');
		$this->load->view('layout/footer');
	}

	public function core($mensalidade_id = NULL)
	{

		if (!$mensalidade_id) {

//			Cadastrando

		} else {

			if (!$this->core_model->get_by_id('mensalidades', array('mensalidade_id' => $mensalidade_id))) {
				$this->session->set_flashdata('error', 'Mensalidade não encontrada');
				redirect($this->router->fetch_class());
			} else {

//			Se ele existe trago toda minha view

				$data = array(
					'titulo' => 'Editar mensalidade',
					'texto_modal' => 'Os dados estão corretos?</br></br> Depois de guardar só será possível alterar a "Mensalidade"',

					'styles' => array(
						'assets/plugin/select2/dist/css/select2.min.css',
					),

					'scripts' => array(
						'assets/plugin/select2/dist/js/select2.min.js',
						'assets/js/mensalidades/custom.js',
					),

//					Preciso enviar para o meu controlador as minhas precificações

					'precificacoes' => $this->core_model->get_all('precificacoes', array('precificacao_ativa' => 1)),
					'mensalistas' => $this->core_model->get_all('mensalistas', array('mensalista_ativo' => 1)),
					'mensalidades' => $this->core_model->get_by_id('mensalidades', array('mensalidade_id' => $mensalidade_id)),
				);

//			echo '<pre>';
//			print_r($data['mensalidades']);
//			exit();

				$this->load->view('layout/header', $data);
				$this->load->view('mensalidades/core');
				$this->load->view('layout/footer');
			}
		}
	}
}

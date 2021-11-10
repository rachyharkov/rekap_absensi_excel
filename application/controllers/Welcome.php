<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Welcome extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->model('Modelnyak');
    }

	public function index()
	{
		$datamaster = $this->Modelnyak->deteksi_masterdata();

		if (!$datamaster) {
			$this->load->view('create_absensidata_form');
		}
		else 
		{
			$ano = array(
				'data_masterdata' => $this->Modelnyak->get_all()
			);

			$this->load->view('list_masterdata',$ano);
		}
	}

	public function create()
	{
		$this->load->view('create_absensidata_form');
	}

	public function create_action()
	{
		$kolomsnya = $this->input->post('title_kolom');
		$nama_absensi = $this->input->post('nama_absensi');
		$jumlah_event_hari = $this->input->post('jumlah_event_hari');

		$reference_data = $this->input->post('reference_data');

		$generatecode = $this->Modelnyak->generate_code_masterdata();

		$this->load->dbforge();

		$this->db->query('use db_datafilterinserter');

		$fields = array(
		  	'id' => array(
			    'type' => 'INT',
			    'constraint' => 9,
			    'unsigned' => TRUE,
			    'auto_increment' => TRUE
		  	),
		  	'data_reference' => array(
		  		'type' => 'VARCHAR',
			    'constraint' => 200,
			    'null' => FALSE
		  	)
		);
			
		for ($i=1; $i <= intval($jumlah_event_hari); $i++) { 
			
			$ke = $i;

			$fields['day'.$ke] =  array(
			    'type' => 'VARCHAR',
			    'constraint' => 200,
			    'null' => FALSE
			);
		}

		// echo '<pre>';
		// print_r($fields);
		// echo '</pre>';

		
		$this->dbforge->add_field($fields);

		// define primary key
		$this->dbforge->add_key('id', TRUE);

		// create table
		$this->dbforge->create_table($generatecode);


		foreach ($reference_data as $value) {
			$dt = array(
				'data_reference' => $value
			);
			// print_r($dt);
			$this->db->insert($generatecode, $dt);
		}

		$datamasterdata = array(
			'kd_masterdata' => $generatecode,
			'nama_masterdata' => $nama_absensi,
			'created_at' => date('Y-m-d H:m:s')
		);

		$this->Modelnyak->insert_masterdata($datamasterdata);

		redirect('Welcome');
	}

	public function read_masterdata($table)
	{
		$data = array(
			'id_masterdata' => $table,
			'masterdata' => $this->Modelnyak->read_table($table),
			'columnsmasterdata' => $this->db->field_data($table)
		);

		$this->load->view('read_masterdata', $data);
	}

	public function preview_excel_data()
	{
		$fileName = $_FILES['file_excel']['name'];
         
        $config['upload_path'] = './temp_doc/'; //path upload
        $config['file_name'] = $fileName;  // nama file
        $config['allowed_types'] = 'xls|xlsx|csv'; //tipe file yang diperbolehkan
        $config['max_size'] = 10000; // maksimal sizze
 
        $this->load->library('upload'); //meload librari upload
        $this->upload->initialize($config);
          
        if(!$this->upload->do_upload('file_excel')){
            echo $this->upload->display_errors();
            exit();
        }

        $inputFileName = './temp_doc/'.$fileName;
 
        try {

            $inputFileType = IOFactory::identify($inputFileName);
            $objReader = IOFactory::createReader($inputFileType);
            $objExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $sheet = $objExcel->getActiveSheet()->toArray(null, true, true, true);
        $sh = $objExcel->getActiveSheet();

        $arrdt = [];

        $numrow = 1;
        $kosong = 0;

        echo '<ul>';

        for ($i=2; $i <= count($sheet); $i++) { 
        	$juduldatautama = $sh->getCellByColumnAndRow(1,1)->getValue();

        	$datautama1 = $sheet[$i]['A'];

        	if ($datautama1 == "") continue;

        	if ($numrow > 1) {
        		if ($datautama1 == "") {
        			$kosong++;
        		}
        	}

        	$datakomplit = array(
        		$juduldatautama => $datautama1
        	);

        	echo '<li>'.$datautama1.'<input type="hidden" name="reference_data[]" value="'.$datautama1.'"/></li>';

        	$numrow++;
        }

        echo '</ul>';
        //print_r($arraysoaldata);

        unlink($inputFileName);
	}

	public function upload($type,$table)
	{
		$fileName = $_FILES['excel-datautama']['name'];
         
        $config['upload_path'] = './temp_doc/'; //path upload
        $config['file_name'] = $fileName;  // nama file
        $config['allowed_types'] = 'xls|xlsx|csv'; //tipe file yang diperbolehkan
        $config['max_size'] = 10000; // maksimal sizze
 
        $this->load->library('upload'); //meload librari upload
        $this->upload->initialize($config);
          
        if(! $this->upload->do_upload('excel-datautama') ){
            echo $this->upload->display_errors();
            exit();
        }

        $inputFileName = './temp_doc/'.$fileName;
 
        try {

            $inputFileType = IOFactory::identify($inputFileName);
            $objReader = IOFactory::createReader($inputFileType);
            $objExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $sheet = $objExcel->getActiveSheet()->toArray(null, true, true, true);
        $sh = $objExcel->getActiveSheet();

        $arrdt = [];

        $numrow = 1;
        $kosong = 0;

        for ($i=2; $i <= count($sheet); $i++) { 

        	$datautama1 = $sheet[$i]['A'];

        	//tune here....
        	$datatoinsert = $sheet[$i]['B'];

        	if ($datautama1 == "") continue;

        	if ($numrow > 1) {
        		if ($datautama1 == "") {
        			$kosong++;
        		}
        	}

        	$dete = array(
        		$type => $datatoinsert
        	);

        	$this->Modelnyak->update_absensi($datautama1, $dete, $table);

        	// array_push($arrdt, $datakomplit);

        	$numrow++;
        }
        //print_r($arraysoaldata);

        // print_r($arrdt);
        // $this->Modelnyak->import_datautama($table,$arrdt);
        unlink($inputFileName);

        $this->read_masterdata($table);
	}
}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelnyak extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $this->db->order_by('id', 'DESC');
        return $this->db->get('tbl_masterdata')->result();
    }

    function deteksi_masterdata()
    {
        return $this->db->get('tbl_masterdata')->result();
    }

    function generate_code_masterdata()
    {
        $q = $this->db->query("SELECT MAX(RIGHT(kd_masterdata,4)) AS kd_max FROM tbl_masterdata WHERE DATE(created_at)=CURDATE()");
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }else{
            $kd = "0001";
        }
        date_default_timezone_set('Asia/Jakarta');
        return 'M'.date('dmy').$kd;
    }

    // insert data
    function insert_masterdata($data)
    {
        $this->db->insert('tbl_masterdata', $data);
    }

    function read_table($table)
    {
        return $this->db->get($table)->result();
    }

    // insert data
    function insert_to($table,$data)
    {
        $this->db->insert($table, $data);
    }

    function update_absensi($id, $data, $table)
    {
        $this->db->where('data_reference', $id);
        $this->db->update($table, $data);
    }
}

?>
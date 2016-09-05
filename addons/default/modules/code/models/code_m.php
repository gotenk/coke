<?php defined('BASEPATH') or exit('No direct script access allowed');

class Code_m extends MY_Model
{
    public function insertData($table, $data)
    {
        $this->db->insert($table, $data);

        return $this->db->insert_id();
    }

    public function insertIgnore($table, $data)
    {
        $insert_query = $this->db->insert_string($table, $data);
        $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);

        return $this->db->query($insert_query);
    }

    public function getData($table)
    {
        return $this->db->get($table)->result();
    }

    public function getSingleData($table, $field, $id)
    {
        $this->db->where($field, $id);

        return $this->db->get($table)->row();
    }

    public function updateData($table, $data, $field, $id)
    {
        return $this->db->update($table, $data, array($field => $id));
    }

    public function deleteData($table, $field, $id)
    {
        return $this->db->delete($table, array($field => $id));
    }

    public function getIndomaretCodeList($parameter, $pagination = null)
    {
        $this->db
            ->select('ic.code_id, ic.code, ic.is_used, ic.date_used, ic.date_created, p.display_name')
            ->from('indomaret_code ic')
            ->join('profiles p', 'p.user_id = ic.user_id', 'left');

        if (isset($parameter['is_used'])) {
            if ($parameter['is_used'] == 'yes') {
                $this->db->where('ic.is_used', 1);
            }

            if ($parameter['is_used'] == 'no') {
                $this->db->where('ic.is_used', 0);
            }

            if ($parameter['is_used'] == 'all') {
                $this->db->where('ic.is_used !=', 2);
            }
        }

        if (isset($parameter['code'])) {
            $this->db->like('ic.code', $parameter['code']);
        }

        if (isset($pagination)) {
            $this->db->limit($pagination['limit'], $pagination['offset']);
        }

        return $this->db->get();
    }

    public function getAlfamartCodeList($parameter, $pagination = null)
    {
        $this->db
            ->select('ac.*, p.display_name')
            ->from('alfamart_code ac')
            ->join('profiles p', 'p.user_id = ac.user_id', 'left');

        if (isset($parameter['unique_code'])) {
            $this->db->like('ac.unique_code', $parameter['unique_code']);
        }

        if (isset($parameter['transaction_code'])) {
            $this->db->like('ac.transaction_code', $parameter['transaction_code']);
        }

        if (isset($pagination)) {
            $this->db->limit($pagination['limit'], $pagination['offset']);
        }

        return $this->db->get();
    }
}
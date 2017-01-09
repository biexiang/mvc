<?php

class testModel extends Model{
    public function test(){
        $res = $this->db->queryString("select * from emp where eid = :eid",array(":eid"=> 12));
        print_r($res);
        echo "<br/>";
        //$this->db->insert("emp",array("name"=>"sumengliang","age"=>20,"gender"=>"male"));
    }
}



?>
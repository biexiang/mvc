<?php

class Db{
    protected $arrConfig;
    protected $conn = null;

    /**
     * @param $arrConfig 数据库配置信息
     */
    public function init($arrConfig){
        $this->arrConfig = $arrConfig;
        $dsn = "mysql:dbname=" . $this->arrConfig['db'] . ";host=" . $this->arrConfig['host'];
        $opt = array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8");
        try{
            $this->conn = new PDO($dsn,$this->arrConfig['usr'],$this->arrConfig['passwd'],$opt);
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    /**
     * @param $sql 如果是有条件判断 WHERE calories < :calories AND colour = :colour'
     * @param array $where  execute(array(':calories' => $calories, ':colour' => $colour))
     */
    public function queryString($sql,$where = array()){
        try{
            $stmt = $this->conn->prepare($sql);

            if(empty($where)){
                $stmt->execute();
            }else{
                $stmt->execute($where);
            }

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll();
            }else{
                $err = $stmt->errorInfo();
                print_r($err);return;
            }

        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }


}


?>
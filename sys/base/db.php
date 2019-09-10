<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/10
 * Time: 11:25
 */

class db
{
    private static $instance;  //static可以保存值不丢失
    private $dbConnect;
    private $dbConfig = array(
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => 'root',
        'database' => 'infogo_oa',
    );//保存数据库的配置信息

    //使用private防止用户new
    private function __construct(){
        $this->dbConnect = mysqli_connect($this->dbConfig['host'],
            $this->dbConfig['user'],$this->dbConfig['password'], $this->dbConfig['database']);

        if(!$this->dbConnect){
            throw new Exception("mysql connect error".mysql_error());
            //die("mysql connect error".mysql_error());
        }

        mysqli_set_charset($this->dbConnect, 'utf8');
    }

    //重写clone防止用户进行clone
    private function __clone(){}

    //由类的自身来进行实例化
    public static function getInstance(){
        if(!(self::$instance instanceof self)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect(){
    }

    //系统级的查询方法，设置 为public ，如果在外部想要调用的时候，想自定sql句的候，可以直接query
    //查询应的结果，这个仅供读取使用
    public function query($sql)
    {

        $result = mysqli_query($this->dbConnect,$sql);
        if ($result) {
            $data = [];
            while($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }

    }

    public function prepare($sql)
    {
        // 创建预处理语句
        $stmt=mysqli_stmt_init($this->dbConnect);

        if (mysqli_stmt_prepare($stmt,$sql)) {
            return $stmt;
        } else {
            return false;
        }
    }
}
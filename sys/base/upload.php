<?php

class upload
{
    //上传到哪个目录
    protected $path = './public/upload/';
    //准许的MIME
    protected $allow_mime = ['image/png','image/jpg','image/jpeg','image/pjpeg','image/gif'];
    //准许的后缀
    protected $allow_subfix = ['jpg','png','jpeg','gif'];
    //准许的大小
    protected $allow_size = 1048576;
    //错误码
    protected $error_no;

    public function __construct()
    {
    }

    //成员方法上传方法
    public function uploadFile($field)
    {

        //检测路径用户是否定义过，如果没有定义失败
        if (!file_exists($this->path)) {
            $this->error_no = -1;
            return $this->error();
        }

        //目录权限检测
        if (!$this->checkPath()) {
            $this->error_no = -2;
            return $this->error();
        }


        //获得$_FILES当中五个基本信息
        $name = $_FILES[$field]['name'];
        $size = $_FILES[$field]['size'];
        $type = $_FILES[$field]['type'];
        $tmp_name = $_FILES[$field]['tmp_name'];
        $error = $_FILES[$field]['error'];

        $info = pathinfo($name);
        $subfix = $info['extension'];

        //检测MIME是否合法，检测后缀是否合法，检测文件大小是否超过了自定义的大小
        if (!$this->checkMime($type) || !$this->checkSubfix($subfix) || !$this->checkSize($size)) {
            return $this->error();
        }

        //上传文件
        $res = $this->move($name, $tmp_name);

        if ($res) {
            return $this->success($name);
        } else {
            return $this->error();
        }
    }

    protected function checkPath()
    {
        //检测路径是否是目录，如果不存在创建
        if (!is_dir($this->path)) {
            return mkdir($this->path,0755,true);
        }
        //检测路径是否可写，如果不写写更改权限
        if (!is_writeable($this->path) ||  !is_readable($this->path)) {
            return chmod($this->path,0755);
        }
        return true;
    }

    protected function checkMime($mime)
    {
        if (in_array($mime,$this->allow_mime)) {

            return true;
        } else {

            $this->error_no = -3;
            return false;
        }

    }

    protected function checkSubFix($subfix)
    {

        if (in_array($subfix,$this->allow_subfix)) {

            return true;
        } else {

            $this->error_no = -4;
            return false;
        }
    }

    protected function checkSize($size)
    {
        if ($size > $this->allow_size) {

            $this->error_no = -5;
            return false;
        } else {

            return true;
        }

    }

    protected function move($name, $tmp_name)
    {
        if (!is_uploaded_file($tmp_name)) {
            $this->error_no = -6;
            return false;
        }

        if (move_uploaded_file($tmp_name,$this->path.$name)) {
            return true;
        } else {
            $this->error_no = -7;
            return false;
        }

    }

    protected function success($name)
    {
        return array(
            'code' => 0,
            'path' => $this->path . $name
        );
    }

    protected function error()
    {
        return array(
            'code' => $this->error_no,
            'msg' => $this->getErrorInfo()
        );
    }

    protected function getErrorInfo()
    {
        switch ($this->error_no) {
            case -1:
                $str = '上传路径不存在';
                break;
            case -2:
                $str = '没有权限';
                break;
            case -3:
            case -4:
                $str = '类型或后缀不准许';
                break;
            case -5:
                $str = '超过了自定义的大小';
                break;
            case -6:
                $str = '不是上传文件';
                break;
            case -7:
                $str = '移动上传文件失败';
                break;

        }
        return $str;
    }
}

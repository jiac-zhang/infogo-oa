<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/10
 * Time: 15:19
 */

class page
{
//url
    protected $url;
    //总页数
    protected $pageCount;
    //总条数
    protected $total;
    //每页显示数
    protected $num;
    //当前页
    protected $page;


    //初使化成员属性
    public function __construct($total, $num = 5)
    {
        //总条数
        $this->total = ($total > 0) ? (int) $total : 1;
        //每页显示数
        $this->num = $num;
        //总页数
        $this->pageCount = $this->getPageCount();
        //当前页
        $this->page = $this->getCurrentPage();
        //url
        $this->url = $this->getUrl();
    }

    //一次性返回所有的分页信息
    public function rendor()
    {
        return [
            'first' => $this->first(),
            'next' => $this->next(),
            'prev' => $this->prev(),
            'end' => $this->end(),
        ];
    }

    //limit方法，在未来分页数据查询时候，直接返回对应的limit 0,5 这样的字符串
    public function limit()
    {
        $offset = ($this->page - 1) * $this->num;

        $str = $offset.','.$this->num;

        return $str;
    }


    protected function end()
    {
        return $this->setQueryString('page='.$this->pageCount);
    }


    //上一页
    protected function  prev()
    {

        $page = ($this->page <=1) ? 1 : ($this->page -1);

        return $this->setQueryString('page='.$page);

    }

    //下一页
    protected function next()
    {

        $page = ($this->page >= $this->pageCount) ?  $this->pageCount : ($this->page + 1);

        return $this->setQueryString('page='.$page);
    }


    //首页
    protected function first()
    {
        //设置查询的page页码为第一页，即为首页
        return $this->setQueryString('page=1');

    }


    //一种况有?，一种情况是没有?的情况
    protected function setQueryString($page)
    {
        //如果url当中有问号
        if (strpos($this->url, '?')) {
            return $this->url.'&'.$page;
        } else {
            //如果url当中没有问号
            return $this->url.'?'.$page;
        }
    }


    protected function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
        //先要获取户传进来的URI



        //用parse_url 解释URI
        $par = parse_url($path);


        //判断是否设置过query
        if (isset($par['query'])) {

            parse_str($par['query'],$query);

            //检查query里面是否有page,如果有就干掉page
            unset($query['page']);

            $path = $par['path'].'?'.http_build_query($query);

        }

        $path = rtrim($path,'?');


        //协议：主机：端口：文件和请求
        //判断是否定义过端口，并且端口是否为443，如果为443则是https协议，否则就是http协议
        $protocal = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ?'https://' : 'http://';

        if (80 == $_SERVER['SERVER_PORT'] || 443 == $_SERVER['SERVER_PORT']) {
            $url = $protocal.$_SERVER['SERVER_NAME'].$path;
        } else {
            //http://www.baidu.com:8012/index.php?username=liwenkai

            $url = $protocal.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$path;
        }



        //拼接 path. ? . 全新组合成的URI
        return $url;


    }


    protected function getCurrentPage()
    {
        //如果用户设置过page信息，就返回page相关信息，强制转为整型
        if (isset($_GET['page'])) {
            //得到页码
            $page = (int) $_GET['page'];

            //你的页码不能够大于总页数
            if ($page > $this->pageCount) {
                $page = $this->pageCount;
            }
            //你的页码不能小于1
            if ($page < 1) {
                $page = 1;
            }

        } else {
            $page = 1;
        }

        return $page;
    }

    public function getPageCount()
    {
        //计算总页数，使用到了进一法取整
        return ceil($this->total / $this->num);
    }

}
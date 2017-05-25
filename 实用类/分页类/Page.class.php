<?php


class Page
{


    //总记录数
    public $totalNum;
    //每页显示的记录数
    public $numPerPage;
    //总页数
    public $totalPage;
    //当前页
    public $currentPage;
    //上一页
    public $lastPage;
    //下一页
    public $nextPage;
    //每页的第一条记录(每页从零开始)
    public $firstNumPerPage;
    //页码栏显示页码数
    public $showNum;
    //最左边显示的页码
    public $firstShowPage;
    //最右边显示的页码
    public $lastShowPage;


    public function __construct($totalNum, $numPerPage=3, $showNum=4)
    {
        $this->totalNum = $totalNum;
        $this->numPerPage = $numPerPage;


        //共多少页
        $totalPage = ceil($totalNum / $numPerPage);
        $this->totalPage = $totalPage;
        //当前页
        $currentPage = isset($_GET['page']) ? $_GET['page'] : '1';
        $currentPage = intval($currentPage);
        $this->currentPage = $currentPage;
        //上一页
        $lastPage = $currentPage == 1 ? 1 : $currentPage - 1;
        $this->lastPage = $lastPage;
        //下一页
        $nextPage = $currentPage == $totalPage ? $totalPage : $currentPage + 1;
        $this->nextPage = $nextPage;

        //每页的第一条记录(每页从零开始)
        $firstNumPerPage = ($currentPage - 1) * $numPerPage;
        $this->firstNumPerPage = $firstNumPerPage;


        //起始页码
        if ($totalPage < $this->showNum) {
            $firstShowPage = 1;
            $lastShowPage = $totalPage;
        } elseif
        ($currentPage <= ceil($showNum / 2)
        ) {
            $firstShowPage = 1;
            $lastShowPage = $showNum;
        } elseif ($showNum % 2) {                            //奇数
            if ($currentPage > $totalPage - floor($showNum / 2)) {
                $firstShowPage = $totalPage - $showNum + 1;
                $lastShowPage = $totalPage;
            } else {
                $firstShowPage = $currentPage - floor($showNum / 2);
                $lastShowPage = $currentPage + floor($showNum / 2);
            }

        } else {                                          //偶数
            if ($currentPage > $totalPage - floor($showNum / 2)) {
                $firstShowPage = $totalPage - $showNum + 1;
                $lastShowPage = $totalPage;
            } else {
                $firstShowPage = $currentPage - floor($showNum / 2) + 1;
                $lastShowPage = $currentPage + floor($showNum / 2);
            }
        }
        $this->firstShowPage = $firstShowPage;
        $this->lastShowPage = $lastShowPage;
    }



    public function showPage()
    {
        if($this->totalNum <= 1){
            return '';
        }
        if ($this->currentPage > 1) {
            $_GET['page'] = $this->lastPage;
            $preStr = '<a href="?' . http_build_query($_GET) . '"><span class="pagepre">上一页</span></a>';
        }else{
            $preStr='';
        }
        $curStr='';
        for ($i = $this->firstShowPage; $i <= $this->lastShowPage; $i++) {
            $_GET['page'] = $i;
            $curStr.= '<a href="?' . http_build_query($_GET) . '">'.$i.'</a>';
        }
        if ($this->currentPage < $this->totalPage) {
            $_GET['page'] = $this->nextPage;
            $nextStr = '<a href="?' . http_build_query($_GET) . '"><span class="pagenxt">下一页</span></a>';
        }else{
            $nextStr='';
        }
        return $preStr.$curStr.$nextStr;

    }
}


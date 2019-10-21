<?php
    class Pagination{
        //Tổng số phần tử
        private $totalItems;
        //Số phần tử trên 1 trang
        private $totalItemsPerPage = 1;
        //Số trang xuất hiện
        private $pageRange = 5;
        //Tổng số trang
        private $totalPages;
        //trang hiện tại
        private $currentPage = 1;

        private $sort_order = '';

        private $column = '';

        public function __construct($totalItems, $totalItemsPerPage = 1, $pageRange = 3, $currentPage = 1, $column, $sort_order){
            $this->totalItems = $totalItems;
            $this->totalItemsPerPage = $totalItemsPerPage;
            if($pageRange % 2 == 0) $pageRange = $pageRange + 1;
            $this->pageRange = $pageRange;
            $this->pageRange = $pageRange;
            $this->currentPage = $currentPage;
            $this->totalPages = ceil($totalItems / $totalItemsPerPage);
            $this->sort_order = $sort_order;
            $this->column = $column;
        }
        public function showPagination(){
            //pagination
            $paginationHTML = "";
            if($this->totalPages > 1){
                $start = '<li>Start</li>';
                $prev = '<li>Previous</li>';
                $listPages = '';
                $next = '<li>Next</li>';
                $end =  '<li>End</li>';
                if($this->currentPage > 1){
                    $start = '<li><a href="?column='. $this->column .'&order='. $this->sort_order .'&page=1">Start</li>';
                    $prev = '<li><a href="?column='. $this->column .'&order='. $this->sort_order .'&page='.($this->currentPage - 1).'">Previous</a></li>';
                }
                if($this->currentPage < $this->totalPages){
                    $next = '<li><a href="?column='. $this->column .'&order='. $this->sort_order .'&page='.($this->currentPage + 1).'">Next</li>';
                    $end = '<li><a href="?column='. $this->column .'&order='. $this->sort_order .'&page='.$this->totalPages.'">End</li>';
                }

                if($this->pageRange < $this->totalPages ){
                    if($this->currentPage == 1){
                $pageStart = 1;
                $pageEnd = $this->pageRange;
                    }else if($this->currentPage == $this->totalPages){
                        $pageEnd = $this->totalPages;
                        $pageStart = $this->totalPages - $this->pageRange + 1;
                    }else{
                        $pageStart = $this->currentPage - ($this->pageRange - 1) /2;
                        $pageEnd = $this->currentPage + ($this->pageRange - 1)/2;
                        if($pageStart < 1){
                            $pageStart = 1;
                            $pageEnd = $this->pageRange;
                        }
                        if($pageEnd > $this->totalPages){
                            $pageEnd = $this->totalPages;
                            $pageStart = $this->totalPages - $this->pageRange + 1;
                        }
                    }
                }else{
                    $pageStart = 1;
                    $pageEnd = $this->totalPages;
                }
        
        
                for ($i=$pageStart; $i <= $pageEnd; $i++) { 
                    if($i == $this->currentPage){
                        $listPages .= '<li class="active">'.$i.'</li>';
                    }else{
                        $listPages .= '<li><a href="?column='. $this->column .'&order='. $this->sort_order .'&page='.$i.'">'.$i.'</a></li>';
                    }
                }
                $paginationHTML =  '<ul class="pagination">'.$start . $prev. $listPages . $next . $end.'</ul>';
            }
            return $paginationHTML;
        }
    }
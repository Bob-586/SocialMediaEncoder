<?php

/**
 * FROM site:
 * @link https://code.tutsplus.com/tutorials/how-to-paginate-data-with-php--net-2928
 */

class paginate {

    private $_conn;
    private $_limit;
    private $_page;
    private $_query;
    private $_total;

    public function __construct($conn, string $query) {
        $this->_conn = $conn;
        $this->_query = $query;

        $rs = $this->_conn->query($this->_query);
        $this->_total = $rs->rowCount();
    }

    public function get_data(int $limit = 10, int $page = 1) {
        $this->_limit = $limit;
        $this->_page = $page;

        if ($this->_limit === 0) {
            $query = $this->_query;
        } else {
            $query = $this->_query . " LIMIT " . ( ( $this->_page - 1 ) * $this->_limit ) . ", $this->_limit";
        }
        $rs = $this->_conn->query($query);
        
        $results = [];
        while ($row = $rs->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }

        $result = new \stdClass();
        $result->page = $this->_page;
        $result->limit = $this->_limit;
        $result->total = $this->_total;
        $result->data = $results;

        return $result;
    }

    public function create_links(int $links = 7, string $list_class = "ui pagination menu", string $item = "item") {
        if ($this->_limit === 0) {
            return '';
        }

        $last = ceil($this->_total / $this->_limit);

        $start = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
        $end = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;

        $html = '<div class="' . $list_class . '">';

        $class = ( $this->_page == 1 ) ? "disabled " : "";
        $item = " " . $item;
        
        $html .= '<a class="' . $class . $item . '" href="?limit=' . $this->_limit . '&page=' . ( $this->_page - 1 ) . '">&laquo;</a>';

        if ($start > 1) {
            $html .= '<a class="' . $item . '" href="?limit=' . $this->_limit . '&page=1">1</a>';
            $html .= '<div class="disabled' . $item . '"><span>...</span></div>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $class = ( $this->_page == $i ) ? "active" : "";
            $html .= '<a class="' . $class . $item . '" href="?limit=' . $this->_limit . '&page=' . $i . '">' . $i . '</a>';
        }

        if ($end < $last) {
            $html .= '<div class="disabled item"><span>...</span></div>';
            $html .= '<a class="' . $class . $item . '" href="?limit=' . $this->_limit . '&page=' . $last . '">' . $last . '</a>';
        }

        $class = ( $this->_page == $last ) ? "disabled" : "";
        $html .= '<a class="' . $class . $item . '" href="?limit=' . $this->_limit . '&page=' . ( $this->_page + 1 ) . '">&raquo;</a>';

        $html .= '</div>';

        return $html;
    }
    
    public function create_jump_menu_with_links(int $links = 7, string $label = "Jump to ", string $end_label = " page.", string $item = "item"): string {
        if ($this->_limit === 0) {
            return '';
        }

        $last = ceil($this->_total / $this->_limit);

        $start = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
        $end = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;

        // Prev. Page
        $class = ( $this->_page == 1 ) ? "disabled" : "";
        $href = ( $this->_page == 1 ) ? "" : 'href="?limit=' . $this->_limit . '&page=' . ( $this->_page - 1 ) . '"' ;
        $html .= '<a class="' . $class . ' item" ' . $href . '>&laquo;</a>';

        $html .= $this->create_jump_menu();
        
        // Next Page
        $class = ( $this->_page == $last ) ? "disabled" : "";
        $href = ( $this->_page == $last ) ? "" : 'href="?limit=' . $this->_limit . '&page=' . ( $this->_page + 1 ) . '"';
        $html .= '<a class="' . $class . ' item" ' . $href . '>&raquo;</a>';
        
        return $html;
        
    }

    public function create_jump_menu(string $label = "Jump to ", string $end_label = " page.", string $item = "item"): string {
        $last = ceil($this->_total / $this->_limit);
        $option = '';
        for($i=1; $i <= $last; $i++) {
          $option .= ($i == $this->_page) ? "<option value=\"{$i}\" selected>Page {$i}</option>\n":"<option value=\"{$i}\">Page {$i}</option>\n";
        }
        return "<label>{$label}<select class=\"{$item}\" onchange=\"window.location='?limit={$this->_limit}&page='+this[this.selectedIndex].value;return false;\">"
            . "{$option}</select>"
            . "{$end_label}</label>\n";
    }
    
    public function create_items_per_page(string $label = "Items ", string $end_label = " per page.", string $item = "item"): string {
        $items = '';
        $ipp_array = array(3,6,12,24,50,100);
        $found = false;
        foreach($ipp_array as $ipp_opt) {
          if ($ipp_opt == $this->_limit) {
            $found = true;
          }
          $items .= ($ipp_opt == $this->_limit) ? "<option selected value=\"{$ipp_opt}\">{$ipp_opt}</option>\n" : "<option value=\"{$ipp_opt}\">{$ipp_opt}</option>\n";
        }
        
        if ($found === false) {
          $items = "<option selected value=\"{$this->_limit}\">{$this->_limit}</option>\n" . $items;
        }
        
        return "<label>{$label}<select class=\"{$item}\" onchange=\"window.location='?page=1&limit='+this[this.selectedIndex].value;return false;\">{$items}</select>"
        . "{$end_label}</label>\n";
    }
    
}
<?php

function html_pages($link, $count, $count_page, $ajax = '0', $func_name = 'load_pages', $func_data = 'this'){
    global $Cur;

    $pages = @ceil($count / $count_page);
    $pages = ($pages == 0 ? '1' : $pages);

    $pages_html = '';

    if($pages <= 2){
        $pages_html .= '&lt;&lt;&lt;&nbsp;';
    }else{
        $pages_html .= '<a href="'.$link.'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>&lt;&lt;&lt;</a>&nbsp;';
    }

    if($pages == 1){
        $pages_html .= ($Cur['page']-1) > -1 ? '&lt;&nbsp;' : '&lt;&nbsp;';
    }else{
        $pages_html .= ($Cur['page']-1) > -1 ? '<a href="'.$link.'&amp;page='.($Cur['page']-1).'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>&lt;</a>&nbsp;' : '<a href="'.$link.'&amp;page=0"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>&lt;</a>&nbsp;';
    }

    for($i = $Cur['page']-10; $i < $Cur['page']; $i++){
        if($i > -1){
            $pages_html .= '<a href="'.$link.'&amp;page='.$i.'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>'.($i+1).'</a>&nbsp;';
        }
    }

    for($i = $Cur['page']; $i < $Cur['page']+10; $i++){
        if($i < $pages){
            if($Cur['page'] == $i){
                $pages_html .= $i+1 . " ";
            }else{
                if($i == 0){
                    $pages_html .= '<a href="'.$link.'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>'.($i+1).'</a>&nbsp;';
                }else{
                    $pages_html .= '<a href="'.$link.'&amp;page='.$i.'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>'.($i+1).'</a>&nbsp;';
                }
            }
        }
    }

    if($pages == 1){
        $pages_html .= '&gt;&nbsp;';
    }else{
        $pages_html .= '<a href="'.$link.'&amp;page='.($Cur['page']+1 < $pages ? $Cur['page']+1 : $Cur['page']).'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>&gt;</a>&nbsp;';
    }

    if($pages <= 2){
        $pages_html .= '&gt;&gt;&gt;&nbsp;';
    }else{
        $pages_html .= '<a href="'.$link.'&amp;page='.($pages-1).'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>&gt;&gt;&gt;</a>&nbsp;';
    }

    return $pages_html;
}

?>
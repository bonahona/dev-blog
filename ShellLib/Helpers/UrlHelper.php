<?php

function Url($url, $variables = array()){
    if(is_array($url)) {

        if (isset($url['vars']) && !empty($url['vars'])) {
            $variables = implode('/', $url['vars']);
        }else{
            $variables = '';
        }

        $result = '/' . $url['controller'] . '/' . $url['action'] . '/' . $variables;
        return $result;
    }else{
        $completeArray = SERVER_ROOT . $url;
        return $completeArray;
    }
}
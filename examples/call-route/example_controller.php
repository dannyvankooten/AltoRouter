<?php

class example_controller {

    public function myMethod($params) {
    
        echo '<h3>Called '.__CLASS__.'->'.__FUNCTION__.'</h1>';
        echo 'Our parameters as a named array';
        echo '<br/>id: '. $params['id'];
        echo '<br/>action: '. $params['action'];
        
    }
    
    public function myMethodIndex($params) {

        echo '<h3>Called '.__CLASS__.'->'.__FUNCTION__.'</h1>';

    }
    
}
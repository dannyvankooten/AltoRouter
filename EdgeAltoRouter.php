<?php
/*
MIT License

Copyright (c) 2024 ROY Emmanuel <emmanuel.roy@infoartsmedia.fr>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class EdgeAltoRouter extends AltoRouter
{

    const USE_SPEED = 0; //Need less cpu but consumes more electric energy
    const USE_MEMORY = 1; //Need more cpu but consumes less electric energy
    
    /**
     * Create router in one call from multiple type of configfile.
     *
     * @param array $routes
     * @param string $basePath
     * @param array $matchTypes
     * @param string $configModelUrl
     * @param int $usage
     * @throws Exception
     */
    public function __construct(array $routes = [], string $basePath = '', array $matchTypes = [], string $configModelUrl = __DIR__.DIRECTORY_SEPARATOR.'routes.model', int $usage = self::USE_MEMORY)
    {
        $this->addRoutes($routes);
        $this->setBasePath($basePath);
        $this->addMatchTypes($matchTypes);
        if(file_exists($configModelUrl)){
            $this->setRouteFromConfig($configModelUrl,$usage);
        }
    }

    /**
     * Load all routes in one call from a multiple type of config file.
     *
     * @param string $configUrl
     * @param int $usage
     * @throws Exception
     */
    public function setRouteFromConfig($configUrl, $usage){
        if(file_exists($configUrl)){
            $path_parts = pathinfo($configUrl);
            switch($path_parts['extension']){
                case 'model':
                    $this->mappingRouteFromConfigModelFile($configUrl, $usage);
                break;
                case 'csv':
                case 'json':
                case 'yaml':
                case 'yml':
                default:
                    throw new RuntimeException('error : configfile type is not implemented yet');
            }
        }else{
            throw new RuntimeException('error : configfile is not found');
        }
    }
    
    /**
     * Load all routes in one call from a .model config file.
     *
     * @link: .model is a filetype invention of Emmanuel ROY, initialy developed for SAND-framework --> https://github.com/Acksop/SAND-framework
     *
     * @param string $configUrl
     * @param int $usage
     * @throws Exception
     */
    public function mappingRouteFromConfigModelFile($fileUrl, $usage){
        if(file_exists($fileUrl)){
                $file = file($fileUrl);
                if($usage == self::USE_MEMORY){
                    $bigArray = array();
                 }
                foreach ($file as $line_num => $line) {
                    //searching pattern parameters
                    if (preg_match("#[ ]*([a-zA-Z_+ ]*)[:][ ]*([a-zA-Z0-9:\/\\ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ_+\-'\"\{\,\ \}\(\)\[\]\|=>\#]*[ ]*)#", $line, $matches)) {
                        //searching array pattern
                        if (preg_match("#{.*}#", $matches[2])) {
                            if (preg_match_all("#(?<capture>((\[([0-9a-zA-ZÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ_\-=>'\" ]*,?)*\])|([0-9a-zA-Z\/\\ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ_+\-\[\]:\|\#]*)))#", $matches[2], $arrayMatches)) {
                                $array = array();
                                foreach ($arrayMatches['capture'] as $capturedValue) {
                                    if(preg_match("#^\[((.*=>.*),?)*\]$#", $capturedValue)){
                                        $capturedArrayIndex = array();
                                        $capturedArray = array();
                                        if (preg_match_all("#(?<capture>[0-9a-zA-Z:ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ_+\-]*)#", trim($capturedValue), $capturedArrayMatches)) {
                                            foreach ($capturedArrayMatches['capture'] as $capturedArrayValue) {
                                                if (trim($capturedArrayValue) !== ''){
                                                    $capturedArrayIndex[] = mb_convert_encoding( trim($capturedArrayValue), 'UTF-8', mb_detect_encoding( $capturedArrayValue, 'auto') );
                                                }
                                            } 
                                            if(count($capturedArrayIndex)%2 !== 0){
                                               $capturedArray = 'error : some key of the array has no values';
                                               throw new RuntimeException('error : some key of the array in configfile has no values');
                                            }else{
                                                for($i = 0; $i < count($capturedArrayIndex) ; $i = $i+2){
                                                    $capturedArray[mb_convert_encoding( trim($capturedArrayIndex[$i]), 'UTF-8', mb_detect_encoding( $capturedArrayIndex[$i], 'auto'))] = mb_convert_encoding( trim($capturedArrayIndex[$i+1]), 'UTF-8', mb_detect_encoding( $capturedArrayIndex[$i+1], 'auto'));
                                                }
                                            }
                                        }
                                        $array[] =  $capturedArray;
                                    }else if(preg_match("#^\[((.*),?)*\]$#", $capturedValue)){
                                        $capturedArray = array();
                                        if (preg_match_all("#(?<capture>[0-9a-zA-Z:ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ_+\-]*)#", trim($capturedValue), $capturedArrayMatches)) {
                                            foreach ($capturedArrayMatches['capture'] as $capturedArrayValue) {
                                                if (trim($capturedArrayValue) != ''){
                                                    $capturedArray[] = mb_convert_encoding( trim($capturedArrayValue), 'UTF-8', mb_detect_encoding( $capturedArrayValue, 'auto') );
                                                }
                                            } 
                                        }          
                                        $array[] =  $capturedArray;              
                                    }else if ($capturedValue != '') {
                                        $array[] = mb_convert_encoding( trim($capturedValue), 'UTF-8', mb_detect_encoding( $capturedValue, 'auto') );
                                    }
                                }
                                $array[] = trim($matches[1]);
                                if($usage == self::USE_SPEED){
                                    $this->map(...$array);
                                }else{
                                    $bigArray[] = $array;
                                }
                                continue;
                            }
                        }
                    }
                }
                if($usage == self::USE_MEMORY){
                    $this->addRoutes($bigArray);
                }
            }else{
                throw new RuntimeException('error : configfile is not found');
            }
        }
    }

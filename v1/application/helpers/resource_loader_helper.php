<?php

if( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('_loadConfig')){

    function _loadConfig($aliasGroup = null){

        $ci = &get_instance();
        
        // load without check since this takes less memory rather then checking if helper is loaded => ci handles this
        // https://stackoverflow.com/questions/19630019/codeigniter-load-libraries-if-not-already-loaded/23597257#23597257

        $ci->load->helper('url');
        $ci->load->config('resource_loader');

        $config = [];

        if(is_null($aliasGroup)){

            foreach ($ci->config->item('dirs_used') as $key => $value) {
                $config[$key] = $value;
            }
            ;
            return $config;
        }

        return [$ci->config->item('enable_groups'), $ci->config->item('alias_names')];
    }
}

if(!function_exists('_rl_handler')){

    function _rl_handler($entry, $type = null){

        if(!is_array($entry[0])){

            return @_rl_single($entry[0],$entry[1],$type);
            
        }else{

            return _rl_array($entry,$type);
        }
    }
}


if(!function_exists('_rl_single')){

    function _rl_single(string $fileName, $args, $type = null){

        if(!empty($type)){

            $config = _loadConfig();
            $attributeString = _parseAttributes($args);
            $location = _locationLink($config['resource_dir'], $config[$type], $fileName);

            return _generateOutput($location, $attributeString, $type);
        }  
        
    }
}

if(!function_exists('_rl_array')){

    function _rl_array(array $multipleInputs, $type){
     
        array_walk($multipleInputs, function ($value,$key,$type){
            // mute since if there is no arguments suplied, it throws notice 
            return @_rl_single($value[0], $value[1], $type);
            
        },$type);  
    }
}

if(!function_exists('_parseAttributes')){

    function _parseAttributes($attr){

        if(!empty($attr) and is_array($attr)){

            $attributeString = '';

            foreach($attr as $key => $value){

                if(!is_string($key)){

                    $attributeString .= $value. ' ';
                    
                    continue;
                }

                $attributeString .= ($key.'="'.$value.'" ');
            }

            return $attributeString;
        }    
    }
}

if(!function_exists('_locationLink')){

    function _locationLink(string $assetsPath, string $assetType, string $fileName){
        
        return base_url($assetsPath.'/'.$assetType. '/'. $fileName);
    }
}

if(!function_exists('_rl_handle_single_load')){

    function _rl_handle_single_load(array $info){

        if(is_array($info)){
            
            $ci = &get_instance();
            $ci->load->helper('url');

            $args = _parseAttributes($info[1]);
            $location = base_url($info[0]);
            $type = $info[2];
            
            return _generateOutput($location, $args, $type);
        }
    }
}


if(!function_exists('_rl_media_handler')){

    function _rl_media_handler(array $info){

        $config = _loadConfig();
        //array(1) { [0]=> array(3) { ["video"]=> array(2) { ["test"]=> string(3) "123" [0]=> string(8) "autoplay" } ["source"]=> array(2) { ["test"]=> string(3) "123" [0]=> string(8) "autoplay" } [0]=> array(2) { [0]=> string(8) "load.mp4" [1]=> array(1) { [0]=> string(8) "load.ogc" } } } }
        if(array_key_exists('video', $info[0])){
            
            return _rl_load_video($info[0]['video'], $info[0]['source'], $info[0]['filename'], $config);

        }else if(array_key_exists('audio', $info[0])){
        
            return _rl_load_audio($info[0]['audio'], $info[0]['source'], $info[0]['filename'], $config);
        }
    }
}

if(!function_exists('_rl_load_video')){

    function _rl_load_video($videoAttr, $sourceAttr, array $fileNames, $config){

        $videoAttr = _parseAttributes($videoAttr);
        $sourceAttr = _parseAttributes($sourceAttr);

        $source = '';

        foreach ($fileNames as $file) {

            list($name, $type) = explode('.', $file);

            $location = _locationLink($config['resource_dir'], $config['video'],$file);

            $source .= '<source src="'.$location.'" type="video/'.$type.'" '.$sourceAttr.' > ';
        }   
        
        return sprintf('<video %s>
                            %s
                            Your browser does not support the video tag.
                        </video>
                        ', $videoAttr, $source);
    }
}



if(!function_exists('_rl_load_audio')){

    function _rl_load_audio($audioAttr, $sourceAttr, array $fileNames, $config){
        
        $audioAttr = _parseAttributes($audioAttr);
        $sourceAttr = _parseAttributes($sourceAttr);

        $source = '';

        foreach ($fileNames as $file) {

            list($name, $type) = explode('.', $file);
            
            $location = _locationLink($config['resource_dir'], $config['audio'],$file);

            $source .= '<source src="'.$location.'" type="audio/'.$type.'" '.$sourceAttr.' >';
        }   
        
        return sprintf('<audio %s>
                            %s
                            Your browser does not support the audio element.
                        </audio>
                        ', $audioAttr, $source);
        
    }
}


if(!function_exists('_rl_handle_alias')){

    function _rl_handle_alias($aliasName){

        $config = _loadConfig(true);

        if($config[0] === true){

            if(is_array($config[1])){

                if(array_key_exists($aliasName,$config[1])){

                    foreach ($config[1][$aliasName] as $function => $parameter) {

                        $parameters = explode(',', $parameter);

                        call_user_func($function, $parameters);
                    }
                }
            }
        }  
    }
}


if(!function_exists('_generateOutput')){

    function _generateOutput(string $location, $attr, string $type){
   
        switch ($type) {
            case 'js':
                printf('<script type="text/javascript" src="%s" %s></script>', $location, $attr);
            break;

            case 'css':
                printf('<link rel="stylesheet" type="text/css" href="%s" %s>', $location, $attr);
            break;

            case 'img':
                printf('<img src="%s" %s />', $location, $attr);
            break; 

            case 'favicon':
                printf('<link rel="icon" type="image/x-icon" href="%s" %s />', $location, $attr);
            break; 

            case 'embed':
                printf('<embed src="%s" %s >', $location, $attr);
            break;

            case 'object':
                printf('<object data="%s" %s ></object>', $location, $attr);
            break; 

            default:
                return;
            break;
        }
    }
}

if(!function_exists('rl_css')){

    function rl_css(...$entry){
  
        return _rl_handler($entry, 'css');
    }
}

if(!function_exists('rl_js')){

    function rl_js(...$entry){
  
        return _rl_handler($entry, 'js');
    }
}

if(!function_exists('rl_img')){

    function rl_img(...$entry){
  
        return _rl_handler($entry, 'img');
    }
}

if(!function_exists('rl_favicon')){

    function rl_favicon(...$entry){
  
        return _rl_handler($entry, 'favicon');
    }
}

if(!function_exists('rl_embed')){

    function rl_embed(...$entry){
  
        return _rl_handler($entry, 'favicon');
    }
}

if(!function_exists('rl_object')){

    function rl_object(...$entry){
  
        return _rl_handler($entry, 'favicon');
    }
}

if(!function_exists('rl_video')){

    function rl_video(...$entry){
  
        return _rl_media_handler($entry);
    }
}

if(!function_exists('rl_audio')){

    function rl_audio(...$entry){
  
        return _rl_media_handler($entry);
    }
}

if(!function_exists('rl_load')){

    function rl_load(...$entry){
  
        return _rl_handle_single_load($entry);
    }
}


if(!function_exists('rl_alias')){

    function rl_alias(string $aliasName){
  
        return _rl_handle_alias($aliasName);
    }
}






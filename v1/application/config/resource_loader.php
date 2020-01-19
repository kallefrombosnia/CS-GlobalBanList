<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Resource directories
| -------------------------------------------------------------------
| In array there are alias for every directory.
| Change value with your own asset directory names.
| If you dont need some fields delete it or leave value empty since 
| its never get used.
|
| NOTE: If uou dont have dirs before your resources leave resource_dir empty string.
|       Dont edit array keys only value of key, otherwise you will 
|       break helper.       
|       Dont put prefix and sufix slash, only in directory    
|       separator. Example:
|
|           $config['dirs_used'] = ['resource_dir' => 'assets','js' => 'some/other/dir/for/js'];
|       
|       This will generate path domain/assets/some/other/dir/for/js/name.js
|
*/

$config['dirs_used'] = [
    'resource_dir' => 'assets',
    'js' => 'js',
    'css' => 'css',
    'img' => 'img',
    'favicon' => 'img',
    'video' => 'videos',
    'audio' => 'audio',
    'embed' => 'embed',
    'object' => 'object'
];

/*
| -------------------------------------------------------------------
|  Resource group load
| -------------------------------------------------------------------
| Enable group load from resources.
| This feature is good when you want to load same group of resources in
| diffrent files. 
|
| NOTE: call rl_alias('alias name'); in view to load this group of 
|       assets      
|     
*/

$config['enable_groups'] = false;

/*
| -------------------------------------------------------------------
|  Resource group load
| -------------------------------------------------------------------
| If enable_groups is enabled in alias_names you can include functions 
| which are gonna executed when being called in view with specific
| predefined alias name.
| You can have multiple alias names in this array, call one which you wanna
| use. Example:
|
|       $config['alias_names'] = [
|           'head' => [
|               'rl_favicon' => "code.ico, ['test' => '123', 'play', 'igra', 'omg' => 'wtf']",
|               'rl_css' => "css.css,['width' => '500px']",
|               'rl_js' => "sb-admin.js, ['test' => '123']",
|            ],
|           'media' => [
|                'rl_audio' => "['audio' => ['test' => '123', 'controls'], 'source' => ['test' => '123', 'autoplay'], 'filename' => ['load.ogg','balenciaga.mp3']]",
|                'rl_video' => "['video' => ['test' => '123', 'autoplay', 'controls'], 'source' => ['test' => '123', 'autoplay'], 'filename' => ['load.ogg','load.mp4']]"
|           ]
|       ];       
|     
*/

$config['alias_names'] = [];

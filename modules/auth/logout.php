<?php 
if(!defined('_INCODE'))  die('Access Dined...');
if(isLogin()){
    $token = getSession('loginToken');
    deleteDtb('logintoken',"token='$token'");
    removeSession('loginToken');
    redirect('?module=auth&action=login');
}
<?php 
namespace Riyu\Contract;

interface Controller
{
    public function callAction($action, $params);
}
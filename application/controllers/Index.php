<?php
class IndexController extends Yaf\Controller_Abstract {
   //默认Action
   public function indexAction() {
       $this->getView()->assign("content", "Hello World");
   }
}

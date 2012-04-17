<?php
class XHProf {
 
   private $XHProfPath = '../xhprof/';
   private $applicationName = 'ClickThis';
   private $sampleSize = 1;
   private static $enabled = false;
 
   public function XHProf_Start() {
      if (mt_rand(1, $this->sampleSize) == 1) {
         require_once $this->XHProfPath . 'xhprof_lib/utils/xhprof_lib.php';
         require_once $this->XHProfPath . 'xhprof_lib/utils/xhprof_runs.php';
         xhprof_enable(XHPROF_FLAGS_NO_BUILTINS);
 
         self::$enabled = true;
      }
   }
 
   public function XHProf_End() {
      if (self::$enabled) {
         $XHProfData = xhprof_disable();
 
         $XHProfRuns = new XHProfRuns_Default();
         $XHProfRuns->save_run($XHProfData, $this->applicationName);
      }
   }
 
}
?>
<?php
namespace nigiri\themes;

use nigiri\Site;
use nigiri\views\Html;
use nigiri\views\Url;

class Theme implements ThemeInterface {
    protected $title = '';
    protected $head = '';
    protected $script = '';
    protected $script_on_ready = '';
    protected $body = '';

    public function append($str, $part = 'body')
    {
        if(property_exists($this,$part)){
            $this->$part .= $str;
        }
    }

    public function resetPart($name)
    {
        if(property_exists($this,$name)){
            $this->$name = '';
        }
    }

    public function render()
    {
        $this->title .= (empty($this->title)?'':' - ').Site::getParam('site_name');

        $ready = '';
        if(!empty($this->script_on_ready)){
            $ready = <<<READY
<script type="application/javascript">
$(function(){
    {$this->script_on_ready}
});
</script>
READY;
        }

        echo '
<!DOCTYPE html>
<html lang="'.Site::getRouter()->getRequestedLanguage().'">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>'.Html::escape($this->title).'</title>

    <!-- Bootstrap -->
    <link href="'.Url::resource('assets/css/bootstrap.min.css').'" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    '.$this->head.'
  </head>
  <body>
    '.$this->body.'

    <!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="'.Url::resource('assets/js/bootstrap.min.js').'"></script>
    
    '.$this->script.'
    
    '.$ready.'
  </body>
</html>
';
    }
}

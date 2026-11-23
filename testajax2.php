<?php
require __DIR__.'/vendor/autoload.php';
\ = require_once __DIR__.'/bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Console\Kernel::class);
\->bootstrap();
\ = new App\Http\Controllers\AutorController();
\ = Illuminate\Http\Request::create('/autores/rapido','POST',['nombre'=>'AjaxTest'.time()]);
\->headers->set('Accept','application/json');
\->headers->set('Content-Type','application/json');
\ = \->storeAjax(\);
if(method_exists(\,'getStatusCode')) echo \->getStatusCode().PHP_EOL;
if(method_exists(\,'getContent')) echo \->getContent().PHP_EOL;

<?php
	$ruta = base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64')." --viewport-size 1024x768";
    return [
        'pdf' => [
        'enabled' => true,
        'binary'  =>  $ruta,
        'timeout' => false,
        'options' => array(
            'page-size' => 'Letter',
            'margin-right' => 10,
            'margin-left' => 10,
            'footer-font-size' => 6,
            'header-font-size' => 6,
            'encoding'=>'utf-8',
        ),
        'env'     => [],
        ],

        'image' => [
        'enabled' => true,
        'binary'  => base_path(' vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
        ],
        'binary'  => '/usr/local/bin/wkhtmltopdf-amd64',
    ];
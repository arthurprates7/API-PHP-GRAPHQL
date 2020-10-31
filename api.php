<?php

use Siler\GraphQL;
use Siler\Http\Request;
use Siler\Http\Response;

require './vendor/autoload.php';

// Enable CORS
Response\cors();

// Respond only for POST requests
if (Request\method_is('post')) {
    // Retrive the Schema
    $schema = include __DIR__.'/schema.php';

    // Give it to siler
    GraphQL\init($schema);
}
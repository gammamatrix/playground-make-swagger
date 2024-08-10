<?php
/**
 * Playground
 */

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Swagger Docs: Response Language Lines
    |--------------------------------------------------------------------------
    |
    |
    */

    'data.description' => 'The :name data.',

    'create.description' => 'The create :name information.',
    'create.resource.description' => 'The create :name information (JSON) or (HTML).',
    'create.resource.content.example' => '<html><body><form method="POST" action="/resource/:route-module/:route-names">Create a :name</form></body></html>',

    'edit.description' => 'The edit :name information.',
    'edit.resource.description' => 'The edit :name information (JSON) or (HTML).',
    'edit.resource.content.example' => '<html><body><form method="POST" action="/resource/:route-module/:route-names/{id}">Edit a :name</form></body></html>',

];

<?php

return array(

    'assetsDirectory' => 'public/assets',

    'css' => array(

        'directories' => array(
            'css'
        ),

        'packages' => array(

            // Default layout
            'css/layouts/default.pack.css' => array(
                'plugins/bootstrap/css/bootstrap.min.css'
            )
        ),
    ),

    'js' => array(

        'directories' => array(
            'js'
        ),

        'packages' => array(

            // Default layout
            'js/default.pack.js' => array(
                'plugins/bootstrap/js/bootstrap.min.js',
            )
        )
    )
);
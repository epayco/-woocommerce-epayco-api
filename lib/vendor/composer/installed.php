<?php return array(
    'root' => array(
        'name' => '__root__',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => null,
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        '__root__' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'reference' => null,
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'epayco/epayco-php' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => 'a57c6e7b135ad86d75555125b1724fb42c98fbf3',
            'type' => 'sdk',
            'install_path' => __DIR__ . '/../epayco/epayco-php',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'rmccue/requests' => array(
            'pretty_version' => 'v2.0.12',
            'version' => '2.0.12.0',
            'reference' => 'fb67e3d392ff6b89a90e96f19745662f4ecd62b1',
            'type' => 'library',
            'install_path' => __DIR__ . '/../rmccue/requests',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);

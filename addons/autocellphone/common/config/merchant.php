<?php

return [

    // ----------------------- 菜单配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'default', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-puzzle-piece',
        ],
        // 子模块配置
        'modules' => [
        ],
    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //
    'menu' => [
        [
            'title' => '自动化粉',
            'route' => 'device/index',
            'icon' => 'fa fa-tool',
            'params' => [
            
            ],
            'child' => [
                [
                    'title' => '设备',
                    'route' => 'device/index',
                ],
                [
                    'title' => '用户',
                    'route' => 'user/index',
                ],
                [
                    'title' => '群',
                    'route' => 'qun/index',
                ],
            ],
        ],
    
    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        [
            'title' => '所有权限',
            'name' => '*',
        ],
    ],
];
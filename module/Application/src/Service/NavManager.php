<?php
namespace Application\Service;
/**
 * This service is responsible for determining which items should be in the main menu.
 * The items may be different depending on whether the user is authenticated or not.
 */
class NavManager {
    /**
     * Auth service.
     * @var Zend\Authentication\Authentication
     */
    private $authService;

    /**
     * Url view helper.
     * @var Zend\View\Helper\Url
     */
    private $urlHelper;

    /**
     * Constructs the service.
     */
    public function __construct($authService, $urlHelper) {
        $this->authService = $authService;
        $this->urlHelper = $urlHelper;
    }


    /**
     * This method returns menu items depending on whether user has logged in or not.
     */
    public function getMenuItems() {
        $items = [];
        $url = $this->urlHelper;

        $items[] = [
            'id' => 'home',
            'label' => 'Начало',
            'link' => $url('home')
        ];

        $items[] = [
            'id' => 'cities',
            'label' => 'Градове',
            'link' => $url('cities')
        ];

        $items[] = [
            'id' => 'clients',
            'label' => 'Клиенти',
            'link' => $url('clients')
        ];

        $items[] = [
            'id' => 'orders',
            'label' => 'Плащания',
            'link' => $url('orders')
        ];

        // Display "Login" menu item for not authorized user only. On the other hand,
        // display "Admin" and "Logout" menu items only for authorized users.
        if (!$this->authService->hasIdentity()) {
            $items[] = [
                'id' => 'login',
                'label' => 'Вход',
                'link' => $url('login'),
                'float' => 'right'
            ];
        } else {

            $items[] = [
                'id' => 'admin',
                'label' => 'Админ',
                'dropdown' => [
                    [
                        'id' => 'users',
                        'label' => 'Управление на потребители',
                        'link' => $url('users')
                    ]
                ]
            ];

            $items[] = [
                'id' => 'logout',
                'label' => $this->authService->getIdentity(),
                'float' => 'right',
                'dropdown' => [
                    [
                        'id' => 'settings',
                        'label' => 'Настройки',
                        'link' => $url('application', ['action' => 'settings'])
                    ],
                    [
                        'id' => 'logout',
                        'label' => 'Изход',
                        'link' => $url('logout')
                    ],
                ]
            ];
        }

        return $items;
    }
}

<?php

use Middleware\AuthorizationMiddleware;
use Services\ForgotPasswordService;
use Request\RequestFactory;
use Exception\SqlException;

return [
    "responseFactory" => function (ServiceContainer $container) {
        return new Response\ResponseFactory($container->get("viewRenderer"));
    },
    "viewRenderer" => function (ServiceContainer $container) {
        return new ViewRenderer($container->get("basePath"));
    },
    'responseEmitter' => function () {
        return new Response\ResponseEmitter();
    },
    "config" => function (ServiceContainer $container) {
        $base = $container->get("basePath");
        return include_once $base . "/config.php";
    },
    "connection" => function (ServiceContainer $container) {
        $config = $container->get("config");
        $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
        if (!$connection) {
            throw new SqlException('Connection error: ' . mysqli_error($connection));
        }
        return $connection;
    },
    "baseUrl" => function() {
        $protocol = strpos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
        return $protocol.$_SERVER['HTTP_HOST'];
    },
    "photoService" => function (ServiceContainer $container) {
        return new Services\PhotoService($container->get("connection"));
    },
    'homeController' => function (ServiceContainer $container) {
        return new Controllers\Image\HomeController($container->get("photoService"));
    },
    'singleImageController' => function (ServiceContainer $container) {
        return new Controllers\Image\SingleImageController($container->get("photoService"));
    },
    'singleImageEditController' => function (ServiceContainer $container) {
        return new Controllers\Image\SingleImageEditController($container->get("photoService"));
    },
    'singleImageDeleteController' => function (ServiceContainer $container) {
        return new Controllers\Image\SingleImageDeleteController($container->get("photoService"));
    },
    'imageCreateFormController' => function () {
        return new Controllers\Image\ImageCreateFormController();
    },
    'imageCreateSubmitController' => function (ServiceContainer $container) {
        return new Controllers\Image\ImageCreateSubmitController($container->get("basePath"), 
        $container->get("request"), $container->get("photoService"));
    },
    'loginFormController' => function (ServiceContainer $container) {
        return new Controllers\Auth\LoginFormController($container->get("session"));
    },
    'loginSubmitController' => function (ServiceContainer $container) {
        return new Controllers\Auth\LoginSubmitController($container->get("authService"), $container->get("session"));
    },
    'logoutSubmitController' => function (ServiceContainer $container) {
        return new Controllers\Auth\LogoutSubmitController($container->get("authService"));
    },
    'forgotPasswordController' => function (ServiceContainer $container) {
        return new Controllers\ForgotPassword\ForgotPasswordController($container->get("session"));
    },
    'passwordResetController' => function (ServiceContainer $container) {
        return new Controllers\ForgotPassword\PasswordResetController($container->get("request"));
    },
    'passwordResetSubmitController' => function (ServiceContainer $container) {
        return new Controllers\ForgotPassword\PasswordResetSubmitController($container->get("request"), $container->get("forgotPasswordService"));
    },
    'forgotPasswordSubmitController' => function (ServiceContainer $container) {
        return new Controllers\ForgotPassword\ForgotPasswordSubmitController($container->get("request"), $container->get("forgotPasswordService"));
    },
    "authService" => function (ServiceContainer $container) {
        return new Services\AuthService($container->get("connection"), $container->get("session"));
    },
    'notFoundController' => function () {
        return new Controllers\NotFoundController();
    },
    'forgotPasswordService' => function (ServiceContainer $container) {
        return new ForgotPasswordService($container->get("connection"), $container->get("mailer"), $container->get("baseUrl"));
    },
    "mailer" => function (ServiceContainer $container) {
        $mailerConfig = $container->get("config")["mail"];
        $transport = (new Swift_SmtpTransport($mailerConfig["host"], $mailerConfig["port"]))
            ->setUsername($mailerConfig["username"])
            ->setPassword($mailerConfig["password"]);
        return new Swift_Mailer($transport);
    },
    'session' => function (ServiceContainer $container) {
        $sessionConfig = $container->get("config")["session"];
        return \Session\SessionFactory::build($sessionConfig["driver"], $sessionConfig["config"]);
    },
    'request' => function (ServiceContainer $container) {
        return RequestFactory::createFromGlobals($container);
    },
    'pipeline' => function (ServiceContainer $container) {
        $pipeline = new Middleware\MiddlewareStack();
        $authMiddleware = new AuthorizationMiddleware(["/"], $container->get("authService"), "/login");
        $dispatcherMiddleware = new Middleware\DispatchingMiddleware($container->get("dispatcher"), $container->get("responseFactory"));
        $pipeline->addMiddleware($authMiddleware);
        $pipeline->addMiddleware($dispatcherMiddleware);
        return $pipeline;
    },
    'dispatcher' => function (ServiceContainer $container) {
        $dispatcher = new Dispatcher($container, 'notFoundController@handle');
        $dispatcher->addRoute('/', 'homeController@handle');
        $dispatcher->addRoute('/image/(?<id>[\d]+)', 'singleImageController@display');
        $dispatcher->addRoute('/image/(?<id>[\d]+)/edit', 'singleImageEditController@edit', "POST");
        $dispatcher->addRoute('/image/(?<id>[\d]+)/delete', 'singleImageDeleteController@delete', "POST");

        $dispatcher->addRoute('/login', 'loginFormController@show');
        $dispatcher->addRoute('/logout', 'logoutSubmitController@submit');
        $dispatcher->addRoute('/login', 'loginSubmitController@submit', "POST");

        $dispatcher->addRoute('/forgotpass', 'forgotPasswordController@show');
        $dispatcher->addRoute('/forgotpass', 'forgotPasswordSubmitController@submit', "POST");

        $dispatcher->addRoute('/reset', 'passwordResetController@show');
        $dispatcher->addRoute('/reset', 'passwordResetSubmitController@submit', "POST");

        $dispatcher->addRoute('/image/add', 'imageCreateFormController@show');
        $dispatcher->addRoute('/image/add', 'imageCreateSubmitController@submit', "POST");
        return $dispatcher;
    }
];

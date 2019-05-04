# Cherry-router 

## [v1.1.0](https://github.com/cherry-framework/router/releases/tag/v1.1.0 "v1.1.0") (2019-04-05)

- Remove namespace part form route action. Now you can use this syntax:

    ```json
    {
      ...
      "action": "DefaultController::index",
      ...
    }
    ```

- Lock Cherry Request dependency to 1.0.x version.

## [v1.0.1](https://github.com/cherry-framework/router/releases/tag/v1.0.1 "v1.0.1") (2019-03-29)

- Change Namespace

    Now you should use new namespace `Cherry\Routing\Router`
    
    In controllers `Cherry\Controller`

- Validate code in PHP CodeSniffer

    Code and comments has checked on [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
    
- Use call_user_func_array function in method calling 


## [v1.0.0](https://github.com/cherry-framework/router/releases/tag/v1.0.0 "v1.0.0") (2019-03-07)
#### The first stable version

- Package available on: `composer require cherry-framework/router`;
- Router path has placeholder support;
- Router has all HTTP Methods support;
- Router has caching feature;

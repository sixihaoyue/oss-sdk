OSS PHP Laravel SDK
======


## Installation

在`composer.json`中增加下面的配置

```json
    "repositories": [
        {
            "type": "git",
              "url": "https://github.com/sixihaoyue/oss-sdk.git"
        }
    ]
```
然后运行`composer require sixihaoyue/oss-sdk`安装它

如果运行上面的命令出现下面的错误：
```bash
[InvalidArgumentException]
  Could not find package laravel-admin-ext/multitenancy at any version for your minimum-stability (dev). Check the package spelling or your minimum-stability
```
这是由于composer的最小稳定性设置不满足，建议在composer.json里面将`minimum-stability`设置为`dev`，另外`prefer-stable`设置为true, 这样给你的应用安装其它package的时候，还是会倾向于安装稳定版本,
composer.json的修改如下
```json
{
    ...
    "minimum-stability": "dev",
    "prefer-stable": true,
    ...
}
```


然后运行下面的命令发布配置文件

```bash
php artisan vendor:publish --tag="oss"
```
运行完成之后会在生成另一个后台的配置文件`config/oss.php`

```
\OSS\SDK\Service::user()->get();


```

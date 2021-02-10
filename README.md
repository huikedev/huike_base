Huikedev
===============

Huikedev是一款基于`ThinkPHP6.0.*`的逻辑分层扩展。请注意，这并不是一个管理后台之类的产品，这是一种业务逻辑规范，通过牺牲很小的一部分性能来规范和提升业务逻辑的开发。

## 主要功能

+ [√] 逻辑分层
+ [√] 模块化管理
+ [√] 文件生成
+ [√] 模型生成
+ [√] 数据库迁移生成
+ [√] 路由生成
+ [√] 自定义异常接管
+ [√] 请求方法扩展
+ [√] 内置Token验证
+ [√] 中间件验证
+ [√] 第三方模块支持
+ [-] 角色权限
+ [-] 字段权限
+ [-] 多数据库支持
+ [-] 自定义路由Dispatcher
+ [-] 模型关联管理
+ [-] 模型获取器管理
+ [-] 简单CURD逻辑生成
+ [-] API文档生成
+ [-] 前端生成
+ [-] TS类型生成

## 安装
### 第一步：安装扩展
```bash
composer require huikedev/huike_base
```

### 第二步：执行安装命令
安装完成后，在命令行执行以下命令：

```bash
php think HuikeInstall
```
### 第三步：修改`composer.json`
如下找到项目根目录的`composer.json`，在`autoload.psr-4`中加入`"huike\\": "huike",`：
~~~
{
  "autoload": {
    "psr-4": {
      "app\\": "app",
      "huike\\": "huike"
    },
    "psr-0": {
      "": "extend/"
    }
  }
}
~~~
### 第四步：开启强制路由

本扩展依赖路由功能，所以请开启强制路由。找到`config/route.php`，将`url_route_must`的值修改为`true`。

## 文档地址

[后端文档](https://huike.dev)

## issues与交流

+ 您可以通过Github或Gitee的issues来反馈您的意见、建议或BUG
+ 您也可以通过Github或Gitee的Pull Requests来提交您的代码
+ QQ交流群：16117272

## 赞赏一下
<img alt="赞赏一下" src="https://huikedev-1255741738.cos.ap-shanghai.myqcloud.com/donate/donate.jpg" style="text-align: center;max-width: 750px;" />

## 鸣谢开源

+ [ThinkPHP](https://github.com/top-think/framework)
+ [Ant.Design](https://ant.design/)
+ [UmiJS](https://umijs.org/)


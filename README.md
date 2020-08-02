## hyperf-facade
Hyperf 门面组件，借鉴 Laravel Facade，不仅能节省依赖注入，还能在各种条件下拿到单例和容器

## 安装

#### 引入包
```
composer require liuchang103/hyperf-facade
```

## 使用

#### 创建门面
直接返回类名，将会在 Hyperf 的容器中直接获取

下面将创建一个 Redis 门面
```
class Redis extends \HyperfFacade\Facade
{
    protected static function getFacadeAccessor()
    {
        // 实例
        return \Hyperf\Redis\Redis::class;
    }
}
```

#### 门面返回实例
```
$redis = Redis::instance();

// 同等效果
$container = \Hyperf\Utils\ApplicationContext::getContainer();
$redis = $container->get(Hyperf\Redis\Redis::class);
```

#### 门面静态使用
可使用在 Facade 静态调用对应方法
```
Redis::get('key');

// 同等效果
$container = \Hyperf\Utils\ApplicationContext::getContainer();
$redis = $container->get(Hyperf\Redis\Redis::class);
$redis->get('key');
```

#### 获取DI容器
在条件苛刻的流程中，想要拿到容器得再引入 Hyperf 工具类，在已引入的 Facade 中，即可获取
```
$container = Redis::container();

// 同等效果
$container = \Hyperf\Utils\ApplicationContext::getContainer();
```

#### 门面别名
当 Facade 需要起别名时，可用注解方式
```
use HyperfFacade\Annotation\Alias;

/**
 * @Alias("Redis")
 */
class Redis extends \HyperfFacade\Facade
{
    protected static function getFacadeAccessor()
    {
        return \Hyperf\Redis\Redis::class;
    }
}
```
使用别名
```
\Redis::set('key', 'value');
```
注：Alias 注解可作用其它类


#### 短生命周期
以上门面代理的都会是长生命周期对象，如果想用门面使用短生命周期并且自动依赖注入，请看示例
```
class Test extends \HyperfFacade\Facade
{
    protected static function getFacadeAccessor()
    {
        // 实例
        return \App\Server\Test::class;
    }
    
    // 不使用单例模式
    protected static function singleton()
    {
        return false;
    }
}
```
使用门面时
```
$test = Test::instance();

// 同等
$test = make(\App\Server\Test::class);
```
同样使用静态调用
```
Test::foo();

// 同等
$test = make(\App\Server\Test::class);
$test->foo();
```
当类需要传入参数
```
class Test extends \HyperfFacade\Facade
{
    protected static function getFacadeAccessor()
    {
        // 实例
        return \App\Server\Test::class;
    }
    
    // 传入参数，当 singleton 为 true 时，不生效
    protected static function getResolveAccessor()
    {
        return [
            'config' => config('test')
        ];
    }
    
    // 不使用单例模式
    protected static function singleton()
    {
        return false;
    }
}

// 调用
Test::foo();

// 同等
$test = make(\App\Server\Test::class, ['config' => config('test')];
$test->foo();
```

## 长短生命周期测试
更好的区分应该在何时用单例模式

#### 服务类
```
class Test
{
    public $foo = 0;
    
    public function foo($foo = 0)
    {
        $this->foo += $foo;
        
        print_r($this->foo);
    }
}

```
#### 长生命周期
单例模式
```
// 门面
class Test extends \HyperfFacade\Facade
{
    protected static function getFacadeAccessor()
    {
        // 实例
        return \App\Server\Test::class;
    }
}

// 测试
Test::foo(1);  // 1 
Test::foo(1);  // 2
Test::foo(1);  // 3 
Test::foo(1);  // 4 
```
#### 短生命周期
使用门面时，同等 new 了一次
```
// 门面
class Test extends \HyperfFacade\Facade
{
    protected static function getFacadeAccessor()
    {
        // 实例
        return \App\Server\Test::class;
    }
    
    protected static function singleton()
    {
        return false;
    }
}

// 测试
Test::foo(1);  // 1 
Test::foo(1);  // 1
Test::foo(1);  // 1 
Test::foo(1);  // 1 
```

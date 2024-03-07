假设按[docker-compose安装](./doc/DEPLOY_TWO)

## 安装补充说明

### filebeat下载地址

https://www.elastic.co/cn/downloads/beats/filebeat
选择适合自己系统平台的

### 如果你在 Kibana 的 Discover 部分看不到你的 `jm-log` 索引，且Filebeat运行日志没什么异常。原因可能如下：

1. **索引模式缺失或不匹配**：
   
    * 确保你在 Kibana 中为 `jm-log` 索引创建了正确的索引模式。索引模式告诉 Kibana 哪些 Elasticsearch 索引包含它可以读取的数据。
    * 进入 Kibana 的 “Stack Management” > “Index Patterns”，检查是否存在针对 `jm-log` 的索引模式。如果没有，你需要创建一个新的索引模式。确保索引模式匹配你的索引名称，例如 `jm-log*`。
2. **时间字段问题**：
   * 当你创建索引模式时，Kibana 会要求你选择一个时间字段。这个字段用于 Kibana 的时间过滤器。如果你的日志数据中没有包含时间戳，或者时间戳格式不正确，那么在 Discover 中可能看不到数据。
    * 确保你的日志数据包含有效的时间戳字段，并在创建索引模式时选择此字段。

### 启动order-srv报错:DI Reflection Manager collecting class reflections failed。这个错误可忽略

```shel
➜  ~ docker exec -it hyperf /bin/bash
bash-5.1# cd /data/www/order-srv/
bash-5.1# php bin/hyperf.php start
[ERROR] DI Reflection Manager collecting class reflections failed.
File: /data/www/order-srv/vendor/dtm/dtm-client/src/Grpc/GrpcClient.php.
Exception: Class "Hyperf\GrpcClient\BaseClient" not found
[ERROR] DI Reflection Manager collecting class reflections failed.
File: /data/www/order-srv/vendor/dtm/dtm-client/src/Grpc/GrpcParser.php.
Exception: Class "Hyperf\Grpc\Parser" not found
[ERROR] DI Reflection Manager collecting class reflections failed.
File: /data/www/order-srv/vendor/dtm/dtm-client/src/Grpc/Message/DtmBranchRequest.php.
Exception: Class "Google\Protobuf\Internal\Message" not found
[ERROR] DI Reflection Manager collecting class reflections failed.
File: /data/www/order-srv/vendor/dtm/dtm-client/src/Grpc/Message/DtmGidReply.php.
Exception: Class "Google\Protobuf\Internal\Message" not found
[ERROR] DI Reflection Manager collecting class reflections failed.
File: /data/www/order-srv/vendor/dtm/dtm-client/src/Grpc/Message/DtmRequest.php.
Exception: Class "Google\Protobuf\Internal\Message" not found
[ERROR] DI Reflection Manager collecting class reflections failed.
File: /data/www/order-srv/vendor/dtm/dtm-client/src/Grpc/Message/DtmTransOptions.php.
Exception: Class "Google\Protobuf\Internal\Message" not found
[ERROR] DI Reflection Manager collecting class reflections failed.
File: /data/www/order-srv/vendor/dtm/dtm-client/src/Grpc/UniversalGrpcClient.php.
Exception: Class "Hyperf\GrpcClient\BaseClient" not found
[INFO] Process[config-center-fetcher.0] start.
[INFO] Worker#1 started.
[INFO] Worker#2 started.
[INFO] Worker#3 started.
[INFO] Worker#0 started.
[INFO] HTTP Server listening at 0.0.0.0:9505
[INFO] HTTP Server listening at 0.0.0.0:9502
```

出现这个错误是因为引入的dtm组件依赖的grpc组件缺失（且看第一行order-srv/vendor/dtm/dtm-client/src/Grpc/UniversalGrpcClient.php文件中extends BaseClient 的 Hyperf\GrpcClient\BaseClient找不到。IDE中点击类声明会跳自动转到order-srv/vendor/dtm/dtm-client/class_map/BaseClient.php，但这是不对的）。实际上代码中没用到grpc是不用关注的。不喜欢看到这个报错可以安装相关模块
```php
composer require hyperf/grpc-client
composer require hyperf/grpc-server
```

user-srv中我已经这么做了。然后就能看到user-srv/vendor/composer/autoload_classmap.php中多了：'Hyperf\\GrpcClient\\BaseClient' => $vendorDir . '/hyperf/grpc-client/src/BaseClient.php', BaseClient class就能找到了。

### 和原安装步骤差异

1. Mac 中从[docker-compose安装](./doc/DEPLOY_TWO)第3步开始即可

```
3.docker-compose up -d #-d 避免各个容器里的日志全往终端塞。可以去个容器内看日志
```

2. **启动服务**：启动hyper服务前已clone代码到宿主机上，docker hyperf:/data/www目录中少了jin-microservices这一层目录。

   ```shell
   ➜  ~ docker exec -it hyperf /bin/bash
   bash-5.1# cd /data/www/
   bash-5.1# cd /data/www/api-gateway/
   bash-5.1# php bin/hyperf.php start
   
   ➜  ~ docker exec -it hyperf /bin/bash
   bash-5.1# cd /data/www/order-srv/
   bash-5.1# php bin/hyperf.php start
   ...
   ```

   


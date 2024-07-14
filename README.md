# 題目解答
## 資料庫測驗
### 題目一
從 bnbs JOIN orders，加入判斷之後排序取前 10 即可
```
select bnbs.bnb_id as ID, bnbs.name as Name, SUM(orders.amount) as may_amount
from bnbs
join orders on bnbs.id = orders.bnb_id
where orders.created_at between '2023-05-01 00:00:00' and '2023-05-11 23:59:59' AND orders.currency = 'TWD'
group by bnbs.bnb_id
order by may_amount desc
limit 10
```

### 題目二
正常來說，若一句 SQL 很慢，第一步應該先從 Log 查看 Raw SQL 是否合理，如：是否正確使用到 Index、是否意外進行 Full-table scan ... 等等，若有這些狀況發生，應先簡化 SQL 語法。

當檢查認為 SQL 正確無誤之後，下一步應考慮資料方面的問題，這時我們可以有幾種作法：
1. 如果資料表很小，可能只有數 MB 或 GB 的資料量時，我們可以考慮增加 Index 來增加搜尋速度。
2. 如果資料量非常大，我們可以考慮建立 Materialized View 把一段 SQL 執行後的資料記錄下來，供未來查詢使用。
3. 若有 Real-time 顯示的需求，這時應考慮使用 NoSQL 輔助資料讀取，如：Redis 本身有許多好用的 function，如：zrange 可用於 Top 排行榜的需求。

最後，可以考慮該資料表的性質來切分新舊版，如：2020 年前的 Orders 可以改為 Read-only 的 table，只供查詢；2020 年後的資料則讀寫到新的 Table，新的 table 就可以考慮使用 Sharding 這類分割技巧存資料。 

## API 實作測驗
### SOLID 與設計模式
這次作業的 Scope 著重在 Single-responsibility principle (S) 和 Open–closed principle (O) 這兩點。
S 體現在 Validator、Controller、Service ... 等等各司其職，如：Controller 只做 I/O、Service 做商業邏輯。
O 的部分則是使用了 Dependency Injection 來避免程式碼過於耦合導致的改 A 壞 B 的問題，如：OrderController 內注入 OrderService。

除此之外，我把各種常數拆分出來成獨立的 Class，如：Currency 幣值就是一個 Enum，應獨立寫成一個檔案並在需要他的程式內 import 使用即可。

## Project Setup
1. 請確認 Local 有啟動 Docker Daemon。
2. 進入 Laradock 資料夾，在 Terminal 輸入 `cp .env.example .env`，修改這兩個參數 ```APP_CODE_PATH_HOST=../www```，```APP_CODE_PATH_CONTAINER=/var/www```
4. 進入 Laradock 資料夾，在 Terminal 輸入 `cp nginx\sites\laravel.conf.example nginx\sites\laravel.conf`，修改 `nginx\sites\laravel.conf` 如下:
```server_name homework.laravel.test;```，```root /var/www/laravel/public;```
3. 確認無誤後 Clone 該專案後進入 Laradock 資料夾內，執行 `docker-compose build mysql nginx workspace`，完成後執行 `docker-compose up -d mysql nginx workspace`。
4. 修改 localhost 指向，若是 Mac 請進入 `/etc/host` 檔案，在該檔案內新增 `127.0.0.1 homework.laravel.test`；若為 Windows，進入 `C:\Windows\System32\drivers\etc\hosts` 新增 `127.0.0.1 homework.laravel.test`。
5. 執行 `docker exec workspace bash` 進入 Container 內並執行 `composer create-project laravel/laravel --prefer-dist laravel`。
6. 完成後即可進入 ```http://homework.laravel.test```，看到預設畫面就完成。
7. 最後可透過 Postman 測試 POST API: ```http://homework.iming.test/api/orders```。
   

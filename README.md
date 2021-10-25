
# Rapid Sql

This package is Developed for Fast And Easy Data Operation in Mysql And Provide Pre Build Some Function For Execute Database Operations Fast and Rapidly.


## Installation

```bash
  composer require harshilkaneria/rapid-sql
```
    
## Usage/Examples

```php

// Import Our Package (Thank You !)
require __DIR__ . '/vendor/autoload.php';

use Rapid\Sql\RapidSql;


// Connect To The Database
$db = new RapidSql("localhost","root","","world");


// Our Function List

1.execute_query($query,$data);

2.getData($row,$table,$where,$group,$order,$data,$exit);
3.getData_R($row,$table,$where,$group,$order,$data,$exit);

4.getJoinData($row,$table,$join,$where,$group,$order,$data,$exit);
5.getJoinData_R($row,$table,$join,$where,$group,$order,$data,$exit);

6.insertData($table,$data,$exit);
7.insertData_R($table,$data,$exit);

8.insertMultiData($table,$column,$data,$exit);
9.insertMultiData_R($table,$column,$data,$exit);

10.updateData($table,$data,$where,$where_data,$exit);
11.updateData_R($table,$data,$where,$where_data,$exit);

12.deleteData($table,$where,$data,$exit);
13.deleteData_R($table,$where,$data,$exit);

// Don't Worry And Don't confuse Let's See All Example One By One With Proper Explanaion

$data = ['IND'];
$result  = $db->execute_query("select * from country where Code=?",$data);

$data = ['IND'];
$result  = $db->getData("*","country","Code=?","","",$data);
$result  = $db->getData_R("*","country","Code=?","","",$data);


$data = ['IND'];
$result = $db->getJoinData("ci.ID,ci.Name,con.Name as CountryName","country as con","INNER JOIN city as ci ON ci.CountryCode = con.Code ","con.Code=?","","ci.id ASC",$data);
$result = $db->getJoinData_R("ci.ID,ci.Name,con.Name as CountryName","country as con","INNER JOIN city as ci ON ci.CountryCode = con.Code ","con.Code=?","","ci.id ASC",$data);

$data = [
    "name"=>"Developer Harshil Kaneria",
    "type"=>2,
    "date"=>"2021-24-10 10:10:10"
];
$result = $db->insertData("test_dev",$data);
$result = $db->insertData_R("test_dev",$data);


$column = ["name","type","date"];
$data = [
    ["Harshil Kaneria 1",1,"2021-05-11 11:11:10"],
    ["Harshil Kaneria 2",2,"2022-05-12 12:12:10"],
    ["Harshil Kaneria 3",3,"2023-05-13 13:13:10"]
];
$result = $db->insertMultiData("test_dev",$column,$data);
$result = $db->insertMultiData_R("test_dev",$column,$data);


$data = [
    "name"=>"Harshil Kaneria",
    "type"=>1,
    "date"=>"2021-09-26 11:14:32"
];
$where_data = [1];
$result = $db->updateData("test_dev",$data,"id=?",$where_data);
$result = $db->updateData_R("test_dev",$data,"id=?",$where_data);

$data = [122];
$result = $db->deleteData("test_dev","id=?",$data);
$result = $db->deleteData_R("test_dev","id=?",$data);

// If You Debug You Query Then Add 1 as last Parameter in function and function Will be return your Query -- in result you will be see Query

// If You Want Autogenerate API Response Then Use _R Function you also directly return in your response and if you want database result then user normal function without _R

```

  
## Features

- Best and Advanced Pre Built Function
- Easy Database Connection
- Easy to Debug
- Minimal Write Code Syntax
- Automatic API Response
- Easy To Use
- Using Prepared Statement For Prevent SQL Injection
- And Many More
## License

[MIT](https://github.com/Harshil-Kaneria/rapid-sql-php/blob/main/LICENSE)

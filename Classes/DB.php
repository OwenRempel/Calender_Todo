<?php
        class Database{
        public static function connection(){
                $username="cal_admin";
                $password="ENTER_YOUR_PASSWORD_HERE";
                $host="127.0.0.1";
                $db="calenders";
                $pdo = new PDO("mysql:dbname=$db;host=$host", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            }
            public static function build($query){
                self::connection()->query($query);
            }
            public static function query($query, $params = array()){
                $stat = self::connection()->prepare($query);
                $stat->execute($params);
                if(explode(" ", $query)[0] == 'SELECT'){
                    $data =$stat->fetchAll(PDO::FETCH_ASSOC);
                    return $data;
                }else{
                    return 1;
                }
            }
            public static function get($query, $params = array()){
                $stat = self::connection()->prepare($query);
                $stat->execute($params);
                $data =$stat->fetchAll(PDO::FETCH_ASSOC);
                return $data;
             
            }
            public static function push($query, $params = array()){
                $stat = self::connection()->prepare($query);
                $stat->execute($params);
                return 1;
              
            }
        }